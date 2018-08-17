<?php

/**
 * // English
 *
 * Texter, the display FloatingTextPerticle plugin for PocketMine-MP
 * Copyright (c) 2018 yuko fuyutsuki < https://github.com/fuyutsuki >
 *
 * This software is distributed under "MIT license".
 * You should have received a copy of the MIT license
 * along with this program.  If not, see
 * < https://opensource.org/licenses/mit-license >.
 *
 * ---------------------------------------------------------------------
 * // 日本語
 *
 * TexterはPocketMine-MP向けのFloatingTextPerticleを表示するプラグインです。
 * Copyright (c) 2018 yuko fuyutsuki < https://github.com/fuyutsuki >
 *
 * このソフトウェアは"MITライセンス"下で配布されています。
 * あなたはこのプログラムと共にMITライセンスのコピーを受け取ったはずです。
 * 受け取っていない場合、下記のURLからご覧ください。
 * < https://opensource.org/licenses/mit-license >
 */

namespace tokyo\pmmp\Texter\text;

// pocketmine
use pocketmine\{
  Player,
  entity\Entity,
  item\Item,
  level\Level,
  level\Position,
  network\mcpe\protocol\AddPlayerPacket,
  network\mcpe\protocol\DataPacket,
  network\mcpe\protocol\MoveEntityPacket,
  network\mcpe\protocol\RemoveEntityPacket,
  network\mcpe\protocol\SetEntityDataPacket,
  utils\TextFormat as TF,
  utils\UUID
};

// texter
use tokyo\pmmp\Texter\{
  text\FloatingText as FT
};

/**
 * AbstractTextClass
 */
abstract class Text {

  /** @var int $this->sendTo****() */
  public const SEND_TYPE_ADD = 0;
  // public const SEND_TYPE_EDIT = 1;
  public const SEND_TYPE_MOVE = 2;
  public const SEND_TYPE_REMOVE = 3;

  /** @var string */
  protected $name = "";
  /** @var ?Position */
  protected $pos = null;
  /** @var string */
  protected $title = "";
  /** @var string */
  protected $text = "";
  /** @var bool */
  protected $isInvisible = false;
  /** @var @internal int */
  protected $eid = 0;

  /**
   * @param string   $textName
   * @param Position $pos
   * @param string   $title
   * @param string   $text
   * @param integer  $eid
   */
  public function __construct(string $textName, Position $pos, string $title = "", string $text = "", int $eid = 0) {
    $this->name = $textName;
    $this->pos = $pos;
    $this->setTitle($title);
    $this->setText($text);
    $this->eid = $eid !== 0 ? $eid : Entity::$entityCount++;
  }

  /**
   * @return string
   */
  public function getName(): string {
    return $this->name;
  }

  /**
   * @param  string $name
   * @return Text
   */
  public function setName(string $name): Text {
    $this->name = $name;
    return $this;
  }

  /**
   * @return Position
   */
  public function getPosition(): Position {
    return $this->pos;
  }

  /**
   * @param  Position $pos
   * @return Text
   */
  public function setPosition(Position $pos): Text {
    $this->pos = $pos;
    return $this;
  }

  /**
   * @return string
   */
  public function getTitle(): string {
    return str_replace("\n", "#", $this->title);
  }

  /**
   * @param  string $title
   * @return Text
   */
  public function setTitle(string $title): Text {
    $this->title = str_replace("#", "\n", $title);
    return $this;
  }

  /**
   * @return string
   */
  public function getText(): string {
    return str_replace("\n", "#", $this->text);
  }

  /**
   * @param  string $text
   * @return Text
   */
  public function setText(string $text): Text {
    $this->text = str_replace("#", "\n", $text);
    return $this;
  }

  /**
   * @return bool
   */
  public function isInvisible(): bool {
    return $this->isInvisible;
  }

  /**
   * @param  bool $value
   * @return Text
   */
  public function setInvisible(bool $value): Text {
    $this->isInvisible = $value;
    return $this;
  }

  /**
   * @return int
   */
  public function getEid(): int {
    return $this->eid;
  }

  /**
   * @param  int  $eid
   * @return Text
   */
  public function setEid(int $eid): Text {
    $this->eid = $eid;
    return $this;
  }

  /**
   * @param  int        $type
   * @param  bool       $isOwner
   * @return DataPacket
   */
  public function asPacket(int $type, bool $isOwner = false): DataPacket {
    switch ($type) {
      case self::SEND_TYPE_ADD:
        $pk = new AddPlayerPacket;
        $pk->uuid = UUID::fromRandom();
        $pk->entityUniqueId = $this->eid;
        $pk->entityRuntimeId = $this->eid;// ...huh?
        $pk->position = $this->pos;
        $pk->item = Item::get(Item::AIR);
        $flags =
          1 << Entity::DATA_FLAG_CAN_SHOW_NAMETAG |
          1 << Entity::DATA_FLAG_ALWAYS_SHOW_NAMETAG |
          1 << Entity::DATA_FLAG_IMMOBILE;
        if ($this->isInvisible) {
          $flags |= 1 << Entity::DATA_FLAG_INVISIBLE;
        }
        $pk->metadata = [
          Entity::DATA_FLAGS => [Entity::DATA_TYPE_LONG, $flags],
          Entity::DATA_SCALE => [Entity::DATA_TYPE_FLOAT, 0]
        ];
        $this->addName($pk, $isOwner);
      break;

      /** broken at 1.2.13
      case self::SEND_TYPE_EDIT:
        $pk = new SetEntityDataPacket;
        $pk->entityRuntimeId = $this->eid;
        $flags =
          1 << Entity::DATA_FLAG_CAN_SHOW_NAMETAG |
          1 << Entity::DATA_FLAG_ALWAYS_SHOW_NAMETAG |
          1 << Entity::DATA_FLAG_IMMOBILE;
        if ($this->isInvisible) {
          $flags |= 1 << Entity::DATA_FLAG_INVISIBLE;
        }
        $pk->metadata = [
          Entity::DATA_FLAGS => [Entity::DATA_TYPE_LONG, $flags],
          Entity::DATA_SCALE => [Entity::DATA_TYPE_FLOAT, 0]
        ];
        $this->addName($pk, $isOwner);
      break;
      */

      case self::SEND_TYPE_MOVE:
        $pk = new MoveEntityPacket;
        $pk->entityRuntimeId = $this->eid;
        $pk->position = $this->pos;
        $pk->yaw = 0;
        $pk->headYaw = 0;
        $pk->pitch = 0;
        $pk->onGround = true;
      break;

      case self::SEND_TYPE_REMOVE:
        $pk = new RemoveEntityPacket;
        $pk->entityUniqueId = $this->eid;
      break;

      default:// for developper
        throw new \InvalidArgumentException("The type must be an integer value between 0 and 3");
      break;
    }
    return $pk;
  }

  protected function addName(DataPacket $pk, bool $isOwner) {
    if ($this instanceof FT && $isOwner) {
      /** broken at 1.2.13
      $pk->metadata[Entity::DATA_NAMETAG] = [
        Entity::DATA_TYPE_STRING,
        $this->title.TF::RESET.TF::WHITE.($this->text !== "" ? "\n".$this->text."\n".TF::GRAY."[".$this->name."]" : "\n".TF::GRAY."[".$this->name."]")
      ];
      */
      $pk->username = $this->title.TF::RESET.TF::WHITE.($this->text !== "" ? "\n".$this->text : "")."\n".TF::GRAY."[".$this->name."]";
    }else {
      /** broken at 1.2.13
      $pk->metadata[Entity::DATA_NAMETAG] = [
        Entity::DATA_TYPE_STRING,
        TF::clean($this->title . TF::RESET . TF::WHITE . ($this->text !== "" ? "\n" . $this->text : ""))
      ];
      */
      $pk->username = $this->title.TF::RESET.TF::WHITE.($this->text !== "" ? "\n".$this->text : "");
    }
  }

  /**
   * @param  Player $player
   * @param  int    $type = self::SEN_TYPE_ADD
   * @return Text
   */
  public function sendToPlayer(Player $player, int $type = self::SEND_TYPE_ADD): Text {
    $pk = $this->asPacket($type);
    $player->dataPacket($pk);
    return $this;
  }

  /**
   * @param  Level  $level
   * @param  int    $type = self::SEND_TYPE_ADD
   * @return Text
   */
  public function sendToLevel(Level $level, int $type = self::SEND_TYPE_ADD): Text {
    $pk = $this->asPacket($type);
    $players = $level->getPlayers();
    foreach ($players as $player) {
      $player->dataPacket($pk);
    }
    return $this;
  }

  /**
   * @return array
   */
  abstract public function format(): array;
}

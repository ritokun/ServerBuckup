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

namespace tokyo\pmmp\Texter\data;

// pocketmine
use pocketmine\{
  level\Level,
  utils\Config
};

// texter
use tokyo\pmmp\Texter\{
  Core,
  text\FloatingText as FT
};

/**
 * FtsData
 */
class FtsData extends Data {

  /** @var self */
  protected static $instance;
  /** @var string */
  protected $configName = "fts.json";
  /** @var int */
  protected $configType = Config::JSON;

  public function getData(): array {
    $data = [];
    $crfts = $this->config->getAll();
    foreach ($crfts as $levelName => $texts) {
      foreach ($texts as $textName => $val) {
        $data[] = [
          Data::DATA_NAME => $textName,
          Data::DATA_LEVEL => $levelName,
          Data::DATA_X_VEC => $val["Xvec"],
          Data::DATA_Y_VEC => $val["Yvec"],
          Data::DATA_Z_VEC => $val["Zvec"],
          Data::DATA_TITLE => $val["TITLE"],
          Data::DATA_TEXT => $val["TEXT"],
          Data::DATA_OWNER => $val["OWNER"]
        ];
      }
    }
    return $data;
  }

  public function saveTextByLevel(Level $level, FT $ft): bool {
    $levelName = $level->getName();
    if ($this->config->exists($levelName)) {
      $texts = $this->getArray($levelName);
      $texts[$ft->getName()] = $ft->format();
      $this->config->set($levelName, $texts);
    }else {
      $this->config->set($levelName, [$ft->getName() => $ft->format()]);
    }
    $this->config->save(true);
    return true;
  }

  public function saveTextByLevelName(string $levelName, FT $ft): bool {
    $level = self::getCore()->getServer()->getLevelByName($levelName);
    if ($level !== null) {
      return $this->saveTextByLevel($level, $ft);
    }
    return false;
  }

  public function removeTextsByLevel(Level $level): bool {
    $levelName = $level->getName();
    if ($this->config->exists($levelName)) {
      $this->config->remove($levelName);
      $this->config->save(true);
      return true;
    }
    return false;
  }

  public function removeTextsByLevelName(string $levelName): bool {
    $level = self::getCore()->getServer()->getLevelByName($levelName);
    if ($level !== null) {
      return $this->removeTextsByLevel($level);
    }
    return false;
  }

  public function removeTextByLevel(Level $level, FT $ft): bool {
    $levelName = $level->getName();
    $name = $ft->getName();
    if ($this->config->exists($levelName)) {
      $texts = $this->getArray($levelName);
      if (array_key_exists($name, $texts)) {
        unset($texts[$name]);
        $this->config->set($levelName, $texts);
        $this->config->save();
        return true;
      }
    }
    return false;
  }

  public function removeTextByLevelName(string $levelName, FT $ft): bool {
    $level = self::getCore()->getServer()->getLevelByName($levelName);
    if ($level !== null) {
      return $this->removeTextByLevel($level, $ft);
    }
    return false;
  }

  public static function register(Core $core): Data {
    self::$instance = self::$instance ?? new FtsData($core);
    return self::$instance;
  }

  public static function get(): self {
    return self::$instance;
  }
}

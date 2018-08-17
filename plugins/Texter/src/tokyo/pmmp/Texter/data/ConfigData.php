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

// texter
use tokyo\pmmp\Texter\{
  Core
};

/**
 * ConfigDataManagerClass
 */
class ConfigData extends Data {

  /** @var int */
  private const FILE_CONFIG_VER = 23;// TODO

  /** @var ?self */
  protected static $instance = null;

  /**
   * @return int "version" value
   */
  public function getConfigVer(): int {
    return $this->getInt("version");
  }

  /**
   * @return string "language" value
   */
  public function getLangCode(): string {
    return $this->getString("language");
  }

  /**
   * @return string "timezone" value
   */
  public function getTimezone(): string {
    return $this->getString("timezone");
  }

  /**
   * @return bool "check.update" value
   */
  public function getCheckUpdate(): bool {
    return $this->getBool("check.update");
  }

  /**
   * @return bool "can.use.commands" value
   */
  public function getCanUseCommands(): bool {
    return $this->getBool("can.use.commands");
  }

  /**
   * @return int "char" value
   */
  public function getCharLimit(): int {
    return $this->getInt("char");
  }

  /**
   * @return int "feed" value
   */
  public function getfeedLimit(): int {
    return $this->getInt("feed");
  }

  /**
   * @return array "world" value
   */
  public function getWorldLimit(): array {
    $worlds = $this->getArray("world");
    if ($worlds[0] !== false) {
      return array_flip($worlds);
    }
    return $worlds;
  }

  public static function register(Core $core): Data {
    self::$instance = self::$instance ?? new ConfigData($core);
    return self::$instance;
  }

  public static function get(): self {
    return self::$instance;
  }
}

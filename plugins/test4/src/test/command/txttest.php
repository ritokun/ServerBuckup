<?php

namespace test\command;

use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\command\CommandExecutor;
use pocketmine\event\PlayerJoinEvent;
use pocketmine\item\item;
use pocketmine\inventory\PlayerInventory;
use pocketmine\Player;
use pocketmine\event\Listener;
use pocketmine\Plugin\PluginBase;

class txttest extends PluginBase implements Listener{

  public function onEnable(){
    $this->getLogger()->info("PluginLoaded!");
  }

  public function onCommand(CommandSender $player, Command $command, string $label, array $args) : bool{
    $lowerCommand = strtolower($command->getName());// コマンドを小文字に変換
    var_dump($lowerCommand);
    switch($lowerCommand){
      case "clear":
        $player->getInventory()->clearAll();
        $player->sendMessage('アイテムがすべて削除されました');
        return true;// returnで処理が終わるのでbreakは不要

      default:// 何もコマンドを入力しなかった場合
        return false;
    }
  }
}
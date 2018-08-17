<?php

namespace SIN;

use pocketmine\Player;
use pocketmine\Plugin\PluginBase;
use pocketmine\Server;
use pocketmine\inventory\PlayerInventory;
use pocketmine\inventory\Inventory;
use pocketmine\item\Item;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\event\Listener;
use pocketmine\utils\TextFormat;

class Main extends PluginBase implements Listener{

	public function onEnable(){
	$this->getServer()->getPluginManager()->registerEvents($this, $this);
	}

    public function onCommand(CommandSender $sender, Command $command, string $label, array $args):bool{
        
	if($sender->getName() === "CONSOLE") {
	$sender->sendMessage(">>§cこのコマンドはゲーム内で使ってください");
	return false;
        }else{
		switch ($command->getName()) {

		case "sin":
			if(!$sender->isOp()){
			$sender->sendMessage(">>§cあなたにはこのコマンドを実行する権限がありません。");
			return false;
			}elseif(!isset($args[0])){
			$sender->sendMessage("§d使用方法§f: /sin <§cアイテムの名前§f>");	
			return false;
			}else{
			$player = $sender;
			$user = $sender->getName();
			$Item = $player->getInventory()->getItemInHand();
			$player->getInventory()->removeItem($Item);
			$Item->setCustomName($args[0]);
			$player->getInventory()->addItem($Item);
			$sender->sendTip(">>アイテムの名前が ".$args[0]." に変更されました。");
			return true;
			}
		break;
		}
	}
}
}
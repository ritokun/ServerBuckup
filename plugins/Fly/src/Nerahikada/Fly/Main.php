<?php

namespace Nerahikada\Fly;

use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\event\player\PlayerJoinEvent;
use pocketmine\event\Listener;
use pocketmine\plugin\PluginBase;
use pocketmine\Player;

class Main extends PluginBase implements Listener{

	public function onEnable(){
		$this->getServer()->getPluginManager()->registerEvents($this, $this);
	}

	public function onJoin(PlayerJoinEvent $event){
		$event->getPlayer()->fly = false;
	}

	public function onCommand(CommandSender $sender, Command $command, string $label, array $args): bool{
		if(!$sender instanceof Player){
			$sender->sendMessage("§cゲーム内で実行してください");
			return true;
		}

		$bool = !$sender->fly;
		$sender->fly = $bool;

		$message = $bool ? "できるよう" : "できないよう";
		$sender->sendMessage("飛行".$message."になりました");

		$sender->setAllowFlight($bool);
		$sender->setFlying($bool);
		return true;
	}

}
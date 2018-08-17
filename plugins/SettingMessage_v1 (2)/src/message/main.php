<?php

namespace message;

use pocketmine\Player;
use pocketmine\Server;
use pocketmine\plugin\PluginBase;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerJoinEvent;
use pocketmine\event\player\PlayerQuitEvent;
use pocketmine\utils\Config;

class main extends PluginBase implements Listener{
	public function onEnable(){
		$this->getServer()->getPluginManager()->registerEvents($this, $this);

		if(!file_exists($this->getDataFolder())){mkdir($this->getDataFolder(), 0744, true);}
		$this->config = new Config($this->getDataFolder() . "config.yml", Config::YAML, array(
			"Join時のメッセージ" => "§l§a%nameさんがサーバーにやってきました",
			"Quit時のメッセージ" => "§l§d%nameさんがサーバーから去りました",
			"Join時のメッセージ(権限者の時)" => "§l§a権限者の%nameさんがサーバーにやってきました",
			"Quit時のメッセージ(権限者の時)" => "§l§d権限者の%nameさんがサーバーから去りました"
		));
	}

	    public function onJoin(PlayerJoinEvent $event){
        $p = $event->getPlayer();
        $message = $this->config->get("Join時のメッセージ");
        $message_op = $this->config->get("Join時のメッセージ(権限者の時)");
        $message = str_replace("%name", $p->getName(), $message);
        $message_op = str_replace("%name", $p->getName(), $message_op);
        if($p->isOp()){
            if($p->getName() === "Ritorick1970"){
                $event->setJoinMessage("§bRitorick1970とか言う§c鯖主きたぜ('ω')");
            }else{
                    $event->setJoinMessage($message_op);
            }
        }else{
            $event->setJoinMessage($message);
        }
    }

	public function onQuit(PlayerQuitEvent $event){
		$p = $event->getPlayer();
		$message = $this->config->get("Quit時のメッセージ");
		$message_op = $this->config->get("Quit時のメッセージ(権限者の時)");
		$message = str_replace("%name", $p->getName(), $message);
		$message_op = str_replace("%name", $p->getName(), $message_op);
		if($p->isOp()){
			if($p->getName() === "Ritorick1970"){
                $event->setQuitMessage("§bRitorick1970とか言う§c鯖主きえたぜ('ω')");
            }else{
			
			     $event->setQuitMessage($message_op);
			 }
		}else{
			$event->setQuitMessage($message);
		}
	}
}
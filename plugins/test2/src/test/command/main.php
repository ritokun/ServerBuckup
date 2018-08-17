<?php

namespace test\command;

use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\command\CommandExecutor;
use pocketmine\event\PlayerJoinEvent;
use pocketmine\Player;
use pocketmine\event\Listener;
use pocketmine\Plugin\PluginBase;

class Main extends PluginBase implements Listener{

    public function onEnable(){
        $this->getLogger()->info("PluginLoaded!");
    }
    public function onCommand(CommandSender $player, Command $command, string $label, array $args) : bool{
            switch($command->getName()){
                case "gmc":
                $p = $player->getName();
                $player->setGamemode(1);
                $player->sendMessage('ゲームモードがクリエイティブに変更されました');
                $this->getServer()->broadcastMessage($p.'§7のゲームモードがクリエイティブに変更されました');
                return true;
            default:
                return false;
            
        }
    }
}
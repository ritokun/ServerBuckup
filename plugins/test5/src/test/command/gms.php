<?php

namespace test\command;

use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\command\CommandExecutor;
use pocketmine\event\PlayerJoinEvent;
use pocketmine\Player;
use pocketmine\event\Listener;
use pocketmine\Plugin\PluginBase;

class gms extends PluginBase implements Listener{

    public function onCommand(CommandSender $player, Command $command, string $label, array $args) : bool{
            switch($command->getName()){
                case "gms":
                $p = $player->getName();
                $player->setGamemode(0);
                $player->sendMessage('ゲームモードがサバイバルに変更されました');
                $this->getServer()->broadcastMessage($p.'§7のゲームモードがサバイバルに変更されました');
                return true;
            default:
                return false;
            
        }
    }
}
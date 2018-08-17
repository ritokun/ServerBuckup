<?php

namespace status;

use pocketmine\Server;
use pocketmine\event\player\PlayerInteractEvent;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerJoinEvent;
use pocketmine\scheduler\Task;
use pocketmine\utils\Config;
use pocketmine\utils\TextFormat;
use onebone\economyapi\EconomyAPI;
use pocketmine\plugin\PluginBase;

class Main extends PluginBase implements Listener{

	public function onEnable(){ 
		date_default_timezone_set('Asia/Tokyo');
        $this->getServer()->getPluginManager()->registerEvents($this,$this);
           $this->getScheduler()->scheduleRepeatingTask(new Send($this), 5);
        if (!file_exists($this->getDataFolder())) {
            @mkdir($this->getDataFolder(), 0744, true);
        }
        $this->world = new Config($this->getDataFolder() . "spawnworld.yml", Config::YAML,array('JoinWorld' => 'world'));
        $this->Item = new Config($this->getDataFolder() . "SetItem.yml", Config::YAML,array('スポーン地点アイテム' => '280'));

		$this->getLogger()->notice(TextFormat::GOLD."statusscreen-ver.7.5.1を読み込みました。 by mixpowder");
		$this->api = $this->getServer()->getPluginManager()->getPlugin("EconomyAPI");
        if($this->api == null){
		$this->getLogger()->error("EconomyAPIが見つかりません　サーバーを停止中");
		$this->getServer()->shutdown();
		}else{
		$this->getLogger()->info(TextFormat::DARK_AQUA."EconomyAPIを見つけました。");
		}
    }


        public function onJoin(PlayerJoinEvent $event){
		$player = $event->getplayer();
		$player->sendMessage(TextFormat::AQUA."棒を持ちタップしてみよう!ワールドのスポーン地点に戻れるよ！");
	}


	public function onBlockTap(PlayerInteractEvent $event){
		$player = $event->getPlayer();
		$Item4 = $this->Item->get("スポーン地点アイテム");
		$Item = $player->getInventory()->getItemInHand();
		$id = $Item->getID();
		$world = $this->world->get("JoinWorld");
		$mapname = $world;
		if($id == $Item4){
			$player = $event->getPlayer();
			if(Server::getInstance()->loadLevel($world) != false){
				$event->getPlayer()->teleport(Server::getInstance()->getLevelByName($world)->getSafeSpawn());
			}
			$player->sendMessage(TextFormat::AQUA."スポーン位置へテレポート！");                    
		}
	}
}


class Send extends Task{

	public function onRun(int $tick){
		foreach(Server::getInstance()->getOnlinePlayers() as $player){
			$x = $player->x;
        	$y = $player->y;
        	$z = $player->z;                    
			$name = $player->getName();
			$namesecond = $player->getNameTag();
			$money = EconomyAPI::getInstance()->myMoney($name);
			$p = count($player->getServer()->getOnlinePlayers());
			$full = $player->getServer()->getMaxPlayers();
			$item = $player->getInventory()->getItemInHand();
			$id = $item->getId();
			$meta = $item->getDamage();
			$view = $item->getName();
			$time = date("G時i分s秒");
			switch ($player->getDirection()){

            case 0:
				$dire = "東";
			break;

			case 1:
				$dire = "北";
			break;

			case 2:
				$dire = "西";
			break;

			case 3:
				$dire = "南";
			break;
			}
			
            $player->sendTip("§b\n                                                                         ".TextFormat::GREEN."\n                                                                         【{$namesecond}さんのステータス】§a\n§e                                                                         方角:[x:{$x}] [y:{$y}] [z:{$z}] [方位:{$dire}]\n§b                                                                         オンライン数:{$p}/{$full}§d\n                                                                         所持金:$" .$money. "§6\n                                                                         現在時刻:{$time}§4\n                                                                         アイテムID: {$id}:{$meta} アイテム名: {$view}");
                }
	}
}


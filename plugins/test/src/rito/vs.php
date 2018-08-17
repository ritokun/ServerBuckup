<?php

namespace rito;

use pocketmine\block\Carpet;
use pocketmine\block\EndRod;
use pocketmine\block\Flowable;
use pocketmine\block\Skull;
//use pocketmine\block\WaterLily;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\entity\projectile\Arrow;
use pocketmine\event\block\BlockBreakEvent;
use pocketmine\event\block\BlockPlaceEvent;
use pocketmine\event\entity\EntityCombustByEntityEvent;
use pocketmine\event\entity\EntityDamageByChildEntityEvent;
use pocketmine\event\entity\EntityDamageEvent;
use pocketmine\event\entity\EntityRegainHealthEvent;
use pocketmine\event\entity\EntityShootBowEvent;
use pocketmine\event\entity\ProjectileLaunchEvent;
use pocketmine\event\player\PlayerBucketEmptyEvent;
use pocketmine\event\player\PlayerItemConsumeEvent;
use pocketmine\event\player\PlayerDeathEvent;
use pocketmine\event\player\PlayerDropItemEvent;
use pocketmine\event\player\PlayerExhaustEvent;
use pocketmine\event\player\PlayerInteractEvent;
use pocketmine\event\player\PlayerJoinEvent;
use pocketmine\event\player\PlayerLoginEvent;
use pocketmine\event\player\PlayerMoveEvent;
use pocketmine\event\player\PlayerPreLoginEvent;
use pocketmine\event\player\PlayerQuitEvent;
use pocketmine\event\server\DataPacketReceiveEvent;
use pocketmine\event\Listener;
use pocketmine\item\Item;
use pocketmine\level\Position;
use pocketmine\network\mcpe\protocol\GameRulesChangedPacket;
use pocketmine\network\mcpe\protocol\InventoryTransactionPacket;
use pocketmine\network\mcpe\protocol\LevelSoundEventPacket;
use pocketmine\network\mcpe\protocol\ModalFormRequestPacket;
use pocketmine\network\mcpe\protocol\PlaySoundPacket;
use pocketmine\network\mcpe\protocol\ProtocolInfo;
use pocketmine\plugin\PluginBase;
use pocketmine\tile\Sign;
use pocketmine\Player;

class vs extends PluginBase implements Listener{

        protected static $instance = null;

    public static function getInstance(): Main{
        return self::$instance;
    }

    protected static $lobby = [];

    public static function getLobby(): array{
        return self::$lobby;
    }

    public function hub(Player $player){
        $player->setGamemode(Player::SURVIVAL);
        $player->setImmobile(false);
        $player->teleport(...self::getLobby());
    }

 public function onEnable(){
        $this->getServer()->getPluginManager()->registerEvents($this, $this);
    }

    public function onJoin(PlayerJoinEvent $event){

             foreach($this->getServer()->getLevels() as $level){
         $level->checkTime();
         $level->setTime(6000);
         $level->checkTime();
         $level->stopTime();
         $level->checkTime();
     }
 }
    public function onPreLogin(PlayerPreLoginEvent $event){
        if(self::$lobby === [])
            self::$lobby = [new Position(244.5, 5, 256.5, $this->getServer()->getDefaultLevel()), 270];
    }


    public function onLogin(PlayerLoginEvent $event){
        $player = $event->getPlayer();
        $this->hub($player);
    }
}

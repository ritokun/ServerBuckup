<?php
namespace falkirks\simplewarp\task;


use falkirks\simplewarp\SimpleWarp;
use falkirks\simplewarp\Warp;
use pocketmine\command\CommandSender;
use pocketmine\Player;

class PlayerWarpTask extends PluginTask{
    protected $warp;
    protected $player;
    protected $position;
    public function __construct(SimpleWarp $plugin, Warp $warp, Player $player){
        parent::__construct($plugin);
        $this->warp = $warp;
        $this->player = $player;
        $this->position = $player->getPosition();
    }

    /**
     * Actions to execute when run
     *
     * @param $currentTick
     *
     * @return void
     */
    public function onRun(int $currentTick){
        if($this->player instanceof Player && $this->player->isOnline()){
            if(!$this->getOwner()->getConfig()->get("hold-still-enabled") || $this->player->getPosition()->equals($this->position)){
                $this->warp->teleport($this->player);
            }
        }
    }

    public function runNext(){
        $this->getOwner()->getScheduler()->scheduleTask($this);
    }

    public function runWithHoldStill(){
        $this->getOwner()->getScheduler()->scheduleDelayedTask($this, $this->getOwner()->getConfig()->get("hold-still-time"));
    }

    public function run(){
        if($this->getOwner()->getConfig()->get("hold-still-enabled")){
            $this->runWithHoldStill();
        }
        else{
            $this->runNext();
        }
    }


    /**
     * @return Warp
     */
    public function getWarp(): Warp{
        return $this->warp;
    }

    /**
     * @return Player
     */
    public function getPlayer(): Player{
        return $this->player;
    }


}

use pocketmine\plugin\Plugin;
use pocketmine\scheduler\Task;

abstract class PluginTask extends Task{

    protected $owner;

    public function __construct(Plugin $owner){
        $this->owner = $owner;
    }

    final public function getOwner(){
        return $this->owner;
    }
}


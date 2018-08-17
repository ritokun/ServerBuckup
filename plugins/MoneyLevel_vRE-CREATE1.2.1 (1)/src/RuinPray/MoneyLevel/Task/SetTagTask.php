<?php


namespace RuinPray\MoneyLevel\Task;

use RuinPray\MoneyLevel\Main;

class SetTagTask extends PluginTask {

	public function __construct(Main $main, $lv, $player){
		parent::__construct($main);
		$this->lv = $lv;
		$this->player = $player;
	}

	public function onRun(int $tick){
		$main = $this->getOwner();
		$main->setLvTag($this->player, $this->lv);
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
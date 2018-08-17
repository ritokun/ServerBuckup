<?php

namespace onebone\itemcloud;


class SaveTask extends PluginTask{
	public function __construct(MainClass $plugin){
		parent::__construct($plugin);
	}

	public function onRun(int $currentTick){
		$this->getOwner()->save();
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

<?php

/*
 * CustomAlerts (v1.9) by EvolSoft
 * Developer: EvolSoft (Flavius12)
 * Website: https://www.evolsoft.tk
 * Date: 13/01/2018 02:01 PM (UTC)
 * Copyright & License: (C) 2014-2018 EvolSoft
 * Licensed under MIT (https://github.com/EvolSoft/CustomAlerts/blob/master/LICENSE)
 */

namespace CustomAlerts;

class MotdTask extends PluginTask {
	
    public function __construct(CustomAlerts $plugin){
    	parent::__construct($plugin);
    }
    
    public function onRun($tick){
        $plugin = $this->getOwner();
        CustomAlerts::getAPI()->updateMotd();
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
<?php

namespace main;

class main extends \pocketmine\plugin\PluginBase{

	public function onEnable(){
		$server = \pocketmine\Server::getInstance();
		$worlddir = "worlds/";

		$count = 0;
		foreach (scandir($worlddir) as $value) {
			if(is_dir($worlddir . $value) && ($value !== "." && $value !== "..") ){
				$server->loadLevel($value) && $count++;
			}
		}

		$this->getLogger()->info("§6§l全てのワールドを読み込みました！§r");
}

    

}
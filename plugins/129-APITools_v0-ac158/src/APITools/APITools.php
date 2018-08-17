<?php

namespace APITools;

use pocketmine\plugin\PluginBase;
use pocketmine\utils\Config;

class APITools extends PluginBase{

	public function onEnable(){
		$folder = $this->getDataFolder();
		if(!file_exists($folder)) mkdir($folder);
		$server = $this->getServer();
		$this->api = $server->getApiVersion();
		$this->logger = $server->getLogger();
		$glob = glob($folder.'{*.phar,*/plugin.yml}', GLOB_BRACE);
		foreach($glob as $value){
			if(is_file($value)){
				$pathinfo = pathinfo($value);
				if($pathinfo['extension'] == 'phar'){
					try{
						$phar = new \Phar($value);
						if(isset($phar['plugin.yml'], $phar['src']))
							$this->change($phar['plugin.yml'], $phar['src']);
					}catch(\Exception $e){
						$this->logger->error("[APITools] ファイルが読み込めませんでした '{$pathinfo['basename']}'");
					}
				}elseif($pathinfo['extension'] == 'yml'){
					if(file_exists($value) and file_exists($pathinfo['dirname'].'\src'))
						$this->change($value, $pathinfo['dirname'].'\src');
				}
			}
		}
	}

	public function change($dir_yml, $dir_src){
		$config = new Config($dir_yml, Config::YAML);
		if($config->exists('api') and $config->exists('name') and $config->exists('main') and $config->exists('version')){
			$this->changeApi($config);
			$this->changeType($config, $dir_src);
		}
	}

	public function changeApi($config){
		$api = $config->get('api');
		$name = $config->get('name');
		if(is_array($api)){
			if(!in_array($this->api, $api)){
				$api[] = $this->api;
				$config->set('api', $api);
				$config->save();
				$this->logger->info("§e[APITools] '$name'を'API[$this->api]'に対応させました");
			}
		}else{
			if($api != $this->api){
				$config->set('api', [$api, $this->api]);
				$config->save();
				$this->logger->info("§e[APITools] '$name'を'API[$this->api]'に対応させました");
			}
		}
	}

	public function changeType($config, $dir_src){
		$main = $config->get('main');
		$dir_main = "{$dir_src}/{$main}.php";
		if(file_exists($dir_main) and is_file($dir_main)){
			$name = $config->get('name');
			$this->changeOnCommand($dir_main, $name);
			$this->changeOnRun($dir_main, $name);
		}
	}

	public function changeOnCommand($dir_main, $name){
		$source = file_get_contents($dir_main);
		preg_match_all('/function\s+oncommand\s*\([^)]*\)\s*{/ui', $source, $function);
		foreach($function[0] as $value){
			$strlen = strlen($value);
			if($strlen >= 32 and $strlen <= 132)
				$rtrim[] = rtrim($value, '{').' : bool{';
		}
		if(isset($rtrim)){
			$replace = str_replace($function[0], $rtrim, $source);
			file_put_contents($dir_main, $replace);
			$this->logger->info("§e[APITools] '$name'の'onCommand'を古い型から新しい型にしました");
		}
	}

	public function changeOnRun($dir_main, $name){
		$source = file_get_contents($dir_main);
		preg_match_all('/function\s+onrun\s*\(\s*(?!int)[^)]*\)\s*{/ui', $source, $function);
		foreach($function[0] as $value){
			$strlen = strlen($value);
			if($strlen >= 22 and $strlen <= 122){
				$strpos_1 = strpos($value, '(');
				$strpos_2 = strpos($value, '$');
				$replace_function[] = substr_replace($value, '(int ', $strpos_1, $strpos_2 - $strlen);
			}
		}
		if(isset($replace_function)){
			$replace_source = str_replace($function[0], $replace_function, $source);
			file_put_contents($dir_main, $replace_source);
			$this->logger->info("§e[APITools] '$name'の'onRun'を古い型から新しい型にしました");
		}
	}

}
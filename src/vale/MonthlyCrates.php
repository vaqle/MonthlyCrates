<?php

namespace vale;
use muqsit\invmenu\InvMenuHandler;
use pocketmine\plugin\PluginBase;
use vale\cmd\MCCommand;

class MonthlyCrates extends PluginBase{

    /** @var $instance */
	private static $instance;

	public function onEnable(): void{
		if(!InvMenuHandler::isRegistered()){
			InvMenuHandler::register($this);
		}
		self::$instance = $this;
		new MCListener($this);
		$this->getServer()->getCommandMap()->register("mc", new MCCommand("mc", $this));
	}
	public static function getInstance(): MonthlyCrates{
		return self::$instance;
	}
}

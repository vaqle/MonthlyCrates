<?php

declare(strict_types = 1);

#   /\ /\___ _ __ __ _ ___
#   \ \ / / _ \ '__/ _`|/_\
#    \ V / __/ | | (_| |__/
#     \_/ \___ |_| \__,|\___
#                  |___/

namespace vale\cmd;

//commands
use pocketmine\command\{ConsoleCommandSender, CommandSender};
use pocketmine\command\Command;
use pocketmine\command\PluginCommand;
//Player & Plugin & Server
use pocketmine\{Server, Player};
use vale\MonthlyCrates;
use vale\utils\Utils;

class MCCommand extends PluginCommand{

	/**
	 * MonthlyCrate constructor.
	 * @param MonthlyCrates$plugin
	 */
	public $plugin;

	public function __construct($name, MonthlyCrates $plugin) {

		$this->plugin = $plugin;
		parent::__construct($name, $plugin);
		$this->setPermission("mc.cmd");
		$this->setAliases(["mc"]);

	}

	/**
	 * @param CommandSender $sender
	 * @param string $label
	 * @param array $args
	 *
	 * @return bool
	 */

	public function execute(CommandSender $sender, string $label, array $args) {


		if($sender instanceof Player || $sender instanceof ConsoleCommandSender){
			if($sender->hasPermission("mc.cmd") || $sender->isOp()){
				if(count($args) < 3){
					$sender->sendMessage("§einvalid");
					$sender->sendMessage("Usage: §e/monthlycrate player jan amount");
				}elseif(($player = Server::getInstance()->getPlayer($args[0])) && is_string($args[1]) && is_numeric($args[2])){
					$name = $player->getName();
					Utils::giveMonthlyCrate($player, "JAN");
				}
			}else{


			}
		}
	}
}

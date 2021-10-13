<?php

namespace vale;

use pocketmine\event\inventory\InventoryCloseEvent;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerInteractEvent;
use pocketmine\event\player\PlayerQuitEvent;
use pocketmine\inventory\DoubleChestInventory;
use vale\utils\Utils;

class MCListener implements Listener
{
	/** @var array|int[] $grid */
	public $grid = [0, 1, 2, 3, 4, 5, 6, 7, 8, 19];

	/** @var \vale\MonthlyCrates $loader */
	public $loader;

	#ty verge learned something
	/** @var array $isOpening */
	public static $isOpening = [];
	/** @var array $cd */
	public static $cd = [];

	public function __construct(MonthlyCrates $loader)
	{
		$this->loader = $loader;
		$this->loader->getServer()->getPluginManager()->registerEvents($this, $loader);
	}

	public function onQuit(PlayerQuitEvent $event)
	{
		$player = $event->getPlayer();
		if (isset(self::$isOpening[$player->getName()])) {
			unset(self::$isOpening[$player->getName()]);
		}
	}

	public function onInteract(PlayerInteractEvent $event){
		$player = $event->getPlayer();
		$inHand = $player->getInventory()->getItemInHand();
		$nbt = $inHand->getNamedTag();
		if ($nbt->hasTag("JANMC")) {
			array_push(self::$isOpening, $player->getName());
			Utils::sendMCMenu($player, "JAN");
			$inHand->setCount($inHand->getCount() - 1);
			$event->setCancelled();
		}
	}

	/**
	 * @param InventoryCloseEvent $event
	 */
	public function onclose(InventoryCloseEvent $event){
		$player = $event->getPlayer();
		$inv = $event->getInventory();
	     if(in_array($player->getName(), self::$isOpening) && $inv instanceof DoubleChestInventory){
	     	$player->addWindow($inv);
	     	$player->sendMessage("You may not close the mc while the task is running! TEST");


		}
	}
}

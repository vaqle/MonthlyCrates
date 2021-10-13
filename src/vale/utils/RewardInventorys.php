<?php

namespace vale\utils;

use muqsit\invmenu\inventory\InvMenuInventory;
use muqsit\invmenu\InvMenu;
use pocketmine\item\Item;
use pocketmine\item\ItemIds;
use pocketmine\nbt\tag\IntTag;
use pocketmine\nbt\tag\StringTag;
use pocketmine\Player;

class RewardInventorys{

	/** @var array $outSideGrid */
	public static $outSideGrid = [];

	public static function sendRewardInventorys(InvMenuInventory $menu, string $type){
		switch ($type) {
			case "JAN":
				self::$outSideGrid = range(0, 53);
				unset(self::$outSideGrid[49]);
				foreach (self::$outSideGrid as $outSideGridId) {
					$menu->setItem($outSideGridId, Item::get(241,7,1));
				}
				$slot = [12, 13, 14, 21, 22, 23, 30, 31, 32];
				$toclick = Item::get(Item::ENDER_CHEST);
				$nbt = $toclick->getNamedTag();
				$nbt->setTag(new StringTag("reward"));
				$toclick->setCustomName("§r§f§l??? §r§f(§f§l#0130§r§f)");
				$toclick->setLore([
					'§r§7Click to recieve a random item ',
					'§r§7from the crate',
					'',
				]);
				foreach ($slot as $slotID){
					$menu->setItem($slotID,$toclick);
				}
				$specialReward = Item::get(351,13,1);
				$specialReward->setCustomName("§r§d§l??? §r§f(§f§l#0130§r§f)");
				$specialReward->setLore([
					'§r§dTap to recieved your final reward',
				]);
				$nbt = $specialReward->getNamedTag();
				$nbt->setTag(new StringTag("specialReward"));
				$menu->setItem(49,$specialReward);
				break;
		}
	}
}

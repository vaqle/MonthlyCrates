<?php

namespace vale\utils;
use muqsit\invmenu\InvMenu;
use muqsit\invmenu\MenuIds;
use muqsit\invmenu\SharedInvMenu;
use muqsit\invmenu\transaction\DeterministicInvMenuTransaction;
use pocketmine\entity\Entity;
use pocketmine\inventory\transaction\action\SlotChangeAction;
use pocketmine\item\enchantment\Enchantment;
use pocketmine\item\enchantment\EnchantmentInstance;
use pocketmine\item\Item;
use pocketmine\nbt\tag\StringTag;
use pocketmine\network\mcpe\protocol\PlaySoundPacket;
use pocketmine\Player;
use pocketmine\utils\Color;
use vale\MCListener;
use vale\MonthlyCrates;
use vale\tasks\MonthlyCratesTask;

class Utils extends RewardInventorys
{
	public static $grid = [];
	/** @var InvMenu $menu */
	public static $menu;

	/** @var array $recievedItem */
	public static $recievedItem = [];

	public static function sendMCMenu(Player $player, string $type)
	{
		switch ($type) {
			case "JAN":
				self::$menu = InvMenu::create(MenuIds::TYPE_DOUBLE_CHEST);
				self::$menu->setName("JAN");
				$menu = self::$menu;
				parent::sendRewardInventorys(self::$menu->getInventory(), $type);
				self::$menu->setListener(InvMenu::readonly(function (DeterministicInvMenuTransaction $transaction) use ($player, $menu): void {
					if ($transaction->getItemClicked()->getNamedTag()->hasTag("reward")) {
						self::addSound($player,"note.bd",1);
						MonthlyCrates::getInstance()->getScheduler()->scheduleRepeatingTask(new MonthlyCratesTask($player, $menu, $transaction->getAction()), 20);
					}
					if ($transaction->getItemClicked()->getNamedTag()->hasTag("specialReward")) {
						if(isset(MClISTENER::$isOpening[$player->getName()])) {
							$player->sendMessage("You may not reveal final item until all slots have been redeemed");
						} else {
							self::$menu->getInventory()->setItem($transaction->getAction()->getSlot(), self::randFinalItems("finalitems"));
							$player->getInventory()->addItem(self::$menu->getInventory()->getItem($transaction->getAction()->getSlot()));
							self::addSound($player, "mob.horse.armor",4);

						}
					}
				}));
				self::$menu->send($player);
				break;
		}
	}

	public static function itemRand(string $items)
	{
		switch ($items) {
			case "rewards":
				$hsword = Item::get(Item::DIAMOND_SWORD);
				$hsword->setCustomName("§r§dHeroic Diamond Sword");
				$hsword->addEnchantment(new EnchantmentInstance(Enchantment::getEnchantmentByName("sharpness"),1));
				$hboots = Item::get(Item::DIAMOND_BOOTS);
				$hboots->setCustomName("§r§dHeroic Diamond Boots");
				$hboots->addEnchantment(new EnchantmentInstance(Enchantment::getEnchantmentByName("protection"),1));
				$godlychest = Item::get(Item::CHEST);
				$godlychest->setCustomName("§r§c§lGodly §r§cSpace Chest");
				$heroricchest = Item::get(Item::CHEST);
				$heroricchest->setCustomName("§r§d§lHeroric §r§dSpace Chest");
				$legenchest = Item::get(Item::CHEST);
				$legenchest->setCustomName("§r§6§lLegendary §r§6Space Chest");
				$phantomhelm = Item::get(Item::LEATHER_HELMET, 0, 1);
				$phantomhelm->setCustomName("§r§c§lPhantom Hood");
				$phantomhelm->setCustomColor(new Color(255,0,0));
				$phantomhelm->setLore([
					"§r§cThe fabled hood of the phantom",
					"§r§c§lPHANTOM SET BONUS",
					"§r§c§l* §r§cDeal +35% more damage to outgoing enemies",
					"§r§c§l* §r§c20% chance to spawn in Minions",
					"§r§7((REQUIRES ALL PHANTOM SET PIECES))",
				]);
				$phantomchest = Item::get(Item::LEATHER_CHESTPLATE, 0, 1);
				$phantomchest->setCustomColor(new Color(255,0,0));
				$phantomchest->setCustomName("§r§c§lPhantom Plate");
				$phantomchest->setLore([
					"§r§cThe fabled plate of the phantom",
					"§r§c§lPHANTOM SET BONUS",
					"§r§c§l* §r§cDeal +35% more damage to outgoing enemies",
					"§r§c§l* §r§c20% chance to spawn in Minions",
					"§r§7((REQUIRES ALL PHANTOM SET PIECES))",
				]);
				$phantomleg = Item::get(Item::LEATHER_LEGGINGS, 0, 1);
				$phantomleg->setCustomColor(new Color(255,0,0));
				$phantomleg->setCustomName("§r§c§lPhantom Robes");
				$phantomleg->setLore([
					"§r§cThe fabled robes of the phantom",
					"§r§c§lPHANTOM SET BONUS",
					"§r§c§l* §r§cDeal +35% more damage to outgoing enemies",
					"§r§c§l* §r§c20% chance to spawn in Minions",
					"§r§7((REQUIRES ALL PHANTOM SET PIECES))",

				]);
				$phantomboot = Item::get(Item::LEATHER_BOOTS, 0, 1);
				$phantomboot->setCustomColor(new Color(255,0,0));
				$phantomboot->setCustomName("§r§c§lPhantom Boots");
				$phantomboot->setLore([
					"§r§cThe fabled robes of the phantom",
					"§r§c§lPHANTOM SET BONUS",
					"§r§c§l* §r§cDeal +35% more damage to outgoing enemies",
					"§r§c§l* §r§c20% chance to spawn in Minions",
					"§r§7((REQUIRES ALL PHANTOM SET PIECES))",

				]);

				$sword = Item::get(Item::DIAMOND_SWORD);
				$sword->setCustomName("§r§c§lPhantom Sword");
				$sword->setLore([
					"§r§c§lPHANTOM SWORD",
					"§r§c§l* §r§cDeal +35% more damage to outgoing enemies",
					"§r§c§l* §r§c20% chance to spawn in Minions",
					"§r§7((REQUIRES ALL PHANTOM SET PIECES))",
				]);
					$Yijikihelm = Item::get(Item::LEATHER_HELMET, 0, 1);
				$Yijikihelm->setCustomName("§r§b§lYijiki Hood");
				$Yijikihelm->setCustomColor(new Color(0,0,255));
				$Yijikihelm->setLore([
					"§r§bThe fabled hood of the Yijiki",
					"§r§b§lYijiki SET BONUS",
					"§r§b§l* §r§bDeal +35% more damage to outgoing enemies",
					"§r§b§l* §r§b20% chance to spawn in Minions",
					"§r§7((REQUIRES ALL Yijiki SET PIECES))",
				]);
				$Yijikichest = Item::get(Item::LEATHER_CHESTPLATE, 0, 1);
				$Yijikichest->setCustomColor(new Color(0,0,255));
				$Yijikichest->setCustomName("§r§b§lYijiki Plate");
				$Yijikichest->setLore([
					"§r§bThe fabled plate of the Yijiki",
					"§r§b§lYijiki SET BONUS",
					"§r§b§l* §r§bDeal +35% more damage to outgoing enemies",
					"§r§b§l* §r§b20% chance to spawn in Minions",
					"§r§7((REQUIRES ALL Yijiki SET PIECES))",
				]);
				$Yijikileg = Item::get(Item::LEATHER_LEGGINGS, 0, 1);
				$Yijikileg->setCustomColor(new Color(0,0,255));
				$Yijikileg->setCustomName("§r§b§lYijiki Robes");
				$Yijikileg->setLore([
					"§r§bThe fabled robes of the Yijiki",
					"§r§b§lYijiki SET BONUS",
					"§r§b§l* §r§bDeal +35% more damage to outgoing enemies",
					"§r§b§l* §r§b20% chance to spawn in Minions",
					"§r§7((REQUIRES ALL Yijiki SET PIECES))",

				]);
				$Yijikiboot = Item::get(Item::LEATHER_BOOTS, 0, 1);
				$Yijikiboot->setCustomColor(new Color(0,0,255));
				$Yijikiboot->setCustomName("§r§b§lYijiki Boots");
				$Yijikiboot->setLore([
					"§r§bThe fabled robes of the Yijiki",
					"§r§b§lYijiki SET BONUS",
					"§r§b§l* §r§bDeal +35% more damage to outgoing enemies",
					"§r§b§l* §r§b20% chance to spawn in Minions",
					"§r§7((REQUIRES ALL Yijiki SET PIECES))",

				]);

				$ysword = Item::get(Item::DIAMOND_SWORD);
				$ysword->setCustomName("§r§b§lYijiki Sword");
				$ysword->setLore([
					"§r§b§lYijiki SWORD",
					"§r§b§l* §r§bDeal +35% more damage to outgoing enemies",
					"§r§b§l* §r§b20% chance to spawn in Minions",
					"§r§7((REQUIRES ALL Yijiki SET PIECES))",

				]);
				$gkit1 = Item::get(Item::DIAMOND);
				$gkit1->setCustomName("§6§lEVOLUTION KIT");
				$mask = Item::get(Item::PUMPKIN);
				$mask->setCustomName("§r§4§lOverlord §r§7Mask");
				$godapple = Item::get(Item::ENCHANTED_GOLDEN_APPLE);

				break;
		}
		$rewards = [$hsword, $hboots,$godlychest,$heroricchest,$legenchest,$phantomchest,$phantomboot,$phantomleg,$phantomhelm,$sword,$Yijikiboot,$Yijikichest,$Yijikihelm,$Yijikiboot,$ysword,$gkit1,$mask,$godapple];
		$reward = $rewards[array_rand($rewards)];

		return $reward;
	}

	public static function randFinalItems(string $items)
	{
		switch ($items) {
			case "finalitems":

				$godgem = Item::get(Item::EMERALD,0,1);
				$godgem->setCustomName( "§r§c§lSoul Gem[§r§f" . "§r§c§l]")->setLore([
						'§r§c§l* §r§cClick this item to toggle Soul Mode',
						'§r§7While in Soul Mode your ACTIVE god tier enchantments will activate and drain souls',
						'§r§7for as long as this mode is enabled',
						'',
						'',
						'§r§c§l* §r§7Use §r§c/splitsouls §r§7with this item to send other players souls',
						'',
					]);
				$sword = Item::get(Item::DIAMOND_SWORD);
				$sword->setCustomName("§r§dHeroic Diamond Sword of Death");
				$axe = Item::get(Item::DIAMOND_SWORD);
				$axe->setCustomName("§r§cGodly Diamond Axe of Death");


				break;
		}
		$rewards = [$godgem,$sword,$axe];
		$reward = $rewards[array_rand($rewards)];


		return $reward;
	}

	public static function giveMonthlyCrate(Player $player, string $type, int $amount = 1)
	{
		if ($player->isOnline()) {
			switch ($type) {
				case "JAN":
					$date = date('d-m-y');
					$jan = Item::get(Item::ENDER_CHEST, 0, $amount);
					$nbt = $jan->getNamedTag();
					$nbt->setTag(new StringTag("JANMC"));
					$jan->setCustomName("§r§f*§d*§e* §r§7JANUARAY CRATE §r§f*§d*§e* ". " §r§c" . $date);
					$jan->setLore([
						'§r§f§lISSUED BY CONSOLE',

						'§r§6§lLEGENDARY REWARDS',
						'§r§6§l* §r§6Evolution Kit',
						'§r§6§l* §r§6GodSet Kit',
						'§r§6§l* §r§6Overlord Kit',
						'§r§6§l* §r§6CE BUNDLE',
						'§r§6§l* §r§6TOP RANK',

						'§d§lEPIC ITEMS',
						'§r§d§l* §r§dRandom Rank',
						'§r§d§l* §r§dPartner Packages',
						'§r§d§l* §r§dDemon Rank',
						'§r§d§l* §r§dFeather of Light',


						"§r§a§lSIMPLE ITEMS",
						"§r§f§l* §r§fSimple Space Chest",
						"§r§f§l* §r§f1-5 Random Ces",
					]);
					$player->getInventory()->addItem($jan);
					break;
			}
		}
	}

	/**
	 * @param Entity $player
	 * @param string $sound
	 * @param int $pitch
	 */
#credits to verge
	public static function addSound(Entity $player, string $sound, int $pitch = 1): void
	{
		if ($player instanceof Player && $player->isOnline()) {
			foreach ($player->getLevel()->getNearbyEntities($player->getBoundingBox()->expandedCopy(2, 2, 2)) as $p) {
				if ($p instanceof Player) {

					$spk = new PlaySoundPacket();
					$spk->soundName = $sound;
					$spk->x = $p->getX();
					$spk->y = $p->getY();
					$spk->z = $p->getZ();
					$spk->volume = 0.5;
					$spk->pitch = $pitch;
					$p->dataPacket($spk);
				}
			}
		}
	}
}

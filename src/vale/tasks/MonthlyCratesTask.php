<?php


namespace vale\tasks;


use muqsit\invmenu\InvMenu;
use pocketmine\inventory\Inventory;
use pocketmine\inventory\transaction\action\SlotChangeAction;
use pocketmine\item\Item;
use pocketmine\nbt\tag\StringTag;
use pocketmine\Player;
use pocketmine\scheduler\Task;
use vale\MCListener;
use vale\MonthlyCrates;
use vale\utils\RewardInventorys;
use vale\utils\Utils;

class MonthlyCratesTask extends Task
{
	public  $player;
	public $inv;
	public $slot;
	public $seconds = 30;
	public $grid = [12, 13, 14, 21, 22, 23, 30, 31, 32];
//was hella lazy to get every single slot my self thx verge
	public $outsideGrid = [0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 15, 16, 17, 18, 19, 20, 24, 25, 26, 27, 28, 29, 33, 34, 35, 36, 37, 38, 39, 40, 41, 42, 43, 44, 45, 46, 47, 48, 50, 51, 52, 53];

	/**
	 * MonthlyCratesTask constructor.
	 * @param Player $player
	 * @param InvMenu $inv
	 * @param SlotChangeAction $slot
	 */
	public function __construct(Player $player, InvMenu $inv, SlotChangeAction $slot)
	{
		$this->player = $player;
		$this->inv = $inv;
		$this->slot = $slot;
		var_dump($player);
	}

	public function onRun(int $currentTick)
	{
		--$this->seconds;
		$glass = Item::get(241, mt_rand(1, 10), 1);
		foreach ($this->outsideGrid as $outID) {
			Utils::addSound($this->player,"note.harp",2);
			$this->inv->getInventory()->setItem($outID, $glass);
			$this->slot->getInventory()->setItem($this->slot->getSlot(), Utils::itemRand("rewards"));

		}
		if ($this->seconds === 0) {
			if ($this->player->isOnline()) {
				MonthlyCrates::getInstance()->getScheduler()->cancelTask($this->getTaskId());
				Utils::addSound($this->player,"note.harp",1);
				$this->player->getInventory()->addItem($this->inv->getInventory()->getItem($this->slot->getSlot()));
				if (in_array($this->player->getName(), Utils::$recievedItem)) {
					unset(MCListener::$isOpening[$this->player->getName()]);
					$this->inv->onClose($this->player);
				}
			}
		}
	}
}

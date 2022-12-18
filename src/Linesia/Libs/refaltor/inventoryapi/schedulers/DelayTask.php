<?php

namespace Linesia\Libs\refaltor\inventoryapi\schedulers;

use pocketmine\player\Player;
use pocketmine\scheduler\Task;
use Linesia\Libs\refaltor\inventoryapi\inventories\SimpleChestInventory;

class DelayTask extends Task {

    public Player $player;
    public SimpleChestInventory $inventory;

    public function __construct(Player $player, SimpleChestInventory $inventory) {
        $this->player = $player;
        $this->inventory = $inventory;
    }

    public function onRun() : void {
        if ($this->player->isConnected()) {
            $this->player->setCurrentWindow($this->inventory);
        }
    }
}

<?php

namespace Linesia\Libs\refaltor\inventoryapi\inventories\type;

use Linesia\Libs\refaltor\inventoryapi\InventoryAPI;
use pocketmine\inventory\Inventory;
use pocketmine\player\Player;

interface InvMenuType {

    public function createGraphic(InventoryAPI $menu, Player $player);

    public function createInventory() : Inventory;

}
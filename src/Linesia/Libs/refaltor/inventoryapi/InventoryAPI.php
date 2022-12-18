<?php

namespace Linesia\Libs\refaltor\inventoryapi;

use Linesia\Libs\refaltor\inventoryapi\inventories\{DoubleInventory, SimpleChestInventory};
use pocketmine\utils\SingletonTrait;

class InventoryAPI {
    /*
     * Features: workbench inventory, hopper inventory
     */
    
    use SingletonTrait;

    public static function createSimpleChest(bool $isViewOnly = false): SimpleChestInventory {
        $inventory = new SimpleChestInventory();
        $inventory->setViewOnly($isViewOnly);
        return $inventory;
    }

    public static function createDoubleChest(bool $isViewOnly = false): SimpleChestInventory {
        $inventory = new DoubleInventory();
        $inventory->setViewOnly($isViewOnly);
        return $inventory;
    }

    public function getDelaySend(): int {
        return 1;
    }

}

<?php

namespace Linesia\Inventories;

use Linesia\Command\Grade\EnderChestCommand;
use pocketmine\block\inventory\EnderChestInventory;
use pocketmine\player\Player;

class FakeEnderChest extends EnderChestInventory {

    public function onClose(Player $who): void {
        parent::onClose($who);
        EnderChestCommand::sendFakeBlock([$who], $who->getWorld()->getBlock($who->getPosition()));
    }

}
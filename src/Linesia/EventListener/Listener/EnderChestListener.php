<?php

namespace Linesia\EventListener\Listener;

use Linesia\Core;
use Linesia\Player\LinesiaPlayer;
use pocketmine\block\inventory\ChestInventory;
use pocketmine\block\inventory\EnderChestInventory;
use pocketmine\event\inventory\InventoryOpenEvent;
use pocketmine\event\inventory\InventoryTransactionEvent;
use pocketmine\event\Listener;
use pocketmine\item\ItemFactory;
use pocketmine\nbt\tag\CompoundTag;

class EnderChestListener implements Listener {

    public function InventoryOpenEvent(InventoryOpenEvent $event) {

        $inv = $event->getInventory();
        $sender = $event->getPlayer();

        if ($inv instanceof EnderChestInventory) {

            if (!$sender instanceof LinesiaPlayer) return;

            self::setSlots($sender, self::getSlots($sender));
        }
    }

    public static function getSlots(LinesiaPlayer $sender): int {

        $group = Core::getInstance()->getRankAPI()->getRankPlayer($sender);

        if ($group == "Administrateur" or $group == "Roi" or $group == "Développeur" or $sender->getPermission("linesia.enderchest.roi")) {

            $slots = 27;

        }elseif ($group == "VIP" or $sender->getPermission("linesia.enderchest.vip")) {

            $slots = 24;

        }elseif ($group == "Mini-VIP" or $sender->getPermission("linesia.enderchest.minivip")) {

            $slots = 21;

        }else{

            $slots = 18;
        }

        return $slots;

    }

    public function onEnderchestTransaction(InventoryTransactionEvent $e): void
    {
        $transactions = $e->getTransaction()->getActions();
        foreach ($transactions as $transaction){
            $item =$transaction->getSourceItem();
            $nbt = ($item->getNamedTag() ?? new CompoundTag());
            $item1 =$transaction->getTargetItem();
            $nbt1 = ($item1->getNamedTag() ?? new CompoundTag());
            foreach ($e->getTransaction()->getInventories() as $inv){
                if ($inv instanceof EnderChestInventory or $inv instanceof ChestInventory) {
                    if($nbt->getTag("Restricted") || $nbt1->getTag("Restricted")) {
                        $e->cancel();
                    }
                }
            }
        }
    }

    public static function setSlots(LinesiaPlayer $player, int $slots): void
    {
        $enderchest = $player->getEnderInventory();

        for ($i = 1; $i <= 26; $i++){
            $item = $player->getEnderInventory()->getItem($i);
            $nbt = ($item->getNamedTag() ?? new CompoundTag());

            if($nbt->getTag("Restricted")){
                $enderchest->setItem($i, ItemFactory::getInstance()->get(0, 0, 1));
            }

            if($slots <= $i){
                if($item->getId() == 0 or $item->getId() == 437 or $item->getId() == 160){
                    $glass = ItemFactory::getInstance()->get(160, 15, 1);
                    $glass->setCustomName("§9- §fBloqué §9-");

                    $nbt = ($glass->getNamedTag() ?? new CompoundTag());
                    $nbt->setString("Restricted", "true");
                    $glass->setNamedTag($nbt);
                    $enderchest->setItem($i, $glass);
                    $slots++;
                }
            }
        }
    }
}
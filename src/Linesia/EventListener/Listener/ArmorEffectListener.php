<?php

namespace Linesia\EventListener\Listener;

use Linesia\Utils\Utils;
use pocketmine\data\bedrock\EffectIdMap;
use pocketmine\entity\effect\EffectInstance;
use pocketmine\entity\Living;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerJoinEvent;
use pocketmine\inventory\ArmorInventory;
use pocketmine\inventory\CallbackInventoryListener;
use pocketmine\inventory\Inventory;
use pocketmine\item\Armor;
use pocketmine\item\Item;
use pocketmine\item\ItemFactory;
use pocketmine\item\ItemIds;

class ArmorEffectListener implements Listener {

    private const EFFECT_MAX_DURATION = 2147483647;

    public function onJoin(PlayerJoinEvent $event) : void {
        $player = $event->getPlayer();
        foreach ($player->getArmorInventory()->getContents() as $targetItem) {
            if ($targetItem instanceof Armor) {
                $slot = $targetItem->getArmorSlot();
                $sourceItem = $player->getArmorInventory()->getItem($slot);

                $this->addEffects($player, $sourceItem, $targetItem);
            } else {
                if ($targetItem->getId() == ItemIds::AIR) {
                    $this->addEffects($player, ItemFactory::air(), $targetItem);
                }
            }
        }

        $player->getArmorInventory()->getListeners()->add(new CallbackInventoryListener(function(Inventory $inventory, int $slot, Item $oldItem) : void{
            if ($inventory instanceof ArmorInventory) {
                $targetItem = $inventory->getItem($slot);
                $this->addEffects($inventory->getHolder(), $oldItem, $targetItem);
            }
        },  null));
    }
    private function addEffects(Living $player, Item $sourceItem, Item $targetItem) : void {
        $configs = Utils::getConfigFile("ArmorEffect", "yml")->getAll();
        $ids = array_keys($configs);

        if (in_array($sourceItem->getId(), $ids)) {
            $array = Utils::getConfigFile("ArmorEffect", "yml")->getAll()[$sourceItem->getId()];
            $effects = $array["effect"];

            foreach ($effects as $effectid => $arrayeffect) {
                $player->getEffects()->remove(EffectIdMap::getInstance()->fromId($effectid));
            }
        }

        if (in_array($targetItem->getId(), $ids)) {
            $array = Utils::getConfigFile("ArmorEffect", "yml")->getAll()[$targetItem->getId()];
            $effects = $array["effect"];

            foreach ($effects as $effectid => $arrayeffect) {
                $eff = new EffectInstance(
                    EffectIdMap::getInstance()->fromId($effectid),
                    self::EFFECT_MAX_DURATION,
                    (int)$arrayeffect["amplifier"],
                    (bool)$arrayeffect["visible"]
                );
                $player->getEffects()->add($eff);
            }
        }
    }

}
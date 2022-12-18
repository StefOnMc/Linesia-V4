<?php

namespace Linesia\EventListener\Listener;

use Linesia\Item\Administration\NPCWandItem;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerInteractEvent;

class NpcListener implements Listener {

    public function onPlayerItemUse(PlayerInteractEvent $event){
        $sender = $event->getPlayer();
        $action = $event->getAction();

        if ($action === PlayerInteractEvent::RIGHT_CLICK_BLOCK){
            if ($sender->getInventory()->getItemInHand() instanceof NPCWandItem){
                NPCWandItem::sendCreationMenu($sender);
            }
        }
    }
}
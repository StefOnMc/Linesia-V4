<?php

namespace Linesia\EventListener\Listener;

use Linesia\Utils\Utils;
use pocketmine\event\block\BlockBreakEvent;
use pocketmine\event\Listener;
use pocketmine\item\ItemFactory;

class RandomOreListener implements Listener {

    public function onBreak(BlockBreakEvent $event)
    {
        $block = $event->getBlock();
        $array = Utils::getConfigFile("RandomOre", "yml")->getAll();

        if ($block->getId() === $array['randomOre']){
            foreach ($array['drops'] as $id => $keys){
                $item = explode(":", $id);
                if (isset($array['chance'])){
                    if (mt_rand(1, $array['chance']) === 1){
                        $item = ItemFactory::getInstance()->get($item[0], $item[1], $array['amount']);
                        if (isset($array['name'])) $item->setCustomName($array['name']);
                        $event->setDrops([$item]);
                    }
                }else {
                    $item = ItemFactory::getInstance()->get($item[0], $item[1], $array['amount']);
                    if (isset($array['name'])) $item->setCustomName($array['name']);
                    $event->setDrops([$item]);
                }
            }
        }
    }

}
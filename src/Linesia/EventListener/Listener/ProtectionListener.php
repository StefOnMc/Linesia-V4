<?php

namespace Linesia\EventListener\Listener;

use Linesia\Player\LinesiaPlayer;
use pocketmine\event\block\BlockBreakEvent;
use pocketmine\event\block\BlockPlaceEvent;
use pocketmine\event\entity\EntityDamageByEntityEvent;
use pocketmine\event\entity\EntityDamageEvent;
use pocketmine\event\Listener;
use pocketmine\math\Vector3;

class ProtectionListener implements Listener {

    /**
     * @param Vector3 $pos
     * @param string $type
     * @return bool
     */
    public function isProtectZone(Vector3 $pos, string $type = "spawn"): bool {

        if($type === "spawn") {
            $minXSpawn = -46;
            $maxXSpawn = 47;
            $minZSpawn = -45;
            $maxZSpawn = 46;
        } else {
            $minXSpawn = -130;
            $maxXSpawn = 130;
            $minZSpawn = -130;
            $maxZSpawn = 130;
        }

        return ($pos->getX() <= $maxXSpawn && $pos->getX() >= $minXSpawn) && ($pos->getZ() <= $maxZSpawn && $pos->getZ() >= $minZSpawn);

    }

    /**
     * @param BlockBreakEvent $event
     * @return void
     */
    public function onBreak(BlockBreakEvent $event): void {

        $block = $event->getBlock();
        $player = $event->getPlayer();

        if ($player instanceof LinesiaPlayer)

        if(!$player->getPermission("linesia.protection.build")) {

                if ($this->isProtectZone($block->getPosition()->asVector3(), "warzone")) {
                    $player->sendTip("§cVous ne pouvez pas casser !");
                    $event->cancel();
            }
        }
    }

    /**
     * @param BlockPlaceEvent $event
     * @return void
     */
    public function onPlace(BlockPlaceEvent $event): void {

        $block = $event->getBlock();
        $player = $event->getPlayer();

        if ($player instanceof LinesiaPlayer)

        if(!$player->hasPermission("protection.build")) {

                if($this->isProtectZone($player->getPosition()->asVector3(), "warzone")) {

                    $player->sendTip("§cVous ne pouvez pas poser de blocs !");
                    $event->cancel();
            }
        }
    }

    /**
     * @param EntityDamageEvent $event
     * @return void
     */
    public function onDamage(EntityDamageEvent $event): void {

        $player = $event->getEntity();

        if($this->isProtectZone($player->getPosition()->asVector3())) {

            if($event instanceof EntityDamageByEntityEvent) {

                $damage = $event->getDamager();
                if($player instanceof LinesiaPlayer && $damage instanceof LinesiaPlayer) {

                        $damage->sendTip("§cVous ne pouvez pas taper cette personne !");
                        $event->cancel();

                }

            } else {
                $event->cancel();
            }
        }
    }

    public static function getInstance() : ProtectionListener{
        return new ProtectionListener();
    }

}
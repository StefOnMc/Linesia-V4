<?php

namespace Linesia\EventListener\Listener;

use Linesia\Entity\Items\DynamiteEntity;
use pocketmine\event\entity\EntityExplodeEvent;
use pocketmine\event\entity\ProjectileHitEntityEvent;
use pocketmine\event\entity\ProjectileHitEvent;
use pocketmine\event\Listener;
use pocketmine\world\Explosion;
use pocketmine\world\Position;

class DynamiteListener implements Listener {

    public function onHitEntity(ProjectileHitEntityEvent $event)
    {
        $this->extracted($event);
    }

    public function onHitEntity2(ProjectileHitEvent $event)
    {
        $this->extracted($event);
    }

    public function onExplodeEntity(EntityExplodeEvent $event){
        $entity = $event->getEntity();
        $block = $entity->getWorld()->getBlock($entity->getPosition());
        $list = [];
        if ($entity instanceof DynamiteEntity){
            if (!$event->isCancelled()){
                for($i = 0; $i <= (3.3*2); $i++) {
                    $list[] = $block->getSide($i);
                }
            }
        }
    }
    /**
     * @param ProjectileHitEvent $event
     * @return void
     */
    private function extracted(ProjectileHitEvent $event): void
    {
        $entity = $event->getEntity();
        if ($entity instanceof DynamiteEntity) {
            if (!ProtectionListener::getInstance()->isProtectZone($entity->getPosition()->asVector3(), "warzone")) {
                $explosion = new Explosion(new Position($entity->getPosition()->getX(), $entity->getPosition()->getY(), $entity->getPosition()->getZ(), $entity->getWorld()), 3.4, $entity);
                $explosion->explodeA();
                $explosion->explodeB();
                $entity->flagForDespawn();
            }

        }
    }
}
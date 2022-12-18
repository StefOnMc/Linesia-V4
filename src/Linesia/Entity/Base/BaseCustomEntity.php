<?php

namespace Linesia\Entity\Base;

use Linesia\Entity\EntityNbt;
use Linesia\Entity\NPCEntity;
use pocketmine\entity\Human;
use pocketmine\entity\Location;
use pocketmine\entity\Skin;
use pocketmine\event\entity\EntityDamageByEntityEvent;
use pocketmine\event\entity\EntityDamageEvent;
use pocketmine\nbt\tag\CompoundTag;

abstract class BaseCustomEntity extends Human {
    use NPCEntity;

    public function __construct(Location $location, Skin $skin, ?CompoundTag $nbt = null) {

        parent::__construct($location, $skin, $nbt);

        if(!is_null($nbt) && $nbt->getTag(EntityNbt::TAG_ISNPC) !== null)
            $this->restorNpc($nbt);

    }

    /**
     * @return CompoundTag
     */
    public function saveNBT(): CompoundTag {

        $nbt = parent::saveNBT();

        if($this->isNpc())
            $nbt = $this->saveNpcNbt($nbt);

        return $nbt;

    }

    /**
     * @param EntityDamageEvent $source
     * @return void
     */
    public function attack(EntityDamageEvent $source): void {

        if(!$this->isNpc())
            parent::attack($source);
        else if ($source instanceof EntityDamageByEntityEvent)
            $this->executeCommands($source->getDamager());

    }
}
<?php

namespace Linesia\Entity\NPC;

use JetBrains\PhpStorm\Pure;
use Linesia\Entity\Base\BaseEntity;
use pocketmine\entity\EntitySizeInfo;
use pocketmine\entity\Location;
use pocketmine\nbt\tag\CompoundTag;
use pocketmine\network\mcpe\protocol\types\entity\EntityIds;

class CowNPC extends BaseEntity {

    public function __construct(Location $location, ?CompoundTag $nbt = null)
    {
        parent::__construct($location, $nbt);
    }

    /**
     * @return EntitySizeInfo
     */
    #[Pure] protected function getInitialSizeInfo(): EntitySizeInfo {
        return new EntitySizeInfo(1.3, 0.9);
    }

    /**
     * @return string
     */
    public static function getNetworkTypeId(): string {
        return EntityIds::COW;
    }

    /**
     * @return string
     */
    public function getName(): string {
        return "Cow";
    }



}
<?php

namespace Linesia\Entity\Box;

use Linesia\Core;
use Linesia\Utils\Utils;
use pocketmine\entity\EntitySizeInfo;
use pocketmine\entity\Human;
use pocketmine\entity\Skin;
use pocketmine\nbt\tag\CompoundTag;
use pocketmine\network\mcpe\protocol\types\entity\EntityIds;

class VoteBox extends Human{

    public static function getNetworkTypeId(): string {
        return EntityIds::NPC;
    }

    protected function getInitialSizeInfo(): EntitySizeInfo {
        return new EntitySizeInfo(1.0, 0.6, 1.0);
    }

    protected function initEntity(CompoundTag $nbt): void {
        parent::initEntity($nbt);
        $this->setImmobile();
        $this->setNameTagAlwaysVisible();
        $this->setNameTag("§7Box §aVote");
        $this->setScale(1.6);

        $path = Core::getInstance()->getDataFolder() . "Box/Box.png";
        $data = Utils::PNGtoBYTES($path);
        $cape = "";
        $path = Core::getInstance()->getDataFolder() . "Box/Box.json";
        $geometry = file_get_contents($path);
        $skin = new Skin($this->getName(), $data, $cape, "geometry.unknown", $geometry);
        $this->setSkin($skin);
    }


}
<?php

namespace Linesia\Item\PvP;

use Linesia\Entity\Items\DynamiteEntity;
use pocketmine\entity\Location;
use pocketmine\entity\projectile\Throwable;
use pocketmine\item\Snowball;
use pocketmine\player\Player;

class Dynamite extends Snowball {

    protected function createEntity(Location $location, Player $thrower): Throwable {
        return new DynamiteEntity($location, $thrower);
    }

}
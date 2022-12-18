<?php

namespace Linesia\Item\PvP;

use Linesia\Entity\Items\FakeEnderPearlEntity as FakePearls;
use pocketmine\entity\Location;
use pocketmine\entity\projectile\Throwable;
use pocketmine\item\Egg;
use pocketmine\player\Player;

class FakeEnderPearl extends Egg {

    /**
     * @param Location $location
     * @param Player $thrower
     * @return Throwable
     */
    protected function createEntity(Location $location, Player $thrower): Throwable {
        return new FakePearls($location, $thrower);
    }
}
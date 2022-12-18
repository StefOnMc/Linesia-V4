<?php


namespace Linesia\Entity;

use Linesia\Item\Administration\NPCWandItem;
use pocketmine\player\Player;

trait BossEntity {

    /**
     * @var bool
     */
    private bool $boss = false;

    /**
     * @param Player $player
     * @return void
     */
    public function isAttackByNPCWand(Player $player): void
    {

        $item = $player->getInventory()->getItemInHand();


        if($item instanceof NPCWandItem) {
            $item->sendMenu($player, $this);
        }
    }

    /**
     * @param bool $value
     * @return void
     */
    public function setBoss(bool $value = true): void
    {
        $this->boss = $value;
    }

    /**
     * @return bool
     */
    public function isBoss() : bool {
        return $this->boss;
    }

}
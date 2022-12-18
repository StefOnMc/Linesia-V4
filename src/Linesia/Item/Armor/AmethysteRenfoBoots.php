<?php

namespace Linesia\Item\Armor;

use http\Exception\InvalidArgumentException;
use pocketmine\inventory\ArmorInventory;
use pocketmine\item\Armor;
use pocketmine\item\ArmorTypeInfo;
use pocketmine\item\enchantment\VanillaEnchantments;
use pocketmine\item\Item;
use pocketmine\item\ItemIdentifier;

class AmethysteRenfoBoots extends Armor {

    public function __construct() {
        parent::__construct(new ItemIdentifier(317,0), "Amethyste-Renfo Boots", new ArmorTypeInfo(3, 2058, ArmorInventory::SLOT_FEET));
    }

    public function getMaxDurability(): int
    {
        return 2058;
    }

    public function getBaseDurability(): int {
        return 91;
    }



    /**
     * @param int $damage
     * @param bool $bool
     * @return Item
     */
    public function setDamage(int $damage, bool $bool = false): Item {
        if ($damage < 0 || $damage > $this->getMaxDurability()){
            throw new InvalidArgumentException("Damage must be in range 0 - " . $this->getMaxDurability());
        }
        if ($bool){
            $this->getNamedTag()->setString("Durabilité", self::getMaxDurability());
        }
        $this->damage = $damage;
        return $this;
    }

    /**
     * @param int $amount
     * @return bool
     */
    public function applyDamage(int $amount): bool {

        $amount -= self::getUnbrekingDamageReductions($this, $amount);
        $baseDurability = $this->getBaseDurability();
        $newDurability = self::getMaxDurability();
        if (is_null($this->getNamedTag()->getTag("Durabilité"))) $this->getNamedTag()->setString("Durabilité", $newDurability-1);
        $durability = intval($this->getNamedTag()->getString("Durabilité"));
        $damage = $newDurability / $baseDurability;
        if ($durability <= 0) {
            return parent::applyDamage($baseDurability);
        }
        $this->getNamedTag()->setString("Durabilité", $durability - $amount);
        $damage = intval(round($durability / $damage - $baseDurability) * -1);
        $this->setDamage($damage);
        $this->setLore(["§7Durabilité : §9" . $this->getNamedTag()->getString("Durabilité") . "§7/§9" . self::getMaxDurability()]);
        return true;
    }

    /**
     * @param Item $item
     * @param int $amount
     * @return int
     */
    protected static function getUnbrekingDamageReductions(Item $item, int $amount): int
    {
        if (($unbreakingLevel = $item->getEnchantmentLevel(VanillaEnchantments::UNBREAKING())) > 0) {
            $negated = 0;
            $chance = 1 / ($unbreakingLevel + 1);
            for ($i = 0; $i < $amount; ++$i) {
                if (mt_rand(1, 100) > 60 and lcg_value() > $chance) {
                    $negated++;
                }
            }
            return $negated;
        }
        return 0;
    }

}
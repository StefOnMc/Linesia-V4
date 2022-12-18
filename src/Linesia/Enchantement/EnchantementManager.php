<?php

namespace Linesia\Enchantement;

use Linesia\Core;
use Linesia\Enchantement\CustomEnchant\AutoRepairEnchant;
use pocketmine\data\bedrock\EnchantmentIdMap;
use pocketmine\item\enchantment\StringToEnchantmentParser;

class EnchantementManager {

    /** @var CustomEnchant[] */
    public static array $enchants = [];

    private static Core $plugin;

    public static function initEnchantement(Core $plugin): void {
        self::$plugin = $plugin;
        self::registerEnchantment(new AutoRepairEnchant($plugin, CustomEnchantIds::AUTOREPAIR));

    }

    public static function registerEnchantment(CustomEnchant $enchant): void
    {
        EnchantmentIdMap::getInstance()->register($enchant->getId(), $enchant);
        self::$enchants[$enchant->getId()] = $enchant;
        StringToEnchantmentParser::getInstance()->register($enchant->name, fn() => $enchant);
        if ($enchant->name !== $enchant->getDisplayName()) StringToEnchantmentParser::getInstance()->register($enchant->getDisplayName(), fn() => $enchant);
    }

    public static function getPlugin() : Core{
        return self::$plugin;
    }

}
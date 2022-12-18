<?php

namespace Linesia\EventListener\Listener;

use Linesia\Core;
use Linesia\Utils\Utils;
use pocketmine\crafting\ShapedRecipe;
use pocketmine\item\Item;
use pocketmine\item\ItemFactory;

class CustomCraftListener {

    public function registerCrafts(Core $plugin): void
    {
        $config = Utils::getConfigFile("Craft", "yml");
        foreach ($config->getAll() as $name => $values) {
            $recipe = new ShapedRecipe(
                array("abc", "def", "ghi"),
                array(
                    "a" => self::getItem($values['shape'][0][0]),
                    "b" => self::getItem($values['shape'][0][1]),
                    "c" => self::getItem($values['shape'][0][2]),
                    "d" => self::getItem($values['shape'][1][0]),
                    "e" => self::getItem($values['shape'][1][1]),
                    "f" => self::getItem($values['shape'][1][2]),
                    "g" => self::getItem($values['shape'][2][0]),
                    "h" => self::getItem($values['shape'][2][1]),
                    "i" => self::getItem($values['shape'][2][2]),
                ),
                [$this->getItem($values['result'][0])]
            );
            $plugin->getServer()->getCraftingManager()->registerShapedRecipe($recipe);
        }
    }

    private function getItem($item): Item {
        $items = explode(":", $item);
        $id = intval($items[0]);
        $meta = intval($items[1]);
        if(array_key_exists(2,$items)) {
            $count = intval($items[2]);
            return ItemFactory::getInstance()->get($id, $meta, $count);
        }
        return ItemFactory::getInstance()->get($id, $meta);
    }

}
<?php

namespace Linesia\API;

use JsonException;
use Linesia\Core;
use Linesia\Player\LinesiaPlayer;
use Linesia\Utils\Utils;
use pocketmine\world\Position;

final class WarpAPI extends API {

    /**
     * @throws JsonException
     */
    public static function createWarp(string $WarpName, int $x, int $y, int $z, string $world, string $permission = "linesia.default"): void {

        $config = Utils::getConfigFile("Warps/Warps", "yml");
        $config->setNested($WarpName . ".Name", $WarpName);
        $config->setNested($WarpName . ".X", $x);
        $config->setNested($WarpName . ".Y", $y);
        $config->setNested($WarpName . ".Z", $z);
        $config->setNested($WarpName . ".World", $world);
        $config->setNested($WarpName . ".Permission", $permission);
        $config->save();
    }

    /**
     * @throws JsonException
     */
    public static function removeWarp(string $WarpName): void {

        $config = Utils::getConfigFile("Warps/Warps", "yml");
        $config->removeNested($WarpName);
        $config->save();
    }

    public static function teleportWarp(LinesiaPlayer $sender,string $warp): void {
        $config = Utils::getConfigFile("Warps/Warps", "yml");
        $sender->teleport(new Position($config->getNested($warp . ".X"), $config->getNested($warp . ".Y"), $config->getNested($warp . ".Z"), Core::getInstance()->getServer()->getWorldManager()->getWorldByName($config->getNested($warp . ".World"))));
    }

}
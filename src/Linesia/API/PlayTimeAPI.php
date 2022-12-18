<?php

namespace Linesia\API;

use JsonException;
use Linesia\Utils\Utils;
use pocketmine\utils\Config;

final class PlayTimeAPI extends API {

    private static array $playTime = [];

    public static function setPlayTime($name): void {
        self::$playTime[$name] = time();
    }

    /**
     * @throws JsonException
     */
    public static function setUnplayTime($name): void
    {
        $config = Utils::getConfigFile("Stats/PlayTime", "json");
        $newTime = time() - self::$playTime[$name];

        $config->set($name, self::getFileplayTime()->get($name) + $newTime);
        $config->save();

        unset(self::$playTime[$name]);
    }

    public static function getPlayTime($name)
    {
        if (isset(self::$playTime[$name])) {
            $playTimeBefore = self::getFileplayTime()->get($name);
            return ($playTimeBefore + (time() - self::$playTime[$name]));
        } else {
            return self::getFileplayTime()->get($name);
        }
    }

    public static function playTime($name): string {
        $t = self::getPlayTime($name);

        if ($t < 0) {
            return "0 heure(s), 0 heure(s), 0 minute(s)";
        } else {
            return floor(abs($t) / 3600) . " heure(s), " . (abs($t) / 60) % 60 . " minute(s), " . abs($t) % 60 . " seconde(s)";
        }
    }

    public static function getFileplayTime(): Config {
        return Utils::getConfigFile("Stats/PlayTime", "json");
    }

}
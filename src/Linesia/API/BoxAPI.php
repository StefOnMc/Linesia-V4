<?php

namespace Linesia\API;

use JsonException;
use Linesia\Player\LinesiaPlayer;
use Linesia\Utils\Utils;

final class BoxAPI extends API {

    /**
     * @throws JsonException
     */
    public function addKey(LinesiaPlayer $sender, string $boxName, int $key): void {
        $config = Utils::getConfigFile("Box/" . $boxName, "json");
        $config->set($sender->getName(), $config->get($sender->getName()) + $key);
        $config->save();
    }

    /**
     * @throws JsonException
     */
    public function delKey(LinesiaPlayer $sender, string $boxName, int $key): void {
        $config = Utils::getConfigFile("Box/" . $boxName, "json");
        $config->set($sender->getName(), $config->get($sender->getName()) - $key);
        $config->save();
    }

    public function getKey(LinesiaPlayer $sender, string $boxName){
        $config = Utils::getConfigFile("Box/" . $boxName, "json");
        return $config->get($sender->getName());
    }

    public static function getInstance(): BoxAPI{
        return new BoxAPI();
    }

}
<?php

namespace Linesia\API;

use JsonException;
use Linesia\Player\LinesiaPlayer;
use Linesia\Utils\Utils;

final class EconomyAPI extends API {

    public function getMoney(LinesiaPlayer $player){
        $config = Utils::getConfigFile("Stats/Money", "json");
        return $config->get($player->getName());
    }

    /**
     * @throws JsonException
     */
    public function addMoneyOnline(LinesiaPlayer $sender, int $amount){
        $config = Utils::getConfigFile("Stats/Money", "json");
        $config->set($sender->getName(), $config->get($sender->getName()) + $amount);
        $config->save();
    }

    /**
     * @throws JsonException
     */
    public function deleteMoney(LinesiaPlayer $sender, int $amount){
        $config = Utils::getConfigFile("Stats/Money", "json");
        $config->set($sender->getName(),$config->get($sender->getName()) - $amount);
        $config->save();
    }

    public static function getInstance(): EconomyAPI{
        return new EconomyAPI();
    }

}
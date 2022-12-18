<?php

namespace Linesia\API;

use Linesia\Utils\Utils;

final class WhitelistAPI extends API {

    public function getWhitelistStatus() {
        return Utils::getConfigFile("Config","yml")->get("maintenance");
    }

    public function getWhitelistRaison(){
        return Utils::getConfigFile("Config","yml")->get("maintenance.raison");
    }

    /**
     * @throws \JsonException
     */
    public function addPlayerInWhitelist($player){
        $config = Utils::getConfigFile("Whitelist/player_whitelist", "txt");
        $config->set($player);
        $config->save();
    }

    /**
     * @throws \JsonException
     */
    public function deletePlayerInWhitelist($player){
        $config = Utils::getConfigFile("Whitelist/player_whitelist", "txt");
        $config->remove($player);
        $config->save();
    }

    public function isInWhitelist($player): bool {
        return Utils::getConfigFile("Whitelist/player_whitelist", "txt")->exists($player);
    }

    public static function getInstance(): WhitelistAPI{
        return new WhitelistAPI();
    }

}
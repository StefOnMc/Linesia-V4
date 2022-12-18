<?php

namespace Linesia\EventListener\PlayerEvent;

use Linesia\API\WhitelistAPI;
use Linesia\Core;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerPreLoginEvent;

class PlayerPreLogin implements Listener {

    public function onPlayerPreLogin(PlayerPreLoginEvent $event){
        $sender = $event->getPlayerInfo()->getUsername();

        if (WhitelistAPI::getInstance()->getWhitelistStatus() === true){
            if (!WhitelistAPI::getInstance()->isInWhitelist($sender)){
                $event->setKickReason(2, "[§c!§f] Le serveur est actuellement en maintenance pour le motif : §e" . WhitelistAPI::getInstance()->getWhitelistRaison() . "§f. Voici notre discord pour rester informé de l'avancement. (§e" . Core::DISCORD . "§f)");
            }
        }
    }

}
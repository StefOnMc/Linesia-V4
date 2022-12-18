<?php

namespace Linesia\EventListener\PlayerEvent;

use JsonException;
use Linesia\API\PlayTimeAPI;
use Linesia\API\StaffModeAPI;
use Linesia\Player\LinesiaPlayer;
use Linesia\Utils\Utils;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerQuitEvent;

class PlayerQuit implements Listener {

    /**
     * @throws JsonException
     */
    public function onPlayerQuit(PlayerQuitEvent $event){
        $sender = $event->getPlayer();
        if ($sender instanceof LinesiaPlayer){
            PlayTimeAPI::setUnplayTime($sender->getName());
            if (StaffModeAPI::getVanish($sender)){
                StaffModeAPI::removeStaffMode($sender);
            }
            $event->setQuitMessage("[§c-§f] " . Utils::getRankPlayer($sender));
        }
    }

}
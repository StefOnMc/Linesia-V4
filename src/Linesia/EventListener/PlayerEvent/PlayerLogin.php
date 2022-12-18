<?php

namespace Linesia\EventListener\PlayerEvent;

use JsonException;
use Linesia\Core;
use Linesia\Player\LinesiaPlayer;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerLoginEvent;

class PlayerLogin implements Listener {

    /**
     * @throws JsonException
     */
    public function onPlayerLogin(PlayerLoginEvent $event){
        $sender = $event->getPlayer();

        if ($sender instanceof LinesiaPlayer) {

            /* Rank */
            if (!Core::getInstance()->getRankAPI()->existRank("Joueur")) {
                Core::getInstance()->getRankAPI()->addRank("Joueur");
            }
            if (!Core::getInstance()->getRankAPI()->existPlayer($sender)) {
                Core::getInstance()->getRankAPI()->setRank($sender, "Joueur");
                Core::getInstance()->getRankAPI()->setClassPlayer($sender, "§gBronze I");
            }
            Core::getInstance()->getRankAPI()->registerPlayer($sender);
            Core::getInstance()->getRankAPI()->updateNametag($sender);
        }
    }

}
<?php

namespace Linesia\EventListener\PlayerEvent;

use Linesia\Player\LinesiaPlayer;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerCreationEvent;

class PlayerCreation implements Listener {

    public function onPlayerCreation(PlayerCreationEvent $event){
        $event->setPlayerClass(LinesiaPlayer::class);
    }

}
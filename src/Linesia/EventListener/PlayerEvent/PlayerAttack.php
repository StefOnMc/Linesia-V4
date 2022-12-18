<?php

namespace Linesia\EventListener\PlayerEvent;

use Linesia\Player\LinesiaPlayer;
use Linesia\Utils\Utils;
use pocketmine\event\entity\EntityDamageByEntityEvent;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerCommandPreprocessEvent;

class PlayerAttack implements Listener {

    public static array $cooldown_combat;

    public function onEntityDamageByEntity(EntityDamageByEntityEvent $event){
        $sender = $event->getDamager();
        $player = $event->getEntity();

        if ($sender instanceof LinesiaPlayer && $player instanceof LinesiaPlayer){
            $event->setKnockback(0.39);
            $event->setAttackCooldown(9.52);

        }
    }

    public function onCommandExecute(PlayerCommandPreprocessEvent $event){
        $message = $event->getMessage();
        $sender = $event->getPlayer();

        $msg = explode(' ', trim($message));
        $m = substr("$message", 0, 1);
        $whitespace_check = substr($message,1,1);
        $slash_check = substr($msg[0], -1, 1);
        $quote_mark_check = substr($message, 1,1) . substr($message, -1, 1);
        if ($m == '/'){
            if ($whitespace_check === ' ' or $whitespace_check === '\\' or $slash_check === '\\' or $quote_mark_check === '""'){
                $event->cancel();
                $sender->sendMessage(Utils::getPrefix() . "§cMerci de ne pas mettre d'espace dans votre commande.");
            }
        }
    }

}
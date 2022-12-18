<?php

namespace Linesia\AntiCheat;

use Linesia\Core;
use Linesia\Utils\Utils;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerKickEvent;
use pocketmine\network\mcpe\protocol\AdventureSettingsPacket;

class AntiFly implements Listener {

    public function onFly(PlayerKickEvent $event) {
        $sender = $event->getPlayer();
        if($event->getReason() == AdventureSettingsPacket::FLYING) {
            Core::getInstance()->getServer()->broadcastMessage("§cSanction §l§7» §r§9" . $sender->getName() . " §fvient de se faire expulser du serveur par §cAnti§cCheat §fpour le motif §9Fly§f !");
            Utils::sendDiscordLogs($sender->getName() . " vient de se faire expulser du serveur par AntiCheat pour le motif Fly", "**ANTI-CHEAT**", 0xB200FF);
            $event->setQuitMessage("[§c§l!§r] Vous avez été kick de §9Linésia §fpar §cAnti§f-§cCheat §fpour le motif §9Fly§f.");

        }

    }

}
<?php

namespace Linesia\AntiCheat;

use Linesia\Core;
use Linesia\Utils\Utils;
use pocketmine\event\block\BlockBreakEvent;
use pocketmine\event\Listener;
use pocketmine\player\GameMode;

class AntiInstantBreak implements Listener {

    public function onBreak(BlockBreakEvent $event) {

        $player = $event->getPlayer();

        if($event->getInstaBreak() && $player->getGamemode() === GameMode::SURVIVAL()) {

            $event->cancel();
            Core::getInstance()->getServer()->broadcastMessage("§cSanction §l§7» §r§9" . $player->getName() . " §fvient de se faire expulser du serveur par §cAnti§cCheat §fpour le motif §9Instant§f-§9Break§f !");
            Utils::sendDiscordLogs($player->getName() . " vient de se faire expulser du serveur par AntiCheat pour le motif Instant-Break", "**ANTI-CHEAT**", 0xB200FF);
            $player->kick("[§c§l!§r] Vous avez été kick de §9Linésia §fpar §cAnti§f-§cCheat §fpour le motif §9Instant§f-§9Break§f.");

        }

    }

}
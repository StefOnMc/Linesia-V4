<?php

namespace Linesia\Command\Moderation;

use JsonException;
use Linesia\Core;
use Linesia\Player\LinesiaPlayer;
use Linesia\Utils\Utils;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;

class KickCommand extends Command {

    public function __construct() {
        parent::__construct("kick", "Kick - Linesia", "/kick <player> <raison>.", ["kick"]);
        $this->setPermission(implode(";", [
            "linesia.*",
            "linesia.moderation.kick"
        ]));
    }

    /**
     * @throws JsonException
     */
    public function execute(CommandSender $sender, string $commandLabel, array $args): bool {
        if (!$sender instanceof LinesiaPlayer){
            return true;
        }
        if (!$sender->getPermission("linesia.moderation.kick")){
            $sender->sendMessage(Utils::getPrefix() . "§cVous n'avez pas la permission de faire ceci.");
            return true;
        }
        if (count($args) < 2){
            $sender->sendMessage(Utils::getPrefix() . "Usage : /kick <player> <raison>.");
            return true;
        }

        $player = Core::getInstance()->getServer()->getPlayerByPrefix($args[0]);
        if (!$player instanceof LinesiaPlayer) {
            $sender->sendMessage(Utils::getPrefix() . "§cCe joueur n'est actuellement pas connecté.");
            return true;
        }

        $senderRank = Core::getInstance()->getRankAPI()->getRankPlayer($sender);
        $playerRank = Core::getInstance()->getRankAPI()->getRankPlayer($player);

        if (!Core::getInstance()->getRankAPI()->ClassPlayerByRank($senderRank, $playerRank)){
            $sender->sendMessage(Utils::getPrefix() . "§cVous ne pouvez pas bannir ce joueur.");
            return true;
        }

        $raison = [];
        for ($i = 1; $i < count($args); $i++) {
            $raison[] = $args[$i];
        }
        $raison = implode(" ", $raison);

        Core::getInstance()->getServer()->broadcastMessage("§cSanction §l§7» §r" . Utils::getRankPlayer($player) . " vient de se faire expulser du serveur par " . Utils::getRankPlayer($sender) . " §fpour le motif §9" . $raison . "§f !");
        Utils::sendDiscordLogs($player->getName() . " vient de se faire expulser du serveur par " . $sender->getName() . " pour le motif " . $raison, "**EXPULSION**", 0xFFFF00);
        $PI = Utils::getConfigFile("PlayerInfos/" . $player->getName(), "yml");
        $PI->set("expulsion", $PI->get("expulsion") + 1);
        $PI->save();
        $player->kick("                            §f[§9EXPULSION§f]                            \n              Vous avez été expulsé du serveur.\n              Staff : " . Utils::getRankPlayer($sender) . "\n              §fMotif(s) : §9" . $raison . "§f\n                  §e" . Core::DISCORD, "t");
        return true;
    }

}
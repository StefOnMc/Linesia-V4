<?php

namespace Linesia\Command\Moderation;

use JsonException;
use Linesia\Core;
use Linesia\Player\LinesiaPlayer;
use Linesia\Utils\Utils;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;

class TempBanCommand extends Command {

    public function __construct() {
        parent::__construct("tempban", "TempsBan - Linesia", "/tempsban <player> <temps> <raison>.", ["tempsban"]);
        $this->setPermission(implode(";", [
            "linesia.*",
            "linesia.moderation.tempsban"
        ]));
    }

    /**
     * @throws JsonException
     */
    public function execute(CommandSender $sender, string $commandLabel, array $args): bool {
        if (!$sender instanceof LinesiaPlayer){
            return true;
        }
        if (!$sender->getPermission("linesia.moderation.tempsban")){
            $sender->sendMessage(Utils::getPrefix() . "§cVous n'avez pas la permission de faire ceci.");
            return true;
        }

        if (count($args) < 3) {
            $sender->sendMessage(Utils::getPrefix() . "Usage : /tempsban <player> <temps> <raison>.");
            return true;
        }

        if (!ctype_alnum($args[1])) {
            $sender->sendMessage(Utils::getPrefix() . "Usage : /tempsban <player> <temps> <raison>.");
            return true;
        }

        if ($args[1] > "30j") {

            if (!$sender->getPermission("linesia.administration.tempban")) {

                $sender->sendMessage(Utils::getPrefix() . "§cVous n'avez pas la permission de bannir une personne plus de 30 jours. Merci de contacter un administrateur.");
                return true;
            }
        }

        $val = substr($args[1], -1);
        if ($val == "j") {
            $temp = time() + ((int)$args[1] * 86400);
            $FormatTemp = (int)$args[1] . " jour(s)";
        } else if ($val == "h") {
            $temp = time() + ((int)$args[1] * 3600);
            $FormatTemp = (int)$args[1] . " heure(s)";
        } else if ($val == "m") {
            $temp = time() + ((int)$args[1] * 60);
            $FormatTemp = (int)$args[1] . " minute(s)";
        } else if ($val == "s") {
            $temp = time() + ((int)$args[1]);
            $FormatTemp = (int)$args[1] . " seconde(s)";
        } else {
            $sender->sendMessage(Utils::getPrefix() . "Usage : /tempsban <player> <temps> <raison>.");
            return true;
        }

        $raison = [];
        for ($i = 2; $i < count($args); $i++) {
            $raison[] = $args[$i];
        }
        $raison = implode(" ", $raison);

        $player = Core::getInstance()->getServer()->getPlayerExact($args[0]);

        if ($player instanceof LinesiaPlayer) {
            Core::getInstance()->getServer()->broadcastMessage("§cSanction §l§7» §r" . Utils::getRankPlayer($player) . " §fvient de se faire bannir du serveur par " . Utils::getRankPlayer($sender) . " §fdurant §9" . $FormatTemp . " §fpour le motif §9" . $raison . "§f.");
            $config = Utils::getConfigFile("Sanctions/Bannissement", "json");

            if (!Core::getInstance()->getRankAPI()->ClassPlayerByRank(Core::getInstance()->getRankAPI()->getRankPlayer($sender), Core::getInstance()->getRankAPI()->getRankPlayer($player))){
                $sender->sendMessage(Utils::getPrefix() . "§cVous ne pouvez pas sanctionner cette personne.");
                return true;
            }

            $grade = Utils::getRankPlayer($sender);

            $config->set($player->getName(), "$grade:$temp:$raison");
            $config->save();

            $ban = Utils::getConfigFile("PlayerInfos/" . $player->getName(), "yml");


            $ban->set("bannissmenet", $ban->get("bannissmenet") + 1);
            $ban->save();

            Utils::sendDiscordLogs($player->getName() . " vient de se faire bannir du serveur par " . $sender->getName() . " durant " . $FormatTemp . " pour le motif " . $raison, "**BANNISSEMENT**", 0xC9FF00);
            $player->kick("                            §f[§9BANNISSEMENT§f]                            \n              Staff : " . Utils::getRankPlayer($sender) . "\n              §fTemps : §9" . $FormatTemp . "\n              §fMotif(s) : §9" . $raison . "§f\n                  §e" . Core::DISCORD, "t");
        }else{
            Core::getInstance()->getServer()->broadcastMessage("§cSanction §l§7» §r§9" . $args[0] . " §fvient de se faire bannir du serveur par " . Utils::getRankPlayer($sender) . " §fdurant §9" . $FormatTemp . " §fpour le motif §9" . $raison . "§f.");
            $config = Utils::getConfigFile("Sanctions/Bannissement", "json");

            $grade = Utils::getRankPlayer($sender);

            $config->set($args[0], "$grade:$temp:$raison");
            $config->save();

            Utils::sendDiscordLogs($args[0] . " vient de se faire bannir du serveur par " . $sender->getName() . " durant " . $FormatTemp . " pour le motif " . $raison, "**BANNISSEMENT**", 0xC9FF00);
            $ban = Utils::getConfigFile("PlayerInfos/" . $args[0], "yml");

            if (!$ban->exists("ip")) {
                $sender->sendMessage(Utils::getPrefix() . "§cCe joueur ne s'est jamais connecté sur le serveur !");
                return true;
            }
            $ban->set("bannissmenet", $ban->get("bannissmenet") + 1);
            $ban->save();
        }
        return true;
    }

}
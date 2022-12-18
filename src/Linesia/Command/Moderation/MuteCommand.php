<?php

namespace Linesia\Command\Moderation;

use JsonException;
use Linesia\Core;
use Linesia\Player\LinesiaPlayer;
use Linesia\Utils\Utils;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;

class MuteCommand extends Command {

    public function __construct() {
        parent::__construct("mute", "Mute - Linesia", "/mute <player> <temps> <raison>.", ["mute"]);
        $this->setPermission(implode(";", [
            "linesia.*",
            "linesia.moderation.mute"
        ]));
    }

    /**
     * @throws JsonException
     */
    public function execute(CommandSender $sender, string $commandLabel, array $args): bool {
        if (!$sender instanceof LinesiaPlayer){
            return true;
        }
        if (!$sender->getPermission("linesia.moderation.mute")){
            $sender->sendMessage(Utils::getPrefix() . "§cVous n'avez pas la permission de faire ceci.");
            return true;
        }

        if (count($args) < 3){
            $sender->sendMessage(Utils::getPrefix() . "Usage : /mute <player> <temps> <raison>.");
            return true;
        }
        if (!ctype_alnum($args[1])){
            $sender->sendMessage(Utils::getPrefix() . "Usage : /mute <plauyer> <temps> <raison>.");
            return true;
        }
        $val = substr($args[1], -1);
        if ($val == "j") {
            $temp = time() + ((int)$args[1] * 86400);
            $FormatTemp = (int)$args[1] . " jour(s).";
        } else if ($val == "h") {
            $temp = time() + ((int)$args[1] * 3600);
            $FormatTemp = (int)$args[1] . " heure(s).";
        } else if ($val == "m") {
            $temp = time() + ((int)$args[1] * 60);
            $FormatTemp = (int)$args[1] . " minute(s).";
        } else if ($val == "s") {
            $temp = time() + ((int)$args[1]);
            $FormatTemp = (int)$args[1] . " seconde(s).";
        } else {
            $sender->sendMessage(Utils::getPrefix() . "Usage : /mute <plauyer> <temps> <raison>.");
            return true;
        }

        $raison = [];
        for ($i = 2; $i < count($args); $i++) {
            $raison[] = $args[$i];
        }
        $raison = implode(" ", $raison);
        $player = Core::getInstance()->getServer()->getPlayerByPrefix($args[0]);
        if (!$player instanceof LinesiaPlayer){
            $sender->sendMessage(Utils::getPrefix() . "Ce joueur n'est pas connecté.");
            return true;
        }

        if (!Core::getInstance()->getRankAPI()->ClassPlayerByRank(Core::getInstance()->getRankAPI()->getRankPlayer($sender), Core::getInstance()->getRankAPI()->getRankPlayer($player))){
            $sender->sendMessage(Utils::getPrefix() . "§cVous ne pouvez pas sanctionner cette personne.");
            return true;
        }

        Core::getInstance()->getServer()->broadcastMessage("§cSanction §l§7» §r" . Utils::getRankPlayer($player) . " §fvient de se faire mute par " . Utils::getRankPlayer($sender) . " §fdurant §c" . $FormatTemp . " §fpour le motif :§c " . $raison . "§f !");
        $config = Utils::getConfigFile("Sanctions/Mute", "json");
        $grade = Utils::getRankPlayer($sender);
        $config->set($player->getName(), "$grade:$temp:$raison");
        $config->save();
        $playerI = Utils::getConfigFile("PlayerInfos/" . $player->getName(), "yml");
        $playerI->set("mute", $playerI->get("mute") + 1);
        $playerI->save();

        Utils::sendDiscordLogs($player->getName() . " vient de se faire mute par " . $sender->getName() . " durant " . $FormatTemp . " pour le motif " . $raison . ".", "**MUTE**", 0xFF7800);
        return true;
    }

}
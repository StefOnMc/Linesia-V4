<?php

namespace Linesia\Command\Administration;

use Linesia\API\PlayTimeAPI;
use Linesia\Core;
use Linesia\Player\LinesiaPlayer;
use Linesia\Utils\Utils;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;

class AdminInfosCommand extends Command {

    private array $admin = ["Theruser", "Julien8436", "Faunejojo34", "Twitchzeiweb"];

    public function __construct() {
        parent::__construct("admininfo", "AdminInfo - Linesia", "/admininfo", ["admininfos"]);
        $this->setPermission("linesia.*");
    }

    public function execute(CommandSender $sender, string $commandLabel, array $args): bool {
        if (!$sender instanceof LinesiaPlayer){
            return true;
        }
        if (!in_array($sender->getName(), $this->admin)){
            $sender->sendMessage(Utils::getPrefix()  . "§cVous n'avez pas la permission de faire ceci.");
            return true;
        }

        if (count($args) < 1){
            $sender->sendMessage(Utils::getPrefix() . "Usage : /admininfo <player>.");
        }

        $player = Core::getInstance()->getServer()->getPlayerByPrefix($args[0]);
        if (!$player instanceof LinesiaPlayer){
            $sender->sendMessage(Utils::getPrefix() . "§cCe joueur n'est actuellement pas connecté.");
            return true;
        }

        $config = Utils::getConfigFile("PlayerInfos/" . $player->getName(), "yml");
        $money = Utils::getConfigFile("Stats/Money", "json");
        $kill = Utils::getConfigFile("Stats/Kill", "json");
        $sender->sendMessage(Utils::getPrefix() . "Voici les informations de " . Utils::getRankPlayer($player) . "§f :");
        $sender->sendMessage(" ");
        $sender->sendMessage("Nom : §9" . $player->getName());
        $sender->sendMessage("Grade : " . Core::getInstance()->getRankAPI()->getRankColor($player));
        $sender->sendMessage("Rank : " . Core::getInstance()->getRankAPI()->getClassPlayer($player));
        $sender->sendMessage(" ");
        $sender->sendMessage("XUID : §9" . $player->getXuid());
        $sender->sendMessage("CID : §9" . $player->getUniqueId());
        $sender->sendMessage("Ip : §9" . $config->get("ip"));
        $sender->sendMessage("");
        $sender->sendMessage("Première connection : §9" . $config->get("first_connexion"));
        $sender->sendMessage("Temps de jeu : §9" . PlayTimeAPI::playTime($player->getName()));
        $sender->sendMessage(" ");
        $sender->sendMessage("Argent : §9" . $money->get($player->getName()) . "$");
        $sender->sendMessage("Kill : §9" . $kill->get($player->getName()));
        $sender->sendMessage(" ");
        $sender->sendMessage("§l§c» §rCes informations sont privées, merci donc de ne pas les divulguer.");
        Utils::sendDiscordLogs($sender->getName() . " vient d'utiliser le /admininfo sur " . $player->getName(), "**ADMININFO**", 0x320546);
        return true;
    }

}
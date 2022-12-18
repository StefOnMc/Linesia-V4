<?php

namespace Linesia\Command\Administration;

use Linesia\Core;
use Linesia\Player\LinesiaPlayer;
use Linesia\Utils\Utils;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;

class RanksCommand extends Command {

    public function __construct() {
        parent::__construct("ranks", "Ranks - Linesia", "/rank", ["rank"]);
        $this->setPermission(implode(";", [
            "linesia.*",
            "linesia.administration.ranks"
        ]));
    }

    public function execute(CommandSender $sender, string $commandLabel, array $args): bool {
        if (!$sender instanceof LinesiaPlayer){
            return true;
        }
        if (!$sender->getPermission("linesia.administration.ranks")){
            $sender->sendMessage(Utils::getPrefix() . "§cVous n'avez pas la permission de faire ceci.");
            return true;
        }
        $rank = Core::getInstance()->getRankAPI()->getAllRank();
        $rankColor = "§9";
        foreach ($rank as $ranks => $format){
            $rankColor .= "§9" . $ranks;
            $rankColor .= "§7, ";
        }
        $sender->sendMessage(Utils::getPrefix() . "Voici la liste des grades présent sur le serveur : " . $rankColor . "§f.");
        return true;
    }
}
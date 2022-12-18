<?php

namespace Linesia\Command\Administration;

use JsonException;
use Linesia\Core;
use Linesia\Player\LinesiaPlayer;
use Linesia\Utils\Utils;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;

class SetFormatCommand extends Command {

    public function __construct() {
        parent::__construct("setformat", "SetFormat - Linesia", "/setformat <rank> <format>", ["setformat"]);
        $this->setPermission(implode(";", [
            "linesia.*",
            "linesia.administration.setformat"
        ]));
    }

    /**
     * @throws JsonException
     */
    public function execute(CommandSender $sender, string $commandLabel, array $args): bool {
        if (!$sender instanceof LinesiaPlayer){
            return true;
        }
        if (!$sender->getPermission("linesia.administration.setformat")){
            $sender->sendMessage(Utils::getPrefix() . "§cVous n'avez pas la permission de faire ceci.");
            return true;
        }

        if (count($args) < 2){
            $sender->sendMessage(Utils::getPrefix() . "Usage : /setformat <rank> <format>.");
            return true;
        }

        if (!Core::getInstance()->getRankAPI()->existRank($args[0])){
            $sender->sendMessage(Utils::getPrefix() . "§cCe grade n'existe pas.");
            return true;
        }
        $rank = $args[0];
        $format = "";
        for ($i = 1; $i < count($args); $i++) {
            $format .= $args[$i];
            $format .= " ";
        }
        Core::getInstance()->getRankAPI()->setFormat($rank, $format);
        $sender->sendMessage(Utils::getPrefix() . "Vous venez de modifier le format du grade " . Core::getInstance()->getRankAPI()->getRankListColor($rank) . " §fen §9" . $format . "§f.");
        return true;
    }

}
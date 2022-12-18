<?php

namespace Linesia\Command\Administration;

use JsonException;
use Linesia\Core;
use Linesia\Player\LinesiaPlayer;
use Linesia\Utils\Utils;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;

class AddRankCommand extends Command {

    public function __construct() {
        parent::__construct("addrank", "Addrank - Linesia", "/addrank <rank>", ["addrank"]);
        $this->setPermission(implode(";", [
            "linesia.*",
            "linesia.administration.addrank"
        ]));
    }

    /**
     * @throws JsonException
     */
    public function execute(CommandSender $sender, string $commandLabel, array $args): bool {
        if (!$sender instanceof LinesiaPlayer){
            return true;
        }

        if (!$sender->getPermission("linesia.administration.addrank")){
            $sender->sendMessage(Utils::getPrefix() . "§cVous n'avez pas la permission de faire ceci.");
            return true;
        }

        if (count($args) < 1){
            $sender->sendMessage(Utils::getPrefix() . "Usage : /addrank <rank>.");
            return true;
        }
        if (Core::getInstance()->getRankAPI()->existRank($args[0])){
            $sender->sendMessage(Utils::getPrefix() . "§cCe grade existe déjà.");
            return true;
        }
        Core::getInstance()->getRankAPI()->addRank($args[0]);
        $sender->sendMessage(Utils::getPrefix() . "Vous avez bien ajouté le grade §9" . $args[0] . "§f.");
        return true;
    }

}
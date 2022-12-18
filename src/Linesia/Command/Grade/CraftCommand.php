<?php

namespace Linesia\Command\Grade;

use Linesia\Player\LinesiaPlayer;
use Linesia\Utils\Utils;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;

class CraftCommand extends Command {

    public function __construct() {
        parent::__construct("craft", "Craft - Linesia", "/craft", ["craft"]);
        $this->setPermission(implode(";", [
            "linesia.*",
            "linesia.grade.craft"
        ]));
    }

    public function execute(CommandSender $sender, string $commandLabel, array $args): bool {
        if (!$sender instanceof LinesiaPlayer){
            return true;
        }
        if (!$sender->getPermission("linesia.grade.craft")){
            $sender->sendMessage(Utils::getPrefix() . "§cVous n'avez pas la permission de faire ceci.");
            return true;
        }

        $sender->sendMessage(Utils::getPrefix() . "§cLe /craft est actuellement désactivé.");
        return true;
    }

}
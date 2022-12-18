<?php

namespace Linesia\Command\Administration;

use JsonException;
use Linesia\Core;
use Linesia\Player\LinesiaPlayer;
use Linesia\Utils\Utils;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;

class RemovePermissionCommand extends Command {

    public function __construct() {
        parent::__construct("removepermission", "RemovePermission - Linesia", "/removepermission <rank> <permission>", ["rmpermission"]);
        $this->setPermission(implode(";", [
            "linesia.*",
            "linesia.administration.removepermission"
        ]));
    }

    /**
     * @throws JsonException
     */
    public function execute(CommandSender $sender, string $commandLabel, array $args): bool {
        if (!$sender instanceof LinesiaPlayer){
            return true;
        }
        if (!$sender->getPermission("linesia.administration.removepermission")){
            $sender->sendMessage(Utils::getPrefix() . "§cVous n'avez pas la permission de faire ceci.");
            return true;
        }
        if (count($args) < 2){
            $sender->sendMessage(Utils::getPrefix() . "Usage: /removepermission <rank> <permission>.");
            return true;
        }
        if (!Core::getInstance()->getRankAPI()->existRank($args[0])){
            $sender->sendMessage(Utils::getPrefix() . "§cCe grade n'existe pas. Faites /ranks pour avoir la liste des grades.");
            return true;
        }
        Core::getInstance()->getRankAPI()->rmPermissions($args[0], $args[1]);
        $sender->sendMessage(Utils::getPrefix() . "Vous avez bien retirer la permission §9" . $args[1] . " §fau grade " . Core::getInstance()->getRankAPI()->getRankListColor($args[0]) ."§f.");
        return true;
    }

}
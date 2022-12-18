<?php

namespace Linesia\Command\Administration;

use Linesia\Core;
use Linesia\Player\LinesiaPlayer;
use Linesia\Utils\Utils;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;

class PermissionListCommand extends Command {

    public function __construct() {
        parent::__construct("permissionlist", "PermissionList - Linesia", "/permissionlist <rank>", ["listperms"]);
        $this->setPermission(implode(";", [
            "linesia.*",
            "linesia.administration.permissionlist"
        ]));
    }
    public function execute(CommandSender $sender, string $commandLabel, array $args): bool {
        if (!$sender instanceof LinesiaPlayer){
            return true;
        }
        if (!$sender->getPermission("linesia.administration.permissionlist")){
            $sender->sendMessage(Utils::getPrefix() . "§cVous n'avez pas la permission de faire ceci.");
            return true;
        }
        if (count($args) < 1){
            $sender->sendMessage(Utils::getPrefix() . "Usage : /permissionlist <rank>.");
            return true;
        }
        if (!Core::getInstance()->getRankAPI()->existRank($args[0])){
            $sender->sendMessage(Utils::getPrefix() . "§cCe grade n'existe pas.");
            return true;
        }
        if (Core::getInstance()->getRankAPI()->getAllPerms($args[0]) == array()){
            $permFormat = "§9Aucune";
        }else{
            $permFormat =  implode("§7, §9", Core::getInstance()->getRankAPI()->getAllPerms($args[0]));
        }
        $sender->sendMessage(Utils::getPrefix() . "Voici toutes les permissions du grade §9" . $args[0] . " §f: §9" . $permFormat ."§f.");
        return true;
    }
}
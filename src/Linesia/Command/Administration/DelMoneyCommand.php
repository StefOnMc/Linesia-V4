<?php

namespace Linesia\Command\Administration;

use JsonException;
use Linesia\API\EconomyAPI;
use Linesia\Core;
use Linesia\Player\LinesiaPlayer;
use Linesia\Utils\Utils;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;

class DelMoneyCommand extends Command {

    public function __construct() {
        parent::__construct("delmoney", "DelMoney - Linesia", "/delmoney <player> <monant>", ["delmoney"]);
        $this->setPermission(implode(";", [
            "linesia.*",
            "linesia.administration.addmoney"
        ]));
    }

    /**
     * @throws JsonException
     */
    public function execute(CommandSender $sender, string $commandLabel, array $args): bool {
        if (!$sender instanceof LinesiaPlayer){
            return true;
        }
        if (!$sender->getPermission("linesia.administration.addmoney")){
            $sender->sendMessage(Utils::getPrefix() . "§cVous n'avez pas la permission de faire ceci.");
            return true;
        }
        if (count($args) < 2){
            $sender->sendMessage(Utils::getPrefix() . "Usage : /delmoney <player> <money>.");
            return true;
        }
        $player = Core::getInstance()->getServer()->getPlayerByPrefix($args[0]);
        if (!$player instanceof LinesiaPlayer){
            $sender->sendMessage(Utils::getPrefix() . "§cCe joueur n'est actuellement pas connecté.");
            return true;
        }
        if (!is_numeric($args[1])){
            $sender->sendMessage(Utils::getPrefix() . "§cMerci de mettre un nombre valide.");
            return true;
        }
        EconomyAPI::getInstance()->deleteMoney($sender, $args[1]);
        $sender->sendMessage(Utils::getPrefix() . "Vous avez bien supprimé §9" . $args[1] . "$ §fà " . Utils::getRankPlayer($player) ."§f.");
        $player->sendMessage(Utils::getPrefix() . Utils::getRankPlayer($sender) . " §fvient de vous supprimer §9" . $args[1] . "$ §f.");
        Utils::sendDiscordLogs($sender->getName() . " vient de supprimer " . $args[1] . "$ à $player", "**DELMONEY**", 0x89EE01);
        return true;
    }

}
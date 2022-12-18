<?php

namespace Linesia\Command\Administration;

use JsonException;
use Linesia\Core;
use Linesia\Player\LinesiaPlayer;
use Linesia\Utils\Utils;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;

class SetRankCommand extends Command {

    public function __construct() {
        parent::__construct("setrank", "SetRank - Linesia", "/setrank <player> <rank>.", ["setrank"]);
        $this->setPermission(implode(";", [
            "linesia.*",
            "linesia.administration.setrank"
        ]));
    }

    /**
     * @throws JsonException
     */
    public function execute(CommandSender $sender, string $commandLabel, array $args): bool {
        if (!$sender instanceof LinesiaPlayer){
            return true;
        }

        if (!$sender->getPermission("linesia.administration.setrank")){
            $sender->sendMessage(Utils::getPrefix() . "§cVous n'avez pas la permission de faire ceci.");
            return true;
        }
        if (count($args ) < 1){
            $sender->sendMessage(Utils::getPrefix() . "Usage : /setrank <player> <rank>.");
            return true;
        }
        $player = Core::getInstance()->getServer()->getPlayerByPrefix($args[0]);
        if (!$player instanceof LinesiaPlayer){
            $sender->sendMessage(Utils::getPrefix() . "§cCe joueur n'est actuellement pas connecté.");
            return true;
        }
        if (!Core::getInstance()->getRankAPI()->existRank($args[1])){
            $sender->sendMessage(Utils::getPrefix() . "§cCe grade n'existe pas.");
            return true;
        }
        $grade = ["Mini-VIP", "VIP", "Roi"];
        if (!in_array($args[1], $grade)){
            Core::getInstance()->getServer()->broadcastMessage("[§c§l!§r] " . Utils::getRankPlayer($player) . " §fa été promus au grade de " . Core::getInstance()->getRankAPI()->getRankListColor($args[1]) . "§f !");
        }else{
            Core::getInstance()->getServer()->broadcastMessage("[§c§l!§r] " . Utils::getRankPlayer($player) . " §fvient d'acheter le grade " . Core::getInstance()->getRankAPI()->getRankListColor($args[1]) . "§f !");
        }
        Core::getInstance()->getRankAPI()->setRank($player, $args[1]);
        Utils::sendDiscordLogs($sender->getName() . " vient de mettre le grade de $args[1] à " . $player->getName(), "**SETRANK**", 0x04B489);
        return true;
    }

}
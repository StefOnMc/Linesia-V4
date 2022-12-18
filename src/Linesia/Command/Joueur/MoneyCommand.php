<?php

namespace Linesia\Command\Joueur;

use Linesia\API\EconomyAPI;
use Linesia\Core;
use Linesia\Player\LinesiaPlayer;
use Linesia\Utils\Utils;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;

class MoneyCommand extends Command {

    public function __construct()
    {
        parent::__construct("money", "Money - Linesia", "/money <player>", ["money"]);
    }

    public function execute(CommandSender $sender, string $commandLabel, array $args): bool {
        if (!$sender instanceof LinesiaPlayer){
            return true;
        }
        if (count($args) < 1){
            $sender->sendMessage(Utils::getPrefix() . "Vous avez actuellement §9" . EconomyAPI::getInstance()->getMoney($sender) . "$ §f.");
        }else{
            $player = Core::getInstance()->getServer()->getPlayerByPrefix($args[0]);
            if (!$player instanceof LinesiaPlayer){
                $sender->sendMessage(Utils::getPrefix() . "§cCe joueur n'est actuellement pas connecté.");
                return true;
            }
            $sender->sendMessage(Utils::getPrefix() . Utils::getRankPlayer($player) . " §fa actuellement §9" . EconomyAPI::getInstance()->getMoney($player) . "$ §f.");
        }
        return true;
    }

}
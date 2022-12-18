<?php

namespace Linesia\Command\Joueur;

use JsonException;
use Linesia\API\EconomyAPI;
use Linesia\Core;
use Linesia\Player\LinesiaPlayer;
use Linesia\Utils\Utils;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;

class PayCommand extends Command {

    public function __construct() {
        parent::__construct("pay", "Pay - Linesia", "/pay <player> <montant>", ["pay"]);
    }

    /**
     * @throws JsonException
     */
    public function execute(CommandSender $sender, string $commandLabel, array $args): bool {
        if (!$sender instanceof LinesiaPlayer){
            return true;
        }
        if (count($args) < 2){
            $sender->sendMessage(Utils::getPrefix() . "Usage : /pay <player> <montant>.");
            return true;
        }
        $player = Core::getInstance()->getServer()->getPlayerByPrefix($args[0]);
        if (!$player instanceof LinesiaPlayer){
            $sender->sendMessage(Utils::getPrefix() . "§cCe joueur n'est actuellement pas connecté.");
            return true;
        }

        if (is_numeric($args[1]) && (int)$args[1] >= 0) {
            if (Utils::getConfigFile("Stats/Money", "json")->get($sender->getName()) >= $args[1]) {
                EconomyAPI::getInstance()->addMoneyOnline($player, $args[1]);
                EconomyAPI::getInstance()->deleteMoney($sender, $args[1]);
                $sender->sendMessage(Utils::getPrefix() . "Vous avez envoyé §9" . $args[1] . "$ §fà " . Utils::getRankPlayer($player) . "§f.");
                $player->sendMessage(Utils::getPrefix() . "Vous avez reçu §9" . $args[1] . "§f de la part de " . Utils::getRankPlayer($sender) . "§f.");
            }else{
                $sender->sendMessage(Utils::getPrefix() . "Vous n'avez pas assez d'argent pour faire ceci.");
            }
        }else{
            $sender->sendMessage(Utils::getPrefix() . "Merci de mettre un nombre valide.");
        }
        return true;
    }

}
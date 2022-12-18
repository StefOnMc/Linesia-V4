<?php

namespace Linesia\Command\Joueur;

use Linesia\API\EconomyAPI;
use Linesia\API\PlayTimeAPI;
use Linesia\Core;
use Linesia\Player\LinesiaPlayer;
use Linesia\Utils\Utils;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\utils\Config;

class StatsCommand extends Command {

    public function __construct() {
        parent::__construct("stats", "Stats - Linesia", "/stats <player>", ["stat"]);
    }

    public function execute(CommandSender $sender, string $commandLabel, array $args): bool {
        if (!$sender instanceof LinesiaPlayer){
            return true;
        }
        if (count($args) < 1){
            $config = Utils::getConfigFile("PlayerInfos/" . $sender->getName(), "yml");
            $kill = Utils::getConfigFile("Stats/Kill", "json");

            $sender->sendMessage(Utils::getPrefix() . "Voici vos statistiques sur §9Linésia §f:");
            $this->extracted($sender, $sender, $config, $kill);
        }else{
            $player = Core::getInstance()->getServer()->getPlayerByPrefix($args[0]);

            if ($player instanceof LinesiaPlayer) {

                $config = Utils::getConfigFile("PlayerInfos/" . $player->getName(), "yml");
                $kill = Utils::getConfigFile("Stats/Kill", "json");

                $sender->sendMessage(Utils::getPrefix() . "Voici la liste des statistiques de " . Utils::getRankPlayer($player) . " §fsur §9Linésia §f:");
                $this->extracted($sender, $player, $config, $kill);
            }
        }
        return true;
    }

    /**
     * @param LinesiaPlayer $sender
     * @param LinesiaPlayer $player
     * @param Config $config
     * @param Config $kill
     * @return void
     */
    public function extracted(LinesiaPlayer $sender, LinesiaPlayer $player, Config $config, Config $kill): void
    {
        $sender->sendMessage(" ");
        $sender->sendMessage("Nom : §9" . $player->getName());
        $sender->sendMessage("Grade : " . Core::getInstance()->getRankAPI()->getRankColor($player));
        $sender->sendMessage("Rank : " . Core::getInstance()->getRankAPI()->getClassPlayer($player));
        $sender->sendMessage("Os : §9" . $config->get("os"));
        $sender->sendMessage(" ");
        $sender->sendMessage("Première connexion : §9" . $config->get("first_connexion"));
        $sender->sendMessage("Temps de jeu : §9" . PlayTimeAPI::playTime($player->getName()));
        $sender->sendMessage(" ");
        $sender->sendMessage("Faction : §9Aucune");
        $sender->sendMessage("Argent : §9" . EconomyAPI::getInstance()->getMoney($player));
        $sender->sendMessage("Kill : §9" . $kill->get($player->getName()));
        $sender->sendMessage(" ");
    }

}
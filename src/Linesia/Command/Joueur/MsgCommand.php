<?php

namespace Linesia\Command\Joueur;

use Linesia\API\StaffModeAPI;
use Linesia\Core;
use Linesia\Player\LinesiaPlayer;
use Linesia\Utils\Utils;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;

class MsgCommand extends Command {

    public static array $lastMessage;

    public function __construct() {
        parent::__construct("msg", "Msg - Linesia", "/msg <player> <message>.", ["m", "tell", "w"]);
    }

    public function execute(CommandSender $sender, string $commandLabel, array $args): bool {
        if (!$sender instanceof LinesiaPlayer){
            $grade = "§cAdministrateur §f- §cConsole";
        }else{
            $sender->sendMessage(Utils::getPrefix() . "§cLes messages privés sont actuellement désactivé. Merci de rester patient.");
            $grade = Utils::getRankPlayer($sender);
            return true;
        }

        if (Utils::getConfigFile("Sanctions/Mute", "json")->exists($sender->getName())){
            $sender->sendMessage(Utils::getPrefix() . "§cVous ne pouvez pas envoyer de message en étant mute.");
            return true;
        }

        if (count($args) < 2){
            $sender->sendMessage(Utils::getPrefix() . "Usage : /msg <player> <message>§f.");
            return true;
        }
        $player = Core::getInstance()->getServer()->getPlayerByPrefix($args[0]);

        if (!$player instanceof LinesiaPlayer){
            $sender->sendMessage(Utils::getPrefix() . "Ce joueur n'est actuellement pas connecté.");
            return true;
        }

        if (StaffModeAPI::getVanish($player)){
            if (!$sender->hasPermission("linesia.*")){
                $sender->sendMessage(Utils::getPrefix() . "Ce joueur n'est actuellement pas connecté.");
                return true;
            }
        }

        $message = [];
        for ($i = 1; $i < count($args); $i++) {
            $message[] = $args[$i];
        }
        $message = implode(" ", $message);

        $sender->sendMessage("[§9MSG§f] §eVous §f-> " . Utils::getRankPlayer($player) . " §f: " . $message);
        $player->sendMessage("[§9MSG§f] $grade §f-> §eVous §f: " . $message);
        self::$lastMessage[$sender->getName()] = $player->getName();
        self::$lastMessage[$player->getName()] = $sender->getName();
        return true;
    }
}
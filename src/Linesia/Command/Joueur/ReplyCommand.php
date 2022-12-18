<?php

namespace Linesia\Command\Joueur;

use Linesia\Core;
use Linesia\Player\LinesiaPlayer;
use Linesia\Utils\Utils;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;

class ReplyCommand extends Command {

    public function __construct() {
        parent::__construct("r", "Reply -Linesia", "/reply <message>", ["reply"]);
    }

    public function execute(CommandSender $sender, string $commandLabel, array $args): bool {
        if (!$sender instanceof LinesiaPlayer){
            $grade = "§cAdministrateur §f- §cConsole";
        }else{
            $grade = Utils::getRankPlayer($sender);
        }

        if (Utils::getConfigFile("Sanctions/Mute", "json")->exists($sender->getName())){
            $sender->sendMessage(Utils::getPrefix() . "§cVous ne pouvez pas envoyer de message en étant mute.");
            return true;
        }


        if (count($args) < 1){
            $sender->sendMessage(Utils::getPrefix() . "Usage : /r <message>.");
            return true;
        }

        if (empty(MsgCommand::$lastMessage[$sender->getName()])) {
            $sender->sendMessage(Utils::getPrefix() . "§cVous n'avez aucun message récent.");
            return true;
        }

        $message = implode(" " , $args);
        $player = MsgCommand::$lastMessage[$sender->getName()];
        if (Core::getInstance()->getServer()->getPlayerExact($player) == null){
            $sender->sendMessage(Utils::getPrefix() . "§cCe joueur n'est plus connecté.");
        }else {

            $player = Core::getInstance()->getServer()->getPlayerExact($player);
            if ($player instanceof LinesiaPlayer)
            $player->sendMessage("[§9MSG] §f" . $grade . " §f-> §eVous §f: " . $message);
            $sender->sendMessage("[§9MSG§f] §eVous §f-> " . Utils::getRankPlayer($player) . " §f: " . $message);
            return true;
        }
        return true;
    }

}
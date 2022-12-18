<?php

namespace Linesia\Command\Joueur;

/*use Linesia\API\BoxAPI;
use Linesia\Core;*/
use Linesia\Player\LinesiaPlayer;
use Linesia\Utils\Utils;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;

class KeyCommand extends Command {

    public function __construct() {
        parent::__construct("key", "Key - Linesia", "/key <joueur>", ["key"]);
    }

    public function execute(CommandSender $sender, string $commandLabel, array $args): bool {
        if ($sender instanceof LinesiaPlayer){
            $sender->sendMessage(Utils::getPrefix() . "§cLes box sont actuellements désactivés. Merci de rester patient.");
            return true;
        }
        /*if (count($args) < 1){
            $sender->sendMessage(Utils::getPrefix() . "Voici la liste de vos clés :");
            $sender->sendMessage(" ");
            $sender->sendMessage("- §aVote §f: §9" . BoxAPI::getInstance()->getKey($sender, "BoxVote"));
            $sender->sendMessage("- §dAméthyste §f: §9" . BoxAPI::getInstance()->getKey($sender, "BoxAmethyste"));
            $sender->sendMessage("- §5Améthyste Renforcé §f: §9" . BoxAPI::getInstance()->getKey($sender, "BoxAmethysteRenfo"));
        }else{
            $player = Core::getInstance()->getServer()->getPlayerByPrefix($args[0]);
            if (!$player instanceof LinesiaPlayer){
                $sender->sendMessage(Utils::getPrefix() . "§cCe joueur n'est actuellement pas connecté.");
                return true;
            }
            $sender->sendMessage(Utils::getPrefix() . "Voici la liste des clés de " . Utils::getRankPlayer($player) ." §r:");
            $sender->sendMessage(" ");
            $sender->sendMessage("- §aVote §f: §9" . BoxAPI::getInstance()->getKey($player, "BoxVote"));
            $sender->sendMessage("- §dAméthyste §f: §9" . BoxAPI::getInstance()->getKey($player, "BoxAmethyste"));
            $sender->sendMessage("- §5Améthyste Renforcé §f: §9" . BoxAPI::getInstance()->getKey($player, "BoxAmethysteRenfo"));
        }
        $sender->sendMessage(" ");
        return true;*/
        return true;
    }
}
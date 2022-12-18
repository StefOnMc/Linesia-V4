<?php

namespace Linesia\Command\Moderation;

use Linesia\Player\LinesiaPlayer;
use Linesia\Utils\Utils;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\command\utils\InvalidCommandSyntaxException;
use pocketmine\player\GameMode;

class GamemodeCommand extends Command {

    public function __construct() {
        parent::__construct("gamemode", "Gamemode - Linesia", "/gamemode <mode> <player:optionel>", ["gm"]);
        $this->setPermission(implode(";", [
            "linesia.*",
            "linesia.moderation.gamemode"
        ]));
    }

    public function execute(CommandSender $sender, string $commandLabel, array $args): bool {
        if (!$sender->hasPermission("linesia.moderation.gamemode") or !$sender->hasPermission("linesia.*")){
            $sender->sendMessage(Utils::getPrefix() ."§cVous n'avez pas la permission de faire ceci.");
            return true;
        }

        if (count($args) < 1){
            $sender->sendMessage(Utils::getPrefix() . "Usage : /gamemode <mode> <player:optionel>.");
            return true;
        }

        $gameMode = GameMode::fromString($args[0]);
        if($gameMode === null){
            $sender->sendMessage(Utils::getPrefix() . "§Ce mode de jeu n'existe pas.");
            return true;
        }

        if(isset($args[1])){
            $target = $sender->getServer()->getPlayerByPrefix($args[1]);
            if($target === null){
                $sender->sendMessage(Utils::getPrefix() . "§cCe joueur n'est actuellement pas connecté.");

                return true;
            }
        }elseif($sender instanceof LinesiaPlayer){
            $target = $sender;
        }else{
            throw new InvalidCommandSyntaxException();
        }

        $target->setGamemode($gameMode);
        if(!$gameMode->equals($target->getGamemode())){
            $sender->sendMessage(Utils::getPrefix() . "Erreur.");
        }else{
            $gamemodeName = [
                0 => "Survie",
                1 => "Créatif",
                2 => "Aventure",
                3 => "Spectateur",
                "s" => "Survie",
                "c" => "Créatif",
                "a" => "Aventure",
                "v" => "Spectateur",
                "view" => "Spectateur",
                "survival" => "Survie",
                "creatif" => "Créatif",
                "spectator" => "Spectateur",
                "adventure" => "Aventure",
                "Survival" => "Survie",
                "Creatif" => "Créatif",
                "Adventure" => "Aventure",
                "Spectator" => "Spectateur"
            ];
            if($target === $sender){
                $sender->sendMessage(Utils::getPrefix() . "Votre mode de jeu a été changé en §9" . $gamemodeName[$args[0]] . "§f.");
            }else{
                $target->sendMessage(Utils::getPrefix() . "Votre mode de jeu a été changé en §9" . $gamemodeName[$args[0]] . "§f.");
            }
        }

        return true;
    }

}
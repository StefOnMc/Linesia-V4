<?php

namespace Linesia\Command\Joueur;

use Linesia\Core;
use Linesia\Player\LinesiaPlayer;
use Linesia\Utils\Utils;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;

class StaffListeCommand extends Command {

    public function __construct() {
        parent::__construct("stafflist", "StaffList - Linesia", "/stafflist", ["stafflist"]);
    }

    public function execute(CommandSender $sender, string $commandLabel, array $args): bool {
        if (!$sender instanceof LinesiaPlayer){
            return true;
        }

        $sender->sendMessage(Utils::getPrefix() . "Voici la liste des staffs actuel de §9Linésia §f:");
        $sender->sendMessage(" ");
        $sender->sendMessage("§l§7» §r§9Administrateur §f:");
        if (Core::getInstance()->getServer()->getPlayerExact("Theruser") instanceof LinesiaPlayer){
            if (!$sender->canSee(Core::getInstance()->getServer()->getPlayerExact("Theruser"))){
                $sender->sendMessage("- Theruser : §cDéconnecté");
            }else{
                $sender->sendMessage("- Theruser : §aConnecté");
            }
        } else{
            $sender->sendMessage("- Theruser : §cDéconnecté");
        }
        if (Core::getInstance()->getServer()->getPlayerExact("Faunejojo34") instanceof LinesiaPlayer){
            if (!$sender->canSee(Core::getInstance()->getServer()->getPlayerExact("Faunejojo34"))){
                $sender->sendMessage("- Faunejojo34 : §cDéconnecté");
            }else{
                $sender->sendMessage("- Faunejojo34 : §aConnecté");
            }
        } else{
            $sender->sendMessage("- Faunejojo34 : §cDéconnecté");
        }
        $sender->sendMessage(" ");
        $sender->sendMessage("§l§7» §r§4Responsable §f:");
        if (Core::getInstance()->getServer()->getPlayerExact("Twitchzeiweb") instanceof LinesiaPlayer){
            if (!$sender->canSee(Core::getInstance()->getServer()->getPlayerExact("Twitchzeiweb"))){
                    $sender->sendMessage("- Twitchzeiweb : §cDéconnecté");
                }else{
                    $sender->sendMessage("- Twitchzeiweb : §aConnecté");
                }
            } else{
            $sender->sendMessage("- Twitchzeiweb : §cDéconnecté");
        }
        if (Core::getInstance()->getServer()->getPlayerExact("NewTheBuilder") instanceof LinesiaPlayer){
            if (!$sender->canSee(Core::getInstance()->getServer()->getPlayerExact("NewTheBuilder"))){
                $sender->sendMessage("- NewTheBuilder : §cDéconnecté");
            }else{
                $sender->sendMessage("- NewTheBuilder : §aConnecté");
            }
        } else{
            $sender->sendMessage("- NewTheBuilder : §cDéconnecté");
        }
        $sender->sendMessage(" ");
        $sender->sendMessage("§l§7» §r§cSuper§f-§cModérateur §f:");
        if (Core::getInstance()->getServer()->getPlayerExact("Kirito3288") instanceof LinesiaPlayer){
            if (!$sender->canSee(Core::getInstance()->getServer()->getPlayerExact("Kirito3288"))){
                $sender->sendMessage("- Kirito3288 : §cDéconnecté");
            }else{
                $sender->sendMessage("- Kirito3288 : §aConnecté");
            }
        } else{
            $sender->sendMessage("- Kirito3288 : §cDéconnecté");
        }
        if (Core::getInstance()->getServer()->getPlayerExact("iKyozMC") instanceof LinesiaPlayer){
            if (!$sender->canSee(Core::getInstance()->getServer()->getPlayerExact("iKyozMC"))){
                $sender->sendMessage("- iKyozMC : §cDéconnecté");
            }else{
                $sender->sendMessage("- iKyozMC : §aConnecté");
            }
        } else{
            $sender->sendMessage("- iKyozMC : §cDéconnecté");
        }
        $sender->sendMessage(" ");
        $sender->sendMessage("§l§7» §r§gModérateur §f:");
        if (Core::getInstance()->getServer()->getPlayerExact("RETURN LOBBY526") instanceof LinesiaPlayer){
            if (!$sender->canSee(Core::getInstance()->getServer()->getPlayerExact("RETURN LOBBY526"))){
                $sender->sendMessage("- RETURN LOBBY526 : §cDéconnecté");
            }else{
                $sender->sendMessage("- RETURN LOBBY526 : §aConnecté");
            }
        } else{
            $sender->sendMessage("- RETURN LOBBY526 : §cDéconnecté");
        }
        $sender->sendMessage(" ");
        $sender->sendMessage("§l§7» §r§dModérateur§f-§dTest §f:");
        if (Core::getInstance()->getServer()->getPlayerExact("RefluentSeal205") instanceof LinesiaPlayer){
            if (!$sender->canSee(Core::getInstance()->getServer()->getPlayerExact("RefluentSeal205"))){
                $sender->sendMessage("- RefluentSeal205 : §cDéconnecté");
            }else{
                $sender->sendMessage("- RefluentSeal205 : §aConnecté");
            }
        } else{
            $sender->sendMessage("- RefluentSeal205 : §cDéconnecté");
        }
        if (Core::getInstance()->getServer()->getPlayerExact("HycallSurMc") instanceof LinesiaPlayer){
            if (!$sender->canSee(Core::getInstance()->getServer()->getPlayerExact("HycallSurMc"))){
                $sender->sendMessage("- HycallSurMc : §cDéconnecté");
            }else{
                $sender->sendMessage("- HycallSurMc : §aConnecté");
            }
        } else{
            $sender->sendMessage("- HycallSurMc : §cDéconnecté");
        }
        $sender->sendMessage(" ");
        $sender->sendMessage("§l§7» §r§aGuide §f:");
        if (Core::getInstance()->getServer()->getPlayerExact("FelinMc3147") instanceof LinesiaPlayer){
            if (!$sender->canSee(Core::getInstance()->getServer()->getPlayerExact("FelinMc3147"))){
                $sender->sendMessage("- FelinMc3147 : §cDéconnecté");
            }else{
                $sender->sendMessage("- FelinMc3147 : §aConnecté");
            }
        } else{
            $sender->sendMessage("- FelinMc3147 : §cDéconnecté");
        }
        $sender->sendMessage(" ");
        return true;
    }
}
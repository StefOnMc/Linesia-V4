<?php

namespace Linesia\Command\Administration;

use Linesia\Entity\EntityManager;
use Linesia\Player\LinesiaPlayer;
use Linesia\Utils\Utils;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\item\VanillaItems;

class NpcCommand extends Command {

    public function __construct() {
        parent::__construct("npc", "Npc - Linesia", "/npc <spawn> <entity>", ["npc"]);
        $this->setPermission(implode(";", [
            "linesia.*",
            "linesia.administration.npc"
        ]));
    }

    public function execute(CommandSender $sender, string $commandLabel, array $args): bool {
        if (!$sender instanceof LinesiaPlayer){
            return true;
        }
        if (!$sender->getPermission("linesia.administration.npc")){
            $sender->sendMessage(Utils::getPrefix() . "§cVous n'avez pas la permission de faire ceci.");
            return  true;
        }
        if(isset($args[0])) {

            if(strtolower($args[0]) === "spawn") {

                if(!isset($args[1])) {
                    $sender->sendMessage(Utils::getPrefix() . "Merci de mettre une entité.");
                    return true;
                }

                if(strtolower($args[1]) === "human" or strtolower($args[1]) === "humannpc")
                    $entity = EntityManager::getEntityById($sender->getLocation(), $args[1], $sender->getSkin());
                else
                    $entity = EntityManager::getEntityById($sender->getLocation(), $args[1]);

                if(is_null($entity)) {

                    $sender->sendMessage(Utils::getPrefix() . "L'entité §9" . $args[1] . "§f n'existe pas (§9/npc list§f).");
                    return true;

                }

                $entity->setNpc();
                $entity->setCustomName($args[2] ?? $sender->getName());
                $entity->spawnToAll();

                $sender->sendMessage(Utils::getPrefix() . "Vous avez bien fait spawn le npc.");
            }elseif($args[0] === "list"){

            }
        }else{
            $sender->sendMessage(Utils::getPrefix() . "Usage : /npc <spawn/wand> <entité>.");
        }
        return true;
    }

}
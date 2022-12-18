<?php

namespace Linesia\Command\Joueur;

use JsonException;
use Linesia\Core;
use Linesia\Player\LinesiaPlayer;
use Linesia\Utils\Utils;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;

class SpawnCommand extends Command {

    public function __construct() {
        parent::__construct("spawn", "Spawn - Linesia", "/spawn <player>", ["spawn", "hub", "lobby"]);
    }

    /**
     * @throws JsonException
     */
    public function execute(CommandSender $sender, string $commandLabel, array $args): bool {
        if (!$sender instanceof LinesiaPlayer){
            return true;
        }

        if (!file_exists(Core::getInstance()->getDataFolder() . "Spawn.yml")){
            $sender->sendMessage(Utils::getPrefix() . "§cAucun spawn n'a été définis, merci de contacter un Administrateur.");
            return true;
        }

        if (count($args) < 1){
            $sender->teleportToSpawn();
            $sender->sendMessage(Utils::getPrefix() . "Vous avez été téléporté au spawn.");
        }else{
            $player = Core::getInstance()->getServer()->getPlayerByPrefix($args[0]);

            if (!$sender->getPermission("linesia.moderation.spawn")){
                $sender->teleportToSpawn();
                $sender->sendMessage(Utils::getPrefix() . "Vous avez été téléporté au spawn.");
                return true;
            }
            if (!$player instanceof LinesiaPlayer){
                $sender->sendMessage(Utils::getPrefix() . "§cCe joueur n'est actuellement pas connecté.");
                return true;
            }
            $player->teleportToSpawn();
            $sender->sendMessage(Utils::getPrefix() . "Vous avez bien téléporté " . Utils::getRankPlayer($player) . " §fau spawn.");
            $player->sendMessage(Utils::getPrefix() . Utils::getRankPlayer($sender) . "§f vous a téléporté au spawn.");
        }
        return true;
    }
}
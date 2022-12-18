<?php

namespace Linesia\Command\Moderation;

use Linesia\Core;
use Linesia\Player\LinesiaPlayer;
use Linesia\Utils\Utils;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;

class AliasCommand extends Command {

    public function __construct() {
        parent::__construct("alias", "Alias - Linesia", "/alias <player>", ["dc"]);
        $this->setPermission(implode(";", [
            "linesia.*",
            "linesia.moderation.alias"
        ]));
    }
    public function execute(CommandSender $sender, string $commandLabel, array $args): bool
    {
        if (!$sender instanceof LinesiaPlayer){
            return true;
        }
        if (!$sender->getPermission("linesia.moderation.alias")){
            $sender->sendMessage(Utils::getPrefix() . "§cVous n'avez pas la permission de faire ceci.");
            return true;
        }
        if (count($args) < 1){
            $sender->sendMessage(Utils::getPrefix() . "Usage : /alias <player>.");
            return true;
        }
        $player = Core::getInstance()->getServer()->getPlayerByPrefix($args[0]);
        if (!$player instanceof LinesiaPlayer){
            $sender->sendMessage(Utils::getPrefix() . "§cCe joueur n'est pas connecté.");
            return true;
        }
        $ip = Utils::getConfigFile("PlayerInfos/ips", "json");
        $uuid = Utils::getConfigFile("PlayerInfos/uuid", "json");

        $sender->sendMessage(Utils::getPrefix() . "Voici tous les comptes de " . Utils::getRankPlayer($player) . "§f :");
        $countA = 1;
        foreach ($ip->getAll() as $name => $ips){
            if ($ips === $player->getIp()){
                $sender->sendMessage("#$countA §l§9» §f$name §9- §6IP");
                $countA++;
            }
        }
        $sender->sendMessage(" ");
        $countB = 1;
        foreach ($uuid->getAll() as $name => $uuids){
            if ($uuids === $player->getUniqueId()){
                $sender->sendMessage("#$countB §l§9» §f$name §9- §6UUID");
                $countB++;
            }
        }
        return true;
    }
}
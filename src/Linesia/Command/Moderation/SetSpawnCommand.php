<?php

namespace Linesia\Command\Moderation;

use JsonException;
use Linesia\Player\LinesiaPlayer;
use Linesia\Utils\Utils;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;

class SetSpawnCommand extends Command {

    public function __construct() {
        parent::__construct("setspawn", "SetSpawn - Linesia", "/setspawn", ["setspawn"]);
        $this->setPermission(implode(";", [
            "linesia.*",
            "linesia.moderation.setspawn"
        ]));
    }

    /**
     * @throws JsonException
     */
    public function execute(CommandSender $sender, string $commandLabel, array $args): bool {
        if (!$sender instanceof LinesiaPlayer){
            return true;
        }
        if (!$sender->getPermission("linesia.moderation.setspawn")){
            $sender->sendMessage(Utils::getPrefix() . "§cVous n'avez pas la permission de faire ceci.");
            return true;
        }

        $sender->setSpawnServer($sender->getPosition()->getX(), $sender->getPosition()->getY(), $sender->getPosition()->getZ());
        $sender->sendMessage(Utils::getPrefix() . "Vous avez bien définis le spawn du monde.");
        return true;
    }

}
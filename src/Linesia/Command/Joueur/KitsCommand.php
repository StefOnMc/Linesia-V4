<?php

namespace Linesia\Command\Joueur;

use Linesia\API\KitAPI;
use Linesia\Player\LinesiaPlayer;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;

class KitsCommand extends Command {

    public function __construct() {
        parent::__construct("kit", "Kits - Linesia", "/kit", ["kits"]);
    }

    public function execute(CommandSender $sender, string $commandLabel, array $args): bool {
        if (!$sender instanceof LinesiaPlayer){
            return true;
        }
        KitAPI::sendKitForm($sender);
        return true;
    }

}
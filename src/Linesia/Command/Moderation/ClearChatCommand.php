<?php

namespace Linesia\Command\Moderation;

use Linesia\Core;
use Linesia\Player\LinesiaPlayer;
use Linesia\Utils\Utils;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;

class ClearChatCommand extends Command {

    public function __construct() {
        parent::__construct("clearchat", "ClearChat - Linesia", "/clearchat", ["clearchat"]);
        $this->setPermission(implode(";", [
            "linesia.*",
            "linesia.moderation.clearchat"
        ]));
    }
    public function execute(CommandSender $sender, string $commandLabel, array $args) {
        if (!$sender instanceof LinesiaPlayer){
            $grade = "§cAdministrateur §f- §cConsole";
        }else{
            $grade = Utils::getRankPlayer($sender);
        }
        Core::getInstance()->getServer()->broadcastMessage(" \n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n" . Utils::getPrefix() ."§fLe chat a été effacé par $grade §f!");
        Utils::sendDiscordLogs("Le chat vient d'être clear par " . $sender->getName(), "**CLEARCHAT**", 0xDCEE01);
    }
}
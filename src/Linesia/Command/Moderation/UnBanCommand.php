<?php

namespace Linesia\Command\Moderation;

use JsonException;
use Linesia\Player\LinesiaPlayer;
use Linesia\Utils\Utils;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;

class UnBanCommand extends Command {

    public function __construct() {
        parent::__construct("unban", "UnBan - Linesia", "/unban <player>", ["unban"]);
        $this->setPermission(implode(";", [
            "linesia.*",
            "linesia.moderation.unban"
        ]));
    }

    /**
     * @throws JsonException
     */
    public function execute(CommandSender $sender, string $commandLabel, array $args): bool
    {
        if (!$sender instanceof LinesiaPlayer){
            return true;
        }
        if (!$sender->getPermission("linesia.moderation.unban")){
            $sender->sendMessage(Utils::getPrefix() . "§cVous n'avez pas la permission de faire ceci.");
            return true;
        }

        if (count($args) < 1){
            $sender->sendMessage(Utils::getPrefix() . "Usage : /unban <player>.");
            return true;
        }

        $ban = Utils::getConfigFile("Sanctions/Bannissement", "json");
        $player = $args[0];

        if (!$ban->exists($player)){
            $sender->sendMessage(Utils::getPrefix() . "§cCe joueur n'est actuellement pas banni.");
            return true;
        }
        $ban->remove($player);
        $ban->save();

        $sender->sendMessage(Utils::getPrefix() . "Vous avez bien débanni §9" . $args[0] ."§f.");
        Utils::sendDiscordLogs($sender->getName() . " vient de débannir " . $args[0], "**UNBAN**", 0xFF7800);
        return true;
    }

}
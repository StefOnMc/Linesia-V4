<?php

namespace Linesia\Command\Moderation;

use JsonException;
use Linesia\Player\LinesiaPlayer;
use Linesia\Utils\Utils;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;

class UnMuteCommand extends Command {

    public function __construct() {
        parent::__construct("unmute", "UnMute - Linesia", "/unmute <player>", ["unmute"]);
        $this->setPermission(implode(";", [
            "linesia.*",
            "linesia.moderation.unmute"
        ]));
    }

    /**
     * @throws JsonException
     */
    public function execute(CommandSender $sender, string $commandLabel, array $args): bool {
        if (!$sender instanceof LinesiaPlayer){
            return true;
        }
        if (!$sender->getPermission("linesia.moderation.unmute")){
            $sender->sendMessage(Utils::getPrefix() . "§cVous n'avez pas la permission de faire ceci.");
            return true;
        }

        if (count($args) < 1){
            $sender->sendMessage(Utils::getPrefix() . "Usage : /unmute <player>.");
            return true;
        }

        $mute = Utils::getConfigFile("Sanctions/Mute", "json");
        $player = $args[0];

        if (!$mute->exists($player)){
            $sender->sendMessage(Utils::getPrefix() . "§cCe joueur n'est actuellement pas mute.");
            return true;
        }

        $mute->remove($player);
        $mute->save();

        $sender->sendMessage(Utils::getPrefix() . "Vous avez bien unmute §9" . $args[0] ."§f.");
        Utils::sendDiscordLogs($sender->getName() . " vient de demute " . $args[0], "**UNMUTE**", 0xFF7800);
        return true;
    }

}
<?php

namespace Linesia\Command\Moderation;

use JsonException;
use Linesia\Core;
use Linesia\Player\LinesiaPlayer;
use Linesia\Utils\Utils;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;

class MuteChatCommand extends Command {

    public function __construct(){
        parent::__construct("mutechat", "MuteChat - Linesia", "/mutechat <on/off>", ["mutechat"]);
        $this->setPermission(implode(";", [
            "linesia.*",
            "linesia.moderation.mutechat"
        ]));
    }

    /**
     * @throws JsonException
     */
    public function execute(CommandSender $sender, string $commandLabel, array $args): bool {
        if (!$sender instanceof LinesiaPlayer) {
            return true;
        }
        if (!$sender->getPermission("linesia.moderation.mutechat")) {
            $sender->sendMessage(Utils::getPrefix() . "§cVous n'avez pas la permission de faire ceci.");
            return true;
        }
        if (count($args) < 1){
            $sender->sendMessage(Utils::getPrefix() . "Usage : /mutechat <on/off>.");
            return true;
        }

        if (isset($args[1])){
            $raison = [];
            for ($i = 1; $i < count($args); $i++) {
                $raison[] = $args[$i];
            }
            $raison = implode(" ", $raison);
        }else{
            $raison = "Aucun";
        }
        $config = Utils::getConfig();
        if ($args[0] === "on"){
            if ($config->get("mute.chat") === true){
                $sender->sendMessage(Utils::getPrefix() . "Le chat est déjà désactivé.");
                return true;
            }
            Core::getInstance()->getServer()->broadcastMessage("[§c!§f] Le chat vient d'être désactivé par " . Utils::getRankPlayer($sender) . " §fpour le motif : §9" . $raison ."§f.");
            $config->set("mute.chat");
            $config->set("mute.chat.raison", $raison);
            $config->save();
        }elseif($args[0] === "off"){
            if ($config->get("mute.chat") === false){
                $sender->sendMessage(Utils::getPrefix() . "Le chat n'est pas désactivé.");
                return true;
            }
            Core::getInstance()->getServer()->broadcastMessage("[§c!§f] Le chat vient d'être réactivé par " . Utils::getRankPlayer($sender) . " §f!");
            $config->set("mute.chat", false);
            $config->set("mute.chat.raison", "Aucun");
            $config->save();
        }else{
            $sender->sendMessage(Utils::getPrefix() . "Usage : /mutechat <on/off> <raison>.");
        }
        return true;
    }

}
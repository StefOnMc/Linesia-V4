<?php

namespace Linesia\Command\Moderation;

use JsonException;
use Linesia\Core;
use Linesia\Player\LinesiaPlayer;
use Linesia\Task\MaintenanceTask;
use Linesia\Utils\Utils;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;

class MaintenanceCommand extends Command {

    public function __construct() {
        parent::__construct("maintenance", "Maintenance - Linesia", "/maintenance <on/off/list/add/remove>", ["maintenance"]);
        $this->setPermission(implode(";", [
            "linesia.*",
            "linesia.moderation.maintenance"
        ]));
    }

    /**
     * @throws JsonException
     */
    public function execute(CommandSender $sender, string $commandLabel, array $args): bool {
        if (!$sender instanceof LinesiaPlayer){
            return true;
        }
        if (!$sender->getPermission("linesia.moderation.maintenance")){
            $sender->sendMessage(Utils::getPrefix() . "§cVous n'avez pas la permission de faire ceci.");
            return true;
        }
        if (count($args) < 1){
            $sender->sendMessage(Utils::getPrefix() . "Usage : /maintenance <on/off/list/add/remove>.");
            return true;
        }
        if ($args[0] === "on"){
            if (Utils::isWhitelist() === true){
                $sender->sendMessage(Utils::getPrefix() . "La maintenance est déjà activé.");
                return true;
            }
            $config = Utils::getConfig();
            if (!isset($args[1])){
                $config->set("maintenance.raison", "Aucun");
                $config->save();
                return true;
            }
            $config->set("maintenance.raison", $args[1]);
            $config->save();
            Core::getInstance()->getScheduler()->scheduleRepeatingTask(new MaintenanceTask($sender), 20);
        }elseif($args[0] === "off"){
            if (Utils::isWhitelist() === false){
                $sender->sendMessage(Utils::getPrefix() . "La maintenance n'est pas activé.");
                return true;
            }
            $config = Utils::getConfig();
            $config->set("maintenance", false);
            $config->save();
        }elseif($args[0] === "add"){
            if (!isset($args[1])){
                $sender->sendMessage(Utils::getPrefix() . "Usage : /maintenance add <player>.");
                return true;
            }
            $player = $args[1];
            $config = Utils::getConfigFile("Whitelist/player_whitelist", "ENUM");
            if ($config->exists($player)){
                $sender->sendMessage(Utils::getPrefix() . "§cCe joueur est déjà dans la liste blanche.");
                return true;
            }
            $config->set($player);
            $config->save();
            $sender->sendMessage(Utils::getPrefix() . "Vous avez bien ajouté §e" . $args[1] . " §fà la maintenance.");
        }elseif($args[0] === "remove"){
            if (!isset($args[1])){
                $sender->sendMessage(Utils::getPrefix() . "Usage : /maintenance remove <player>.");
                return true;
            }
            $player = $args[1];
            $config = Utils::getConfigFile("Whitelist/player_whitelist", "ENUM");
            if (!$config->exists($player)){
                $sender->sendMessage(Utils::getPrefix() . "§cCe joueur n'est pas dans la liste blanche.");
                return true;
            }
            $config->remove($player);
            $config->save();
            $sender->sendMessage(Utils::getPrefix() . "Vous avez bien supprimé §e" . $args[1] . " §fde la maintenance.");
        }elseif($args[0] === "list"){
            $config = Utils::getConfigFile("Whitelist/player_whitelist", "ENUM")->getAll(true);
            $count = count($config);
            $list = implode("§7, §e", $config);
            $sender->sendMessage(Utils::getPrefix() . "Il y a un total de §e" . $count . " §fpersonne(s) dans la liste blanche : \n§e" . $list);
        }
        return true;
    }

}
<?php

namespace Linesia\Command\Moderation;

use Linesia\Core;
use Linesia\Libs\refaltor\inventoryapi\InventoryAPI;
use Linesia\Player\LinesiaPlayer;
use Linesia\Utils\Utils;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;

class EnderInvseeCommand extends Command{

    public function __construct() {
        parent::__construct("enderinvsee", "EnderInvsee - Linesia", "/enderinvsee <player>", ["ecinv"]);
        $this->setPermission(implode(";", [
            "linesia.*",
            "linesia.moderation.invsee"
        ]));
    }

    public function execute(CommandSender $sender, string $commandLabel, array $args) {
        if (!$sender instanceof LinesiaPlayer){
            return true;
        }
        if (!$sender->getPermission("linesia.moderation.invsee")){
            $sender->sendMessage(Utils::getPrefix() . "§cVous n'avez pas la permission de faire ceci.");
            return true;
        }
        if (count($args) < 1){
            $sender->sendMessage(Utils::getPrefix() . "Usage : /enderinvsee <player>.");
            return true;
        }

        $player = Core::getInstance()->getServer()->getPlayerByPrefix($args[0]);
        if ($player instanceof LinesiaPlayer){
            $menu = InventoryAPI::createSimpleChest();
            $menu->setName("§9- §fEnderChest de §9" . $player->getName() . " §9-");
            $menu->setViewOnly();
            $menu->setContents($player->getEnderInventory()->getContents());
            $menu->send($sender);
        }

    }

}
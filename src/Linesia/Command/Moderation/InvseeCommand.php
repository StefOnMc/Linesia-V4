<?php

namespace Linesia\Command\Moderation;

use Linesia\Core;
use Linesia\Libs\refaltor\inventoryapi\InventoryAPI;
use Linesia\Player\LinesiaPlayer;
use Linesia\Utils\Utils;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\item\ItemFactory;

class InvseeCommand extends Command {

    public function __construct() {
        parent::__construct("invsee", "Invsee - Linesia", "/invsee <player>", ["invsee"]);
        $this->setPermission(implode(";", [
            "linesia.*",
            "linesia.moderation.invsee"
        ]));
    }

    public function execute(CommandSender $sender, string $commandLabel, array $args): bool {
        if (!$sender instanceof LinesiaPlayer){
            return true;
        }

        if (!$sender->getPermission("linesia.moderation.invsee")){
            $sender->sendMessage(Utils::getPrefix() . "§cVous n'avez pas la permission de faire ceci.");
            return true;
        }
        if (count($args) < 1){
            $sender->sendMessage(Utils::getPrefix() . "Usage : /invsee <player>.");
            return true;
        }

        if (Core::getInstance()->getServer()->getPlayerByPrefix($args[0]) instanceof LinesiaPlayer) {
            $target = Core::getInstance()->getServer()->getPlayerByPrefix($args[0]);
            $menu = InventoryAPI::createDoubleChest();
            $glass = ItemFactory::getInstance()->get(437);
            $glass->setCustomName("§c---");
            $menu->setItem(36, $glass);
            $menu->setItem(37, $glass);
            $menu->setItem(38, $glass);
            $menu->setItem(39, $glass);
            $menu->setItem(40, $glass);
            $menu->setItem(41, $glass);
            $menu->setItem(42, $glass);
            $menu->setItem(43, $glass);
            $menu->setItem(44, $glass);
            $glass->setCustomName("§cCasque ->");
            $menu->setItem(45, $glass);
            $glass->setCustomName("§c<- Casque | Plastron ->");
            $menu->setItem(47, $glass);
            $glass->setCustomName("§c<- Plastron | Pantalon ->");
            $menu->setItem(49, $glass);
            $glass->setCustomName("§c<- Pantalon | Bottes ->");
            $menu->setItem(51, $glass);
            $glass->setCustomName("§c<- Bottes");
            $menu->setItem(53, $glass);
            foreach($target->getInventory()->getContents() as $value => $item){
                $menu->setItem($value, $item);
            }
            $menu->setItem(46, $target->getArmorInventory()->getHelmet());
            $menu->setItem(48, $target->getArmorInventory()->getChestplate());
            $menu->setItem(50, $target->getArmorInventory()->getLeggings());
            $menu->setItem(52, $target->getArmorInventory()->getBoots());
            $menu->setName("§c- §8Inventaire de §9{$target->getName()} §c-");
            $menu->setViewOnly();
            $menu->send($sender);
        }else{
            $sender->sendMessage(Utils::getPrefix() . "§cCe joueur n'est actuellement pas connecté.");
        }
        return true;
    }

}
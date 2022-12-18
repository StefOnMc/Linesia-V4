<?php

namespace Linesia\Command\Grade;

use Linesia\Player\LinesiaPlayer;
use Linesia\Utils\Utils;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\item\Armor;
use pocketmine\item\Tool;

class RepairCommand extends Command {

    private static array $cooldown = [];

    public function __construct() {
        parent::__construct("repair", "Repair - Linesia", "/repair", ["repair"]);
        $this->setPermission(implode(";", [
            "linesia.*",
            "linesia.grade.repair"
        ]));
    }

    public function execute(CommandSender $sender, string $commandLabel, array $args): bool {
        if (!$sender instanceof LinesiaPlayer) {
            return true;
        }
        if (!$sender->getPermission("linesia.grade.repair")) {
            $sender->sendMessage(Utils::getPrefix() . "§cVous n'avez pas la permission de faire ceci.");
            return true;
        }

        if (!isset(self::$cooldown[$sender->getName()]) or self::$cooldown[$sender->getName()] - time() <= 0) {

            $item = $sender->getInventory()->getItemInHand();
            if (($item instanceof Tool) or ($item instanceof Armor)) {
                    $item->setDamage(0);
                    $sender->getInventory()->setItemInHand($item);
                    $sender->sendMessage(Utils::getPrefix() . "Votre item à bien été réparé.");
                    self::$cooldown[$sender->getName()] = time() + 30;

            }else{
                $sender->sendMessage(Utils::getPrefix() . "Cet objet ne peut pas être réparé.");
            }
        }else{
            $timeRestant = self::$cooldown[$sender->getName()] - time();
            $minutes = intval(abs($timeRestant / 60));
            $secondes = intval(abs($timeRestant - $minutes * 60));
            if ($minutes > 0) {
                $formatTemp = "$minutes minute(s) et $secondes seconde(s)";
            } else {
                $formatTemp = "$secondes seconde(s)";
            }
            $sender->sendMessage(Utils::getPrefix() . "Vous ne pourrez réparer votre objet que dans §9 " . $formatTemp ." §f.");
        }
        return true;
    }

}
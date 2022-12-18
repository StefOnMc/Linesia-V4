<?php

namespace Linesia\Command\Administration;

use Linesia\Player\LinesiaPlayer;
use Linesia\Utils\Utils;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
/*use Linesia\Core;
use Linesia\Entity\Box\VoteBox;
use pocketmine\entity\Skin;
use pocketmine\math\Vector3;
use pocketmine\nbt\tag\CompoundTag;
use pocketmine\nbt\tag\DoubleTag;
use pocketmine\nbt\tag\FloatTag;
use pocketmine\nbt\tag\ListTag;*/

class BoxCommand extends Command {

    public function __construct() {
        parent::__construct("box", "Box - Linesia", "/box <spawn> <box:type>");
        $this->setPermission("linesia.*");
    }

    public function execute(CommandSender $sender, string $commandLabel, array $args): bool
    {
        if ($sender instanceof LinesiaPlayer) {
            $sender->sendMessage(Utils::getPrefix() . "§cLes box sont actuellement désactivé, merci de contacter Julien.");
        }
        return true;
        /*
        if (!$sender->getPermission("linesia.*")){
            $sender->sendMessage(Utils::getPrefix() . "§cVous n'avez pas la permission de faire ceci.");
            return true;
        }

        if (count($args) < 1){
            $sender->sendMessage(Utils::getPrefix() . "Usage : /box <spawn/remove> <box:type>.");
            return true;
        }

        if ($args[0] === "spawn"){
            if (!isset($args[1])){
                $sender->sendMessage(Utils::getPrefix() . "§cMerci de mettre le nom d'un box valide (§9vote, amethyste, amethysterenfo§f)/");
            }
            $nbt = $this->createBaseNBT($sender->getLocation());
            if ($args[1] === "vote"){
                $path = Core::getInstance()->getDataFolder() . "Box.png";
                $data = Utils::PNGtoBYTES($path);
                $cape = "";
                $path = Core::getInstance()->getDataFolder() . "Box.json";
                $geometry = file_get_contents($path);

                $skin = new Skin($this->getName(), $data, $cape, "geometry.unknow", $geometry);
                $entity = new VoteBox($sender->getLocation(), $skin, $nbt);
                $entity->spawnToAll();

                $sender->sendMessage(Utils::getPrefix() . "Vous avez bien fait spawn la box vote.");
            }
        }
        return true;
    }

    public function createBaseNBT(Vector3 $pos, ?Vector3 $motion = null, float $yaw = 0.0, float $pitch = 0.0): CompoundTag {
        return CompoundTag::create()
            ->setTag("Pos", new ListTag([
                new DoubleTag($pos->x),
                new DoubleTag($pos->y),
                new DoubleTag($pos->z)
            ]))
            ->setTag("Motion", new ListTag([
                new DoubleTag($motion !== null ? $motion->x : 0.0),
                new DoubleTag($motion !== null ? $motion->y : 0.0),
                new DoubleTag($motion !== null ? $motion->z : 0.0)
            ]))
            ->setTag("Rotation", new ListTag([
                new FloatTag($yaw),
                new FloatTag($pitch)
            ]));
        */
    }

}
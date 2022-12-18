<?php

namespace Linesia\Command\Administration;

use Linesia\Core;
use Linesia\Entity\NPC\CyclopeNPC;
use Linesia\Player\LinesiaPlayer;
use Linesia\Utils\Utils;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\entity\Skin;
use pocketmine\math\Vector3;
use pocketmine\nbt\tag\CompoundTag;
use pocketmine\nbt\tag\DoubleTag;
use pocketmine\nbt\tag\FloatTag;
use pocketmine\nbt\tag\ListTag;

class BossCommand extends Command {

    public function __construct() {
        parent::__construct("cyclope");
    }

    public function execute(CommandSender $sender, string $commandLabel, array $args): bool {
        if (!$sender instanceof LinesiaPlayer) {
            return true;
        }
        if (!Core::getInstance()->getServer()->getPlayerExact("Julien8436")){
            $sender->sendMessage(Utils::getPrefix() . "§cLes boss sont encore en développement. Merci de rester patient.");
            return true;
        }
        $nbt = $this->createBaseNBT($sender->getLocation());
        $path = Core::getInstance()->getDataFolder() . "Boss/Cyclope.png";
        $data = Utils::PNGtoBYTES($path);
        $cape = "";
        $path = Core::getInstance()->getDataFolder() . "Boss/Cyclope.json";
        $geometry = file_get_contents($path);

        $skin = new Skin($this->getName(), $data, $cape, "geometry.cyclope", $geometry);
        $entity = new CyclopeNPC($sender->getLocation(), $skin, $nbt);
        $entity->setBoss();
        $entity->spawnToAll();
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
    }
}
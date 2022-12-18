<?php

namespace Linesia\Entity;

use Linesia\Entity\Base\BaseCustomEntity;
use Linesia\Entity\Base\BaseEntity;
use Linesia\Entity\Box\VoteBox;
use Linesia\Entity\NPC\CowNPC;
use Linesia\Entity\NPC\CyclopeNPC;
use Linesia\Entity\NPC\HumanNpc;
use pocketmine\data\bedrock\EntityLegacyIds;
use pocketmine\entity\Entity;
use pocketmine\entity\EntityDataHelper;
use pocketmine\entity\EntityFactory;
use pocketmine\entity\Human;
use pocketmine\entity\Location;
use pocketmine\nbt\tag\CompoundTag;
use pocketmine\network\mcpe\protocol\types\entity\EntityIds;
use pocketmine\world\World;

class EntityManager {

    /**
     * @var array
     */
    public static array $entities = [];

    public static array $entityName = [
        0 => "Human",
        1 => "Cow",
        2 => "Cyclope",
        3 => "VoteBox",
    ];

    public static function init(): void {

        self::register(HumanNpc::class, ["HumanEntity", "human"]);
        self::register(CyclopeNPC::class, ["cyclopeEntity", "cyclope"]);
        self::register(CowNPC::class, ["Cow", EntityIds::COW, EntityLegacyIds::COW], EntityLegacyIds::COW, "aa", "bb");

        EntityFactory::getInstance()->register(VoteBox::class, function (World $world, CompoundTag $nbt) : VoteBox{
            return new VoteBox(EntityDataHelper::parseLocation($nbt, $world), Human::parseSkinNBT($nbt), $nbt);
        }, ["vote_box", "minecraft:vote_box"]);

    }

    /**
     * @param string $classEntity
     * @param array $names
     * @param int|null $entityId
     * @param ...$args
     * @return void
     */
    public static function register(string $classEntity, array $names, int $entityId = null, ...$args): void {

        foreach ($names as $name)
            self::$entities[strtolower($name)] = $classEntity;

        EntityFactory::getInstance()->register($classEntity, function (World $world, CompoundTag $nbt) use($classEntity, $names, $args) : Entity {

            if($classEntity === HumanNpc::class)
                return new $classEntity(EntityDataHelper::parseLocation($nbt, $world), HumanNpc::parseSkinNBT($nbt), $nbt, ...$args);

            return new $classEntity(EntityDataHelper::parseLocation($nbt, $world), $nbt, ...$args);

        }, $names, $entityId);

    }

    /**
     * @param Location $location
     * @param string|int $id
     * @param ...$args
     * @return BaseEntity|BaseCustomEntity|null
     */
    public static function getEntityById(Location $location, string|int $id, ...$args) : BaseEntity|BaseCustomEntity|null {

        if(!isset(self::$entities[strtolower($id)]))
            return null;

        return new self::$entities[strtolower($id)]($location, ...$args);

    }

}
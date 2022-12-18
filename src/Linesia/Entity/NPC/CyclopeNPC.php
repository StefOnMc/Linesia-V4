<?php

namespace Linesia\Entity\NPC;

use Linesia\Core;
use Linesia\Entity\Base\BaseCustomEntity;
use Linesia\Entity\BossEntity;
use Linesia\Item\Administration\NPCWandItem;
use Linesia\Utils\Utils;
use pocketmine\block\Block;
use pocketmine\entity\Entity;
use pocketmine\entity\EntitySizeInfo;
use pocketmine\entity\Human;
use pocketmine\entity\Skin;
use pocketmine\event\entity\EntityDamageByEntityEvent;
use pocketmine\event\entity\EntityDamageEvent;
use pocketmine\item\ItemFactory;
use pocketmine\item\ItemIds;
use pocketmine\math\Vector2;
use pocketmine\math\Vector3;
use pocketmine\nbt\tag\CompoundTag;
use pocketmine\network\mcpe\protocol\types\entity\EntityIds;
use pocketmine\player\GameMode;
use pocketmine\player\Player;

class CyclopeNPC extends BaseCustomEntity {

    use BossEntity;

    private $target;
    protected int $moveTime = 0;
    public int $stayTime = 0;
    protected int $attackDelay = 0;
    public $lastUpdate = 0;
    private int|float $yaw;
    private int|float $pitch;

    public static function getNetworkTypeId(): string {
        return EntityIds::NPC;
    }

    protected function getInitialSizeInfo(): EntitySizeInfo {
        return new EntitySizeInfo(2.0, 2.2, 2.0);
    }

    protected function initEntity(CompoundTag $nbt): void {
        parent::initEntity($nbt);
        $this->setNameTagAlwaysVisible(true);
        $this->setNameTag(" ");
        $this->setMaxAirSupplyTicks(5);
        $this->setMaxHealth(500);
        $this->setHealth($this->getMaxHealth());
        $path = Core::getInstance()->getDataFolder() . "Boss/Cyclope.png";
        $data = Utils::PNGtoBYTES($path);
        $cape = "";
        $path = Core::getInstance()->getDataFolder() . "Boss/Cyclope.json";
        $geometry = file_get_contents($path);
        $skin = new Skin($this->getName(), $data, $cape, "geometry.cyclope", $geometry);
        $this->setSkin($skin);
    }

    public function onUpdate(int $currentTick): bool {
        if(!$this->isAlive() || $this->isClosed() || $this->isFlaggedForDespawn()){
            return false;
        }

        $tickDiff = $currentTick - $this->lastUpdate;
        $this->lastUpdate = $currentTick;
        $this->entityBaseTick($tickDiff);

        $target = $this->updateMove($tickDiff);

        if($target instanceof Human && $target->getPosition()->distanceSquared($this->getPosition()->asVector3()) <= 5){
            $this->checkAndAttackEntity($target);
        }elseif(
            $target instanceof Vector3
            && $this->getPosition()->distanceSquared($target) <= 1
            && $this->motion->y == 0
        ){
            $this->moveTime = 0;
        }

        return true;
    }

    public function checkAndAttackEntity(Human $player){
        $this->attackEntity($player);
    }

    public function attackEntity(Human $player){
        if($this->attackDelay > 20 && $this->getPosition()->distanceSquared($player->getPosition()->asVector3()) < 2){
            $this->attackDelay = 0;
            $damage = 15;
            $ev = new EntityDamageByEntityEvent($this, $player, EntityDamageEvent::CAUSE_ENTITY_ATTACK, $damage);
            $player->attack($ev);
            $this->doHitAnimation();
        }
    }

    public function getDrops(): array {
        $item = ItemFactory::getInstance()->get(ItemIds::RAW_MUTTON, 0, 12);
        $drops[] = $item;
        return $drops;
    }


    public function getTargetEntity(): ?Human
    {
        return $this->target;
    }

    public function isKnockback() : bool{
        return $this->attackTime > 0;
    }
    
    public function updateMove($tickDiff): Human|Player|null {
        if($this->isClosed() or $this->getWorld() == null){
            return null;
        }

        if($this->isKnockback()){
            $kbLevel = 4 / 2;
            $this->move(($this->motion->x * $tickDiff) / $kbLevel, $this->motion->y, ($this->motion->z * $tickDiff) / $kbLevel);
            $this->motion->y -= 0.2 * $tickDiff;
            $this->updateMovement();
            return null;
        }

        $before = $this->getBaseTarget();
        $this->changeTarget();
        if($this->getBaseTarget() instanceof Entity or $this->getBaseTarget() instanceof Block or $before !== $this->getBaseTarget() and
            $this->getBaseTarget() !== null
        ){
            $x = $this->getBaseTarget()->getPosition()->x - $this->getPosition()->x;
            $y = $this->getBaseTarget()->getPosition()->y - $this->getPosition()->y;
            $z = $this->getBaseTarget()->getPosition()->z - $this->getPosition()->z;

            $diff = abs($x) + abs($z);
            if($x ** 2 + $z ** 2 < 0.7){
                $this->motion->x = 0;
                $this->motion->z = 0;
            }elseif($diff > 0){
                $this->motion->x = 2 * 0.15 * ($x / $diff);
                $this->motion->z = 2 * 0.15 * ($z / $diff);
                $this->yaw = -atan2($x / $diff, $z / $diff) * 180 / M_PI;
            }
            $this->pitch = $y == 0 ? 0 : rad2deg(-atan2($y, sqrt($x ** 2 + $z ** 2)));

        }

        $dx = $this->motion->x * $tickDiff;
        $dz = $this->motion->z * $tickDiff;
        if($this->isCollidedHorizontally or $this->isUnderwater()){
            $this->updateMovement();
        }
        if($this->stayTime > 0){
            $this->stayTime -= $tickDiff;
            $this->move(0, $this->motion->y * $tickDiff, 0);
        }else{
            $futureLocation = new Vector2($this->getPosition()->x + $dx, $this->getPosition()->z + $dz);
            $this->move($dx, $this->motion->y * $tickDiff, $dz);
            $myLocation = new Vector2($this->getPosition()->x, $this->getPosition()->z);
            if(($futureLocation->x != $myLocation->x || $futureLocation->y != $myLocation->y)){
                $this->moveTime -= 90 * $tickDiff;
            }
        }
        $this->move($this->motion->x, $this->motion->y, $this->motion->z);
        $this->updateMovement();
        parent::updateMovement();
        return $this->getBaseTarget();
    }

    protected function changeTarget(){
        if($this->target instanceof Entity and $this->target->isAlive()){
            return;
        }
        if(!$this->target instanceof Entity || !$this->target->isAlive() || $this->target->isClosed() || $this->target->isFlaggedForDespawn()){
            foreach($this->getWorld()->getEntities() as $entities){
                if($entities === $this or !($entities instanceof Entity)){
                    continue;
                }
                if($this->getPosition()->distanceSquared($entities->getPosition()->asVector3()) > 81){
                    continue;
                }
                if($entities instanceof Player){
                    if($entities->getGamemode() === GameMode::ADVENTURE() or $entities->getGamemode() === GameMode::SURVIVAL()){
                        continue;
                    }
                }
                $this->target = $entities;
            }
        }
    }

    public function getBaseTarget() {
        return $this->target;
    }

    /**
     * @param EntityDamageEvent $source
     * @return void
     */
    public function attack(EntityDamageEvent $source): void {

        if(!$this->isBoss())
            parent::attack($source);
        else if ($source instanceof EntityDamageByEntityEvent)
            $damager = $source->getDamager();
            if ($damager instanceof Player)
                if ($damager->getInventory()->getItemInHand() instanceof NPCWandItem) {
                    $this->isAttackByNPCWand($damager);
                }
    }

}
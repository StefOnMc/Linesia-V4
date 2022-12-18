<?php

namespace Linesia\Player;

use JsonException;
use Linesia\Core;
use Linesia\Utils\Utils;
use pocketmine\color\Color;
use pocketmine\network\mcpe\protocol\OnScreenTextureAnimationPacket;
use pocketmine\network\mcpe\protocol\PlaySoundPacket;
use pocketmine\player\Player;
use pocketmine\world\particle\DustParticle;
use pocketmine\world\Position;

class LinesiaPlayer extends Player {

    private array $clicks;

    /* Permission */
    /**
     * @param string $permission
     * @return bool
     */
    public function getPermission(string $permission): bool {
        return $this->hasPermission("linesia.*") or $this->hasPermission($permission);
    }

    /* Particle */
    /**
     * @return void
     */
    public function particle(): void {

        $level = $this->getServer()->getWorldManager()->getDefaultWorld();
        $pos = $level->getSafeSpawn();
        $particle = new DustParticle(Color::mix(Color::fromARGB("§6")));
        for ($yaw = 0, $y = $pos->y; $y < $pos->y + 4; $yaw += (M_PI * 2) / 20, $y += 1 / 20) {
            $particle->encode($pos);
            $level->addParticle($pos,$particle);
        }
    }

    /* PlayerInfos */
    /**
     * @return string
     */
    public function getIp() : string {
        return $this->getNetworkSession()->getIp();
    }

    /**
     * @throws JsonException
     */
    public function createProfile(){
        $config = Utils::getConfigFile("PlayerInfos/" . $this->getName(), "yml");
        $money = Utils::getConfigFile("Stats/Money", "json");
        $kill = Utils::getConfigFile("Stats/Kill", "json");

        $config->set("first_connexion", date('d.m.Y H:I') . date_default_timezone_set("Europe/Berlin"));
        $config->set("ip", $this->getIp());
        $config->set("bannissement", 0);
        $config->set("expulsion", 0);
        $config->set("mute", 0);
        $config->set("avertissement", 0);
        $config->set("Factions", "Aucune");
        $config->save();

        $money->set($this->getName(), 1000);
        $money->save();

        $kill->set($this->getName(), 0);
        $kill->save();
    }

    /* Spawn */

    /**
     * @throws JsonException
     */
    public function setSpawnServer(int $x, int $y, int $z){
        $config = Utils::getConfigFile("Spawn", "yml");
        $config->set("x", $x);
        $config->set("y", $y);
        $config->set("z", $z);
        $config->save();

    }
    public function teleportToSpawn(): bool {
        $config = Utils::getConfigFile("Spawn", "yml");
        return $this->teleport(new Position($config->get("x"), $config->get("y"), $config->get("z"), Core::getInstance()->getServer()->getWorldManager()->getDefaultWorld()));
    }

}
<?php

namespace Linesia\EventListener\Listener;

use JsonException;
use Linesia\Core;
use Linesia\Player\LinesiaPlayer;
use Linesia\Utils\Utils;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerJoinEvent;
use pocketmine\event\player\PlayerQuitEvent;
use pocketmine\event\server\DataPacketReceiveEvent;
use pocketmine\network\mcpe\protocol\InventoryTransactionPacket;
use pocketmine\network\mcpe\protocol\LevelSoundEventPacket;
use pocketmine\network\mcpe\protocol\types\inventory\UseItemOnEntityTransactionData;
use pocketmine\network\mcpe\protocol\types\LevelSoundEvent;
use pocketmine\player\Player;
use pocketmine\Server;

class CPSListener implements Listener
{

    public array $cps = [];
    private array $cooldown = [];
    public static array $cpsEnabled;

    public function onDisconnect(PlayerQuitEvent $event)
    {
        unset($this->cps[$event->getPlayer()->getName()]);
    }

    public function onConnect(PlayerJoinEvent $event)
    {
        self::$cpsEnabled[$event->getPlayer()->getName()] = true;
    }

    /**
     * @throws JsonException
     */
    public function onDataPacketReceive(DataPacketReceiveEvent $event)
    {
        $packet = $event->getPacket();

        if ($packet instanceof LevelSoundEventPacket) {
            if ($packet->sound === LevelSoundEvent::ATTACK_NODAMAGE) {
                $this->addCPS($event->getOrigin()->getPlayer());
                if (self::$cpsEnabled[$event->getOrigin()->getPlayer()->getName()] === true) {
                    $event->getOrigin()->getPlayer()->sendPopup("§l§7» §r§9" . $this->getCPS($event->getOrigin()->getPlayer()) . " §fCPS");
                }
                if ($this->getCPS($event->getOrigin()->getPlayer()) >= 15) {
                    $players = server::getInstance()->getOnlinePlayers();
                    if (!$this->hasCooldown($event->getOrigin()->getPlayer())) {
                        Utils::sendDiscordLogs($event->getOrigin()->getPlayer()->getName() . " fait actuellement du " . $this->getCPS($event->getOrigin()->getPlayer()), "**CPS**", 0xEE8601);
                        $this->updateCooldown($event->getOrigin()->getPlayer());
                        foreach ($players as $playerName) {
                            $origine = $event->getOrigin()->getPlayer();
                            if ($origine instanceof LinesiaPlayer) {
                                if ($playerName instanceof LinesiaPlayer) {
                                    if ($playerName->getPermission("linesia.alert.cps")) {
                                        $playerName->sendMessage("[§cCPS§f] " . Utils::getRankPlayer($origine) . " §ffait actuellement du §9" . $this->getCPS($origine) . " CPS §f!");
                                    }
                                }
                            }
                        }
                    }
                }
            }
        }
        if ($packet instanceof InventoryTransactionPacket) {
            if ($packet->trData instanceof UseItemOnEntityTransactionData) {
                $this->addCPS($event->getOrigin()->getPlayer());
                if (isset(self::$cpsEnabled[$event->getOrigin()->getPlayer()->getName()])) {
                    if (self::$cpsEnabled[$event->getOrigin()->getPlayer()->getName()] === true) {
                        $event->getOrigin()->getPlayer()->sendPopup("§l§7» §r§9" . $this->getCPS($event->getOrigin()->getPlayer()) . " §fCPS");
                    }
                }
                if ($this->getCPS($event->getOrigin()->getPlayer()) >= 15) {
                    $players = server::getInstance()->getOnlinePlayers();

                    if (!$this->hasCooldown($event->getOrigin()->getPlayer())) {
                        $this->updateCooldown($event->getOrigin()->getPlayer());
                        foreach ($players as $playerName) {
                            $offender = $event->getOrigin()->getPlayer()->getName();
                            $origine = $event->getOrigin()->getPlayer();
                            if ($origine instanceof LinesiaPlayer) {
                                if ($playerName instanceof LinesiaPlayer) {
                                    if ($playerName->getPermission("linesia.alert.cps")) {
                                        $playerName->sendMessage("[§cCPS§f] " . Utils::getRankPlayer($origine) . " §ffait actuellement du §9" . $this->getCPS($origine) . " CPS §f!");
                                    }
                                }
                            }
                        }
                    }
                }
            }
            if ($this->getCPS($event->getOrigin()->getPlayer()) > 25) {
                $event->cancel();
                $origine = $event->getOrigin()->getPlayer();
                if ($origine instanceof LinesiaPlayer)
                Server::getInstance()->broadcastMessage("[§c§l!§r] " . Utils::getRankPlayer($origine) . " §fvient de se faire expulsé du serveur par §cAnti§f-§cCheat §fpour le motif §9CPS > 30§f.");
                $origine->kick("[§l§c!§r] Vous avez été expulsé de §9Linésia §f:\n- Staff : §cAnti§f-§cCheat\n§f- Motif : §9CPS > 30§f.\n§e" . Core::DISCORD);
            }
        }
    }
        public function hasCooldown(Player $player): bool {
            return isset($this->cooldown[$player->getName()]) && $this->cooldown[$player->getName()] > time();
        }

        public
        function updateCooldown(Player $player): void
        {
            $this->cooldown[$player->getName()] = time() + 10;
        }

        public function addCPS(Player $player): void {
            $time = microtime(true);
            $this->cps[$player->getName()][] = $time;
        }

        public function getCPS(Player $player): int
        {
            $time = microtime(true);
            return count(array_filter($this->cps[$player->getName()] ?? [], static function (float $t) use ($time): bool {
                return ($time - $t) <= 1;
            }));
        }
}
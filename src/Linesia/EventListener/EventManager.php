<?php

namespace Linesia\EventListener;

use Linesia\AntiCheat\AntiFly;
use Linesia\AntiCheat\AntiInstantBreak;
use Linesia\Blocs\Anvil;
use Linesia\Blocs\EnchantingTable;
use Linesia\Core;
use Linesia\EventListener\Listener\ArmorEffectListener;
use Linesia\EventListener\Listener\BoxListener;
use Linesia\EventListener\Listener\CPSListener;
use Linesia\EventListener\Listener\DynamiteListener;
use Linesia\EventListener\Listener\EnderChestListener;
use Linesia\EventListener\Listener\HeadFinderListener;
use Linesia\EventListener\Listener\NpcListener;
use Linesia\EventListener\Listener\ProtectionListener;
use Linesia\EventListener\Listener\RandomOreListener;
use Linesia\EventListener\Listener\StaffModeListener;
use Linesia\EventListener\PlayerEvent\PlayerAttack;
use Linesia\EventListener\PlayerEvent\PlayerChat;
use Linesia\EventListener\PlayerEvent\PlayerCreation;
use Linesia\EventListener\PlayerEvent\PlayerDeath;
use Linesia\EventListener\PlayerEvent\PlayerItemUse;
use Linesia\EventListener\PlayerEvent\PlayerJoin;
use Linesia\EventListener\PlayerEvent\PlayerLogin;
use Linesia\EventListener\PlayerEvent\PlayerPreLogin;
use Linesia\EventListener\PlayerEvent\PlayerQuit;
use Linesia\Libs\refaltor\inventoryapi\events\PacketListener;
use pocketmine\event\Listener;

class EventManager implements Listener {

    public static function initEvent() : void{
        $event = [
            new PlayerCreation(),
            new PlayerPreLogin(),
            new PlayerLogin(),
            new PlayerJoin(),
            new PlayerQuit(),
            new PlayerChat(),
            new PlayerItemUse(),
            new PlayerAttack(),
            new PlayerDeath()
        ];
        foreach ($event as $events){
            Core::getInstance()->getServer()->getPluginManager()->registerEvents($events, Core::getInstance());
        }
        Core::getInstance()->getLogger()->info("Il y a un total de §9" . count($event) . " event(s) §fchargé(s).");

    }

    public static function initListener(): void {

        $listner = [

            new StaffModeListener(),
            new CPSListener(),
            new EnderChestListener(),
            new ArmorEffectListener(),
            new HeadFinderListener(),
            new BoxListener(),
            new ProtectionListener(),
            new DynamiteListener(),
            new RandomOreListener(),
            new NpcListener(),
            new PacketListener(),

        ];

        foreach ($listner as $events){
            Core::getInstance()->getServer()->getPluginManager()->registerEvents($events, Core::getInstance());
        }
        Core::getInstance()->getLogger()->info("Il y a un total de §9" . count($listner) . " listner(s) §fchargé(s).");
    }

    public static function initAntiCheat(): void{

        $anticheat = [
            new AntiFly(),
            new AntiInstantBreak()
        ];

        foreach ($anticheat as $events){
            Core::getInstance()->getServer()->getPluginManager()->registerEvents($events, Core::getInstance());
        }
        Core::getInstance()->getLogger()->info("Il y a un total de §9" . count($anticheat) . " anticheat(s) §fchargé(s).");

    }

    public static function initBlocs(): void {

        $blocs = [
            new EnchantingTable(),
            new Anvil(),
        ];

        foreach ($blocs as $events){
            Core::getInstance()->getServer()->getPluginManager()->registerEvents($events, Core::getInstance());
        }
        Core::getInstance()->getLogger()->info("Il y a un total de §9" . count($blocs) . " bloc(s) §fchargé(s).");

    }

}
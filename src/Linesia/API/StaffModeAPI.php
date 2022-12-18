<?php

namespace Linesia\API;

use JsonException;
use Linesia\Core;
use Linesia\Player\LinesiaPlayer;
use Linesia\Utils\Utils;
use pocketmine\item\VanillaItems;
use pocketmine\network\mcpe\convert\SkinAdapterSingleton;
use pocketmine\network\mcpe\protocol\PlayerListPacket;
use pocketmine\network\mcpe\protocol\types\PlayerListEntry;
use pocketmine\player\GameMode;

final class StaffModeAPI extends API {

    public static array $staffMode = [];
    public static array $vanish = [];
    public static array $inventaire = [];
    public static array $armure = [];

    /**
     * @param LinesiaPlayer $sender
     * @return void
     */
    public static function isStaffMode(LinesiaPlayer $sender): void {
        if (isset(self::$staffMode[$sender->getName()])){
            self::removeStaffMode($sender);
        }else{
            self::addStaffMode($sender);
        }
    }

    /**
     * @param LinesiaPlayer $sender
     * @return void
     */
    private static function addStaffMode(LinesiaPlayer $sender): void {
        /* Inventaire */
        self::$armure[$sender->getName()] = $sender->getArmorInventory()->getContents();
        self::$inventaire[$sender->getName()] = $sender->getInventory()->getContents();
        $sender->getInventory()->clearAll();

        /* StaffMode Item */
        $sender->getInventory()->setItem(1, VanillaItems::GHAST_TEAR()->setCustomName("§9- §fVanish §9-")->setLore(["§o§cStaffMode"]));
        $sender->getInventory()->setItem(2, VanillaItems::APPLE()->setCustomName("§9- §fRandomTp §9-")->setLore(["§o§cStaffMode"]));
        $sender->getInventory()->setItem(3, VanillaItems::SUGAR()->setCustomName("§9- §fPlayerInfos §9-")->setLore(["§o§cStaffMode"]));
        $sender->getInventory()->setItem(4, VanillaItems::IRON_SWORD()->setCustomName("§9- §fBannissement §9-")->setLore(["§o§cStaffMode"]));
        $sender->getInventory()->setItem(5, VanillaItems::IRON_AXE()->setCustomName("§9- §fKick §9-")->setLore(["§o§cStaffMode"]));
        $sender->getInventory()->setItem(6, VanillaItems::STICK()->setCustomName("§9- §fFreeze §9-")->setLore(["§o§cStaffMode"]));
        $sender->getInventory()->setItem(7, VanillaItems::BLAZE_ROD()->setCustomName("§9- §fKnockBack §9-")->setLore(["§o§cStaffMode"]));

        /* FakeLeave */
        foreach (Core::getInstance()->getServer()->getOnlinePlayers() as $player){
            if ($player instanceof LinesiaPlayer){
                if (!$player->getPermission("linesia.staffmode.fakejoin")){
                    $player->sendMessage("[§c-§f] " . Utils::getRankPlayer($sender));
                }
            }
        }

        /* Vanish */
        self::setVanishPlayer($sender);

        /* StaffMode */
        $sender->sendMessage(Utils::getPrefix() . "Vous êtes maintenant en StaffMode.");
        $sender->setGamemode(GameMode::ADVENTURE());
        $sender->setFlying(true);
        $sender->setAllowFlight(true);
        self::$staffMode[$sender->getName()] = $sender->getName();
    }

    /**
     * @param LinesiaPlayer $sender
     * @return void
     */
    public static function removeStaffMode(LinesiaPlayer $sender): void {

        /* Inventaire */
        $sender->getInventory()->clearAll();
        $sender->getArmorInventory()->setContents(self::$armure[$sender->getName()]);
        $sender->getInventory()->setContents(self::$inventaire[$sender->getName()]);
        unset(self::$armure[$sender->getName()]);
        unset(self::$inventaire[$sender->getName()]);

        /* Vanish */
        self::removeVanishPlayer($sender);

        /* FakeJoin */
        foreach (Core::getInstance()->getServer()->getOnlinePlayers() as $player){
            if ($player instanceof LinesiaPlayer){
                if (!$player->getPermission("linesia.staffmode.fakejoin")){
                    $player->sendMessage("[§a+§f] " . Utils::getRankPlayer($sender));
                }
            }
        }

        /* TeleportSpawn */
        $sender->teleport(Core::getInstance()->getServer()->getWorldManager()->getDefaultWorld()->getSafeSpawn());

        /* StaffMode */
        $sender->sendMessage(Utils::getPrefix() . "Vous venez de quitter le StaffMode.");
        $sender->setGamemode(GameMode::SURVIVAL());
        $sender->setFlying(false);
        $sender->setAllowFlight(false);
        unset(self::$staffMode[$sender->getName()]);

    }

    /* Vanish */

    /**
     * @param LinesiaPlayer $sender
     * @return bool
     */
    public static function getVanish(LinesiaPlayer $sender): bool {
        return isset(self::$staffMode[$sender->getName()]);
    }

    /**
     * @param LinesiaPlayer $sender
     * @return void
     */
    public static function isInVanish(LinesiaPlayer $sender): void {
        if (isset(self::$vanish[$sender->getName()])){
            self::removeVanishPlayer($sender);
        }else{
            self::setVanishPlayer($sender);
        }
    }

    /**
     * @param LinesiaPlayer $sender
     * @return void
     */
    private static function setVanishPlayer(LinesiaPlayer $sender): void {
        self::$vanish[$sender->getName()] = $sender->getName();
        foreach (Core::getInstance()->getServer()->getOnlinePlayers() as $player){
            if ($player instanceof LinesiaPlayer){
                if (!$player->getPermission("linesia.staffmode.vanish")){
                    $player->hidePlayer($sender);
                    $entry = new PlayerListEntry();
                    $entry->uuid = $sender->getUniqueId();
                    $pk = new PlayerListPacket();
                    $pk->entries[] = $entry;
                    $pk->type = PlayerListPacket::TYPE_REMOVE;
                    $player->getNetworkSession()->sendDataPacket($pk);
                }else{
                    $player->showPlayer($sender);
                }
            }
        }
        $sender->sendMessage(Utils::getPrefix() . "Vous venez d'activer le vanish.");
        $sender->setNameTag("§7[§9Vanish§7] " . $sender->getName());
    }

    /**
     * @param LinesiaPlayer $sender
     * @return void
     */
    private static function removeVanishPlayer(LinesiaPlayer $sender): void{
        unset(self::$vanish[$sender->getName()]);
        Core::getInstance()->getRankAPI()->updateNametag($sender);
        foreach (Core::getInstance()->getServer()->getOnlinePlayers() as $player){
            $player->showPlayer($sender);
            $pk = new PlayerListPacket();
            $pk->type = PlayerListPacket::TYPE_ADD;
            $pk->entries[] = PlayerListEntry::createAdditionEntry(
                $sender->getUniqueId(),
                $sender->getId(),
                $sender->getDisplayName(),
                SkinAdapterSingleton::get()->toSkinData($sender->getSkin()),
                $sender->getXuid()
            );
            $sender->sendMessage(Utils::getPrefix() . "Vous venez de désactiver le vanish.");
            $player->getNetworkSession()->sendDataPacket($pk);
        }
    }
}
<?php

namespace Linesia\EventListener\PlayerEvent;

use JsonException;
use Linesia\API\PlayTimeAPI;
use Linesia\Core;
use Linesia\Player\LinesiaPlayer;
use Linesia\Task\BanTask;
use Linesia\Utils\Utils;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerJoinEvent;
use pocketmine\network\mcpe\protocol\types\DeviceOS;
use pocketmine\Server;

class PlayerJoin implements Listener {

    private static array $os = [
        DeviceOS::UNKNOWN => "unknown",
        DeviceOS::ANDROID => "Android",
        DeviceOS::IOS => "iPhone",
        DeviceOS::OSX => "mac",//macos
        DeviceOS::AMAZON => "fire",//fire tablet, fireOS
        DeviceOS::GEAR_VR => "gear vr",
        DeviceOS::HOLOLENS => "HoloLens",
        DeviceOS::WINDOWS_10 => "windows",
        DeviceOS::WIN32 => "windows",//32
        DeviceOS::DEDICATED => "unknown(dedicated)",
        DeviceOS::TVOS => " Apple TV",//tvOS
        DeviceOS::PLAYSTATION => "PlayStation",//Orbis OS
        DeviceOS::NINTENDO => "Nintendo Switch",//NX(development code name)
        DeviceOS::XBOX => "Xbox",
        DeviceOS::WINDOWS_PHONE => "Windows Phone",
    ];


    /**
     * @throws JsonException
     */
    public function onPlayerJoin(PlayerJoinEvent $event): bool
    {
        $sender = $event->getPlayer();

        if ($sender instanceof LinesiaPlayer){
            if (!file_exists(Core::getInstance()->getDataFolder() . "PlayerInfos/" . $sender->getName() . ".yml")){
                $sender->createProfile();
            }

            /* Key */
            $key = Utils::getConfigFile("Box/BoxVote", "json");
            $key1 = Utils::getConfigFile("Box/BoxAmethyste", "json");
            $key2 = Utils::getConfigFile("Box/BoxAmethysteRenfo", "json");
            if (!$key->exists($sender->getName())){
                $key->set($sender->getName(), 0);
                $key->save();
                $key1->set($sender->getName(), 0);
                $key1->save();
                $key2->set($sender->getName(), 0);
                $key2->save();
            }

            /* DC */
            $ip = Utils::getConfigFile("PlayerInfos/ips", "json");
            $uuid = Utils::getConfigFile("PlayerInfos/uuid", "json");

            $ip->set($sender->getName(), $sender->getIp());
            $ip->save();

            $uuid->set($sender->getName(), $sender->getUniqueId());
            $uuid->save();

            /* OS */
            $playerInfo = $sender->getNetworkSession()->getPlayerInfo();
            if ($playerInfo === null) {
                Server::getInstance()->getLogger()->info("error playerInfo is null");
                return true;
            }
            $deviceos = $playerInfo->getExtraData()["DeviceOS"] ?? DeviceOS::UNKNOWN;//int
            $osname = self::$os[$deviceos] ?? "unknown";
            $config = Utils::getConfigFile("PlayerInfos/" . $sender->getName(), "yml");
            $config->set("os", $osname);
            $config->save();

            /* PlayTime */
            PlayTimeAPI::setPlayTime($sender->getName());

            /* JoinMessage */
            $event->setJoinMessage("[§a+§f] " . Utils::getRankPlayer($sender));

            /* Bannissement */
            if (Utils::getConfigFile("Sanctions/Bannissement", "json")->exists($sender->getName())){
                $ban = Utils::getConfigFile("Sanctions/Bannissement", "json")->get($sender->getName());
                $ban = explode(":", $ban);
                $time = $ban[1];

                if ($time - time() <= 0){
                    $ban2 = Utils::getConfigFile("Sanctions/Bannissement", "json");
                    $ban2->remove($sender->getName());
                    $ban2->save();
                }else{
                    Core::getInstance()->getScheduler()->scheduleRepeatingTask(new BanTask($sender), 7);
                }
            }

        }
        return true;
    }

}
<?php

namespace Linesia\Task;

use JsonException;
use Linesia\Core;
use Linesia\Player\LinesiaPlayer;
use Linesia\Utils\Utils;
use pocketmine\scheduler\Task;
use pocketmine\Server;

class MaintenanceTask extends Task {

    private LinesiaPlayer $sender;
    private int $time = 31;

    public function __construct(LinesiaPlayer $sender) {
        $this->sender = $sender;
    }

    /**
     * @return void
     * @throws JsonException
     */
    public function onRun(): void {
        $sender = $this->sender;
        $time = $this->time;
        $time--;
        if ($time === 0){
            $config = Utils::getConfig();
            $config->set("maintenance");
            $config->save();
            foreach (Core::getInstance()->getServer()->getOnlinePlayers() as $player){
                if ($player instanceof LinesiaPlayer){
                    $config = Utils::getConfigFile("Whitelist/player_whitelist", "ENUM");
                    if (!$config->exists($player) or !Server::getInstance()->isOp($player)){
                        $player->kick("[§l§c!§r] Le serveur vient de passer en maintenance :\n- Staff : " . Utils::getRankPlayer($sender) . "\n- Motif : §9" . Utils::getWhitelistRaison() . "\n§e" . Core::DISCORD);
                    }
                }
            }
            $this->onCancel();
        }elseif($time === 5){
            Core::getInstance()->getServer()->broadcastMessage("[§l§c!§r] Le serveur va passer en maintenance dans §9" . $this->time . " §f!");
        }elseif($time === 10){
            Core::getInstance()->getServer()->broadcastMessage("[§l§c!§r] Le serveur va passer en maintenance dans §9" . $this->time . " §f!");
        }elseif($time === 20){
            Core::getInstance()->getServer()->broadcastMessage("[§l§c!§r] Le serveur va passer en maintenance dans §9" . $this->time . " §f!");
        }elseif($time === 30){
            Core::getInstance()->getServer()->broadcastMessage("[§l§c!§r] Le serveur va passer en maintenance dans §9" . $this->time . " §f!");
        }
    }

}
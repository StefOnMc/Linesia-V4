<?php

namespace Linesia\EventListener\Listener;

use JsonException;
use Linesia\Core;
use Linesia\Utils\Utils;
use pocketmine\console\ConsoleCommandSender;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerInteractEvent;

class HeadFinderListener implements Listener {

    /**
     * @throws JsonException
     */
    public function onInteract(PlayerInteractEvent $event ){

        $player = $event->getPlayer();
        $action = $event->getAction();
        $block = $event->getBlock();
        $config = Utils::getConfigFile("heads_find", "yml");

        if( $action !== PlayerInteractEvent::RIGHT_CLICK_BLOCK || $player->getLocation()->getWorld()->getFolderName() !== Core::getInstance()->getServer()->getWorldManager()->getWorldByName("world")->getFolderName()) return;

        $coords = [
            $block->getPosition()->getFloorX(),
            $block->getPosition()->getFloorY(),
            $block->getPosition()->getFloorZ()
        ];

        $id = array_search( $coords, $config->get("coords"));
        if( !is_int($id) ) return;

        if( $this->haveHead($player->getName(), $id ) ){
            $player->sendMessage(Utils::getPrefix() . "§r§cVous avez deja trouvé cette tête !");
            return;
        };

        $this->addHead( $player->getName(), $id );

        $player_heads = $this->headsNum($player->getName());
        $heads_num = count($config->get("coords"));
        $player->sendMessage("§c§l» §rVous avez trouvé §9$player_heads" . "§f/§9$heads_num");

        if( $player_heads == $heads_num ){

            $player->sendMessage(Utils::getPrefix() . "§r§fBien joué ! Vous avez trouvé toutes les têtes !");

            foreach( $config->get("rewards") as $reward ){
                Core::getInstance()->getServer()->dispatchCommand(
                    new ConsoleCommandSender(
                        Core::getInstance()->getServer(),
                        Core::getInstance()->getServer()->getLanguage()
                    ),
                    str_replace("{player}", $player->getName(), $reward)
                );
            }
        }

    }

    public function haveHead( string $player, int $id ) : bool {
        $config = Utils::getConfigFile("heads_find", "yml");
        $config->reload();
        $heads = $config->get($player, null) ?? [];
        return in_array( $id, $heads );
    }

    /**
     * @throws JsonException
     */
    public function addHead(string $player, int $id ) : void {
        $config = Utils::getConfigFile("heads_find", "yml");
        $config->reload();
        $heads = $config->get($player, null) ?? [];
        $heads[] = $id;
        $config->set( $player, $heads );
        $config->save();
    }

    public function headsNum( string $player ) : int {
        $config = Utils::getConfigFile("heads_find", "yml");
        $config->reload();
        $heads = $config->get($player, null) ?? [];
        return count( $heads );
    }

}
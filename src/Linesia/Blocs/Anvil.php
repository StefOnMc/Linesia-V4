<?php

namespace Linesia\Blocs;

use Linesia\API\EconomyAPI;
use Linesia\Libs\Form\SimpleForm;
use Linesia\Player\LinesiaPlayer;
use Linesia\Utils\Utils;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerInteractEvent;
use pocketmine\item\Armor;
use pocketmine\item\Tool;

class Anvil implements Listener {

    public function onPlayerInteractEvent(PlayerInteractEvent $event){
        $player = $event->getPlayer();
        $blocs = $event->getBlock();

        if ($blocs->getId() === 145) {
            $event->cancel();
            if ($player->getInventory()->getItemInHand() instanceof Tool || $player->getInventory()->getItemInHand() instanceof Armor) {

                if ($player->getInventory()->getItemInHand()->getMeta() != 0) {
                    $form = new SimpleForm(function (LinesiaPlayer $player, $data){
                        if ($data === null){
                            return true;
                        }
                        if ($data === 0){
                            if (Utils::getConfigFile("Stats/Money", "json")->get($player->getName()) >= 50){
                                $item = $player->getInventory()->getItemInHand();
                                $item->setDamage(0);
                                $player->getInventory()->setItemInHand($item);
                                $player->sendMessage(Utils::getPrefix() . "Votre item a bien été réparé.");
                                EconomyAPI::getInstance()->deleteMoney($player, 50);
                            }else{
                                $player->sendMessage(Utils::getPrefix() . "§cVous n'avez pas l'argent requis pour réparer cet objet, il vous manque §9" . Utils::getConfigFile("Stats/Money", "json")->get($player->getName()) - 50 . "$" . "§f.");
                            }
                        }elseif($data === 1){
                            return true;
                        }
                    });
                    $form->setTitle("§9- §fRepair §9-");
                    $form->setContent("§c-> §fBienvenue dans l'interface de repair, la réparation de votre outils vous coutera §950$" . "§f. Êtes-vous sur de vouloir réparer votre objet ?");
                    $form->addButton("§aOui");
                    $form->addButton("§cNon");
                    $player->sendForm($form);
                } else {
                    $player->sendMessage(Utils::getPrefix() . "§cCet objet n'a pas besoin d'être réparé.");
                }
            }else{
                $player->sendMessage(Utils::getPrefix() . "§cCet objet ne peut pas être réparé.");
            }
        }

    }

}
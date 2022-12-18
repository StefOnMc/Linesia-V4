<?php

namespace Linesia\EventListener\Listener;

use Linesia\API\BoxAPI;
use Linesia\Entity\Box\VoteBox;
use Linesia\Libs\Form\SimpleForm;
use Linesia\Player\LinesiaPlayer;
use Linesia\Utils\Utils;
use pocketmine\event\entity\EntityDamageByEntityEvent;
use pocketmine\event\entity\EntityDamageEvent;
use pocketmine\event\Listener;

class BoxListener implements Listener {

    public function onAttack(EntityDamageEvent $event){
        if ($event instanceof EntityDamageByEntityEvent){
            $sender = $event->getDamager();
            $entity = $event->getEntity();
            if ($sender instanceof LinesiaPlayer) {
                if ($entity instanceof VoteBox) {
                    $event->cancel();
                    $form = new SimpleForm(function (LinesiaPlayer $sender, $data) {
                        if ($data === null) {
                            return true;
                        }
                        if ($data === 0) {

                            if (BoxAPI::getInstance()->getKey($sender, "BoxVote") >= 1) {
                                BoxAPI::getInstance()->delKey($sender, "BoxVote", 1);
                                $sender->sendMessage(Utils::getPrefix() . "Vous venez d'ouvrir un box §aVote§f.");
                            } else {
                                $sender->sendMessage(Utils::getPrefix() . "Vous n'avez pas assez de clé(s) pour ouvrir la box.");
                            }
                        }
                        return true;
                    });
                    $form->setTitle("§9- §fBox Vote §9-");
                    $form->setContent("§9§l» §rBienvenue dans l'interface de la box vote.\n§7--------------------------------\n§fVous avez actuellement : §9" . BoxAPI::getInstance()->getKey($sender, "BoxVote") . " §fclé(s) vote.\n§7--------------------------------");
                    $form->addButton("§l§9» §rOuvir la box");
                    $form->addButton("§l§9» §rLoots");
                    $form->addButton("§cRetour");
                    $sender->sendForm($form);
                }
            }
        }
    }

}
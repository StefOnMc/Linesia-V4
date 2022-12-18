<?php

namespace Linesia\EventListener\Listener;

use Linesia\API\PlayTimeAPI;
use Linesia\API\StaffModeAPI;
use Linesia\Core;
use Linesia\Libs\Form\CustomForm;
use Linesia\Player\LinesiaPlayer;
use Linesia\Utils\Utils;
use pocketmine\event\entity\EntityDamageByEntityEvent;
use pocketmine\event\entity\EntityItemPickupEvent;
use pocketmine\event\inventory\InventoryTransactionEvent;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerDropItemEvent;
use pocketmine\event\player\PlayerItemUseEvent;
use pocketmine\player\GameMode;
use pocketmine\Server;
use pocketmine\world\Position;

class StaffModeListener implements Listener {

    /**
     * @param PlayerDropItemEvent $event
     * @return void
     */
    public function onPlayerDrop(PlayerDropItemEvent $event): void {
        $sender = $event->getPlayer();
        if ($sender instanceof LinesiaPlayer) {
            if (StaffModeAPI::getVanish($sender)){
                $event->cancel();
            }
        }
    }

    /**
     * @param InventoryTransactionEvent $event
     * @return void
     */
    public function onInventoryTransaction(InventoryTransactionEvent $event): void {
        $sender = $event->getTransaction()->getSource();
        if ($sender instanceof LinesiaPlayer) {
            if (StaffModeAPI::getVanish($sender)) {
                $event->cancel();
            }
        }
    }

    /**
     * @param PlayerItemUseEvent $event
     * @return bool|void
     */
    public function onPlayerItemUse(PlayerItemUseEvent $event){

        $sender = $event->getPlayer();
        $item = $sender->getInventory()->getItemInHand();

        if ($sender instanceof LinesiaPlayer){
            if (StaffModeAPI::getVanish($sender)){
                $event->cancel();
                if ($item->getId() == 260){
                    $senders = Core::getInstance()->getServer()->getOnlinePlayers();
                    if (count($senders) <= 1) {
                        $sender->sendMessage(Utils::getPrefix() . "§cIl n'y a actuellement aucun joueur connecté.");
                        return true;
                    }
                    $random = $senders[array_rand($senders)];
                    while ($random === $sender){
                        $random = $senders[array_rand($senders)];
                    }
                    if ($random instanceof LinesiaPlayer) {
                        $sender->teleport($random->getLocation());
                        $sender->sendMessage(Utils::getPrefix() . "Vous avez été téléporté à " . Utils::getRankPlayer($random));
                    }
                }elseif ($item->getId() == 370){
                    StaffModeAPI::isInVanish($sender);
                }
            }
        }
    }

    public function onEntityDamageByEntity(EntityDamageByEntityEvent $event){
        $sender = $event->getDamager();
        $player = $event->getEntity();

        if ($sender instanceof LinesiaPlayer){
            if ($player instanceof LinesiaPlayer){
                if (StaffModeAPI::getVanish($sender)){
                    $event->cancel();
                }
                $item = $sender->getInventory()->getItemInHand()->getId();
                if ($item == 280){
                    if ($player->isImmobile() === true){
                        $player->setImmobile(false);
                        $player->sendMessage("§bFreeze §l§7» §rVous n'êtes actuellement plus gelé.");
                        $player->teleport(new Position($player->getPosition()->getX(), $player->getPosition()->getY() - 100, $player->getPosition()->getZ(), $player->getWorld()));
                        $sender->teleport(new Position($sender->getPosition()->getX() + 2, $sender->getPosition()->getY() - 100, $sender->getPosition()->getZ(), $sender->getWorld()));
                        $sender->sendMessage(Utils::getPrefix() . "Vous avez bien dégelé " . Utils::getRankPlayer($player) . ".");
                    }else{
                        $player->setImmobile();
                        $player->sendMessage("§bFreeze §l§7» §rVous venez d'être gelé par " . Utils::getRankPlayer($sender) . ".");
                        $sender->teleport(new Position($sender->getPosition()->getX() + 2, $sender->getPosition()->getY() + 100, $sender->getPosition()->getZ(), $sender->getWorld()));
                        $player->teleport(new Position($player->getPosition()->getX(), $player->getPosition()->getY() + 100, $player->getPosition()->getZ(), $player->getWorld()));
                        $sender->sendMessage(Utils::getPrefix() . "Vous avez bien gelé " . Utils::getRankPlayer($player) . ".");
                    }
                }elseif($item == 369){
                    $event->setKnockBack(100);
                }elseif($item == 353) {
                    $config = Utils::getConfigFile("PlayerInfos/" . $player->getName(), "yml");
                    $money = Utils::getConfigFile("Stats/Money", "json");
                    $kill = Utils::getConfigFile("Stats/Kill", "json");
                    $sender->sendMessage(Utils::getPrefix() . "Voici les informations de " . Utils::getRankPlayer($player) . " :");
                    $sender->sendMessage("  ");
                    $sender->sendMessage("Nom : §9" . $player->getName());
                    $sender->sendMessage("Grade : " . Core::getInstance()->getRankAPI()->getRankColor($player));
                    $sender->sendMessage("Rank : " . Core::getInstance()->getRankAPI()->getClassPlayer($player));
                    $sender->sendMessage("Os : " . $config->get("os"));
                    $sender->sendMessage("Faction : §9Aucune");
                    $sender->sendMessage("  ");
                    $sender->sendMessage("Première connection : §9" . $config->get("first_connexion"));
                    $sender->sendMessage("Temps de jeu : §9" . PlayTimeAPI::playTime($player->getName()));
                    $sender->sendMessage("  ");
                    $sender->sendMessage("Argent : §9" . $money->get($player->getName()));
                    $sender->sendMessage("Kill : §9" . $kill->get($player->getName()));
                    $sender->sendMessage(" ");
                    $sender->sendMessage("Bannissement : §9" . $config->get("bannissement"));
                    $sender->sendMessage("Expulsion : §9" . $config->get("expulsion"));
                    $sender->sendMessage("Mute : §9" . $config->get("mute"));
                    $sender->sendMessage("Avertissement : §9" . $config->get("avertissement"));
                    $sender->sendMessage("  ");
                }elseif($item == 258){
                    $form = new CustomForm(function (LinesiaPlayer $sender, $data) use ($player){
                        if ($data === null){
                            return true;
                        }
                        $config = Utils::getConfigFile("PlayerInfos/" . $player->getName(), "yml");
                        $config->set("expulsion", $config->get("expulsion") + 1);
                        $config->save();
                        Server::getInstance()->broadcastMessage("[§l§c!§r] " . Utils::getRankPlayer($player) . "§f vient de se faire expulser du serveur par " . Utils::getRankPlayer($sender) . "§f motif : §9" . $data[1]);
                        $player->kick("[§c§l!§r] Vous avez été expulsé du serveur : \n- Staff : " . Utils::getRankPlayer($sender) . "\n- Motif : §9" . $data[1] . "§f,\n§e" . Core::DISCORD);
                        return true;
                    });
                    $form->setTitle("§9- §fExpulsion §9-");
                    $form->addDropdown("§l§7» §rJoueur", [$player->getName()]);
                    $form->addInput("§l§7» §rRaison");
                    $sender->sendForm($form);
                }
            }
        }
    }

    /**
     * @param EntityItemPickupEvent $event
     * @return void
     */
    public function onPlayerPickupItem(EntityItemPickupEvent $event): void{
        $sender = $event->getEntity();
        if ($sender instanceof LinesiaPlayer){
            if (StaffModeAPI::getVanish($sender)){
                $event->cancel();
            }
        }
    }
}
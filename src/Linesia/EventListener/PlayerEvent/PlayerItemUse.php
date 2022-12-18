<?php

namespace Linesia\EventListener\PlayerEvent;

use Linesia\Player\LinesiaPlayer;
use Linesia\Utils\Utils;
use pocketmine\entity\effect\EffectInstance;
use pocketmine\entity\effect\VanillaEffects;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerExhaustEvent;
use pocketmine\event\player\PlayerInteractEvent;
use pocketmine\event\player\PlayerItemUseEvent;
use pocketmine\item\EnderPearl;
use pocketmine\item\ItemFactory;

class PlayerItemUse implements Listener {

    private array $cooldowns;

    private array $cooldown_vitesse;
    private array $cooldown_force;

    public function onPlayerItemUse(PlayerItemUseEvent $event): void{
        $item = $event->getItem();
        $player = $event->getPlayer();

        if ($player instanceof LinesiaPlayer) {

            /* EnderPearl CoolDown */

            if($event->getItem()->getId() === 423){
                $event->cancel();
                if ($player->getHealth() == 20){
                    return;
                }
                $player->getInventory()->removeItem(ItemFactory::getInstance()->get(423));
                $player->setHealth($player->getHealth() + 4);
                $player->sendPopup("§a+ 2");
            }

            if (!$item instanceof EnderPearl) return;
            $time = ($this->cooldowns[$player->getName()] ?? 0) - time();
            if ($time > 0) {
                $event->cancel();
                $player->sendMessage(Utils::getPrefix() . "Merci d'attendre encore §9" . $time . " seconde(s)§f.");
                return;
            }
            $this->cooldowns[$player->getName()] = (time() + 15);
        }
    }

    public function onPlayerInteract(PlayerInteractEvent $event): bool {
        $player = $event->getPlayer();
        if ($player instanceof LinesiaPlayer) {
            /* Stick */
            if ($player->getInventory()->getItemInHand()->getId() == 369){
                if (!isset($this->cooldown_vitesse[$player->getName()]) or $this->cooldown_vitesse[$player->getName()] - time() <= 0){
                    $player->getEffects()->add(new EffectInstance(VanillaEffects::SPEED(), 10, 2));
                    $player->getInventory()->removeItem(ItemFactory::getInstance()->get(369));
                    $this->cooldown_vitesse[$player->getName()] = time() + 15;
                }else{
                    $time = $this->cooldown_vitesse[$player->getName()] - time();
                    $event->cancel();
                    $player->sendMessage(Utils::getPrefix() . "Merci d'attendre encore §9" . $time . " seconde(s)§f.");
                }
            }elseif($player->getInventory()->getItemInHand()->getId() == 370) {
                if (!isset($this->cooldown_force[$player->getName()]) or $this->cooldown_force[$player->getName()] - time() <= 0) {
                    $player->getEffects()->add(new EffectInstance(VanillaEffects::STRENGTH(), 10, 2));
                    $player->getInventory()->removeItem(ItemFactory::getInstance()->get(370));
                    $this->cooldown_force[$player->getName()] = time() + 15;
                } else {
                    $event->cancel();
                    $time = $this->cooldown_force[$player->getName()] - time();
                    $player->sendMessage(Utils::getPrefix() . "Merci d'attendre encore §9" . $time . " seconde(s)§f.");
                }
            }elseif($event->getItem()->getId() === 423){
                $event->cancel();
                if ($player->getHealth() == 20){
                    return true;
                }
                $player->getInventory()->removeItem(ItemFactory::getInstance()->get(423));
                $player->setHealth($player->getHealth() + 4);
                $player->sendPopup("§a+ 2");
            }
        }
        return true;
    }

    public function onPlayerEx(PlayerExhaustEvent $event){
        $event->cancel();
    }

}
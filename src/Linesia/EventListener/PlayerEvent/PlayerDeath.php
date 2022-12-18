<?php

namespace Linesia\EventListener\PlayerEvent;

use JsonException;
use Linesia\Core;
use Linesia\Player\LinesiaPlayer;
use Linesia\Utils\Utils;
use pocketmine\event\entity\EntityDamageByEntityEvent;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerDeathEvent;

class PlayerDeath implements Listener {

    /**
     * @throws JsonException
     */
    public function onPlayerDeath(PlayerDeathEvent $event){
        $sender = $event->getPlayer();

        if (isset(PlayerAttack::$cooldown_combat[$sender->getName()])){
            unset(PlayerAttack::$cooldown_combat[$sender->getName()]);
        }

        $event->setDeathMessage("");

        $cause = $sender->getLastDamageCause();
        if ($cause instanceof EntityDamageByEntityEvent){
            $damager = $cause->getDamager();
            if ($damager instanceof LinesiaPlayer){
                if (isset(PlayerAttack::$cooldown_combat[$damager->getName()])){
                    unset(PlayerAttack::$cooldown_combat[$damager->getName()]);
                    $damager->sendMessage(Utils::getPrefix() . "Vous n'êtes plus en combat.");
                }
                $config = Utils::getConfigFile("Stats/Kill", "json");
                $config->set($damager->getName(), $config->get($damager->getName()) + 1);
                $config->save();
                $money = Utils::getConfigFile("Stats/Money", "json");
                $money->set($damager->getName(), $money->get($damager->getName()) + 10);
                $money->save();

                if ($config->get($damager->getName()) === 10){
                    Core::getInstance()->getRankAPI()->setClassPlayer($damager->getName(), "§gBronze II");
                    $damager->sendMessage(Utils::getPrefix() . "Félicitation vous venez de passer §gBronze II§f.");
                }elseif($config->get($damager->getName() === 20)){
                    Core::getInstance()->getRankAPI()->setClassPlayer($damager->getName(), "§gBronze III");
                    $damager->sendMessage(Utils::getPrefix() . "Félicitation vous venez de passer §gBronze III§f.");
                }elseif($config->get($damager->getName() === 35)){
                    Core::getInstance()->getRankAPI()->setClassPlayer($damager->getName(), "§7Silver I");
                    $damager->sendMessage(Utils::getPrefix() . "Félicitation vous venez de passer §7Silver I§f.");
                }elseif($config->get($damager->getName()) === 45){
                    Core::getInstance()->getRankAPI()->setClassPlayer($damager->getName(), "§7Silver II");
                    $damager->sendMessage(Utils::getPrefix() . "Félicitation vous venez de passer §7Silver II§f.");
                }elseif($config->get($damager->getName()) === 55){
                    Core::getInstance()->getRankAPI()->setClassPlayer($damager->getName(), "§7Silver III");
                    $damager->sendMessage(Utils::getPrefix() . "Félicitation vous venez de passer §7Silver III§f.");
                }elseif($config->get($damager->getName()) === 70){
                    Core::getInstance()->getRankAPI()->setClassPlayer($damager->getName(), "§eOr I");
                    $damager->sendMessage(Utils::getPrefix() . "Félicitation vous venez de passer §eOr I§f.");
                }elseif($config->get($damager->getName()) === 80){
                    Core::getInstance()->getRankAPI()->setClassPlayer($damager->getName(), "§eOr II");
                    $damager->sendMessage(Utils::getPrefix() . "Félicitation vous venez de passer §eOr II§f.");
                }elseif($config->get($damager->getName()) === 90){
                    Core::getInstance()->getRankAPI()->setClassPlayer($damager->getName(), "§eOr III");
                    $damager->sendMessage(Utils::getPrefix() . "Félicitation vous venez de passer §eOr III§f.");
                }elseif($config->get($damager->getName()) === 105){
                    Core::getInstance()->getRankAPI()->setClassPlayer($damager->getName(), "§dPlatinium I");
                    $damager->sendMessage(Utils::getPrefix() . "Félicitation vous venez de passer §dPlatinium I§f.");
                }elseif($config->get($damager->getName()) === 115){
                    Core::getInstance()->getRankAPI()->setClassPlayer($damager->getName(), "§dPlatinium II");
                    $damager->sendMessage(Utils::getPrefix() . "Félicitation vous venez de passer §dPlatinium II§f.");
                }elseif($config->get($damager->getName()) === 125){
                    Core::getInstance()->getRankAPI()->setClassPlayer($damager->getName(), "§dPlatinium III");
                    $damager->sendMessage(Utils::getPrefix() . "Félicitation vous venez de passer §dPlatinium III§f.");
                } elseif($config->get($damager->getName()) === 140){
                    Core::getInstance()->getRankAPI()->setClassPlayer($damager->getName(), "§bDiamant I");
                    $damager->sendMessage(Utils::getPrefix() . "Félicitation vous venez de passer §bDiamant I§f.");
                }elseif($config->get($damager->getName()) === 150){
                    Core::getInstance()->getRankAPI()->setClassPlayer($damager->getName(), "§bDiamant II");
                    $damager->sendMessage(Utils::getPrefix() . "Félicitation vous venez de passer §bDiamant II§f.");
                }elseif($config->get($damager->getName()) === 160){
                    Core::getInstance()->getRankAPI()->setClassPlayer($damager->getName(), "§bDiamant III");
                    $damager->sendMessage(Utils::getPrefix() . "Félicitation vous venez de passer §bDiamant III§f.");
                }elseif($config->get($damager->getName()) === 175){
                    Core::getInstance()->getRankAPI()->setClassPlayer($damager->getName(), "§aPro I");
                    $damager->sendMessage(Utils::getPrefix() . "Félicitation vous venez de passer §aPro I§f.");
                }elseif($config->get($damager->getName()) === 185){
                    Core::getInstance()->getRankAPI()->setClassPlayer($damager->getName(), "§aPro II");
                    $damager->sendMessage(Utils::getPrefix() . "Félicitation vous venez de passer §aPro II§f.");
                }elseif($config->get($damager->getName()) === 195){
                    Core::getInstance()->getRankAPI()->setClassPlayer($damager->getName(), "§aPro III");
                    $damager->sendMessage(Utils::getPrefix() . "Félicitation vous venez de passer §aPro III§f.");
                }elseif($config->get($damager->getName()) === 210){
                    Core::getInstance()->getRankAPI()->setClassPlayer($damager->getName(), "§6Master I");
                    $damager->sendMessage(Utils::getPrefix() . "Félicitation vous venez de passer §6Master I§f.");
                }elseif($config->get($damager->getName()) === 220){
                    Core::getInstance()->getRankAPI()->setClassPlayer($damager->getName(), "§6Master II");
                    $damager->sendMessage(Utils::getPrefix() . "Félicitation vous venez de passer §6Master II§f.");
                }elseif($config->get($damager->getName()) === 230){
                    Core::getInstance()->getRankAPI()->setClassPlayer($damager->getName(), "§6Master III");
                    $damager->sendMessage(Utils::getPrefix() . "Félicitation vous venez de passer §6Master III§f.");
                }elseif($config->get($damager->getName()) === 245){
                    Core::getInstance()->getRankAPI()->setClassPlayer($damager->getName(), "§5Elite I");
                    $damager->sendMessage(Utils::getPrefix() . "Félicitation vous venez de passer §5Elite I§f.");
                }elseif($config->get($damager->getName()) === 255){
                    Core::getInstance()->getRankAPI()->setClassPlayer($damager->getName(), "§5Elite II");
                    $damager->sendMessage(Utils::getPrefix() . "Félicitation vous venez de passer §5Elite II§f.");
                }elseif($config->get($damager->getName()) === 265){
                    Core::getInstance()->getRankAPI()->setClassPlayer($damager->getName(), "§5Elite III");
                    $damager->sendMessage(Utils::getPrefix() . "Félicitation vous venez de passer §5Elite III§f.");
                }
            }
        }

    }

}
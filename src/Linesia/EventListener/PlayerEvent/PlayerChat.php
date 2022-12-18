<?php

namespace Linesia\EventListener\PlayerEvent;

use Linesia\Core;
use Linesia\Player\LinesiaPlayer;
use Linesia\Utils\Utils;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerChatEvent;

class PlayerChat implements Listener {

    private array $cooldown;

    public function onPlayerChat(PlayerChatEvent $event){
        $sender = $event->getPlayer();
        $message = $event->getMessage();

        if ($sender instanceof LinesiaPlayer) {

            $event->setFormat(Core::getInstance()->getRankAPI()->getChatFormat($sender, $message));

            /* Mute */
            if (Utils::getConfigFile("Sanctions/Mute", "json")->exists($sender->getName())){
                if (!$sender->getPermission("linesia.mute.bypass")){

                    $mute = Utils::getConfigFile("Sanctions/Mute", "json")->get($sender->getName());
                    $mute = explode(":", $mute);
                    $staff = $mute[0];
                    $time = $mute[1];
                    $raison = $mute[2];

                    if ($time - time() <= 0){
                        Utils::getConfigFile("Sanctions/Mute", "json")->remove($sender->getName());
                        $sender->sendMessage(Utils::getPrefix() . "Vous n'êtes actuellement plus mute, attention à bien respecter le règlement.");
                    }

                    $timeRestant = $time - time();
                    $jours = intval(abs($timeRestant / 86400));
                    $timeRestant = $timeRestant - ($jours * 86400);
                    $heures = intval(abs($timeRestant / 3600));
                    $timeRestant = $timeRestant - ($heures * 3600);
                    $minutes = intval(abs($timeRestant / 60));
                    $secondes = intval(abs($timeRestant - $minutes * 60));
                    if ($jours > 0) {
                        $formatTemp = "$jours jour(s) et $heures heure(s)";
                    } else if ($heures > 0) {
                        $formatTemp = "$heures heure(s) et $minutes minute(s)";
                    } else if ($minutes > 0) {
                        $formatTemp = "$minutes minute(s) et $secondes seconde(s)";
                    } else {
                        $formatTemp = "$secondes seconde(s)";
                    }

                    $sender->sendMessage("§cMute §l§7» §rVous êtes encore mute :\n§l§7» §rTemps : §e" . $formatTemp . "\n§l§7» §rStaff : " . $staff . "\n§l§7» §rRaison : §e" . $raison ." \n§l§7» §rSi vous trouvez votre sanction injuste, nous vous invitons à ouvrir un ticket sur notre discord dans la catégorie support (§e" . Core::DISCORD . "§f).");
                    $event->cancel();
                }
            }

            /* MuteChat */
            if (Utils::getConfig()->get("mute.chat") === true){
                if (!$sender->getPermission("linesia.mutechat.bypass")){
                    $sender->sendMessage("§cMute§f-§cChat §l§7» §rLe chat est actuellement désactivé pour le motif §e" . Utils::getConfig()->get("mute.chat.raison") . " §f!");
                    $event->cancel();
                }
            }

            /* CoolDown */
            if (!$sender->getPermission("linesia.cooldown.bypass")){
                if (!isset($this->cooldown[$sender->getName()])) $this->cooldown[$sender->getName()] = time();
                if (time() < $this->cooldown[$sender->getName()]){
                    $sender->sendMessage("§cCoolDown §l§7» §rMerci d'écrire moins vite dans le chat.");
                    $event->cancel();
                }else{
                    $this->cooldown[$sender->getName()] = time() + 1.35;
                }
            }

        }

    }

}
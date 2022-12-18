<?php

namespace Linesia\Command\Moderation;

use Linesia\Player\LinesiaPlayer;
use Linesia\Utils\Utils;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;

class MuteListCommand extends Command {

    public function __construct() {
        parent::__construct("mutelist", "MuteList - Linesia", "/mutelist", ["muteliste"]);
        $this->setPermission(implode(";", [
            "linesia.*",
            "linesia.moderation.mutelist"
        ]));
    }

    public function execute(CommandSender $sender, string $commandLabel, array $args): bool {
        if (!$sender instanceof LinesiaPlayer){
            return true;
        }
        if (!$sender->getPermission("linesia.moderation.mutelist")){
            $sender->sendMessage(Utils::getPrefix() . "§cVous n'avez pas la permission de faire ceci.");
            return true;
        }

        $mutelist = Utils::getConfigFile("Sanctions/Mute", "json")->getAll();
        if (count($mutelist) <= 0) {
            $sender->sendMessage(Utils::getPrefix() . "§cIl n'y a aucun joueur mute");
            return true;
        }
        $mutelist = array_reverse($mutelist);
        $maxpages = intval(abs(count($mutelist) / 10));
        $reste = count($mutelist) % 10;
        if ($reste > 0) {
            $maxpage = $maxpages + 1;
        } else {
            $maxpage = $maxpages;
        }
        if ((isset($args[0])) and (!(is_numeric($args[0])))) {
            $sender->sendMessage(Utils::getPrefix() . "Merci de mettre un nombre entre §91 §fet §9" . $maxpage . " §f!");
            return true;
        }
        if (isset($args[0])) $args[0] = intval($args[0]);
        if (!isset($args[0]) or $args[0] == 1) {
            $deptop = 1;
            $fintop = 11;
            $page = 1;
        } else {
            $deptop = (($args[0] - 1) * 10) + 1;
            $fintop = (($args[0] - 1) * 10) + 11;
            $page = $args[0];
        }
        if ($page > $maxpage) {
            $sender->sendMessage(Utils::getPrefix() . "Merci de mettre un nombre entre §91 et §9" . $maxpage . " §f!");
            return true;
        }
        $top = 1;
        $sender->sendMessage("§9- §fListe des joueurs mute (§9" . $page . "§f/§9" . $maxpage . "§f) §9-");
        $sender->sendMessage(" ");
        foreach ($mutelist as $name => $value) {
            if ($top === $fintop) break;
            if ($top >= $deptop) {
                $ban = explode(":", $value);
                $time = $ban[1];
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
                $sender->sendMessage("#" . $top . " §9§l» §r §c" . $name . " §f- " . $formatTemp);

            }
            $top++;
        }
        return true;
    }

}
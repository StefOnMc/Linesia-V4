<?php

namespace Linesia\Task;

use Linesia\Core;
use Linesia\Player\LinesiaPlayer;
use Linesia\Utils\Utils;
use pocketmine\scheduler\Task;

class BanTask extends Task {

    private LinesiaPlayer $sender;
    private int $time = 1;

    public function __construct($sender) {
        $this->sender = $sender;
    }

    public function onRun(): void {

        if ($this->time === 0) {

            $sender = $this->sender;

            if (!Utils::getConfigFile("Sanctions/Bannissement", "json")->exists($sender->getName())){
                return;
            }

            $ban = Utils::getConfigFile("Sanctions/Bannissement", "json")->get($sender->getName());
            $ban = explode(":", $ban);

            $staff = $ban[0];
            $temps = $ban[1];
            $raison = $ban[2];

            $timeRestant = $temps - time();
            $jours = intval(abs($timeRestant / 86400));
            $timeRestant = $timeRestant - ($jours * 86400);
            $heures = intval(abs($timeRestant / 3600));
            $timeRestant = $timeRestant - ($heures * 3600);
            $minutes = intval(abs($timeRestant / 60));
            $secondes = intval(abs($timeRestant - $minutes * 60));

            if($jours > 0){
                $formatTemp = "$jours jour(s) et $heures heure(s)";
            } else if($heures > 0){
                $formatTemp = "$heures heure(s) et $minutes minute(s)";
            } else if($minutes > 0){
                $formatTemp = "$minutes minute(s) et $secondes seconde(s)";
            } else {
                $formatTemp = "$secondes seconde(s)";
            }
            $sender->kick("                            §f[§9BANNISSEMENT§f]                            \n              Staff : " . $staff . "\n              §fTemps : §9" . $formatTemp ."\n              §fMotif(s) : §9" . $raison . "§f\n                  §e" . Core::DISCORD, "t");

        }else{
            $this->time--;
        }


    }

}
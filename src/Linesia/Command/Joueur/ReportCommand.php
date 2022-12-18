<?php

namespace Linesia\Command\Joueur;

use Linesia\Core;
use Linesia\Libs\Form\CustomForm;
use Linesia\Libs\Form\SimpleForm;
use Linesia\Player\LinesiaPlayer;
use Linesia\Utils\Utils;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;

class ReportCommand extends Command {

    private array $playerList;

    public function __construct() {
        parent::__construct("report", "Report - Linesia", "/report", ["report"]);
    }

    public function execute(CommandSender $sender, string $commandLabel, array $args): bool {
        if (!$sender instanceof LinesiaPlayer){
            return true;
        }
        $form = new SimpleForm(function (LinesiaPlayer $sender, $data){
            if ($data === null){
                return true;
            }
            $this->sendReportForm($sender);
            return true;
        });
        $form->setTitle("§9- §fLinésia §9-");
        $form->setContent("§l§7» §rBienvenue dans le menu de report, avant de signaler une personne merci de vous assurez que le motif est bel est bien valable. Si vous n'êtes pas sur posez la question en amont à un membre du staff ou de regarder le règlement sur notre discord (§e" . Core::DISCORD . "§f). Tous signalement qui sera à but de troll ou abusif est sanctionnable.");
        $form->addButton("§9- §fSignaler", SimpleForm::IMAGE_TYPE_URL, "https://cdn.discordapp.com/attachments/1013365916639170630/1018223760840601660/Sans_titre-1.png");
        $sender->sendForm($form);
        return true;
    }

    public function sendReportForm(LinesiaPlayer $sender): bool {
        $list = [];
        foreach (Core::getInstance()->getServer()->getOnlinePlayers() as $player){
            $list[] = $player->getName();
        }
        $this->playerList[$sender->getName()] = $list;
        $form = new CustomForm(function (LinesiaPlayer $sender, $data){
            if ($data === null){
                return true;
            }
            $index = $data[1];
            $playerName = $this->playerList[$sender->getName()][$index];
            $raison = $data[2];
            Utils::sendDiscordLogs($sender->getName() . " vient de report " . $playerName . " pour le motif " . $raison . ".", "**REPORT**", 0xEE011E);
            foreach (Core::getInstance()->getServer()->getOnlinePlayers() as $staff) {
                if ($staff instanceof LinesiaPlayer) {
                    if ($staff->getPermission("linesia.report.logs")) {
                        $staff->sendMessage("§cReport §l§7» §r" . Utils::getRankPlayer($sender) . " §fvient de report §9" . $playerName . " §fpour le motif §9" . $raison . "§f.");
                    }
                }
            }
            $sender->sendMessage(Utils::getPrefix() . "Votre report a bien été pris en compte.");
            return true;

        });
        $form->setTitle("§9- §fReport §9-");
        $form->addLabel("§l§7» §rMerci de vérifier que votre report en vaut la penne avant de l'envoyé aux membres du staffs.");
        $form->addDropdown("§l§7» §rListe des joueurs", $this->playerList[$sender->getName()]);
        $form->addInput("§l§7» §rRaison");
        $sender->sendForm($form);
        return true;
    }

}
<?php

namespace Linesia\Command\Administration;

use Linesia\API\WarpAPI;
use Linesia\Libs\Form\CustomForm;
use Linesia\Libs\Form\SimpleForm;
use Linesia\Player\LinesiaPlayer;
use Linesia\Utils\Utils;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;

class WarpCommands extends  Command {

    public function __construct() {
        parent::__construct("warps", "Warps - Linesia", "/warps", ["warp"]);
    }

    public function execute(CommandSender $sender, string $commandLabel, array $args): bool {
        if (!$sender instanceof LinesiaPlayer){
            return true;
        }

        if (count($args) < 1){
            $sender->sendMessage(Utils::getPrefix() . "Usage : /warp <warp/list>.");
        }else{
            if ($args[0] === "admin"){

                if (!$sender->getPermission("linesia.administration.warp")){
                    $sender->sendMessage(Utils::getPrefix() . "§cVous n'avez pas la permission de faire ceci.");
                    return true;
                }

                $form = new SimpleForm(function (LinesiaPlayer $sender, $data){
                    if ($data === null){
                        return true;
                    }
                    if ($data === 0){
                        $this->sendCreateWarp($sender);
                    }elseif($data === 1){
                        $this->sendRemoveWarp($sender);
                    }elseif($data === 2){
                        $sender->sendMessage(Utils::getPrefix() . "§cMaintenance.");
                    }
                    return true;
                });
                $form->setTitle("§9- §fWarps §9-");
                $form->addButton("§l§9» §r§fAjouter un warp");
                $form->addButton("§l§9» §r§fSupprimer un warp");
                $form->addButton("§l§9» §r§fModifier un warp");
                $sender->sendForm($form);
                return true;
            }elseif($args[0] === "list"){
                $config = Utils::getConfigFile("Warps/Warps", "yml");
                $allwarp = $config->getAll();
                $sender->sendMessage(Utils::getPrefix() . "Voici la liste des warps du serveur :");
                $sender->sendMessage(" ");
                foreach ($allwarp as $warp){
                    $sender->sendMessage("- §9" . $warp["Name"]);
                }
                return true;
            }
            $config = Utils::getConfigFile("Warps/Warps", "yml");
            if (!$config->exists($args[0])){
                $sender->sendMessage(Utils::getPrefix() . "Ce warp n'existe pas.");
                return true;
            }

            $warp_permission = $config->getNested($args[0] . ".Permission");
            if (!$sender->getPermission($warp_permission)){
                $sender->sendMessage(Utils::getPrefix() . "§cVous n'avez pas la permission de vous téléporter à ce warp.");
                return true;
            }
            WarpAPI::teleportWarp($sender, $args[0]);
            $sender->sendMessage(Utils::getPrefix() . "Vous avez été téléporté au warp §9" . $args[0] . "§f.");
        }
        return true;
    }

    public function sendCreateWarp(LinesiaPlayer $sender){
        $form = new CustomForm(function (LinesiaPlayer $sender, $data){
            if ($data === null){
                return true;
            }
            $warp_name = $data[0];
            $warp_permission = $data[1];
            WarpAPI::createWarp($warp_name, $sender->getPosition()->getX(), $sender->getPosition()->getY(), $sender->getPosition()->getZ(), $sender->getWorld()->getFolderName(), $warp_permission);
            $sender->sendMessage(Utils::getPrefix() . "Vous avez bien définis le warp §9" . $warp_name . "§f.");
            return true;
        });
        $form->setTitle("§9- §fAddWarp §9-");
        $form->addInput("§l§7» §rNom du warp");
        $form->addInput("§l§7» §rPermission (default: null)");
        $sender->sendForm($form);
    }

    public function sendRemoveWarp(LinesiaPlayer $sender){
        $form = new CustomForm(function (LinesiaPlayer $sender, $data){
            if ($data === null){
                return true;
            }
            $warp_name = $data[0];
            $config = Utils::getConfigFile("Warps/Warps", "yml");
            if (!$config->exists($warp_name)){
                $sender->sendMessage(Utils::getPrefix() . "Ce warp n'existe pas, faites /warps list.");
            }
            WarpAPI::removeWarp($warp_name);
            $sender->sendMessage(Utils::getPrefix() . "Vous avez bien supprimé le warp §9" . $warp_name . "§f.");
            return true;
        });
        $form->setTitle("§9- §fDelWarp §9-");
        $form->addInput("§l§7» §rNom du warp");
        $sender->sendForm($form);
    }

}
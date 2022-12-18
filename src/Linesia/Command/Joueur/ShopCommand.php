<?php

namespace Linesia\Command\Joueur;

use Linesia\Libs\Form\CustomForm;
use Linesia\Libs\Form\SimpleForm;
use Linesia\Player\LinesiaPlayer;
use Linesia\Utils\Utils;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\item\ItemFactory;
use pocketmine\item\ItemIds;
use pocketmine\item\VanillaItems;
use pocketmine\utils\Config;

class ShopCommand extends Command {

    private static function getMoney(): Config {
        return Utils::getConfigFile("Stats/Money", "yml");
    }

    public function __construct() {
        parent::__construct("shop", "Shop - Linesia");
    }

    public function execute(CommandSender $sender, string $commandLabel, array $args): bool {
        if (!$sender instanceof LinesiaPlayer){
            return true;
        }

        $this->sendShopForm($sender);
        return true;
    }

    private function sendShopForm(LinesiaPlayer $sender){
        $form = new SimpleForm(function (LinesiaPlayer $sender, $data){
            if ($data === null){
                return true;
            }
            if ($data === 0){
                $this->sendItemsModeesForm($sender);
            }
            return true;
        });
        $form->setTitle("§9- §fShop §9-");
        $form->setContent("§l§7» §rBienvenue dans le shop, choisissez la catégorie dans laquelle vous voulez aller.");
        $form->addButton("§9-> §fItems Modés", 0, "textures/items/emerald");
        $form->addButton("§cQuitter");
        $sender->sendForm($form);
    }

    public function sendItemsModeesForm(LinesiaPlayer $sender) {
        $form = new SimpleForm(function (LinesiaPlayer $sender, $data){

            if ($data === null){
                return true;
            }

            if ($data === 0){
                $this->sendFakePearlForm($sender);
            }elseif($data === 1){
                $this->sendStickSpeed($sender);
            }elseif($data === 2){
                $this->sendStickForceForm($sender);
            }elseif($data === 3){
                $sender->sendMessage(Utils::getPrefix() . "§cProchainement...");
            }elseif($data === 4){
                $sender->sendMessage(Utils::getPrefix() . "§cProchainement...");
            }elseif($data === 5){
                $this->sendLingotAmethyste($sender);
            }elseif($data === 6){
                $this->sendCasqueAmeRenfo($sender);
            }elseif($data === 7){
                $this->sendPlastronAmeRenfo($sender);
            }elseif($data === 8){
                $this->sendLeggingsAmeRenfo($sender);
            }elseif($data === 9){
                $this->sendBottesAmeRenfo($sender);
            }elseif($data === 10){
                $this->sendDynamiteForm($sender);
            }
            return true;
        });
        $form->setTitle("§9- §fShop §9-");
        $form->setContent("§l§7» §rVoici l'interface des items modés.\n\n§7--------------------------------\n§9- §fVous avez actuellement : §9" . self::getMoney()->get($sender->getName()) . "$ §f\n§7--------------------------------\n ");
        $form->addButton("§9-> §fFake EnderPearl", 0, "textures/items/ender_pearl");
        $form->addButton("§9-> §fStick Speed", 0, "textures/items/ghast_tear");
        $form->addButton("§9-> §fStick Force", 0, "textures/items/blaze_rod");
        $form->addButton("§9-> §fStick Slowness\n§cProchainement", 0, "textures/blocks/barrier");
        $form->addButton("§9-> §fStick Blindness\n§cProchainement", 0, "textures/blocks/barrier");
        $form->addButton("§9-> §fLingot d'§dAméthyste", 0, "textures/items/gold_ingot");
        $form->addButton("§9-> §fCasque en §5Améthyste Renforcé", 0, "textures/items/gold_helmet");
        $form->addButton("§9-> §fPlastron en §5Améthyste Renforcé", 0, "textures/items/gold_chestplate");
        $form->addButton("§9-> §fJambière en §5Améthyste Renforcé", 0, "textures/items/gold_leggings");
        $form->addButton("§9-> §fBottes en §5Améthyste Renforcé", 0, "textures/items/gold_boots");
        $form->addButton("§9-> §fDynamite", 0, "textures/items/snowball");
        $form->addButton("§cRetour", 0, "textures/blocks/barrier");
        $sender->sendForm($form);
    }

    public function sendFakePearlForm(LinesiaPlayer $sender){
        $form = new CustomForm(function (LinesiaPlayer $sender, $data){
            if ($data === null){
                return true;
            }
            $index = ["§aAchat"];
            if ($index[$data[1]] === "§aAchat"){
                if ($sender->getInventory()->canAddItem(VanillaItems::EGG())){
                    if (self::getMoney()->get($sender->getName()) >= $data[2] * 65){
                        self::getMoney()->set($sender->getName(), self::getMoney()->get($sender->getName()) - $data[2] * 65);
                        self::getMoney()->save();
                        $sender->getInventory()->addItem(ItemFactory::getInstance()->get(344, 0, $data[2]));
                        $sender->sendMessage(Utils::getPrefix() . "Vous avez acheté §9" . $data[2] . " §fFake EnderPearls au pris de §9" . $data[2] * 65 . "$" . "§f.");
                    }else{
                        $sender->sendMessage(Utils::getPrefix() . "§cVous n'avez pas l'argent requis pour acheter §9" . $data[2] . "§c Fake EnderPearls.");
                    }
                }else{
                    $sender->sendMessage(Utils::getPrefix() . "§cVotre inventaire est plein.");
                }
            }
            return true;
        });
        $form->setTitle("§9- §fShop §9-");
        $form->addLabel("§l§7» §rVoici l'interface d'achat des Fakes EnderPearls :\n\n§7--------------------------------\n§9- §fAchat §f: §965$" . "§f/u§f.\n§9- §fVous avez actuellement : §9" . self::getMoney()->get($sender->getName()) . "$\n§7--------------------------------\n");
        $form->addDropdown("§l§7» §rAction", ["§aAchat"]);
        $form->addSlider("§l§7» §rCombien en voulez-vous", "1", self::getMoney()->get($sender->getName()) / 65);
        $sender->sendForm($form);
    }

    public function sendStickSpeed(LinesiaPlayer $sender){
        $form = new CustomForm(function (LinesiaPlayer $sender, $data){
            if ($data === null){
                return true;
            }
            $index = ["§aAchat"];
            if ($index[$data[1]] === "§aAchat"){
                if ($sender->getInventory()->canAddItem(VanillaItems::GHAST_TEAR())){
                    if (self::getMoney()->get($sender->getName()) >= $data[2] * 5000){
                        self::getMoney()->set($sender->getName(), self::getMoney()->get($sender->getName()) - $data[2] * 5000);
                        self::getMoney()->save();
                        $sender->getInventory()->addItem(ItemFactory::getInstance()->get(370, 0, $data[2]));
                        $sender->sendMessage(Utils::getPrefix() . "Vous avez acheté §9" . $data[2] . " §fSpeed Stick au pris de §9" . $data[2] * 5000 . "$" . "§f.");
                    }else{
                        $sender->sendMessage(Utils::getPrefix() . "§cVous n'avez pas l'argent requis pour acheter §9" . $data[2] . "§c Speed Stick.");
                    }
                }else{
                    $sender->sendMessage(Utils::getPrefix() . "§cVotre inventaire est plein.");
                }
            }
            return true;
        });
        $form->setTitle("§9- §fShop §9-");
        $form->addLabel("§l§7» §rVoici l'interface d'achat des Speeds Sticks :\n\n§7--------------------------------\n§9- §fAchat §f: §95000$" . "§f/u§f.\n§9- §fVous avez actuellement : §9" . self::getMoney()->get($sender->getName()) . "$\n§7--------------------------------\n");
        $form->addDropdown("§l§7» §rAction", ["§aAchat"]);
        $form->addSlider("§l§7» §rCombien en voulez-vous", "1", self::getMoney()->get($sender->getName()) / 5000);
        $sender->sendForm($form);
    }

    public function sendStickForceForm(LinesiaPlayer $sender){
        $form = new CustomForm(function (LinesiaPlayer $sender, $data){
            if ($data === null){
                return true;
            }
            $index = ["§aAchat"];
            if ($index[$data[1]] === "§aAchat"){
                if ($sender->getInventory()->canAddItem(VanillaItems::BLAZE_ROD())){
                    if (self::getMoney()->get($sender->getName()) >= $data[2] * 5000){
                        self::getMoney()->set($sender->getName(), self::getMoney()->get($sender->getName()) - $data[2] * 5000);
                        self::getMoney()->save();
                        $sender->getInventory()->addItem(ItemFactory::getInstance()->get(370, 0, $data[2]));
                        $sender->sendMessage(Utils::getPrefix() . "Vous avez acheté §9" . $data[2] . " §fSpeed Force au pris de §9" . $data[2] * 5000 . "$" . "§f.");
                    }else{
                        $sender->sendMessage(Utils::getPrefix() . "§cVous n'avez pas l'argent requis pour acheter §9" . $data[2] . "§c Speed Force.");
                    }
                }else{
                    $sender->sendMessage(Utils::getPrefix() . "§cVotre inventaire est plein.");
                }
            }
            return true;
        });
        $form->setTitle("§9- §fShop §9-");
        $form->addLabel("§l§7» §rVoici l'interface d'achat des Speeds Force :\n\n§7--------------------------------\n§9- §fAchat §f: §95000$" . "§f/u§f.\n§9- §fVous avez actuellement : §9" . self::getMoney()->get($sender->getName()) . "$\n§7--------------------------------\n");
        $form->addDropdown("§l§7» §rAction", ["§aAchat"]);
        $form->addSlider("§l§7» §rCombien en voulez-vous", "1", self::getMoney()->get($sender->getName()) / 5000);
        $sender->sendForm($form);
    }

    public function sendLingotAmethyste(LinesiaPlayer $sender){
        $form = new CustomForm(function (LinesiaPlayer $sender, $data){
            if ($data === null){
                return true;
            }
            $index = ["§cVente"];
            if ($index[$data[1]] === "§cVente") {
                 if ($sender->getInventory()->contains(ItemFactory::getInstance()->get(388,0, $data[2]))) {
                        self::getMoney()->set($sender->getName(), self::getMoney()->get($sender->getName()) + $data[2] * 50);
                        self::getMoney()->save();
                        $sender->getInventory()->removeItem(ItemFactory::getInstance()->get(388, 0, $data[2]));
                        $sender->sendMessage(Utils::getPrefix() . "Vous avez vendu §9" . $data[2] . " §fAméthyste au pris de §9" . $data[2] * 50 . "$" . "§f.");
                  } else {
                    $sender->sendMessage(Utils::getPrefix() . "§cVous n'avez pas assez d'améthyste pour en vendre §9" . $data[2] . "§c.");
                }
            }
            return true;
        });
        $contents = $sender->getInventory()->getContents();
        $nombre = 0;
        foreach ($contents as $item) {
            if($item->getId() == ItemIds::EMERALD) {
                $nombre = $nombre + $item->getCount();
            }
        }
        $form->setTitle("§9- §fShop §9-");
        $form->addLabel("§l§7» §rVoici l'interface de vente de l'améthyste :\n\n§7--------------------------------\n§9- §fVente §f: §950$" . "§f/u§f.\n§9- §fVous avez actuellement : §9" . $nombre . " améthyste(s)" . "§f.\n§7--------------------------------\n");
        $form->addDropdown("§l§7» §rAction", ["§cVente"]);
        $form->addSlider("§l§7» §rCombien en vendez-vous", "1", $nombre);
        $sender->sendForm($form);
    }

    public function sendCasqueAmeRenfo(LinesiaPlayer $sender){
        $form = new CustomForm(function (LinesiaPlayer $sender, $data){
            if ($data === null){
                return true;
            }
            $index = ["§aAchat"];
            if ($index[$data[1]] === "§aAchat"){
                if ($sender->getInventory()->canAddItem(VanillaItems::GOLDEN_HELMET())){
                    if (self::getMoney()->get($sender->getName()) >= $data[2] * 17000){
                        self::getMoney()->set($sender->getName(), self::getMoney()->get($sender->getName()) - $data[2] * 17000);
                        self::getMoney()->save();
                        $sender->getInventory()->addItem(ItemFactory::getInstance()->get(314, 0, $data[2]));
                        $sender->sendMessage(Utils::getPrefix() . "Vous avez acheté §9" . $data[2] . " §fCasque en §daméthyste renforcé §fau pris de §9" . $data[2] * 17000 . "$" . "§f.");
                    }else{
                        $sender->sendMessage(Utils::getPrefix() . "§cVous n'avez pas l'argent requis pour acheter §9" . $data[2] . "§c Casque en §daméthyste renforcé §c.");
                    }
                }else{
                    $sender->sendMessage(Utils::getPrefix() . "§cVotre inventaire est plein.");
                }
            }
            return true;
        });
        $form->setTitle("§9- §fShop §9-");
        $form->addLabel("§l§7» §rVoici l'interface d'achat des Casque en §daméthyste renforcé §f :\n\n§7--------------------------------\n§9- §fAchat §f: §917000$" . "§f/u§f.\n§9- §fVous avez actuellement : §9" . self::getMoney()->get($sender->getName()) . "$\n§7--------------------------------\n");
        $form->addDropdown("§l§7» §rAction", ["§aAchat"]);
        $form->addSlider("§l§7» §rCombien en voulez-vous", "1", self::getMoney()->get($sender->getName()) / 17000);
        $sender->sendForm($form);
    }

    public function sendPlastronAmeRenfo(LinesiaPlayer $sender){
        $form = new CustomForm(function (LinesiaPlayer $sender, $data){
            if ($data === null){
                return true;
            }
            $index = ["§aAchat"];
            if ($index[$data[1]] === "§aAchat"){
                if ($sender->getInventory()->canAddItem(VanillaItems::GOLDEN_CHESTPLATE())){
                    if (self::getMoney()->get($sender->getName()) >= $data[2] * 20000){
                        self::getMoney()->set($sender->getName(), self::getMoney()->get($sender->getName()) - $data[2] * 20000);
                        self::getMoney()->save();
                        $sender->getInventory()->addItem(ItemFactory::getInstance()->get(315, 0, $data[2]));
                        $sender->sendMessage(Utils::getPrefix() . "Vous avez acheté §9" . $data[2] . " §fPlastron en §daméthyste renforcé §fau pris de §9" . $data[2] * 20000 . "$" . "§f.");
                    }else{
                        $sender->sendMessage(Utils::getPrefix() . "§cVous n'avez pas l'argent requis pour acheter §9" . $data[2] . "§c Plastron en §daméthyste renforcé §c.");
                    }
                }else{
                    $sender->sendMessage(Utils::getPrefix() . "§cVotre inventaire est plein.");
                }
            }
            return true;
        });
        $form->setTitle("§9- §fShop §9-");
        $form->addLabel("§l§7» §rVoici l'interface d'achat des Plastron en §daméthyste renforcé §f :\n\n§7--------------------------------\n§9- §fAchat §f: §920000$" . "§f/u§f.\n§9- §fVous avez actuellement : §9" . self::getMoney()->get($sender->getName()) . "$\n§7--------------------------------\n");
        $form->addDropdown("§l§7» §rAction", ["§aAchat"]);
        $form->addSlider("§l§7» §rCombien en voulez-vous", "1", self::getMoney()->get($sender->getName()) / 20000);
        $sender->sendForm($form);
    }

    public function sendLeggingsAmeRenfo(LinesiaPlayer $sender){
        $form = new CustomForm(function (LinesiaPlayer $sender, $data){
            if ($data === null){
                return true;
            }
            $index = ["§aAchat"];
            if ($index[$data[1]] === "§aAchat"){
                if ($sender->getInventory()->canAddItem(VanillaItems::GOLDEN_LEGGINGS())){
                    if (self::getMoney()->get($sender->getName()) >= $data[2] * 19000){
                        self::getMoney()->set($sender->getName(), self::getMoney()->get($sender->getName()) - $data[2] * 19000);
                        self::getMoney()->save();
                        $sender->getInventory()->addItem(ItemFactory::getInstance()->get(316, 0, $data[2]));
                        $sender->sendMessage(Utils::getPrefix() . "Vous avez acheté §9" . $data[2] . " §fPlastron en §daméthyste renforcé §fau pris de §9" . $data[2] * 19000 . "$" . "§f.");
                    }else{
                        $sender->sendMessage(Utils::getPrefix() . "§cVous n'avez pas l'argent requis pour acheter §9" . $data[2] . "§c Jambière en §daméthyste renforcé §c.");
                    }
                }else{
                    $sender->sendMessage(Utils::getPrefix() . "§cVotre inventaire est plein.");
                }
            }
            return true;
        });
        $form->setTitle("§9- §fShop §9-");
        $form->addLabel("§l§7» §rVoici l'interface d'achat des Jambière en §daméthyste renforcé §f :\n\n§7--------------------------------\n§9- §fAchat §f: §919000$" . "§f/u§f.\n§9- §fVous avez actuellement : §9" . self::getMoney()->get($sender->getName()) . "$\n§7--------------------------------\n");
        $form->addDropdown("§l§7» §rAction", ["§aAchat"]);
        $form->addSlider("§l§7» §rCombien en voulez-vous", "1", self::getMoney()->get($sender->getName()) / 19000);
        $sender->sendForm($form);
    }

    public function sendBottesAmeRenfo(LinesiaPlayer $sender){
        $form = new CustomForm(function (LinesiaPlayer $sender, $data){
            if ($data === null){
                return true;
            }
            $index = ["§aAchat"];
            if ($index[$data[1]] === "§aAchat"){
                if ($sender->getInventory()->canAddItem(VanillaItems::GOLDEN_BOOTS())){
                    if (self::getMoney()->get($sender->getName()) >= $data[2] * 15000){
                        self::getMoney()->set($sender->getName(), self::getMoney()->get($sender->getName()) - $data[2] * 15000);
                        self::getMoney()->save();
                        $sender->getInventory()->addItem(ItemFactory::getInstance()->get(317, 0, $data[2]));
                        $sender->sendMessage(Utils::getPrefix() . "Vous avez acheté §9" . $data[2] . " §fBottes en §daméthyste renforcé §fau pris de §9" . $data[2] * 15000 . "$" . "§f.");
                    }else{
                        $sender->sendMessage(Utils::getPrefix() . "§cVous n'avez pas l'argent requis pour acheter §9" . $data[2] . "§c Bottes en §daméthyste renforcé §c.");
                    }
                }else{
                    $sender->sendMessage(Utils::getPrefix() . "§cVotre inventaire est plein.");
                }
            }
            return true;
        });
        $form->setTitle("§9- §fShop §9-");
        $form->addLabel("§l§7» §rVoici l'interface d'achat des Bottes en §daméthyste renforcé §f :\n\n§7--------------------------------\n§9- §fAchat §f: §915000$" . "§f/u§f.\n§9- §fVous avez actuellement : §9" . self::getMoney()->get($sender->getName()) . "$\n§7--------------------------------\n");
        $form->addDropdown("§l§7» §rAction", ["§aAchat"]);
        $form->addSlider("§l§7» §rCombien en voulez-vous", "1", self::getMoney()->get($sender->getName()) / 15000);
        $sender->sendForm($form);
    }

    public function sendDynamiteForm(LinesiaPlayer $sender){
        $form = new CustomForm(function (LinesiaPlayer $sender, $data){
            if ($data === null){
                return true;
            }
            $index = ["§aAchat"];
            if ($index[$data[1]] === "§aAchat"){
                if ($sender->getInventory()->canAddItem(VanillaItems::SNOWBALL())){
                    if (self::getMoney()->get($sender->getName()) >= $data[2] * 6000){
                        self::getMoney()->set($sender->getName(), self::getMoney()->get($sender->getName()) - $data[2] * 6000);
                        self::getMoney()->save();
                        $sender->getInventory()->addItem(ItemFactory::getInstance()->get(332, 0, $data[2]));
                        $sender->sendMessage(Utils::getPrefix() . "Vous avez acheté §9" . $data[2] . " §fDynamite(s) §fau pris de §9" . $data[2] * 6000 . "$" . "§f.");
                    }else{
                        $sender->sendMessage(Utils::getPrefix() . "§cVous n'avez pas l'argent requis pour acheter §9" . $data[2] . "§c Dynamite(s) §c.");
                    }
                }else{
                    $sender->sendMessage(Utils::getPrefix() . "§cVotre inventaire est plein.");
                }
            }
            return true;
        });
        $form->setTitle("§9- §fShop §9-");
        $form->addLabel("§l§7» §rVoici l'interface d'achat des dynamites §f :\n\n§7--------------------------------\n§9- §fAchat §f: §96000$" . "§f/u§f.\n§9- §fVous avez actuellement : §9" . self::getMoney()->get($sender->getName()) . "$\n§7--------------------------------\n");
        $form->addDropdown("§l§7» §rAction", ["§aAchat"]);
        $form->addSlider("§l§7» §rCombien en voulez-vous", "1", self::getMoney()->get($sender->getName()) / 6000);
        $sender->sendForm($form);
    }
}
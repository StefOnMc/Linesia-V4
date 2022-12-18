<?php

namespace Linesia\Blocs;

use Linesia\API\EconomyAPI;
use Linesia\Libs\Form\CustomForm;
use Linesia\Libs\Form\SimpleForm;
use Linesia\Player\LinesiaPlayer;
use Linesia\Utils\Utils;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerInteractEvent;
use pocketmine\item\Armor;
use pocketmine\item\Axe;
use pocketmine\item\Bow;
use pocketmine\item\enchantment\EnchantmentInstance;
use pocketmine\item\enchantment\VanillaEnchantments;
use pocketmine\item\Hoe;
use pocketmine\item\Pickaxe;
use pocketmine\item\Shovel;
use pocketmine\item\Sword;

class EnchantingTable implements Listener{

    public function onPlayerInteract(PlayerInteractEvent $event){
        $sender = $event->getPlayer();

        if ($sender instanceof LinesiaPlayer){

            if ($event->getBlock()->getId() === 116){
                $event->cancel();
                if ($sender->getInventory()->getItemInHand() instanceof Armor){
                    $this->sendTableUIForArmor($sender);
                }elseif($sender->getInventory()->getItemInHand() instanceof Pickaxe){
                    $this->sendTableUIForPickaxe($sender);
                }elseif($sender->getInventory()->getItemInHand() instanceof Sword){
                    $this->sendTableUIForSword($sender);
                }elseif($sender->getInventory()->getItemInHand() instanceof Axe){
                    $this->sendTableUIForAxe($sender);
                }elseif($sender->getInventory()->getItemInHand() instanceof Shovel){
                    $this->sendTableUIForShovel($sender);
                }elseif($sender->getInventory()->getItemInHand() instanceof Hoe){
                    $this->sendTableUIForHoe($sender);
                }elseif($sender->getInventory()->getItemInHand() instanceof Bow){
                    $this->sendTableUIForBow($sender);
                }else{
                    $sender->sendMessage(Utils::getPrefix() . "§cCet objet ne peut pas être enchanté.");
                }
            }
        }
    }

    public function sendTableUIForArmor(LinesiaPlayer $sender){
        $form = new SimpleForm(function (LinesiaPlayer $sender, $data){

            if ($data === null){
                return true;
            }
            if ($data === 0) {
                $this->sendProtectionEnchantement($sender);
            }elseif($data === 1){
                $this->sendUnbreakingEnchantement($sender);
            }elseif($data === 2){
                $this->sendMedingEnchantement($sender);
            }
            return true;
        });
        $form->setTitle("§9- §fTable d'Enchantement §9-");
        $form->setContent("§9§l» §rBienvenue dans la table d'enchentement, je te laisse choisir ton enchantement.");
        $form->addButton("§7Protection");
        $form->addButton("§7Solidité");
        $form->addButton("§7Meding");
        $sender->sendForm($form);
    }

    public function sendTableUIForPickaxe(LinesiaPlayer $sender){
        $this->extracted($sender);
    }

    public function sendTableUIForAxe(LinesiaPlayer $sender){
        $this->extracted1($sender);
    }

    public function sendTableUIForShovel(LinesiaPlayer $sender){
        $this->extracted($sender);
    }

    public function sendTableUIForHoe(LinesiaPlayer $sender){
        $this->extracted1($sender);
    }

    public function sendTableUIForSword(LinesiaPlayer $sender){
        $form = new SimpleForm(function (LinesiaPlayer $sender, $data){

            if ($data === null){
                return true;
            }

            if ($data === 0){
                $this->sendSharpnessEnchantement($sender);
            }elseif($data === 1){
                $this->sendUnbreakingEnchantement($sender);
            }elseif($data === 2){
                $this->sendFireAspectEnchantement($sender);
            }elseif($data === 3){
                $this->sendMedingEnchantement($sender);
            }
            return true;
        });
        $form->setTitle("§9- §fTable d'Enchantement §9-");
        $form->setContent("§9§l» §rBienvenue dans la table d'enchentement, je te laisse choisir ton enchantement.");
        $form->addButton("§7Tranchant");
        $form->addButton("§7Solidité");
        $form->addButton("§7Aura de feu");
        $form->addButton("§7Meding");
        $sender->sendForm($form);
    }

    public function sendTableUIForBow(LinesiaPlayer $sender){
        $form = new SimpleForm(function (LinesiaPlayer $sender, $data){

            if ($data === null){
                return true;
            }

            if ($data[0] === 0){
                $this->sendInfinityEnchantement($sender);
            }elseif($data[0] === 1){
                $this->sendUnbreakingEnchantement($sender);
            }elseif($data[0] === 2){
                $this->sendPowerEnchantement($sender);
            }elseif($data[0] === 3){
                $this->sendPunchEnchantement($sender);
            }

            return true;
        });
        $form->setTitle("§9- §fTable d'Enchantement §9-");
        $form->setContent("§9§l» §rBienvenue dans la table d'enchentement, je te laisse choisir ton enchantement.");
        $form->addButton("§7Infinité");
        $form->addButton("§7Solidité");
        $form->addButton("§7Puissance");
        $form->addButton("§7Recule");
        $sender->sendForm($form);
    }

    public function sendProtectionEnchantement(LinesiaPlayer $sender) {
        $form = new CustomForm(function (LinesiaPlayer $sender, $data){

            if ($data === null){
                return true;
            }
            if ($data[1] == 1){
                if (EconomyAPI::getInstance()->getMoney($sender) >= 50){
                    $index = $sender->getInventory()->getHeldItemIndex();
                    $item = $sender->getInventory()->getItem($index);
                    $sender->getInventory()->setItem($index, $item->addEnchantment(new EnchantmentInstance(VanillaEnchantments::PROTECTION(), 1)));
                    EconomyAPI::getInstance()->deleteMoney($sender, 50);
                }else{
                    $sender->sendMessage(Utils::getPrefix() . "Vous n'avez pas l'argent requis pour enchanter cette objet.");
                }
            }elseif($data[1] == 2){
                if (EconomyAPI::getInstance()->getMoney($sender) >= 100){
                    $index = $sender->getInventory()->getHeldItemIndex();
                    $item = $sender->getInventory()->getItem($index);
                    $sender->getInventory()->setItem($index, $item->addEnchantment(new EnchantmentInstance(VanillaEnchantments::PROTECTION(), 2)));
                    EconomyAPI::getInstance()->deleteMoney($sender, 100);
                }else{
                    $sender->sendMessage(Utils::getPrefix() . "Vous n'avez pas l'argent requis pour enchanter cette objet.");
                }
            }elseif($data[1] == 3){
                if (EconomyAPI::getInstance()->getMoney($sender) >= 150){
                    $index = $sender->getInventory()->getHeldItemIndex();
                    $item = $sender->getInventory()->getItem($index);
                    $sender->getInventory()->setItem($index, $item->addEnchantment(new EnchantmentInstance(VanillaEnchantments::PROTECTION(), 3)));
                    EconomyAPI::getInstance()->deleteMoney($sender, 150);
                }else{
                    $sender->sendMessage(Utils::getPrefix() . "Vous n'avez pas l'argent requis pour enchanter cette objet.");
                }
            }elseif($data[1] == 4){
                if (EconomyAPI::getInstance()->getMoney($sender) >= 200){
                    $index = $sender->getInventory()->getHeldItemIndex();
                    $item = $sender->getInventory()->getItem($index);
                    $sender->getInventory()->setItem($index, $item->addEnchantment(new EnchantmentInstance(VanillaEnchantments::PROTECTION(), 4)));
                    EconomyAPI::getInstance()->deleteMoney($sender, 200);
                }else{
                    $sender->sendMessage(Utils::getPrefix() . "Vous n'avez pas l'argent requis pour enchanter cette objet.");
                }
            }
            return true;
        });
        $form->setTitle("§9- §fProtection §9-");
        $form->addLabel("§9§l» §rChoisissez votre niveau d'enchantement :\n\n- Protection I : §950$\n§f- Protection II : §9100$\n§f- Protection III : §9150$\n§f- Protection IV : §9200$\n ");
        $form->addSlider("§9§l» §rNiveau(x) d'enchantement", "1", "4");
        $sender->sendForm($form);
    }

    public function sendUnbreakingEnchantement(LinesiaPlayer $sender){
        $form = new CustomForm(function (LinesiaPlayer $sender, $data){

            if ($data === null){
                return true;
            }
            if ($data[1] == 1){
                if (EconomyAPI::getInstance()->getMoney($sender) >= 25){
                    $index = $sender->getInventory()->getHeldItemIndex();
                    $item = $sender->getInventory()->getItem($index);
                    $sender->getInventory()->setItem($index, $item->addEnchantment(new EnchantmentInstance(VanillaEnchantments::UNBREAKING(), 1)));
                    EconomyAPI::getInstance()->deleteMoney($sender, 25);
                }else{
                    $sender->sendMessage(Utils::getPrefix() . "Vous n'avez pas l'argent requis pour enchanter cette objet.");
                }
            }elseif($data[1] == 2){
                if (EconomyAPI::getInstance()->getMoney($sender) >= 50){
                    $index = $sender->getInventory()->getHeldItemIndex();
                    $item = $sender->getInventory()->getItem($index);
                    $sender->getInventory()->setItem($index, $item->addEnchantment(new EnchantmentInstance(VanillaEnchantments::UNBREAKING(), 2)));
                    EconomyAPI::getInstance()->deleteMoney($sender, 50);
                }else{
                    $sender->sendMessage(Utils::getPrefix() . "Vous n'avez pas l'argent requis pour enchanter cette objet.");
                }
            }elseif($data[1] == 3){
                if (EconomyAPI::getInstance()->getMoney($sender) >= 100){
                    $index = $sender->getInventory()->getHeldItemIndex();
                    $item = $sender->getInventory()->getItem($index);
                    $sender->getInventory()->setItem($index, $item->addEnchantment(new EnchantmentInstance(VanillaEnchantments::UNBREAKING(), 3)));
                    EconomyAPI::getInstance()->deleteMoney($sender, 100);
                }else{
                    $sender->sendMessage(Utils::getPrefix() . "Vous n'avez pas l'argent requis pour enchanter cette objet.");
                }
            }
            return true;
        });
        $form->setTitle("§9- §fSolidité §9-");
        $form->addLabel("§9§l» §rChoisissez votre niveau d'enchantement :\n\n- Solidité I : §925$\n§f- Solidité II : §950$\n§f- Solidité III : §9100$\n ");
        $form->addSlider("§9§l» §rNiveau(x) d'enchantement", "1", "3");
        $sender->sendForm($form);
    }

    public function sendMedingEnchantement(LinesiaPlayer $sender){
        $form = new CustomForm(function (LinesiaPlayer $sender, $data){

            if ($data === null){
                return true;
            }
            if ($data[1] == 1){
                if (EconomyAPI::getInstance()->getMoney($sender) >= 200){
                    $index = $sender->getInventory()->getHeldItemIndex();
                    $item = $sender->getInventory()->getItem($index);
                    $sender->getInventory()->setItem($index, $item->addEnchantment(new EnchantmentInstance(VanillaEnchantments::MENDING(), 1)));
                    EconomyAPI::getInstance()->deleteMoney($sender, 200);
                }else{
                    $sender->sendMessage(Utils::getPrefix() . "Vous n'avez pas l'argent requis pour enchanter cette objet.");
                }
            }
            return true;
        });
        $form->setTitle("§9- §fMeding §9-");
        $form->addLabel("§9§l» §rChoisissez votre niveau d'enchantement :\n\n- Meding I : §9200$\n ");
        $form->addSlider("§9§l» §rNiveau(x) d'enchantement", "1", "1");
        $sender->sendForm($form);
    }

    public function sendEfficiencyEnchantement(LinesiaPlayer $sender)
    {
        $form = new CustomForm(function (LinesiaPlayer $sender, $data) {

            if ($data === null) {
                return true;
            }
            if ($data[1] == 1) {
                if (EconomyAPI::getInstance()->getMoney($sender) >= 50) {
                    $index = $sender->getInventory()->getHeldItemIndex();
                    $item = $sender->getInventory()->getItem($index);
                    $sender->getInventory()->setItem($index, $item->addEnchantment(new EnchantmentInstance(VanillaEnchantments::EFFICIENCY(), 1)));
                    EconomyAPI::getInstance()->deleteMoney($sender, 50);
                } else {
                    $sender->sendMessage(Utils::getPrefix() . "Vous n'avez pas l'argent requis pour enchanter cette objet.");
                }
            } elseif ($data[1] == 2) {
                if (EconomyAPI::getInstance()->getMoney($sender) >= 100) {
                    $index = $sender->getInventory()->getHeldItemIndex();
                    $item = $sender->getInventory()->getItem($index);
                    $sender->getInventory()->setItem($index, $item->addEnchantment(new EnchantmentInstance(VanillaEnchantments::EFFICIENCY(), 2)));
                    EconomyAPI::getInstance()->deleteMoney($sender, 100);
                } else {
                    $sender->sendMessage(Utils::getPrefix() . "Vous n'avez pas l'argent requis pour enchanter cette objet.");
                }
            } elseif ($data[1] == 3) {
                if (EconomyAPI::getInstance()->getMoney($sender) >= 150) {
                    $index = $sender->getInventory()->getHeldItemIndex();
                    $item = $sender->getInventory()->getItem($index);
                    $sender->getInventory()->setItem($index, $item->addEnchantment(new EnchantmentInstance(VanillaEnchantments::EFFICIENCY(), 3)));
                    EconomyAPI::getInstance()->deleteMoney($sender, 150);
                } else {
                    $sender->sendMessage(Utils::getPrefix() . "Vous n'avez pas l'argent requis pour enchanter cette objet.");
                }
            } elseif ($data[1] == 4) {
                if (EconomyAPI::getInstance()->getMoney($sender) >= 200) {
                    $index = $sender->getInventory()->getHeldItemIndex();
                    $item = $sender->getInventory()->getItem($index);
                    $sender->getInventory()->setItem($index, $item->addEnchantment(new EnchantmentInstance(VanillaEnchantments::EFFICIENCY(), 4)));
                    EconomyAPI::getInstance()->deleteMoney($sender, 200);

                } else {
                    $sender->sendMessage(Utils::getPrefix() . "Vous n'avez pas l'argent requis pour enchanter cette objet.");
                }
            } elseif ($data[1] == 5) {
                if (EconomyAPI::getInstance()->getMoney($sender) >= 250) {
                    $index = $sender->getInventory()->getHeldItemIndex();
                    $item = $sender->getInventory()->getItem($index);
                    $sender->getInventory()->setItem($index, $item->addEnchantment(new EnchantmentInstance(VanillaEnchantments::EFFICIENCY(), 5)));
                    EconomyAPI::getInstance()->deleteMoney($sender, 250);

                } else {
                    $sender->sendMessage(Utils::getPrefix() . "Vous n'avez pas l'argent requis pour enchanter cette objet.");
                }
            }
            return true;
        });
        $form->setTitle("§9- §fEfficacité §9-");
        $form->addLabel("§9§l» §rChoisissez votre niveau d'enchantement :\n\n- Efficacité I : §950$\n§f- Efficacité II : §9100$\n§f- Efficacité III : §9150$\n§f- Efficacité IV : §9200$\n§f- Efficacité V : §9250$\n ");
        $form->addSlider("§9§l» §rNiveau(x) d'enchantement", "1", "5");
        $sender->sendForm($form);
    }


    public function sendSilkToutchEnchantement(LinesiaPlayer $sender)
    {
        $form = new CustomForm(function (LinesiaPlayer $sender, $data) {

            if ($data === null) {
                return true;
            }
            if ($data[1] == 1) {
                if (EconomyAPI::getInstance()->getMoney($sender) >= 250) {
                    $index = $sender->getInventory()->getHeldItemIndex();
                    $item = $sender->getInventory()->getItem($index);
                    $sender->getInventory()->setItem($index, $item->addEnchantment(new EnchantmentInstance(VanillaEnchantments::SILK_TOUCH(), 1)));
                    EconomyAPI::getInstance()->deleteMoney($sender, 250);

                } else {
                    $sender->sendMessage(Utils::getPrefix() . "Vous n'avez pas l'argent requis pour enchanter cette objet.");
                }
            }
            return true;
        });
        $form->setTitle("§9- §fTouché de soi §9-");
        $form->addLabel("§9§l» §rChoisissez votre niveau d'enchantement :\n\n- Touché de soi I : §9250$\n ");
        $form->addSlider("§9§l» §rNiveau(x) d'enchantement", "1", "1");
        $sender->sendForm($form);
    }

    public function sendSharpnessEnchantement(LinesiaPlayer $sender)
    {
        $form = new CustomForm(function (LinesiaPlayer $sender, $data) {

            if ($data === null) {
                return true;
            }
            if ($data[1] == 1) {
                if (EconomyAPI::getInstance()->getMoney($sender) >= 50) {
                    $index = $sender->getInventory()->getHeldItemIndex();
                    $item = $sender->getInventory()->getItem($index);
                    $sender->getInventory()->setItem($index, $item->addEnchantment(new EnchantmentInstance(VanillaEnchantments::SHARPNESS(), 1)));
                    EconomyAPI::getInstance()->deleteMoney($sender, 50);

                } else {
                    $sender->sendMessage(Utils::getPrefix() . "Vous n'avez pas l'argent requis pour enchanter cette objet.");
                }
            } elseif ($data[1] == 2) {
                if (EconomyAPI::getInstance()->getMoney($sender) >= 100) {
                    $index = $sender->getInventory()->getHeldItemIndex();
                    $item = $sender->getInventory()->getItem($index);
                    $sender->getInventory()->setItem($index, $item->addEnchantment(new EnchantmentInstance(VanillaEnchantments::SHARPNESS(), 2)));
                    EconomyAPI::getInstance()->deleteMoney($sender, 100);

                } else {
                    $sender->sendMessage(Utils::getPrefix() . "Vous n'avez pas l'argent requis pour enchanter cette objet.");
                }
            } elseif ($data[1] == 3) {
                if (EconomyAPI::getInstance()->getMoney($sender) >= 150) {
                    $index = $sender->getInventory()->getHeldItemIndex();
                    $item = $sender->getInventory()->getItem($index);
                    $sender->getInventory()->setItem($index, $item->addEnchantment(new EnchantmentInstance(VanillaEnchantments::SHARPNESS(), 3)));
                    EconomyAPI::getInstance()->deleteMoney($sender, 150);

                } else {
                    $sender->sendMessage(Utils::getPrefix() . "Vous n'avez pas l'argent requis pour enchanter cette objet.");
                }
            } elseif ($data[1] == 4) {
                if (EconomyAPI::getInstance()->getMoney($sender) >= 200) {
                    $index = $sender->getInventory()->getHeldItemIndex();
                    $item = $sender->getInventory()->getItem($index);
                    $sender->getInventory()->setItem($index, $item->addEnchantment(new EnchantmentInstance(VanillaEnchantments::SHARPNESS(), 4)));
                    EconomyAPI::getInstance()->deleteMoney($sender, 200);
                } else {
                    $sender->sendMessage(Utils::getPrefix() . "Vous n'avez pas l'argent requis pour enchanter cette objet.");
                }
            } elseif ($data[1] == 5) {
                if (EconomyAPI::getInstance()->getMoney($sender) >= 250) {
                    $index = $sender->getInventory()->getHeldItemIndex();
                    $item = $sender->getInventory()->getItem($index);
                    $sender->getInventory()->setItem($index, $item->addEnchantment(new EnchantmentInstance(VanillaEnchantments::SHARPNESS(), 5)));
                    EconomyAPI::getInstance()->deleteMoney($sender, 250);

                } else {
                    $sender->sendMessage(Utils::getPrefix() . "Vous n'avez pas l'argent requis pour enchanter cette objet.");
                }
            }
            return true;
        });
        $form->setTitle("§9- §fTranchant §9-");
        $form->addLabel("§9§l» §rChoisissez votre niveau d'enchantement :\n\n- Tranchant I : §950$\n§f- Tranchant II : §9100$\n§f- Tranchant III : §9150$\n§f- Tranchant IV : §9200$\n§f- Tranchant V : §9250$\n ");
        $form->addSlider("§9§l» §rNiveau(x) d'enchantement", "1", "5");
        $sender->sendForm($form);
    }

    public function sendFireAspectEnchantement(LinesiaPlayer $sender)
    {
        $form = new CustomForm(function (LinesiaPlayer $sender, $data) {

            if ($data === null) {
                return true;
            }
            if ($data[1] == 1) {
                if (EconomyAPI::getInstance()->getMoney($sender) >= 250) {
                    $index = $sender->getInventory()->getHeldItemIndex();
                    $item = $sender->getInventory()->getItem($index);
                    $sender->getInventory()->setItem($index, $item->addEnchantment(new EnchantmentInstance(VanillaEnchantments::FIRE_ASPECT(), 1)));
                    EconomyAPI::getInstance()->deleteMoney($sender, 250);

                } else {
                    $sender->sendMessage(Utils::getPrefix() . "Vous n'avez pas l'argent requis pour enchanter cette objet.");
                }
            }elseif($data[1] == 2){
                if (EconomyAPI::getInstance()->getMoney($sender) >= 500) {
                    $index = $sender->getInventory()->getHeldItemIndex();
                    $item = $sender->getInventory()->getItem($index);
                    $sender->getInventory()->setItem($index, $item->addEnchantment(new EnchantmentInstance(VanillaEnchantments::FIRE_ASPECT(), 2)));
                    EconomyAPI::getInstance()->deleteMoney($sender, 500);

                } else {
                    $sender->sendMessage(Utils::getPrefix() . "Vous n'avez pas l'argent requis pour enchanter cette objet.");
                }
            }
            return true;
        });
        $form->setTitle("§9- §fAura de feu §9-");
        $form->addLabel("§9§l» §rChoisissez votre niveau d'enchantement :\n\n- Aura de feu I : §9250$\n§f- Aura de feu II : §9500$\n ");
        $form->addSlider("§9§l» §rNiveau(x) d'enchantement", "1", "2");
        $sender->sendForm($form);
    }

    public function sendInfinityEnchantement(LinesiaPlayer $sender)
    {
        $form = new CustomForm(function (LinesiaPlayer $sender, $data) {

            if ($data === null) {
                return true;
            }
            if ($data[1] == 1) {
                if (EconomyAPI::getInstance()->getMoney($sender) >= 250) {
                    $index = $sender->getInventory()->getHeldItemIndex();
                    $item = $sender->getInventory()->getItem($index);
                    $sender->getInventory()->setItem($index, $item->addEnchantment(new EnchantmentInstance(VanillaEnchantments::INFINITY(), 1)));
                    EconomyAPI::getInstance()->deleteMoney($sender, 250);

                } else {
                    $sender->sendMessage(Utils::getPrefix() . "Vous n'avez pas l'argent requis pour enchanter cette objet.");
                }
            }
            return true;
        });
        $form->setTitle("§9- §fInfinité §9-");
        $form->addLabel("§9§l» §rChoisissez votre niveau d'enchantement :\n\n- Infinité I : §9250$\n ");
        $form->addSlider("§9§l» §rNiveau(x) d'enchantement", "1", "1");
        $sender->sendForm($form);
    }

    public function sendPunchEnchantement(LinesiaPlayer $sender)
    {
        $form = new CustomForm(function (LinesiaPlayer $sender, $data) {

            if ($data === null) {
                return true;
            }
            if ($data[1] == 1) {
                if (EconomyAPI::getInstance()->getMoney($sender) >= 250) {
                    $index = $sender->getInventory()->getHeldItemIndex();
                    $item = $sender->getInventory()->getItem($index);
                    $sender->getInventory()->setItem($index, $item->addEnchantment(new EnchantmentInstance(VanillaEnchantments::PUNCH(), 1)));
                    EconomyAPI::getInstance()->deleteMoney($sender, 250);

                } else {
                    $sender->sendMessage(Utils::getPrefix() . "Vous n'avez pas l'argent requis pour enchanter cette objet.");
                }
            }elseif($data[1] == 2){
                if (EconomyAPI::getInstance()->getMoney($sender) >= 500) {
                    $index = $sender->getInventory()->getHeldItemIndex();
                    $item = $sender->getInventory()->getItem($index);
                    $sender->getInventory()->setItem($index, $item->addEnchantment(new EnchantmentInstance(VanillaEnchantments::PUNCH(), 2)));
                    EconomyAPI::getInstance()->deleteMoney($sender, 500);

                } else {
                    $sender->sendMessage(Utils::getPrefix() . "Vous n'avez pas l'argent requis pour enchanter cette objet.");
                }
            }
            return true;
        });
        $form->setTitle("§9- §fRecule §9-");
        $form->addLabel("§9§l» §rChoisissez votre niveau d'enchantement :\n\n- Recule I : §9250$\n§f- Recule II : §9500$\n ");
        $form->addSlider("§9§l» §rNiveau(x) d'enchantement", "1", "2");
        $sender->sendForm($form);
    }

    public function sendPowerEnchantement(LinesiaPlayer $sender)
    {
        $form = new CustomForm(function (LinesiaPlayer $sender, $data) {

            if ($data === null) {
                return true;
            }
            if ($data[1] == 1) {
                if (EconomyAPI::getInstance()->getMoney($sender) >= 50) {
                    $index = $sender->getInventory()->getHeldItemIndex();
                    $item = $sender->getInventory()->getItem($index);
                    $sender->getInventory()->setItem($index, $item->addEnchantment(new EnchantmentInstance(VanillaEnchantments::POWER(), 1)));
                    EconomyAPI::getInstance()->deleteMoney($sender, 50);
                } else {
                    $sender->sendMessage(Utils::getPrefix() . "Vous n'avez pas l'argent requis pour enchanter cette objet.");
                }
            } elseif ($data[1] == 2) {
                if (EconomyAPI::getInstance()->getMoney($sender) >= 100) {
                    $index = $sender->getInventory()->getHeldItemIndex();
                    $item = $sender->getInventory()->getItem($index);
                    $sender->getInventory()->setItem($index, $item->addEnchantment(new EnchantmentInstance(VanillaEnchantments::POWER(), 2)));
                    EconomyAPI::getInstance()->deleteMoney($sender, 100);
                } else {
                    $sender->sendMessage(Utils::getPrefix() . "Vous n'avez pas l'argent requis pour enchanter cette objet.");
                }
            } elseif ($data[1] == 3) {
                if (EconomyAPI::getInstance()->getMoney($sender) >= 150) {
                    $index = $sender->getInventory()->getHeldItemIndex();
                    $item = $sender->getInventory()->getItem($index);
                    $sender->getInventory()->setItem($index, $item->addEnchantment(new EnchantmentInstance(VanillaEnchantments::POWER(), 3)));
                    EconomyAPI::getInstance()->deleteMoney($sender, 150);

                } else {
                    $sender->sendMessage(Utils::getPrefix() . "Vous n'avez pas l'argent requis pour enchanter cette objet.");
                }
            } elseif ($data[1] == 4) {
                if (EconomyAPI::getInstance()->getMoney($sender) >= 200) {
                    $index = $sender->getInventory()->getHeldItemIndex();
                    $item = $sender->getInventory()->getItem($index);
                    $sender->getInventory()->setItem($index, $item->addEnchantment(new EnchantmentInstance(VanillaEnchantments::POWER(), 4)));
                    EconomyAPI::getInstance()->deleteMoney($sender, 200);
                } else {
                    $sender->sendMessage(Utils::getPrefix() . "Vous n'avez pas l'argent requis pour enchanter cette objet.");
                }
            } elseif ($data[1] == 5) {
                if (EconomyAPI::getInstance()->getMoney($sender) >= 250) {
                    $index = $sender->getInventory()->getHeldItemIndex();
                    $item = $sender->getInventory()->getItem($index);
                    $sender->getInventory()->setItem($index, $item->addEnchantment(new EnchantmentInstance(VanillaEnchantments::POWER(), 5)));
                    EconomyAPI::getInstance()->deleteMoney($sender, 250);
                } else {
                    $sender->sendMessage(Utils::getPrefix() . "Vous n'avez pas l'argent requis pour enchanter cette objet.");
                }
            }
            return true;
        });
        $form->setTitle("§9- §fPuissance §9-");
        $form->addLabel("§9§l» §rChoisissez votre niveau d'enchantement :\n\n- Puissance I : §950$\n§f- Puissance II : §9100$\n§f- Puissance III : §9150$\n§f- Puissance IV : §9200$\n§f- Puissance V : §9250$\n ");
        $form->addSlider("§9§l» §rNiveau(x) d'enchantement", "1", "5");
        $sender->sendForm($form);
    }

    /**
     * @param LinesiaPlayer $sender
     * @return void
     */
    private function extracted(LinesiaPlayer $sender): void
    {
        $form = new SimpleForm(function (LinesiaPlayer $sender, $data) {

            if ($data === null) {
                return true;
            }
            if ($data === 0) {
                $this->sendEfficiencyEnchantement($sender);
            } elseif ($data === 1) {
                $this->sendUnbreakingEnchantement($sender);
            } elseif ($data === 2) {
                $this->sendSilkToutchEnchantement($sender);
            } elseif ($data === 3) {
                $this->sendMedingEnchantement($sender);
            }
            return true;
        });
        $form->setTitle("§9- §fTable d'Enchantement §9-");
        $form->setContent("§9§l» §rBienvenue dans la table d'enchentement, je te laisse choisir ton enchantement.");
        $form->addButton("§7Efficacité");
        $form->addButton("§7Solidité");
        $form->addButton("§7Touché de soi");
        $form->addButton("§7Meding");
        $sender->sendForm($form);
    }

    /**
     * @param LinesiaPlayer $sender
     * @return void
     */
    private function extracted1(LinesiaPlayer $sender): void
    {
        $form = new SimpleForm(function (LinesiaPlayer $sender, $data) {

            if ($data === null) {
                return true;
            }

            if ($data === 0) {
                $this->sendEfficiencyEnchantement($sender);
            } elseif ($data === 1) {
                $this->sendUnbreakingEnchantement($sender);
            } elseif ($data === 2) {
                $this->sendMedingEnchantement($sender);
            }
            return true;
        });
        $form->setTitle("§9- §fTable d'Enchantement §9-");
        $form->setContent("§9§l» §rBienvenue dans la table d'enchentement, je te laisse choisir ton enchantement.");
        $form->addButton("§7Efficacité");
        $form->addButton("§7Solidité");
        $form->addButton("§7Meding");
        $sender->sendForm($form);
    }

}
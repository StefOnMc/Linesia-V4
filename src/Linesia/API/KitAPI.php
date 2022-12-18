<?php

namespace Linesia\API;

use JsonException;
use Linesia\Libs\Form\SimpleForm;
use Linesia\Player\LinesiaPlayer;
use Linesia\Utils\Utils;
use pocketmine\block\VanillaBlocks;
use pocketmine\item\enchantment\EnchantmentInstance;
use pocketmine\item\enchantment\VanillaEnchantments;
use pocketmine\item\VanillaItems;

final class KitAPI extends API {

    public static function sendKitForm(LinesiaPlayer $sender): void {
        $form = new SimpleForm(function (LinesiaPlayer $sender, $data){

            if ($data === null){
                return true;
            }

            if ($data === 0){
                self::sendKitJoueur($sender);
            }elseif($data === 1){
                self::sendKitBuilder($sender);
            }elseif($data == 2){
                if ($sender->getPermission("linesia.kit.mini-vip")){
                    self::sendKitMiniVIP($sender);
                }else{
                    $sender->sendMessage(Utils::getPrefix() . "Vous n'avez pas le grade requis pour prendre ce kit (§eMini§f-§eVIP§f).");
                }
            }elseif($data === 3){
                if ($sender->getPermission("linesia.kit.vip")){
                    self::sendKitVIP($sender);
                }else{
                    $sender->sendMessage(Utils::getPrefix() . "Vous n'avez pas le grade requis pour prendre ce kit (§gVIP§f).");
                }
            }elseif($data === 4){
                if ($sender->getPermission("linesia.kit.roi")){
                    self::sendKitRoi($sender);
                }else{
                    $sender->sendMessage(Utils::getPrefix() . "Vous n'avez pas le grade requis pour prendre ce kit (§3Roi§f).");
                }
            }
            return true;

        });
        $form->setTitle("§9- §fKits §9-");
        $form->setContent("§l§7» §rBienvenue dans l'interface des kits choisissez votre kit et partez à l'assaut d'autre joueur.");
        $form->addButton("Kit §7Joueur\n§aDébloqué");
        $form->addButton("Kit §6Builder\n§aDébloqué");
        if ($sender->getPermission("linesia.kit.mini-vip")){
            $form->addButton("Kit §eMini§f-§eVIP\n§aDébloqué");
        }else{
            $form->addButton("Kit §eMini§f-§eVIP\n§cBloqué");
        }
        if ($sender->getPermission("linesia.kit.vip")){
            $form->addButton("Kit §gVIP\n§aDébloqué");
        }else{
            $form->addButton("Kit §gVIP\n§cBloqué");
        }
        if ($sender->getPermission("linesia.kit.roi")){
            $form->addButton("Kit §3Roi\n§aDébloqué");
        }else{
            $form->addButton("Kit §3Roi\n§cBloqué");
        }
        $sender->sendForm($form);
    }

    private static function sendKitJoueur(LinesiaPlayer $sender): void {
        $sender->getArmorInventory()->setHelmet(VanillaItems::DIAMOND_HELMET()->addEnchantment(new EnchantmentInstance(VanillaEnchantments::PROTECTION(), 1)));
        $sender->getArmorInventory()->setChestplate(VanillaItems::DIAMOND_CHESTPLATE()->addEnchantment(new EnchantmentInstance(VanillaEnchantments::PROTECTION(), 1)));
        $sender->getArmorInventory()->setLeggings(VanillaItems::DIAMOND_LEGGINGS()->addEnchantment(new EnchantmentInstance(VanillaEnchantments::PROTECTION(), 1)));
        $sender->getArmorInventory()->setBoots(VanillaItems::DIAMOND_BOOTS()->addEnchantment(new EnchantmentInstance(VanillaEnchantments::PROTECTION(), 1)));

        $sender->getInventory()->setItem(0, VanillaItems::DIAMOND_SWORD()->addEnchantment(new EnchantmentInstance(VanillaEnchantments::SHARPNESS(), 1)));
        $sender->getInventory()->setItem(1, VanillaItems::RAW_MUTTON()->setCount(64));
        $sender->getInventory()->setItem(2, VanillaItems::ENDER_PEARL()->setCount(16));
        $sender->getInventory()->setItem(3, VanillaItems::GOLDEN_APPLE()->setCount(16));
    }

    private static function sendKitBuilder(LinesiaPlayer $sender): void {

        $config = Utils::getConfigFile("Kits/KitBuilder", "json");
        $time = $config->get($sender->getName());

        if ($time - time() <= 0 or !$time) {


            $sender->getInventory()->setItem(0, VanillaItems::DIAMOND_PICKAXE()->addEnchantment(new EnchantmentInstance(VanillaEnchantments::EFFICIENCY(), 5)));
            $sender->getInventory()->setItem(1, VanillaItems::DIAMOND_AXE()->addEnchantment(new EnchantmentInstance(VanillaEnchantments::EFFICIENCY(), 5)));
            $cobweb = VanillaBlocks::COBWEB()->asItem()->setCount(128);
            $wood = VanillaBlocks::OAK_WOOD()->asItem()->setCount(128);
            $crafting = VanillaBlocks::CRAFTING_TABLE()->asItem()->setCount(128);
            $plaque = VanillaBlocks::OAK_PRESSURE_PLATE()->asItem()->setCount(128);

            $sender->getInventory()->addItem($cobweb);
            $sender->getInventory()->addItem($wood);
            $sender->getInventory()->addItem($crafting);
            $sender->getInventory()->addItem($plaque);

            $config->set($sender->getName(), time() + 60);
            $config->save();
        } else {
            self::extracted($time, $sender);
        }
    }

    /**
     * @throws JsonException
     */
    private static function sendKitMiniVIP(LinesiaPlayer $sender): void {

        $config = Utils::getConfigFile("Kits/KitMiniVIP", "json");
        $time = $config->get($sender->getName());

        if ($time - time() <= 0 or !$time) {

            $sender->getArmorInventory()->setHelmet(VanillaItems::DIAMOND_HELMET()->addEnchantment(new EnchantmentInstance(VanillaEnchantments::PROTECTION(), 2)));
            $sender->getArmorInventory()->setChestplate(VanillaItems::DIAMOND_CHESTPLATE()->addEnchantment(new EnchantmentInstance(VanillaEnchantments::PROTECTION(), 2)));
            $sender->getArmorInventory()->setLeggings(VanillaItems::DIAMOND_LEGGINGS()->addEnchantment(new EnchantmentInstance(VanillaEnchantments::PROTECTION(), 2)));
            $sender->getArmorInventory()->setBoots(VanillaItems::DIAMOND_BOOTS()->addEnchantment(new EnchantmentInstance(VanillaEnchantments::PROTECTION(), 2)));

            $sender->getInventory()->setItem(0, VanillaItems::DIAMOND_SWORD()->addEnchantment(new EnchantmentInstance(VanillaEnchantments::SHARPNESS(), 2)));
            $sender->getInventory()->setItem(1, VanillaItems::RAW_MUTTON()->setCount(64));
            $sender->getInventory()->setItem(2, VanillaItems::ENDER_PEARL()->setCount(16));
            $sender->getInventory()->setItem(3, VanillaItems::GOLDEN_APPLE()->setCount(16));

            $config->set($sender->getName(), time() + 60);
            $config->save();
        } else {
            self::extracted($time, $sender);
        }

    }

    /**
     * @throws JsonException
     */
    private static function sendKitVIP(LinesiaPlayer $sender): void {

        $config = Utils::getConfigFile("Kits/KitVIP", "json");
        $time = $config->get($sender->getName());

        if ($time - time() <= 0 or !$time) {

            $sender->getArmorInventory()->setHelmet(VanillaItems::DIAMOND_HELMET()->addEnchantment(new EnchantmentInstance(VanillaEnchantments::PROTECTION(), 3)));
            $sender->getArmorInventory()->setChestplate(VanillaItems::DIAMOND_CHESTPLATE()->addEnchantment(new EnchantmentInstance(VanillaEnchantments::PROTECTION(), 3)));
            $sender->getArmorInventory()->setLeggings(VanillaItems::DIAMOND_LEGGINGS()->addEnchantment(new EnchantmentInstance(VanillaEnchantments::PROTECTION(), 3)));
            $sender->getArmorInventory()->setBoots(VanillaItems::DIAMOND_BOOTS()->addEnchantment(new EnchantmentInstance(VanillaEnchantments::PROTECTION(), 3)));

            $sender->getInventory()->setItem(0, VanillaItems::DIAMOND_SWORD()->addEnchantment(new EnchantmentInstance(VanillaEnchantments::SHARPNESS(), 3)));
            $sender->getInventory()->setItem(1, VanillaItems::RAW_MUTTON()->setCount(64));
            $sender->getInventory()->setItem(2, VanillaItems::ENDER_PEARL()->setCount(16));
            $sender->getInventory()->setItem(3, VanillaItems::GOLDEN_APPLE()->setCount(16));

            $config->set($sender->getName(), time() + 300);
            $config->save();
        } else {
            self::extracted($time, $sender);
        }

    }

    /**
     * @throws JsonException
     */
    private static function sendKitRoi(LinesiaPlayer $sender) : void {
        $config = Utils::getConfigFile("Kits/KitRoi", "json");
        $time = $config->get($sender->getName());

        if ($time - time() <= 0 or !$time) {

            $sender->getArmorInventory()->setHelmet(VanillaItems::DIAMOND_HELMET()->addEnchantment(new EnchantmentInstance(VanillaEnchantments::PROTECTION(), 4)));
            $sender->getArmorInventory()->setChestplate(VanillaItems::DIAMOND_CHESTPLATE()->addEnchantment(new EnchantmentInstance(VanillaEnchantments::PROTECTION(), 4)));
            $sender->getArmorInventory()->setLeggings(VanillaItems::DIAMOND_LEGGINGS()->addEnchantment(new EnchantmentInstance(VanillaEnchantments::PROTECTION(), 4)));
            $sender->getArmorInventory()->setBoots(VanillaItems::DIAMOND_BOOTS()->addEnchantment(new EnchantmentInstance(VanillaEnchantments::PROTECTION(), 4)));

            $sender->getInventory()->setItem(0, VanillaItems::DIAMOND_SWORD()->addEnchantment(new EnchantmentInstance(VanillaEnchantments::SHARPNESS(), 4)));
            $sender->getInventory()->setItem(1, VanillaItems::RAW_MUTTON()->setCount(64));
            $sender->getInventory()->setItem(2, VanillaItems::ENDER_PEARL()->setCount(16));
            $sender->getInventory()->setItem(3, VanillaItems::GOLDEN_APPLE()->setCount(16));

            $config->set($sender->getName(), time() + 480);
            $config->save();
        } else {
            self::extracted($time, $sender);
        }
    }

    /**
     * @param mixed $time
     * @param LinesiaPlayer $sender
     * @return void
     */
    private static function extracted(mixed $time, LinesiaPlayer $sender): void
    {
        $timeRestant = $time - time();
        $minutes = intval(abs($timeRestant / 60));
        $secondes = intval(abs($timeRestant - $minutes * 60));
        if ($minutes > 0) {
            $formatTemp = "$minutes minute(s) et $secondes seconde(s)";
        } else {
            $formatTemp = "$secondes seconde(s)";
        }
        $sender->sendMessage(Utils::getPrefix() . "Vous pourrez récupérer ce kit dans§9 " . $formatTemp);
    }

}
<?php

namespace Linesia\Item;

use Linesia\Core;
use Linesia\Item\Administration\NPCWandItem;
use Linesia\Item\Armor\AmethysteBoots;
use Linesia\Item\Armor\AmethysteChestPlate;
use Linesia\Item\Armor\AmethysteHelmet;
use Linesia\Item\Armor\AmethysteLeggings;
use Linesia\Item\Armor\AmethysteRenfoBoots;
use Linesia\Item\Armor\AmethysteRenfoChestPlate;
use Linesia\Item\Armor\AmethysteRenfoHelmet;
use Linesia\Item\Armor\AmethysteRenfoLeggings;
use Linesia\Item\PvP\AmethysteSword;
use Linesia\Item\PvP\Dynamite;
use Linesia\Item\PvP\FakeEnderPearl;
use Linesia\Item\PvP\Soupe;
use pocketmine\inventory\CreativeInventory;
use pocketmine\item\ItemFactory;
use pocketmine\item\ItemIdentifier;
use pocketmine\item\ItemIds;

class ItemManager {

    public static function initItems(): void {

        $amh = new AmethysteHelmet();
        $amh->setCustomName("§r§7Casque en §dAméthyste");
        $amc = new AmethysteChestPlate();
        $amc->setCustomName("§r§7Plastron en §dAméthyste");
        $aml = new AmethysteLeggings();
        $aml->setCustomName("§r§7Jambière en §dAméthyste");
        $amb = new AmethysteBoots();
        $amb->setCustomName("§r§7Bottes en §dAméthyste");
        $amrh = new AmethysteRenfoHelmet();
        $amrh->setCustomName("§r§7Casque en §dAméthyste Renforcé");
        $amrc = new AmethysteRenfoChestPlate();
        $amrc->setCustomName("§r§7Plastron en §dAméthyste Renforcé");
        $amrl = new AmethysteRenfoLeggings();
        $amrl->setCustomName("§r§7Jambière en §dAméthyste Renforcé");
        $amrb = new AmethysteRenfoBoots();
        $amrb->setCustomName("§r§7Bottes en §dAméthyste Renforcé");
        $ams = new AmethysteSword();
        $ams->setCustomName("§r§7Épée en §dAméthyste");
        $fakepearl = new FakeEnderPearl(new ItemIdentifier(ItemIds::EGG,0), "FakeEnderPearls");
        $fakepearl->setCustomName("§r§7Fake EnderPearls");
        $npcwand = new NPCWandItem();
        $npcwand->setCustomName("§r§7NpcWand");
        $dynamite = new Dynamite(new ItemIdentifier(ItemIds::SNOWBALL, 0), "Dynamite");
        $dynamite->setCustomName("§r§7Dynamite");

        $items = [
            $amh,
            $amc,
            $aml,
            $amb,
            $amrh,
            $amrc,
            $amrl,
            $amrb,
            $ams,
            $fakepearl,
            $npcwand,
            $dynamite
        ];


        foreach ($items as $item){
            ItemFactory::getInstance()->register($item, true);
            CreativeInventory::getInstance()->remove($item);
            CreativeInventory::getInstance()->add($item);
        }
        Core::getInstance()->getLogger()->info("Il y a un total de §9" . count($items) . " item(s) §fchargées.");
    }

}
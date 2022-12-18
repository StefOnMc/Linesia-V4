<?php

namespace Linesia\Item\Administration;

use Linesia\Core;
use Linesia\Entity\Base\BaseCustomEntity;
use Linesia\Entity\Base\BaseEntity;
use Linesia\Entity\EntityManager;
use Linesia\Entity\NPC\CyclopeNPC;
use Linesia\Libs\Form\CustomForm;
use Linesia\Libs\Form\SimpleForm;
use Linesia\Utils\Utils;
use pocketmine\entity\Skin;
use pocketmine\item\Item;
use pocketmine\item\ItemIdentifier;
use pocketmine\item\ItemIds;
use pocketmine\math\Vector3;
use pocketmine\nbt\tag\CompoundTag;
use pocketmine\nbt\tag\DoubleTag;
use pocketmine\nbt\tag\FloatTag;
use pocketmine\nbt\tag\ListTag;
use pocketmine\player\Player;
use pocketmine\Server;

class NPCWandItem extends Item {

    private array $commandsList;

    public function __construct() {
        parent::__construct(new ItemIdentifier(ItemIds::WOODEN_HOE, 0), "NpcWand");
    }

    public static function sendCreationMenu(Player $player): void{
        if (!$player->hasPermission("linesia.administration.npc") or !$player->hasPermission("linesia.*")){
            $player->sendMessage(Utils::getPrefix() . "§cVous n'avez pas la permission d'utiliser cette objet.");
            return;
        }
        $form = new SimpleForm(function (Player $player, $data){
            if ($data === null){
                return true;
            }
            if ($data === 0){
                $this->sendCustomForm($player);
            }elseif($data === 1){
                $this->sendCustomForm($player);
            }elseif($data === 2){
                Server::getInstance()->dispatchCommand($player, "cyclope");
            }elseif($data === 3){
                $nbt = $this->createBaseNBT($player->getLocation(), null, 0.0, 0.0);
                $path = Core::getInstance()->getDataFolder() . "Box/Box.png";
                $data = Utils::PNGtoBYTES($path);
                $cape = "";
                $path = Core::getInstance()->getDataFolder() . "Box/Box.json";
                $geometry = file_get_contents($path);

                $skin = new Skin($this->getName(), $data, $cape, "geometry.unknow", $geometry);
                $entity = new CyclopeNPC($player->getLocation(), $skin, $nbt);
                $entity->spawnToAll();
            }
            return true;
        });
        $form->setTitle("§9- §fNpcCreationMenu §9-");
        $form->setContent("§9§l» §rBienvenue dans le menu de création d'un npc, choisissez toutes les caractéristiques du npc.");
        foreach (EntityManager::$entityName as $value){
            $form->addButton("§f- §9" . $value);
        }
        $player->sendForm($form);
    }

    public function sendCustomForm(Player $player){

    }

    /**
     * @param Player $player
     * @param BaseEntity|BaseCustomEntity $npc
     * @return void
     */
    public function sendMenu(Player $player, BaseEntity|BaseCustomEntity $npc): void {

        if (!$player->hasPermission("linesia.administration.npc") or !$player->hasPermission("linesia.*")) {
            $player->sendMessage(Utils::getPrefix() . "§cVous n'avez pas la permission d'utilisé cette objet.");
            return;
        }

        $form = new SimpleForm(function (Player $player, int $data = null) use ($npc) {

            if($data === null){
                return true;
            }
            if ($data === 0) {
                $this->removeNpc($player, $npc);
            }elseif($data === 1) {
                $this->sendAddCommandMenu($player, $npc);
            }elseif($data === 2) {
                $player->sendMessage(Utils::getPrefix() . "§cProchainement...");
            }elseif ($data === 3) {
                $this->changeSizeMenu($player, $npc);
            }
            return true;
        });
        $form->setTitle("§9- §fNpcMenu §9-");
        $form->setContent("§9§l» §rBienvenue dans l'interface des npc, merci de choisir une action.");
        $form->addButton("§9§l» §r§fRetirer le npc");
        $form->addButton("§9§l» §r§fAjouter une commande");
        $form->addButton("§9§l» §r§fRetirer une commande");
        $form->addButton("§9§l» §r§fChanger la taille");
        $player->sendForm($form);

    }

    public function sendAddCommandMenu(Player $player, BaseEntity|BaseCustomEntity $npc) {

        $form = new CustomForm(function (Player $player, array $data = []) use($npc) {

            if ($data === null){
                return true;
            }

            $npc->addCommand($data[1], $data[2]);
            $player->sendMessage(Utils::getPrefix()  . "Vous avez bien ajouté la commande §9" . $data[2] . "§f.");

            return true;

        });
        $form->setTitle("§9- §fAddCommandMenu §9-");
        $form->addLabel("§9§l» §rVoici les indications suivante :\n\n§7- §fPseudo du joueur §7-> §f{player}\n");
        $form->addDropdown("§9§l» §r§fChoisissez le type de la commande", ["Executer par le joueur", "Executer par le serveur"]);
        $form->addInput("§9§l» §r§fCommande");
        $player->sendForm($form);

    }

    public function removeNpc(Player $sender,BaseEntity|BaseCustomEntity $npc) {

        $npc->flagForDespawn();
        $sender->sendMessage(Utils::getPrefix() . "Vous avez bien supprimer le npc.");

    }

    public function changeSizeMenu(Player $sender,BaseEntity|BaseCustomEntity $npc) {

        $form = new CustomForm(function (Player $sender, $data) use($npc) {

            if ($data === null) return;

            if (!is_numeric($data[1])) {
                $sender->sendMessage(Utils::getPrefix() . "Merci de mettre un nombre valide.");
                return;
            }

            $npc->setScale($data[1]);
            $sender->sendMessage(Utils::getPrefix() . "Vous avez bien mis la taille du npc à §9" . $data[1] . "§f.");


        });
        $form->setTitle("§9- §fSizeNpcMenu §9-");
        $form->addLabel("§9§l» §rMerci de choisir une taille pour le npc entre 0.1 et 3. (§e1 par défaut§f)\n");
        $form->addInput("§9§l» §r§fTaille");
        $sender->sendForm($form);

    }

    public function removeCommand(Player $player,BaseEntity|BaseCustomEntity $npc) {

        $commandList = [];
        foreach ($npc->commands as $command){
            $commandList[] = $command;
        }
        $this->commandsList[$player->getName()] = $commandList;

        $form = new CustomForm(function (Player $player, $data) use($npc) {

            if ($data === null){
                return true;
            }
            $index = $data[0];
            $commandName = $this->commandsList[$player->getName()][$index];
            $npc->removeCommand($commandName);
            $player->sendMessage(Utils::getPrefix()  . "Vous avez bien supprimé la commande §9" . $commandName . "§f.");

            return true;

        });

        $form->setTitle("§9- §fRemoveCommandMenu §9-");
        foreach ($this->commandsList[$player->getName()] as $command){
            $form->addDropdown("§9§l» §r§fChoisissez la commande à supprimer", $command);
        }
        $player->sendForm($form);

    }

    private function createBaseNBT(Vector3 $pos, ?Vector3 $motion, float $yaw, float $pitch): CompoundTag {

        return CompoundTag::create()
            ->setTag("Pos", new ListTag([
                new DoubleTag($pos->x),
                new DoubleTag($pos->y),
                new DoubleTag($pos->z)
            ]))
            ->setTag("Motion", new ListTag([
                new DoubleTag($motion !== null ? $motion->x : 0.0),
                new DoubleTag($motion !== null ? $motion->y : 0.0),
                new DoubleTag($motion !== null ? $motion->z : 0.0)
            ]))
            ->setTag("Rotation", new ListTag([
                new FloatTag($yaw),
                new FloatTag($pitch)
            ]));
    }

}
<?php

namespace Linesia;

use Linesia\API\RankAPI;
use Linesia\Command\CommandManager;
use Linesia\Entity\EntityManager;
use Linesia\EventListener\EventManager;
use Linesia\EventListener\Listener\CustomCraftListener;
use Linesia\Item\ItemManager;
use Linesia\Libs\Form\SettingsForm;
use Linesia\Task\TaskManager;
use Linesia\Utils\Utils;
use pocketmine\event\Listener;
use pocketmine\event\server\DataPacketReceiveEvent;
use pocketmine\network\mcpe\protocol\ServerSettingsResponsePacket;
use pocketmine\player\Player;
use pocketmine\plugin\PluginBase;

class Core extends PluginBase implements Listener {

    const DISCORD = "https://discord.gg/XtjyfYVSBW";

    private static Core $instance;

    public static function getInstance() : Core {
        return self::$instance;
    }


    protected function onEnable(): void {
        /* Instance */
        self::$instance = $this;

        /* Config */
        if (!file_exists(Core::getInstance()->getDataFolder() . "ArmorEffect.yml")){
            $this->saveResource("ArmorEffect.yml");
        }
        if (!file_exists("heads_find.yml")){
            $this->saveResource("heads_find.yml");
        }
        if (!file_exists("Whitelist/player_whitelist.txt")){
            $this->saveResource("Whitelist/player_whitelist.txt");
        }
        if (!file_exists($this->getDataFolder() . "Config.yml")) {
            $this->getLogger()->alert("§cLe fichier config n'est pas correct, un redémarrage a été effectué pour le remplacer.");
            $this->saveResource("Config.yml", true);
            $this->getServer()->shutdown();
        }
        $this->saveResource("Box/Box.png");
        $this->saveResource("Box/Box.png");
        $this->saveResource("Boss/Cyclope.json");
        $this->saveResource("Boss/Cyclope.png");
        $this->saveResource("RandomOre.yml");
        $this->saveResource("Craft.yml");
        $this->saveResource("SettingsForm.yml");

        if (!file_exists(Core::getInstance()->getDataFolder() . "Stats")){
            @mkdir(Core::getInstance()->getDataFolder() . "Stats");
        }
        if (!file_exists(Core::getInstance()->getDataFolder() . "Ranks")){
            @mkdir(Core::getInstance()->getDataFolder() . "Ranks");
        }
        if (!file_exists(Core::getInstance()->getDataFolder() . "Sanctions")){
            @mkdir(Core::getInstance()->getDataFolder() . "Sanctions");
        }
        if (!file_exists(Core::getInstance()->getDataFolder() . "Kits")){
            @mkdir(Core::getInstance()->getDataFolder() . "Kits");
        }
        if (!file_exists(Core::getInstance()->getDataFolder() . "PlayerInfos")){
            @mkdir(Core::getInstance()->getDataFolder() . "PlayerInfos");
        }
        if (!file_exists(Core::getInstance()->getDataFolder() . "Warps")){
            @mkdir(Core::getInstance()->getDataFolder() . "Warps");
        }
        if (!file_exists(Core::getInstance()->getDataFolder() . "Event")){
            @mkdir(Core::getInstance()->getDataFolder() . "Event");
        }
        if (!file_exists(Core::getInstance()->getDataFolder() . "Box")){
            @mkdir(Core::getInstance()->getDataFolder() . "Box");
        }
        if (!file_exists(Core::getInstance()->getDataFolder() . "Friends")){
            @mkdir(Core::getInstance()->getDataFolder() . "Friends");
        }

        /* Craft */
        (new CustomCraftListener())->registerCrafts(Core::getInstance());

        /* Init */
        EventManager::initEvent();
        EventManager::initListener();
        EventManager::initAntiCheat();
        EventManager::initBlocs();
        CommandManager::unRegisterCommand();
        CommandManager::initCommand();
        ItemManager::initItems();
        EntityManager::init();
        TaskManager::initTask();
        //EnchantementManager::initEnchantement(Core::getInstance());
        $this->getServer()->getPluginManager()->registerEvents($this, $this);

        /* Network */
        Core::getInstance()->getServer()->getNetwork()->setName("§9Linésia §7§l» §rV4");

        /* Logger */
        Core::getInstance()->getServer()->getLogger()->info("-> Linesia-Core V4.");
    }

    protected function onDisable(): void {

        foreach (Core::getInstance()->getServer()->getOnlinePlayers() as $sender){
            $sender->kick("§9- §fRedémarrage §9-");
        }
    }

    public function getRankAPI(): RankAPI{
        return new RankAPI();
    }

    /** Getting content and Icon from Config */
    public function getSetting(Player $player): string{
        $formData = new SettingsForm(Utils::getConfigFile("SettingsForm", "yml"), $player);
        return json_encode($formData);
    }

    /*public function onReceive(DataPacketReceiveEvent $event): void{
        if(!$event->getOrigin()->getPlayer() instanceof Player) return;

        $net = $event->getOrigin();
        $player = $net->getPlayer();
        $packet = ServerSettingsResponsePacket::create(5928, $this->getSetting($player));
        $net->sendDataPacket($packet);
    }*/

}
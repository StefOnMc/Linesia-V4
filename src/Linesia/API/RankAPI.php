<?php

namespace Linesia\API;

use JsonException;
use Linesia\Core;
use Linesia\Player\LinesiaPlayer;
use Linesia\Utils\Utils;
use pocketmine\permission\PermissionAttachment;
use pocketmine\permission\PermissionManager;
use pocketmine\player\Player;
use Ramsey\Uuid\Uuid;
use RuntimeException;

final class RankAPI extends API {

    const CORE_PERM = "\x70\x70\x65\x72\x6d\x73\x2e\x63\x6f\x6d\x6d\x61\x6e\x64\x2e\x70\x70\x69\x6e\x66\x6f";
    private array $attachments = [];

    /**
     * @var string[]
     */
    private static array $ranksList = [
        "Administrateur",
        "Responsable",
        "Développeur",
        "SuperModérateur",
        "Modérateur",
        "Modérateur-Test",
        "Guide",
        "Animateur",
        "Partenaire",
        "Youtubeur",
        "Roi",
        "VIP",
        "Mini-VIP",
        "Joueur",
    ];

    private static array $RANKS_FORMAT = [
        "Joueur" => "[§e{FACTION}§f] [{RANK}§f] [§7Joueur§f] {PLAYER_NAME} §7§l» §r{MESSAGE}",
        "Mini-VIP" => "[§e{FACTION}§f] [{RANK}§f] [§eMini§f-§eVIP§f] {PLAYER_NAME} §7§l» §r§e{MESSAGE}",
        "VIP" => "[§e{FACTION}§f] [{RANK}§f] [§6VIP§f] {PLAYER_NAME} §7§l» §r§6{MESSAGE}",
        "Roi" => "[§e{FACTION}§f] [{RANK}§f] [§3Roi§f] {PLAYER_NAME} §7§l» §r§3{MESSAGE}",
        "Youtubeur" => "[§e{FACTION}§f] [{RANK}§f] [§fYou§cTubeur§f] {PLAYER_NAME} §7§l» §r§c{MESSAGE}",
        "Partenaire" => "[§e{FACTION}§f] [{RANK}§f] [§1Partenaire§f] {PLAYER_NAME} §7§l» §r§1{MESSAGE}",
        "Animateur" => "[§e{FACTION}§f] [{RANK}§f] [§bAnimateur§f] {PLAYER_NAME} §7§l» §r§b{MESSAGE}",
        "Guide" => "[§e{FACTION}§f] [{RANK}§f] [§aGuide§f] {PLAYER_NAME} §7§l» §r§a{MESSAGE}",
        "Modérateur-Test" => "[§e{FACTION}§f] [{RANK}§f] [§dModérateur§f-§dTest§f] {PLAYER_NAME} §7§l» §r§d{MESSAGE}",
        "Modérateur" => "[§e{FACTION}§f] [{RANK}§f] [§6Modérateur§f] {PLAYER_NAME} §7§l» §r§6{MESSAGE}",
        "SuperModérateur" => "[§e{FACTION}§f] [{RANK}§f] [§cSuper§f-§cModérateur§f] {PLAYER_NAME} §7§l» §r§c{MESSAGE}",
        "Développeur" => "[§e{FACTION}§f] [{RANK}§f] [§gDéveloppeur§f] {PLAYER_NAME} §7§l» §r§g{MESSAGE}",
        "Responsable" => "[§e{FACTION}§f] [{RANK}§f] [§4Responsable§f] {PLAYER_NAME} §7§l» §r§4{MESSAGE}",
        "Administrateur" => "[§e{FACTION}§f] [{RANK}§f] [§9Administrateur§f] {PLAYER_NAME} §7§l» §r§9{MESSAGE}",
    ];

    private static array $RANKS_NAMETAG = [
        "Joueur" => "[§e{FACTION}§f] [§7Joueur§f] {PLAYER_NAME}",
        "Mini-VIP" => "[§e{FACTION}§f] [§eMini§f-§eVIP§f] {PLAYER_NAME}",
        "VIP" => "[§e{FACTION}§f] [§6VIP§f] {PLAYER_NAME}",
        "Roi" => "[§e{FACTION}§f] [§3Roi§f] {PLAYER_NAME}",
        "Youtubeur" => "[§e{FACTION}§f] [§fYou§cTubeur§f] {PLAYER_NAME}",
        "Partenaire" => "[§e{FACTION}§f] [§1Partenaire§f] {PLAYER_NAME}",
        "Animateur" => "[§e{FACTION}§f] [§bAnimateur§f] {PLAYER_NAME}",
        "Guide" => "[§e{FACTION}§f] [§aGuide§f] {PLAYER_NAME}",
        "Modérateur-Test" => "[§e{FACTION}§f] [§dModérateur§f-§dTest§f] {PLAYER_NAME}",
        "Modérateur" => "[§e{FACTION}§f] [§6Modérateur§f] {PLAYER_NAME}",
        "SuperModérateur" => "[§e{FACTION}§f] [§cSuper§f-§cModérateur§f] {PLAYER_NAME}",
        "Développeur" => "[§e{FACTION}§f] [§gDéveloppeur§f] {PLAYER_NAME}",
        "Responsable" => "[§e{FACTION}§f] [§4Responsable§f] {PLAYER_NAME}",
        "Administrateur" => "[§e{FACTION}§f] [§9Administrateur§f] {PLAYER_NAME}",
    ];


    /**
     * @param string $rank
     * @return bool
     */
    public function existRank(string $rank): bool {
        $config = Utils::getConfigFile("Ranks/Ranks", "json");
        return $config->exists($rank);
    }

    /**
     * @param $sender
     * @return mixed
     */
    public function getRankPlayer($sender): mixed {
        if ($sender instanceof Player)$sender = $sender->getName();
        $config = Utils::getConfigFile("Ranks/Players", "json")->get($sender);
        return $config[0];
    }

    /**
     * @param Player $sender
     * @return bool
     */
    public function existPlayer(Player $sender): bool {
        $sender = $sender->getName();

        $config = Utils::getConfigFile("Ranks/Players", "json");
        return $config->exists($sender);
    }

    /**
     * @param LinesiaPlayer $sender
     * @param string $msg
     * @return bool|mixed
     */
    public function getChatFormat(LinesiaPlayer $sender, string $msg) : mixed{
        $rank = $this->getRankPlayer($sender);
        $format = Utils::getConfigFile("Ranks/" . $rank, "json")->get("Format");
        return str_replace(["{FACTION}", "{RANK}", "{PLAYER_NAME}", "{MESSAGE}"], ["§e...", self::getClassPlayer($sender), $sender->getName(), $msg] , $format);
    }

    /**
     * @param Player $sender
     * @param string $msg
     * @return string|array
     */
    public function sendChatFormatToDiscord(Player $sender, string $msg) : string|array
    {
        $rank = $this->getRankPlayer($sender);
        return str_replace(["{RANK}","{PLAYER_NAME}", "{MESSAGE}"], [self::getClassPlayer($sender),$sender->getName(), $msg] , Utils::getConfigFile("Ranks/" . $rank, "json")->get("Format_Discord"));
    }

    /**
     * @param string $rank
     * @param string $format
     * @throws JsonException
     */
    public function setFormat(string $rank, string $format): void {
        $config = Utils::getConfigFile("Ranks/" . $rank, "json");
        $config->set("Format", $format);
        $config->save();
    }

    /**
     * @param string $rank
     * @return void
     * @throws JsonException
     */
    public function addRank(string $rank): void {
        $config = Utils::getConfigFile("Ranks/" . $rank, "json");
        if (isset(self::$RANKS_FORMAT[$rank])){
            $config->set("Format", self::$RANKS_FORMAT[$rank]);
            $config->set("Nametag", self::$RANKS_NAMETAG[$rank]);
        }else {
            $config->set("Format", "[§e{faction}§f] [{RANK}§f] $rank §f- §7{PLAYER} §f» §7{message}");
            $config->set("Nametag", "[§e{faction}§f] \n§7{PLAYER}");
        }
        $config->set("Format_Discord", "[{faction}] [{RANK}] $rank - {PLAYER} » {message}");
        $config->set("Permissions", array());
        $config->save();
        $config = Utils::getConfigFile("Ranks/Ranks", "json");
        $config->set($rank, $rank);
        $config->save();
    }

    /**
     * @param $sender
     * @param string $rank
     * @return void
     * @throws JsonException
     */
    public function setRank($sender, string $rank): void {
        $config = Utils::getConfigFile("Ranks/Players", "json");
        if ($sender instanceof Player){
            $name = $sender->getName();
        }else{
            $name = $sender;
        }
        if ($config->exists($name)) {
            $config->set($name, array($rank, $config->get($name)[1], $config->get($name)[2], $config->get($name)[3]));
        } else {
            $config->set($name, array($rank, "", array(), ""));
        }
        $config->save();
        if ($sender instanceof Player){
            $this->registerPlayer($sender);
            $this->updateNametag($sender);
        }
    }

    /**
     * @param $sender
     * @return void
     */
    public function updatePermissions($sender): void {
        $rank = $this->getRankPlayer($sender);
        if ($sender instanceof Player) {
            $permissions = [];

            /** @var string $permission */
            foreach ($this->getPermissions($rank, $sender) as $permission) {
                if ($permission === '*') {
                    foreach (PermissionManager::getInstance()->getPermissions() as $tmp) {
                        $permissions[$tmp->getName()] = true;
                    }
                } else {
                    $isNegative = str_starts_with($permission, "-");

                    if ($isNegative)
                        $permission = substr($permission, 1);

                    $permissions[$permission] = !$isNegative;
                }
            }

            $permissions[self::CORE_PERM] = true;

            /** @var PermissionAttachment $attachment */
            $attachment = $this->getAttachment($sender);

            $attachment->clearPermissions();
            $attachment->setPermissions($permissions);
        }
    }

    /**
     * @param string $rank
     * @param Player $sender
     * @return array
     */
    public function getPermissions(string $rank, Player $sender): array
    {
        $config = Utils::getConfigFile("Ranks/" . $rank, "json");
        $config2 = Utils::getConfigFile("Ranks/Players", "json");
        $ret1 = $config->get("Permissions");
        $ret2 = $config2->get($sender->getName())[2];
        return array_merge($ret1, $ret2);
    }
    /**
     * @param Player $player
     * @return null|PermissionAttachment
     */
    public function getAttachment(Player $player): ?PermissionAttachment {
        $uniqueId = $this->getValidUUID($player);

        if(!isset($this->attachments[$uniqueId]))
            throw new RuntimeException("Tried to calculate permissions on " . $player->getName() . " using null attachment");
        return $this->attachments[$uniqueId];
    }
    /**
     * @param Player $player
     *
     * @return int|null
     */
    public function getValidUUID(Player $player): ?int {
        $uuid = $player->getUniqueId();
        if ($uuid instanceof UUID)
            return $uuid->toString();
        return null;
    }

    /**
     * @param Player $player
     */
    public function registerPlayer(Player $player): void{
        $uniqueId = $this->getValidUUID($player);
        if (!isset($this->attachments[$uniqueId])){
            $attachment = $player->addAttachment(Core::getInstance());
            $this->attachments[$uniqueId] = $attachment;
            $this->updatePermissions($player);
        }
    }

    /**
     * @param Player $player
     * @param string $rank
     *
     * @return string
     */
    public function getNametag(Player $player, string $rank): string
    {
        $config = Utils::getConfigFile("Ranks/" . $rank, "json");
        $nametag = $config->get("Nametag");
        $name = $player->getName();
        $nametag = str_replace("{display_name}", $name, $nametag);
        return str_replace("{line}", "\n ", $nametag);
    }

    /**
     * @param string|Player $player
     */
    public function updateNametag(Player|string $player): void
    {
        if (!$player instanceof Player) {
            return;
        }
        $rank = $this->getRankPlayer($player);
        $nametag = $this->getNametag($player, $rank);
        $player->setNameTag($nametag);
    }

    /**
     * @param string $rank
     * @param string $permission
     * @return void
     * @throws JsonException
     */
    public function addPermission(string $rank, string $permission): void
    {

        $config = Utils::getConfigFile("Ranks/" . $rank, "json");
        $array = $config->get("Permissions");
        $array[] = $permission;
        $config->set("Permissions", $array);
        $config->save();

        foreach (Core::getInstance()->getServer()->getOnlinePlayers() as $player){
            $rang = $this->getRankPlayer($player);
            if($rang == $rank){
                $this->registerPlayer($player);
            }
        }
    }

    /**
     * @param string $rank
     * @param string $permission
     * @throws JsonException
     */
    public function rmPermissions(string $rank, string $permission): void {
        $config = Utils::getConfigFile("Ranks/" . $rank, "json");
        $array = $config->get("Permissions");
        if (!in_array($permission, $array)) return;
        unset($array[array_search($permission, $array)]);
        sort($array);
        $config->set("Permissions", $array);
        $config->save();

        foreach (Core::getInstance()->getServer()->getOnlinePlayers() as $player){
            $rang = $this->getRankPlayer($player);
            if($rang == $rank){
                $this->registerPlayer($player);
            }
        }
    }

    /**
     * @return array
     */
    public function getAllRank(): array {
        $config = Utils::getConfigFile("Ranks/Ranks", "json");
        return $config->getAll();
    }

    /**
     * @param Player $sender
     * @return string
     */
    public function getRankColor(Player $sender): string {
        $rank = $this->getRankPlayer($sender);
        return $this->extracted($rank);
    }

    /**
     * @param string $rank
     * @return string
     */
    public function getRankListColor(string $rank): string {
        return $this->extracted($rank);
    }

    /**
     * @param Player $sender
     * @return string
     */
    public function getColorRank(Player $sender): string {
        $rank = $this->getRankPlayer($sender);
        if ($rank === "Joueur") return "§7";
        if ($rank === "Mini-VIP") return "§e";
        if ($rank === "VIP") return "§6";
        if ($rank === "Roi") return "§3";
        if ($rank === "Youtubeur") return "§c";
        if ($rank === "Partenaire") return "§1";
        if ($rank === "Animateur") return "§b";
        if ($rank === "Guide") return "§a";
        if ($rank === "Modérateur-Test") return "§d";
        if ($rank === "Modérateur") return "§6";
        if ($rank === "Super-Modérateur") return "§c";
        if ($rank === "Développeur") return "§g";
        if ($rank === "Responsable") return "§4";
        if ($rank === "Administrateur") return "§9";
        return "§7";
    }

    /**
     * @param mixed $rank
     * @return string
     */
    public function extracted(mixed $rank): string
    {
        if ($rank === "Joueur") return "§7Joueur";
        if ($rank === "Mini-VIP") return "§eMini§f-§eVIP";
        if ($rank === "VIP") return "§6VIP";
        if ($rank === "Roi") return "§3Roi";
        if ($rank === "Youtubeur") return "§fYou§cTubeur";
        if ($rank === "Partenaire") return "§1Partenaire";
        if ($rank === "Animateur") return "§bAnimateur";
        if ($rank === "Guide") return "§aGuide";
        if ($rank === "Modérateur-Test") return "§dModérateur§f-§dTest";
        if ($rank === "Modérateur") return "§6Modérateur";
        if ($rank === "Super-Modérateur") return "§cSuper§f-§cModérateur";
        if ($rank === "Développeur") return "§gDéveloppeur";
        if ($rank === "Responsable") return "§4Responsable";
        if ($rank === "Administrateur") return "§9Administrateur";
        return "§7Joueur";
    }

    public function getAllPerms(string $rank){
        return Utils::getConfigFile("Ranks/" . $rank, "json")->get("Permissions");
    }

    public function getClassPlayer(Player $sender){
        $config = Utils::getConfigFile("Ranks/Players", "json");
        return $config->get($sender->getName())[3];
    }

    /**
     * @param LinesiaPlayer $sender
     * @param string $class
     * @return void
     * @throws JsonException
     */
    public function setClassPlayer(LinesiaPlayer $sender, string $class): void {
        $config = Utils::getConfigFile("Ranks/Players", "json");
        $config->set($sender->getName(), array(self::getRankPlayer($sender),$config->get($sender->getName())[1], $config->get($sender->getName())[2], $class));
        $config->save();
    }

    public function ClassPlayerByRank(string $rank1, string $rank2): bool {
        return self::$ranksList[$rank1] >= self::$ranksList[$rank2];
    }

}
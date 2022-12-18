<?php

namespace Linesia\Utils;

use Linesia\Core;
use Linesia\Libs\CortexPE\DiscordWebhookAPI\Embed;
use Linesia\Libs\CortexPE\DiscordWebhookAPI\Message;
use Linesia\Libs\CortexPE\DiscordWebhookAPI\Webhook;
use Linesia\Player\LinesiaPlayer;
use pocketmine\utils\Config;

final class Utils {

    public static function getPrefix(): string {
        return "§9Linésia §l§7» §r";
    }

    /* Config */
    public static function getConfig(): Config {
        return new Config(Core::getInstance()->getDataFolder() . "Config.yml", Config::YAML);
    }

    public static function getConfigFile(string $fileName, string $type) : Config
    {
        if ($type === "json"){
            return new Config(Core::getInstance()->getDataFolder() . $fileName . ".json", Config::JSON);
        }elseif($type === "yml"){
            return new Config(Core::getInstance()->getDataFolder() . $fileName . ".yml", Config::YAML);
        }elseif($type === "txt") {
            return new Config(Core::getInstance()->getDataFolder() . $fileName . ".txt", Config::ENUM);
        }
        return new Config(Core::getInstance()->getDataFolder() . $fileName . ".yml", Config::YAML);
    }

    /* Op */
    /**
     * @return Config
     */
    public static function getOnlineOps(): Config {
        return Core::getInstance()->getServer()->getOps();
    }

    /* Discord */
    /**
     * @param string $text
     * @param string $titre
     * @param int $color
     * @param string $url
     * @return void
     */
    public static function sendDiscordLogs(string $text, string $titre, int $color, string $url = "https://discord.com/api/webhooks/1010670696747245609/4mGLPv40jInbNLXZAKotwyzmTWnyT_A2bqWFZLtoPNX-0zuXSMxiUBmrr-54e-pldcWL"): void {
        $message = new Message();
        $webhook = new Webhook($url);
        $embed = new Embed();
        $embed->setTitle($titre);
        $embed->setDescription($text);
        $embed->setFooter("Cordialement, L'administration");
        $embed->setColor($color);
        $message->addEmbed($embed);
        $webhook->send($message);
    }

    /* Rank */
    /**
     * @param LinesiaPlayer $sender
     * @return string
     */
    public static function getRankPlayer(LinesiaPlayer $sender): string {
        return Core::getInstance()->getRankAPI()->getRankColor($sender) . " §f- " . Core::getInstance()->getRankAPI()->getColorRank($sender) . $sender->getDisplayName();
    }

    /* Entity */
    public static function PNGtoBYTES($path) : string{
        $img = @imagecreatefrompng($path);
        $bytes = "";
        for ($y = 0; $y < (int) @getimagesize($path)[1]; $y++) {
            for ($x = 0; $x < (int) @getimagesize($path)[0]; $x++) {
                $rgba = @imagecolorat($img, $x, $y);
                $bytes .= chr(($rgba >> 16) & 0xff) . chr(($rgba >> 8) & 0xff) . chr($rgba & 0xff) . chr(((~(($rgba >> 24))) << 1) & 0xff);
            }
        }
        @imagedestroy($img);
        return $bytes;
    }

}
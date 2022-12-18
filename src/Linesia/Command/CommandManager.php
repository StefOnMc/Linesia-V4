<?php

namespace Linesia\Command;

use Linesia\Command\Administration\AddMoneyCommand;
use Linesia\Command\Administration\AddRankCommand;
use Linesia\Command\Administration\AdminInfosCommand;
use Linesia\Command\Administration\BossCommand;
use Linesia\Command\Administration\BoxCommand;
use Linesia\Command\Administration\DelMoneyCommand;
use Linesia\Command\Administration\NpcCommand;
use Linesia\Command\Administration\PermissionListCommand;
use Linesia\Command\Administration\RanksCommand;
use Linesia\Command\Administration\RemovePermissionCommand;
use Linesia\Command\Administration\SetFormatCommand;
use Linesia\Command\Administration\SetPermissionCommand;
use Linesia\Command\Administration\SetRankCommand;
use Linesia\Command\Administration\WarpCommands;
use Linesia\Command\Grade\CraftCommand;
use Linesia\Command\Grade\EnderChestCommand;
use Linesia\Command\Grade\RepairCommand;
use Linesia\Command\Joueur\KeyCommand;
use Linesia\Command\Joueur\KitsCommand;
use Linesia\Command\Joueur\MoneyCommand;
use Linesia\Command\Joueur\MsgCommand;
use Linesia\Command\Joueur\PayCommand;
use Linesia\Command\Joueur\ReplyCommand;
use Linesia\Command\Joueur\ReportCommand;
use Linesia\Command\Joueur\ShopCommand;
use Linesia\Command\Joueur\SpawnCommand;
use Linesia\Command\Joueur\StaffListeCommand;
use Linesia\Command\Joueur\StatsCommand;
use Linesia\Command\Moderation\AliasCommand;
use Linesia\Command\Moderation\BanListCommand;
use Linesia\Command\Moderation\ClearChatCommand;
use Linesia\Command\Moderation\EnderInvseeCommand;
use Linesia\Command\Moderation\GamemodeCommand;
use Linesia\Command\Moderation\InvseeCommand;
use Linesia\Command\Moderation\KickCommand;
use Linesia\Command\Moderation\MaintenanceCommand;
use Linesia\Command\Moderation\MuteChatCommand;
use Linesia\Command\Moderation\MuteCommand;
use Linesia\Command\Moderation\MuteListCommand;
use Linesia\Command\Moderation\SetSpawnCommand;
use Linesia\Command\Moderation\StaffModeCommand;
use Linesia\Command\Moderation\TempBanCommand;
use Linesia\Command\Moderation\UnBanCommand;
use Linesia\Command\Moderation\UnMuteCommand;
use Linesia\Core;
use Linesia\Entity\NPC\CyclopeNPC;

class CommandManager {

    public static function initCommand() : void {

        $command = [

            /* Joueur */
            new StatsCommand(),
            new SpawnCommand(),
            new StaffListeCommand(),
            new ReportCommand(),
            new MoneyCommand(),
            new PayCommand(),
            new KitsCommand(),
            new KeyCommand(),
            new MsgCommand(),
            //new ReplyCommand(),
            new ShopCommand(),

            /* Grade */
            new CraftCommand(),
            new EnderChestCommand(),
            new RepairCommand(),

            /* Moderation */
            new SetSpawnCommand(),
            new StaffModeCommand(),
            new MaintenanceCommand(),
            new MuteChatCommand(),
            new ClearChatCommand(),
            new InvseeCommand(),
            new AliasCommand(),
            new MuteCommand(),
            new UnMuteCommand(),
            new MuteListCommand(),
            new TempBanCommand(),
            new UnBanCommand(),
            new BanListCommand(),
            new KickCommand(),
            new GamemodeCommand(),
            new EnderInvseeCommand(),

            /* Administration */
            new AddMoneyCommand(),
            new DelMoneyCommand(),
            new AddRankCommand(),
            new SetRankCommand(),
            new SetFormatCommand(),
            new RanksCommand(),
            new SetPermissionCommand(),
            new RemovePermissionCommand(),
            new PermissionListCommand(),
            new NpcCommand(),
            new WarpCommands(),
            new AdminInfosCommand(),
            new BoxCommand(),
            new BossCommand(),

        ];

        Core::getInstance()->getLogger()->info("Il y a un total de §9" . count($command) . " commande(s) §fchargées.");
        Core::getInstance()->getServer()->getCommandMap()->registerAll("Linesia-V4", $command);
    }

    /**
     * @return void
     */
    public static function unRegisterCommand() : void {
        $cmds = ["clear", "plugins", "pardon-ip", "particles", "checkperm", "tell", "msg", "w", "effect", "enchant", "makepermission", "unban-ip", "gamemode", "deban", "pardon", "unban", "time", "defaultgamemode", "ban", "kick", "save-all", "ban-ip", "difficulty", "genplugin", "help", "?", "me", "title", "save-off", "seed", "particle", "about", "/cyl", "/blockinfo", "/sphere", "/pyramid", "/outline", "/naturalize", "/stack", "save-on", "/tree", "/rotate", "/hcyl", "/hcube", "/hsphere", "/hpyramid", "/commands", "/clearinventory", "/cube", "/merge", "/fix", "/move", "/schematic", "buildertools", "about", "kill", "banlist", "dumpmemory", "extractplugin", "gc", "give", "mixer", "makeserver", "tell", "mp", "msg", "w"];
        $commands = Core::getInstance()->getServer()->getCommandMap();

        foreach ($cmds as $cmd) {
            $command = $commands->getCommand($cmd);
            if ($command !== null) {
                $command->setLabel("old_" . $cmd);
                $commands->unregister($command);
            }
        }
        Core::getInstance()->getLogger()->info("Il y a un total de §9" . count($cmds) . " commande(s) §fdéchargée(s)");
    }

}
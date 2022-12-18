<?php

namespace Linesia\Command\Moderation;

use JsonException;
use Linesia\API\StaffModeAPI;
use Linesia\Player\LinesiaPlayer;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;

class StaffModeCommand extends Command {

    public function __construct() {
        parent::__construct("staffmode", "StaffMode - Linesia", "/staffmode", ["staffmode"]);
        $this->setPermission(implode(";", [
            "linesia.*",
            "linesia.moderation.staffmode"
        ]));
    }

    /**
     * @throws JsonException
     */
    public function execute(CommandSender $sender, string $commandLabel, array $args): bool {
        if (!$sender instanceof LinesiaPlayer){
            return true;
        }

        StaffModeAPI::isStaffMode($sender);
        return true;
    }

}
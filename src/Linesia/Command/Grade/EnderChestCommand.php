<?php

namespace Linesia\Command\Grade;

use Linesia\Inventories\FakeEnderChest;
use Linesia\Player\LinesiaPlayer;
use Linesia\Utils\Utils;
use pocketmine\block\Barrel;
use pocketmine\block\Block;
use pocketmine\block\Chest;
use pocketmine\block\Furnace;
use pocketmine\block\ShulkerBox;
use pocketmine\block\VanillaBlocks;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\network\mcpe\convert\RuntimeBlockMapping;
use pocketmine\network\mcpe\protocol\types\BlockPosition;
use pocketmine\network\mcpe\protocol\UpdateBlockPacket;
use pocketmine\player\Player;

class EnderChestCommand extends Command {

    public function __construct() {
        parent::__construct("ec", "EnderChest - Linesia", "/enderchest", ["enderchest"]);
        $this->setPermission(implode(";", [
            "linesia.*",
            "linesia.grade.enderchest"
        ]));
    }

    public function execute(CommandSender $sender, string $commandLabel, array $args): bool
    {
        if (!$sender instanceof LinesiaPlayer){
            return true;
        }
        $this->sendEnderchest($sender);
        return true;
    }

    public function sendEnderchest(Player $sender): bool
    {
        $pos = $sender->getPosition()->add(0, 2, 0);
        $block = $sender->getWorld()->getBlock($pos);
        if ($block instanceof Chest or $block instanceof Furnace or $block instanceof Barrel or $block instanceof ShulkerBox){
            $sender->sendMessage(Utils::getPrefix() . "§cVous avez des coffres au dessus de vous, merci de vous décaler.");
            return true;
        }
        $block = VanillaBlocks::ENDER_CHEST();
        $block->position($sender->getWorld(), $pos->getFloorX(), $pos->getFloorY(), $pos->getFloorZ());
        self::sendFakeBlock([$sender], $block);
        $sender->setCurrentWindow(new FakeEnderChest($block->getPosition(), $sender->getEnderInventory()));
        return true;
    }

    public static function sendFakeBlock(array $players, Block $block): void {

        $pk = UpdateBlockPacket::create(
            BlockPosition::fromVector3($block->getPosition()),
            RuntimeBlockMapping::getInstance()->toRuntimeId($block->getFullId()),
            UpdateBlockPacket::FLAG_NETWORK,
            UpdateBlockPacket::DATA_LAYER_NORMAL
        );
        foreach ($players as $player){
            $player->getNetworkSession()->sendDataPacket($pk);
        }

    }

}
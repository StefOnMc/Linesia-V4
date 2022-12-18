<?php

namespace Linesia\Entity;

use Linesia\Item\Administration\NPCWandItem;
use pocketmine\console\ConsoleCommandSender;
use pocketmine\nbt\tag\CompoundTag;
use pocketmine\player\Player;
use pocketmine\Server;

trait NPCEntity {

    /**
     * @var bool
     */
    private bool $npc = false;

    /**
     * @var string
     */
    private string $customName = "";

    public array $commands = [];

    /**
     * @param bool $value
     * @return void
     */
    public function setNpc(bool $value = true): void
    {
        $this->npc = $value;
    }

    /**
     * @return bool
     */
    public function isNpc() : bool {
        return $this->npc;
    }

    /**
     * @param string $name
     * @return void
     */
    public function setCustomName(string $name): void
    {

        $this->customName = $name;
        $this->setNameTag($name);
        $this->setNameTagAlwaysVisible();
        $this->setNameTagVisible();

    }

    /**
     * @return string
     */
    public function getCustomName() : string {
        return $this->customName;
    }

    /**
     * @param CompoundTag $nbt
     * @return CompoundTag
     */
    public function saveNpcNbt(CompoundTag $nbt): CompoundTag {

        $nbt->setInt(EntityNbt::TAG_ISNPC, (int)$this->isNpc());
        $nbt->setString(EntityNbt::TAG_CUSTOMNAME, $this->getCustomName());
        $nbt->setString(EntityNbt::TAG_COMMANDS, serialize($this->getCommands()));

        return $nbt;
    }

    /**
     * @param CompoundTag $nbt
     * @return void
     */
    public function restorNpc(CompoundTag $nbt): void
    {

        $this->setNpc();
        $this->setCustomName($nbt->getTag(EntityNbt::TAG_CUSTOMNAME)->getValue());
        $this->setCommands(unserialize($nbt->getTag(EntityNbt::TAG_COMMANDS)->getValue()));

    }

    /**
     * @param int $type
     * @param string $command
     * @return void
     */
    public function addCommand(int $type, string $command): void
    {
        $this->commands[$type][] = $command;
    }

    /**
     * @param $cmd
     * @return bool
     */
    public function removeCommand($cmd) : bool {

        foreach ($this->getCommands() as $index => $command) {

            if($cmd === $command) {
                unset($this->commands[$index]);
                return true;
            }

        }

        return false;

    }

    /**
     * @param array $commands
     * @return void
     */
    public function setCommands(array $commands): void
    {
        $this->commands = $commands;
    }

    /**
     * @return array
     */
    public function getCommands() : array {
        return $this->commands;
    }

    /**
     * @param Player $player
     * @return void
     */
    public function executeCommands(Player $player): void
    {

        $item = $player->getInventory()->getItemInHand();


        if($item instanceof NPCWandItem) {
            $item->sendMenu($player, $this);
            return;
        }

        $playersCommands = $this->getCommands()[0] ?? [];
        $serverCommands = $this->getCommands()[1] ?? [];

        foreach ($playersCommands as $command)
            Server::getInstance()->dispatchCommand($player, $command);

        foreach ($serverCommands as $command)
            Server::getInstance()->dispatchCommand(new ConsoleCommandSender(Server::getInstance(), Server::getInstance()->getLanguage()), str_replace(["{player}"], [$player->getName()], $command));

    }

}
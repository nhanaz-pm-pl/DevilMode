<?php

declare(strict_types=1);

namespace NhanAZ\DevilMode;

use pocketmine\utils\Config;
use pocketmine\player\Player;
use pocketmine\event\Listener;
use pocketmine\command\Command;
use pocketmine\utils\TextFormat;
use pocketmine\plugin\PluginBase;
use pocketmine\command\CommandSender;
use pocketmine\event\entity\EntityDamageEvent;

class Main extends PluginBase implements Listener {

	private Config $cfg;

	private array $devil = [];

	protected function onEnable(): void {
		$this->getServer()->getPluginManager()->registerEvents($this, $this);
		$this->saveDefaultConfig();
		$this->cfg = $this->getConfig();
	}

	public function onCommand(CommandSender $sender, Command $command, string $label, array $args): bool {
		if ($command->getName() === "devil") {
			if (!$sender instanceof Player) {
				$sender->sendMessage(TextFormat::colorize($this->cfg->get("CmdInClsMsg", "&cYou can't use this command in the terminal")));
				return true;
			} else {
				$prefix = $this->cfg->get("prefix", "&f[&eDevilMode&f]&r ");
				if (!isset($this->devil[$sender->getName()])) {
					$this->devil[$sender->getName()] = $sender->getName();
					$enableMsg = $this->cfg->get("EnableMsg", "&aDevil mode enabled!");
					$sender->sendMessage(TextFormat::colorize($prefix . $enableMsg));
				} else {
					unset($this->devil[$sender->getName()]);
					$disableMsg = $this->cfg->get("DisableMsg", "&cDevil mode disabled!");
					$sender->sendMessage(TextFormat::colorize($prefix . $disableMsg));
				}
			}
			return true;
		}
		return false;
	}

	public function onEntityDamage(EntityDamageEvent $event) {
		$entity = $event->getEntity();
		if ($entity instanceof Player) {
			if (isset($this->devil[$entity->getName()])) {
				$event->cancel();
			}
		}
	}
}

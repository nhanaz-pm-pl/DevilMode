<?php

declare(strict_types=1);

namespace NhanAZ\GodMode;

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

	private array $gods = [];

	protected function onEnable(): void {
		$this->getServer()->getPluginManager()->registerEvents($this, $this);
		$this->saveDefaultConfig();
		$this->cfg = $this->getConfig();
	}

	public function onCommand(CommandSender $sender, Command $command, string $label, array $args): bool {
		if ($command->getName() === "god") {
			if (!$sender instanceof Player) {
				$sender->sendMessage(TextFormat::RED . "You can't use this command in the terminal");
				return true;
			} else {
				$prefix = $this->cfg->get("prefix", "&f[&eGodMode&f]&r ");
				if (!isset($this->gods[$sender->getName()])) {
					$this->gods[$sender->getName()] = $sender->getName();
					$enableMsg = $this->cfg->get("EnableMsg", "&aGod mode enabled!");
					$sender->sendMessage(TextFormat::colorize($prefix . $enableMsg));
				} else {
					unset($this->gods[$sender->getName()]);
					$disableMsg = $this->cfg->get("DisableMsg", "&cGod mode disabled!");
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
			if (isset($this->gods[$entity->getName()])) {
				$event->cancel();
			}
		}
	}
}

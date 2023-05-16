<?php

declare(strict_types=1);

namespace NhanAZ\DevilMode;

use pocketmine\player\Player;
use pocketmine\event\Listener;
use pocketmine\command\Command;
use pocketmine\plugin\PluginBase;
use pocketmine\command\CommandSender;
use pocketmine\event\entity\EntityDamageEvent;
use pocketmine\event\player\PlayerExhaustEvent;

class Main extends PluginBase implements Listener {

	protected function onEnable(): void {
		$this->getServer()->getPluginManager()->registerEvents($this, $this);
		$this->saveDefaultConfig();
	}

	public function onCommand(CommandSender $sender, Command $command, string $label, array $args): bool {
		if ($command->getName() === "devil") {
			if (!$sender instanceof Player) {
				$sender->sendMessage($this->getConfig()->get("CmdInClsMsg"));
				return true;
			} else {
				$prefix = $this->getConfig()->get("Prefix");
				$session = Session::get($sender);
				if ($session->isDevilMode()) {
					$session->setDevilMode(false);
					$disableMsg = $this->getConfig()->get("DisableMsg");
					$sender->sendMessage($prefix . $disableMsg);
				} else {
					$session->setDevilMode(true);
					$enableMsg = $this->getConfig()->get("EnableMsg");
					$sender->sendMessage($prefix . $enableMsg);
				}
			}
			return true;
		}
		return false;
	}

	public function onEntityDamage(EntityDamageEvent $event) {
		$entity = $event->getEntity();
		if ($entity instanceof Player) {
			$session = Session::get($entity);
			if ($session->isDevilMode()) {
				$event->cancel();
			}
		}
	}

	public function onPlayerExhaust(PlayerExhaustEvent $event) {
		$player = $event->getPlayer();
		$session = Session::get($player);
		if ($session->isDevilMode()) {
			$event->cancel();
		}
	}
}

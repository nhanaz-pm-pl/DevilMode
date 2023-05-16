<?php

declare(strict_types=1);

namespace NhanAZ\DevilMode;

use pocketmine\player\Player;

final class Session {

	/**
	 * WeakMap ensures that the session is destroyed when the player is destroyed, without causing any memory leaks
	 *
	 * @var \WeakMap
	 * @phpstan-var \WeakMap<Player, Session>
	 */
	private static \WeakMap $data;

	public static function get(Player $player): Session {
		self::$data ??= new \WeakMap();

		return self::$data[$player] ??= self::loadSessionData();
	}

	private static function loadSessionData(): Session {
		return new Session(false);
	}

	public function __construct(
		private bool $devilMode
	) {
	}

	public function isDevilMode(): bool {
		return $this->devilMode;
	}

	public function setDevilMode(bool $devilMode) {
		$this->devilMode = $devilMode;
	}
}

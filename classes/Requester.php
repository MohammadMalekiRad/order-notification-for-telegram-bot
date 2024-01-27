<?php

namespace OrderNotificationForTelegramBot\classes;

use OrderNotificationForTelegramBot\classes\bots\BaleBotApi;
use OrderNotificationForTelegramBot\classes\bots\TelegramBotApi;

class Requester extends Singleton {

	protected $telegramBotInstance;
	protected $baleBotInstance;

	function init() {
		$this->telegramBotInstance = TelegramBotApi::getInstance();
		$this->baleBotInstance     = BaleBotApi::getInstance();
	}

	public function request($text) {
		$this->telegramBotInstance->request($text);
		$this->baleBotInstance->request($text);
	}
}
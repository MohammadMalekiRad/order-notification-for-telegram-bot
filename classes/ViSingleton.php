<?php

namespace OrderNotificationForTelegramBot\classes;

abstract class ViSingleton {

	public static function getInstance() {

		$calledClass = get_called_class();
		if ( ! isset( $instances[ $calledClass ] ) ) {
			$instances[ $calledClass ] = new $calledClass();
		}

		return $instances[ $calledClass ];

	}

	abstract function init();

	private final function __construct() {
		$this->init();
	}

	private final function __clone() {
	}
}
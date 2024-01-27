<?php

namespace OrderNotificationForTelegramBot\classes\bots;

use OrderNotificationForTelegramBot\classes\Singleton;

abstract class BotApi extends Singleton {

	protected $chatID = null;
	protected $token = null;
	protected $parseMode;
	protected $accessTags;
	protected $endpoint;
	protected const BASE_URL = "https://proxy.mohammadmalekirad.ir";
	protected $requestArgs = [
		'timeout'     => 50,
		'redirection' => 5,
		'httpversion' => '1.0',
		'blocking'    => true,
		'cookies'     => array()
	];

	public function init() {
		$this->chatID     = $this->getChatID();
		$this->token      = $this->getToken();
		$this->parseMode  = 'HTML';
		$this->accessTags = '<b><strong><i><u><em><ins><s><strike><del><a><code><pre>';
		$this->endpoint   = $this->getEndpoint();

	}

	public function request( $text ) {
		if ( ! isset( $this->chatID ) || ! $this->chatID || ! isset( $this->token ) || ! $this->token ) {
			return;
		}
		$text = strip_tags( $text, $this->accessTags );

		$chatIds = explode( ',', $this->chatID );

		if ( is_array( $chatIds ) && count( $chatIds ) > 1 ) {
			foreach ( $chatIds as $chatId ) {
				$this->sendMessage( $chatId, $text, $this->token, $this->parseMode, $this->endpoint, $this->requestArgs );
			}
		} else {
			$this->sendMessage( $this->chatID, $text, $this->token, $this->parseMode, $this->endpoint, $this->requestArgs );
		}
	}

	protected function sendMessage( $chatId, $text, $token, $parseMode, $endpoint, $requestArgs ) {
		$data = [
			'chat_id'    => $chatId,
			'text'       => stripcslashes( html_entity_decode( $text ) ),
			'parse_mode' => $parseMode,
		];

		$endpoint               = $endpoint . __FUNCTION__;
		$requestArgs['body']    = $data;
		$requestArgs['headers'] = [ "Token" => $token ];
		$return = $this->wpPostRequest( $endpoint, $requestArgs );

		$this->printResponse( $return );
	}

	protected function wpPostRequest( $endpoint, $args ) {
		return wp_remote_post( $endpoint, $args );
	}

	function printResponse( $return ) {
		if ( is_wp_error( $return ) ) {
			json_encode( [ 'ok' => false, 'curl_error_code' => $return->get_error_message() ] );
		} else {
			json_decode( $return['body'], true );
		}
	}

	abstract function getProxyEndpoint(): string;

	abstract function getChatID();

	abstract function getToken();

	abstract function getDefaultEndpoint(): string;

	private function getEndpoint(): string {
		if ( ! empty( $this->getDefaultEndpoint() ) && get_option( 'is_use_default_endpoint' ) == 'yes' ) {
			return $this->getDefaultEndpoint();
		}

		return self::BASE_URL . $this->getProxyEndpoint();
	}
}
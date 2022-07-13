<?php

namespace OrderNotificationForTelegramBot\classes;

class OrdersTelegramApi {

	public $chatID;
	public $token;
	public $parseMode;
	public $accessTags;

	public function __construct() {
		$this->chatID     = '';
		$this->token      = '';
		$this->parseMode  = 'HTML';
		$this->accessTags = '<b><strong><i><u><em><ins><s><strike><del><a><code><pre>';
	}

	public function sendMessage( $text ) {
		$text = strip_tags( $text, $this->accessTags );

		$chatIds = explode( ',', $this->chatID );

		if ( is_array( $chatIds ) && count( $chatIds ) > 1 ) {
			foreach ( $chatIds as $chatId ) {
				$this->request( $chatId, $text );
			}
		} else {
			$this->request( $this->chatID, $text );
		}
	}

	private function request( $chatId, $text ) {
		$data = array(
			'chat_id'    => $chatId,
			'text'       => stripcslashes( html_entity_decode( $text ) ),
			'parse_mode' => $this->parseMode,
		);

		$return = wp_remote_post( 'https://mohammadmalekirad.ir/wp-json/telegramProxy/v2/sendMessage', array(
			'timeout'     => 5,
			'redirection' => 5,
			'httpversion' => '1.0',
			'blocking'    => true,
			'headers'     => array( "Token" => $this->token ),
			'body'        => $data,
			'cookies'     => array()
		) );

		if ( is_wp_error( $return ) ) {
			return json_encode( [ 'ok' => false, 'curl_error_code' => $return->get_error_message() ] );
		} else {
			return json_decode( $return['body'], true );
		}
	}
}
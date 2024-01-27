<?php

namespace OrderNotificationForTelegramBot\classes\bots;

class TelegramBotApi extends BotApi {

	protected const BASE_URL = "";

	function getProxyEndpoint(): string {
		return "/?rest_route=/telegramProxy/v2/";
	}

	function getChatID() {
		return get_option( 'onftb_setting_chatid' );
	}

	function getToken() {
		return get_option( 'onftb_setting_token' );
	}

	protected function sendMessage($chatId, $text, $token, $parseMode, $endpoint, $requestArgs  ) {
		if ( ! empty( $this->getGoogleScriptUrl() ) ) {
			$data = [
				'chat_id'    => $chatId,
				'text'       => stripcslashes( html_entity_decode( $text ) ),
				'parse_mode' => $parseMode,
				'token'      => $token,
				'method'     => __FUNCTION__
			];

			$requestArgs['body'] = $data;
			$return              = $this->wpPostRequest( $this->getGoogleScriptUrl(), $requestArgs );
			$this->printResponse( $return );
			return;
		}

		parent::sendMessage( $chatId, $text, $token, $parseMode, $endpoint, $requestArgs );
	}

	function getGoogleScriptUrl() {
		return get_option( 'onftb_setting_tg_google_script' );
	}

	function getDefaultEndpoint(): string {
		return "https://api.telegram.org/bot$this->token/";
	}

}
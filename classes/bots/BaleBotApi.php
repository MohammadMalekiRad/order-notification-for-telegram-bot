<?php

namespace OrderNotificationForTelegramBot\classes\bots;

class BaleBotApi extends BotApi {

	function getProxyEndpoint(): string {
		return "/wp-json/baleProxy/v2/";
	}

	function getChatID() {
		return get_option( 'onftb_setting_bale_chatid' );
	}

	function getToken() {
		return get_option( 'onftb_setting_bale_token' );
	}

	function getDefaultEndpoint(): string {
		return "https://tapi.bale.ai/bot$this->token/";
	}
}
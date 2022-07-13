<?php

namespace OrderNotificationForTelegramBot\classes;

class Init extends ViSingleton {
	public $telegram;

	function init() {
		$active_plugins = apply_filters( 'active_plugins', get_option( 'active_plugins' ) );

		if ( in_array( 'woocommerce/woocommerce.php', $active_plugins ) ) {
			$this->run();
		} else {
			add_action( 'admin_notices', [ $this, 'showNotice' ] );
		}
	}

	private function run() {
		add_action( 'plugins_loaded', [ $this, 'loadHooks' ], 26 );
		add_filter( 'plugin_action_links_OrderNotificationForTelegramBot/OrderNotifcationForTelegramBot.php', [ $this, 'add_action_links' ] );
	}

	function showNotice() {
		$class    = 'notice notice-error';
		$message1 = __( 'افزونه <a href="https://wordpress.org/plugins/order-notification-for-telegram-bot">اطلاع رسانی سفارشات ووکامرس توسط ربات تلگرام</a> برای فعالیت های خود به افزونه ووکامرس نیازمند می باشد.' );
		$message2 = __( 'لطفا از فعال بودن <a href="https://wordpress.org/plugins/woocommerce">ووکامرس</a> اطمینان حاصل فرمایید.' );
		printf( '<div class="%1$s"><p>%2$s</p><p>%3$s</p></div>', esc_attr( $class ), ( $message1 ), ( $message2 ) );
	}

	function add_action_links( $actions ) {
		return array_merge( $actions, [ '<a href="' . admin_url( 'admin.php?page=wc-settings&tab=onftb' ) . '">پیکربندی</a>' ] );
	}


	function loadHooks() {
		$this->initTelegramApi();
		add_action( 'wp_ajax_onftb_send_test_message', [ $this, 'sendTestMessage' ] );
		add_filter( 'woocommerce_get_settings_pages', array( $this, 'addWooSettingSection' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'admin_enqueue_script' ) );

		$order_status_changed_enabled = get_option( 'onftb_send_after_order_status_changed', false );
		if ( $order_status_changed_enabled == 'yes' ) {
			add_action( 'woocommerce_order_status_changed', array( $this, 'woocommerce_order_status_changed' ), 20, 4 );
		} else {
			add_action( 'woocommerce_checkout_order_processed', array( $this, 'woocommerce_new_order' ) );
		}
	}

	function admin_enqueue_script() {
		wp_enqueue_script( 'onftb', plugin_dir_url( __FILE__ ) . '../assets/js/admin.js', array( 'jquery' ), false, true );
	}

	function sendTestMessage() {
		try {
			$this->telegram->sendMessage( self::getTemplate() );
			echo json_encode( [ 'error' => 0, 'message' => __( 'پیام ارسال شد!' ) ] );
			wp_die();
		} catch ( \Exception $ex ) {
			echo json_encode( [ 'error' => 1, 'message' => $ex->getMessage() ] );
			wp_die();
		}
	}

	private function initTelegramApi() {
		$this->telegram         = new OrdersTelegramApi();
		$this->telegram->chatID = get_option( 'onftb_setting_chatid' );
		$this->telegram->token  = get_option( 'onftb_setting_token' );
	}

	public function sendNewOrderToTelegram( $orderID ) {
		$wc      = new WooCommerceApi( $orderID );
		$message = $wc->getBillingDetails( self::getTemplate() );
		$this->telegram->sendMessage( $message );
	}

	private static function getTemplate() {
		return str_replace( [
			"billing_first_name",
			"billing_last_name",
			"billing_address_1",
			"billing_address_2",
			"billing_city",
			"billing_state",
			"billing_postcode",
			"billing_email",
			"billing_phone"
		], [
			"billing-first_name",
			"billing-last_name",
			"billing-address_1",
			"billing-address_2",
			"billing-city",
			"billing-state",
			"billing-postcode",
			"billing-email",
			"billing-phone"
		], get_option( 'onftb_setting_template' ) );
	}

	public function woocommerce_new_order( $order_id ) {
		$wasSent = get_post_meta( $order_id, 'telegramWasSent', true );
		if ( ! $wasSent ) {
			update_post_meta( $order_id, 'telegramWasSent', 1 );
			$this->sendNewOrderToTelegram( $order_id );
		}

	}

	public function woocommerce_order_status_changed( $order_id, $status_transition_from, $status_transition_to, $that ) {
		$order    = wc_get_order( $order_id );
		$statuses = get_option( 'onftb_order_statuses' );
		if ( in_array( 'wc-' . $order->get_status(), $statuses ) ) {
			$this->sendNewOrderToTelegram( $order->data['id'] );
		}
	}

	public function addWooSettingSection( $settings ) {
		$settings[] = new OptionPanel();

		return $settings;
	}
}
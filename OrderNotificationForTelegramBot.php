<?php

namespace OrderNotificationForTelegramBot;

use OrderNotificationForTelegramBot\classes\Init;
use OrderNotificationForTelegramBot\classes\Methods;
use OrderNotificationForTelegramBot\classes\Requester;
use OrderNotificationForTelegramBot\classes\WooCommerceApi;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

require_once 'vendor/autoload.php';

/**
 * @package OrderNotificationForTelegramBot
 * @version 1.7.0.1
 */

/*
Plugin Name: Order Notification For Telegram Bot
Plugin URI: https://mohammadmalekirad.ir/OrderNotificationForTelegramBot
Description: اطلاع رسانی سفارشات از طریق ربات تلگرام
Author: Mohammad MalekiRad
Version: 1.7.0.1
Author URI: https://mohammadmalekirad.ir
Text Domain: OrderNotificationForTelegramBot
License URI: https://opensource.org/licenses/MIT
Code Name: OrderNotificationForTelegramBot
WC requires at least: 7.6
WC tested up to: 8.4.0
Requires at least: 6.0
Requires PHP: 7.3
*/

define( 'ONFTB_PLUGIN_PATH', plugin_dir_path( __FILE__ ) );
define( 'ONFTB_PLUGIN_URL', plugin_dir_url( __FILE__ ) );
define( 'ONFTB_PLUGIN_FILE', __FILE__ );
define( 'ONFTB_PLUGIN_BASE_NAME', plugin_basename( __FILE__ ) );
define( 'ONFTB_PLUGIN_ICON', plugins_url( "images/ic.png", __FILE__ ) );
define( 'ONFTB_PLUGIN_TG_BANNER', plugins_url( "assets/images/onftb.jpg", __FILE__ ) );

Init::getInstance();
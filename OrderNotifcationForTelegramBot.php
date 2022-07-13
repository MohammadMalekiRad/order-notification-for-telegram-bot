<?php

namespace OrderNotificationForTelegramBot;

use OrderNotificationForTelegramBot\classes\Init;
use OrderNotificationForTelegramBot\classes\PersianDate;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

require_once 'vendor/autoload.php';

/**
 * @package OrderNotificationForTelegramBot
 * @version 1.5.1
 */

/*
Plugin Name: Order Notification For Telegram Bot
Plugin URI: https://mohammadmalekirad.ir/OrderNotificationForTelegramBot
Description: اطلاع رسانی سفارشات از طریق ربات تلگرام
Author: Mohammad MalekiRad
Version: 1.5.1
Author URI: https://mohammadmalekirad.ir
Requires at least: 5.2
Requires PHP: 7.0
WC requires at least: 3.2
WC tested up to: 5.6
Text Domain: OrderNotificationForTelegramBot
License URI: https://opensource.org/licenses/MIT
Code Name: OrderNotificationForTelegramBot
*/

define( 'ONFTB_PLUGIN_PATH', plugin_dir_path( __FILE__ ) );
define( 'ONFTB_PLUGIN_URL', plugin_dir_url( __FILE__ ) );
define( 'ONFTB_PLUGIN_FILE', __FILE__ );
define( 'ONFTB_PLUGIN_ICON', plugins_url( "images/ic.png", __FILE__ ) );

Init::getInstance();
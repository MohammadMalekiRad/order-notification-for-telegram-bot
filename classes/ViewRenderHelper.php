<?php

namespace OrderNotificationForTelegramBot\classes;

class ViewRenderHelper {

	public static function renderView( $view_name, $data = [] ) {
		$view_name = self::checkViewFiles( $view_name );
		if ( $view_name ) {
			is_array( $data ) ? extract( $data ) : null;
			include "$view_name";
		}
	}

	private static function checkViewFiles( $file ) {
		$file = ONFTB_PLUGIN_PATH . "/views/$file" . ".php";
		if ( is_file( $file ) && is_readable( $file ) && file_exists( $file ) ) {
			return $file;
		}

		return false;
	}
}
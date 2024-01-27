<?php

namespace OrderNotificationForTelegramBot\classes;

use WC_Customer;
use WC_Order;
use WC_Order_Item_Product;

class WooCommerceApi {

	public $pattern;
	public $order;
	public $order_id;
	public $status_access;

	function __construct( $order_id ) {
		$this->pattern       = array();
		$this->status_access = array();
		$this->order         = new WC_Order( $order_id );
		$this->order_id      = $order_id;

		add_filter( 'onftb_filter_method_get_billing_state', [ $this, 'getBillingStateFilter' ] );
		add_filter( 'onftb_filter_method_get_status', [ $this, 'getStatusFilter' ] );
		add_filter( 'onftb_filter_method_get_total', [ $this, 'getTotalFilter' ] );
		add_filter( 'onftb_filter_method_get_date_created', [ $this, 'getDateFilter' ] );
		add_filter( 'onftb_filter_method_get_items', [ $this, 'getProductsFilter' ] );
	}

	function getBillingStateFilter( $arg ): string {
		$wc = new \WC_Countries();

		return ( $wc->get_states( $wc->get_base_country() )[ $arg ] ) ?? $arg;
	}

	function getStatusFilter( $arg ): string {
		return ( wc_get_order_status_name( $arg ) ) ?? $arg;
	}

	function getTotalFilter( $arg ): string {
		return ( wc_price( $arg ) ) ?? $arg;
	}

	function getDateFilter( $arg ): string {
		return $arg;
	}

	function getProductsFilter( $arg ): string {

		if ( ! is_array( $arg ) ) {
			return "";
		}

		if ( count( $arg ) < 1 ) {
			return "";
		}

		$product = chr( 10 );

		foreach ( $arg as $item ) {
			if ( ! $item instanceof WC_Order_Item_Product ) {
				return "";
			}
			$product .= $item->get_name() . ' × ' . $item->get_quantity() . ' عدد' . ' با قیمت ' . wc_price( $item->get_total() ) . chr( 10 );
		}

		return $product;
	}

	public function getBillingDetails( $str ) {
		$this->decodeShortcode( $str );

		return str_replace( array_keys( $this->pattern ), array_values( $this->pattern ), $str );
	}

	private function decodeShortcode( $str ) {
		$pattern = '/\{.+?}/m';
		preg_match_all( $pattern, $str, $matches );
		array_walk_recursive( $matches, function ( $item ) {
			$pattern = preg_replace( '/[{}]/', '', $item );

			if ( ! isset( ONFTB_PLUGIN_METHODS[ $pattern ] ) ) {
				return;
			}

			$method = hex2bin( base64_decode( 'Njc2NTc0NWY=' ) ) . ONFTB_PLUGIN_METHODS[ $pattern ];

			switch ( $method ):
				case is_callable( $method ):
					$this->pattern[ $item ] = $method();
					break;
				case method_exists( $this, $method ):
					$this->pattern[ $item ] = $this->$method();
					break;
				case method_exists( $this->order, $method ):
					$this->pattern[ $item ] = apply_filters( 'onftb_filter_method_' . $method, $this->order->$method() );
					break;
			endswitch;

		} );
	}

	function get_order_items_epo(): string {
		$items = $this->order->get_items();

		if ( ! is_array( $items ) < 1 ) {
			return "";
		}

		if ( count( $items ) < 1 ) {
			return "";
		}

		$product = chr( 10 );

		foreach ( $items as $item ) {
			if ( ! $item instanceof WC_Order_Item_Product ) {
				return "";
			}
			$product .= $item->get_name() . ' × ' . $item->get_quantity() . ' عدد' . ' با قیمت ' . wc_price( $item->get_total() ) . chr( 10 );

			if ( ! empty( $item->get_meta( '_tmcartepo_data' )[0]['name'] ) ) {
				$name    = $item->get_meta( '_tmcartepo_data' )[0]['name'];
				$value   = $item->get_meta( '_tmcartepo_data' )[0]['value'];
				$product .= PHP_EOL . $name . ": " . PHP_EOL . $value;
			}

		}

		return $product;
	}

	function get_epo(): string {
		$res   = PHP_EOL;
		$items = $this->order->get_items();
		foreach ( $items as $item ) {
			if ( ! $item instanceof WC_Order_Item_Product ) {
				return "";
			}
			if ( empty( $item->get_meta( '_tmcartepo_data' )[0]['name'] ) ) {
				return "";
			}
			$name  = $item->get_meta( '_tmcartepo_data' )[0]['name'];
			$value = $item->get_meta( '_tmcartepo_data' )[0]['value'];
			$res   .= $name . ": " . PHP_EOL . $value;
		}

		return $res;
	}

	function get_customer_order_count() {
		$count = "";
		try {
			$customer = new WC_Customer( $this->order->get_customer_id() );
			$count    = $customer->get_order_count();
		} catch ( \Exception $e ) {
		}

		return $count ?? "";
	}

	function get_date_created_per() {
		return ( PersianDate::jdate( 'd F Y, g:i a', strtotime( $this->order->get_date_created() ) ) ) ?? "";
	}
}
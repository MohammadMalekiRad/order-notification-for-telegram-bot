<?php

namespace OrderNotificationForTelegramBot\classes;

class Methods extends Singleton {

	function init() {

		define( 'ONFTB_PLUGIN_METHODS', apply_filters( 'onftb_filter_methods', [
			'site_name'                     => 'bloginfo',
			'order_id'                      => 'id',
			'order_items'                   => 'items',
			'order_items_epo'               => 'order_items_epo',
			'order_items_with_extra_fields' => 'order_items_with_extra_fields',
			'order_date'                    => 'date_created',
			'order_date_per'                => 'date_created_per',
			'order_status'                  => 'status',
			'order_total'                   => 'total',
			'order_note'                    => 'customer_note',
			'billing_first_name'            => 'billing_first_name',
			'billing_last_name'             => 'billing_last_name',
			'billing_address_part_1'        => 'billing_address_1',
			'billing_address_part_2'        => 'billing_address_2',
			'billing_address_city'          => 'billing_city',
			'billing_address_state'         => 'billing_state',
			'billing_address_postcode'      => 'billing_postcode',
			'billing_email'                 => 'billing_email',
			'billing_phone'                 => 'billing_phone',
			'billing_payment_method'        => 'payment_method_title',
			'billing_shipping_method'       => 'shipping_method',
			'customer_id'                   => 'customer_id',
			'customer_ip'                   => 'customer_ip_address',
			'customer_order_count'          => 'customer_order_count',
			'customer_user_agent'           => 'customer_user_agent',
			'extra_product_options'         => 'epo',
		] ) );
	}
}
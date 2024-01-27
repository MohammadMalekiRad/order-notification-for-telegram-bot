<?php

namespace OrderNotificationForTelegramBot\classes;

use WC_Settings_Page;

class OptionPanel extends WC_Settings_Page {

	public function __construct() {
		parent::__construct();
		$this->id    = 'onftb';
		$this->label = __( 'اطلاع رسانی ربات تلگرام' );
		add_action( 'woocommerce_settings_' . $this->id, array( $this, 'output' ) );
		add_action( 'woocommerce_settings_save_' . $this->id, array( $this, 'save' ) );

	}

	public function get_settings( $section = null ) {
		$settings =
			array(
				'section_title'                      => array(
					'title' => __( 'راهنما' ),
					'type'  => 'title',
					'desc'  => $this->renderHelpDescription(),
					'id'    => 'wc_settings_tab_onftb_title_1'
				),
				'section_title_tg'                   => array(
					'title' => __( 'تلگرام' ),
					'type'  => 'title',
					'desc'  => "",
					'id'    => 'wc_settings_tab_onftb_title_tg',
				),
				'token'                              => array(
					'title'    => __( 'توکن ربات تلگرام' ),
					'type'     => 'text',
					'id'       => 'onftb_setting_token',
					'desc'     => __( 'توکن ربات که شبیه همچین چیزیه 123456:ABC-DEF1234ghIkl-zyx57W2v1u123ew11 را وارد کنید' ),
					'desc_tip' => false
				),
				'chatid'                             => array(
					'title'    => __( 'آیدی چت یا گروه' ),
					'type'     => 'text',
					'id'       => 'onftb_setting_chatid',
					'desc_tip' => false,
					'desc'     => __( 'آیدی چت پی وی یا گروه که شبیه همچین چیزیه 431654987 را وارد کنید، برای اطلاعات بیشتر به ربات تلگرامی @UserAccInfoBot مراجعه نمایید' )
				),
				'tg_google_script'                   => array(
					'title'    => __( 'استفاده از گوگل اسکریپت بعنوان پراکسی' ),
					'type'     => 'url',
					'id'       => 'onftb_setting_tg_google_script',
					'desc_tip' => false,
					'desc'     => __( 'یه چیزی شبیه این https://script.google.com/***83f2e0f33d4dce3f331e013c***/exec هستش. برای اطلاعات بیشتر به کانال افزونه مراجعه نمایید.' )
				),
				'section_end_tg'                     => array(
					'type' => 'sectionend',
					'id'   => 'wc_settings_tab_onftb_end_section_tg'
				),
				'section_title_bale'                 => array(
					'title' => __( 'پیام رسان بله' ),
					'type'  => 'title',
					'desc'  => "",
					'id'    => 'wc_settings_tab_onftb_title_bale',
					'css'   => 'color:red;'
				),
				'bale_token'                         => array(
					'title'    => __( 'توکن بازوی بله' ),
					'type'     => 'text',
					'id'       => 'onftb_setting_bale_token',
					'desc'     => __( 'توکن ربات که شبیه همچین چیزیه 123456:ABC-DEF1234ghIkl-zyx57W2v1u123ew11 را وارد کنید' ),
					'desc_tip' => false
				),
				'bale_chatid'                        => array(
					'title'    => __( 'آیدی چت یا گروه' ),
					'type'     => 'text',
					'id'       => 'onftb_setting_bale_chatid',
					'desc_tip' => false,
					'desc'     => __( 'آیدی چت پی وی یا گروه که شبیه همچین چیزیه 431654987 را وارد کنید، برای اطلاعات بیشتر به بازوی بله @UserAccInfoBot مراجعه نمایید' )
				),
				'is_use_default_endpoint'            => array(
					'title'    => __( 'از پراکسی افزونه استفاده نشود' ),
					'type'     => 'checkbox',
					'id'       => 'is_use_default_endpoint',
					'desc_tip' => false,
					'desc'     => __( "اگر هاستتون خارجی هستش حتما تیک بزنید!" )
				),
				'section_end_bale'                     => array(
					'type' => 'sectionend',
					'id'   => 'wc_settings_tab_onftb_end_section_tg'
				),
				'section_title_settings'                 => array(
					'title' => __( 'تنظیمات افزونه' ),
					'type'  => 'title',
					'desc'  => "",
					'id'    => 'wc_settings_tab_onftb_title_settings',
					'css'   => 'color:red;'
				),
				'sending_after_order_status_changed' => array(
					'title'    => __( 'ارسال نوتیفیکشن با تغییر وضعیت' ),
					'type'     => 'checkbox',
					'id'       => 'onftb_send_after_order_status_changed',
					'desc_tip' => false,
					'desc'     => __( "براساس وضعیت های انتخابی هنگام ثبت سفارش نوتیفیکیشن ارسال می شود. در غیر اینصورت برای همه وضعیت ها نوتیفیکیشن ارسال می شود." )
				),
				'order_statuses'                     => array(
					'title'    => __( 'انتخاب وضعیت های سفارش' ),
					'type'     => 'multiselect',
					'id'       => 'onftb_order_statuses',
					'options'  => wc_get_order_statuses(),
					'class'    => 'wc-enhanced-select',
					'desc_tip' => false,
					'css'      => 'width:45%;',
					'desc'     => __( 'وضعیت هایی که برایشان نوتیفیکیشن ارسال می شود' )
				),
				'message_template'                   => array(
					'title'             => __( 'نمونه پیام ارسالی' ),
					'type'              => 'textarea',
					'id'                => 'onftb_setting_template',
					'class'             => 'code',
					'css'               => 'max-width:550px;width:100%;',
					'default'           =>
						'نام وبسایت: {site_name}' . chr( 10 ) .
						'شماره سفارش: {order_id}' . chr( 10 ) .
						'زمان ثبت سفارش: {order_date}' . chr( 10 ) .
						'زمان ثبت سفارش (شمسی): {order_date_per}' . chr( 10 ) .
						'وضعیت سفارش: {order_status}' . chr( 10 ) .
						'آیتم های سفارش: {order_items}' . chr( 10 ) .
						'مجموع مبلغ سفارش: {order_total}' . chr( 10 ) .
						'نام: {billing_first_name}' . chr( 10 ) .
						'نام خانوادگی: {billing_last_name}' . chr( 10 ) .
						'بخش اول آدرس: {billing_address_part_1}' . chr( 10 ) .
						'بخش دوم آدرس: {billing_address_part_2}' . chr( 10 ) .
						'شهر: {billing_address_city}' . chr( 10 ) .
						'استان: {billing_address_state}' . chr( 10 ) .
						'کدپستی: {billing_address_postcode}' . chr( 10 ) .
						'ایمیل: {billing_email}' . chr( 10 ) .
						'شماره تلفن: {billing_phone}' . chr( 10 ) .
						'روش پرداخت: {billing_payment_method}' . chr( 10 ) .
						'روش ارسال: {billing_shipping_method}' . chr( 10 ) .
						'آیپی پرداخت کننده: {customer_ip}' . chr( 10 ) .
						'آیدی کاربری مشتری: {customer_id}' . chr( 10 ) .
						'تعداد سفارش های مشتری: {customer_order_count}' . chr( 10 ) .
						'agent پرداخت کننده: {customer_user_agent}' . chr( 10 ),
					'custom_attributes' => [ 'rows' => 25 ],
				),
				'section_end'                        => array(
					'type' => 'sectionend',
					'id'   => 'wc_settings_tab_onftb_end_section_2'
				),
			);

		return apply_filters( 'wc_settings_tab_onftb_settings', $settings, $section );

	}

	public function renderHelpDescription() {
		$token_help  = wp_kses( __( "تنها با ارسال پیام به ربات <a href='https://t.me/botfather' target='_blank'>@BotFather</a> و ارسال متن <code>/start</code>, سپس <code>/newbot</code> ومشخصات ربات که شامل نام و آیدی می باشد، توکن ربات شما برای تان ارسال می گردد.", 'onftb' ), array( 'a' => [ 'href' => 'https://t.me/BotFather', 'target' => '_blank' ], 'code' => [] ) );
		$chatid_help = wp_kses( __( "برای دریافت آیدی تان نیز از ربات <a href='https://t.me/UserAccInfoBot' target='_blank'>@UserAccInfoBot</a> استفاده کنید. راهنمایی های بیشتر و نحوه بدست آوردن آیدی چت یا گروه در این ربات وجود دارد.", "onftb" ), [ 'a' => [ 'href' => 'https://t.me/UserAccInfoBot', 'target' => '_blank' ], 'code' => [] ] );
		$more_info   = wp_kses( __( "کانال تلگرامی افزونه را دنبال کنید:  <a href='https://t.me/ONFTB' target='_blank'>@ONFTB</a>", "onftb" ), [ 'a' => [ 'href' => 'https://tlgrm.in/ONFTB', 'target' => '_blank' ], 'code' => [] ] );

		return $token_help . chr( 10 ) . $chatid_help . chr( 10 ) . $more_info;
	}

	public function renderAllowTagsDescription() {
		?>
        <style>
            .row {
                display: flex;
                flex-wrap: wrap;
                justify-content: space-between;
            }

            textarea {
                background-color: #141414;
                color: #F8F8F8;
                width: 100%;
                font: 12px/normal 'Monaco', 'Menlo', 'Ubuntu Mono', 'Consolas', 'source-code-pro', monospace;
            }
        </style>
        <div class="row text-right">
            <table class="form-table">
                <tbody>
                <tr>
                    <th><?php echo __( 'ارسال پیام تستی' ) ?></th>
                    <td>
                        <button id="onftb_send_test_message" type="button" class="button-primary"><?= __( 'ارسال پیام' ) ?></button>
                    </td>
                </tr>
                <tr>
                    <th><?php echo __( 'تگ های مجاز' ) ?></th>
                    <td>
                        <div class="text-right" style="text-align: right;">
                            <pre>&ltb&gtbold&lt/b&gt</pre>
                            &#10;<pre>&ltstrong&gtbold&lt/strong&gt</pre>
                            &#10;<pre>&lti&gtitalic&lt/i&gt</pre>
                            &#10;<pre>&ltem&gtitalic&lt/em&gt</pre>
                            &#10;<pre>&ltu&gtunderline&lt/u&gt</pre>
                            &#10;<pre>&ltins&gtunderline&lt/ins&gt</pre>
                            &#10;<pre>&lts&gtstrikethrough&lt/s&gt</pre>
                            &#10;<pre>&ltstrike&gtstrikethrough&lt/strike&gt</pre>
                            &#10;<pre>&ltdel&gtstrikethrough&lt/del&gt</pre>
                            &#10;<pre>&lta href="http://www.domain.com/">inline URL&lt/a&gt</pre>
                            &#10;<pre>&ltcode&gtcode&lt/code&gt</pre>
                            &#10;<pre>&ltpre&gtcode block&lt/pre&gt</pre>

                        </div>
                    </td>
                </tr>
                <tr>
                    <th><?php echo __( 'شورت کد های قابل استفاده' ) ?></th>
                    <td>
                        <div>
                            <p>نام وبسایت: <code>{site_name}</code></p>
                            <p>شماره سفارش: <code>{order_id}</code></p>
                            <p>زمان ثبت سفارش: <code>{order_date}</code></p>
                            <p>زمان ثبت سفارش (شمسی): <code>{order_date_per}</code></p>
                            <p>وضعیت سفارش: <code>{order_status}</code></p>
                            <p>آیتم های سفارش: <code>{order_items}</code></p>
                            <p>مجموع مبلغ سفارش: <code>{order_total}</code></p>
                            <p>نام: <code>{billing_first_name}</code></p>
                            <p>نام خانوادگی: <code>{billing_last_name}</code></p>
                            <p>بخش اول آدرس: <code>{billing_address_part_1}</code></p>
                            <p>بخش دوم آدرس: <code>{billing_address_part_2}</code></p>
                            <p>شهر: <code>{billing_address_city}</code></p>
                            <p>استان: <code>{billing_address_state}</code></p>
                            <p>کدپستی: <code>{billing_address_postcode}</code></p>
                            <p>ایمیل: <code>{billing_email}</code></p>
                            <p>شماره تلفن: <code>{billing_phone}</code></p>
                            <p>روش پرداخت: <code>{billing_payment_method}</code></p>
                            <p>روش ارسال: <code>{billing_shipping_method}</code></p>
                            <p>نام متد پرداخت: <code>{payment_method_title}</code></p>
                            <p>آیپی پرداخت کننده: <code>{customer_ip_address}</code></p>
                            <p>آیدی کاربری مشتری: <code>{customer_id}</code></p>
                            <p>تعداد سفارش های مشتری: <code>{customer_order_count}</code></p>
                            <p>agent پرداخت کننده: <code>{customer_user_agent}</code></p>
                            <p>تمامی متا دیتاهای محصول: <code>{product_meta}</code></p>
                            <p>یک متا دیتای خاص براساس کلید آن: <code>{product_meta_[meta_key]}</code></p>
                            <p>فیلد های اضافی: <code>{extra_product_options}</code></p>
                            <p>محصولات همراه فیلدهای اضافی: <code>{order_items_epo}</code></p>
                        </div>
                    </td>
                </tr>
                </tbody>
            </table>
        </div>
		<?php
	}

	public function output() {
		echo '<div id="nktgnfw-header">
			<a href="https://t.me/ONFTB" target="_blank">
			<img src="' . ONFTB_PLUGIN_TG_BANNER . '" alt="Order Notification For Telegram Bot"></a>
			<br>
		    </div>';
		$settings = $this->get_settings();
		\WC_Admin_Settings::output_fields( $settings );
		$this->renderAllowTagsDescription();
	}

	public function save() {
		$settings = $this->get_settings();
		\WC_Admin_Settings::save_fields( $settings );
	}
}
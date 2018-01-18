<?php

if ( ! defined( 'ABSPATH' ) ) {
  exit; // Exit if accessed directly
}


function wpc_yandex_delivery_init() {

if ( ! class_exists( 'WC_Yandex_Delivery_Method' ) ) {
  class WC_Yandex_Delivery_Method extends WC_Shipping_Method {
    /**
     * Constructor for your shipping class
     *
     * @access public
     * @return void
     */
    public function __construct() {
      $this->id                 = 'wpc_yandex_delivery';
      $this->method_title       = 'Яндекс Доставка';
      $this->method_description = __( 'Поддержка системы Яндекс Доставка' ); //

      $this->init();

      $this->enabled = isset( $this->settings['enabled'] ) ? $this->settings['enabled'] : 'yes';
      $this->title = isset( $this->settings['title'] ) ? $this->settings['title'] : "Яндекс Доставка";

    }

    /**
     * Init your settings
     *
     * @access public
     * @return void
     */
    function init() {
      // Load the settings API
      $this->init_form_fields(); // This is part of the settings API. Override the method to add your own settings
      $this->init_settings(); // This is part of the settings API. Loads settings you previously init.

      $this->enabled	= $this->get_option( 'enabled' );
      $this->title 		= $this->get_option( 'title' );
      // Save settings in admin if you have any defined
      add_action( 'woocommerce_update_options_shipping_' . $this->id, array( $this, 'process_admin_options' ) );

    }

    /**
     * Initialise Gateway Settings Form Fields.
     */
    public function init_form_fields() {
      $this->form_fields = array(
        'enabled' => array(
          'title' 		=> __( 'Enable/Disable', 'woocommerce' ),
          'type' 			=> 'checkbox',
          'label' 		=> "",
          'default' 		=> 'no',
        ),
        'title' => array(
          'title' 		=> __( 'Method title', 'woocommerce' ),
          'type' 			=> 'text',
          'description' 	=> __( 'This controls the title which the user sees during checkout.', 'woocommerce' ),
          'default'		=> "Калькулятор доставки",
          'desc_tip'		=> true,
        ),
      );
    }

    /**
     * calculate_shipping function.
     *
     * @access public
     * @param mixed $package
     * @return void
     */
    public function calculate_shipping( $package ) {

      if( ! empty($_REQUEST["post_data"])){
        $post_data = wp_parse_args($_REQUEST["post_data"]);

        if( ! empty($post_data["yd_cost"])){
          $cost = (int)$post_data["yd_cost"];
          WC()->session->set( 'yd_cost', $cost );
        }
      }
      // do_action('logger_u7', ['test6', $package]); //@TODO: remove logger

       $cost = WC()->session->get('yd_cost');
       if(empty($cost)){
         $cost = 0;
       }

       $rate = array(
        'id' => $this->id,
        'label' => $this->title, //@TODO: add name a variant
        'cost' => $cost
      );

      $this->add_rate( $rate );
    }
  }
}

}

add_action( 'woocommerce_shipping_init', 'wpc_yandex_delivery_init' );

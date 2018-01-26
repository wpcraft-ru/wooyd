<?php
/*
Plugin Name: WooYD
Description: Интеграция Яндекс Доставка и WooCommerce
Plugin URI: https://wpcraft.ru/product/wooyd/
Author: WPCraft
Author URI: https://wpcraft.ru/
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html
Version: 0.7
*/

require_once 'inc/class-wc-shipping-yandex-delivery.php';
require_once 'inc/class-settings.php';
require_once 'inc/cart-widget.php';
require_once 'inc/class-widgets.php';

/*
* Add class for init
*/
function wpc_add_yandex_delivery_shipping_method( $methods ) {
  $methods['yandex_delivery_wpc'] = 'WC_Yandex_Delivery_Method';
  return $methods;
}
add_filter( 'woocommerce_shipping_methods', 'wpc_add_yandex_delivery_shipping_method' );

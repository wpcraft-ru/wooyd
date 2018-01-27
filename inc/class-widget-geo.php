<?php

/**
 * Geo widget Yandex Delivery https://yandex.ru/support/delivery/widgets.html
 * Shortcode [yd_widget_geo]
 */
class WooYD_Widget_Geo
{

  function __construct()
  {
    add_shortcode('yd_widget_geo', array($this, 'shortcode_display'));
    add_action( 'admin_init', array($this, 'settings_init'), $priority = 10, $accepted_args = 1 );
  }

  function shortcode_display()
  {
    $code = get_option('wooyd_widget_geo');
    if(empty($code)){
      return;
    } else {
      return $code;
    }
  }

  function settings_init()
  {
    register_setting('yandex_delivery_wpc', 'wooyd_widget_geo');
    add_settings_field(
      $id = 'wooyd_widget_geo',
      $title = 'Гео-виджет',
      $callback = [$this, 'display_wooyd_widget_geo'],
      $page = 'yandex_delivery_wpc',
      $section = 'wooyd_general'
    );
  }

  function display_wooyd_widget_geo(){
    ?>
    <p>Этот виджет выводится через шорткод [yd_widget_geo]. Например можно его вставить в какой то виджет WordPress. Можно на странице или интегрировать в код темы.</p>
    <?php
    $name = 'wooyd_widget_geo';
    printf('<textarea rows="10" cols="95" name="%s">%s</textarea>',$name, get_option($name));
  }
}
new WooYD_Widget_Geo;

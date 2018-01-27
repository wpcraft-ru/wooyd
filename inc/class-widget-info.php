<?php

/**
 * Info widget YD https://yandex.ru/support/delivery/widgets.html
 * Shortcode [yd_widget_info]
 */
class WooYD_Widget_Info
{

  function __construct()
  {
    add_shortcode('yd_widget_info', array($this, 'shortcode_dusplay'));
    add_action( 'admin_init', array($this, 'settings_init'), $priority = 10, $accepted_args = 1 );
  }

  function shortcode_dusplay()
  {
    $code = get_option('wooyd_widget_info');
    if(empty($code)){
      return;
    } else {
      return $code;
    }
  }

  function settings_init()
  {
    register_setting('yandex_delivery_wpc', 'wooyd_widget_info');
    add_settings_field(
      $id = 'wooyd_widget_info',
      $title = 'Инфо-виджет',
      $callback = [$this, 'display_wooyd_widget_info'],
      $page = 'yandex_delivery_wpc',
      $section = 'wooyd_general'
    );
  }

  function display_wooyd_widget_info()
  {
    ?>
    <p>Этот виджет выводится через шорткод [yd_widget_info]. Например можно вставить этот шорткод на странице Доставка, для того чтобы Клиенты могли понимать примерные варианты и цены доставки.</p>
    <?php
    $name = 'wooyd_widget_info';
    printf('<textarea rows="10" cols="95" name="%s">%s</textarea>',$name, get_option($name));
  }

}
new WooYD_Widget_Info;

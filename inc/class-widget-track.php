<?php

/**
 *
 */
class WooYD_Widget_Tracking
{
  function __construct()
  {
    add_action('woocommerce_account_content', array($this, 'tracking_widget_display'));

    add_action( 'admin_init', array($this, 'settings_init'), $priority = 10, $accepted_args = 1 );

  }

  function tracking_widget_display(){
    $code = get_option('wooid_script_track_widget');
    if($code){
      echo $code;
    }
  }

  function settings_init(){
    register_setting('yandex_delivery_wpc', 'wooyd_script_track_widget');
    add_settings_field(
      $id = 'wooyd_script_track_widget', //@TODO: fix for wooyd
      $title = 'Трекинг-виджет',
      $callback = [$this, 'display_wooyd_script_track_widget'],
      $page = 'yandex_delivery_wpc',
      $section = 'wooyd_general'
    );
  }

  function display_wooyd_script_track_widget()
  {
    ?>
    <p>Этот виджет выводится на странице Мой Аккаунт, если заполнен.</p>
    <?php
    $name = 'wooyd_script_track_widget';
    printf('<textarea rows="10" cols="95" name="%s">%s</textarea>',$name, get_option($name));
  }

}
new WooYD_Widget_Tracking;

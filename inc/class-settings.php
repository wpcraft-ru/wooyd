<?php
if ( ! defined( 'ABSPATH' ) ) {
  exit; // Exit if accessed directly
}

/**
 * Settings API
 */
class WooYD_Settings
{

  function __construct()
  {
    add_action('admin_menu', function(){
      add_options_page(
        $page_title = 'Яндекс Доставка',
        $menu_title = 'Яндекс Доставка',
        $capability = 'manage_options',
        $menu_slug = 'yandex_delivery_wpc',
        $function = array($this, 'settings_display')
      );
    });

    add_action( 'admin_init', array($this, 'settings_general'), $priority = 10, $accepted_args = 1 );

  }


  function settings_general(){
    add_settings_section(
      'wooyd_general',
      'Основные настройки',
      $function = array($this, 'display_section_general'),
      'yandex_delivery_wpc'
    );

    register_setting('yandex_delivery_wpc', 'wooid_script_cart_widget');
    add_settings_field(
      $id = 'wooid_script_cart_widget',
      $title = 'Корзинный виджет',
      $callback = [$this, 'display_wooid_script_cart_widget'],
      $page = 'yandex_delivery_wpc',
      $section = 'wooyd_general'
    );

    register_setting('yandex_delivery_wpc', 'wooid_script_track_widget');
    add_settings_field(
      $id = 'wooid_script_track_widget', //@TODO: fix for wooyd
      $title = 'Трекинг-виджет',
      $callback = [$this, 'display_wooid_script_track_widget'],
      $page = 'yandex_delivery_wpc',
      $section = 'wooyd_general'
    );

    register_setting('yandex_delivery_wpc', 'wooyd_widget_cart');
    add_settings_field(
      $id = 'wooyd_widget_cart',
      $title = 'Карточный виджет',
      $callback = [$this, 'display_wooyd_widget_cart'],
      $page = 'yandex_delivery_wpc',
      $section = 'wooyd_general'
    );

    register_setting('yandex_delivery_wpc', 'wooyd_widget_info');
    add_settings_field(
      $id = 'wooyd_widget_info',
      $title = 'Инфо-виджет',
      $callback = [$this, 'display_wooyd_widget_info'],
      $page = 'yandex_delivery_wpc',
      $section = 'wooyd_general'
    );

    register_setting('yandex_delivery_wpc', 'wooyd_widget_geo');
    add_settings_field(
      $id = 'wooyd_widget_geo',
      $title = 'Гео-виджет',
      $callback = [$this, 'display_wooyd_widget_geo'],
      $page = 'yandex_delivery_wpc',
      $section = 'wooyd_general'
    );

  }

  //Display instruction for general section
  function display_section_general(){
    ?>
    <p>Настройки нужно скопировать из панели управления Яндекс Доставка: <a href="https://delivery.yandex.ru/integration/index" target="_blank">https://delivery.yandex.ru/integration/index</a></p>
    <?php
  }

  function display_wooid_script_cart_widget(){
    $name = 'wooid_script_cart_widget';
    printf('<textarea rows="3" cols="95" name="%s">%s</textarea>',$name, get_option($name));
  }
  function display_wooid_script_track_widget(){
    $name = 'wooid_script_track_widget';
    printf('<textarea rows="10" cols="95" name="%s">%s</textarea>',$name, get_option($name));
  }
  function display_wooyd_widget_cart(){
    $name = 'wooyd_widget_cart';
    printf('<textarea rows="10" cols="95" name="%s">%s</textarea>',$name, get_option($name));
  }
  function display_wooyd_widget_info(){
    $name = 'wooyd_widget_info';
    printf('<textarea rows="10" cols="95" name="%s">%s</textarea>',$name, get_option($name));
  }
  function display_wooyd_widget_geo(){
    $name = 'wooyd_widget_geo';
    printf('<textarea rows="10" cols="95" name="%s">%s</textarea>',$name, get_option($name));
  }

  function settings_display(){
    // var_dump($option); exit;
    ?>
    <h1>Настройки интеграции для "Яндекс Доставка"</h1>
    <form method="POST" action="options.php">
      <?php
        settings_fields( 'yandex_delivery_wpc' );
        do_settings_sections( 'yandex_delivery_wpc' );
        submit_button();
      ?>
    </form>
    <div class="wrapper_instruction">
      <p><a href="">Расширенная версия</a></p>
      <p><a href="">Техническая поддержка</a></p>
    </div>
    <?php
  }
}

new WooYD_Settings;

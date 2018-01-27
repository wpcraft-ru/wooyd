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


  function settings_display()
  {
    ?>
    <h1>Настройки интеграции для "Яндекс Доставка"</h1>
    <p>Инструкция по виджетам: <a href="https://yandex.ru/support/delivery/widgets.html" target="_blank">https://yandex.ru/support/delivery/widgets.html</a></p>
    <form method="POST" action="options.php">
      <?php
        settings_fields( 'yandex_delivery_wpc' );
        do_settings_sections( 'yandex_delivery_wpc' );
        submit_button();
      ?>
    </form>
    <div class="wrapper_instruction">
      <p><a href="https://wpcraft.ru/product/wooyd-expert/" target="_blank">Расширенная версия с технической поддержкой</a></p>
      <p><a href="https://wpcraft.ru/contacts/" target="_blank">Техническая поддержка</a></p>
    </div>
    <?php
  }
}

new WooYD_Settings;

<?php
/**
 * Карточный виджет Яндекс Доставка https://yandex.ru/support/delivery/widgets.html
 */

class WooYD_Wodget_Product
{

  function __construct()
  {
    add_action( 'admin_init', array($this, 'settings_init'), $priority = 10, $accepted_args = 1 );

    add_filter( 'woocommerce_product_tabs', array($this, 'add_product_tab') );
  }



  function add_product_tab( $tabs ) {
  // Adds the new tab

    $code = get_option('wooyd_widget_card');
    if(empty($code)){
      return $tabs;
    } else {
      $tabs['wooyd_tab_delivery'] = array(
          'title'     => 'Доставка',
          'priority'  => 50,
          'callback'  => array($this, 'tab_content')
      );
      return $tabs;
    }
  }

  function tab_content()
  {
    $code = get_option('wooyd_widget_card');
    echo $code;
  }

  function settings_init()
  {
    register_setting('yandex_delivery_wpc', 'wooyd_widget_card');
    add_settings_field(
      $id = 'wooyd_widget_card',
      $title = 'Карточный виджет',
      $callback = [$this, 'display_wooyd_widget_card'],
      $page = 'yandex_delivery_wpc',
      $section = 'wooyd_general'
    );
  }

  function display_wooyd_widget_card(){
    ?>
    <p>Этот виджет выводится на странице продукта в отдельной вкладке если заполнен</p>
    <?php
    $name = 'wooyd_widget_card';
    printf('<textarea rows="10" cols="95" name="%s">%s</textarea>',$name, get_option($name));
  }

}
new WooYD_Wodget_Product;

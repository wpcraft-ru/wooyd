<?php

/**
 *
 */
class WooYD_Widgets
{
  function __construct()
  {
    add_action('woocommerce_account_content', array($this, 'tracking_widget_display'));
  }

  function tracking_widget_display(){
    $code = get_option('wooid_script_track_widget');
    if($code){
      echo $code;
    }
  }

}
new WooYD_Widgets;

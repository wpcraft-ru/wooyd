<?php
if ( ! defined( 'ABSPATH' ) ) {
  exit; // Exit if accessed directly
}

class WooYD_FrontEnd
{

  function __construct()
  {
    add_action('woocommerce_checkout_process', array($this, 'checkout_control_errors'));

    add_action('wp_footer', array($this, 'print_js'), 100);

  }



  //Get ship data for Yandex Widget
  function get_ship_data(){
    $ship_data = WC()->cart->get_shipping_packages();

    if( ! empty($ship_data[0]["contents"]) ){
      $data_ship = array();
      foreach($ship_data[0]["contents"] as $item_ship){
        $data_ship[] = array(
          (int)$item_ship["data"]->length,
          (int)$item_ship["data"]->width,
          (int)$item_ship["data"]->height,
          (int)$item_ship["quantity"],
        );
      }
    } else {
      $data_ship = array();
    }

    return $data_ship;
  }

  /**
  * Выводим JS для работы виджета Яндекс Доставка
  */
  function print_js()
  {
    if( ! is_checkout()){
      return;
    }

    $cart_data = array(
      'quantity' => WC()->cart->get_cart_contents_count(),
      'weight' => WC()->cart->get_cart_contents_weight(),
      'cost' => WC()->cart->cart_contents_total,
    );

    $data_ship = $this->get_ship_data();

    // Print script for Cart Widget Yandex Delivery
    $script = get_option('wooid_script_cart_widget');
    echo $script;

    ?>

    <!-- Создаем условный объект с данными о содержимом корзины (для примера) -->
    <script type="text/javascript">
      window.cart = {
        quantity: <?php echo $cart_data['quantity'] ?>, //общее количество товаров
        weight: <?php echo $cart_data['weight'] ?>,
        cost: <?php echo $cart_data['cost'] ?>
      }
    </script>

    <!-- Инициализация виджета -->
    <script type="text/javascript">

      ydwidget.ready(function(){
        ydwidget.initCartWidget({
          //получить указанный пользователем город
          'getCity': function () {
            var city = yd$('#billing_city').val();
            if (city) {
              return {value: city};
            } else {
              return false;
            }
          },

          //id элемента-контейнера
          'el': 'ydwidget',

          //общее количество товаров в корзине
          'totalItemsQuantity': function () { return cart.quantity },

          //общий вес товаров в корзине
          'weight': function () { return cart.weight },

          //общая стоимость товаров в корзине
          'cost': function () { return cart.cost },

          //габариты и количество по каждому товару в корзине
          'itemsDimensions': function () {return [
            <?php echo json_encode($data_ship); ?>
            // [10,15,10,2],
            // [20,15,5,1]
          ]},

          //объявленная ценность заказа. Влияет на расчет стоимости в предлагаемых вариантах доставки, для записи поля в заказ данное поле так же нужно передать в объекте order (поле order_assessed_value)
          'assessed_value': cart.cost,
          //Способы доставки. Влияют на предлагаемые в виджете варианты способов доставки.
          onlyDeliveryTypes: function(){return ['todoor','pickup','post'];},
          //обработка автоматически определенного города
          'setCity': function (city, region) { yd$('#billing_city').val(region ? city + ', ' + region : city) },
          //обработка смены варианта доставки
          'onDeliveryChange': function (delivery) {
            //если выбран вариант доставки, выводим его описание и закрываем виджет, иначе произошел сброс варианта,
            //очищаем описание
            if (delivery) {

              var yd_params = [{
                'id': ydwidget.cartWidget.selectedDelivery.delivery.id,
                'delivery_service_id': ydwidget.cartWidget.selectedDelivery.delivery.delivery_service_id,
                'name': ydwidget.cartWidget.selectedDelivery.delivery.name,
                'unique_name': ydwidget.cartWidget.selectedDelivery.delivery.unique_name,
                'description': ydwidget.cartWidget.view.helper.getDeliveryDescription(delivery),
                'cost': ydwidget.cartWidget.selectedDelivery.costWithRules,
              }];

              // console.log(yd_params);

              yd$('#yd_cost').val(ydwidget.cartWidget.selectedDelivery.costWithRules); //@TODO: do right
              yd$('#yd_params').val(JSON.stringify(yd_params)); //@TODO: do right


              if (ydwidget.cartWidget.selectedDelivery.type == "POST") {
                yd$('#billing_address_1').val(ydwidget.cartWidget.getAddress().street);
                yd$('#billing_address_2').val(ydwidget.cartWidget.getAddress().house);
                ydwidget.cartWidget.close();
              } else {
                // yd$(document).trigger('ydmyevent');
                ydwidget.cartWidget.close();
              }

              jQuery( 'form.checkout' ).trigger( 'update' );

            }
            // else {
            //   yd$('#delivery_description').text('');
            // }
          },
          //завершение загрузки корзинного виджета
          'onLoad': function () {
            //при клике на радиокнопку, если это не радиокнопка "Яндекс.Доставка", сбрасываем выбранную доставку
            //в виджете
            yd$(document).on('click', 'input:radio[name="delivery"]', function () {
              if (yd$(this).not('#yd_delivery')) {
                ydwidget.cartWidget.setDeliveryVariant(null);
              }
            });

          },

          //снятие выбора с варианта доставки "Яндекс.Доставка" (настроенного в CMS)
          'unSelectYdVariant': function () { yd$('#yd_delivery').prop('checked', false) },

          //автодополнение
          'autocomplete': ['billing_city', 'street', 'index'],
          'cityEl': '#billing_city',
          'streetEl': '#billing_address_1',
          'houseEl': '#billing_address_2',
          'indexEl': '#billing_postcode',

          //id элемента для вывода ошибок валидации. Вместо него можно указать параметр onValidationEnd, для кастомизации
          //вывода ошибок
          'errorsEl': 'yd_errors',
        })
      })
    </script>
    <?php
  }

  /**
  * Checking the data for Yandex delivery and, possibly, a break
  */
  function checkout_control_errors()
  {
    $checkout_fields = WC()->session->get( 'chosen_shipping_methods' );
    if(isset($checkout_fields[0]) and $checkout_fields[0] == 'wpc_yandex_delivery'){

      $params = WC()->session->get('yd_params');

      do_action('logger_u7', ['t1', $params]);

      if(empty($params["unique_name"])){
        wc_add_notice( __( 'Пожалуйста, выберите вариант доставки' ), 'error' );
      }
    }
  }
}
new WooYD_FrontEnd;



/**
* Добавляем Ссылку и Контейнер для выбора и вывода способов доставки Яндекс
*/
function display_btn_select_ship($method){
  if('wpc_yandex_delivery' == $method->id){
    echo '<input type="hidden" name="yd_cost" id="yd_cost"/>';
    echo '<input type="hidden" name="yd_params" id="yd_params"/>';
    printf('<div><a href="%s" data-ydwidget-open>Выбрать варианты</a></div>', '#yd-select-variants');
    // echo '<div><small id="delivery_description"></small></div>';
  }
}
add_action('woocommerce_after_shipping_rate', 'display_btn_select_ship');

/**
* Dysplay containers for Widget Yandex Delivery
*/
function wooid_display_widget(){
  ?>

  <!-- Элемент-контейнер виджета. Класс yd-widget-modal обеспечивает отображение виджета в модальном окне -->
  <div id="ydwidget" class="yd-widget-modal"></div>

  <!-- элемент для отображения ошибок валидации -->
  <div id="yd_errors"></div>

  <?php
}
add_action('woocommerce_after_cart', 'wooid_display_widget');
add_action('woocommerce_after_checkout_form', 'wooid_display_widget');

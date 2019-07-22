<?php

  class Woo_Payments_By_Shipping {

    //contructor
    function __construct() {
      add_action('plugins_loaded', array($this, 'woo_payments_by_shipping_init'));
      add_filter('woocommerce_available_payment_gateways', array($this, 'woo_payments_by_shipping_unset_payments'));
    }
      
    //init plugin 
    public function woo_payments_by_shipping_init() {
      if (is_admin()) {
        $shipping_methods = WC()->shipping->get_shipping_methods();
		WC()->payment_gateways->payment_gateways();    
		
        foreach($shipping_methods as $shipping_method) 
        {
          add_filter( 'woocommerce_shipping_instance_form_fields_' . $shipping_method->id, array($this, 'woo_payments_by_shipping_add_settings'));
        }

        add_action('admin_enqueue_scripts', array($this, 'woo_payments_by_shipping_enqueue_scripts'));
      }
    }

    //add setting to each shipping method 
    public function woo_payments_by_shipping_add_settings($settings) {
      $options_array = array();

      $payment_methods = WC()->payment_gateways->payment_gateways();
      foreach($payment_methods as $payment_method)
      {
        if ( isset( $payment_method->enabled ) && 'yes' === $payment_method->enabled ) {
          $options_array[$payment_method->id] = $payment_method->title;
        }
      }

      $payment_settings['woo-payments-by-shipping'] = array(
        'title'     => __('Available payments', 'woo-payments-by-shipping'),
        'desc_tip'  => __('Select available payments for this shipping method', 'woo-payments-by-shipping'),
        'class'     => 'agano',
        'type'      => 'multiselect',
        'options'   => $options_array
      );

      return array_merge($settings, $payment_settings);
    }

    //enqueue admin scripts
    public function woo_payments_by_shipping_enqueue_scripts() {
      wp_enqueue_script('woo_payments_by_shipping_script', plugin_dir_url( __FILE__ ) . 'woo-payments-by-shipping.js' );
    }

    //unset payments on checkout page
    public function woo_payments_by_shipping_unset_payments($gateways) {
      if (!is_admin())  {
        $chosen_shipping_methods = WC()->session->get('chosen_shipping_methods');
        $chosen_shipping_method = $chosen_shipping_methods[0];

        $pos = strrpos($chosen_shipping_method, ":");
        if ($pos > 0) {  
          $method_id = intval(substr($chosen_shipping_method, $pos+1));

          //get details of instance
          $shipping_method2 = WC_Shipping_Zones::get_shipping_method( $method_id );
          $options = $shipping_method2->get_instance_option("woo-payments-by-shipping");

          //unset
          foreach($gateways as $gateway)
          {
            if (!in_array($gateway->id, $options)) {
              unset($gateways[$gateway->id]);
            }
          }
        }
      }

      return $gateways;
    } 
  }

?>

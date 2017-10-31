<?php
/*
  Plugin Name: Woo Payments By Shipping
  Plugin URI: https://github.com/piotr-urbanowicz/woo-payments-by-shipping
  Description: Lets you select payments for particaluar shipping methods in WooCommerce
  Version: 1.0.0
  Author: Piotr Urbanowicz
  Author URI: https://github.com/piotr-urbanowicz
  License: GPL2
  License URI: https://www.gnu.org/licenses/gpl-2.0.html
*/

  //exit if accessed directly
  if (!defined('ABSPATH')) { 
    exit;
  }

  //check if woocommerce is active and initialize plugin class
  if (in_array('woocommerce/woocommerce.php', apply_filters('active_plugins', get_option('active_plugins')))) {
    require_once plugin_dir_path( __FILE__ ) . 'includes/class-woo-payments-by-shipping.php';
  
    new Woo_Payments_By_Shipping();
  }

?>

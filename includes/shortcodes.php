<?php
/**
 * WP_ADVERTISING_MANAGEMENT
 *
 * Questa classe gestisce i shortcodes
 *
 *
 * @package     advertising
 * @subpackage  Classes/API
 * @copyright   Copyright (c) 2018, Marcon Simone
 * @license     http://opensource.org/licenses/gpl-2.0.php GNU Public License
 * @since       1.0
 */
// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

//shortcode pagina purchase
add_shortcode( 'purchase_page', 'adv_advertising_list' );
function adv_advertising_list(){
    ob_start();

    advertising_management_purchase();

    return ob_get_clean();
 }

 //shortcode pagina custom adv
 add_shortcode( 'custom_adv_page', 'custom_adv' );
 function custom_adv(){
     ob_start();
 
     custom_adv_content();
 
     return ob_get_clean();
  } 

  //funzione pagina registrazione /acquisto
add_shortcode( 'registration_wp_advertising', 'registration_plugin_advertising' );
function registration_plugin_advertising() {
   ob_start();

   advertising_management_register();

   return ob_get_clean();
}

//funzione pagina grazie pagamento
add_shortcode( 'thank_you_page_advertising', 'thank_you_plugin_advertising' );

function thank_you_plugin_advertising() {

   ob_start();

   thankYou_page_advertising();

   return ob_get_clean();
}
	
add_shortcode( 'advertising_management_sidebar_banner', 'sidebar_banner_plugin_advertising' );

function sidebar_banner_plugin_advertising() {

   ob_start();

   advertising_management_sidebar_banner();

   return ob_get_clean();
}

?>
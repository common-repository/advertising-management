<?php
/**
 * WP_ADVERTISING_MANAGEMENT
 *
 * Questa crea pagine e menu del plugin
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


 
//option for registration page
add_option('advertising_management_registrationPage','100',null,false);
add_option('advertising_management_purchasePage','100',null,false);
add_option('advertising_management_customAdvPage','100',null,false);
add_option('advertising_management_itemSelected', 100, null,false); 
add_option('advertising_management_IDadvertising', '' , null , false);
add_option('advertising_management_currencyName','Euro (€)',null,false);
add_option('advertising_management_currencySymbol','€',null,false);
add_option('advertising_management_linkPrivacyPolice','',null,false);
add_option('advertising_management_linkCondGenVend','',null,false);
add_option('advertising_management_emailNotice','',null,false);
add_option('advertising_management_emailPaypal','',null,false);
add_option('advertising_management_thank_you_adv_page','100',null,false);										
//from pro option
add_option('advertising_management_listShortcode','',null,false);
add_option('IDadvertisingAfterPost','',null,false);
add_option('IDadvertisingBeginPost','',null,false);


function activate_advertising_management_plugin() {

    //Richiamo funzione creazione DB clienti
    created_adv_db_customers();

    //richiamo la funzione creazione db advertising
    created_adv_db_advertising();
	//richiamo la funzione db sales
    created_adv_db_sales();
							   

    //richiamo la funzione creazione db paypal
    created_adv_db_paypal();

    
     //-----start  Create page PURCHASE
     $purchase_page = array(
        'post_title'    =>  __( 'Acquisto spazi pubblicitari', 'advertising_purchase' ),
        'post_content'  => '[purchase_page]',
        'post_status'   => 'publish',
        'post_author'   => 1,
        'post_type'     => 'page',
        'comment_status' => 'closed'
      );
  
      // Insert the post into the database
      $insertP= wp_insert_post( $purchase_page,true );
  
      //save the id in the database
      update_option( 'advertising_management_purchasePage', $insertP ); 
      

      //-----finish  Create page PURCHASE

    //-----start  Create page CUSTOM ADV
    $custom_adv_page = array(
      'post_title'    =>  __( 'Personalizza spazio pubblicitario', 'advertising_custom' ),
      'post_content'  => '[custom_adv_page]',
      'post_status'   => 'publish',
      'post_author'   => 1,
      'post_type'     => 'page',
      'comment_status' => 'closed'
    );

    // Insert the post into the database
    $insertC= wp_insert_post( $custom_adv_page,true );

    //save the id in the database
    update_option( 'advertising_management_customAdvPage', $insertC ); 
    

    //-----finish  Create page PURCHASE  

    //-----start  Create page REGISTRATION
    $registration_page = array(
        'post_title'    =>  __( 'Registrazione e pagamento', 'advertising_registration' ),
        'post_content'  => '[registration_wp_advertising]',
        'post_status'   => 'publish',
        'post_author'   => 1,
        'post_type'     => 'page',
        'comment_status' => 'closed'
      );
  
      // Insert the post into the database
      $insert= wp_insert_post( $registration_page,true );
  
      //save the id in the database
      update_option( 'advertising_management_registrationPage', $insert ); 

      //-----finish  Create page REGISTRATION       

  //-----start  Create page THANK YOU PAGE
    $thank_you_adv_page = array(
      'post_title'    =>  __( 'Pagina di ringraziamento', 'thank_you_page_advertising' ),
      'post_content'  => '[thank_you_page_advertising]',
      'post_status'   => 'publish',
      'post_author'   => 1,
      'post_type'     => 'page',
      'comment_status' => 'closed'
    );

    // Insert the post into the database
    $insertC= wp_insert_post( $thank_you_adv_page,true );

    //save the id in the database
    update_option( 'advertising_management_thank_you_adv_page', $insertC ); 
    

    //-----finish  Create page THANK YUOU PAGE  												
     
      // inizio: se non è stata schedulata, fallo ora
    if( !wp_next_scheduled( 'check_advertising' ) ) {
        wp_schedule_event( time(), 'hourly', 'check_advertising' );
    }

}

//menù impostazioni
add_action( 'admin_menu', 'advertising_management_menu' );

add_action('check_advertising', 'event_daily_advertising');

 ?>
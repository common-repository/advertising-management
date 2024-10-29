<?php
/**
 * WP_ADVERTISING_MANAGEMENT
 *
 * Questa elimina pagine collegate all'attivazione del plugin
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

function deactivate_advertising_management_plugin() {


    /** Delete the Plugin Pages */
    $adv_created_pages = array( 'advertising_management_registrationPage', 'advertising_management_purchasePage', 'advertising_management_customAdvPage', 'advertising_management_thank_you_adv_page');
	foreach ( $adv_created_pages as $p ) {
		$page = get_option( $p );
		if ( $page ) {
			wp_delete_post( $page, true );
        }
        delete_option($p);
    }
	  /*DELETE OPTION*/
    $adv_created_options = array('advertising_management_itemSelected','advertising_management_IDadvertising','advertising_management_currencyName','advertising_management_currencySymbol','advertising_management_emailNotice','advertising_management_emailPaypal','advertising_management_linkPrivacyPolice','advertising_management_linkCondGenVend');
    foreach ( $adv_created_options as $p ) {		
        delete_option($p);
    }				 
    
    /* DELETE TABLE  */
     
	delete_adv_db_paypal();
    delete_adv_db_sales();
    delete_adv_db_customers();
    delete_adv_db_advertising();
    
    /*elimino evento schedulato */
    wp_clear_scheduled_hook('check_advertising');

}


 ?>
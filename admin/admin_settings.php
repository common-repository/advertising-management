<?php
/**
 * WP_ADVERTISING_MANAGEMENT
 *
 * Pagina principale opzioni plguin wp advertising
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

/** Step 1. */
function advertising_management_menu() {

    $slug_menu_top = 'option_adv_management';
    $capability = 'administrator';

    add_menu_page( 'Advertising management option', 
    'ADV management', 
    $capability,
    $slug_menu_top,
    'adv_management_option_welcome', 
    plugins_url( 'wp_advertising_management/template/images/wp_advertising_menu_icon.png' ) 
    );  

    add_submenu_page( $slug_menu_top, 
    'Premium',
    'Premium',
    $capability,
    'option_adv_management_premium',
    'adv_management_option_premium');
    

    add_submenu_page($slug_menu_top, 
    'Articoli',
    'Articoli',
    $capability,
    'option_adv_management_advertising',
    'adv_management_option_advertising');

    add_submenu_page($slug_menu_top, 
    'Clienti',
    'Clienti',
    $capability,
    'option_adv_management_customer',
    'adv_management_option_customer');

    add_submenu_page($slug_menu_top, 
    'Vendite',
    'Vendite',
    $capability,
    'option_adv_management_sales',
    'adv_management_option_sales');

    add_submenu_page($slug_menu_top, 
    'Impostazioni' ,
    'Impostazioni',
    $capability,
    'option_adv_management_support',
    'adv_management_option_support');


}   

// Caricamento file CSS personalizzato solo per
// quanto riguarda i componenti di amministrazione


//Caricamento bootstrap
function botstrap_premium_adv_admin($hook) {
    

    switch($hook){
        case 'toplevel_page_option_adv_management':
        
        wp_enqueue_style( 'customCSSAdvManagementAdmin',  URL_ADVERTISING_PLUGIN . 'admin/css/PageAdvManagementAdmin.css');

        wp_enqueue_style('bootstrap4', 'https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css');
            wp_enqueue_script( 'boot1','https://code.jquery.com/jquery-3.3.1.slim.min.js', array( 'jquery' ),'',true );
            wp_enqueue_script( 'boot2','https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js', array( 'jquery' ),'',true );
            wp_enqueue_script( 'boot3','https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js', array( 'jquery' ),'',true );
            wp_enqueue_script( 'fontawesone','https://use.fontawesome.com/releases/v5.0.6/js/all.js' );

        break;

        case 'adv-management_page_option_adv_management_support':
        
        wp_enqueue_style( 'customCSSAdvManagementAdmin',  URL_ADVERTISING_PLUGIN . 'admin/css/PageAdvManagementAdmin.css');

        wp_enqueue_style('bootstrap4', 'https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css');
            wp_enqueue_script( 'boot1','https://code.jquery.com/jquery-3.3.1.slim.min.js', array( 'jquery' ),'',true );
            wp_enqueue_script( 'boot2','https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js', array( 'jquery' ),'',true );
            wp_enqueue_script( 'boot3','https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js', array( 'jquery' ),'',true );
            wp_enqueue_script( 'fontawesone','https://use.fontawesome.com/releases/v5.0.6/js/all.js' );

        break;

        case 'adv-management_page_option_adv_management_premium':
        
        wp_enqueue_style( 'customCSSAdvManagementAdmin',  URL_ADVERTISING_PLUGIN . 'admin/css/PageAdvManagementAdmin.css');

        wp_enqueue_style('bootstrap4', 'https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css');
            wp_enqueue_script( 'boot1','https://code.jquery.com/jquery-3.3.1.slim.min.js', array( 'jquery' ),'',true );
            wp_enqueue_script( 'boot2','https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js', array( 'jquery' ),'',true );
            wp_enqueue_script( 'boot3','https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js', array( 'jquery' ),'',true );
            wp_enqueue_script( 'fontawesone','https://use.fontawesome.com/releases/v5.0.6/js/all.js' );

        break;

        default:
        return;
    }
    
	
}	

add_action('admin_enqueue_scripts', 'botstrap_premium_adv_admin');

 ?>
<?php
/*
Plugin Name: advertising management
Plugin URI: http://www.wpadvertisingmanagement.com
Description: Vendi spazi pubblicitari del tuo sito web in totale sicurezza ed autonomia. Wpmanagementadvertising ti permette di gestire gli spazi pubblicitari in modo automatico e semplice.
Version: 1.0.3
Author: Marcon Simone
Author URI: http://www.simonemarcon.com
License: GPL2
License URI: https://www.gnu.org/licenses/gpl-2.0.html
*/

//blocco accesso diretto ai file
defined('ABSPATH') or die ('access denied');
//recupero url
define('URL_ADVERTISING_PLUGIN', plugin_dir_url( __FILE__ ) );
//recupero percorso
define('PATH_ADVERTISING_PLUGIN', plugin_dir_path( __FILE__ ) );


//includo i file
require_once(PATH_ADVERTISING_PLUGIN . '/includes/install.php');
require_once(PATH_ADVERTISING_PLUGIN . 'unistall.php');
require_once(PATH_ADVERTISING_PLUGIN . '/template/register.php');
require_once(PATH_ADVERTISING_PLUGIN . '/includes/database_customers.php');
require_once(PATH_ADVERTISING_PLUGIN . '/template/purchase.php');
require_once(PATH_ADVERTISING_PLUGIN . '/includes/shortcodes.php');
require_once(PATH_ADVERTISING_PLUGIN . '/includes/database_advertising.php');
require_once(PATH_ADVERTISING_PLUGIN . '/template/custom_adv.php');   
require_once(PATH_ADVERTISING_PLUGIN . '/includes/database_sales.php'); 
require_once(PATH_ADVERTISING_PLUGIN . '/includes/daily_event.php');   
require_once(PATH_ADVERTISING_PLUGIN . '/includes/database_paypal.php');
require_once(PATH_ADVERTISING_PLUGIN . '/template/sidebar_banner.php');
require_once(PATH_ADVERTISING_PLUGIN . '/includes/after_post.php');
require_once(PATH_ADVERTISING_PLUGIN . '/template/thank_you_page_adv.php'); 

//admin settings
require_once(PATH_ADVERTISING_PLUGIN . '/admin/admin_settings.php');   
require_once(PATH_ADVERTISING_PLUGIN . '/admin/includes/premium_adv.php');   
require_once(PATH_ADVERTISING_PLUGIN . '/admin/includes/welcome_adv.php');   
require_once(PATH_ADVERTISING_PLUGIN . '/admin/includes/advertising_adv.php');   
require_once(PATH_ADVERTISING_PLUGIN . '/admin/includes/sales_adv.php');   
require_once(PATH_ADVERTISING_PLUGIN . '/admin/includes/settings_adv.php');   
require_once(PATH_ADVERTISING_PLUGIN . '/admin/includes/customer_adv.php');   

//admin class
require_once(PATH_ADVERTISING_PLUGIN . '/admin/class/advertising_view_class.php'); 
require_once(PATH_ADVERTISING_PLUGIN . '/admin/class/customer_view_class.php');   
require_once(PATH_ADVERTISING_PLUGIN . '/admin/class/sales_view_class.php'); 

//require_once(PATH_ADVERTISING_PLUGIN . '/template/css/purchase.css');


//funzioni di attivazione e disattivazione plugin
register_activation_hook(__FILE__, 'activate_advertising_management_plugin');
register_deactivation_hook(__FILE__, 'deactivate_advertising_management_plugin');


?>
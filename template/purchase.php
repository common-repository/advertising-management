<?php ob_start(); ?>
<?php
/**
 * WP_ADVERTISING_MANAGEMENT
 *
 * Pagina purchase
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


function advertising_management_purchase() {	

	global $wpdb;
	
	
	?>
	<h1 style="text-align: center">Prodotti:</h1>
	<div class="container-fluid">
		<div class="row">
	<?php
	
	//recupero le pubblicità
	$advertisingList = $wpdb->get_results( "SELECT * FROM " . $wpdb->prefix . "adv_advertising" );

    //mostro le possibilità di acquisto   
	foreach ($advertisingList as $object) 
	{
		if($object->activeted == 1 && $object->availability >= 1){
		?>
		<div id="box-product" class="col-sm-6">
			<p class="purchasePageAdvH5"><?php echo $object->name; ?><br></p>
			<p><?php echo $object->description; ?><br></p>
			<p>Il suo annuncio resterà visbile sul nostro sito per <?php echo $object->timeOnline; ?> giorni <br></p>
			<a class="purchasePageAdvLink" href="<?php printf(home_url() . "/personalizza-spazio-pubblicitario?Item=%s",urlencode($object->ID)); ?>" >Acquista il tuo spazio nel nostro sito web a soli <?php echo $object->price . ' ' . get_option('advertising_management_currencySymbol'); ?> <?php add_filter('Submit','register'); ?></a>
			</form>
			<hr>
		</div>
		<?php
		}
	}?>
		</div>
	</div>
	<?php

	
}	
function adv_purchase_page_css() {
	wp_register_style( 'customCSSAdvManagement', URL_ADVERTISING_PLUGIN . 'template/css/PageAdvManagement.css' );
	wp_enqueue_style( 'customCSSAdvManagement' );

	wp_enqueue_style('bootstrap4', 'https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css');
    wp_enqueue_script( 'boot1','https://code.jquery.com/jquery-3.3.1.slim.min.js', array( 'jquery' ),'',true );
    wp_enqueue_script( 'boot2','https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js', array( 'jquery' ),'',true );
    wp_enqueue_script( 'boot3','https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js', array( 'jquery' ),'',true );
}	

add_action('wp_enqueue_scripts', 'adv_purchase_page_css');



?>

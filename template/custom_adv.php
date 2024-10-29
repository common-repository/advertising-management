<?php
/**
 * WP_ADVERTISING_MANAGEMENT
 *
 * Pagina custom adv
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

function custom_adv_content() {

if ( isset( $_GET['Item'] ) ) { 
        
    global $wpdb;

    
    //ricavo i dati dal database dell'item scelto dall'utente
    $itemSelected = $wpdb->get_row( $wpdb->prepare(
        "SELECT * FROM " . $wpdb->prefix . "adv_advertising WHERE ID= %d",
        intval($_GET['Item'])
    ));

    if($itemSelected===null){ ?>

    <h4> Errore: il prodotto da lei scelto non è disponibile <br>
    La preghiamo di <a href="<?php echo home_url() ?>">riprovare</a></h4>
    
    <?php
    }else{
   
    ?>
    <div class="container">

        <div class="row">
            <h4 class="purchasePageAdvH5">Personalizza la tua pubblicità </h4>
        </div>
            <div class="row">
                <div class="col-md-12">
                    <form enctype="multipart/form-data" method="post" >
                    <div class="form-group">
                    <label for="link">Link sponsor:</label>
                    <input class="form-control" type="text" id="link" name="link" placeholder="Link" required />
                    <small class="form-text text-muted"> In questa sezione inserisci il link al quale verranno poi reindirizzati gli utenti.</small>
                    </div>
                    <?php 
                    //controllo se è un tipo link o banner
                    if($itemSelected->advtype == 'link'){ ?>
                    <div class="form-group">
                    <label for="textLink" >Testo che verrà visualizzato agli utenti:</label>
                    <input class="form-control" type="text" id="textLink" name="textLink" placeholder="Testo del link" maxlength="20" />
                    <small class="form-text text-muted"> Massimo 20 caratteri</small>
                    </div>
                    <?php }

                    if($itemSelected->advtype != 'link'){ ?>
                    <div class="form-group">
                    <label for="imgBanner" >Immagine del banner:</label>
                    <input class="form-control-file" type="file" name="imgBanner" id="imgBanner" required>
                    <small class="form-text text-muted"> Per una corretta visualizzazione si raccomanda di rispettare le dimensioni (<?php echo $itemSelected->width . 'X' . $itemSelected->height; ?>)</small>
                    </div>

                    <?php } 
                    
                        wp_nonce_field( 'imgBanner', 'my_details_submit_nonce' ); ?>
                        <input type="submit" value="Procedi alla registrazione" name="submit" id="AdvManagementSubitCustomAdv"><?php do_action('SubmitCustom',$_GET['Item']);  ?>
                        
                    </form>
                </div>
            </div>
        </div>
<?php
}
}
}


add_action( 'SubmitCustom', 'send_custom_adv',10,1 );

function send_custom_adv($Item){

    global $wpdb;

      //nonce custom adv 
      $nonceADVcustomadvertising = wp_create_nonce('adv_custom_adv_to_register');
    
    //iNSERISCO LA DATA DI IERI PER EVITARE VENGA PUBBLICATO
    $newdate = date('Y-m-d', mktime(0, 0, 0, date('m'), date('d') -1 , date('Y')));

   // Check that the nonce is valid, and the user can edit this post.
    if ( isset( $_POST['submit'] ) && wp_verify_nonce( $_POST['my_details_submit_nonce'], 'imgBanner' ))
    {      

        if(isset( $_POST['textLink'])){

            $sql_sale = $wpdb->insert( $wpdb->prefix . 'adv_sales',
            array(
                'state' => '1',
                'advertisingID'=> $Item,
                'link'=>  $_POST['link'],
                'textLink'=> $_POST['textLink'],
                'datefinish'=> $newdate
                )
            );      
            
            
            //pagina di registrazione passando atraverso metodo get l'id della vendita
            wp_redirect( home_url() . "/registrazione-e-pagamento?ID=$wpdb->insert_id&Item=$Item&_wpnonce=$nonceADVcustomadvertising&token=adv_custom_to_register" );

        }
        else
        {   
	        // The nonce was valid and the user has the capabilities, it is safe to continue.

	        // These files need to be included as dependencies when on the front end.
            require_once( ABSPATH . 'wp-admin/includes/file.php' );
                
            $upload_overrides = array( 'test_form' => false );

	
	        // Let WordPress handle the upload.
	        // Remember, 'my_image_upload' is the name of our file input in our form above.
	        $adv_photo_id = wp_handle_upload( $_FILES['imgBanner'] , $upload_overrides);
	
	        if ( $adv_photo_id && ! isset( $adv_photo_id['error'] ) ) {
                
                $sql_sale = $wpdb->insert( $wpdb->prefix . 'adv_sales',
                array(
                    'state' => '1',
                    'advertisingID'=> $Item,
                    'link'=>  $_POST['link'],
                    'linkImg'=> $adv_photo_id['url'],
                    'datefinish'=> $newdate
                    )
                );      
                            
                //pagina di registrazione
                wp_redirect( home_url() . "/registrazione-e-pagamento?ID=$wpdb->insert_id&Item=$Item&_wpnonce=$nonceADVcustomadvertising&token=adv_custom_to_register" );
            
             } else {
            /**
             * Error generated by _wp_handle_upload()
             * @see _wp_handle_upload() in wp-admin/includes/file.php
             */
            echo $adv_photo_id['error'];
            
            }
    
        } 
    
    }else {
        
           //inserimento errore --------------------------
            
    }

}

function adv_custom_adv_page_css() {
	wp_register_style( 'customCSSAdvManagement', URL_ADVERTISING_PLUGIN . 'template/css/PageAdvManagement.css' );
	wp_enqueue_style( 'customCSSAdvManagement' );
}	

add_action('wp_enqueue_scripts', 'adv_custom_adv_page_css');
?>
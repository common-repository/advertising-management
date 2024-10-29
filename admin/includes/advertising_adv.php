<?php
/**
 * WP_ADVERTISING_management
 *
 * Pagina advertising opzioni plugin adv management
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

 
 // Creazione Tabella per visualizzare i records
// che sono stati memorizzati nel database PVCLI00F

function adv_management_option_advertising()
{
  if(isset($_POST['submitAdvsettings'])){ 
    
    //controllo nonce
    if(!wp_verify_nonce( $_POST['editADVNonce'], 'nonceEditAdv')){
      ?>
    <div class="notice notice-error"><p>Errore: si prega di riprovare</p></div>
    <?php
    }
    else
    {
        //controllo nonce superato

        $name = sanitize_text_field( $_POST['name'] );
        $description = sanitize_textarea_field( $_POST['description'] );
        $height = sanitize_text_field( $_POST['height'] );
        $width = sanitize_text_field( $_POST['width'] );
        $price = sanitize_text_field( $_POST['price'] );

        //modifico valore variabile se = ND
        if( $height == 'ND')
          $height = null;

        if( $width == 'ND')
          $width = null;

        
        global $wpdb;

        $save_update_adv = $wpdb->update( $wpdb->prefix . 'adv_advertising',
                array(
                    'name' => $name,
                    'description'=> $description,
                    'height'=> $height,
                    'width'=> $width,
                    'price' => $price
                ),
                array('ID' => $_POST['ID'])
              ); 

        //salvataggio non andato a buon fine
        if($save_update_adv == false)  { 
          ?>
            <div class="notice notice-error"><p>Errore nel salvataggio: si prega di riprovare</p></div>
          <?php
        }else   { 
          ?>
            <div class="notice notice-success"><p>Articolo aggiornato!</p></div>
          <?php
        }

    }


  }
  
  $table = new WPAdvTableAdvertising(); // Il codice della classe a seguire
  $table->prepare_items(); // Metodo per elenco campi

  // Definizione variabili per contenere i valori
  // di paginazione e il nome della pagina visualizzata

  $page  = filter_input(INPUT_GET,'page' ,FILTER_SANITIZE_STRIPPED);
  $paged = filter_input(INPUT_GET,'paged',FILTER_SANITIZE_NUMBER_INT);

  echo '<div class="wrap">';
  echo '<h2>Articoli:</h2>';

  // Form per contenere la tabella con elenco records
  // presenti nel database e campi definiti nella classe

  echo '<form id="persons-table" method="GET">';
  echo '<input type="hidden" name="paged" value="'.$paged.'"/>';
    $table->display(); // Metodo per visualizzare elenco records
  echo '</form>';

  echo '</div>';
  }



 ?>
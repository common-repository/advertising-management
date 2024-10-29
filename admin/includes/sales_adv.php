<?php
/**
 * WP_ADVERTISING_management
 *
 * Pagina vendite opzioni plugin adv management
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

 function adv_management_option_sales() {

  if(isset($_POST['submitSalesettings'])){ 
    
    //controllo nonce
    if(!wp_verify_nonce( $_POST['editSaleNonce'], 'nonceEditSale')){
      ?>
    <div class="notice notice-error"><p>Errore: si prega di riprovare</p></div>
    <?php
    }
    else
    {
        //controllo nonce superato

        $state = sanitize_text_field( $_POST['state'] );
        $link = sanitize_text_field( $_POST['link'] );
        $textlink = sanitize_text_field( $_POST['textlink'] );
        $imglink = sanitize_text_field( $_POST['imglink'] );
        $datefinish = sanitize_text_field( $_POST['datefinish'] );//data
        $pay=0;

        //modifica state e pay
        switch( $state ){ 

          case 'completato':
            $state = 3;
            $pay = 1;
            break;
          case 'erroreTransazione':
            $state = 3;
            $pay = 0;  
            break;
          case 'annullato':  
            $state = -1;
            $pay = 0;
            break;
          default:
            $state = 0;
            $pay = 0;

        }

        //controlo se non c'è testo link o img
        if( $textlink == 'ND' )
          $textlink = null;

        if( $imglink == 'ND' )
          $imglink = null;  

        
        $date = date("Y-m-d", strtotime($datefinish));

        global $wpdb;

        $save_update_sale = $wpdb->update( $wpdb->prefix . 'adv_sales',
                array(
                  'state'      =>   $state,
                  'pay'        =>   $pay,
                  'link'       =>   $link,
                  'textLink'   =>   $textlink,
                  'linkImg'    =>   $imglink,
                  'datefinish' =>   $date

                ),
                array('ID' =>  $_POST['ID'] )
              ); 

        //salvataggio non andato a buon fine
        if($save_update_sale == false)  { 
          ?>
            <div class="notice notice-error"><p>Errore nel salvataggio: si prega di riprovare</p></div>
          <?php
        }else   { 
          ?>
            <div class="notice notice-success"><p>Dati aggiornati!</p></div>
          <?php
        }

    }


  }

  $table = new WPAdvTableSales(); // Il codice della classe a seguire
  $table->prepare_items(); // Metodo per elenco campi

  // Definizione variabili per contenere i valori
  // di paginazione e il nome della pagina visualizzata

  $page  = filter_input(INPUT_GET,'page' ,FILTER_SANITIZE_STRIPPED);
  $paged = filter_input(INPUT_GET,'paged',FILTER_SANITIZE_NUMBER_INT);

  echo '<div class="wrap">';
  echo '<h2>Vendite:</h2>';

  // Form per contenere la tabella con elenco records
  // presenti nel database e campi definiti nella classe

  echo '<form id="persons-table" method="GET">';
  echo '<input type="hidden" name="paged" value="'.$paged.'"/>';
    $table->display(); // Metodo per visualizzare elenco records
  echo '</form>';

  echo '</div>';
    }
    


 ?>
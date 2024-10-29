<?php
/**
 * WP_ADVERTISING_management
 *
 * Classe per la gestione della visualizzaizone della tabella sales
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

// Se non esiste la classe eseguo il caricamento
// del file di wordpress necessario all'esecuzione

if (!class_exists('WP_List_Table')) {
 require_once(ABSPATH.'wp-admin/includes/class-wp-list-table.php');
}

// Definizione Classe per il nostro database e aggiunta
// dei metodi necessari alla visualizzazione delle informazioni

class WPAdvTableSales extends WP_List_Table
{
  function __construct() {
    parent::__construct(array('plural'=>'Vendite'));
  }

  // Funzione per la preparazione dei campi da visualizzare
  // e la query SQL principale che deve essere eseguita 

  function prepare_items()
  {

    //controllo azioni
    //annullo ordine
    if( isset($_GET['action']) == 'trash' ){ 

      //cestino la riga metto state = -1
      $nonce = @esc_attr($_REQUEST['_wpnonce']);
      if(!wp_verify_nonce($nonce, 'adv_trash_sale')){
        die('Weird: Plugin is resolving itself');
      }

      global $wpdb;

        $save_update_adv = $wpdb->update( $wpdb->prefix . 'adv_sales',
                array(
                    'state' => '-1'
                ),
                array('ID' => $_GET['sale'])
              ); 

      //informo l'utente se l'operazione è andata a buon fine o meno      

      //salvataggio non andato a buon fine
      if($save_update_adv == false)  { 
        ?>
          <div class="notice notice-error"><p>Errore nel salvataggio: si prega di riprovare</p></div>
        <?php
      }else   { 
        ?>
          <div class="notice notice-success"><p>Ordine annullato!</p></div>
        <?php
      }

      }

      //elimino definitivamente ordine
      if( isset($_GET['action']) == 'delete' ){

         //cestino la riga metto state = -1
      $nonce = @esc_attr($_REQUEST['_wpnonce']);
      if(!wp_verify_nonce($nonce, 'adv_delete_sale')){
        die('Weird: Plugin is resolving itself');
      }

      global $wpdb;

        $delete_sale_adv = $wpdb->delete( $wpdb->prefix . 'adv_sales',
                array('ID' => $_GET['sale'])
              ); 

      //informo l'utente se l'operazione è andata a buon fine o meno      

      //salvataggio non andato a buon fine
      if($delete_sale_adv == false)  { 
        ?>
          <div class="notice notice-error"><p>Errore nel salvataggio: si prega di riprovare</p></div>
        <?php
      }else   { 
        ?>
          <div class="notice notice-success"><p>Ordine eliminato!</p></div>
        <?php
      }

    } 

    if( isset($_GET['action'])=='edit'){
      //controllo il nonce
      $nonce = @esc_attr($_REQUEST['_wpnonce']);
      if(!wp_verify_nonce($nonce, 'adv_edit_sale')){
        die('Weird: Plugin is resolving itself');
      }

      do_action('editSale');

      exit();
    }

    global $wpdb;
    $table_name = $wpdb->prefix.'adv_sales';
    $per_page = 25; // Numero dei record presenti in una pagina

    // Calcolo elenco de dei campi per le differenti
    // sezioni e memorizzo tutto in array separati

    $columns  = $this->get_columns();
    $hidden   = $this->get_columns_hidden();
    $sortable = $this->get_columns_sortable();

    // Bisogna memorizzare tre array che devono contenere i campi da 
    // visualizzare, quelli nascosti e quelli per eseguire l'ordinamento

    $this->_column_headers = array($columns,$hidden,$sortable);

    // Preparazione delle variabili che devono essere utilizzate
    // nella preparazione della query con gli ordinamenti e la posizione

    if (!isset($_REQUEST['paged'])) $paged = 0;
      else $paged = max(0,(intval($_REQUEST['paged'])-1)*25);

    if (isset($_REQUEST['orderby'])
        and in_array($_REQUEST['orderby'],array_keys($sortable)))
    $orderby = $_REQUEST['orderby']; else $orderby = 'saledate';

    if (isset($_REQUEST['order'])
        and in_array($_REQUEST['order'],array('asc','desc')))
    $order = $_REQUEST['order']; else $order = 'asc';

    // Calcolo le variabili che contengono il numero dei record totali
    // e l'elenco dei record da visualizzare per una singola pagina

    $total_items = $wpdb->get_var(
      "SELECT COUNT(*) FROM $table_name");

    $this->items = $wpdb->get_results($wpdb->prepare(
            "SELECT * FROM $table_name ".
            "ORDER BY $orderby $order ".
            "LIMIT %d OFFSET %d",$per_page, $paged), ARRAY_A);

    $this->set_pagination_args(array(
        'total_items' => $total_items,
        'per_page'    => $per_page,
        'total_pages' => ceil($total_items/$per_page)
    ));        

  }

  // Funzione per la definizione dei campi che devono
  // essere visualizzati nella lista da visualizzare

  function get_columns()
  {
    $columns = array(
      'ID'                 => 'ID',
      'state'              => 'Stato',
      'saledate'           => 'Data vendita',
      'customerID'         => 'Cliente',
      'advertisingID'      => 'Articolo',
      'totalprice'         => 'Totale',
      'invoice'            => 'Richiesta fattura',
      'datefinish'         => 'Data chiusura annuncio',
    );
    return $columns;
  }

  // Funzione per la definizione dei campi che possono
  // essere utilizzati per eseguire la funzione di ordinamento

  function get_columns_sortable()
  {
    $sortable_columns = array(
      'name'       => array('name',true),
      'price'         => array('price',false),
      'activeted' => array('activeted',false),
    );
    return $sortable_columns;
  }

  // Funzione per la definizione dei campi che devono 
  // essere calcolati dalla query ma non visualizzati

  function get_columns_hidden() {
    return array();
  }

  // Funzione per reperire il valore di un campo in
  // maniera standard senza una personalizzazione di output

  function column_default($item,$column_name) { 
    return $item[$column_name]; 
  }

  // Dato che alcuni campi hanno bisogno di output 
  // personalizzato bisogna creare una funzione per campo

  function column_state($item){ 

    if( $item['state'] == '3' && $item['pay'] == '1' ){ 
      return "Completato";
    }else if( $item['state'] == '3' && $item['pay'] == '0' ){ 
      return "Transazione fallita";
    }else if( $item['state'] == '-1' ){ 
      return "Annullato";
    }else{ 
      return "Non completato";
    }

     
  }

  function column_totalprice($item) { 
    return number_format(floatval($item['totalprice']),2,',','.').' ' . get_option('advertising_management_currencySymbol'); 
  }


  function column_invoice($item) { 
    if($item['invoice'] == '1')
        return "SI";
    return "NO";
  }

  //inserisco nome e cognome cliente con link
  function column_customerID($item) { 
    global $wpdb;
    $table_name = $wpdb->prefix.'adv_customers';

    if( ! is_null($item['customerID'] )){

      //ricavo i dati dal cliente dal DB
      $customer = $wpdb->get_row( $wpdb->prepare(
        "SELECT * FROM $table_name WHERE ID= %d",
        intval($item['customerID'] )
      ));

        //nonce
        $nonceEdit = wp_create_nonce('adv_edit_customer');

          
        return sprintf('<a href="?page=option_adv_management_customer&action=%s&customer=%s&_wpnonce=%s">' . $customer->name . ' ' . $customer->surname . '</a>', 
        'edit', absint($item['customerID']),$nonceEdit);
    }else{
        return "Registrazione non completata da parte del cliente";
      } 
  }

  //inserisco nome annuncio con link
  function column_advertisingID($item) { 
    global $wpdb;
    $table_name = $wpdb->prefix.'adv_advertising';

    if( ! is_null($item['advertisingID'] )){
          
        //ricavo i dati dal cliente dal DB
        $advertising = $wpdb->get_row( $wpdb->prepare(
          "SELECT * FROM $table_name WHERE ID= %d",
          intval($item['advertisingID'])
        ));

        //nonce
        $nonceEdit = wp_create_nonce('adv_edit_adv');

        return sprintf('<a href="?page=option_adv_management_advertising&action=%s&advertising=%s&_wpnonce=%s">' . $advertising->name . '</a>', 
        'edit', absint($item['advertisingID']), $nonceEdit);

      }else{
        return "Informazione non disponibile";
      } 
  }


  //FUNZIONE CAMPO ID CON AZIONI
  function column_ID($item) { 

  //nonce 
  $nonceEdit = wp_create_nonce('adv_edit_sale');
  $nonceTrash = wp_create_nonce('adv_trash_sale');
  $nonceDelete = wp_create_nonce('adv_delete_sale');

   // Definizioni azioni che devo comparire sotto la 
  // tessera quando andiamo in hover con il mouse
  if($item['state'] != '-1' ){

  $actions = array(

    'edit' => sprintf('<a href="?page=%s&action=%s&sale=%s&_wpnonce=%s">Modifica</a>', 
    esc_attr($_REQUEST['page']), 'edit', absint($item['ID']), 
    $nonceEdit),

   'trash' => sprintf('<a href="?page=%s&action=%s&sale=%s&_wpnonce=%s">Annulla ordine</a>', 
    esc_attr($_REQUEST['page']), 'trash', absint($item['ID']), 
    $nonceTrash),
  );
  } else {

  $actions = array(
    'edit' => sprintf('<a href="?page=%s&action=%s&sale=%s&_wpnonce=%s">Modifica</a>', 
    esc_attr($_REQUEST['page']), 'edit', absint($item['ID']), 
    $nonceEdit),

    'delete' => sprintf('<a href="?page=%s&action=%s&sale=%s&_wpnonce=%s">Elimina definitivamente</a>', 
    esc_attr($_REQUEST['page']), 'delete', absint($item['ID']), 
    $nonceDelete),
  );
  }

  // Ritorno  il valore della colonna tessera e
  // richiamo il metodo row_actions per le azioni 

  return sprintf('%1$s %2$s',$item['ID'],
    $this->row_actions($actions));
  }



}

function adv_SelectSaleEdit(){ 
/*
  *In questa funzione creo un form dove l'utente può modificare i dati
  *ed aggiornare il DB
  */

  //RICAVO I DATI DAL DB
  global $wpdb;

  $sql_row_sale = $wpdb->get_row( $wpdb->prepare(
    "SELECT * FROM " . $wpdb->prefix . "adv_sales WHERE ID= %d",
    intval($_GET['sale'])
  ));

  //converto la data
  $date= date("d-m-Y", strtotime($sql_row_sale->datefinish));

  //ricavo i dati dal cliente dal DB
  $customer = $wpdb->get_row(
    "SELECT * FROM " . $wpdb->prefix . "adv_customers WHERE ID=" . $sql_row_sale->customerID
  );

  //ricavo i dati dal cliente dal DB
  $advertising = $wpdb->get_row(
    "SELECT * FROM " . $wpdb->prefix . "adv_advertising WHERE ID=" . $sql_row_sale->advertisingID
  );

  ?>

  <h1>Vendita:</h1>

  <a href="<?php echo  home_url() . "/wp-admin/admin.php?page=option_adv_management_sales"; ?>" class="btn-primary">Torna all'elenco </a>
  <form method="post" >

    <table>

    <tr>
      <th>
        <label for="state">Stato:</label>
      </th>
      <td>  
        <select id="state" name="state" >
          <option value="completato"
          <?php if( $sql_row_sale->state == '3' && $sql_row_sale->pay == '1' ){ 
                  echo "selected";
           } ?>>Completato</option>
          <option value="erroreTransazione" 
          <?php if( $sql_row_sale->state == '3' && $sql_row_sale->pay == '0' ){ 
                  echo "selected";
           } ?> >Errore transazione</option>
          <option value="nonCompletato"
          <?php if( $sql_row_sale->state != '3' && $sql_row_sale->state != '-1' ){ 
                  echo "selected";
           } ?> >Non completato</option>
          <option value="annullato" 
          <?php if( $sql_row_sale->state == '-1' ){ 
                  echo "selected";
           } ?>>Annullato</option>
        </select>
      </td>
    </tr>

    <tr>
      <th>
        <label for="customer">Cliente:</label>
      </th>
      <td>  
        <input id="customer" name="customer" type="text"  readonly="readonly" value="<?php echo $customer->name . ' ' .$customer->surname; ?>" >
      </td>
    </tr>

    <tr>
      <th>
        <label for="advertising">Articolo:</label>
      </th>
      <td>  
        <input id="advertising" name="advertising" type="text" readonly="readonly" value="<?php echo $advertising->name; ?>" >
      </td>
    </tr>

    <tr>
      <th>
        <label for="totalprice">Importo totale:</label>
      </th>
      <td>  
        <input id="totalprice" name="totalprice" type="text" readonly="readonly" value="<?php echo $sql_row_sale->totalprice . ' ' . get_optio0n('advertising_management_currencySymbol'); ?>" >
      </td>
    </tr>

    <tr>
      <th>
        <label for="link">Link:</label>
      </th>
      <td>  
        <input id="link" name="link" type="text" value="<?php echo $sql_row_sale->link; ?>" >
      </td>
    </tr>

    <tr>
      <th>
        <label for="textlink">Testo del link:</label>
      </th>
      <td>  
        <input id="textlink" name="textlink" type="text" 
        <?php 
        if($sql_row_sale->textLink == null){ 
        echo "readonly=\"readonly\"";}
        ?>
        value="<?php 
        if($sql_row_sale->textLink == null){ 
        echo N.D;} else {
        echo $sql_row_sale->textLink; } ?>" required >
      </td>
    </tr>

    <tr>
      <th>
        <label for="imglink">Immagine del link:</label>
      </th>
      <td>  
        <input id="imglink" name="imglink" type="text" 
        <?php 
        if($sql_row_sale->linkImg == null){ 
        echo "readonly=\"readonly\"";}
        ?>
        value="<?php 
        if($sql_row_sale->linkImg == null){ 
        echo N.D;} else {
        echo $sql_row_sale->linkImg; } ?>" required >
      </td>
    </tr>

    <tr>
      <th>
        <label for="datefinish">Data chiusura annuncio:</label>
      </th>
      <td>  
        <input id="datefinish" name="datefinish" type="text" value="<?php echo $date; ?>" >
      </td>
    </tr>

    <tr>
      <th>
        <label for="invoice">Richiesta fattura:</label>
      </th>
      <td>  
        <input id="invoice" name="invoice" type="text" 
        readonly="readonly"
        value="<?php 
        if( $sql_row_sale->invoice == '1' ){ 
        echo "SI"; }else{
          echo "NO"; }   ?>" >
      </td>
    </tr>

    <tr>
        <input id="ID" name="ID" type="hidden" value="<?php echo $sql_row_sale->ID; ?>" >
        <?php wp_nonce_field( 'nonceEditSale', 'editSaleNonce' ); ?>
        <td><input type="submit" value="Salva" name="submitSalesettings"> </td>
    </tr>

</table>    
</form>


  <?php

}


//Azione quando l'utente seleziona un articolo
add_action('editSale','adv_SelectSaleEdit');

?>
<?php
/**
 * WP_ADVERTISING_management
 *
 * Classe per la gestione della visualizzaizone della tabella advertising
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

class WPAdvTableAdvertising extends WP_List_Table
{
  function __construct() {
    parent::__construct(array('plural'=>'advertising'));
  }

  // Funzione per la preparazione dei campi da visualizzare
  // e la query SQL principale che deve essere eseguita 

  function prepare_items()
  {

    if($_GET['action']=='delete'){

      //controllo il nonce
      $nonce = @esc_attr($_REQUEST['_wpnonce']);
      if(!wp_verify_nonce($nonce, 'adv_hidden_adv')){
        die('Weird: Plugin is resolving itself');
      }

      global $wpdb;

      $sql_adv_hidden = $wpdb->update( $wpdb->prefix . 'adv_advertising',
                array(
                    'activeted' => '0'
                ),
                array('ID' => $_GET['advertising'])
                );  
    }

    if($_GET['action']=='clone'){

      //controllo il nonce
      $nonce = @esc_attr($_REQUEST['_wpnonce']);
      if(!wp_verify_nonce($nonce, 'adv_clone_adv')){
        die('Weird: Plugin is resolving itself');
      }

      global $wpdb;

      $sql_adv_hidden = $wpdb->update( $wpdb->prefix . 'adv_advertising',
                array(
                    'activeted' => '1'
                ),
                array('ID' => $_GET['advertising'])
                );  
    }

    if($_GET['action']=='edit'){
      //controllo il nonce
      $nonce = @esc_attr($_REQUEST['_wpnonce']);
      if(!wp_verify_nonce($nonce, 'adv_edit_adv')){
        die('Weird: Plugin is resolving itself');
      }

      do_action('editAdvertising');

      exit();
    }

    global $wpdb;
    $table_name = $wpdb->prefix.'adv_advertising';
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
    $orderby = $_REQUEST['orderby']; else $orderby = 'name';

    if (isset($_REQUEST['order'])
        and in_array($_REQUEST['order'],array('asc','desc')))
    $order = $_REQUEST['order']; else $order = 'asc';

    // Calcolo le variabili che contengono il numero dei record totali
    // e l'elenco dei record da visualizzare per una singola pagina

    $total_items = $wpdb->get_var(
      "SELECT COUNT(name) FROM $table_name");

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

  //funzione che faccia apparire un messaggio di errore in caso di lista vuota
  function no_items() {
    _e( 'Errore, nessun valore presente. Reinstalla il plugin' );
  }

  // Funzione per la definizione dei campi che devono
  // essere visualizzati nella lista da visualizzare

  function get_columns()
  {
    $columns = array(
      'ID'                 => 'ID',
      'name'               => 'Nome',
      'description'        => 'Descrizione',
      'shortcode'          => 'Shortcode',
      'timeOnline'         => 'Durata annuncio',
      'price'              => 'Prezzo',
      'activeted'          => 'Visibile',
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

  function column_price($item) { 
    return number_format(floatval($item['price']),2,',','.').' €'; 
  }

  function column_shortcode($item) { 
    if($item['shortcode'] == '0')
        return "N.D.";
    return $item['shortcode'];
  }						 

  function column_activeted($item) { 
    if($item['activeted'] == '1')
        return "SI";
    return "NO";
  }

  function column_timeOnline($item) { 
    return $item['timeOnline'] . " giorni";
  }

  //FUNZIONE CAMPO ID CON AZIONI
  function column_ID($item) { 
   // Definizioni azioni che devo comparire sotto la 
  // tessera quando andiamo in hover con il mouse

  //creo i nonce per modifica e rendere l'articolo non visibile
  $nonceEdit = wp_create_nonce('adv_edit_adv');
  $nonceHidden = wp_create_nonce('adv_hidden_adv');
  $nonceClone = wp_create_nonce('adv_clone_adv');

  //controllo se è visibile o meno l'elemento
  if($item['activeted'] == '1'){

  $actions = array(
      'edit' => sprintf('<a href="?page=%s&action=%s&advertising=%s&_wpnonce=%s">Modifica</a>', 
      esc_attr($_REQUEST['page']), 'edit', absint($item['ID']), 
      $nonceEdit),

      'delete' => sprintf('<a href="?page=%s&action=%s&advertising=%s&_wpnonce=%s">Non visibile</a>', 
      esc_attr($_REQUEST['page']), 'delete', absint($item['ID']), 
      $nonceHidden),
    );

  }else{

    $actions = array(
      'edit' => sprintf('<a href="?page=%s&action=%s&advertising=%s&_wpnonce=%s">Modifica</a>', 
      esc_attr($_REQUEST['page']), 'edit', absint($item['ID']), 
      $nonceEdit),

      'clone' => sprintf('<a href="?page=%s&action=%s&advertising=%s&_wpnonce=%s">Visibile</a>', 
      esc_attr($_REQUEST['page']), 'clone', absint($item['ID']), 
      $nonceClone),
    );

  }  

  // Ritorno  il valore della colonna tessera e
  // richiamo il metodo row_actions per le azioni 

  return sprintf('%1$s %2$s',$item['ID'],
    $this->row_actions($actions));
  }  
   
}

//funzione quando è stato selezionato un elemento in modifica
function adv_SelectAdvertisingItemEdit(){ 
  
  /*
  *In questa funzione creo un form dove l'utente può modificare i dati
  *ed aggiornare il DB
  */

  //RICAVO I DATI DAL DB
  global $wpdb;

  $sql_row_advertising = $wpdb->get_row( $wpdb->prepare(
    "SELECT * FROM " . $wpdb->prefix . "adv_advertising WHERE ID= %d",
    intval($_GET['advertising'])
  ));

  ?>

  <h1><?php echo $sql_row_advertising->name; ?></h1>

  <a href="<?php echo  home_url() . "/wp-admin/admin.php?page=option_adv_management_advertising"; ?>" class="btn-primary">Torna agli articoli </a>
  <form method="post" >

    <table>

    <tr>
      <th>
        <label for="name">Nome articolo:</label>
      </th>
      <td>
        <input id="name" name="name" type="text" value="<?php echo $sql_row_advertising->name; ?>" required >
      </td>
    </tr>

    <tr>
      <th>
        <label for="description">Descrizione:</label>
      </th>
      <td>  
        <textarea id="description" name="description" rows="3" required ><?php echo $sql_row_advertising->description; ?></textarea>
      </td>
    </tr>

    <tr>
      <th>
        <label for="shortcode">Shortcode:</label>
      </th>
      <td>  
        <input id="shortcode" name="shortcode" type="text" readonly="readonly" value="<?php 
        if($sql_row_advertising->shortcode == '0'){ 
        echo N.D;} else {
        echo $sql_row_advertising->shortcode; } ?>" required >
      </td>
    </tr>

    <tr>
      <th>
        <label for="height">Altezza:</label>
      </th>
      <td>  
        <input id="height" name="height" type="text" <?php 
        if($sql_row_advertising->height == null){ 
        echo "readonly=\"readonly\"";}
        ?>
        value="<?php 
        if($sql_row_advertising->height == null){ 
        echo N.D;} else {
        echo $sql_row_advertising->height; } ?>" required >
      </td>
    </tr>

    <tr>
      <th>
        <label for="width">Larghezza:</label>
      </th>
      <td>  
        <input id="width" name="width" type="text" <?php 
        if($sql_row_advertising->width == null){ 
        echo "readonly=\"readonly\"";}
        ?>
        value="<?php 
        if($sql_row_advertising->width == null){ 
        echo N.D;} else {
        echo $sql_row_advertising->width; } ?>" required >
      </td>
    </tr>

    <tr>
      <th>
        <label for="price">Prezzo:</label>
      </th>
      <td>  
        <input id="price" name="price" type="text" value="<?php echo $sql_row_advertising->price; ?>" required >
      </td>
    </tr>

    <tr>
      <th>
        <label for="timeonline">Durata annuncio (giorni) PRO VERSION:</label>
      </th>
      <td>  
        <input id="timeonline" name="timeonline" type="text" readonly="readonly" value="<?php echo $sql_row_advertising->timeOnline; ?>" required >
      </td>
    </tr>

    <tr>
      <th>
        <label for="availability">Disponibilità PRO VERSION:</label>
      </th>
      <td>  
        <input id="availability" name="availability" type="text" readonly="readonly" value="<?php echo $sql_row_advertising->availability; ?>" \>
      </td>
    </tr>

    <tr>
        <input id="ID" name="ID" type="hidden" value="<?php echo $sql_row_advertising->ID; ?>" >
        <?php wp_nonce_field( 'nonceEditAdv', 'editADVNonce' ); ?>
        <td><input type="submit" value="Salva" name="submitAdvsettings"> </td>
    </tr>

</table>    
</form>


  <?php


}

//Azione quando l'utente seleziona un articolo
add_action('editAdvertising','adv_SelectAdvertisingItemEdit');
//add_action('InvioSettingsADV','adv_SaveAdvSettings');
?>
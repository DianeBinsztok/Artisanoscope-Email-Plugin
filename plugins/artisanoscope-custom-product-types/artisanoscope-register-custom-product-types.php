<?php
/**
* Plugin Name: Artisanoscope Custom Product Types
* Description: Pour l'enregistrement des produits spécifiques à l'Artisanoscope
* Author: Diane Binsztok
* Version: 1.0
*/

// Virer les champs inutiles:
//https://rudrastyh.com/woocommerce/remove-product-tabs.html
// produit virtuel 
//https://wordpress.org/support/topic/how-to-add-a-custom-product-type-option-like-virtual-downloadable/
// Ajouter un attribut spécifique à l'atelier
// https://webkul.com/blog/add-custom-product-attributes-in-magento-2/

// Pour le stock et le bouton d'ajout au panier
//https://stackoverflow.com/questions/47910821/missing-add-to-cart-button-on-custom-product

if ( ! defined( 'ABSPATH' ) ) {
return;
}
// Charger les classes nécessaires dès que Woocommerce est chargé
add_action( 'plugins_loaded', 'artisanoscope_register_custom_product_classes' );
function artisanoscope_register_custom_product_classes(){
    require_once('class-artisanoscope-product-workshop.php');
}

/*
require_once 'class-artisanoscope-product-training.php';
require_once 'class-artisanoscope-product-giftcard.php';

// Initialiser un nouveau type de produit: Worskhop
// Quelle différence avec "add_action( 'init', 'register_workshop_product_type' );" ?
add_action( 'plugins_loaded', 'artisanoscope_register_custom_product_type' );

    function artisanoscope_register_custom_product_type() {

    class WC_Product_Workshop extends WC_Product_Variable {
        public function __construct( $product ) {
        $this->product_type = 'workshop';
        parent::__construct( $product );
        }
        
        public function get_type(){
            return 'workshop';
        }

        public function add_to_cart_url() {
            $url = $this->is_purchasable() && $this->is_in_stock() ? remove_query_arg( 'added-to-cart', add_query_arg( 'add-to-cart', $this->id ) ) : get_permalink( $this->id );
        
            return apply_filters( 'woocommerce_product_add_to_cart_url', $url, $this );
        }
    }

    class WC_Product_Training extends WC_Product_Variable {
        public function __construct( $product ) {
        $this->product_type = 'training';
        parent::__construct( $product );
        }

        public function get_type(){
            return 'training';
        }

        public function add_to_cart_url() {
            $url = $this->is_purchasable() && $this->is_in_stock() ? remove_query_arg( 'added-to-cart', add_query_arg( 'add-to-cart', $this->id ) ) : get_permalink( $this->id );
            return apply_filters( 'woocommerce_product_add_to_cart_url', $url, $this );
        }
    }

    class WC_Product_GiftCard extends WC_Product {
        public function __construct( $product ) {
        $this->product_type = 'giftcard';
        parent::__construct( $product );
        }

        public function get_type(){
            return 'giftcard';
        }

        public function add_to_cart_url() {
            $url = $this->is_purchasable() && $this->is_in_stock() ? remove_query_arg( 'added-to-cart', add_query_arg( 'add-to-cart', $this->id ) ) : get_permalink( $this->id );
        
            return apply_filters( 'woocommerce_product_add_to_cart_url', $url, $this );
        }
    }
}
*/

/**
 * Set variable to handler add to cart
 *
 * @param string $handler
 * @param WC_Product_Variable $product
 * @return string
 */
/*
public static function set_correct_add_to_cart_handler( $handler, $product ) {
    if ( $product->is_type( 'workshop' ) ) {
        $handler = 'variable'; # variable, simple or grouped
    }
    return $handler;
}
add_filter( 'woocommerce_add_to_cart_handler', 'set_correct_add_to_cart_handler', 10, 2 );
*/

/*
// Ajout du type de produit aux options des données de produit
add_filter( 'product_type_selector', 'artisanoscope_add_custom_product_types' );
function artisanoscope_add_custom_product_types( $types ){
// ajout des clés 'workshop' et 'training' au tableau de $types, avec leurs valeurs.
$types[ 'workshop' ] = __( 'Artisanoscope - Atelier ponctuel (en cours de test)', 'ws_product' );
$types[ 'training' ] = __( 'Artisanoscope - Formation continue (en cours de test)', 'tn_product' );
$types[ 'giftcard' ] = __( 'Artisanoscope - Carte cadeau (en cours de test)', 'gc_product' );
return $types; 
}


add_action( 'admin_footer', 'artisnoscope_show_pricing_fields' );
function artisnoscope_show_pricing_fields() {

	if ( 'product' != get_post_type() ) :
		return;
	endif;

	?><script type='text/javascript'>
		jQuery( document ).ready( function() {
			jQuery( '.options_group.pricing' ).addClass( 'show_if_workshop' ).show();
		});
        jQuery( document ).ready( function() {
			jQuery( '.options_group.pricing' ).addClass( 'show_if_training' ).show();
		});

	</script>
    
    
    <?php

}


// Ajout des onglets
add_filter( 'woocommerce_product_data_tabs', 'artisanoscope_custom_product_tabs' );
function artisanoscope_custom_product_tabs( $tabs) {
//https://stackoverflow.com/questions/67341234/woocommerce-custom-product-type-option-not-hiding-custom-product-tab

    //Ajouter l'onglet "Atelier ponctuel" si le produit Atelier est sélectionné
    $tabs['workshop'] = array(
        'label' => __( 'Artisanoscope - Atelier ponctuel', 'ws_product' ),
        'target' => 'workshop_product_options',
        'class' => array( 'hide_if_simple', 'hide_if_variable', 'show_if_workshop'),
    );

    //Ajouter l'onglet "Formation continue" si le produit Formation est sélectionné
    $tabs['training'] = array(
        'label' => __( 'Artisanoscope - Formation continue', 'tn_product' ),
        'target' => 'training_product_options',
        'class' => array( 'hide_if_simple', 'hide_if_variable', 'show_if_training' ),
    );

    //Ajouter l'onglet "Carte cadeau" si le produit Formation est sélectionné
    $tabs['giftcard'] = array(
        'label' => __( 'Artisanoscope - Carte cadeau (en cours de test)', 'tn_product' ),
        'target' => 'giftcard_product_options',
        'class' => array( 'hide_if_simple', 'hide_if_variable', 'show_if_giftcard' ),
    );

    //Enlever l'onglet "expédition" des deux types d'atelier
    array_push($tabs['shipping']['class'], "hide_if_workshop", "hide_if_training", "hide_if_giftcard");
    array_push($tabs['variations']['class'], "show_if_workshop", "show_if_training");

    return $tabs;
}

//Bouton d'activation du nouveau type de produit
add_action( 'woocommerce_product_data_panels', 'artisanoscope_workshop_tab_enable_custom_product_type' );
function artisanoscope_workshop_tab_enable_custom_product_type() {

    echo("<div id='workshop_product_options' class='panel woocommerce_options_panel'>");
    woocommerce_wp_checkbox( array(
        'id' 		=> '_enable_workshop_option', 
        'value' 		=> 'yes', 
        'label' 	=> __( 'Atelier', 'woocommerce' ),
    ) );
    echo("</div>");

    echo("<div id='training_product_options' class='panel woocommerce_options_panel'>");
    woocommerce_wp_checkbox( array(
        'id' 		=> '_enable_training_option', 
        'value' 	=> 'yes', 
        'label' 	=> __( 'Formation', 'woocommerce' ),
    ) );
    echo("</div>");

    echo("<div id='giftcard_product_options' class='panel woocommerce_options_panel'>");
    woocommerce_wp_checkbox( array(
        'id' 		=> '_enable_giftcard_option', 
        'value' 	=> 'yes', 
        'label' 	=> __( 'Carte cadeau', 'woocommerce' ),
    ) );
    echo("</div>");
}

add_action( 'woocommerce_process_product_meta_simple_rental', 'artisanoscope_save_cutom_product_type'  );
add_action( 'woocommerce_process_product_meta_variable_rental', 'artisanoscope_save_cutom_product_type'  );
function artisanoscope_save_cutom_product_type($post_id){
    $workshop_type_activated = isset($_POST['_enable_workshop_option'] ) ? 'yes' : 'no';
    update_post_meta( $post_id, '_enable_workshop_option', $workshop_type_activated );

    $training_type_activated = isset($_POST['_enable_training_option'] ) ? 'yes' : 'no';
    update_post_meta( $post_id, '_enable_training_option', $training_type_activated );

    $giftcard_type_activated = isset($_POST['_enable_giftcard_option'] ) ? 'yes' : 'no';
    update_post_meta( $post_id, '_enable_giftcard_option', $giftcard_type_activated );
}
*/

/**
 * Ajoute des nouvelles clés au tableau $stores
 * @param array $stores
 * @return array
 */
/*
add_filter( 'woocommerce_data_stores', 'artisanoscope_save_custom_product_types_in_db', 10, 1 );
function artisanoscope_save_custom_product_types_in_db( $stores ) {
    $stores['product-workshop'] = 'WC_Product_Variable_Data_Store_CPT';
    $stores['product-training'] = 'WC_Product_Variable_Data_Store_CPT';
    $stores['product-giftcard'] = 'WC_Product_Variable_Data_Store_CPT';
    return $stores;
}
*/

/*
add_action( 'woocommerce_product_data_panels', 'artisanoscope_custom_product_options_product_tab_content' );
function artisanoscope_custom_product_options_product_tab_content() {
 
    //echo('<div id="workshop-product-options" class="panel woocommerce_options_panel"></div>');

?><div id='demo_product_options' class='panel woocommerce_options_panel'><?php
?><div class='options_group'><?php

woocommerce_wp_checkbox( array(
'id' => '_enable_custom_product',
'label' => __( 'Enable Custom product Type'),
) );

woocommerce_wp_text_input(
array(
'id' => 'demo_product_info',
'label' => __( 'Custom Product details', 'dm_product' ),
'placeholder' => '',
'desc_tip' => 'true',
'description' => __( 'Enter Demo product details.', 'dm_product' ),
'type' => 'text'
)
);

?></div>
</div><?php
}
*/

// Ajoute la checkbox "Workshops"
/*
function filter_product_type_options( $product_type_options ) { 
    $product_type_options['workshops'] = array(
        'id'            => '_workshops',
        'wrapper_class' => 'hide_if_simple hide_if_variable show_if_workshop',
        'label'         => __( 'Ateliers', 'workshops' ),
        'description'   => __( 'Les ateliers', 'workshops' ),
        'default'       => 'yes',
    );

    return $product_type_options;
}
add_filter( 'product_type_options', 'filter_product_type_options', 10, 1 );
*/

/**
 * Show pricing fields for simple_rental product.
 */
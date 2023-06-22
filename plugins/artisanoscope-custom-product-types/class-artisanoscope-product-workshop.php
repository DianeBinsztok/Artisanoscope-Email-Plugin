<?php

//add_action( 'plugins_loaded', 'artisanoscope_register_workshop_product_class' );
class WC_Product_Workshop extends WC_Product_Variable {

    public function __construct( $product ) {
    $this->product_type = 'workshop';
    parent::__construct( $product );
    $this->data['attributes'] = $this->get_attributes();
    }

    public function get_type(){
        return 'workshop';
    }

        // Ajouter les hooks pour inclure les templates nécessaires pour afficher les options de variation
        /*
        add_action('woocommerce_workshop_add_to_cart', array($this, 'add_workshop_variations_template'));
        }
        */

    /*
        public function add_to_cart_url() {
            $url = $this->is_purchasable() && $this->is_in_stock() ? remove_query_arg( 'added-to-cart', add_query_arg( 'add-to-cart', $this->id ) ) : get_permalink( $this->id );
            return apply_filters( 'woocommerce_product_add_to_cart_url', $url, $this );
        }
    */
    /*
        public function add_workshop_variations_template() {
            // Inclure le template pour les options de variation, en utilisant le même hook que la classe parente
            wc_get_template('single-product/add-to-cart/variable.php');
        }
            */
}


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

// Ajout du type de produit aux options des données de produit
add_filter( 'product_type_selector', 'artisanoscope_add_workshop_product_type' );
function artisanoscope_add_workshop_product_type( $types ){
    // ajout de la clé 'workshop' et sa valeur au tableau de $types.
    $types[ 'workshop' ] = __( 'Artisanoscope - Atelier ponctuel', 'workshop' );
    return $types; 
}



// Ajout de l'onglet "Artisanoscope - Atelier ponctuel"
add_filter( 'woocommerce_product_data_tabs', 'artisanoscope_workshop_product_tab' );
function artisanoscope_workshop_product_tab( $tabs) {
//https://stackoverflow.com/questions/67341234/woocommerce-custom-product-type-option-not-hiding-custom-product-tab

    //Ajouter l'onglet "Artisanoscope - Atelier ponctuel" si le produit Atelier est sélectionné
    $tabs['workshop'] = array(
        'label' => __( 'Artisanoscope - Atelier ponctuel', 'workshop' ),
        'target' => 'workshop_product_options',
        'class' => array( 'hide_if_simple', 'hide_if_variable','show_if_workshop'),
    );

    //Enlever l'onglet "expédition" pour les ateliers
    array_push($tabs['shipping']['class'], "hide_if_workshop");
    array_push($tabs['variations']['class'], "show_if_workshop", "show_if_training");

    return $tabs;
}


//Champs du type de produit "Workshop"
add_action( 'woocommerce_product_data_panels', 'artisanoscope_workshop_tab_enable_custom_product_type' );
function artisanoscope_workshop_tab_enable_custom_product_type() {
    echo("<div id='workshop_product_options' class='panel woocommerce_options_panel'>");
    woocommerce_wp_checkbox( array(
        'id' 		=> '_enable_workshop_option', 
        'value' 		=> 'yes', 
        'label' 	=> __( 'Atelier', 'woocommerce' ),
    ) );
    echo("</div>");
}

add_action('woocommerce_process_product_meta', 'artisanoscope_save_workshop_product_meta', 10, 1);
function artisanoscope_save_workshop_product_meta($post_id) {
    if (isset($_POST['_enable_workshop_option'])) {
        update_post_meta($post_id, '_enable_workshop_option', 'yes');
        wp_set_object_terms($post_id, 'workshop', 'product_type', false);
    } else {
        update_post_meta($post_id, '_enable_workshop_option', 'no');
    }
}

/**
 * Ajoute des nouvelles clés au tableau $stores
 * @param array $stores
 * @return array
 */
add_filter( 'woocommerce_data_stores', 'artisanoscope_save_custom_product_type_in_db', 10, 1 );
function artisanoscope_save_custom_product_type_in_db( $stores ) {
    //$stores['product-workshop'] = 'WC_Product_Variable_Data_Store_CPT';
    $stores['workshop'] = 'WC_Product_Variable_Data_Store_CPT';
    return $stores;
}


//TRASH

/*
add_action( 'admin_footer', 'artisnoscope_show_pricing_fields' );
function artisnoscope_show_pricing_fields() {

	if ( 'product' != get_post_type() ) :
		return;
	endif;

	?><script type='text/javascript'>
		jQuery( document ).ready( function() {
			jQuery( '.options_group.pricing' ).addClass( 'show_if_workshop' ).show();
		});
	</script>
    <?php
}
*/

/*
add_filter('woocommerce_dropdown_variation_attribute_options_html', 'artisanoscope_workshop_display_date_options', 10, 3);
// Pour les dates d'ateliers ponctuels
function artisanoscope_workshop_display_date_options($html, $args){
	global $product;
	global $svg;
    echo("OK");

	if($product->get_type() == "workshop"){
		echo("OK");
	}
    
	// Vérifier que l'attribut est "Date"
	//var_dump($args);
	
	if ($args['attribute'] === 'pa_date') {
	
		$dates ='';
		$variations = $product->get_available_variations();
		if($variations){
            var_dump($variations);
        }

		foreach($variations as $variation){

			// Tarif enfant ou pas?
			$pricings = "";
			if(!empty($variation["children_pricing"])){

				$pricings = '
				<button style="background-color:orange;" class="artisanoscope-product-option-price"> Adulte: '.$variation["price"].'</button>
				<button style="background-color:orange;" class="artisanoscope-product-option-price"> Enfant: '.$variation["children_pricing"].'</button>
				';

			}else{
				$pricings = '<p class="artisanoscope-product-option-availabilities">'.$variation["price"].'</p>';
			}

			// Tarifs selon date ou pas?
			$dates .= '
			<div href="#" class="artisanoscope-product-option" name="date" id="'.$variation["variation_id"].'">
				<div class="artisanoscope-product-option-line">
				'.$svg["date"].'
				<h3 class="artisanoscope-product-option-title artisanoscope-product-option-line">'.$variation["date"].'</h3>
				</div>
				<div class="artisanoscope-product-option-line">
				'.$svg["hours"].'
					<p class="artisanoscope-product-option-hours">De '.$variation["start_hour"].' à '.$variation["end_hour"].'</p>
				</div>
				<div class="artisanoscope-product-option-line">
				'.$svg["location"].'
					<p class="artisanoscope-product-option-location">'.$variation["location"].'</p>
				</div>
				<div class="artisanoscope-product-option-line">
				'.$svg["people"].'
					<p class="artisanoscope-product-option-availabilities"><span class="stock in-stock artisanoscope-single-product-info-availabilities">'.$variation["availabilities"].'places </span> disponibles</p>
				</div>
				<div class="artisanoscope-product-option-line">
				'.$svg["price"].''.$pricings.'
				</div>
			</div>';
		}
		
		$html= '
		<section id="date-options" class="artisanoscope-product-options-container" name="attribute_date" data-attribute_name="attribute_date">
		'.$dates.'
		</section>
		';
	}
	wp_enqueue_script("chooseDateOptionAndAddToCart", get_stylesheet_directory_uri().'/assets/js/artisanoscopeSingleProductScripts.js');

	return $html;
}
*/
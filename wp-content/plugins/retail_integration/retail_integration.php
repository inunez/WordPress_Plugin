<?php

/**
 * Plugin Name: Retail Integration
 * Plugin URI: http://www.superatic.com/retailintegration/
 * Description: An e-commerce - retail integration plugin.
 * Version: 0.1
 * Author: SuperAtic
 * Author URI: http://superatic.com
 * Requires at least: 4.1
 * Tested up to: 4.4
 *
 * Text Domain: retail_integration
 * Domain Path: /i18n/languages/
 *
 * @package RetailIntegration
 * @category Integration
 * @author SuperAtic
 */
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

class RetailIntegration {
    
    private $payment_method_table = 'retail_integration_payment_method';
    /**
     * Hook onto all of the actions and filters needed by the plugin.
     */
    protected function __construct() {
        
        //add_action('init', array($this, 'mami'));

        $path_file = plugin_basename(__FILE__);

        register_activation_hook(__FILE__, 'superatic_ei_activation');
        add_action('superatic_ei_daily_event', 'superatic_ei_process_daily_events');

        register_deactivation_hook(__FILE__, 'superatic_ei_deactivation');
     
        add_action( 'woocommerce_product_options_sku',  array($this, 'superatic_ei_productIdFields') );
        add_action('init', array($this, 'superatic_ei_createTables'));
        add_action('admin_menu', array($this, 'superatic_ei_createPluginMenu'));
        
        //add_action('init', array($this, 'superatic_ei_registerPaymentTaxonomy'));
        
        //add_action('init', array($this, 'superatic_ei_receiveProducts'));
        //add_action('init', array($this, 'superatic_ei_processOrders'));

    }
    
    function mami(){
        //Esta funcion imprime la edad de Matias
        //echo $titulo . $matias . ' al dia de ' . $hoy;
        
//        $matias = 8;
//        $titulo = 'La edad de Matias es: ';
//        $hoy = '16/02/2016';
//        
//        echo $titulo . $matias . ' al dia de ' . $hoy;
//        
//        $matias = $matias * 2;
//        
//        echo $titulo . $matias . ' al dia de ' . $hoy;
        
        $Mensaje1 = 'mi mama tiene 35 años ';
        $Mensaje2 = 'es la mejor mama en el mundo';
        echo $Mensaje1 . $Mensaje2;
        
        die;
               
    }
    
    function superatic_ei_createPluginMenu() {
        add_menu_page('Retail Integration', 'Retail Integration Settings', 'manage_options', 'ei_settings', array($this, 'superatic_ei_showSettings')); 
    }
    
    function superatic_ei_showSettings() {
       ?>
            <div id="settings" class="wrap">
                <h2>Retail integration Settings
            </div>
       <?php
    }
    
    
    function superatic_ei_createTables() {
        global $wpdb;

        $table_name = $wpdb->prefix . $this->payment_method_table;
        
        $charset_collate = $wpdb->get_charset_collate();

        $sql = "CREATE TABLE $table_name (
            key_id                  mediumint(9)    NOT NULL AUTO_INCREMENT,
            payment_method_id       smallint(3)     NOT NULL,
            method_name             varchar(100)    NOT NULL,
            enabled                 tinyint(1)      NOT NULL,
            mandatory               tinyint(1)      NOT NULL,
            loyalty_enabled         tinyint(1)      NOT NULL,
            POS_enabled             tinyint(1)      NOT NULL,
            loyalty_ratio           varchar(100)    DEFAULT '0.00' NOT NULL,
            UNIQUE KEY id (key_id)
          ) $charset_collate;";

        require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
        dbDelta($sql);
    }

    function superatic_ei_registerPaymentTaxonomy(){
        
        if (taxonomy_exists('pos_payment_option')){
            return;
        }
        
        $plural = 'P.O.S. Payment Types';
        $singular = 'P.O.S. Payment Type';
        
        $labels = array(
            'name'              => $plural,
            'singular_name'     => $singular,
            'search_items'      => __( 'Search ' . $plural ),
            'search_items'      => __( 'Search ' . $plural ),
            'view'              => __( 'View ' . $singular ),
            'view_item'         => __( 'View ' . $singular ),
            'all_items'         => __( 'All ' . $plural ),
            'edit_item'         => __( 'Edit ' . $singular ),
            'update_item'       => __( 'Update ' . $singular ),
            'add_new_item'      => __( 'Add New ' . $singular ),
            'new_item_name'     => __( 'New ' . $singular ),
            'menu_name'         => __( $plural ),
            'not_found'         => 'No ' . $plural . ' found',
            'not_found_in_trash' => 'No ' . $plural . ' in trash',
        );
        
        $args = array (
            'hierarchical'      => false,
            'labels'            => $labels,
            'show_ui'           => true,
            'show_admin_column' => true,
            'update_count_callback' => '',
            'query_var'             => true,
            'rewrite'               => array('slug' => 'pos_payment_type'),
            'description'           => 'List of payments options from the P.O.S.',
        );
        register_taxonomy('pos_payment_option', 'product', $args);
    }
        
    function superatic_ei_productIdFields() {
        woocommerce_wp_text_input(array(
            'id'    => '_pos_product_id', 
            'description' => __( 'Product Id on the Point of Sale', 'woocommerce' ),
            'type' => 'number',
            'desc_tip' => 'true',
            //'class' => 'wc_input_price short', 
            'readonly' => 'readonly',
            'custom_attributes' => array(
                'step'  => 'any',
                'min'   => '0',
                'readonly' => 'readonly'
            ), 
            'label' => __('P.O.S. Product ID', 'woocommerce')));
    }

    function superatic_ei_soapDebug($client, $exp) {
        if ($client != null) {
            $requestHeaders = $client->__getLastRequestHeaders();
            $request = ($client->__getLastRequest());
            $responseHeaders = $client->__getLastResponseHeaders();
            $response = ($client->__getLastResponse());

            echo '<code>' . nl2br(htmlspecialchars($requestHeaders, true)) . '</code>';
            echo highlight_string($request, true) . "<br/>\n";

            echo '<code>' . nl2br(htmlspecialchars($responseHeaders, true)) . '</code>' . "<br/>\n";
            echo highlight_string($response, true) . "<br/>\n";
        } else {
            echo "Message: " . $exp->faultstring . "<br />";
            echo "Error Code: " . $exp->faultcode . "<br />";
            echo "Line: " . $exp->getLine() . "<br />";
        }
        die;
    }

    function superatic_ei_receiveProducts() {
        $usr = 'wsi';
        $password = 'wsipass';
        $clientID = '9bf6dd42-35b9-46dd-948a-1c3c91906caa';
        $url = "http://v2wsisandbox.retailexpress.com.au/dotnet/admin/webservices/v2/webstore/service.asmx?wsdl";

//        $usr = 'DEV';
//        $password = 'nJDonVpA';
//        $clientID = 'da7aa99b-42b1-4576-86d5-727f7ac61f7c';
//        $url = "http://fashionbar.retailexpress.com.au/dotnet/admin/webservices/v2/webstore/service.asmx?wsdl";
        
        $mode = array(
            'soap_version' => 'SOAP_1_1', // use soap 1.1 client
            'keep_alive' => true,
            'trace' => 1,
            'encoding' => 'UTF-8',
            'compression' => SOAP_COMPRESSION_ACCEPT | SOAP_COMPRESSION_GZIP | SOAP_COMPRESSION_DEFLATE,
            'exceptions' => true,
            'cache_wsdl' => WSDL_CACHE_NONE,
        );
        try {
            $client = new SoapClient($url, $mode);

            $auth = array(
                'UserName' => $usr,
                'Password' => $password,
                'ClientID' => $clientID,
            );
            $header = new SoapHeader('http://retailexpress.com.au/', 'ClientHeader', $auth, false);
            $client->__setSoapHeaders($header);
            
            //IAN todo get the date last update
            $res = $client->ProductsGetBulkDetailsByChannel(array('LastUpdated' => '2000-01-01T00:00:00.000Z', 'ChannelId' => 1,));
//            $res = $client->ProductsGetBulkDetails(array('LastUpdated' => '2000-01-01T00:00:00.000Z', 'ChannelId' => 1,));
        } catch (SoapFault $exp) {
            if (strpos($exp->faultstring, 'no XML document') !== false) {
                $unzipResponse = gzdecode($client->__getLastResponse());
                $xml_response = new SimpleXMLElement($unzipResponse);
                $this->superatic_ei_processProducts($xml_response);
            } else {
                $this->superatic_ei_soapDebug($client, $exp);
            }
        }

    }

    public function superatic_ei_processProducts($xml) {
        //global $woocommerce;
        global $wpdb;
        
        $products = $xml->Products;
        $colours = $xml->Attributes->Colours;
        $sizes = $xml->Attributes->Sizes;
        $seasons = $xml->Attributes->Seasons;
        $paymentMethods = $xml->Attributes->PaymentMethods;
        
//        var_dump ($sizes);
//        die;
        
        if (!taxonomy_is_product_attribute('pa_colour')) {
            $this->superatic_ei_insertAttribute('Colour');
        }
        
        if (!taxonomy_is_product_attribute('pa_size')) {
            $this->superatic_ei_insertAttribute('Size');
        }
         
        if (!taxonomy_is_product_attribute('pa_season')) {
            $this->superatic_ei_insertAttribute('Season');
        }
        
        //if (taxonomy_exists('pos_payment_option')){
        $table_name = $wpdb->prefix . $this->payment_method_table;

        foreach($paymentMethods->PaymentMethod as $paymentMethod){

            $arrPaymentMethods = $wpdb->get_results('SELECT payment_method_id FROM ' . $table_name . ' WHERE payment_method_id = ' . $paymentMethod->ID);

            if (!$arrPaymentMethods) {
                $wpdb->insert(
                    $table_name, array(
                        payment_method_id => $paymentMethod->ID,
                        method_name => $paymentMethod->Name,
                        enabled => $paymentMethod->Enabled,
                        mandatory => $paymentMethod->Mandatory,
                        loyalty_enabled => $paymentMethod->LoyaltyEnabled,
                        POS_enabled => $paymentMethod->POSEnabled,
                        loyalty_ratio => $paymentMethod->LoyaltyRatio,
                    )
                );
            }

        }
        //}
        
        foreach($sizes->Size as $size){
            $exists = term_exists($size->SizeName, 'pa_size');
            if ($exists=='' || $exists == null) {
                $this->superatic_ei_insertAttributeTerm('pa_size', $size->SizeName);
            }
        }
        
        foreach($colours->Colour as $colour){
            $exists = term_exists($colour->ColourName, 'pa_colour');
            if ($exists == '' || $exists == null) {
                $this->superatic_ei_insertAttributeTerm('pa_colour', $colour->ColourName);
            }
        }
        
        foreach($seasons->Season as $season){
            $exists = term_exists($season->SeasonName, 'pa_season');
            if ($exists == '' || $exists == null) {
                $this->superatic_ei_insertAttributeTerm('pa_season', $season->SeasonName);
            }
        }
        
        $cont = 0;
        
        foreach($products->Product as $product){
            $cont += 1;
            //echo 'Product No: ' . $cont;
            //var_dump ($product);
            
            $search = $sizes->xpath('//Size/SizeId[.="' . $product->SizeId . '"]/parent::*');
            $sizeName = (string) $search[0]->SizeName;
            $search = $colours->xpath('//Colour/ColourId[.="' . $product->ColourId . '"]/parent::*');
            $colourName = (string) $search[0]->ColourName;

            $this->superatic_ei_addProduct($product, $sizeName, $colourName);
            if ($cont == 3) {
                break;
            }
        }

    }

    function superatic_ei_insertAttributeTerm($tax, $term){
        $insert = wp_insert_term( $term, $tax,  
                array(
                    'description'=> $term,
                    'slug' => strtolower($term),
                )
            );
        
        if ( is_wp_error( $insert ) ) {
            throw new WC_API_Exception( 'woocommerce_api_cannot_create_product_attribute', $insert->get_error_message(), 400 );
        }
    }
    
    function superatic_ei_insertAttribute($attribute){
        global $wpdb; 

        $insert = $wpdb->insert(
            $wpdb->prefix . 'woocommerce_attribute_taxonomies',
            array(
                'attribute_label'   => $attribute,
                'attribute_name'    => strtolower($attribute),
                'attribute_type'    => 'select',
                'attribute_orderby' => 'name',
                'attribute_public'  => 1
            ),
            array( '%s', '%s', '%s', '%s', '%d' )
        );

        if ( is_wp_error( $insert ) ) {
            throw new WC_API_Exception( 'woocommerce_api_cannot_create_product_attribute', $insert->get_error_message(), 400 );
        }

        // Clear transients
        delete_transient( 'wc_attribute_taxonomies' );
    }

    public function superatic_ei_addProduct($product, $sizeName, $colourName) {
        global $wpdb;
        
        $size_tax = wc_attribute_taxonomy_name( 'Size' );
        $colour_tax = wc_attribute_taxonomy_name( 'Colour' );

        //$cats = array(25);
        //$insertLog = "insert_product_logs.txt"; //name the log file in wp-admin folder
        
        //Check if product already exists
        $productExists = $this->productExist("_sku", (string) $product->Code);
        
        if ($productExists == null){
            $post = array(
                'post_title' => (string) $product->Description,
                'post_content' => (string) $product->Description,
                'post_status' => "publish",
                'post_excerpt' => (string) $product->Description,
                'post_name' => (string) $product->Description,
                'post_type' => "product"
            );

            //Create product/post:
            try {
                $product_id = wp_insert_post($post, $wp_error);
            } catch (Exception $exp) {
                echo "Message: " . $exp->faultstring . "<br />";
                echo "Error Code: " . $exp->faultcode . "<br />";
                echo "Line: " . $exp->getLine() . "<br />";
            }

            //add category to product:
            wp_set_object_terms($product_id, 'Retail Product', 'product_cat');

            update_post_meta($product_id, '_visibility', 'visible');
            update_post_meta($product_id, '_weight', (string) $product->Freight);
            update_post_meta($product_id, '_sku', (string) $product->Code);
            update_post_meta($product_id, '_pos_product_id', (string) $product->ProductId);

        }else{
            $product_id = $productExists;
        }

        //###################### Add Variation post types for sizes #############################

        //Check id produc has variations
        if ((string)$product->SKU === (string)$product->Code){
            //No variations
            update_post_meta($product_id, '_stock_status', ($product->StockAvailable > 0 ? 'instock' : 'outofstock'));
            update_post_meta($product_id, '_manage_stock', 'yes');
            update_post_meta($product_id, '_stock', (integer) $product->StockAvailable);       
            update_post_meta($product_id, '_price', (double) $product->DiscountedPrice);
            update_post_meta($product_id, '_regular_price', (double) $product->WebSellPrice);
            wp_set_object_terms($product_id, 'simple', 'product_type', false);
        }else{
            //Variations
            // Assign sizes and colours to the main product
            if ($productExists == null) {
                $termSizes = array();
                $termColours = array();

                $terms = get_terms($size_tax, array('hide_empty' => false, 'hierarchical' => false));
                foreach ($terms as $term) {
                    array_push($termSizes, $term->name);
                }

                $terms = get_terms($colour_tax, array('hide_empty' => false, 'hierarchical' => false));
                foreach ($terms as $term) {
                    array_push($termColours, $term->name);
                }

                // Insert the attributes (I will be using size and colour for variations)
                $attributes = array(
                    $size_tax => array(
                        'name' => $size_tax,
                        'value' => '',
//                    'value' => $termSizes,
                        'position' => 0,
                        'is_visible' => '1',
                        'is_variation' => '1',
                        'is_taxonomy' => '1'
                    ),
                    $colour_tax => array(
                        'name' => $colour_tax,
                        'value' => '',
//                    'value' => $termColours,
                        'position' => 0,
                        'is_visible' => '1',
                        'is_variation' => '1',
                        'is_taxonomy' => '1'
                    )
                );
                update_post_meta($product_id, '_product_attributes', $attributes);
                update_post_meta($product_id, '_manage_stock', 'no');
                update_post_meta($product_id, '_backorders', 'no');
                update_post_meta($product_id, '_downloadable', 'no');
                
                wp_set_object_terms($product_id, $termSizes, $size_tax);
                wp_set_object_terms($product_id, $termColours, $colour_tax);
                //make product type be variable:
                wp_set_object_terms($product_id, 'variable', 'product_type', false);
            }

            //Check if variation already exists
            $variationExists = $this->productExist("_sku", (string) $product->SKU);
        
            //Check if variations already exists
            if ($variationExists == null) {
                $variation = array(
                    'post_title' => (string) $product->Description . ' ' . $colourName . ' ' . $sizeName,
                    'post_name' => (string) $product->Description . ' ' . $colourName . ' ' . $sizeName,
                    'post_status' => 'publish',
                    'post_parent' => $product_id, //post is a child post of product post
                    'post_type' => 'product_variation', //set post type to product_variation
                    'guid' => home_url() . '/?product_variation=product-' . $product_id . '-variation-' . $colourName . '-' . $sizeName
                );

                //Insert ea. post/variation into database:
                $variation_id = wp_insert_post($variation);

                //Create variation for ea product_variation:
                
                $price = (double) $product->DiscountedPrice;
                update_post_meta($variation_id, '_price', $price);
                if ((double)$product->WebSellPrice > 0){
                    $price = (double)$product->WebSellPrice;
                }    

                update_post_meta($variation_id, '_regular_price', $price);
                update_post_meta($variation_id, '_manage_stock', 'yes');
                update_post_meta($variation_id, '_stock', (integer) $product->StockAvailable);
                update_post_meta($variation_id, '_backorders', 'no');
                update_post_meta($variation_id, '_downloadable', 'no');       
                update_post_meta($variation_id, '_virtual', 'no'); 
                update_post_meta($variation_id, '_thumbnail_id', 0); 
                update_post_meta($variation_id, '_stock_status', ($product->StockAvailable > 0 ? 'instock' : 'outofstock'));
                update_post_meta($variation_id, '_sku', (string) $product->SKU);
                
                //update_post_meta($variation_id, '_product_attributes', $attributes);
                wp_set_object_terms($variation_id, $termSizes, $size_tax);
                wp_set_object_terms($variation_id, $termColours, $colour_tax);
                
                update_post_meta($variation_id, 'attribute_' . $size_tax, strtolower($sizeName));
                update_post_meta($variation_id, 'attribute_' . $colour_tax, strtolower($colourName));
            
                //############################ Done adding variation posts ############################

                WC_Product_Variable::sync($product_id);
                
//                $transient_name = 'wc_product_children_ids_' . $product_id;
//                delete_transient( $transient_name );
            }
        }

        //IAN TODO
        //Send email to admin for new product (images)
    }

    /**
     * @access public
     * @param  string   $sku            SKU code
     * @return mixed    $product_id     Return product ID if exist, false if not
     */
    public function productExist( $key, $value ) {
        global $wpdb;
        $product_id = $wpdb->get_var( $wpdb->prepare( "SELECT post_id FROM $wpdb->postmeta WHERE meta_key= %s AND meta_value= %s LIMIT 1", $key, $value ) );
        return $product_id;
    }
    
    function superatic_ei_processOrders(){
        //$this->superatic_ei_receiveProducts();
        $filters = array(
            'post_status' => 'any',
            'post_type' => 'shop_order',
            'posts_per_page' => 200,
            'paged' => 1,
            'orderby' => 'modified',
            'order' => 'ASC'
        );

        $loop = new WP_Query($filters);

        while ($loop->have_posts()) {
            $loop->the_post();
            $order = new WC_Order($loop->post->ID);

            $customer = get_userdata($order->customer_user);
            
            $xml = new SimpleXMLElement('<xml/>');
            $orders =  $xml->addChild('Orders');
            $orderXML = $orders->addChild('Order');
            $orderXML->addChild('ExternalOrderId', $order->get_order_number());
            $orderDate = date('c', strtotime( $order->order_date));
            $orderXML->addChild('DateCreated', $orderDate);
            $orderXML->addChild('OrderTotal', $order->get_total()); //*
            $orderXML->addChild('FreightTotal', $order->get_shipping_method());
            $orderXML->addChild('OrderStatus', 'Processed');//*
            $orderXML->addChild('PublicComments', 'Order sent from ecommerce');
            $orderXML->addChild('CustomerId', '');
            $orderXML->addChild('ExternalCustomerId', $customer->ID);
            $orderXML->addChild('Password', 'password123');
            $orderXML->addChild('BillFirstName', $order->billing_first_name);//*
            $orderXML->addChild('BillLastName', $order->billing_last_name);
            $orderXML->addChild('BillAddress', $order->billing_address_1);
            $orderXML->addChild('BillAddress2', $order->billing_address_2);
            $orderXML->addChild('BillCompany', $order->billing_company);
            $orderXML->addChild('BillAcn', $order->billing_company);
            $orderXML->addChild('BillMobile', $order->billing_phone);
            $orderXML->addChild('BillPhone', $order->billing_phone);
            $orderXML->addChild('BillPostCode', $order->billing_postcode);
            $orderXML->addChild('BillState', $order->billing_state);
            $orderXML->addChild('BillSuburb', $order->billing_city);
            $orderXML->addChild('BillCountry', $order->billing_country);
            $orderXML->addChild('BillEmail', $order->billing_email);//*
            $orderXML->addChild('DelName', $order->shipping_first_name);
            $orderXML->addChild('DelAddress', $order->shipping_address_1);//*
            $orderXML->addChild('DelAddress2', $order->shipping_address_2);
            $orderXML->addChild('DelCompany', $order->shipping_company);
            $orderXML->addChild('DelMobile', '');
            $orderXML->addChild('DelPhone', '');
            $orderXML->addChild('DelPostCode', $order->shipping_postcode);//*
            $orderXML->addChild('DelSuburb', $order->shipping_city);//*
            $orderXML->addChild('DelState', $order->shipping_state);//*
            $orderXML->addChild('DelCountry', $order->shipping_country);
            $orderXML->addChild('CustomerPONumber', '');
            $orderXML->addChild('CustomerReference', '');
            $orderXML->addChild('ReceivesNews', 'Yes');//*
            $orderXML->addChild('PublicComments', 'Order sent from ecommerce');
            $orderXML->addChild('PrivateComments','Woocommerce to Retail Express');
            $orderXML->addChild('FullfilmentOutletId', '');
            
            $items = $orderXML->addChild('OrderItems');            
            foreach ($order->get_items() as $lineItem) {
//                var_dump($lineItem);
//                die;
                $product = new WC_Product($lineItem['product_id']);
                $posProductId = get_post_meta( $lineItem['product_id'], '_pos_product_id', true );
                $itemXML = $items->addChild('OrderItem');
                $itemXML->addChild('ProductId',$posProductId);
                $itemXML->addChild('QtyOrdered',$lineItem['qty']);//*
                $itemXML->addChild('QtyFulfilled',$lineItem['qty']);//*
                $itemXML->addChild('UnitPrice',$lineItem['line_subtotal']/$lineItem['qty']);//*
                //IAN todo get tax from woocommerce settings
                $_tax = new WC_Tax(); 
                $rates = array_shift($_tax->get_rates( $product->get_tax_class()));
                if (isset($rates['rate'])) { //vat found
                    $rate = $rates['rate'];
                }
                $itemXML->addChild('TaxRateApplied',$rate);//*
//                $itemXML->addChild('DeliveryDueDate',$lineItem['product_id']);//*
//                $itemXML->addChild('DeliveryMethod',$lineItem['product_id']);
//                $itemXML->addChild('DeliveryDriverId',$lineItem['product_id']);
//                $itemXML->addChild('DeliveryDriverName',$lineItem['product_id']);
//                $itemXML->addChild('Reference',$lineItem['product_id']);
                
            }

            $payments = $orderXML->addChild('OrderPayments');
            $paymentXML = $payments->addChild('OrderPayment');
            //IAN TODO search woocommerce payment method in a cross table against P.O.S. payment method table
            $paymentXML->addChild('MethodId',9);//*
            $paymentXML->addChild('Amount',$order->order_total);//*
            $paymentXML->addChild('VoucherCode','');
            $paymentXML->addChild('DateCreated',$orderDate);
  
//            Header('Content-type: text/xml');
//            print($xml->asXML());
//            die;
//            
            if ($this->sendOrder($xml)){
                //update post order id status
                
            }
            
//            $billing_address = $order->get_billing_address();
//            $billing_address_html = $order->get_formatted_billing_address(); // for printing or displaying on web page
//            $shipping_address = $order->get_shipping_address();
//            $shipping_address_html = $order->get_formatted_shipping_address();
//            
//            $customer = get_userdata($order->customer_user);
            //echo $customer->display_name . ' - '. $billing_address;

        }
        
        //die;
    }
    
    function sendOrder($xmlOrder){
        $usr = 'wsi';
        $password = 'wsipass';
        $clientID = '9bf6dd42-35b9-46dd-948a-1c3c91906caa';
        $url = "http://v2wsisandbox.retailexpress.com.au/dotnet/admin/webservices/v2/webstore/service.asmx?wsdl";

        $mode = array(
            'soap_version' => 'SOAP_1_1', // use soap 1.1 client
            'keep_alive' => true,
            'trace' => 1,
            'encoding' => 'UTF-8',
            'compression' => SOAP_COMPRESSION_ACCEPT | SOAP_COMPRESSION_GZIP | SOAP_COMPRESSION_DEFLATE,
            'exceptions' => true,
            'cache_wsdl' => WSDL_CACHE_NONE,
        );
        try {
            $client = new SoapClient($url, $mode);

            $auth = array(
                'UserName' => $usr,
                'Password' => $password,
                'ClientID' => $clientID,
            );
            $header = new SoapHeader('http://retailexpress.com.au/', 'ClientHeader', $auth, false);
            $client->__setSoapHeaders($header);

            $result = $client->OrderCreateByChannel(array('OrderXML' => $xmlOrder->asXML(), 'ChannelId' => 1,));
            $any = $result->OrderCreateByChannelResult->any;
            $xmlResponse = new SimpleXMLElement($any);
            
            $orderNumber = $xmlResponse->OrderCreate->Order->OrderId;
            
            //Order is created?
            if($orderNumber != null){
                return true;
            }else
            {
                //log error
                return false;
            }
                
        } catch (SoapFault $exp) {
            $this->superatic_ei_soapDebug($client, $exp);
        }
    }

    //Process all the daily events. 
    function superatic_ei_process_daily_events() {
        //superatic_ei_receiveProducts();
    }

    function superatic_ei_activation() {
        /**
         * @todo change local time
         */
        $midnight_timestamp = strtotime(date("Y-m-d") . " 00:00:00 AM");
        wp_schedule_event($midnight_timestamp, 'daily', 'superatic_ei_daily_event');
        /**
         * @todo add setup plugin function
         */
    }

    function superatic_ei_deactivation() {
        wp_clear_scheduled_hook('superatic_ei_daily_event');
    }
    
    public static function init() {
        static $instance;

        if ( ! $instance ) {
                $instance = new RetailIntegration;
        }
        return $instance;
    }

}

/**
 * Check if the woocommerce plugin is installed
 */
if (in_array('woocommerce/woocommerce.php', apply_filters('active_plugins', get_option('active_plugins')))) {

    RetailIntegration::init();
    
} 

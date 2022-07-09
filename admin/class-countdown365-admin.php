<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://countdown365.vuiphor.com/
 * @since      1.0.0
 *
 * @package    Countdown365
 * @subpackage Countdown365/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Countdown365
 * @subpackage Countdown365/admin
 * @author     vuiphor <vuiphor.pvt@gmail.com>
 */
class Countdown365_Admin {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

	    add_action( 'admin_menu', [ $this, 'admin_menu' ] );
	    add_action( 'admin_init', [ $this, 'admin_settings_options' ] );
	    add_action( 'init',  [ $this,'gutenberg_shortcode_block'] );

	    if ( $this->is_woocommerce() && get_option('countdown_enable_on_woocommerce',true) ) {
           add_action( 'add_meta_boxes',  [ $this,'cuntdown365_meta_box'] );
           add_action( 'save_post', [ $this,'countdown365_save_postdata'] );
        } 

	}

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

        wp_register_style( 'jquery-ui', plugin_dir_url( __FILE__ ) . 'css/countdown365-ui.css', array(), $this->version, 'all' );
        wp_enqueue_style( 'jquery-ui' ); 
		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/countdown365-admin.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Countdown365_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Countdown365_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */
        wp_enqueue_script('jquery-ui-core'); 
        wp_enqueue_script( 'wp-color-picker' );
        wp_enqueue_script('jquery-ui-datepicker');
        
		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/countdown365-admin.js', 
			               array( 'jquery' ), $this->version, false );

       

	}

	public function is_woocommerce(){
		$plugin_path = trailingslashit( WP_PLUGIN_DIR ) . 'woocommerce/woocommerce.php';

		if (
		    in_array( $plugin_path, wp_get_active_and_valid_plugins() )
		    || in_array( $plugin_path, wp_get_active_network_plugins() )
		) {
		    return true;
		}
	}

	public function admin_menu()
	{
		global $submenu;

        $capability = 'manage_woocommerce';
        $slug       = 'countdown365';
        $hook = add_menu_page( __( 'Countdown365', 'countdown365' ), 
        	                   __( 'Countdown365', 'countdown365' ),
                                   'manage_options', 
        	                       'countdown365_settings', 
        	                     [ $this, 'countdown365_settings' ],
        	                       'dashicons-clock' , 50 );

        if ( current_user_can( $capability ) )
        {
           
          add_submenu_page( $slug, 
          	                 __('Settings', 'countdown365' ), 
          	                 __('Settings', 'countdown365' ),  
          	                   $capability, 
          	                   $slug, [ $this, 'countdown365_settings' ]);
          

        }

        
	}

    public function gutenberg_shortcode_block(){
    	if ( ! function_exists( 'register_block_type' ) ) {
             return;
        }

        wp_enqueue_script( $this->plugin_name.'gutenberg_block', 
        	               plugin_dir_url( __FILE__ ) . 'js/countdown365-gutenberg-block.js', 
        	               array('wp-blocks'));

    	register_block_type( 'vuiphor/countdown365', array(
    	
                 'editor_script' => $this->plugin_name.'gutenberg_block',
    	));
    }

    public function render_gutenberg_shortcode_block(){

    }


	public function countdown365_settings(){
            
            settings_errors();

            ?>

            <div class="<?php echo esc_attr('wrap');?>">
            <div class="<?php echo esc_attr('countdown365_settings_header_col1');?>" >
            	 <span class="<?php echo esc_attr('dashicons dashicons-clock');?>"></span></div>

			<div class="<?php echo esc_attr('countdown365_settings_header_col2');?>" >
				<?php _e( 'Countdown365 Settings', 'countdown365' ); ?></div>

			<div class="<?php echo esc_attr('countdown365_settings_header_col3');?>">
				  <a href="<?php echo esc_url('https://countdown365.vuiphor.com/')?>" target="_blank">
				  	<?php _e( 'Documentation','countdown365' ); ?></a></div>
            
            <form method="post" action="options.php">
                <?php
                  
                    settings_fields("theme_settings");
                    do_settings_sections("countdown365_settings","theme_settings");
                    submit_button();                    
                   
                ?>         
            </form>
            </div>
        <?php
	}

	public function admin_settings_options(){
		 
        add_settings_section("theme_settings", "", [$this,"theme_settings_callback"], "countdown365_settings" );

        add_settings_field(
	        'countdown_enable_on_woocommerce',
	        __( 'Countdown Enable for WooCommerce', 'countdown365' ),
	        [$this,'countdown_enable_on_woocommerce_callback'],
	        'countdown365_settings',
	        'theme_settings'
	       
         );

         add_settings_field(
	        'countdown_on_shop',
	        __( 'Countdown On Shop', 'countdown365' ),
	        [$this,'countdown_on_shop_callback'],
	        'countdown365_settings',
	        'theme_settings'
	       
         );

         add_settings_field(
	        'countdown_on_shop_location',
	        __( 'Countdown On Shop', 'countdown365' ),
	        [$this,'countdown_on_shop_callback'],
	        'countdown365_settings',
	        'theme_settings'
	       
         );

          add_settings_field(
	        'countdown_on_shop_location',
	        '',
	        [$this,'countdown_on_shop_location_callback'],
	        'countdown365_settings',
	        'theme_settings'
	       
         );

         add_settings_field(
	        'countdown_on_product_details',
	         __('Countdown On Product Details', 'countdown365' ),
	        [$this,'countdown_on_product_details_callback'],
	        'countdown365_settings',
	        'theme_settings'
	       
         );

         add_settings_field(
	        'countdown_on_product_details_location',
	        '',
	        [$this,'countdown_on_product_details_location_callback'],
	        'countdown365_settings',
	        'theme_settings'
	       
         );

          add_settings_field(
	        'countdown365_shortcode',
	         __('countdown365 Shortcode and Block', 'countdown365' ),
	        [$this,'countdown365_shortcode_callback'],
	        'countdown365_settings',
	        'theme_settings'
	       
         );


       
        register_setting("theme_settings", "countdown_enable_on_woocommerce");
        register_setting("theme_settings", "countdown_on_shop");
        register_setting("theme_settings", "countdown_on_shop_location");
        register_setting("theme_settings", "countdown_on_product_details");
        register_setting("theme_settings", "countdown_on_product_details_location");
    
       
	}
	

	public function theme_settings_callback(){
       
    }

    public function countdown_enable_on_woocommerce_callback($args){
    	?>       
        <input type="checkbox" 
               name="countdown_enable_on_woocommerce" 
               value="1" <?php checked(1, get_option("countdown_enable_on_woocommerce"), true) ?> >

        <p><?php _e( ' If the countdown is enable for WooComerce then countdown meta field will be avilable on product. After that, countdown timer will be countdown depending on enable countdown for perticular product.', 'countdown365' ); ?>
        </p>
        <img src="<?php echo esc_url( plugin_dir_url( __FILE__ ).'images/c1.png'); ?>"
             style ="<?php echo esc_attr('width: 150px; padding: 10px 0; display: inline;')?>" >
        <?php
    }

    public function countdown_on_shop_callback($args){

    	
    	
        ?>       
        <input type="checkbox" 
               name="countdown_on_shop" 
               value="1" <?php checked(1, get_option("countdown_on_shop"), true) ?> >

        <p><?php _e( 'Depending on enable, countdown will be visible on shop page. ', 'countdown365' ); ?></p>
        <img src="<?php echo esc_url( plugin_dir_url( __FILE__ ).'images/c2.png'); ?>"
             style ="<?php echo esc_attr('width: 150px; padding: 10px 0; display: inline;')?>" >
        
        <?php
    }

     public function countdown_on_shop_location_callback($args){
       $hooks = [ 
    		         'woocommerce_before_shop_loop_item' => 'Before Shop Loop Item',
    		         'woocommerce_before_shop_loop_item_title' => 'Before Single Product Summary',
    		         'woocommerce_shop_loop_item_title' => 'Before Shop Loop Item Title',
    		         'woocommerce_after_shop_loop_item_title' => 'Shop Loop Item Title',
    		         'woocommerce_after_shop_loop_item' => 'After Shop Loop Item'
    		         
    		         

                 ];

        $optionValue = $this->get_option('countdown_on_shop_location');
    	
    	?>       
       <select name="countdown_on_shop_location">
        	<?php foreach ($hooks as $key => $value): ?>
        		<option value="<?php echo esc_attr( $key ); ?>" <?php $this->countdown365selected($key, $optionValue ); ?> > 
        			<?php echo esc_html( $value );?></option>
        	<?php endforeach; ?>
        </select>
        <?php 
    }


    public function countdown_on_product_details_callback($args){

    	
    	?>       
        <input type="checkbox" name="countdown_on_product_details" value="1" <?php checked(1, get_option("countdown_on_product_details"), true) ?> >
        <?php 
    }

    public function countdown_on_product_details_location_callback($args){
    	$hooks = [ 
    		         'woocommerce_before_single_product' => 'Before Single Product',
    		         'woocommerce_before_single_product_summary' => 'Before Single Product Summary',
    		         'woocommerce_single_product_summary' => 'Single Product Summary',
    		         'woocommerce_product_meta_end' => 'Product Meta End',
    		         'woocommerce_share' => 'Share',
    		         'woocommerce_after_single_product_summary' => 'After Single Product Summary',
    		         'woocommerce_after_single_product' => 'After Single Product',

                 ];

        $optionValue = $this->get_option('countdown_on_product_details_location');
    	?>  

    	<select name="countdown_on_product_details_location">
        	<?php foreach ($hooks as $key => $value): ?>
        		<option value="<?php echo esc_attr( $key ); ?>" <?php $this->countdown365selected($key, $optionValue ); ?> > 
        			<?php echo esc_html( $value );?></option>
        	<?php endforeach; ?>
        </select>
        <?php     
    }

    public function countdown365_shortcode_callback($args){
    	?>
        <p style="<?php echo esc_attr('padding: 10px 0; font-size:12px;') ?>" >[ countdown365 date="" ]</p>
        <p style="<?php echo esc_attr('padding: 10px 0; font-size:12px;') ?>" >[countdown365 enable_on_product_page=""  title="" date=""  language=""  face=""  label=""  background_color="" label_color=""  font_color="" separator_color=""  font_size="" border_radius="" digit_gap=""  dot_size=""  message="" ]</p>

        <img src="<?php echo esc_url( plugin_dir_url( __FILE__ ).'images/c3.png'); ?>" 
             style="<?php echo esc_attr('width: 150px; padding: 10px 0; display: inline;')?>">
    	<?php
    }

   
    public function cuntdown365_meta_box(){
    	
			        add_meta_box(
			            'countdown365_product_meta_box',
			            __( 'Countdown365<em>(optional)</em>', 'countdown365' ),
			            [$this,'countdown365_custom_content'],
			            'product',
			            'normal',
			            'default'
			        );
		

    }
    public function get_metadate($post_id, $key){
    	$key = sanitize_key( $key );
		if( get_post_meta( $post_id, $key, true )){
		    return esc_html( get_post_meta( $post_id, $key, true ) );	
		}else{
                  return null ;
		}
	}

	public function get_option($key){
		$key = sanitize_key( $key );
		if( get_option( $key ) ){
		    return esc_html( get_option( $key ) );	
		}else{
                  return null ;
		}
	}

    public function countdown365_custom_content( $post ){

			$is_enable_countdown365 = $this->get_metadate( $post->ID, 'enable_countdown365_on_single_product');
			$checked = '';
			if ( $is_enable_countdown365 == "yes" ) { $checked = "checked"; } 
			else if ( $is_enable_countdown365 == "no" ) { $checked = ""; } 
			else { $checked="";}
	 
			$title          =  $this->get_metadate( $post->ID, 'countdown365_title') ; 
			$language       =  $this->get_metadate( $post->ID, 'countdown365_label_language' ) ; 
			$face           =  $this->get_metadate( $post->ID, 'countdown365_face'); 
			$label_show     =  $this->get_metadate( $post->ID, 'countdown365_label_show'); 
			$date           =  $this->get_metadate( $post->ID, 'countdown365_date' ); 
			$bg             =  $this->get_metadate( $post->ID, 'countdown365_bg' );
			$label_color    =  $this->get_metadate( $post->ID, 'countdown365_label_color' );  
			$font_color     =  $this->get_metadate( $post->ID, 'countdown365_font_color' );  
			$separator_color = $this->get_metadate( $post->ID, 'countdown365_separator_color' ); 
			$font_size      =  intval( $this->get_metadate( $post->ID, 'countdown365_font_size' )); 
			$border_radius  =  intval( $this->get_metadate( $post->ID, 'countdown365_border_radius' )); 
			$digit_gap      =  intval( $this->get_metadate( $post->ID, 'countdown365_digit_gap' ) ); 
			$dot_size       =  intval( $this->get_metadate( $post->ID, 'countdown365_dot_size' ) );
			$message        =  $this->get_metadate( $post->ID, 'countdown365_message' );


			if ( $language == null ) {
				$language = "English";
			}

			if ( $face == null ) {
				 $face = "DailyCounter";
			}

			if ( $label_show == null ) {
				 $label_show = "show";
			}

			

			if ( $date == null ) {
				 $datetime = new DateTime('tomorrow');
				 $date = $datetime->format('Y/m/d');
			}

			if ( $bg == null ) {
				 $bg = "#d80b0b";
			}

			if ( $label_color == null ) {
				 $label_color = "#000000";
			}

			if ( $font_color == null ) {
				 $font_color = "#FFFFFF";
			}

			if ( $separator_color == null ) {
				 $separator_color = "#000000";
			}
			

			if ( $font_size == null ) {
				 $font_size = 30;
			}

			if ( $border_radius == null ) {
				 $border_radius = 5;
			}

			if ( $digit_gap == null ) {
				 $digit_gap = 2;
			}

			if ( $dot_size == null ) {
				 $dot_size = 4;
			}
		wp_nonce_field( basename(__FILE__), 'countdown_meta_nonce' );		
       ?>
	    <div class='countdown365_metadata'>

		    <div class="<?php echo esc_attr( 'label' ); ?>">
		    	 <?php _e('Enable Countdown', 'countdown365' ) ?>
		         <input type="checkbox" name="enable_countdown365_on_single_product" 
		         id="enable_countdown365_on_single_product" 
		         value="<?php echo esc_attr('yes') ; ?>" <?php echo esc_html( $checked ); ?> />
		    </div> 

		    <div class="<?php echo esc_attr('label'); ?>" 
		    	  for="countdown365_title">
		    	  <?php _e('Countdown Title', 'countdown365' ) ?></div>

		    <div class="<?php echo esc_attr( 'countdown365_meta_in'); ?>">
		    <input type="text" name="countdown365_title" 
		           class="<?php echo esc_attr( 'countdown365_title' ); ?>" 
		           value="<?php echo esc_attr( $title ) ; ?>">
		    </div>

		    <div class="<?php echo esc_attr('label'); ?>" 
		    	 for="countdown365_label_language">
		    	 <?php _e('Label Language', 'countdown365' ) ?></div>

		    <div class="<?php echo esc_attr('countdown365_meta_in'); ?>">
		    <select name="countdown365_label_language" 
		              id="<?php echo esc_attr('countdown365_label_language'); ?>" 
		              class="<?php echo esc_attr('field');?>">
				        <?php foreach ($this->countdown365_get_languages() as $key => $value): ?> {
				        <option value="<?php echo esc_attr($key); ?>" 
				        	<?php $this->countdown365selected($key, $language ); ?> >
				        	<?php echo esc_html( $value ) ; ?></option>
				        <?php endforeach ; ?>
		    </select>
		    </div>

		    <div class="<?php echo esc_attr('label'); ?>" 
		         for="countdown365_face"><?php _e('Countdown Face', 'countdown365' ) ?></div>
		    <div class="<?php echo esc_attr('countdown365_meta_in'); ?>">
		    <select name="countdown365_face" 
		            id="<?php echo esc_attr('countdown365_face'); ?>" 
		            class="<?php echo esc_attr('field'); ?>">
		            <?php foreach ($this->countdown365_get_faces() as $key => $value): ?> {
		            <option value="<?php echo esc_attr( $key ); ?>" 
		        	<?php $this->countdown365selected($key, $face ); ?> ><?php echo esc_html( $value ) ; ?></option>
		            <?php endforeach ; ?>
		    </select>
		    </div>


		    <div class="<?php echo esc_attr('label'); ?>" 
		         for="countdown365_label_show"><?php _e('Countdown Label', 'countdown365' ) ?></div>
		    <div class="<?php echo esc_attr('countdown365_meta_in'); ?>">
		    <select name="countdown365_label_show" 
		            id="<?php echo esc_attr('countdown365_label_show');?>" 
		            class="<?php echo esc_attr('field'); ?>">
			        <?php foreach ($this->countdown365_get_label_show() as $key => $value): ?> {
			         <option value="<?php echo $key; ?>" 
			         	<?php $this->countdown365selected($key, $label_show ); ?> ><?php echo $value ; ?></option>
			        <?php endforeach ; ?>
		    </select>
		    </div>

		    <div class="<?php echo esc_attr('label'); ?>" 
		    	for="countdown365_date">
		    	<?php _e('Countdown Target Date', 'countdown365' ) ?></div>

		    <div class="<?php echo esc_attr('countdown365_meta_in'); ?>">
		    <input type="text" 
		           name="countdown365_date" 
		           class="<?php echo esc_attr( 'countdown365_shortcode_block_date') ; ?>" 
		           value="<?php echo esc_attr( $date ) ; ?>">
		    </div>

		    <div class="<?php echo esc_attr('label'); ?>" 
		    	 for="countdown365_bg"><?php _e('Countdown Background Color', 'countdown365' ) ?></div>

		    <div class="<?php echo esc_attr('countdown365_meta_in'); ?>">
		    <input type="" 
		           name="countdown365_bg" 
		           class="<?php echo esc_attr('color-field'); ?>" 
		           value="<?php echo esc_attr( $bg )  ; ?>">
		    </div>

		    <div class="<?php echo esc_attr('label'); ?>" 
		    	 for="countdown365_label_color">
		    	 <?php _e('Countdown Label Font Color', 'countdown365' ) ?></div>

		    <div class="<?php echo esc_attr('countdown365_meta_in'); ?>">
		    <input type="" 
		           name="countdown365_label_color" 
		           class="<?php echo esc_attr('color-field'); ?>"  
		           value="<?php echo esc_attr($label_color)  ; ?>" >
		    </div>

		    <div class="<?php echo esc_attr('label'); ?>" 
		    	 for="countdown365_font_color">
		    	 <?php _e('Countdown Digit Font Color', 'countdown365' ) ?></div>

		    <div class="<?php echo esc_attr('countdown365_meta_in'); ?>">
		    <input type="" 
		           name="countdown365_font_color" 
		           class="<?php echo esc_attr('color-field'); ?>"  
		           value="<?php echo esc_attr($font_color)  ; ?>" >
		    </div>

		    <div class="<?php echo esc_attr('label'); ?>" 
		    	 for="countdown365_separator_color">
		    	 <?php _e('Countdown Separator Color', 'countdown365' ) ?></div>

		    <div class="<?php echo esc_attr('countdown365_meta_in'); ?>">
		    <input type="" 
		           name="countdown365_separator_color" 
		           class="<?php echo esc_attr('color-field'); ?>"  
		           value="<?php echo esc_attr($separator_color)  ; ?>" >
		    </div>

		    <div class="<?php echo esc_attr('label'); ?>" 
		    	 for="countdown365_font_size"><?php _e('Countdown Font Size', 'countdown365' ) ?></div>

		    <div class="<?php echo esc_attr('countdown365_meta_in'); ?>">
		    <input type="number" 
		           name="countdown365_font_size" 
		           class="" 
		           value="<?php echo esc_attr( $font_size )   ; ?>" >
		    </div>

		    <div class="<?php echo esc_attr('label'); ?>" 
		    	 for="countdown365_border_radius">
		    	 <?php _e('Countdown Border Radius', 'countdown365' ) ?></div>


		    <div class="<?php echo esc_attr('countdown365_meta_in'); ?>">
		    <input type="number" 
		           name="countdown365_border_radius" 
		           class="" 
		           value="<?php echo esc_attr( $border_radius )   ; ?>">
		    </div>

		    <div class="<?php echo esc_attr('label'); ?>" 
		    	 for="countdown365_digit_gap"><?php _e('Countdown Digit Gap', 'countdown365' ) ?></div>

		    <div class="<?php echo esc_attr('countdown365_meta_in'); ?>">
		    <input type="number" 
		           name="countdown365_digit_gap" 
		           class="" value="<?php echo esc_attr( $digit_gap )   ; ?>">
		    </div>

		    

		    <div class="<?php echo esc_attr('label'); ?>" 
		    	 for="countdown365_dot_size"><?php _e('Countdown Dot Size', 'countdown365' ) ?></div>
		    <div class="<?php echo esc_attr('countdown365_meta_in'); ?>">

		    <input type="number" 
		           name="countdown365_dot_size" class="" 
		           value="<?php echo esc_attr( $dot_size )   ; ?>">
		    </div>

		    <div class="<?php echo esc_attr('label'); ?>" 
		    	 for="countdown365_message"><?php _e('Countdown Footer Message', 'countdown365' ) ?></div>

		    <div class="<?php echo esc_attr('countdown365_meta_in'); ?>">
		    <textarea name="countdown365_message" 
		              class=""><?php echo esc_attr( $message )  ; ?></textarea>  
		    </div>
		    
	    </div>
     <?php
    }

    function countdown365selected($key, $value ){
    	if( $value == $key ){
    		_e("selected");
    	} 
    }

    function countdown365_save_postdata( $post_id ) {
	   
	    if ( ! current_user_can( 'edit_post', $post_id ) ) {
            return false;
        }

        $is_autosave = wp_is_post_autosave($post_id);
		$is_revision = wp_is_post_revision($post_id);
		$is_valid_nonce = ( isset($_POST['countdown_meta_nonce']) && wp_verify_nonce($_POST['countdown_meta_nonce'], basename(__FILE__)))? 'true' : 'false';

		if($is_autosave || $is_revision || !$is_valid_nonce){
		return;
	    }
       
        $is_wc = ( isset( $_POST['enable_countdown365_on_single_product'] ) ) ? sanitize_text_field( $_POST['enable_countdown365_on_single_product'] ) : '';

        $title = ( isset($_POST['countdown365_title']) ) ? sanitize_text_field( $_POST['countdown365_title'] ) : '';

        $language = ( isset($_POST['countdown365_label_language']) ) ? sanitize_text_field( $_POST['countdown365_label_language'] ) : '';

        $face = ( isset($_POST['countdown365_face'] )) ? sanitize_text_field( $_POST['countdown365_face'] ) : '' ;

        $label_show = ( isset($_POST['countdown365_label_show'] )) ? sanitize_text_field( $_POST['countdown365_label_show'] ) : '';

        $date = ( isset($_POST['countdown365_date'] )) ? sanitize_text_field( $_POST['countdown365_date'] ): '';

        $bg = ( isset($_POST['countdown365_bg'] )) ? sanitize_text_field( $_POST['countdown365_bg'] ) : '';

        $label_color = ( isset($_POST['countdown365_label_color'] )) ? sanitize_text_field( $_POST['countdown365_label_color'] ): '';
        $font_color = ( isset($_POST['countdown365_font_color'] )) ? sanitize_text_field( $_POST['countdown365_font_color'] ): '';

        $separator_color = ( isset($_POST['countdown365_separator_color'] )) ? sanitize_text_field( $_POST['countdown365_separator_color'] ):'';

        $font_size = ( isset($_POST['countdown365_font_size'] ))? intval( $_POST['countdown365_font_size'] ):'';

        $border_radius = ( isset($_POST['countdown365_border_radius'] ) ) ? intval( $_POST['countdown365_border_radius'] ) : '';

        $digit_gap = ( isset($_POST['countdown365_digit_gap'] ) )?intval( $_POST['countdown365_digit_gap'] ):'';

        $dot_size = ( isset($_POST['countdown365_dot_size']) ) ? intval( $_POST['countdown365_dot_size'] ): '';

        $message = ( isset( $_POST['countdown365_message']) ) ? sanitize_text_field( $_POST['countdown365_message'] ): '';


	    if ( 'product' == get_post_type() ) {

	        if ( isset( $is_wc  ) && ($is_wc != '')) {
	            update_post_meta( $post_id, 'enable_countdown365_on_single_product', $is_wc );
	        }else {
	            update_post_meta( $post_id, 'enable_countdown365_on_single_product', sanitize_text_field("no") );
	        }

	        if ( isset( $title ) && $title != '' ) {
	            update_post_meta( $post_id, 'countdown365_title', $title );
	        }

	        if ( isset(  $language ) && $language != '' ) {
	            update_post_meta( $post_id, 'countdown365_label_language',  $language );
	        }

	        if ( isset( $face ) &&  $face != '' ) {
	            update_post_meta( $post_id, 'countdown365_face', $face );
	        }

	        if ( isset( $label_show  ) && $label_show  != '' ) {
	            update_post_meta( $post_id, 'countdown365_label_show', $label_show  );
	        }

	        if ( isset(  $date ) &&  $date != '' ) {
	            update_post_meta( $post_id, 'countdown365_date',  $date );
	        }

	        if ( isset( $bg ) && $bg != '' ) {
	            update_post_meta( $post_id, 'countdown365_bg',  $bg );
	        }

	        if ( isset( $label_color  ) && $label_color  != '' ) {
	            update_post_meta( $post_id, 'countdown365_label_color', $label_color  );
	        }

	        if ( isset( $font_color ) && $font_color != '' ) {
	            update_post_meta( $post_id, 'countdown365_font_color', $font_color );
	        }

	        if ( isset( $separator_color ) && $separator_color  != '' ) {
	            update_post_meta( $post_id, 'countdown365_separator_color', $separator_color  );
	        }

	        if ( isset( $font_size ) && $font_size != '' ) {
	            update_post_meta( $post_id, 'countdown365_font_size', $font_size );
	        }

	        if ( isset(  $border_radius ) &&  $border_radius != '' ) {
	            update_post_meta( $post_id, 'countdown365_border_radius',  $border_radius  );
	        }

	        if ( isset( $digit_gap ) && $digit_gap != '' ) {
	            update_post_meta( $post_id, 'countdown365_digit_gap', $digit_gap );
	        }

	        if ( isset( $dot_size ) && $dot_size  != '' ) {
	            update_post_meta( $post_id, 'countdown365_dot_size', $dot_size  );
	        }

	        if ( isset( $message ) && $message != '' ) {
	            update_post_meta( $post_id, 'countdown365_message', $message );
	        }
	    }
    }


    public function countdown365_get_languages(){

    	$languages = [ "English" => "English",
                       "spanish" => "Spanish",
                       "Finnish" => "Finnish",
                       "French"  => "French",
                       "Italian" => "Italian",
                       "Latvian" => "Latvian",
                       "Dutch"   => "Dutch",
                       "Norwegian" => "Norwegian",
                       "Portuguese" => "Portuguese",
                       "Russian" => "Russian",
                       "Swedish" => "Swedish",
                       "Bangla" => "Bangla",
                       "Chinese" => "Chinese"
                      
                   ];
        return $languages;
    }

    public function countdown365_get_faces(){

    	$faces = [ "DailyCounter" => "Days : Hours : Minutes : Seconds",
                       "HourlyCounter" => "Hours : Minutes : Seconds",
                       "MinuteCounter" => "Minutes : Seconds"
                       
                   ];
        return $faces;
    }

    public function countdown365_get_label_show(){

    	    $label   = [ "show" => "Show",
                       "hide" => "Hide"
                       
                   ];
        return $label;
    }

}

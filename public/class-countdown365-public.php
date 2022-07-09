<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       https://countdown365.vuiphor.com/
 * @since      1.0.0
 *
 * @package    Countdown365
 * @subpackage Countdown365/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 *
 * @package    Countdown365
 * @subpackage Countdown365/public
 * @author     vuiphor <vuiphor.pvt@gmail.com>
 */
class Countdown365_Public {

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

	private $is_shop;
	private $shop;

	private $is_product;
	private $product;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of the plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;
		$this->product  = $this->is_shortcode_enable( 'countdown_on_product_details','countdown_on_product_details_location' );
		$this->shop  = $this->is_shortcode_enable(  'countdown_on_shop','countdown_on_shop_location' );
		add_shortcode( 'countdown365', [$this, 'countdown365_shortcode'] );

		if(!empty($this->product) && get_option('countdown_enable_on_woocommerce',true) ){
		add_action( $this->product, [$this,'action_woocommerce_after_main_content']);
		}

		if(!empty($this->shop) && get_option('countdown_enable_on_woocommerce',true) ){
		add_action( $this->shop , [$this,'action_woocommerce_after_main_content']);
		}
		
		
	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

	
        wp_enqueue_style( $this->plugin_name.'-flipclock',
                           plugin_dir_url( __FILE__ ) . 'css/countdown365-flipclock.css', 
                           array(), $this->version, 'all' );

        wp_enqueue_style( $this->plugin_name, 
        	             plugin_dir_url( __FILE__ ) . 'css/countdown365-public.css', 
        	             array(), $this->version, 'all' );

		
	}

	/**
	 * Register the JavaScript for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		
		wp_enqueue_script( $this->plugin_name.'-flipclock', 
			            plugin_dir_url( __FILE__ ) . 'js/countdown365-flipclock.js', 
			            array( 'jquery' ), $this->version, false );

		wp_enqueue_script( $this->plugin_name, 
			            plugin_dir_url( __FILE__ ) . 'js/countdown365-public.js', 
			            array( 'jquery' ), $this->version, false );

	} 

	public function is_shortcode_enable( $where,$key ){
	  
          if( $this->is_woocommerce() ){
             if( get_option( $where ) ){
                 return esc_html( get_option( $key ) );
             }else{
               return null ;   
	      }
          }else{
               return false ;   
	   }

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


	public function get_metadate($post_id, $key){
		if( get_post_meta( $post_id, $key, true )){
		    return esc_html( get_post_meta( $post_id, $key, true ) );	
		}else{
                  return null ;
		}
	}

	function action_woocommerce_after_main_content(){
		global $post;

		$is_enable_countdown365 = $this->get_metadate( $post->ID, 'enable_countdown365_on_single_product');
		 
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



              echo do_shortcode('[countdown365 enable_on_product_page="'.$is_enable_countdown365.'"  title="'.$title.'" date="'.$date.'"  language="'.$language.'"  face="'.$face.'"  label="'.$label_show.'"  background_color="'.$bg.'" label_color="'.$label_color.'"  font_color="'.$font_color.'" separator_color="'.$separator_color.'"  font_size="'.$font_size.'" border_radius="'.$border_radius.'" digit_gap="'.$digit_gap.'"  dot_size="'.$dot_size.'"  message="'.$message.'" ]');
    }

	public function countdown365_shortcode( $atts ){
		
		
        $atts = shortcode_atts( array(
        	    'enable_on_product_page' => '',
				'date' => '',
				'title' => '',
				'language' => 'english',
				'face' => 'DailyCounter',
				'label' => 'show',
				'background_color' => "",
				'label_color' => "",
				'font_color' => "",
				'separator_color' => "",
				'font_size' => 20,
				'border_radius' => 5,
				'digit_gap' => 0,
				'dot_size' => 3,
				'message' => '',
				
	         ), $atts );
        
        $is_enable_countdown365 = sanitize_text_field( $atts['enable_on_product_page'] ); 
		$title          =  sanitize_text_field( $atts['title'] );  ; 
		$language       =  sanitize_text_field( $atts['language'] ); 
		$face           =  sanitize_text_field( $atts['face'] ); 
		$label_show     =  sanitize_text_field( $atts['label'] ); 
		$date           =  sanitize_text_field( $atts['date'] ); 
		$bg             =  sanitize_text_field( $atts['background_color'] ); 
		$label_color    =  sanitize_text_field( $atts['label_color'] ); 
		$font_color     =  sanitize_text_field( $atts['font_color'] ); 
		$separator_color = sanitize_text_field( $atts['separator_color'] ); 
		$font_size      =  intval( $atts['font_size'] ); 
		$border_radius  =  intval( $atts['border_radius'] ); 
		$digit_gap      =  intval( $atts['digit_gap'] ); 
		$dot_size       =  intval( $atts['dot_size'] );
		$message        =  sanitize_text_field( $atts['message'] ); 

        $id   = uniqid();
       

        // Strat clock content 
        $div  =  '';
        if( isset( $date ) && !empty( $date )  && ( $is_enable_countdown365 !== 'no' )){
			$div  .=  '<div id="'.esc_attr('countdown365_shortcode').'">';

			if( $title ){
			     $div  .=  '<div class="'.esc_attr('flip-clock-wrapper title').'">'. esc_attr($title). '</div>';	
		       }	
			$div  .=  '<div class="'.esc_attr('countdown365').'" id="'.esc_attr('clock').'_'.$id.'" >';
			$div  .=  '<div class="'.esc_attr('clock').'" id="'.esc_attr('cl').'_'.$id.'"';
			           $div  .=  ' data-id="'.esc_attr($id).'"';
			           $div  .=  ' data-face="'.esc_attr($face).'"';
			           $div  .=  ' data-ln="'.esc_attr( $language ).'" ';
			           $div  .=  ' data-countdown365="'.esc_attr($date).'" ';
			           $div  .=  ' >';

			$div  .=  '</div>';
			$div  .=  '</div>';
			if($atts['title']){
			     $div  .=  '<div class="'.esc_attr('flip-clock-wrapper').'">'. esc_html( $message ). '</div>';	
		       }
			$div  .=  '</div>';

			$div  .= '
			<script type="text/javascript">
				(function( $ ) {
				"use strict";
				$(document).ready(function() {
				    var clocks = [];
				    $(".clock").each(function() {
	                    var clock = $("#cl_"+ $(this).data("id"));
				        var date = (new Date(clock.data("countdown365")).getTime() - new Date().getTime()) / 1000;
				        if( date > 0 ){
				        	clock.FlipClock(date, {
								clockFace: clock.data("face"),
								countdown: true,
								language: clock.data("ln") ,
						    });
				        }
				        clocks.push(clock);
				  });              
               }); 
                
			})( jQuery );
			</script>';

		$clock_flip_font_size = $font_size;
		$clock_flip_border_radius = $border_radius;
		$clock_digit_gap = $digit_gap;
		$clock_dot_size = $dot_size;
		

		$clock_height = $clock_flip_font_size * 1.2 ;
		$clock_flip_width = $clock_flip_font_size * 0.8 ;
		$clock_flip_margin =  $clock_digit_gap / 2 ;
		$clock_flip_section_width = (2 * ($clock_flip_width + 2 * $clock_flip_margin));

		$clock_flip_bg = $bg ;
		$clock_flip_shadow = '0 2px 5px rgba(0, 0, 0, 0.7)' ;
		$clock_flip_label_color = $label_color;
		$clock_flip_font_color = $font_color;
		$clock_flip_dot_color = $separator_color;
		$clock_flip_font_shadow =  '0 1px 2px #000';
		$top = ($clock_height / 2 - $clock_flip_font_size * 0.2 - $clock_dot_size / 2) ; 

 
        $css  = '';

        $css .= '#clock_'.$id.' .flip-clock-wrapper ul{
		  height: '.$clock_height.'px;
		  margin: '.$clock_flip_margin.'px;
		  width: '.$clock_flip_width.'px;
		  box-shadow:'.$clock_flip_font_shadow.';}';

	$css .= '#clock_'.$id.' .flip-clock-wrapper ul li{
		  line-height: '.$clock_height.'px;}';

	$css .= '#clock_'.$id.' .flip-clock-wrapper ul li a div div.inn{
		  background-color: '.$clock_flip_bg.';
		  color: '.$clock_flip_font_color.';
		  font-size: '.$clock_flip_font_size.'px;
		  box-shadow:'.$clock_flip_font_shadow.';}';

	$css .= '#clock_'.$id.' .flip-clock-wrapper ul, #clock_'.$id.' .flip-clock-wrapper ul li a div div.inn{
            border-radius: '.$clock_flip_border_radius.'}';


        $css .= '#clock_'.$id.' .flip-clock-dot.top{
               top:'.$top.'px !important;  }';

        $css .= '#clock_'.$id.' .flip-clock-dot.bottom{
               top:'.($top*3).'px !important;  }';

        $css .= '#clock_'.$id.' .flip-clock-dot{
              height: '.$clock_dot_size.'px;
			  left: '.$clock_dot_size.'px;
			  width: '.$clock_dot_size.'px;
			  background: '.$clock_flip_dot_color.'; }';

	 $css .= '#clock_'.$id.' .flip-clock-divider{
               height: '.$clock_height.'px;
               width:'.($clock_dot_size * 3).'px;
               &:first-child
                  width: 0
			  }';

        
	  $css .= '#clock_'.$id.' .flip-clock-divider .flip-clock-label{
			right:'.(-1*$clock_flip_section_width).'px;
			color: '.$clock_flip_label_color.';
			font-size:'.( $clock_flip_font_size / 3).'px;
		       width: '.(2 * $clock_flip_width + 4 * $clock_flip_margin).'px;
			}';

		if($atts['label'] == 'hide'){

			$css .= '#clock_'.$id.' .flip-clock-divider .flip-clock-label{display: none !important ;}';
		}

        $div .= '<style>'.$css.'</style>';
       
        return  $div;

        }
	}	

}
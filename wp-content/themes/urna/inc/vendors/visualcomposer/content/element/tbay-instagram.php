<?php
/**
* ------------------------------------------------------------------------------------------------
* Urna instagram element map
* ------------------------------------------------------------------------------------------------
*/

if ( !function_exists('urna_vc_map_tbay_instagram') ) {
	function urna_vc_map_tbay_instagram() {
		$columns 	= array(1,2,3,4,5,6,7,8);
		$rows 			= apply_filters( 'urna_admin_visualcomposer_rows', array(1,2,3) );

		$params = array(
	    	array(
				"type" => "textfield",
				"holder" => "div",
				"heading" => esc_html__('Title', 'urna'),
				"param_name" => "title",
				"value" => '',
				"admin_label"	=> true
			),		 
			array(
                "type" => "textfield",
                "holder" => "div",
                "class" => "",
                "heading" => esc_html__('Sub Title','urna'),
                "param_name" => "subtitle",
                "admin_label" => true
            ),   	
			array(
				"type" 			=> "textfield",
				"heading" 		=> esc_html__('@username', 'urna'),
				"param_name" 	=> "username",
				"value" 		=> '',
				"admin_label"	=> true,
				"std"			=> 'superette_wellington',
			),
			array(
				"type" => "textfield",
				"heading" => esc_html__('Number of photos:', 'urna'),
				"param_name" => "number",
				"value" => '',
				'std' => '12'
			),
			array(
				"type" 			=> "checkbox",
				"heading" 		=> esc_html__( 'Show Button Follow', 'urna' ),
				"description" 	=> esc_html__( 'Show/hidden button follow ', 'urna' ),
				"param_name" 	=> "btn_follow",
				"value" 		=> array(
									esc_html__('Yes', 'urna') =>'yes' ),
			),
			array(
				'type' 			=> 'dropdown',
				'heading' 		=> esc_html__( 'Photo size:', 'urna' ),
				'param_name' 	=> 'size',
				'description' 	=> esc_html__( 'Choose Type Photo size', 'urna' ) ,
				"admin_label"	=> true,
				'value'       	=> array(
					'thumbnail'   	=> 'thumbnail',
					'small'   		=> 'small',
					'Large'   		=> 'large',
					'Original'   	=> 'original'					),
				'std' => 'small',
				'save_always' => true,
			),				
			array(
				'type' 			=> 'dropdown',
				'heading' 		=> esc_html__( 'Open links in:', 'urna' ),
				'param_name' 	=> 'target',
				'description' 	=> esc_html__( 'Choose Open links in', 'urna' ) ,
				'value'       	=> array(
					esc_html__('Current window (_self)', 'urna')  	=> '_self',
					esc_html__('New window (_blank)', 'urna')   	=> '_blank'
				),
				'std' => '_blank',
				'save_always' => true,
			),
			array(
				"type" => "dropdown",
				"heading" => esc_html__('Layout Type', 'urna'),
				"param_name" => "layout_type",
				'value' 	=> array(
					esc_html__('Carousel', 'urna') => 'carousel', 
					esc_html__('Grid', 'urna') => 'grid'
				),
				'std' => 'carousel'
			)
	   	);

		$responsive     = apply_filters( 'urna_vc_map_param_responsive_instagrams', array() );
		$carousel 		= apply_filters( 'urna_vc_map_param_carousel', array() );
		$last_params 	= apply_filters( 'urna_vc_map_param_last_params', array() );

		$params = array_merge($params, $carousel, $responsive, $last_params);

		vc_map( array(
		    "name" => esc_html__('Urna Instagram','urna'),
		    "base" => "tbay_instagram",
		    "icon" => "vc-icon-urna",
		    "class" => "",
		    "description"=> esc_html__('Show images Instagram', 'urna'),
		    "category" => esc_html__('Urna Elements', 'urna'),
		    "params" => $params,
		));
	}
	add_action( 'vc_before_init', 'urna_vc_map_tbay_instagram' );
}

if( class_exists( 'WPBakeryShortCode' ) ){
    class WPBakeryShortCode_tbay_instagram extends WPBakeryShortCode {}
}
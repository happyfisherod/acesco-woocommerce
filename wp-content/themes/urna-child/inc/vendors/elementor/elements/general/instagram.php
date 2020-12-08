<?php

if ( ! defined( 'ABSPATH' ) || function_exists('Urna_Elementor_Instagram') ) {
    exit; // Exit if accessed directly.
}

use Elementor\Controls_Manager;

/**
 * Elementor tabs widget.
 *
 * Elementor widget that displays vertical or horizontal tabs with different
 * pieces of content.
 *
 * @since 1.0.0
 */
class Urna_Elementor_Instagram extends  Urna_Elementor_Carousel_Base{
    /**
     * Get widget name.
     *
     * Retrieve tabs widget name.
     *
     * @since 1.0.0
     * @access public
     *
     * @return string Widget name.
     */
    public function get_name() {
        return 'tbay-instagram';
    }

    /**
     * Get widget title.
     *
     * Retrieve tabs widget title.
     *
     * @since 1.0.0
     * @access public
     *
     * @return string Widget title.
     */ 
    public function get_title() {
        return esc_html__( 'Urna Instagram', 'urna' );
    }

    public function get_script_depends() {
        return [ 'urna-script', 'slick', 'urna-slick', 'jquery-instagramfeed', 'jquery-timeago' ];
    } 
 
    /**
     * Get widget icon.
     *
     * Retrieve tabs widget icon.
     *
     * @since 1.0.0
     * @access public
     *
     * @return string Widget icon.
     */
    public function get_icon() {
        return 'eicon-gallery-justified';
    }

    /**
     * Register tabs widget controls.
     *
     * Adds different input fields to allow the user to change and customize the widget settings.
     *
     * @since 1.0.0
     * @access protected
     */
    protected function _register_controls() {
        $this->register_controls_heading();
        $this->register_remove_heading_element();

        $this->start_controls_section(
            'section_general',
            [
                'label' => esc_html__( 'General', 'urna' ),
            ]
        );
 
        $this->add_control(
            'username',
            [
                'label' => esc_html__('Username', 'urna'),
                'type' => Controls_Manager::TEXT,
                'default' => esc_html__( 'instagram', 'urna' ),
                'label_block' => true,
            ]
        );  

        $this->add_control(
            'limit',
            [
                'label' => esc_html__('Number of photos', 'urna'),
                'type' => Controls_Manager::NUMBER,
                'default' => 6,
                'min'  => 1,
                'max'  => 12
            ]
        );

 
        $this->add_control(
            'layout_type',
            [
                'label'     => esc_html__('Layout Type', 'urna'),
                'type'      => Controls_Manager::SELECT,
                'default'   => 'grid',
                'options'   => [
                    'grid'      => esc_html__('Grid', 'urna'), 
                    'carousel'  => esc_html__('Carousel', 'urna'), 
                ],
            ]
        );  

        $this->add_control(
            'photo_size',
            [
                'label' => esc_html__('Photo Size', 'urna'),
                'type' => Controls_Manager::SELECT,
                'default' => 'small',
                'options' => [
                    'small' => esc_html__('Small', 'urna'),
                    'thumnail' => esc_html__('Thumnail', 'urna'),
                    'large' => esc_html__('Large', 'urna'),
                    'original' => esc_html__('Original', 'urna'),
                ],
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'target',
            [
                'label' => esc_html__('Open link in', 'urna'),
                'type' => Controls_Manager::SELECT,
                'default' => '_blank',
                'options' => [
                    '_self' => esc_html__('Current window (_self)', 'urna'),
                    '_blank' => esc_html__('New window (_blank)', 'urna'),
                ],
            ]
        );

        $this->add_control(
            'heading_settings',
            [
                'label' => esc_html__( 'Settings', 'urna' ),
                'type' => Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'show_time',
            [
                'label' => esc_html__( 'Show Time', 'urna' ),
                'type' => Controls_Manager::SWITCHER,
                'label_on' => esc_html__( 'Show', 'urna' ),
                'label_off' => esc_html__( 'Hide', 'urna' ),
                'default' => 'yes',
            ]
        );

        $this->add_control(
            'show_like',
            [
                'label' => esc_html__( 'Show Likes', 'urna' ),
                'type' => Controls_Manager::SWITCHER,
                'label_on' => esc_html__( 'Show', 'urna' ),
                'label_off' => esc_html__( 'Hide', 'urna' ),
                'default' => 'yes',
            ]
        );

        $this->add_control(
            'show_comment',
            [
                'label' => esc_html__( 'Show Comments', 'urna' ),
                'type' => Controls_Manager::SWITCHER,
                'label_on' => esc_html__( 'Show', 'urna' ),
                'label_off' => esc_html__( 'Hide', 'urna' ),
                'default' => 'yes',
            ]
        );

        $this->add_control(
            'show_button',
            [
                'label' => esc_html__( 'Show Button Follow', 'urna' ),
                'type' => Controls_Manager::SWITCHER,
                'label_on' => esc_html__( 'Show', 'urna' ),
                'label_off' => esc_html__( 'Hide', 'urna' ),
                'default' => '',
            ]
        ); 

        $this->end_controls_section();

        $this->add_control_responsive();
        $this->add_control_carousel(['layout_type' => 'carousel']);
        $this->remove_control('rows');
    }

    protected function render_button() {
        $settings = $this->get_settings_for_display();
        extract( $settings );

        if ($show_button === 'yes') {
            ?>
            <?php
                $username   = trim( strtolower( $username) );
                $url        = 'https://instagram.com/' . str_replace( '@', '', $username );
            ?>

            <a class="btn-follow" href="<?php echo esc_url($url); ?>">
                <?php esc_html_e('Follow ', 'urna'); ?><span>@<?php echo trim($username); ?></span>
            </a>
            <?php
        }
    }

}
$widgets_manager->register_widget_type(new Urna_Elementor_Instagram());

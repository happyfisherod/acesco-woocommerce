<?php 
/**
 * Templates Name: Elementor
 * Widget: Search Form
 */
$search_style = 'full';
extract( $settings );

// if( $search_style !== 'full' ) $this->add_render_attribute('_wrapper', 'class', 'w-auto');

$this->add_render_attribute('wrapper', 'class', ['search-form-'.$search_style ]);
?>
<div <?php echo trim($this->get_render_attribute_string('wrapper')); ?>>
    <?php $this->render_search_form_full(); ?>
</div>
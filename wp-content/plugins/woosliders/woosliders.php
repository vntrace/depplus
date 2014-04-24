<?php 
    /*
    Plugin Name: Wooslider widget
    Plugin URI: http://rjmshop.com
    Description: Do a short code for metaslider
    Author: Thanh Dc
    Version: 1.0
    Author URI: http://rjmshop.com
    */
?>
<?php
class woosliders_plugin extends WP_Widget
{
	public function __construct() 
	{
		parent::WP_Widget(false, $name = __('Wooslider', 'wp_widget_plugin') );
	}

	public function form($instance)
	{
		$slider = '';
		if($instance) {
			$slider = esc_attr($instance['slider']);
		}

		echo '<p>' .
				'<label>Slider</label>'.
				'<input id="'. $this->get_field_id('slider') .'" name="'. $this->get_field_name('slider') .'" type="text" value="'. $slider .'"/>'.
			 '</p>';
	}

	public function update($new_instance, $old_instance)
	{

	}

	/**
	 * Display my widget
	 */
	public function widget() 
	{

	}
}

add_action('widgets_init', create_function('', 'return register_widget("woosliders_plugin");'));
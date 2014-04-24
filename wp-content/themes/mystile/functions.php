<?php
// File Security Check
if ( ! empty( $_SERVER['SCRIPT_FILENAME'] ) && basename( __FILE__ ) == basename( $_SERVER['SCRIPT_FILENAME'] ) ) {
    die ( 'You do not have sufficient permissions to access this page!' );
}
?>
<?php

/*-----------------------------------------------------------------------------------*/
/* Start WooThemes Functions - Please refrain from editing this section */
/*-----------------------------------------------------------------------------------*/

// Define the theme-specific key to be sent to PressTrends.
define( 'WOO_PRESSTRENDS_THEMEKEY', 'zdmv5lp26tfbp7jcwiw51ix9sj389e712' );

// WooFramework init
require_once ( get_template_directory() . '/functions/admin-init.php' );

/*-----------------------------------------------------------------------------------*/
/* Load the theme-specific files, with support for overriding via a child theme.
/*-----------------------------------------------------------------------------------*/

$includes = array(
				'includes/theme-options.php', 			// Options panel settings and custom settings
				'includes/theme-functions.php', 		// Custom theme functions
				'includes/theme-actions.php', 			// Theme actions & user defined hooks
				'includes/theme-comments.php', 			// Custom comments/pingback loop
				'includes/theme-js.php', 				// Load JavaScript via wp_enqueue_script
				'includes/sidebar-init.php', 			// Initialize widgetized areas
				'includes/theme-widgets.php',			// Theme widgets
				'includes/theme-install.php',			// Theme installation
				'includes/theme-woocommerce.php',		// WooCommerce options
				'includes/theme-plugin-integrations.php'	// Plugin integrations
				);

// Allow child themes/plugins to add widgets to be loaded.
$includes = apply_filters( 'woo_includes', $includes );

foreach ( $includes as $i ) {
	locate_template( $i, true );
}

/*-----------------------------------------------------------------------------------*/
/* You can add custom functions below */
/*-----------------------------------------------------------------------------------*/
add_action( 'woocommerce_archive_description', 'woocommerce_category_image', 2 );

function woocommerce_category_image() {
    if ( is_product_category() ){
	    global $wp_query;
	    $cat = $wp_query->get_queried_object();
	    $thumbnail_id = get_woocommerce_term_meta( $cat->term_id, 'thumbnail_id', true );
	    $image = wp_get_attachment_url( $thumbnail_id );
	    if ( $image ) {
		    echo '<img src="' . $image . '" alt="" />';
		}
	}
}

function add_custom_script() {
	wp_enqueue_style( 'font_awesome', get_template_directory_uri() . '/css/font-awesome.min.css' );
	wp_enqueue_script( 'custom_script', get_template_directory_uri() . '/js/custom.js', array(), '1.0.0', true );
}

add_action('wp_enqueue_scripts', 'add_custom_script');

// Hook footer
function init_social_app() {
?>

	<div id="fb-root"></div>
<script>(function(d, s, id) {
  var js, fjs = d.getElementsByTagName(s)[0];
  if (d.getElementById(id)) return;
  js = d.createElement(s); js.id = id;
  js.src = "//connect.facebook.net/en_US/all.js#xfbml=1&appId=449182018545644";
  fjs.parentNode.insertBefore(js, fjs);
}(document, 'script', 'facebook-jssdk'));</script>

<script type="text/javascript">
  (function() {
    var po = document.createElement('script'); po.type = 'text/javascript'; po.async = true;
    po.src = 'https://apis.google.com/js/platform.js';
    var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(po, s);
  })();
</script>

<?php }

add_action('wp_footer', 'init_social_app');

function sharethis_code() {
	echo '<div class="g-plusone" data-size="medium"></div>';
	// share facebook like box
	echo '<div class="fb-like" data-href="'. get_permalink() .'" data-layout="standard" data-action="like" data-show-faces="false" data-share="true"></div>';
}

add_action( 'woocommerce_share', 'sharethis_code' );

// Register sidebar
function register_slider_widget_area()
{
	if ( !function_exists( 'register_sidebar') )
	    return;

	register_sidebar(array( 
		'name' => 'Primary Slider',
		'id' => 'primary-slider', 
		'description' => "Primary Slider",
		'before_widget' => '<div class="primary-slider">',
		'after_widget' => '</div>'
	));

	register_sidebar(array( 
		'name' => 'Second Slider',
		'id' => 'second-slider', 
		'description' => "Second Slider",
		'before_widget' => '<div class="second-slider">',
		'after_widget' => '</div>'
	));
}

add_action( 'init', 'register_slider_widget_area' );

/*-----------------------------------------------------------------------------------*/
/* Don't add any code below here or the sky will fall down */
/*-----------------------------------------------------------------------------------*/
?>
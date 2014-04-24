<?php
// File Security Check
if ( ! empty( $_SERVER['SCRIPT_FILENAME'] ) && basename( __FILE__ ) == basename( $_SERVER['SCRIPT_FILENAME'] ) ) {
    die ( 'You do not have sufficient permissions to access this page!' );
}
?>
<?php
/**
 * Header Template
 *
 * Here we setup all logic and XHTML that is required for the header section of all screens.
 *
 * @package WooFramework
 * @subpackage Template
 */
global $woo_options, $woocommerce;
?><!DOCTYPE html>
<html <?php language_attributes(); ?> class="<?php if ( $woo_options['woo_boxed_layout'] == 'true' ) echo 'boxed'; ?> <?php if (!class_exists('woocommerce')) echo 'woocommerce-deactivated'; ?>">
<head>

<meta charset="<?php bloginfo( 'charset' ); ?>" />

<title><?php woo_title(''); ?></title>
<?php woo_meta(); ?>
<link rel="stylesheet" type="text/css" href="<?php bloginfo( 'stylesheet_url' ); ?>" media="screen" />
<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>" />
<?php
	wp_head();
	woo_head();
?>

</head>

<body <?php body_class(); ?>>
<?php woo_top(); ?>

<div id="wrapper">
	<div id="top">
		<nav class="col-full" role="navigation">
			<ul class="contact-nav">
				<?php if(is_user_logged_in()): ?>
					<?php 
						$user = get_user_by( 'id', get_current_user_id() );		
					?>
					<li>
						Hi <a href="/my-account/"><?php echo $user->display_name; ?></a>
					</li>
					<li>
						<a href="<?php echo wp_logout_url( home_url() ); ?>">
							<i class="fa fa-sign-out"></i>
							Thoát
						</a>
					</li>
				<?php else: ?>
				<li>
					<a href="<?php echo site_url( 'wp-login.php?action=register', 'login' ); ?>">
						<i class="fa fa-edit"></i>
						Đăng Ký
					</a>
				</li>
				<li>
					<a href="<?php echo wp_login_url( home_url()); ?>">
						<i class="fa fa-unlock"></i>
						Đăng Nhập
					</a>
				</li>
				<?php endif; ?>
				<li>
					<div class="fb-like" data-href="https://www.facebook.com/rjmshop" data-layout="button_count" data-action="like" data-show-faces="true" data-share="false"></div>
					
				</li>
				<li>
					<div class="g-plusone" data-size="medium"></div>
				</li>
			</ul>
			<?php
				if ( class_exists( 'woocommerce' ) ) {
					echo '<ul class="nav wc-nav">';
					woocommerce_cart_link();
					echo '<li class="checkout"><a href="'.esc_url($woocommerce->cart->get_checkout_url()).'">'.__('Checkout','woothemes').'</a></li>';
					echo get_search_form();
					echo '</ul>';
				}
			?>
		</nav>
	</div><!-- /#top -->

    <?php woo_header_before(); ?>

	<header id="header">
		<div class="col-full">
		    <hgroup>
		    	<?php
				    $logo = esc_url( get_template_directory_uri() . '/images/logo.png' );
					if ( isset( $woo_options['woo_logo'] ) && $woo_options['woo_logo'] != '' ) { $logo = $woo_options['woo_logo']; }
					if ( isset( $woo_options['woo_logo'] ) && $woo_options['woo_logo'] != '' && is_ssl() ) { $logo = preg_replace("/^http:/", "https:", $woo_options['woo_logo']); }
				?>
				<?php if ( ! isset( $woo_options['woo_texttitle'] ) || $woo_options['woo_texttitle'] != 'true' ) { ?>
				    <a id="logo" href="<?php echo esc_url( home_url( '/' ) ); ?>" title="<?php esc_attr( get_bloginfo( 'description' ) ); ?>">
				    	<img src="<?php echo $logo; ?>" alt="<?php echo esc_attr( get_bloginfo( 'name' ) ); ?>" />
				    </a>
			    <?php } ?>

				<h1 class="site-title"><a href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php bloginfo( 'name' ); ?></a></h1>
				<h2 class="site-description"><?php bloginfo( 'description' ); ?></h2>
				<h3 class="nav-toggle"><a href="#navigation">&#9776; <span><?php _e('Navigation', 'woothemes'); ?></span></a></h3>

			</hgroup>

			<img id="hotline" src="<?php echo esc_url( get_template_directory_uri() . '/images/hotline.png'); ?>"/>

			<?php woo_nav_before(); ?>

			<nav id="navigation" class="col-full" role="navigation">
				<?php
				if ( function_exists( 'has_nav_menu' ) && has_nav_menu( 'primary-menu' ) ) {
					wp_nav_menu( array( 'depth' => 6, 'sort_column' => 'menu_order', 'container' => 'ul', 'menu_id' => 'main-nav', 'menu_class' => 'nav fr', 'theme_location' => 'primary-menu' ) );
				} else {
				?>
		        <ul id="main-nav" class="nav fl">
		        	<?php $highlight = is_home() ? 'page_item current_page_item' : 'page_item'; ?>
					<?php //if ( is_page()) $highlight = 'page_item'; else $highlight = 'page_item current_page_item'; ?>
					<li class="<?php echo $highlight; ?>"><a href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php _e( 'Home', 'woothemes' ); ?></a></li>
					<?php wp_list_pages( 'sort_column=menu_order&depth=6&title_li=&exclude=' ); ?>
				</ul><!-- /#nav -->
		        <?php } ?>
			</nav><!-- /#navigation -->

			<?php woo_nav_after(); ?>
		</div>

		<div class="col-full">
			<?php if ( function_exists( 'has_nav_menu' ) && has_nav_menu( 'top-menu' ) ) { ?>
			<?php wp_nav_menu( array( 'depth' => 6, 'sort_column' => 'menu_order', 'container' => 'ul', 'menu_id' => 'top-nav', 'menu_class' => 'nav fl', 'theme_location' => 'top-menu' ) ); ?>
			<?php } ?>
		</div>


	</header><!-- /#header -->
	<?php if(is_home()): ?>
	<div id="woo_slider" class="col-full">
		<div class="col-left">

			<?php if(woo_active_sidebar( 'primary-slider' )): ?>
				<?php woo_sidebar('primary-slider'); ?>
			<?php endif; ?>

			<?php //echo do_shortcode("[metaslider id=51]"); ?>
		</div>
		<div class="col-right">
			<?php if(woo_active_sidebar( 'second-slider' )): ?>
				<?php woo_sidebar('second-slider'); ?>
			<?php endif; ?>					
		</div>
	</div>
	<?php endif; ?>

	<?php woo_content_before(); ?>
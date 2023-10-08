<?php
/**
 * Template part for displaying my account
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @author 	PressLayouts
 * @package kapee/template-parts/header
 * @since 1.0
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if( ! KAPEE_WOOCOMMERCE_ACTIVE ) return;
$user_data 					= wp_get_current_user();
$current_user 				= apply_filters('kapee_myaccount_username',$user_data->user_login );	


$orders  					= get_option( 'woocommerce_myaccount_orders_endpoint', 'orders' );
$account_page_id 			= get_option( 'woocommerce_myaccount_page_id' );
$account_page_url 			= !empty( $account_page_id ) ? get_permalink( get_option( 'woocommerce_myaccount_page_id' ) ) : '#';
if ( !empty( $account_page_id ) && substr( $account_page_url, - 1, 1 ) != '/' ) {
	$account_page_url .= '/';
}
$orders_url   				= $account_page_url . $orders;
$dashboard_url				= apply_filters('kapee_myaccount_dashboard_url',$account_page_url );
$woocommerce_account_menu  	= kapee_get_account_menu();
$myaccount_style			= kapee_get_option( 'header-myaccount-style', 1 );
$myaccount_text 			= apply_filters('kapee_myaccount_text', esc_html__('Login/Signup','kapee') );
?>			

<div class="header-myaccount myaccount-style-<?php echo esc_attr($myaccount_style); ?>">
	
	<?php 	
	ob_start();
	switch ( $myaccount_style ) {
		case 1: ?> 
			<div class="myaccount-wrap">
				<span><?php echo ( ! is_user_logged_in() ) ? esc_html($myaccount_text) : esc_html($current_user);?></span>
			</div><?php
			break;
		case 2:?>
			<div class="myaccount-wrap">
				<small><?php esc_html_e('Hello,', 'kapee');?></small>
				<span><?php echo ( ! is_user_logged_in() ) ? esc_html__('My Account','kapee') : esc_html($current_user);?></span>
			</div><?php
			break;
		default:
	}
	$cart_html = ob_get_clean();?>
	
	<?php if( ! is_user_logged_in() ):?>
		<a class="customer-signinup" href="<?php echo esc_url($dashboard_url);?>"><?php echo wp_kses_post($cart_html); ?></a>
	<?php else:?>
		<a class="user-myaccount" href="<?php echo esc_url($dashboard_url);?>"><?php echo wp_kses_post($cart_html); ?></a>
		<ul class="myaccount-items kapee-arrow">
			<?php 
			foreach( $woocommerce_account_menu as $menu_item ){
				$class = ( isset( $menu_item['class'] ) && !empty( $menu_item['class'] ) ) ? $menu_item['class'] : '';?>
				<li>
					<a class="<?php echo esc_attr($class);?>" href="<?php echo esc_url($menu_item['link']);?>">
						<i class="<?php echo esc_attr($menu_item['icon']);?>"></i><?php echo esc_html($menu_item['label']);?>
					</a>
				</li>
				<?php
			}?>
		</ul>
	<?php endif;?>
</div>
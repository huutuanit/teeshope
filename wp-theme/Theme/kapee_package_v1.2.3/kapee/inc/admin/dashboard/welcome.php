<?php
/**
 * Kapee Admin Dashboard Tab
 *
 * @package Kapee
 * @since 1.0
 */
require_once KAPEE_INC_DIR.'admin/dashboard/header.php';
global $obj_kp_updatetheme, $wp_filesystem, $wpdb;;
$obj_kp_dash = new Kapee_Dashboard();
if ( isset( $_GET['tgmpa-deactivate'] ) && 'deactivate-plugin' == $_GET['tgmpa-deactivate'] ) {
	$plugins = TGM_Plugin_Activation::$instance->plugins;
	check_admin_referer( 'tgmpa-deactivate', 'tgmpa-deactivate-nonce' );
	foreach ( $plugins as $plugin ) {
		if ( $plugin['slug'] == $_GET['plugin'] ) {
			deactivate_plugins( $plugin['file_path'] );
		}
	}
}
if ( isset( $_GET['tgmpa-activate'] ) && 'activate-plugin' == $_GET['tgmpa-activate'] ) {
	check_admin_referer( 'tgmpa-activate', 'tgmpa-activate-nonce' );
	$plugins = TGM_Plugin_Activation::$instance->plugins;
	foreach ( $plugins as $plugin ) {
		if ( isset( $_GET['plugin'] ) && $plugin['slug'] == $_GET['plugin'] ) {
			activate_plugin( $plugin['file_path'] );
		}
	}
}

$plugins 				= TGM_Plugin_Activation::$instance->plugins;
$tgm_plugins_required 	= 0;
$tgm_plugins_action 	= array();
foreach ( $plugins as $plugin ) {
	$tgm_plugins_action[ $plugin['slug'] ] = $obj_kp_dash->plugin_action( $plugin );
}
$is_theme_active 		= kapee_is_license_activated();
$active_button_txt 		= esc_html__('Activate Theme', 'kapee');
$active_button_class 	= 'kapee-activate-btn';
$input_attr 			= '';
$theme_activate 		= 'theme-deactivated';
$status_txt 			= esc_html__('No Activated', 'kapee');
$purchase_code 			= '';
$readonly 				= 'false';
$status_activate_class 	= ' red';
if( $is_theme_active ){
	$purchase_code 			= kapee_get_purchase_code();
	$active_button_txt 		= esc_html__('Deactivate Theme', 'kapee');
	$active_button_class 	= 'kapee-deactivate-btn';
	$input_attr 			= ' value="'.$purchase_code.'" readonly="true"';
	$readonly				= 'true';
	$theme_activate 		= 'theme-activated';
	$status_txt 			= esc_html__('Activated', 'kapee');
	$status_activate_class 	= ' green';
}
?>
<div class="kapee-content-body">
	<div class="kp-row">
		<div class="kp-col-12">
			<div class="kapee-box theme-activate <?php echo esc_attr($theme_activate);?>">
				<div class="kapee-box-header">
					<div class="title"> <?php esc_html_e('Purchase Code', 'kapee')?></div>
					<div class="kapee-button<?php echo esc_attr($status_activate_class);?>"> <?php echo esc_html( $status_txt );?></div>
				</div>
				<div class="kapee-box-body">
					<form action="" method="post">
						<?php if( $is_theme_active ){ ?>
						<input name="purchase-code" class="purchase-code" type="text" placeholder="<?php esc_attr_e('Purchase code','kapee');?>" value="<?php echo esc_attr($purchase_code); ?>" readonly = "true">
						<?php } else { ?>
						<input name="purchase-code" class="purchase-code" type="text" placeholder="<?php esc_attr_e('Purchase code','kapee');?>">
						<?php } ?>
						<button type="button"  id="kapee-activate-theme"  class="button action <?php echo esc_attr($active_button_class);?>"><?php echo esc_html( $active_button_txt );?></button>
						
					</form>
					<div class="purchase-desc">
						<?php echo wp_kses ( sprintf( __( 'You can learn how to find your purchase key <a href="%s" target="_blank"> here </a>', 'kapee' ),'https://help.market.envato.com/hc/en-us/articles/202822600-Where-Is-My-Purchase-Code' ), kapee_allowed_html( 'a' ) );?>
					</div>
				</div>
			</div>
		</div>
	</div>		
	<div class="kp-row">
		<div class="kp-col-md-6">
			<div class="kapee-box docs">
				<div class="kapee-box-header">
					<div class="title"><?php esc_html_e('Documentation','kapee');?></div>
				</div>
				<div class="kapee-box-body">	
					<p><?php esc_html_e('Our documentation is simple and functional wit full details and cover all essential aspects from beginning to the most advanced parts.','kapee');?> </p>
					<div class="s-button">
						<a class="button" href="https://docs.presslayouts.com/kapee" target="_blank"><?php esc_html_e('Documentation','kapee');?></a>
					</div>
				</div>
			</div>
		</div>
		<div class="kp-col-md-6">
			<div class="kapee-box support">
				<div class="kapee-box-header">
					<div class="title"><?php esc_html_e('Support','kapee');?></div>
				</div>
				<div class="kapee-box-body">	
					<p><?php esc_html_e('Kapee theme comes with 6 months of free support for every license you purchase. Support can be extended through subscriptions via ThemeForest.','kapee');?> </p>
					<div class="s-button">
						<a class="button" href="https://docs.presslayouts.com/kapee" target="_blank"><?php esc_html_e('Send Request','kapee');?></a>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="kp-row">
		<div class="kp-col-12">
			<div class="kapee-box system-requirements">
				<div class="kapee-box-header">
					<div class="title"><?php esc_html_e('System Requirements','kapee');?></div>
				</div>
				<div class="kapee-box-body">
					<table class="widefat" cellspacing="0">
						<tbody>
							<?php if( function_exists( 'kapee_get_server_info' ) ) { ?>
							<tr>
								<td data-export-label="Server Info"><?php esc_html_e( 'Server Info:', 'kapee' ); ?></td>
								<td><?php echo esc_html( kapee_get_server_info() ); ?></td>
							</tr>
							<?php } ?>
							<tr>
								<td data-export-label="PHP Version"><?php esc_html_e( 'PHP Version:', 'kapee' ); ?></td>
								<td>
									<?php 
									if ( function_exists( 'phpversion' ) ) { 
										$php_version = phpversion();
										if( version_compare(phpversion(), '5.6', '<') ){ 
										echo esc_html__('Currently:','kapee').' '. phpversion().' ';  
										esc_html_e('(min: 5.6)','kapee') ?> 
										<label class="hero button" for="php-version"> <?php esc_html_e('Please contact Host provider to fix it.','kapee') ?> </label>
									<?php } else { 
										echo esc_html__('Currently:','kapee').' '. phpversion() ?> </span>
									<?php }
									}else{
										echo  esc_html__('Couldn\'t determine PHP version because phpversion() doesn\'t exist.','kapee');
									}
									?>
								</td>
							</tr>
							<tr>
								<td data-export-label="PHP Post Max Size"><?php esc_html_e( 'PHP Post Max Size:', 'kapee' ); ?></td>
								<td><?php echo size_format( wp_convert_hr_to_bytes( ini_get( 'post_max_size' ) ) ); ?></td>
							</tr>
							<tr>
								<td data-export-label="PHP Time Limit"><?php esc_html_e( 'PHP Time Limit:', 'kapee' ); ?></td>
								<td>
									<?php
									$time_limit = ini_get('max_execution_time');

									if ( $time_limit < 180 && $time_limit != 0 ) {
										echo '<mark class="error">' . wp_kses(sprintf( __( '%1$s - We recommend setting max execution time to at least 600. <br /> To import demo content, <strong>600</strong> seconds of max execution time is required.<br />See: <a href="%2$s" target="_blank">Increasing max execution to PHP</a>', 'kapee' ), $time_limit, 'https://wordpress.org/support/article/common-wordpress-errors/#php-errors' ), array( 'strong' => array(), 'br' => array(), 'a' => array( 'href' => array(), 'target' => array() ) ) ) . '</mark>';
									} else {
										echo '<mark class="yes">' . $time_limit . '</mark>';
										if ( $time_limit < 600 && $time_limit != 0 ) {
											echo '<br /><mark class="error">' . wp_kses(__( 'Current time limit is sufficient, but if you need import demo content, the required time is <strong>600</strong>.', 'kapee' ), array( 'strong' => array(),  ) ) . '</mark>';
										}
									}
									?>
								</td>
							</tr>
							<tr>
								<td data-export-label="PHP Max Input Vars"><?php esc_html_e( 'PHP Max Input Vars:', 'kapee' ); ?></td>
								<td>
									<?php 
									$registered_navs = get_nav_menu_locations();
									$menu_items_count = array( '0' => '0' );
									foreach ( $registered_navs as $handle => $registered_nav ) {
										$menu = wp_get_nav_menu_object( $registered_nav );
										if ( $menu ) {
											$menu_items_count[] = $menu->count;
										}
									}

									$max_items = max( $menu_items_count );
									$required_input_vars = $max_items * 20;
									$max_input_vars = ini_get( 'max_input_vars' );
									$required_input_vars = $required_input_vars + ( 500 + 1000 );
									echo esc_html( $max_input_vars );
									?>
								</td>
							</tr>
							 <tr>
								<td data-export-label="ZipArchive"><?php esc_html_e( 'ZipArchive:', 'kapee' ); ?></td>
								<td><?php echo class_exists( 'ZipArchive' ) ? '<span class="yes">&#10004;</span>' : '<span class="error">No.</span>'; ?></td>
							</tr>
							<tr>
								<td data-export-label="Max Upload Size"><?php esc_html_e( 'Max Upload Size:', 'kapee' ); ?></td>
								<td><?php echo size_format( wp_max_upload_size() ); ?></td>
							</tr>
							<tr>
								<td data-export-label="MySQL Version"><?php esc_html_e( 'MySQL Version:', 'kapee' ); ?></td>
								<td><?php echo esc_html( $wpdb->db_version() ); ?></td>
							</tr>
							<tr>
								<td data-export-label="GD Library"><?php esc_html_e( 'GD Library:', 'kapee' ); ?></td>
								<td>
									<?php
									$info = esc_attr__( 'Not Installed', 'kapee' );
									if ( extension_loaded( 'gd' ) && function_exists( 'gd_info' ) ) {
										$info = esc_attr__( 'Installed', 'kapee' );
										$gd_info = gd_info();
										if ( isset( $gd_info['GD Version'] ) ) {
											$info = $gd_info['GD Version'];
										}
									}
									echo esc_html( $info );
									?>
								</td>
							</tr>
							<tr>
								<td data-export-label="cURL"><?php esc_html_e( 'cURL:', 'kapee' ); ?></td>
								<td>
									<?php
									$info = esc_attr__( 'Not Enabled', 'kapee' );
									if ( function_exists( 'curl_version' ) ) {
										$curl_info = curl_version();
										$info = $curl_info['version'];
									}
									echo esc_html( $info );
									?>
								</td>
							</tr>
						</tbody>
					</table>
				</div>
			</div>
		</div>		
	</div>
	<div class="kp-row">
		<div class="kp-col-12">
			<div class="kapee-box install-plugin ">
				<div class="kapee-box-header">
					<div class="title"><?php esc_html_e('Installation Required Plugins','kapee');?></div>
				</div>
				<div class="kapee-box-body">
					<table class="widefat">
						<thead>
							<tr>
								<th> <?php esc_html_e('Plugin', 'kapee');?> </th>
								<th> <?php esc_html_e('Version','kapee');?> </th>
								<th> <?php esc_html_e('Type', 'kapee');?> </th>
								<th> <?php esc_html_e('Action', 'kapee');?> </th>
							</tr>
						</thead>
						<tbody>
							<?php foreach ( $plugins as $tgm_plugin ) { ?>
								<tr>
									<td>
										<?php
										//$instance = call_user_func( array( get_class( $GLOBALS['tgmpa'] ), 'get_instance' ) );
										if ( isset( $tgm_plugin['required'] ) && ( $tgm_plugin['required'] == true ) ) {
											if ( ! kapee_tgmpa_is_plugin_check_active( $tgm_plugin['slug'] ) ){
												echo '<span>' . $tgm_plugin['name'] . '</span>';
												$tgm_plugins_required ++;
											} else {
												echo '<span class="actived">' . $tgm_plugin['name'] . '</span>';
											}
										} else {
											echo esc_html( $tgm_plugin['name'] );
										}?>
									</td>
									<td><?php echo( isset( $tgm_plugin['version'] ) ? $tgm_plugin['version'] : '' ); ?></td>
									<td><?php echo( isset( $tgm_plugin['required'] ) && ( $tgm_plugin['required'] == true ) ? 'Required' : 'Recommended' ); ?></td>
									<td>
										<?php echo wp_kses_post( $tgm_plugins_action[ $tgm_plugin['slug'] ] ); ?>
									</td>
								</tr>
							<?php } ?>
						</tbody>
					</table>	
				</div>
			</div>
		</div>
	</div>
	<div class="kp-row">
		<div class="kp-col-12">
			<div class="kapee-box changelog">
				<div class="kapee-box-header">
					<div class="title"><?php esc_html_e('Changelog (Updates)','kapee');?> </div>
				</div>
				<div class="kapee-box-body">
<pre>
V1.2.3 released on 12-05-2020
===========================================================
ADDED : WooCommerce Compatible 4.1.0
UPDATED : Revolution Slider 6.2.6

V1.2.2 released on 05-05-2020
===========================================================
ADDED : WordPress Compatible 5.4.1
ADDED : WooCommerce Compatible 4.0.1
UPDATED : Kapee Extensions Plugin 1.0.8
UPDATED : WPBakery Page Builder 6.2.0
UPDATED : Revolution Slider 6.2.3
FIXED : Instagram Element Link not Working
FIXED : WooCommerce Password Strength Issue
FIXED : Sale Countdown Timer Issue
FIXED : Price Summary Discount Amount Issue
FIXED : Prodcut Categories Element Product Count Show/Hide Issue
FIXED : Bought Together Add to Cart Loading Issue
FIXED : Minor RTL CSS Issues
FIXED : Minor CSS Issues

V1.2.1 released on 14-03-2020
===========================================================
ADDED : Option Sticky Add to Cart/Buy Now button on single product page in Mobile device
ADDED : Option Sticky Proceed To Checkout button on cart page in Mobile device
ADDED : Option Sticky Place Order button on checkout page in Mobile device
ADDED : WooCommerce Compatible 4.0.0
ADDED : YITH WooCommerce Wishlist Compatible 3.0.9
UPDATED : Kapee Extensions Plugin 1.0.7
UPDATED : Revolution Slider 6.2.2
UPDATED : Language POT Files
FIXED : PHP 7.3.x warnings in Mobile Bottom Navbar
FIXED : Product Categories Elements CSS Issue
FIXED : Minor RTL CSS Issues
FIXED : Minor CSS Issues

V1.2.0 released on 29-02-2020
===========================================================
ADDED : NEW FEATURE Mobile Bottom Navbar
ADDED : NEW FEATURE Sticky Single Product Buttons in Mobile Bottom  
ADDED : NEw FEATURE Mobile Categories Menu
ADDED : Mobile bottom Navbar Options
ADDED : Option Newsletter Popup Show on Front Page or All Pages
ADDED : WooCommerce Compatible 3.9.2
ADDED : YITH WooCommerce Wishlist Compatible 3.0.6
IMPROVED : Mobile Layout Design
IMPROVED : Mobile Layout Cart Page Design
IMPROVED : Mobile Layout Wishlist Page Design
UPDATED : Revolution Slider 6.2.1
UPDATED : Language POT Files
FIXED : Product Countdown One Day Issue
FIXED : Minor RTL CSS Issues
FIXED : Minor CSS Issues

V1.1.5 released on 27-01-2020
===========================================================
ADDED : WordPress Compatible 5.3.2
ADDED : WooCommerce Compatible 3.9.0
ADDED : YITH WooCommerce Wishlist Compatible 3.0.5
UPDATED : Revolution Slider 6.1.7
UPDATED : Language POT Files
FIXED : Bought Together Option Not Working Issue
FIXED : Minor CSS Issues

V1.1.4 released on 14-12-2019
===========================================================
ADDED : WordPress Compatible 5.3.1
ADDED : YITH WooCommerce Wishlist Compatible 3.0.3
ADDED : NEW Sidebar Sticky Option
ADDED : Product Categories Page Title Color Option
UPDATED : Kapee Extensions Plugin 1.0.6
UPDATED : WPBakery Page Builder 6.1.0
FIXED : Mini Search Icon Header transparent Color Issue
FIXED : Product Shortcode Display in List View When Set Default Option
FIXED : Product Categories Display in List View in Shop Page
FIXED : YITH WooCommerce Wishlist Issue
FIXED : POST Archive Page Empty Issue
FIXED : Minor CSS Issues
UPDATED : RTL CSS

V1.1.3 released on 28-11-2019
===========================================================
ADDED : WordPress Compatible 5.3.0
UPDATED : Revolution Slider 6.1.5
UPDATED : Language POT Files
FIXED : Full Width Issue
FIXED : Shop Page Sidebar
FIXED : Translate language issue
FIXED : Minor CSS Issues

V1.1.2 released on 06-11-2019
===========================================================
ADDED : WooCommerce Compatible 3.8.0
ADDED : Newsletter Popup Supported Shortcode
UPDATED : Language POT Files

V1.1.1 released on 24-10-2019
===========================================================
ADDED : NEW DEMO - 3 Maintenance Pages
UPDATED : Kapee Extensions Plugin 1.0.5
UPDATED : Dummy Content
UPDATED : Documentation

V1.1.0 released on 23-10-2019
===========================================================
ADDED : NEW DEMO - Dokan ( Dokan Marketplace )
ADDED : NEW DEMO - WCMP( WC Marketplace )
ADDED : NEW DEMO - WCVendors ( WC Vendors Marketplace )
ADDED : NEW DEMO - WCFM ( Frontend Manager )
ADDED : Dokan Vendors VC Element With 5 Styles
ADDED : WCMP Vendors VC Element With 5 Styles
ADDED : WCVendors VC Element With 5 Styles
ADDED : WCFM Vendors VC Element With 5 Styles
ADDED : Extand Options for VC Row,VC Column Element
ADDED : WordPress Compatible 5.2.4
UPDATED : Kapee Extensions Plugin 1.0.4
UPDATED : WooCommerce Compatible 3.7.1
UPDATED : Language POT Files
UPDATED : Dummy Content
UPDATED : Documentation
FIXED : Single product Page Scroll Down Automatically Issue in Chrome
FIXED : Single Product Page Swatches Issue with Alidrop Woo Plugin
FIXED : Sidebar Widget Menu Toggle Issue On Third Level Menu Item
FIXED : Minor JS Issues
FIXED : Minor CSS Issues

V1.0.7 released on 09-10-2019
===========================================================
FIXED : Ajax Add To Cart Issue
FIXED : Minor CSS Issues

V1.0.6 released on 04-10-2019
===========================================================
ADDED : AliDropship Woo Plugin Compatible
ADDED : Single Product Tabs to Accordions on Mobile Device
ADDED : Collapse Footer Widgets on Mobile Device
ADDED : Option Collapse Footer Widgets
ADDED : Wishlist on Header
ADDED : Filter My Account Text on Header
UPDATED : Kapee Extensions Plugin 1.0.3
UPDATED : Revolution Slider Plugin 6.1.3
UPDATED : Language POT Files
FIXED : Single Product Variation Swatches Out of Area Issue
FIXED : Minor CSS Issues

V1.0.5 released on 27-09-2019
===========================================================
IMPROVED : Demo Import Process
UPDATED : Kapee Extensions Plugin 1.0.2
FIXED : Import Demo Data Issue
FIXED : Minor CSS Issues

V1.0.4 released on 20-09-2019
===========================================================
ADDED : Header Style 6 & 7
ADDED : Product Page Price Summary Tooltip Show in Mobile
ADDED : Product Page Terms And Conditions Tooltip Show in Mobile
IMPROVED : Code Improvement For Envato Requirement
UPDATED : Revolution Slider Plugin 6.1.2
UPDATED : Language POT Files
UPDATED : Documentation
FIXED : Out of Stock Message for Variable Product
FIXED : Registraion Button Text
FIXED : Disable Zoom On a Mobile Web Page
FIXED : Product Page Sticky scroll
FIXED : Minor JS issue
FIXED : Minor CSS Issues

V1.0.3 released on 14-09-2019
===========================================================
ADDED : YITH Woocommerce Request A Quote Compatible
ADDED : Element Secondary Menu in Header Navigation
ADDED : Option Wide Layout Site
UPDATED : Revolution Slider 6.1.1
FIXED : Product Gallery Thumbnail Issue
FIXED : Product Price Summary Out Area Issue
FIXED : Product Attribute Swatch Issue
FIXED : Footer Payment Logo Issue
FIXED : Minor CSS Issues

V1.0.2 released on 02-09-2019
===========================================================
ADDED : Product Category Header Banner 
ADDED : Product Category Custom Sidebar Option
ADDED : Option Single Line Product/Category/Widget Title
UPDATED : Language POT Files
UPDATED : Kapee Extensions Plugin 1.0.1
UPDATED : Revolution Slider 6.1.0
FIXED : Header Manager Secondary Menu Issue
FIXED : Login Popup Issue
FIXED : Dynamic Color Issue
FIXED : Minor CSS Issues

V1.0.1 released on 14-08-2019
===========================================================
ADDED :  WooCommerce Compatible 3.7.0
UPDATED : Revolution Slider 6.0.9
FIXED : Compare Button Issue
FIXED : Minor CSS Issue

V1.0.0 released on 09-08-2019
===========================================================
- Initial release

</pre>
				</div>
			</div>
		</div>
	</div>
</div>
<?php 
require_once KAPEE_INC_DIR.'admin/dashboard/footer.php';
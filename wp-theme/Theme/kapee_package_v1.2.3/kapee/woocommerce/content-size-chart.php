<?php 
/**
 * The template for displaying product size chart
 * $title
 * $content
 * $table_html
 * $chart_id
 */
 
defined( 'ABSPATH' ) || exit;
?>
<div class="kapee-product-sizechart">
	<div class="sizechart-header row">
		<div class="col-12"><h2><?php esc_html_e('Size Chart','kapee');?></h2></div>
	</div>
	<div class="product-sizechart-inner row">
		<div class="col-12 col-md-6 table-responsive"><?php echo wp_kses_post( $table_html );?></div>
		<div class="col-12 col-md-6"><?php echo wp_kses_post( $content );?></div>
	</div>
</div>
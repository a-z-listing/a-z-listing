<?php
/**
 * Default multicolumn template for the A-Z Listing plugin
 *
 * This template will be given the variable `$a_z_query` which is an instance of
 * `A_Z_Listing`.
 *
 * You can override this template by copying this file into your theme
 * directory.
 *
 * @package a-z-listing
 */

$_a_z_listing_colcount  = 3;
$_a_z_listing_minpercol = 10;
?>
<div id="az-tabs">
	<div id="letters">
		<div class="az-letters">
			<?php $a_z_query->the_letters(); ?>
		</div>
	</div>
	<?php if ( $a_z_query->have_letters() ) : ?>
	<div id="az-slider">
		<div id="inner-slider">
			<?php
			while ( $a_z_query->have_letters() ) :
				$a_z_query->the_letter();
				?>
				<?php if ( $a_z_query->have_items() ) : ?>
					<?php
					$item_count   = $a_z_query->get_the_letter_count();
					$column_limit = round( $item_count / $_a_z_listing_minpercol );
					if ( $column_limit > $_a_z_listing_colcount ) {
						$column_limit = $_a_z_listing_colcount;
					}
					?>
					<div class="letter-section" id="<?php $a_z_query->the_letter_id(); ?>">
						<h2 class="letter-title">
							<span><?php $a_z_query->the_letter_title(); ?></span>
						</h2>
						<ul class="columns max-<?php echo $column_limit; ?>-columns">
							<?php
							while ( $a_z_query->have_items() ) :
								$a_z_query->the_item();
								?>
								<li>
									<a href="<?php $a_z_query->the_permalink(); ?>"><?php $a_z_query->the_title(); ?></a>
								</li>
							<?php endwhile; ?>
						</ul>
						<div class="back-to-top"><a href="#letters"><?php _e( 'Back to top', 'a-z-listing' ); ?></a></div>
					</div>
					<?php
				endif;
			endwhile;
			?>
		</div>
	</div>
</div>
<?php else : ?>
	<p><?php esc_html_e( 'There are no posts included in this index.', 'a-z-listing' ); ?></p>
	<?php
endif;

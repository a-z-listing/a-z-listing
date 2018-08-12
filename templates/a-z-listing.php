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
<style>
	.letter-section div.columns {
		column-count: <?php echo $_a_z_listing_colcount; ?>;
	}
	@supports (display: grid) {
		.letter-section div.columns {
			grid-template-columns: repeat(auto-fill, minmax(10rem, 1fr));
		}
	}
</style>
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
				<div class="letter-section" id="<?php $a_z_query->the_letter_id(); ?>">
					<h2 class="letter-title">
						<span><?php $a_z_query->the_letter_title(); ?></span>
					</h2>
					<div class="columns">
						<?php
						$i = 0;
						$j = 0;

						$numpercol = ceil( $a_z_query->get_the_letter_count() / $_a_z_listing_colcount );

						while ( $a_z_query->have_items() ) :
							$a_z_query->the_item();
						?>
							<?php if ( 0 === $i++ ) : ?>
								<div class="column"><ul>
							<?php
							endif;

							$j++;
							?>
							<li>
								<a href="<?php $a_z_query->the_permalink(); ?>"><?php $a_z_query->the_title(); ?></a>
							</li>
							<?php if ( ( $_a_z_listing_minpercol - $i <= 0 && $numpercol - $i <= 0 ) || $a_z_query->get_the_letter_count() <= $j ) : ?>
								</ul></div>
								<?php
								$i = 0;
							endif;
						endwhile;
						?>
					</div>
					<div class="back-to-top"><a href="#letters"><?php _e( 'Back to top', 'a-z-listing' ); ?></a></div>
				</div>
			<?php
			endif;
		endwhile;
		?>
	</div>
</div>
<?php else : ?>
	<p><?php esc_html_e( 'There are no posts included in this index.', 'a-z-listing' ); ?></p>
<?php
endif;

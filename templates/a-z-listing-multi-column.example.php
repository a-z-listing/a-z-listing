<?php
$_a_z_listing_colcount = 3;
$_a_z_listing_minpercol = 10;
?>
<style>
	.letter-section > div {
		width: calc( 100% / <?php echo esc_html( $a_z_listing_colcount ); ?> );
	}
</style>
<div id="letters">
	<div class="az-letters">
		<?php $a_z_query->the_letters(); ?><div class="clear empty"></div>
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
					<h2>
						<span><?php $a_z_query->the_letter_title(); ?></span>
					</h2>
					<?php $i = 0; ?>
					<?php $j = 0; ?>
					<?php $numpercol = ceil( $a_z_query->get_the_letter_count() / $_a_z_listing_colcount ); ?>
					<?php
					while ( $a_z_query->have_items() ) :
						$a_z_query->the_item();
					?>
						<?php if ( 0 === $i++ ) : ?>
							<div><ul>
						<?php endif; ?>
						<?php $j++; ?>
						<li>
							<a href="<?php $a_z_query->the_permalink(); ?>"><?php $a_z_query->the_title(); ?></a>
						</li>
						<?php if ( ( $_a_z_listing_minpercol - $i <= 0 && $numpercol - $i <= 0 ) || $a_z_query->get_the_letter_count() <= $j ) : ?>
							</ul></div>
							<?php $i = 0; ?>
						<?php endif; ?>
					<?php endwhile; ?>
					<div class="clear empty"></div>
				</div>
			<?php endif; ?>
		<?php endwhile; ?>
	</div>
</div>
<?php else : ?>
	<p><?php esc_html_e( 'There are no posts included in this index.', 'a-z-listing' ); ?></p>
<?php
endif;

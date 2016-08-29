<?php
$_a_z_listing_colcount = 3,
$_a_z_listing_minpercol = 10;
?>
<div id="letters">
	<div class="az-letters">
		<?php the_a_z_letters(); ?><div class="clear empty"></div>
	</div>
</div>
<?php if ( have_a_z_letters() ) : ?>
<div id="az-slider">
	<div id="inner-slider">
		<?php while ( have_a_z_letters() ) : the_a_z_letter(); ?>
			<?php if ( have_a_z_items() ) : ?>
				<div class="letter-section" id="<?php the_a_z_letter_id(); ?>">
					<h2>
						<span><?php the_a_z_letter_title(); ?></span>
					</h2>
					<?php $i = $j = 0; ?>
					<?php $numpercol = ceil( num_a_z_posts() / $_a_z_listing_colcount ); ?>
					<?php while ( have_a_z_items() ) : the_a_z_item(); ?>
						<?php if ( 0 === $i++ ) : ?>
							<div><ul>
						<?php endif; ?>
						<?php $j++; ?>
						<li>
							<a href="<?php the_permalink(); ?>"><?php the_a_z_item_title(); ?></a>
						</li>
						<?php if ( ( $_a_z_listing_minpercol - $i <= 0 && $numpercol - $i <= 0 ) || num_a_z_posts() <= $j ) : ?>
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
<?php endif;

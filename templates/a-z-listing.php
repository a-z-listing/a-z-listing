<?php
/**
 * This template will be provided with the variable $a_z_query which is an A_Z_Listing instance.
 * You can override this template by copying this file into your theme directory.
 */
?>
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

					<ul>
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
			<?php endif; ?>
		<?php endwhile; ?>
	</div>
</div>
<?php else : ?>
	<p><?php esc_html_e( 'There are no posts included in this index.', 'a-z-listing' ); ?></p>
<?php
endif;

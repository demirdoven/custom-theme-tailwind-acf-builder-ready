<?php
/**
 * The template for displaying archive pages
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package animal-management
 */

get_header();
?>
<section id="primary">
	<main id="main">
 
		<div class="archive-header-part"><?php block_template_part('courses-archive-header'); ?></div>

		<div class="container mx-auto px-6">
			<div class="grid grid-cols-4 gap-x-8">

				<div class="filter-wrapper md:col-span-1 col-span-4 mb-8 md:mb-0">
					
					<?php
					$taxonomies = array('campus', 'course-type');

					foreach($taxonomies as $tax){
						$taxonomy_obj = get_taxonomy($tax);

						?>
						<div class="filter-box mb-4 md:mb-8" data-tax="<?php echo $tax; ?>">
							<div class="filter-title bg-custom-color-1 p-4 cursor-pointer relative font-semibold">Filter by <?php echo $taxonomy_obj->labels->name; ?> <i class="block w-[16px] h-[16px] absolute top-[50%] mt-[-6px] right-4 -translate-y-[50%] fa-solid fa-chevron-up"></i></div>
							<ul class="options bg-custom-color-2 p-4">
								<li>
									<label for="<?php echo $tax; ?>-all" class="cursor-pointer"><input type="checkbox" id="<?php echo $tax; ?>-all" name="<?php echo $tax; ?>[]" value="all"/> <span class="ml-2 leading-8"><?php echo __('All', 'animal-management'); ?></span></label>
								</li>
								<?php
								$terms = get_terms( array(
									'taxonomy' 	 => $tax,
									'hide_empty' => false
								));
						
								if( !empty($terms) ) {
									foreach( $terms as $term ) {
										?>
										<li>
											<label for="<?php echo $tax.'-'.$term->slug; ?>" class="cursor-pointer"><input type="checkbox" id="<?php echo $tax.'-'.$term->slug; ?>" name="<?php echo $tax; ?>[]" value="<?php echo $term->slug; ?>"/> <span class="ml-2 leading-8"><?php echo $term->name; ?></span></label>
										</li>
										<?php
									}
								}
								?>
							</ul>
						</div>
						<?php
					}
					?>
					
					<button class="apply-filters w-full md:w-auto bg-custom-color-5 py-2 px-4 rounded-sm font-semibold text-custom-color-4"><?php echo __('Apply Now', 'animal-management'); ?></button>

				</div>

				<div class="courses-wrapper grid-flow-row auto-rows-max col-span-4 md:col-span-3 grid md:grid-cols-3 grid-col-1 w-full gap-8">
					
					<?php
					if ( have_posts() ) :
						while ( have_posts() ) :
							the_post();
							get_template_part( 'template-parts/content/content', 'courses-list' );
						endwhile;
					else :
						echo 'No courses found!';
					endif;
					?>

				</div>

			</div>
		</div>

	
	</main><!-- #main -->
</section><!-- #primary -->
<?php
get_footer();

<?php global $post; ?>
<div class="card shadow-xl hover:shadow-2xl">
    <?php
    if( has_post_thumbnail() ){
        $thumb_src = wp_get_attachment_image_src(get_post_thumbnail_id(), 'large');
        ?>
        <a href="<?php echo get_permalink(); ?>">
            <img src="<?php echo $thumb_src[0]; ?>" class="w-full aspect-video object-cover	object-center" alt="<?php the_title(); ?>">
        </a>
        <?php
    }
    ?>
    <div class="card-body p-4">
        <a href="<?php echo get_permalink(); ?>"><h2 class="font-semibold text-lg"><?php the_title(); ?></h2></a>
        <ul class="card-metas flex gap-x-4 mt-4">
            <?php
            $course_types = get_the_terms( $post->ID, 'course-type' );
            if( $course_types && ! is_wp_error( $course_types ) ){
                foreach ( $course_types as $course_type ) {
                    echo '<li>'.$course_type->name.'</li>';
                }
            }

            $duration = get_post_meta($post->ID, 'duration', true);
            if( $duration && $duration != ''){
                echo '<li>'.$duration.'</li>';
            }
            ?>
        </ul>
    </div>
    <div class="card-footer p-4 bg-custom-color-3">
        <ul class="card-dots flex gap-x-2">
            <?php
            $campuses = get_the_terms( $post->ID, 'campus' );
            if( $campuses && ! is_wp_error( $campuses ) ){
                foreach ( $campuses as $campuses ) {
                    ?>
                    <li class="group relative">
                        <span class="dot"><?php echo animal_management_str_first_letters($campuses->name); ?></span>
                        <span class="dot-tooltip"><?php echo $campuses->name; ?></span>
                    </li>
                    <?php
                }
            }
            ?>
        </ul>
    </div>
</div>
<?php
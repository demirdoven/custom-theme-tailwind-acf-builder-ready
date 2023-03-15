<?php
/**
 * animal-management functions and definitions
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package animal-management
 */

use StoutLogic\AcfBuilder\FieldsBuilder;

if ( ! defined( 'ANIMAL_MANAGEMENT_VERSION' ) ) {
	define( 'ANIMAL_MANAGEMENT_VERSION', '1.0' );
}

if ( ! function_exists( 'animal_management_setup' ) ) :
	/**
	 * Sets up theme defaults and registers support for various WordPress features.
	 *
	 * Note that this function is hooked into the after_setup_theme hook, which
	 * runs before the init hook. The init hook is too late for some features, such
	 * as indicating support for post thumbnails.
	 */
	function animal_management_setup() {
		/*
		 * Make theme available for translation.
		 * Translations can be filed in the /languages/ directory.
		 * If you're building a theme based on animal-management, use a find and replace
		 * to change 'animal-management' to the name of your theme in all the template files.
		 */
		load_theme_textdomain( 'animal-management', get_template_directory() . '/languages' );

		// Add default posts and comments RSS feed links to head.
		add_theme_support( 'automatic-feed-links' );

		/*
		 * Let WordPress manage the document title.
		 * By adding theme support, we declare that this theme does not use a
		 * hard-coded <title> tag in the document head, and expect WordPress to
		 * provide it for us.
		 */
		add_theme_support( 'title-tag' );

		/*
		 * Enable support for Post Thumbnails on posts and pages.
		 *
		 * @link https://developer.wordpress.org/themes/functionality/featured-images-post-thumbnails/
		 */
		add_theme_support( 'post-thumbnails' );

		// This theme uses wp_nav_menu() in two locations.
		register_nav_menus(
			array(
				'menu-1' => __( 'Primary', 'animal-management' ),
				'menu-2' => __( 'Footer Menu', 'animal-management' ),
			)
		);

		/*
		 * Switch default core markup for search form, comment form, and comments
		 * to output valid HTML5.
		 */
		add_theme_support(
			'html5',
			array(
				'search-form',
				'comment-form',
				'comment-list',
				'gallery',
				'caption',
				'style',
				'script',
			)
		);

		// Add theme support for selective refresh for widgets.
		add_theme_support( 'customize-selective-refresh-widgets' );

		// Add support for editor styles.
		add_theme_support( 'editor-styles' );

		// Enqueue editor styles.
		add_editor_style( 'style-editor.css' );

		// Add support for responsive embedded content.
		add_theme_support( 'responsive-embeds' );

		add_theme_support( 'block-template-parts' );


		
	}
endif;
add_action( 'after_setup_theme', 'animal_management_setup' );


/**
 * Register widget area.
 *
 * @link https://developer.wordpress.org/themes/functionality/sidebars/#registering-a-sidebar
 */
function animal_management_widgets_init() {
	register_sidebar(
		array(
			'name'          => __( 'Footer', 'animal-management' ),
			'id'            => 'sidebar-1',
			'description'   => __( 'Add widgets here to appear in your footer.', 'animal-management' ),
			'before_widget' => '<section id="%1$s" class="widget %2$s">',
			'after_widget'  => '</section>',
			'before_title'  => '<h2 class="widget-title">',
			'after_title'   => '</h2>',
		)
	);
}
add_action( 'widgets_init', 'animal_management_widgets_init' );

/**
 * Enqueue scripts and styles.
 */
function animal_management_scripts() {
	wp_enqueue_style( 'animal-management-style', get_stylesheet_uri(), array(), ANIMAL_MANAGEMENT_VERSION );
	wp_enqueue_style( 'font-awesome', 'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.1/css/all.min.css' );
	wp_enqueue_script( 'animal-management-script', get_template_directory_uri() . '/js/script.min.js', array('jquery'), ANIMAL_MANAGEMENT_VERSION, true );
	wp_localize_script( 'animal-management-script', 'frontend_ajax_object',
		array( 
			'ajaxurl'    => admin_url( 'admin-ajax.php' ),
            'ajax_nonce' => wp_create_nonce( 'am_ajax_nonce' ),
		)
	);

	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}
}
add_action( 'wp_enqueue_scripts', 'animal_management_scripts' );

/**
 * Add the block editor class to TinyMCE.
 *
 * This allows TinyMCE to use Tailwind Typography styles.
 *
 * @param array $settings TinyMCE settings.
 * @return array
 */
function animal_management_tinymce_add_class( $settings ) {
	$settings['body_class'] = 'block-editor-block-list__layout';
	return $settings;
}
add_filter( 'tiny_mce_before_init', 'animal_management_tinymce_add_class' );

require get_template_directory() . '/inc/template-tags.php';
require get_template_directory() . '/inc/template-functions.php';

function animal_management_cpt() {
    $singular   = 'Course';
    $plural     = 'Courses';

	$args = array(
		'labels'             => array(
            'name'                  => _x( $plural, 'animal-management' ),
            'singular_name'         => _x( $singular, 'animal-management' ),
            'menu_name'             => _x( $plural, 'animal-management' ),
            'name_admin_bar'        => _x( $singular, 'animal-management' ),
            'add_new'               => __( 'Add New', 'animal-management' ),
            'add_new_item'          => __( 'Add New '.$singular, 'animal-management' ),
            'new_item'              => __( 'New '.$singular, 'animal-management' ),
            'edit_item'             => __( 'Edit '.$singular, 'animal-management' ),
            'view_item'             => __( 'View '.$singular, 'animal-management' ),
            'all_items'             => __( 'All '.$plural, 'animal-management' ),
            'search_items'          => __( 'Search '.$plural, 'animal-management' ),
            'parent_item_colon'     => __( 'Parent '.$plural.':', 'animal-management' ),
            'not_found'             => __( 'No '.$plural.' found.', 'animal-management' ),
            'not_found_in_trash'    => __( 'No '.$plural.' found in Trash.', 'animal-management' ),
        ),
		'public'             => true,
		'publicly_queryable' => true,
		'show_ui'            => true,
		'show_in_menu'       => true,
		'query_var'          => true,
		'rewrite'            => array( 'slug' => 'courses' ),
		'capability_type'    => 'post',
		'has_archive'        => true,
		'hierarchical'       => false,
		'menu_position'      => null,
		'supports'           => array( 'title', 'editor', 'author', 'thumbnail', 'excerpt', 'comments' ),
	);

	register_post_type( 'course', $args );

    $taxonomies = array('Course type', 'Campus');
    
    foreach ($taxonomies as $tax) {
    
        register_taxonomy( sanitize_title($tax), 'course', array(
            'label'             => __( $tax, 'animal-management' ),
            'hierarchical'      => true,
            'show_admin_column' => true,
        ) );
    
    }
    flush_rewrite_rules();
}
add_action( 'init', 'animal_management_cpt' );

function animal_management_str_first_letters($string){
    $split = explode(' ', $string);
    $letters = '';
    foreach( $split as $letter){
        $letters .= $letter[0];
    }
    return strtoupper($letters);
}

function animal_management_filter_courses(){

    $values = $_POST['values'];
    $args   = array(
        'post_type'         => 'course',
        'post_status'       => 'publish',
        'posts_per_page'    => -1,
        
    );
    
    $args['tax_query'] = [];
    
    if( !empty($values) ){

        foreach( $values as $key=>$value){
            $taxonomy = $key;
            $terms = $value;

            $args['tax_query'][] = array(
                'taxonomy' => $key,
                'field'    => 'slug',
                'terms'    => $value,
                'operator' => 'IN',
            );

        }
        $args['tax_query']['relation'] = 'AND';
    }

    $query = new WP_Query( $args );

    if( $query->have_posts() ){
        ob_start();

        while( $query->have_posts() ){
            $query->the_post();
            global $post;

            ?>
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
        }

        $render = ob_get_clean();
        $status = 'success';

    }else {
        $render = 'No courses found!';
        $status = 'fail';
    }
    echo json_encode(array('render'=>$render, 'status'=>$status, 'args'=>$args));
    wp_die();
}
add_action('wp_ajax_animal_management_filter_courses', 'animal_management_filter_courses');
add_action('wp_ajax_nopriv_animal_management_filter_courses', 'animal_management_filter_courses');

function build_course_fields(){
    
    require_once get_template_directory().'/vendor/autoload.php';
    $course_details = new StoutLogic\AcfBuilder\FieldsBuilder('course_details');
    $course_details
        ->addText('course_code')
        ->addText('duration')
        ->setLocation('post_type', '==', 'course');

    add_action('acf/init', function() use ($course_details) {
        acf_add_local_field_group($course_details->build());
    });

}
if( class_exists('ACF') ){
    build_course_fields();
}
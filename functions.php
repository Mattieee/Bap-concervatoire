<?php
require TEMPLATEPATH.'/framework/theme.php';
$theme = new Theme(array(
	'menus' => array(
		'nav' => 'Navigation'
		)
	));




require_once('wp_bootstrap_navwalker.php');



 /**
	 * Enqueue scripts
	 *
	 * @param string $handle Script name
	 * @param string $src Script url
	 * @param array $deps (optional) Array of script names on which this script depends
	 * @param string|bool $ver (optional) Script version (used for cache busting), set to null to disable
	 * @param bool $in_footer (optional) Whether to enqueue the script before </head> or before </body>
	 */
 function theme_multipage_scripts() {
 	wp_deregister_script( 'Jquery' );
 	wp_enqueue_script( 'Jquery', 'https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js');
 	wp_enqueue_script( 'bootstrapsjs', 'https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js', array( 'jquery' ), false, false);
 	wp_enqueue_style( 'bootstrapcss', 'https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css');
 }

 add_action( 'wp_enqueue_scripts', 'theme_multipage_scripts' );


 add_theme_support('post-thumbnails');


 add_action( 'wp_enqueue_scripts', 'theme_multipage_scripts' );


 register_nav_menus( 
 	array(


 		'primaire'=> __('Barre de menu du thème','test')	
 		) 
 	);

	function arphabet_widgets_init() {

		register_sidebar( array(
			'name' => 'Home right sidebar',
			'id' => 'home_right_1',
			'before_widget' => '<div>',
			'after_widget' => '</div>',
			'before_title' => '<h2 class="rounded">',
			'after_title' => '</h2>',
		) );
	}
	add_action( 'widgets_init', 'arphabet_widgets_init' );

	wp_enqueue_style( 'speackers', get_template_directory_uri() . '/css/spearckers.css' );

	wp_enqueue_style( 'slick', get_template_directory_uri() . '/js/slick/slick.css' );
	wp_enqueue_style( 'slick-theme', get_template_directory_uri() . '/js/slick/slick-theme.css' );

	function load_my_script(){
		wp_register_script(
			'slick',
			get_template_directory_uri() . '/js/slick/slick.js',
			array( 'jquery' )
		);
		wp_enqueue_script( 'slick' );

		wp_register_script(
			'speakers',
			get_template_directory_uri() . '/js/speakers.js',
			array( 'jquery' )
		);
		wp_enqueue_script( 'speakers' );

		wp_register_script(
			'foggy',
			get_template_directory_uri() . '/js/jquery.foggy.min.js',
			array( 'jquery' )
		);
		wp_enqueue_script( 'foggy' );
	}

	add_action('wp_enqueue_scripts', 'load_my_script');

	function init_speakers_list($atts, $content = null ){
		$atts = shortcode_atts(
			array(
				'slug' => "",
			), $atts);
		$html = '';
		$terms = get_terms($atts);
		if( !empty($terms) ){
			$term = $terms[0];

			$posts_array = get_posts(array(
				'post_type' => 'team_member',
				'tax_query' => array(
					array(
						'taxonomy' => 'team_group',
						'field' => 'term_id',
						'terms' => $term->term_id
					)
				)
			));


			$html .= '<div class="title-speackers">Speakers</div>';
			$html .= '<div class="speacker-wrapper-content">';
			foreach($posts_array as $post){
				$html .= '<div class="row-speacker">';
					$html .= '<div class="image-wrapper-r">';
					$html .= get_the_post_thumbnail( $post->ID , $size = [
						500,500
					]);
					$html .= '</div>';
					$html .= '<div class="title-wrapper">';
						$html .= get_the_title( $post->ID );
					$html .= '</div>';
					$html .= '<div class="content-wrapper">';
						$html .= $post->post_content;
					$html .= '</div>';


					$html .= '<div class="btn-wrapper-conference">
						<a href="#">
							Découvrir les conférence de ' . get_the_title( $post->ID ) . '
						</a>
					</div>';

				$html .= '</div>';
			}
			$html .= '</div>';
		}
		return $html;
	}
	add_shortcode( 'speackers_list', 'init_speakers_list' );
?>
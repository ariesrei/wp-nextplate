<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       https://ariesmeralles.website
 * @since      1.0.0
 *
 * @package    Wp_Nextplate
 * @subpackage Wp_Nextplate/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 *
 * @package    Wp_Nextplate
 * @subpackage Wp_Nextplate/public
 * @author     Aries Meralles <arsrymeralles@gmail.com>
 */
class Wp_Nextplate_Public {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of the plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

		add_action('rest_api_init', array($this, 'register_routes'));
	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Wp_Nextplate_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Wp_Nextplate_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/wp-nextplate-public.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Wp_Nextplate_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Wp_Nextplate_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/wp-nextplate-public.js', array( 'jquery' ), $this->version, false );

	}

	
    /**
     * Register custom API routes for Nextplate boilerplate.
	 * WP headless + NextJs
     */
    public function register_routes() {

        register_rest_route('wp/v2', '/frontpage/', array(
            'methods'   => 'GET',
            'callback'  => array($this, 'get_frontpage_callback'),
        ));
 
		register_rest_route( 'wp/v2', '/wp-next-menu/', array(
			'methods' => 'GET',
			'callback' => array($this, 'wp_next_menu_callback'),
		));

    }

	/**
	 * Callback function for the example endpoint.
	 *
	 * @param WP_REST_Request $request The request object.
	 * @return WP_REST_Response
	 */
    public function get_frontpage_callback($request) {
		
        // Get WP options front page from settings > reading.
		$frontpage_id = get_option('page_on_front');

		// Handle if error.
		if ( empty( $frontpage_id ) ) {
			return 'error';
		}

		$request  = new \WP_REST_Request( 'GET', '/wp/v2/pages/' . $frontpage_id );
		$response = rest_do_request( $request );

		if ( $response->is_error() ) {
			return 'error';
		}

		return $response->get_data();
    }

		
	public function wp_next_menu_callback() { 
		$menuLocations = get_nav_menu_locations();

		$primaryMenuID = $menuLocations['primary'];
		$footerMenuID = $menuLocations['footer'];

		$results = array(
			'main' => $this->getWPMenus($primaryMenuID),
			'footer' => $this->getWPMenus($footerMenuID)
		);

		return $results;		
	}


	public function getWPMenus($id) {

		$navigation = wp_get_nav_menu_items($id);
		$children = array();
		$clean = array();

		foreach ($navigation as $nav) {
			if( $nav->menu_item_parent == "0") {
				$clean[$nav->ID] = array( 'name' => $nav->title, 'url' => $nav->url);
			} else {
				array_push($children, array( 'name' => $nav->title, 'url' => $nav->url));
				$clean[$nav->menu_item_parent] = array_merge( $clean[$nav->menu_item_parent], array( 'hasChildren' => true, 'children' => $children) );
			}
		}

		$clean = array_merge($clean, array() );

		return $clean;
	}

 
}

<?php
/**
 * WordPress_Gist.
 *
 * @package     WordPress_Gist
 * @author      Michael Novotny <manovotny@gmail.com>
 * @license     GPL-3.0+
 * @link        https://github.com/manovotny/wordpress-gist
 * @copyright   2013 Michael Novotny
 */

/*
/\/\/\/\/\/\/\/\/\/\/\/\/\/\/\/\/\/\ CONTENTS /\/\/\/\/\/\/\/\/\/\/\/\/\/\//\/\/\/\/\

    1. Properties
    2. Constructor
    3. Actions
    4. Shortcodes

/\/\/\/\/\/\/\/\/\/\/\/\/\/\/\/\/\/\/\/\/\/\/\/\/\/\/\/\/\/\/\/\//\/\/\/\/\/\/\/\/\/\
*/

class WordPress_Gist {

    /* Properties
    ---------------------------------------------------------------------------------- */

    /* Instance
    ---------------------------------------------- */

    /**
     * Instance of the WordPress_Gist class.
     *
     * @access      protected static
     * @var         WordPress_Gist
     *
     * @since       2.0.0
     * @version     1.0.0
     */
    protected static $instance = null;

    /**
     * Get accessor method for instance property.
     *
     * @return      WordPress_Gist  Instance of the WordPress_Gist class.
     *
     * @since       2.0.0
     * @version     1.0.0
     */
    public static function get_instance() {

        // Check if an instance has not been created yet.
        if ( null == self::$instance ) {

            // Set instance of class.
            self::$instance = new self;

        } // end if

        // Return instance.
        return self::$instance;

    } // end get_instance

    /* Slug
    ---------------------------------------------- */

    /**
     * Plugin unique identifier.
     *
     * @access      protected
     * @var         string
     *
     * @since       2.0.0
     * @version     1.0.0
     */
    protected $slug = 'wordpress-gist';

    /* Version
    ---------------------------------------------- */

    /**
     * Plugin version, used for cache-busting of style and script file references.
     *
     * @access      protected
     * @var         string
     *
     * @since       2.0.0
     * @version     1.0.0
     */
    protected $version = '2.0.0';

    /* Constructor
    ---------------------------------------------------------------------------------- */
    /**
     * Initializes plugin.
     *
     * @since       1.0.0
     * @version     2.0.0
     */
    function __construct() {

        // Add shortcode hook.
        add_shortcode( 'gist', array( $this, 'wordpress_gist_shortcode' ) );

        // Register styles.
        add_action( 'wp_enqueue_scripts', array( $this, 'wordpress_gist_styles' ), 1000 );

    } // end constructor

    /* Actions
    ---------------------------------------------------------------------------------- */

    /**
     * Registers plugin styles.
     *
     * @since       1.0.0
     * @version     2.0.0
     */
    public function wordpress_gist_styles() {

        // Plugin styles.
        wp_enqueue_style( $this->slug . '-style', plugins_url( $this->slug . '/css/public.css' ), false, $this->version );

    } // end wordpress_gist_styles

    /* Shortcodes
    ---------------------------------------------------------------------------------- */
    /**
     * @param       object  $attributes     Post attributes.
     * @param       string  $content        Post content.
     *
     * @return      string                  Expanded shortcode.
     *
     * @since       1.0.0
     * @version     2.0.0
     */
    function wordpress_gist_shortcode( $attributes, $content = null ) {

        // Extract shortcode attributes.
        extract( shortcode_atts( array( 'url' => '', 'file' => '' ), $attributes ) );

        // Check that we at least have a Gist URL.
        if ( empty( $url ) ) {

            // No Gist URL.
            return '';

        } else {

            // Need to append a '.js' file extension to the Gist URL.
            $url .= '.js';

        } // end if / else

        // Check for specific file in the Gist.
        if ( ! empty( $file ) ) {

            // Append file to URL.
            $url .= '?file=' . $file;

        } // end if

        // Construct Gist script and return.
        return '<script src="' . $url . '"></script>';

    } // end wordpress_gist_shortcode

} // end class
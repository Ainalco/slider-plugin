<?php
/**
 * Plugin Name:       Custom Post and Page Slider
 * Description:       A clean, compliant post/page slider using Slick Carousel.
 * Version:           1.0.0
 * Author:            Ainal
 * Author URI:        https://octexpro.com
 * License:           GPLv2 or later
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

// Load plugin text domain
function custom_slider_load_textdomain() {
    load_plugin_textdomain( 'custom-slider', false, dirname( plugin_basename( __FILE__ ) ) . '/languages' );
}
add_action( 'plugins_loaded', 'custom_slider_load_textdomain' );

// Include settings page
require_once plugin_dir_path( __FILE__ ) . 'includes/slider-settings.php';

// Enqueue styles and scripts
function custom_slider_enqueue_assets() {
    wp_enqueue_style( 'slick-css', 'https://cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick.css' );
    wp_enqueue_style( 'custom-slider-style', plugin_dir_url( __FILE__ ) . 'css/slider-style.css', [], '1.0' );

    wp_enqueue_script( 'slick-js', 'https://cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick.min.js', array( 'jquery' ), null, true );
    wp_enqueue_script( 'custom-slider-script', plugin_dir_url( __FILE__ ) . 'js/slider-script.js', array( 'jquery', 'slick-js' ), '1.0', true );
}
add_action( 'wp_enqueue_scripts', 'custom_slider_enqueue_assets' );

// Slider shortcode
function custom_slider_shortcode() {
    $type  = get_option( 'custom_slider_type', 'post' );
    $count = absint( get_option( 'custom_slider_count', 5 ) );

    $args = array(
        'post_type'      => $type,
        'posts_per_page' => $count,
        'post_status'    => 'publish',
    );

    $query = new WP_Query( $args );

    ob_start();

    if ( $query->have_posts() ) {
        echo '<div class="custom-slider">';
        while ( $query->have_posts() ) {
            $query->the_post();
            ?>
            <div class="slider-item">
                <?php
                if ( has_post_thumbnail() ) {
                    the_post_thumbnail( 'medium' );
                }
                ?>
                <h3><?php echo esc_html( get_the_title() ); ?></h3>
            </div>
            <?php
        }
        echo '</div>';
    }
    wp_reset_postdata();

    return ob_get_clean();
}
add_shortcode( 'custom_slider', 'custom_slider_shortcode' );

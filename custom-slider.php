<?php
/*
Plugin Name: Custom Post and Page Slider
Description: A simple and unique slider for posts or pages.
Version: 1.0
Author: Ainal
*/

if (!defined('ABSPATH')) {
    exit;
}

// Include settings
require_once plugin_dir_path(__FILE__) . 'includes/slider-settings.php';

// Enqueue scripts
function custom_slider_enqueue_scripts() {
    wp_enqueue_style('slick-css', 'https://cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick.css');
    wp_enqueue_style('custom-slider-style', plugin_dir_url(__FILE__) . 'css/slider-style.css');

    wp_enqueue_script('slick-js', 'https://cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick.min.js', array('jquery'), null, true);
    wp_enqueue_script('custom-slider-script', plugin_dir_url(__FILE__) . 'js/slider-script.js', array('jquery', 'slick-js'), null, true);
}
add_action('wp_enqueue_scripts', 'custom_slider_enqueue_scripts');

// Shortcode function
function custom_slider_shortcode() {
    $type = get_option('slider_type', 'post');
    $count = get_option('slider_count', 5);

    $args = array(
        'post_type' => $type,
        'posts_per_page' => $count,
        'post_status' => 'publish',
    );

    $query = new WP_Query($args);

    ob_start();
    if ($query->have_posts()) {
        echo '<div class="custom-slider">';
        while ($query->have_posts()) {
            $query->the_post();
            echo '<div class="slider-item">';
            if (has_post_thumbnail()) {
                the_post_thumbnail('medium');
            }
            echo '<h3>' . get_the_title() . '</h3>';
            echo '</div>';
        }
        echo '</div>';
    }
    wp_reset_postdata();
    return ob_get_clean();
}
add_shortcode('custom_slider', 'custom_slider_shortcode');

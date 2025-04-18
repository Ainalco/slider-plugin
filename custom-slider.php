<?php
/**
 * Plugin Name:       Custom Post and Page Slider
 * Description:       A clean, compliant post/page slider using Slick Carousel.
 * Version:           1.0.0
 * Author:            Ainal
 * Author URI:        https://octexpro.com
 * License:           GPLv2 or later
 */

 // Exit if accessed directly
if (!defined('ABSPATH')) exit;

// Define plugin constants
define('CUSTOM_SLIDER_PATH', plugin_dir_path(__FILE__));
define('CUSTOM_SLIDER_URL', plugin_dir_url(__FILE__));

// Include admin pages
require_once CUSTOM_SLIDER_PATH . 'includes/slider-settings.php';
require_once CUSTOM_SLIDER_PATH . 'includes/create-slider.php';
require_once CUSTOM_SLIDER_PATH . 'includes/upload-slider-images.php';

// Enqueue styles and scripts
function custom_slider_enqueue_assets() {
    wp_enqueue_style('custom-slider-style', CUSTOM_SLIDER_URL . 'css/slider-style.css', [], '1.0');
    wp_enqueue_script('custom-slider-script', CUSTOM_SLIDER_URL . 'js/slider-script.js', ['jquery'], '1.0', true);
}
add_action('wp_enqueue_scripts', 'custom_slider_enqueue_assets');

// Register custom image size.
function custom_slider_register_image_size() {
    add_image_size( 'custom_slider_image', 1920, 400, false );
}
add_action( 'after_setup_theme', 'custom_slider_register_image_size' );

// Register shortcode
function custom_slider_shortcode($atts) {
    static $instance = 0;
    $instance++;
    $atts = shortcode_atts([
        'id' => ''
    ], $atts, 'custom_slider');

    $slider_id = sanitize_text_field($atts['id']);
    $sliders = get_option('custom_slider_data');
    if (!isset($sliders[$slider_id])) return '<p>Slider not found.</p>';

    $slider = $sliders[$slider_id];
    $show_captions = get_option('custom_slider_show_captions', true);
    

    ob_start();
    ?>
    <div class="custom-slider-wrapper" id="custom-slider-<?php echo esc_attr($instance); ?>" data-slider-id="<?php echo esc_attr($slider_id); ?>">
        <div class="custom-slider">
            <?php foreach ($slider['slides'] as $slide): 
                $image_url = esc_url($slide['image']);
                $attachment_id = attachment_url_to_postid($image_url);
                $custom_size_url = wp_get_attachment_image_url($attachment_id, 'custom_slider_image');

            ?>
                <div class="slide">
                    <img src="<?php echo esc_url($custom_size_url ? $custom_size_url : $image_url); ?>" alt="" />
                    <?php if ($show_captions): ?>
                        <div class="caption">
                            <h3><?php echo esc_html($slide['caption']); ?></h3>
                            <p><?php echo esc_html($slide['description']); ?></p>
                        </div>
                    <?php endif; ?>
                </div>
            <?php endforeach; ?>
        </div>
        <div class="custom-slider-controls">
            <button class="prev">&#10094;</button>
            <button class="next">&#10095;</button>
        </div>
        <div class="custom-slider-pagination"></div>
    </div>
    <?php
    return ob_get_clean();
}
add_shortcode('custom_slider', 'custom_slider_shortcode');
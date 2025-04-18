<?php
function custom_slider_menu() {
    add_options_page(
        'Custom Slider Settings',
        'Custom Slider',
        'manage_options',
        'custom-slider-settings',
        'custom_slider_settings_page'
    );
}
add_action('admin_menu', 'custom_slider_menu');

function custom_slider_settings_page() {
    ?>
    <div class="wrap">
        <h1>Custom Slider Settings</h1>
        <form method="post" action="options.php">
            <?php
            settings_fields('custom_slider_settings_group');
            do_settings_sections('custom-slider-settings');
            submit_button();
            ?>
        </form>
    </div>
    <?php
}

function custom_slider_settings_init() {
    register_setting('custom_slider_settings_group', 'slider_type');
    register_setting('custom_slider_settings_group', 'slider_count');

    add_settings_section('custom_slider_main', '', null, 'custom-slider-settings');

    add_settings_field('slider_type', 'Content Type', 'slider_type_callback', 'custom-slider-settings', 'custom_slider_main');
    add_settings_field('slider_count', 'Number of Items', 'slider_count_callback', 'custom-slider-settings', 'custom_slider_main');
}
add_action('admin_init', 'custom_slider_settings_init');

function slider_type_callback() {
    $option = get_option('slider_type', 'post');
    ?>
    <select name="slider_type">
        <option value="post" <?php selected($option, 'post'); ?>>Posts</option>
        <option value="page" <?php selected($option, 'page'); ?>>Pages</option>
    </select>
    <?php
}

function slider_count_callback() {
    $count = get_option('slider_count', 5);
    echo '<input type="number" name="slider_count" value="' . esc_attr($count) . '" min="1" max="20" />';
}

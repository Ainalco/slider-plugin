<?php
// Admin menu for Settings
add_action('admin_menu', function () {
    add_menu_page('Custom Slider Settings', 'Custom Slider', 'manage_options', 'custom-slider', 'custom_slider_settings_page', 'dashicons-images-alt2', 30);
});

// Settings page content
function custom_slider_settings_page() {
    if (isset($_POST['custom_slider_settings_save'])) {
        update_option('custom_slider_show_captions', isset($_POST['show_captions']));
        update_option('custom_slider_items_count', intval($_POST['items_count']));
        echo '<div class="updated"><p>Settings saved.</p></div>';
    }

    $show_captions = get_option('custom_slider_show_captions', true);
    $items_count = get_option('custom_slider_items_count', 3);
    ?>
    <div class="wrap">
        <h1>Slider Settings</h1>
        <form method="post">
            <table class="form-table">
                <tr>
                    <th scope="row"><label for="items_count">Number of Items</label></th>
                    <td><input name="items_count" type="number" min="1" value="<?php echo esc_attr($items_count); ?>" /></td>
                </tr>
                <tr>
                    <th scope="row">Show Captions?</th>
                    <td><input name="show_captions" type="checkbox" <?php checked($show_captions); ?> /></td>
                </tr>
            </table>
            <p><input type="submit" name="custom_slider_settings_save" class="button-primary" value="Save Settings"></p>
        </form>
    </div>
    <?php
}

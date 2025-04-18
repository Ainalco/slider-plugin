<?php
// Add submenu page under 'Custom Slider'
add_action('admin_menu', function () {
    add_submenu_page('custom-slider', 'Create Slider', 'Create Slider', 'manage_options', 'create-slider', 'custom_slider_create_page');
});

// Display the Create Slider page
function custom_slider_create_page() {
    $sliders = get_option('custom_slider_data', []);

    // Handle slider creation
    if (isset($_POST['slider_name']) && !empty($_POST['slider_name'])) {
        $id = sanitize_title($_POST['slider_name']);
        if (!isset($sliders[$id])) {
            $sliders[$id] = ['name' => sanitize_text_field($_POST['slider_name']), 'slides' => []];
            update_option('custom_slider_data', $sliders);
            echo '<div class="updated"><p>Slider created.</p></div>';
        } else {
            echo '<div class="error"><p>Slider ID already exists.</p></div>';
        }
    }

    // Handle slider deletion
    if (isset($_GET['action'], $_GET['slider_id']) && $_GET['action'] === 'delete') {
        $slider_id = sanitize_text_field($_GET['slider_id']);
        if (isset($sliders[$slider_id])) {
            unset($sliders[$slider_id]);
            update_option('custom_slider_data', $sliders);
            echo '<div class="updated"><p>Slider deleted.</p></div>';
        }
    }

    ?>
    <div class="wrap">
        <h1>Create Slider</h1>
        <form method="post">
            <input type="text" name="slider_name" placeholder="Slider Name" required />
            <input type="submit" value="Create Slider" class="button-primary" />
        </form>
        <h2>Existing Sliders</h2>
        <table class="wp-list-table widefat fixed striped">
            <thead>
                <tr>
                    <th>Slider Name</th>
                    <th>Shortcode</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($sliders as $id => $slider): ?>
                    <tr>
                        <td><?php echo esc_html($slider['name']); ?></td>
                        <td>[custom_slider id="<?php echo esc_attr( $id ); ?>"]</td>
                        <td>
                            <a href="<?php echo admin_url('admin.php?page=upload-slider-images&slider_id=' . esc_attr($id)); ?>" class="button">Edit</a>
                            <a href="<?php echo admin_url('admin.php?page=create-slider&action=delete&slider_id=' . esc_attr($id)); ?>" class="button delete-button" onclick="return confirm('Are you sure you want to delete this slider?');">Delete</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    <?php
}

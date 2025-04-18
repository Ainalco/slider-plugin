<?php

// Add submenu page under 'Custom Slider'
add_action('admin_menu', function () {
    add_submenu_page('custom-slider', 'Upload Images', 'Upload Images', 'manage_options', 'upload-slider-images', 'custom_slider_upload_page');
});



// Display the Upload Slider Images page
function custom_slider_upload_page() {
    $sliders = get_option('custom_slider_data', []);
    $selected_id = $_GET['slider_id'] ?? '';

    // Handle slide updates
    if (isset($_POST['save_slider_images']) && $selected_id && isset($sliders[$selected_id])) {
        $slides = [];
        foreach ($_POST['slide'] as $slide) {
            if (!empty($slide['image'])) {
                $slides[] = [
                    'image' => esc_url_raw($slide['image']),
                    'caption' => sanitize_text_field($slide['caption']),
                    'description' => sanitize_text_field($slide['description']),
                ];
            }
        }
        $sliders[$selected_id]['slides'] = $slides;
        update_option('custom_slider_data', $sliders);
        echo '<div class="updated"><p>Slides updated for slider: ' . esc_html($selected_id) . '</p></div>';
    }

    ?>
    <div class="wrap">
        <h1>Upload Slides</h1>

        <?php if ($selected_id && isset($sliders[$selected_id])): ?>
        <form method="post">
            <table class="form-table">
                <tbody id="slides-container">
                    <?php 
                    $index = 0;
                    if (!empty($sliders[$selected_id]['slides'])):
                        foreach ($sliders[$selected_id]['slides'] as $slide):
                    ?>
                    <tr class="slide-row">
                        <th>Slide <?php echo $index + 1; ?></th>
                        <td>
                            <input type="text" name="slide[<?php echo $index; ?>][image]" 
                                   id="slide_image_<?php echo $index; ?>" 
                                   value="<?php echo esc_url($slide['image']); ?>" />
                            <button type="button" class="button upload-button" 
                                    data-target="slide_image_<?php echo $index; ?>">Upload Image</button>
                            <br><br>
                            <input type="text" name="slide[<?php echo $index; ?>][caption]" 
                                   placeholder="Caption" value="<?php echo esc_attr($slide['caption']); ?>" />
                            <br><br>
                            <textarea name="slide[<?php echo $index; ?>][description]" 
                                      placeholder="Description"><?php echo esc_textarea($slide['description']); ?></textarea>
                        </td>
                    </tr>
                    <?php 
                        $index++;
                        endforeach;
                    endif;
                    ?>
                </tbody>
            </table>

            <!-- Button to add multiple slides via media uploader -->
            <p>
                <button type="button" class="button button-secondary" id="upload-multiple-images" data-index="<?php echo $index; ?>">
                    Upload Multiple Images
                </button>
            </p>

            <p class="submit">
                <input type="submit" name="save_slider_images" class="button-primary" value="Save Slides">
            </p>
        </form>

        <?php endif; ?>
    </div>

    <script type="text/javascript">
        jQuery(document).ready(function($){
            // Upload single image
            $('.upload-button').on('click', function(e) {
                e.preventDefault();
                var target = $(this).data('target');
                var mediaUploader = wp.media({
                    title: 'Select or Upload Image',
                    button: { text: 'Use this image' },
                    multiple: false
                }).open().on('select', function() {
                    var attachment = mediaUploader.state().get('selection').first().toJSON();
                    $('#' + target).val(attachment.url);
                });
            });

            // Upload multiple images
            $('#upload-multiple-images').on('click', function(e) {
                e.preventDefault();
                var currentIndex = parseInt($(this).data('index'));
                var mediaUploader = wp.media({
                    title: 'Upload Multiple Images',
                    button: { text: 'Add Images' },
                    multiple: true
                }).open().on('select', function() {
                    var attachments = mediaUploader.state().get('selection').toArray();
                    attachments.forEach(function(attachment, i) {
                        attachment = attachment.toJSON();
                        var html = `
                        <tr class="slide-row">
                            <th>Slide ${currentIndex + 1}</th>
                            <td>
                                <input type="text" name="slide[${currentIndex}][image]" 
                                       id="slide_image_${currentIndex}" value="${attachment.url}" />
                                <button type="button" class="button upload-button" 
                                        data-target="slide_image_${currentIndex}">Upload Image</button>
                                <br><br>
                                <input type="text" name="slide[${currentIndex}][caption]" 
                                       placeholder="Caption" value="" />
                                <br><br>
                                <textarea name="slide[${currentIndex}][description]" placeholder="Description"></textarea>
                            </td>
                        </tr>`;
                        $('#slides-container').append(html);
                        currentIndex++;
                    });
                    $('#upload-multiple-images').data('index', currentIndex); // update index
                });
            });
        });
    </script>
    <?php
}
?>

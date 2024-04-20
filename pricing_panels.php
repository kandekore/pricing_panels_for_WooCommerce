<?php
/*
Plugin Name: WooCommerce Pricing Panels
Description: Plugin to generate HTML pricing panels from WooCommerce products based on selected product category.
Version: 1.0
Author: D Kandekore
*/

// Add custom meta box for category selection
function wpb_custom_meta_box() {
    add_meta_box(
        'wpb_meta',
        'Select Product Category',
        'wpb_meta_box_callback',
        'page',
        'normal',
        'high'
    );
}
add_action('add_meta_boxes', 'wpb_custom_meta_box');

// Callback function to display category selection
function wpb_meta_box_callback($post) {
    wp_nonce_field('wpb_save_meta_box_data', 'wpb_meta_box_nonce');
    $selected_category_id = get_post_meta($post->ID, 'selected_category_id', true);
    ?>
    <label for="selected_category_id">Select a category:</label>
    <select name="selected_category_id" id="selected_category_id">
        <?php
        $args = array(
            'show_option_none' => 'Select Category',
            'taxonomy'         => 'product_cat',
            'name'             => 'selected_category_id',
            'selected'         => $selected_category_id,
            'hide_empty'       => false,
        );
        wp_dropdown_categories($args);
        ?>
    </select>
    <?php
}

// Save category selection when page is saved
function wpb_save_meta_box_data($post_id) {
    if (!isset($_POST['wpb_meta_box_nonce']) || !wp_verify_nonce($_POST['wpb_meta_box_nonce'], 'wpb_save_meta_box_data')) {
        return;
    }
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
        return;
    }
    if (!current_user_can('edit_post', $post_id)) {
        return;
    }
    if (isset($_POST['selected_category_id'])) {
        update_post_meta($post_id, 'selected_category_id', sanitize_text_field($_POST['selected_category_id']));
    }
}
add_action('save_post', 'wpb_save_meta_box_data');

// Define shortcode to output pricing panels
function pricing_panels_shortcode($atts, $content = null, $tag = '') {
    global $post;

    // Retrieve selected product category ID from the current page
    $selected_category_id = get_post_meta($post->ID, 'selected_category_id', true);

    if (empty($selected_category_id)) {
        return 'No category selected.';
    }

    $args = array(
        'post_type'      => 'product',
        'posts_per_page' => -1,
        'tax_query'      => array(
            array(
                'taxonomy' => 'product_cat',
                'field'    => 'term_id',
                'terms'    => $selected_category_id,
            ),
        ),
    );
    $products_query = new WP_Query($args);

    if ($products_query->have_posts()) {
        ob_start();
        ?>
        <style>
            .pricing-panels {
                display: flex;
                justify-content: space-around;
                flex-wrap: wrap;
            }
            .pricing-panel {
                width: 300px;
                padding: 20px;
                margin: 20px;
                border: 1px solid #ddd;
                border-radius: 5px;
                text-align: center;
                background-color: #f9f9f9;
                box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
            }
            .pricing-panel h3 {
                font-size: 24px;
                margin-bottom: 10px;
            }
            .pricing-panel .price {
                font-size: 18px;
                font-weight: bold;
                margin-bottom: 20px;
            }
            .pricing-panel .button {
                display: inline-block;
                padding: 10px 20px;
                background-color: #007bff;
                color: #fff;
                text-decoration: none;
                border-radius: 5px;
                transition: background-color 0.3s;
            }
            .pricing-panel .button:hover {
                background-color: #0056b3;
            }
        </style>
        <div class="pricing-panels">
            <?php
            while ($products_query->have_posts()) {
                $products_query->the_post();
                global $product;
                if (has_term($selected_category_id, 'product_cat', $product->get_id())) {
                    ?>
                    <div class="pricing-panel">
                        <h3><?php the_title(); ?></h3>
                        <div class="price"><?php echo $product->get_price_html(); ?></div>
                        <div class="description"><?php echo wpautop(get_the_content()); ?></div>
                        <a href="<?php echo esc_url(add_query_arg('add-to-cart', $product->get_id(), wc_get_checkout_url())); ?>" class="button">Subscribe Now</a>
                    </div>
                    <?php
                }
            }
            ?>
        </div>
        <?php
        return ob_get_clean();
    } else {
        return 'No products found.';
    }
}
add_shortcode('pricing_panels', 'pricing_panels_shortcode');

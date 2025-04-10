<?php
class SFB_Post_Type {
    public function __construct() {
        add_action('init', array($this, 'register_post_type'));
        add_action('add_meta_boxes', array($this, 'add_meta_boxes'));
        add_action('save_post', array($this, 'save_meta_boxes'));
    }

    public function register_post_type() {
        $labels = array(
            'name'               => __('FAQs', 'smart-faq-builder'),
            'singular_name'      => __('FAQ', 'smart-faq-builder'),
            'menu_name'          => __('FAQs', 'smart-faq-builder'),
            'add_new'            => __('Add New', 'smart-faq-builder'),
            'add_new_item'       => __('Add New FAQ', 'smart-faq-builder'),
            'edit_item'          => __('Edit FAQ', 'smart-faq-builder'),
            'new_item'           => __('New FAQ', 'smart-faq-builder'),
            'view_item'          => __('View FAQ', 'smart-faq-builder'),
            'search_items'       => __('Search FAQs', 'smart-faq-builder'),
            'not_found'          => __('No FAQs found', 'smart-faq-builder'),
            'not_found_in_trash' => __('No FAQs found in Trash', 'smart-faq-builder'),
        );

        $args = array(
            'labels'              => $labels,
            'public'              => true,
            'publicly_queryable'  => true,
            'show_ui'             => true,
            'show_in_menu'        => true,
            'query_var'           => true,
            'rewrite'             => array('slug' => 'faq'),
            'capability_type'     => 'post',
            'has_archive'         => true,
            'hierarchical'        => false,
            'menu_position'       => null,
            'supports'            => array('title', 'editor'),
            'menu_icon'           => 'dashicons-format-chat',
        );

        register_post_type('sfb_faq', $args);
    }

    public function add_meta_boxes() {
        add_meta_box(
            'sfb_faq_order',
            __('FAQ Order', 'smart-faq-builder'),
            array($this, 'render_order_meta_box'),
            'sfb_faq',
            'side',
            'high'
        );
    }

    public function render_order_meta_box($post) {
        $order = get_post_meta($post->ID, '_sfb_faq_order', true);
        wp_nonce_field('sfb_faq_order', 'sfb_faq_order_nonce');
        ?>
        <p>
            <label for="sfb_faq_order"><?php _e('Order Number:', 'smart-faq-builder'); ?></label>
            <input type="number" id="sfb_faq_order" name="sfb_faq_order" value="<?php echo esc_attr($order); ?>" min="0" />
        </p>
        <?php
    }

    public function save_meta_boxes($post_id) {
        if (!isset($_POST['sfb_faq_order_nonce'])) {
            return;
        }

        if (!wp_verify_nonce($_POST['sfb_faq_order_nonce'], 'sfb_faq_order')) {
            return;
        }

        if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
            return;
        }

        if (!current_user_can('edit_post', $post_id)) {
            return;
        }

        if (isset($_POST['sfb_faq_order'])) {
            update_post_meta($post_id, '_sfb_faq_order', sanitize_text_field($_POST['sfb_faq_order']));
        }
    }
} 
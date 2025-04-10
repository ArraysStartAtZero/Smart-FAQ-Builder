<?php
class SFB_Shortcode {
    public function __construct() {
        add_action('init', array($this, 'register_shortcode'));
        add_action('wp_enqueue_scripts', array($this, 'enqueue_scripts'));
    }

    public function register_shortcode() {
        add_shortcode('smart_faq', array($this, 'render_shortcode'));
    }

    public function enqueue_scripts() {
        wp_enqueue_style(
            'sfb-styles',
            SFB_PLUGIN_URL . 'assets/css/sfb-styles.css',
            array(),
            SFB_VERSION
        );

        wp_enqueue_script(
            'sfb-scripts',
            SFB_PLUGIN_URL . 'assets/js/sfb-scripts.js',
            array('jquery'),
            SFB_VERSION,
            true
        );
    }

    public function render_shortcode($atts) {
        $atts = shortcode_atts(array(
            'category' => '',
            'limit' => -1,
        ), $atts);

        $args = array(
            'post_type' => 'sfb_faq',
            'posts_per_page' => $atts['limit'],
            'orderby' => 'meta_value_num',
            'meta_key' => '_sfb_faq_order',
            'order' => 'ASC',
        );

        if (!empty($atts['category'])) {
            $args['tax_query'] = array(
                array(
                    'taxonomy' => 'category',
                    'field' => 'slug',
                    'terms' => $atts['category'],
                ),
            );
        }

        $faqs = new WP_Query($args);

        if (!$faqs->have_posts()) {
            return '<p>' . __('No FAQs found.', 'smart-faq-builder') . '</p>';
        }

        $output = '<div class="sfb-faq-container">';
        
        while ($faqs->have_posts()) {
            $faqs->the_post();
            $output .= sprintf(
                '<div class="sfb-faq-item">
                    <div class="sfb-faq-question">%s</div>
                    <div class="sfb-faq-answer">%s</div>
                </div>',
                get_the_title(),
                get_the_content()
            );
        }

        $output .= '</div>';
        
        wp_reset_postdata();
        
        return $output;
    }
} 
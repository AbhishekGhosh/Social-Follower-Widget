<?php
/*
Plugin Name: Social Followers Widget
Description: A widget to display social media follower counts.
Version: 1.0
Author: Abhishek Ghosh
*/

class Social_Followers_Widget extends WP_Widget {
    public function __construct() {
        parent::__construct(
            'social_followers_widget',
            __('Social Followers Widget', 'text_domain'),
            array('description' => __('Displays follower counts for social media platforms.', 'text_domain'))
        );
        add_action('wp_enqueue_scripts', array($this, 'enqueue_styles'));
    }

    public function enqueue_styles() {
        // wp_enqueue_style('font-awesome', 'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css');
        wp_enqueue_style('social-followers-widget-style', plugins_url('style.css', __FILE__));
    }

    public function widget($args, $instance) {
        echo $args['before_widget'];
        echo '<div class="social-followers-widget">';
        
        $socials = array(
            'facebook' => array('url' => $instance['facebook'], 'icon' => 'fab fa-facebook-f'),
            'linkedin' => array('url' => $instance['linkedin'], 'icon' => 'fab fa-linkedin-in'),
            'twitter' => array('url' => $instance['twitter'], 'icon' => 'fab fa-twitter'),
            'pinterest' => array('url' => $instance['pinterest'], 'icon' => 'fab fa-pinterest-p'),
            'github' => array('url' => $instance['github'], 'icon' => 'fab fa-github'),
            'youtube' => array('url' => $instance['youtube'], 'icon' => 'fab fa-youtube'),
        );

        foreach ($socials as $key => $social) {
            if (!empty($social['url'])) {
                echo '<a href="' . esc_url($social['url']) . '" class="social-icon ' . esc_attr($key) . '" target="_blank">
                        <i class="' . esc_attr($social['icon']) . '"></i>
                      </a>';
            }
        }
        
        echo '</div>';
        echo $args['after_widget'];
    }

    public function form($instance) {
        $fields = ['facebook', 'linkedin', 'twitter', 'pinterest', 'github', 'youtube'];
        foreach ($fields as $field) {
            $value = !empty($instance[$field]) ? $instance[$field] : '';
            echo '<p>
                    <label for="' . $this->get_field_id($field) . '">' . ucfirst($field) . ' URL:</label>
                    <input class="widefat" id="' . $this->get_field_id($field) . '" name="' . $this->get_field_name($field) . '" type="text" value="' . esc_attr($value) . '" />
                  </p>';
        }
    }

    public function update($new_instance, $old_instance) {
        $instance = [];
        $fields = ['facebook', 'linkedin', 'twitter', 'pinterest', 'github', 'youtube'];
        foreach ($fields as $field) {
            $instance[$field] = (!empty($new_instance[$field])) ? esc_url_raw($new_instance[$field]) : '';
        }
        return $instance;
    }
}

function register_social_followers_widget() {
    register_widget('Social_Followers_Widget');
}
add_action('widgets_init', 'register_social_followers_widget');

// Plugin Styles
function social_followers_widget_styles() {
    echo '<style>
        .social-followers-widget {
            display: flex;
            gap: 10px;
        }
        .social-icon {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 40px;
            height: 40px;
            background: black;
            color: white;
            border-radius: 10px;
            text-decoration: none;
            font-size: 20px;
            transition: 0.3s;
        }
        .social-icon:hover {
            opacity: 0.7;
        }
    </style>';
}
add_action('wp_head', 'social_followers_widget_styles');

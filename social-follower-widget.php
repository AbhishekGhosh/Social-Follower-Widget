<?php
/*
Plugin Name: Social Followers Widget
Description: A widget to display social media follower counts.
Version: 1.2
Author: Your Name
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
        wp_enqueue_style('font-awesome', 'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css');
        wp_enqueue_style('social-followers-widget-style', plugins_url('style.css', __FILE__));
    }

    public function widget($args, $instance) {
        echo $args['before_widget'];
        
        if (!empty($instance['title'])) {
            echo $args['before_title'] . apply_filters('widget_title', $instance['title']) . $args['after_title'];
        }
        
        echo '<div class="social-followers-widget">';
        
        $socials = array(
            'facebook' => array('url' => $instance['facebook'], 'icon' => 'fab fa-facebook-f', 'count' => $instance['facebook_count']),
            'linkedin' => array('url' => $instance['linkedin'], 'icon' => 'fab fa-linkedin-in', 'count' => $instance['linkedin_count']),
            'twitter' => array('url' => $instance['twitter'], 'icon' => 'fab fa-twitter', 'count' => $instance['twitter_count']),
            'pinterest' => array('url' => $instance['pinterest'], 'icon' => 'fab fa-pinterest-p', 'count' => $instance['pinterest_count']),
            'github' => array('url' => $instance['github'], 'icon' => 'fab fa-github', 'count' => $instance['github_count']),
            'youtube' => array('url' => $instance['youtube'], 'icon' => 'fab fa-youtube', 'count' => $instance['youtube_count']),
        );

        foreach ($socials as $key => $social) {
            if (!empty($social['url'])) {
                echo '<a href="' . esc_url($social['url']) . '" class="social-icon ' . esc_attr($key) . '" target="_blank">
                        <i class="' . esc_attr($social['icon']) . '"></i>
                        <span class="follower-text">' . esc_html($social['count']) . ' Followers</span>
                      </a>';
            }
        }
        
        echo '</div>';
        echo $args['after_widget'];
    }

    public function form($instance) {
        $title = !empty($instance['title']) ? $instance['title'] : '';
        echo '<p>
                <label for="' . $this->get_field_id('title') . '">Title:</label>
                <input class="widefat" id="' . $this->get_field_id('title') . '" name="' . $this->get_field_name('title') . '" type="text" value="' . esc_attr($title) . '" />
              </p>';
        
        $fields = ['facebook', 'linkedin', 'twitter', 'pinterest', 'github', 'youtube'];
        foreach ($fields as $field) {
            $url_value = !empty($instance[$field]) ? $instance[$field] : '';
            $count_value = !empty($instance[$field . '_count']) ? $instance[$field . '_count'] : '';
            echo '<p>
                    <label for="' . $this->get_field_id($field) . '">' . ucfirst($field) . ' URL:</label>
                    <input class="widefat" id="' . $this->get_field_id($field) . '" name="' . $this->get_field_name($field) . '" type="text" value="' . esc_attr($url_value) . '" />
                  </p>';
            echo '<p>
                    <label for="' . $this->get_field_id($field . '_count') . '">' . ucfirst($field) . ' Followers:</label>
                    <input class="widefat" id="' . $this->get_field_id($field . '_count') . '" name="' . $this->get_field_name($field . '_count') . '" type="number" value="' . esc_attr($count_value) . '" />
                  </p>';
        }
    }

    public function update($new_instance, $old_instance) {
        $instance = [];
        $instance['title'] = (!empty($new_instance['title'])) ? sanitize_text_field($new_instance['title']) : '';
        
        $fields = ['facebook', 'linkedin', 'twitter', 'pinterest', 'github', 'youtube'];
        foreach ($fields as $field) {
            $instance[$field] = (!empty($new_instance[$field])) ? esc_url_raw($new_instance[$field]) : '';
            $instance[$field . '_count'] = (!empty($new_instance[$field . '_count'])) ? intval($new_instance[$field . '_count']) : '';
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
            flex-direction: column;
            gap: 10px;
        }
        .social-icon {
            display: flex;
            align-items: center;
            gap: 10px;
            background: #474e5a;
            color: white;
            padding: 10px;
			margin-left: 30px;
            border-radius: 25px;
            text-decoration: none;
            font-size: 16px;
            transition: 0.3s;
            width: 200px;
        }
        .social-icon i {
            font-size: 20px;
        }
        .follower-text {
            font-size: 14px;
        }
        .social-icon:hover {
            opacity: 0.7;
			color: white;
        }
		.social-icon.facebook:hover {background: #1778F2;}
		.social-icon.linkedin:hover { background: #0077b5; }
		.social-icon.twitter:hover { background: #1da1f2; }
		.social-icon.pinterest:hover { background: #bd081c; }
		.social-icon.github:hover { background: #333; }
		.social-icon.youtube:hover { background: #ff0000; }
@media (max-width: 600px) {
    .social-icon {
        width: 100%;
        justify-content: center;
    }
}

    </style>';
}
add_action('wp_head', 'social_followers_widget_styles');

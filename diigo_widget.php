<?php

/*
  Plugin Name: Diigo Bookmarks Widget
  Plugin URI: mailto:rezatxe@gmail.com
Description: Display bookmarks from <a href="http://diigo.com">Diigo</a> in your sidebar. Uses <a href="http://www.diigo.com/api_dev/docs">Diigo API</a> to fetch the bookmarks. First get an API key from <a href="http://www.diigo.com/api_keys/new/">here</a> and use this key along with your username and password in the widget settings. Then click the "Test API" button to test the api response. You can mention the 'User' input to fetch bookmarks from a particular user or leave it empty to fetch any public bookmark.
  Author: Forhadur Reza
  Author URI: mailto:rezatxe@gmail.com
  Version: 1.0
 */
if (!defined('ABSPATH'))
    die('You do not have sufficient permissions to access this file.');

add_action('widgets_init', create_function('', 'register_widget("diigo_widget");'));

class diigo_widget extends WP_Widget {

    private $default_options = array(
        'title' => 'Diigo Bookmarks',
        'api_key' => '',
        'username' => '',
        'password' => '',
        'bookmark_count' => 5,
        'user' => 'joel',
        'count' => '5',
        'sort' => '0',
        'filter' => 'all',
        'tags' => ''
    );

    public function __construct() {
        $this->plugindir = realpath(dirname(__FILE__));
        parent::__construct(
                false, // Base ID
                'Diigo Widget', // Name
                array('description' => __('Diigo Widget', 'text_domain'),), // Args
                array('width' => '520px')
        );
        add_action('init', array($this, 'admin_enqueue_scripts'));
    }

    function form($instance) {

        $instance = wp_parse_args((array) $instance, $this->default_options);
        $data = array();
        if ($instance) {
            $data = $instance;
        }
        $this->render('form', $data);
    }

    function widget($args, $instance) {

        $data = $args;
        $data['title'] = apply_filters('widget_title', $instance['title']);
        require_once realpath(dirname(__FILE__)) . '/lib/diigo.class.php';
        $diigo = new diigo($instance['api_key'], $instance['username'], $instance['password']);
        
        $params['user']=$instance['user'];
        $params['count']=$instance['count'];
        $params['sort']=$instance['sort'];
        $params['filter']=$instance['filter'];
        $params['tags']=$instance['tags'];        
        $params = array_filter($params);                        
            
        foreach ($params as $key=>$val)
            $params[urlencode ($key)]=urlencode ($val);
        $params = http_build_query($params);            
        $json = $diigo->get_bookmarks($params);
        if ($diigo->http_code !== 200) {
            echo 'Invalid Response: ' . $diigo->http_code;
            return;
        }
        $data['bookmarks'] = json_decode($json);
        $this->render('widget', $data);
    }

    //template parser
    public function render($template, $data=array(), $echo=true) {

        $file = $this->plugindir . '/views/' . $template . '.php';
        if (!file_exists($file)) {
            if ($echo) {
                echo 'View file doesn\'t exists';
                return;
            }
            else
                return 'View file doesn\'t exists';
        }
        extract($data);

        if ($echo) {
            include $file;
        }
        else {
            ob_start();
            include $file;
            $content = ob_get_contents();
            ob_end_clean();
            return $content;
        }
    }

    function update($new_instance, $old_instance) {
        $instance = wp_parse_args($old_instance, $new_instance);
        foreach ($new_instance as $key => $val) {
            $instance[strip_tags($key)] = strip_tags(stripslashes($val));
        }
        return $instance;
    }

    public function admin_enqueue_scripts() {
        wp_enqueue_script('jquery');
        wp_localize_script('jquery', get_class(), array('ajaxurl' => admin_url('admin-ajax.php')));
        // this hook is fired if the current viewer is not logged in        
        add_action('wp_ajax_nopriv_ajax_test', array($this, 'ajax_test'));
        add_action('wp_ajax_ajax_test', array($this, 'ajax_test'));
    }

    public static function ajax_test() {
        $widget_number = $_POST['widget_number'];
        $id_base = 'widget-' . $_POST['id_base'];
        $widget_fields = $_POST[$id_base][$widget_number];
        foreach ($widget_fields as $key => $val) {
            $widget_fields[strip_tags($key)] = strip_tags(stripslashes($val));
        }
        $username = $widget_fields['username'];
        $password = $widget_fields['password'];
        $api_key = $widget_fields ['api_key'];
        require_once realpath(dirname(__FILE__)) . '/lib/diigo.class.php';
        $diigo = new diigo($api_key, $username, $password);
        $json = $diigo->get_bookmarks('');
        echo $diigo->get_http_code();
        exit;
    }

    private function curl_get($url) {
        
    }

// embed the javascript file that makes the AJAX request
}

?>
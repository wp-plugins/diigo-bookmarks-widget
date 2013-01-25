<script>        
    jQuery('#<?php echo $this->get_field_id('test'); ?>').live('click',function(){
        var data ='action=ajax_test&'+jQuery(this).parents('form').serialize();
        jQuery('#<?php echo $this->get_field_id('ajax_response'); ?>').html('Checking api data...');
        jQuery.post( <?php echo get_class() ?>.ajaxurl, data,
        function( data ) {
            jQuery('#<?php echo $this->get_field_id('ajax_response'); ?>').html('API Response: <code>'+data+'</code>');
          
        }
    );
    });  

</script>
<?php
$sortOptions = array(
    'Created At', 'Updated At', 'Popularity', 'Hot'
);
$filterOptions = array(
    'all' => 'All',
    'public' => 'Public'
);
?>
<div style="float: left;">
    <div style="width:250px;float: left;margin-right: 15px">
        <h3>Widget Options</h3>
        <p>
            <label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:'); ?></label> 
            <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php esc_attr_e($title); ?>" />
        </p>
        <p>
            <label for="<?php echo $this->get_field_id('user'); ?>"><?php _e('User:'); ?></label> 
            <input size="15" id="<?php echo $this->get_field_id('user'); ?>" name="<?php echo $this->get_field_name('user'); ?>" type="text" value="<?php esc_attr_e($user); ?>" />
        </p>
        <p>
            <label for="<?php echo $this->get_field_id('count'); ?>"><?php _e('Bookmarks Count:'); ?></label> 
            <input type="number" step="1" min="0" max="100" id="medium_size_w" id="<?php echo $this->get_field_id('count'); ?>" name="<?php echo $this->get_field_name('count'); ?>" type="text" value="<?php esc_attr_e($count); ?>" />
        </p>
        <p>
            <label for="<?php echo $this->get_field_id('sort'); ?>"><?php _e('Sort By:'); ?></label> 
            <select style="width:130px"  id="<?php echo $this->get_field_id('sort'); ?>" name="<?php echo $this->get_field_name('sort'); ?>" >
                <?php
                foreach ($sortOptions as $key => $option) {
                    $selected = "";
                    if ($key == esc_attr($sort))
                        $selected = "selected";
                    echo '<option ' . $selected . ' value="' . $key . '"> ' . $option . '</option>';
                }
                ?>
            </select>  
        </p>
        <p>
            <label for="<?php echo $this->get_field_id('filter'); ?>"><?php _e('filter By:'); ?></label> 
            <select style="width:130px" id="<?php echo $this->get_field_id('filter'); ?>" name="<?php echo $this->get_field_name('filter'); ?>" >
                <?php
                foreach ($filterOptions as $key => $option) {
                    $selected = "";
                    if ($key == esc_attr($filter))
                        $selected = "selected";
                    echo '<option ' . $selected . ' value="' . $key . '"> ' . $option . '</option>';
                }
                ?>
            </select>  
        </p>
        <p>
            <label for="<?php echo $this->get_field_id('tags'); ?>"><?php _e('Tags:(comma separated)'); ?></label> 
            <input class="widefat" id="<?php echo $this->get_field_id('tags'); ?>" name="<?php echo $this->get_field_name('tags'); ?>" type="text" value="<?php esc_attr_e($tags); ?>" />
        </p>
    </div>
    <div style="width:250px;float: left">
        <h3>API settings</h3>
        <p>
            <label for="<?php echo $this->get_field_id('api_key'); ?>"><?php _e('API Key:'); ?> (Get Key <a target="_blank" href="http://www.diigo.com/api_keys/new/">here</a>)</label> 
            <input class="widefat" id="<?php echo $this->get_field_id('api_key'); ?>" name="<?php echo $this->get_field_name('api_key'); ?>" type="text" value="<?php esc_attr_e($api_key); ?>" />
        </p>
        <p>
            <label for="<?php echo $this->get_field_id('username'); ?>"><?php _e('Username:'); ?></label> 
            <input class="widefat" id="<?php echo $this->get_field_id('username'); ?>" name="<?php echo $this->get_field_name('username'); ?>" type="text" value="<?php esc_attr_e($username); ?>" />
        </p>
        <p>
            <label for="<?php echo $this->get_field_id('password'); ?>"><?php _e('Password:'); ?></label> 
            <input class="widefat" id="<?php echo $this->get_field_id('password'); ?>" name="<?php echo $this->get_field_name('password'); ?>" type="password" value="<?php esc_attr_e($password); ?>" />
        </p>
        <input type="button" id="<?php echo $this->get_field_id('test'); ?>" name="test" class="button-primary" value="Test API">
        <span id="<?php echo $this->get_field_id('ajax_response'); ?>"> </span>
    </div>
</div>
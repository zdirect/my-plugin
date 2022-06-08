<?php 

class DirectPostType{

    public static function register(){
        add_action('init', [__CLASS__, 'custom_post_type']);
        add_action('add_meta_boxes', [__CLASS__, 'add_meta_box_property']);
        add_action('save_post', [__CLASS__, 'save_metabox'], 10, 2);
    }

    public static function add_meta_box_property(){
        add_meta_box(
            'alertproperty_settings',
            'Property Settings',
            [__CLASS__, 'metabox_property_html'],
            'property',
            'normal',
            'default'
        );
    }

    public static function save_metabox($post_id, $post){

        if(!isset($_POST['_alertproperty']) || !wp_verify_nonce($_POST['_alertproperty'], 'alertpropertyfield')){
            return;
        }

        if(defined('DOING_AUTOSAVE') && DOING_AUTOSAVE){
            return;
        }

        if($post->post_type != 'property'){
            return;
        }



        if(isset($_POST['alertpropert_price'])){
            update_post_meta($post_id, 'alertpropert_price', $_POST['alertpropert_price']);
        }

        if(isset($_POST['alertpropert_period'])){
            update_post_meta($post_id, 'alertpropert_period', $_POST['alertpropert_period']);
        }

        if(isset($_POST['alertpropert_type'])){
            update_post_meta($post_id, 'alertpropert_type', $_POST['alertpropert_type']);
        }

        if(isset($_POST['alertpropert_agent'])){
            update_post_meta($post_id, 'alertpropert_agent', $_POST['alertpropert_agent']);
        }
    }

    public static function metabox_property_html($post, $meta){
        $price = get_post_meta($post->ID, 'alertpropert_price', true);
        $period = get_post_meta($post->ID, 'alertpropert_period', true);
        $type = get_post_meta($post->ID, 'alertpropert_type', true);
        $agent_meta = get_post_meta($post->ID, 'alertpropert_agent', true);

        wp_nonce_field('alertpropertyfield', '_alertproperty');

        echo '<p>
            <label for="alertpropert_price">Price</label>
            <input type="text" id="pro_price" name="alertpropert_price" value="'.$price.'">
        <p>
        
        <p>
            <label for="alertpropert_period">Period</label>
            <input type="text" id="pro_period" name="alertpropert_period" value="'.$period.'">
        <p>
        
        <p>
            <label for="alertpropert_type">Type</label>
            <select id="type" name="alertpropert_type">
                <option value="">Select type</option>
                <option value="sale" '.selected('sale', $type, false).'>For Sale</option>
                <option value="rent" '.selected('rent', $type, false).'>For Rent</option>
                <option value="sold" '.selected('sold', $type, false).'>Sold</option>
            </select>
        <p>';

        $agents = get_posts([
            'post_type' => 'agent',
            'posts_per_page' => -1
        ]);
        if($agents): ?>
        <label for="alertpropert_agent">Type</label>
        <select id="agent" name="alertpropert_agent">
            <option value="">Select agent</option>
            <?php foreach($agents as $agent): ?>
                <option value="<?php echo $agent->ID; ?>" <?php echo selected($agent->ID, $agent_meta, false); ?>><?php echo $agent->post_title; ?></option>
            <?php endforeach; ?>
        </select>
        <?php endif; 
    }

    public static function custom_post_type(){

        $labels = array(
            'name'              => _x( 'location', 'taxonomy general name', 'direct' ),
            'singular_name'     => _x( 'location', 'taxonomy singular name', 'direct' ),
            'search_items'      => __( 'Search location', 'direct' ),
            'all_items'         => __( 'All location', 'direct' ),
            'parent_item'       => __( 'Parent location', 'direct' ),
            'parent_item_colon' => __( 'Parent location:', 'direct' ),
            'edit_item'         => __( 'Edit location', 'direct' ),
            'update_item'       => __( 'Update location', 'direct' ),
            'add_new_item'      => __( 'Add New location', 'direct' ),
            'new_item_name'     => __( 'New location Name', 'direct' ),
            'menu_name'         => __( 'location', 'direct' ),
        );

        register_taxonomy('location', 'property', [
            'hierarchical' => true,
            'show_ui' => true,
            'show_admin_column' => true,
            'query_var' => true,
            'rewrite' => ['slug' => 'location'],
            'labels' => $labels
        ]);

        $labels = array(
            'name'              => _x( 'Type', 'taxonomy general name', 'direct' ),
            'singular_name'     => _x( 'Type', 'taxonomy singular name', 'direct' ),
            'search_items'      => __( 'Search Type', 'direct' ),
            'all_items'         => __( 'All Type', 'direct' ),
            'parent_item'       => __( 'Parent Type', 'direct' ),
            'parent_item_colon' => __( 'Parent Type:', 'direct' ),
            'edit_item'         => __( 'Edit Type', 'direct' ),
            'update_item'       => __( 'Update Type', 'direct' ),
            'add_new_item'      => __( 'Add New Type', 'direct' ),
            'new_item_name'     => __( 'New Type Name', 'direct' ),
            'menu_name'         => __( 'Type', 'direct' ),
        );

        register_taxonomy('property-type', 'property', [
            'hierarchical' => true,
            'show_ui' => true,
            'show_admin_column' => true,
            'query_var' => true,
            'rewrite' => ['slug' => 'property-type'],
            'labels' => $labels
        ]);

        register_post_type('property', [
            'public' => true,
            'has_archive' => true,
            'rewrite' => ['slug' => 'property'],
            'label' => 'property',
            'supports' => ['title', 'editor', 'thumbnail']
        ]);

        register_post_type('agent', [
            'public' => true,
            'has_archive' => true,
            'rewrite' => ['slug' => 'agent'],
            'label' => 'agent',
            'supports' => ['title', 'editor', 'thumbnail']
        ]);

    }
}

if(class_exists('DirectPostType')){
    $direct = new DirectPostType();
    $direct::register();
}
<?php 

class Direct_Template_loader extends Gamajo_Template_Loader{

    protected $filter_prefix = 'direct';

    protected $theme_direcory = 'direct';

    protected $plugin_directory = PLUGIN_PATH;

    protected $plugin_template_directory = 'templates';

    public function __construct(){
        add_filter('template_include', [$this, 'direct_templates']);
    }

    public function direct_templates($template){

        if(is_post_type_archive('property')){

            $theme_files = ['archive-property.php', 'direct/archive-property.php'];
            $exits = locale_template($theme_files, false);
            if($exits != ''){
                return $exits;
            }else{
                return plugin_dir_path(__FILE__).'templates/archive-property.php';
            }
            
        }

        return $template;
    }
}

//$direct_template = new Direct_Template_loader();
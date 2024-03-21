<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/**
 *
 */
class Layout {
    function __construct(){
    }
    
    function render() {

        global $OUT;
        $CI = & get_instance();

        $output = $CI->output->get_output();
        if (!isset($CI->layout)) {
            $CI->layout = "default_layout2";
        }
        if ($CI->layout != false) {
            if (!preg_match('/(.+).php$/', $CI->layout)) {
                $CI->layout .= '.php';
            }

            $requested = BASEPATH . '../application/views/layouts/' . $CI->layout;
            $default = BASEPATH . '../application/views/layouts/default_layout2.php';

            if (file_exists($requested)) {
                $layout = $CI->load->file($requested, true);
            } else {
                $layout = $CI->load->file($default, true);
            }

            $view = str_replace("{content}", $output, $layout);

            if(isset($CI->parts)){
                if(! ($CI->parts))
                {
                    $CI->parts = $this->load->view('submit');
                }
                
                if (count($CI->parts) > 0) {    // Массив с частями страницы
                    foreach ($CI->parts as $name => $part) {
                        $view = str_replace("{" . $name . "}", $part, $view);
                    }
                }
            }
            
            $view = preg_replace("/{.*?}/ims", "", $view); // Подчищаем пустые неподгруженные части шаблона
        } else {
            $view = $output;
        }
        $OUT->_display($view);
    }
}


?> 
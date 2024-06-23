<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$hook['display_override'][] = array('class' => 'Layout',
    'function' => 'render',
    'filename' => 'Layout.php',
    'filepath' => 'hooks'
);

// $hook['pre_controller'] = array(
//     'class'    => 'Requests',
//     'function' => '',
//     'filename' => 'Login.php',
//     'filepath' => 'modules/admin/controllers/common/'
// );

/*
| -------------------------------------------------------------------------
| Hooks
| -------------------------------------------------------------------------
| This file lets you define "hooks" to extend CI without hacking the core
| files.  Please see the user guide for info:
|
|	https://codeigniter.com/user_guide/general/hooks.html
|
*/

<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

// params code: 0: deny access if uri not set in config/acl.php, 1: permit access by default.

$hook['post_controller_constructor'][] = array(
    'class'     => 'Acl',
    'function'  => 'hook_exec_access_control',
    'filename'  => 'acl.php',
    'filepath'  => 'hooks',
    'params'	=> array(
    	'code'	=> 0
    )
);

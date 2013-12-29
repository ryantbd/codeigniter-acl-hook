<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

$hook['post_controller_constructor'][] = array(
    'class'     => 'Acl',
    'function'  => 'hook_exec_access_control',
    'filename'  => 'acl.php',
    'filepath'  => 'hooks'
);

<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * ACL Configuration (By Role Ids)
 */

$acl = array();

// ['controller']['method'] 				Role Ids which have permission to visit, joined by comma

// In this case, users with role id 1 or 2,3,4,6 will be able to access uri home/*, 
// but only users with role id 1 are able to access uri home/protected

$acl['home']['*']							= '1,2,3,4,6'; 	// http://your.site.url/home
$acl['home']['protected']					= '1';			// http://your.site.url/home/protected

$acl['project']['*']						= '1';

$acl['user']['*']							= '1,2';		// http://your.site.url/user
$acl['user']['list']['*']					= '1';			// http://your.site.url/user/list/
$acl['user']['group']['admin']				= '1';			// http://your.site.url/user/group/admin



<?php
/**
 * Access Control List (Codeigniter Hook)
 *
 * @category   Libraries
 * @package    Acl
 * @version    0.0.1, 2013-12-29
 */

class Acl
{
    public $config;     // acl config array
    public $roleId;     // current user role Id
    public $code = 1;   // default value 1: access permitted.

    /**
     * init CI instance
     */
    public function __construct()
    {
        $this->CI =& get_instance();
    }

    /**
     * Called From Hook, your response to the result.
     * @return [type] [description]
     */
    public function hook_exec_access_control()
    {
        $roleId = $this->CI->session->userdata('role_id'); // or get Role Id from somewhere else
        
        $this->check_permission($roleId);

        //  handle check permission result code on your own : )
        if ($this->code !== 1)
        {
            echo 'access denied';
            exit;
        }        
    }

    /**
     * Set Acl Config from somewhere else, e.g. via memcached
     * @param array $config [description]
     */
    public function set_config($config = array())
    {
        $this->config = $config;
    }

    /**
     * Get Acl Config, if not set, get from config/acl.php
     * @return array    Acl config
     */
    public function get_config()
    {
        $config = array();

        if (empty($this->config))
        {
            $this->CI->load->config('acl', TRUE);
            $config = $this->CI->config->config['acl']['acl'];
        }
        else
        {
            $config = $this->config;
        }

        return $config;
    }

    /**
     * Check if user role has permission to access target uri
     * @param  string $roleId [description]
     * @return [type]         [description]
     */
    public function check_permission($roleId = '0')
    {
        $config = $this->get_config();
        $uri    = $this->_get_uri_info();

        $this->roleId = $roleId;

        $this->_check_permission_by_uri($uri, $config);
    }

    /**
     * Check URI Access Permission
     * @param  [type] $uri    
     * @param  [type] $config [description]
     * @return [type]         [description]
     */
    private function _check_permission_by_uri($uri, $config)
    {
        $roleIds = array();

        if (!empty($uri))
        {
            $aclKey = array_shift($uri);

            if (isset($config[$aclKey]))
            {
                $aclMethods = $config[$aclKey];

                $this->_check_permission_by_uri($uri, $aclMethods);
            }
            else
            {
                if (is_array($config))
                {
                    if (isset($config['*']))
                    {
                        $roleIdStr = $config['*'];
                    }
                    else
                    {
                        $roleIdStr = '';
                    }
                }
                else
                {
                    $roleIdStr = $config;
                }

                $this->_get_permission_code($roleIdStr);
            }
        }
        else
        {
            $this->_get_permission_code($config);
        }
    }

    /**
     * Get URI Permission Code: 1: permitted, 0: denied
     * @param  string $roleIdStr    Role IDs joined by ','
     * @return int                  Permission Code
     */
    private function _get_permission_code($roleIdStr)
    {
        $code = 1;

        if ($roleIdStr != '')
        {
            $roleIds = explode(',', $roleIdStr);

            $code = intval(in_array($this->roleId, $roleIds));
        }

        $this->code = $code;
    }

    /**
     * Get Structured URI Info
     * @return array    0: controller, 
     */ 
    private function _get_uri_info()
    {
        $uri = $this->CI->uri->segment_array();
        
        // default controller and default method will be omitted
        $uri[1] = $this->CI->router->fetch_class();
        $method = $this->CI->router->fetch_method();

        if (!in_array($method, $uri))
        {
            $uri[] = $method;
        }

        return $uri;
    }
}
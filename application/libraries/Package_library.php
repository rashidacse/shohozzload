<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/**
 *
 */
class Package_library {

    public function __construct() {
        $this->load->model('package_model');
    }

    /**
     * __call
     *
     * Acts as a simple way to call model methods without loads of stupid alias'
     *
     * */
    public function __call($method, $arguments) {
        if (!method_exists($this->package_model, $method)) {
            throw new Exception('Undefined method package_model::' . $method . '() called');
        }

        return call_user_func_array(array($this->package_model, $method), $arguments);
    }

    /**
     * __get
     *
     * Enables the use of CI super-global without having to define an extra variable.
     *
     * I can't remember where I first saw this, so thank you if you are the original author. -Militis
     *
     * @access	public
     * @param	$var
     * @return	mixed
     */
    public function __get($var) {
        return get_instance()->$var;
    }
    
    /*
     * This method will return all packages
     * @author nazmul hasan on 18th november 2016
     */
    public function get_all_packages($operator_id = 0)
    {
        $package_list = array();
        $this->load->library('Date_utils');
        $package_list_array = $this->package_model->get_all_packages($operator_id)->result_array();
        foreach($package_list_array as $package_info)
        {
            $package_info['created_on'] = $this->date_utils->get_unix_to_display($package_info['created_on']);
            $package_info['modified_on'] = $this->date_utils->get_unix_to_display($package_info['modified_on']);
            $package_list[] = $package_info;
        }
        return $package_list;
    }
    
    /*
     * This method will retrn all operators
     * @param $operator_id_default, which operator will be selected in dropdown at view page
     * @author nazmul hasan on 18th november 2016
     */
    public function get_all_operators($operator_id_default = OPERATOR_ID_DEFAULT)
    {
        $operator_list = array();
        $operator_list_array = $this->package_model->get_all_operators()->result_array();
        foreach($operator_list_array as $operator_info)
        {
            if($operator_info['operator_id'] == $operator_id_default)
            {
                $operator_info['selected'] = true;
            }
            else
            {
                $operator_info['selected'] = false;
            }
            $operator_list[] = $operator_info;
        }
        return $operator_list;
    }
}

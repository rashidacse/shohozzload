<?php

class Role_Controller extends CI_Controller{
    function __construct() {
        parent::__construct();
        $this->load->model('company_model');
        $site_info = array();
        $basic_configuration_info_array = $this->company_model->get_basic_configuration_info()->result_array();
        if(!empty($basic_configuration_info_array)){
            $site_info =  $basic_configuration_info_array[0];
        }
        $this->data['site_info'] = $site_info;
        
        $user_id = $this->session->userdata('user_id');
        if(!isset($user_id) || $user_id == '' || $user_id == 0)
        {
            return;
        }
        $this->load->library('reseller_library');
        $this->load->library('payment_library');        
        $this->data['current_balance'] = $this->payment_library->get_user_current_balance($user_id);        
        $this->data['user_title'] = $this->reseller_library->get_user_title();        
        $topup_service_allow_flag = FALSE;
        $bkash_service_allow_flag = FALSE;
        $this->load->model('service_model');
        $service_list = $this->service_model->get_user_assigned_services($user_id)->result_array();
        if (!empty($service_list)) {
            foreach ($service_list as $service) {
                if ($service['service_id'] == SERVICE_TYPE_ID_TOPUP_GP || $service['service_id'] == SERVICE_TYPE_ID_TOPUP_ROBI || $service['service_id'] == SERVICE_TYPE_ID_TOPUP_BANGLALINK || $service['service_id'] == SERVICE_TYPE_ID_TOPUP_AIRTEL || $service['service_id'] == SERVICE_TYPE_ID_TOPUP_TELETALK) {
                    $topup_service_allow_flag = TRUE;
                }
                if ($service['service_id'] == SERVICE_TYPE_ID_BKASH_CASHIN || $service['service_id'] == SERVICE_TYPE_ID_BKASH_CASHOUT) {
                    $bkash_service_allow_flag = TRUE;
                }
            }
        }
        $this->data['my_service_list'] = $service_list;
        $this->data['topup_service_allow_flag'] = $topup_service_allow_flag;
        $this->data['bkash_service_allow_flag'] = $bkash_service_allow_flag;
               
    }
}

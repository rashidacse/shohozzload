<?php

class Package extends Role_Controller {

    function __construct() {
        parent::__construct();
        $this->load->helper('url');
        //No permission to load this page without login
        if (!$this->ion_auth->logged_in()) {
            redirect('auth/login', 'refresh');
        }
        $group = $this->session->userdata('group');
        if($group != GROUP_ADMIN)
        {
            //Only Admin can view this controller
            $this->data['app'] = PACKAGE_APP;
            $this->data['error_message'] = "Sorry !! You don't have permission to use this feature.";
            $this->template->load(null, 'common/error_message', $this->data);
        }
        $this->load->library('package_library');
    }
    /*
     * This method will show package list
     * @author nazmul hasan on 18th november 2016
     */
    public function index() {
        $this->data['package_list'] = $this->package_library->get_all_packages();
        $this->data['message'] = "";
        $this->data['app'] = PACKAGE_APP;
        $this->template->load(null, 'admin/package/index', $this->data);
    }
    
    /*
     * This method will create a new package
     * @author nazmul hasan on 18th november 2016
     */
    public function create_package() {        
        //package creation info provided by user
        if (file_get_contents("php://input") != null) {
            $response = array();
            $postdata = file_get_contents("php://input");
            $requestInfo = json_decode($postdata);
            $packageInfo = new stdClass();
            if (property_exists($requestInfo, "packageInfo") != FALSE) {
                $packageInfo = $requestInfo->packageInfo;
            }
            else
            {
                $response['message'] = "Invalid package info.";
                echo json_encode($response);
                return;
            }
            $title = "";
            $amount = 0;
            $operator_id = 0;
            //extracting package info
            if (property_exists($packageInfo, "title") != FALSE) {
                $title = $packageInfo->title;
            }
            if (property_exists($packageInfo, "amount") != FALSE) {
                $amount = $packageInfo->amount;
            }
            if (property_exists($packageInfo, "operatorId") != FALSE) {
                $operator_id = $packageInfo->operatorId;
            }
            //validating required fields
            if($title == "")
            {
                $response['message'] = "Invalid package title.";
                echo json_encode($response);
                return;
            }
            if($amount == 0)
            {
                $response['message'] = "Invalid package amount.";
                echo json_encode($response);
                return;
            }
            if($operator_id == 0)
            {
                $response['message'] = "Invalid operator.";
                echo json_encode($response);
                return;
            }
            
            $data = array(
                'operator_id' => $operator_id,
                'title' => $title,
                'amount' => $amount
            );
            $this->load->library("security");
            $package_data = $this->security->xss_clean($data);
            //creating a new package
            if($this->package_model->create_package($package_data))
            {
                $response['message'] = $this->package_model->messages();
            }
            else
            {
                $response['message'] = $this->package_model->errors();
            }
            echo json_encode($response);
            return;
        }
        $operator_list1 = array();
        $operator_list1[] = array(
            'operator_id' => 0,
            'title' => 'Select operator',
            'selected' => true
        );
        $operator_list = array_merge($operator_list1, $this->package_library->get_all_operators(0));    
        $this->data['operator_list'] = $operator_list;
        $this->data['message'] = "";
        $this->data['app'] = PACKAGE_APP;
        $this->template->load(null, 'admin/package/create_package', $this->data);
    }
    /*
     * This method will update an existing package
     * @author nazmul hasan on 18th november 2016
     */
    public function update_package($package_id = 0) {
        if (file_get_contents("php://input") != null) {
            $response = array();
            $postdata = file_get_contents("php://input");
            $requestInfo = json_decode($postdata);
            $packageInfo = new stdClass();
            if (property_exists($requestInfo, "packageInfo") != FALSE) {
                $packageInfo = $requestInfo->packageInfo;
            }
            else
            {
                $response['message'] = "Invalid package info.";
                echo json_encode($response);
                return;
            }
            $title = "";
            $amount = 0;
            $operator_id = 0;
            //extracting package info
            if (property_exists($packageInfo, "package_id") != FALSE) {
                $package_id = $packageInfo->package_id;
            }
            if (property_exists($packageInfo, "title") != FALSE) {
                $title = $packageInfo->title;
            }
            if (property_exists($packageInfo, "amount") != FALSE) {
                $amount = $packageInfo->amount;
            }
            if (property_exists($packageInfo, "operator_id") != FALSE) {
                $operator_id = $packageInfo->operator_id;
            }
            
            //validating required fields
            if($package_id == 0)
            {
                $response['message'] = "Invalid package id.";
                echo json_encode($response);
                return;
            }
            if($title == "")
            {
                $response['message'] = "Invalid package title.";
                echo json_encode($response);
                return;
            }
            if($amount == 0)
            {
                $response['message'] = "Invalid package amount.";
                echo json_encode($response);
                return;
            }
            if($operator_id == 0)
            {
                $response['message'] = "Invalid operator.";
                echo json_encode($response);
                return;
            }
            
            $data = array(
                'operator_id' => $operator_id,
                'title' => $title,
                'amount' => $amount
            );
            $this->load->library("security");
            $package_data = $this->security->xss_clean($data);
            //updating package info
            if($this->package_model->update_package($package_id, $package_data))
            {
                $response['message'] = $this->package_model->messages();
            }
            else
            {
                $response['message'] = $this->package_model->errors();
            }
            echo json_encode($response);
            return;
        }
        $package_info = array();
        $package_info_array = $this->package_model->get_package_info($package_id)->result_array();
        if(!empty($package_info_array))
        {
            $package_info = $package_info_array[0];
        }
        else
        {
            //Invalid package id
            $this->data['app'] = PACKAGE_APP;
            $this->data['error_message'] = "Sorry !! Invalid package id.";
            $this->template->load(null, 'common/error_message', $this->data);
            return;
        }
        $this->data['package_info'] = $package_info;
        $this->data['operator_list'] = $this->package_library->get_all_operators($package_info['operator_id']);
        $this->data['message'] = "";
        $this->data['app'] = PACKAGE_APP;
        $this->template->load(null, 'admin/package/update_package', $this->data);
    }
    
    /*
     * This method will delete a package
     * @author nazmul hasan on 18 november 2016
     */
    public function delete_package()
    {
        if (file_get_contents("php://input") != null) {
            $response = array();
            $postdata = file_get_contents("php://input");
            $requestInfo = json_decode($postdata);
            $package_id = 0;
            if (property_exists($requestInfo, "packageId") != FALSE) {
                $package_id = $requestInfo->packageId;
            }
            else
            {
                $response['message'] = "Please select a package.";
                echo json_encode($response);
                return;
            }
            if($package_id == 0)
            {
                $response['message'] = "Invalid package to delete.";
                echo json_encode($response);
                return;
            }
            //deleting package info
            if($this->package_model->delete_package($package_id))
            {
                $response['message'] = $this->package_model->messages();
            }
            else
            {
                $response['message'] = $this->package_model->errors();
            }
            echo json_encode($response);
            return;
        }
    }
}

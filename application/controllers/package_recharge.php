<?php
class Package_recharge extends Role_Controller {

    function __construct() {
        parent::__construct();
        $this->load->helper('url');
        //No permission to load this page without login
        if (!$this->ion_auth->logged_in()) {
            redirect('auth/login', 'refresh');
        }
        $this->load->library('package_library');
        $this->load->library('transaction_library');
        $this->load->library('utils');
        $this->load->model('service_model');
    }
    
    public function index() 
    {
        $service_info_list = array();
        $service_info_array = $this->service_model->get_service_info_list(array(SERVICE_TYPE_ID_TOPUP_GP, SERVICE_TYPE_ID_TOPUP_ROBI, SERVICE_TYPE_ID_TOPUP_BANGLALINK, SERVICE_TYPE_ID_TOPUP_AIRTEL, SERVICE_TYPE_ID_TOPUP_TELETALK))->result_array();
        foreach ($service_info_array as $service_info) {
            $service_info_list[$service_info['service_id']] = $service_info;
        }
        if ($service_info_list[SERVICE_TYPE_ID_TOPUP_GP]['type_id'] == SERVICE_TYPE_ID_NOT_ALLOW_TRNASCATION && $service_info_list[SERVICE_TYPE_ID_TOPUP_ROBI]['type_id'] == SERVICE_TYPE_ID_NOT_ALLOW_TRNASCATION && $service_info_list[SERVICE_TYPE_ID_TOPUP_BANGLALINK]['type_id'] == SERVICE_TYPE_ID_NOT_ALLOW_TRNASCATION && $service_info_list[SERVICE_TYPE_ID_TOPUP_AIRTEL]['type_id'] == SERVICE_TYPE_ID_NOT_ALLOW_TRNASCATION && $service_info_list[SERVICE_TYPE_ID_TOPUP_TELETALK]['type_id'] == SERVICE_TYPE_ID_NOT_ALLOW_TRNASCATION) {
            $this->data['app'] = TRANSCATION_APP;
            $this->data['error_message'] = "Sorry !! Package recharge service is unavailable right now! please try again later!.";
            $this->template->load(null, 'common/error_message', $this->data);
            return;
        }
        
        $user_id = $this->session->userdata('user_id');
        $where = array(
            'user_id' => $user_id
        );
        if (file_get_contents("php://input") != null) {
            $response = array();
            $postdata = file_get_contents("php://input");
            $requestInfo = json_decode($postdata);
            $cell_number_list = array();
            $service_id_list = array();
            if (property_exists($requestInfo, "transactionDataList")) {
                $user_assigned_service_id_list = array();
                $user_topup_operator_id_list = $this->service_model->get_user_assigned_services($user_id)->result_array();
                if (!empty($user_topup_operator_id_list)) {
                    // generate user assign service id list and declare specific transction data list
                    foreach ($user_topup_operator_id_list as $operator_id_info) {
                        $user_assigned_service_id_list[] = $operator_id_info['service_id'];
                    }
                }

                $transaction_data_list = $requestInfo->transactionDataList;
                $transction_list = array();
                $total_amount = 0;
                foreach ($transaction_data_list as $key => $transaction_data) {
                    $mapping_id = $this->utils->get_random_mapping_id();
                    $description = "test";
                    $transaction_id = "";
                    $topup_data_info = array(
                        'user_id' => $user_id,
                        'transaction_id' => $transaction_id,
                        'description' => $description,
                        'mapping_id' => $mapping_id
                    );
                    if (property_exists($transaction_data, "topupOperatorId")) {
                        $service_id = $transaction_data->topupOperatorId;
                        if (!in_array($service_id, $user_assigned_service_id_list)) {
                            $response["message"] = "The Operator Id  is not assigned to you at serial number " . ($key + 1);
                            echo json_encode($response);
                            return;
                        }
                        if ($service_info_list[$service_id]['type_id'] == SERVICE_TYPE_ID_NOT_ALLOW_TRNASCATION) {
                            $response["message"] = $service_info_list[$service_id]['title'] . " Service Unavailable right now that you assigned  at serial number " . ($key + 1);
                            echo json_encode($response);
                            return;
                        }

                        $topup_data_info['service_id'] = $service_id;
                        $service_id_list[] = $service_id;
                        if ($service_info_list[$service_id]['type_id'] == SERVICE_TYPE_ID_ALLOW_TO_USE_LOCAL_SERVER) {
                            $topup_data_info['process_type_id'] = TRANSACTION_PROCESS_TYPE_ID_AUTO;
                        } else if ($service_info_list[$service_id]['type_id'] == SERVICE_TYPE_ID_ALLOW_TO_USE_WEBSERVER) {
                            $topup_data_info['process_type_id'] = TRANSACTION_PROCESS_TYPE_ID_MANUAL;
                        }
                    } else {
                        $response["message"] = "Operator Id  is Required at serial number " . ($key + 1);
                        echo json_encode($response);
                        return;
                    }
                    if (property_exists($transaction_data, "number")) {
                        $cell_no = $transaction_data->number;
                        if ($this->utils->cell_number_validation($cell_no) == FALSE) {
                            $response["message"] = "Please Enter a Valid Cell Number at serial number " . ($key + 1);
                            echo json_encode($response);
                            return;
                        }
                        $topup_data_info['cell_no'] = $cell_no;
                        $cell_number_list[] = $cell_no;
                    } else {
                        $response["message"] = "Please give a Cell number! Cell Number is Required   at serial number " . ($key + 1);
                        echo json_encode($response);
                        return;
                    }

                    if (property_exists($transaction_data, "topupType")) {
                        $topup_type_id = $transaction_data->topupType;
                        if ($topup_type_id != OPERATOR_TYPE_ID_PREPAID && $topup_type_id != OPERATOR_TYPE_ID_POSTPAID) {
                            $response["message"] = "Please give valid Operator Type Id at serial number " . ($key + 1);
                            echo json_encode($response);
                            return;
                        }
                        $topup_data_info['operator_type_id'] = $topup_type_id;
                    } else {
                        $response["message"] = "Operator Type Id  is Required at serial number " . ($key + 1);
                        echo json_encode($response);
                        return;
                    }
                    if (property_exists($transaction_data, "amount")) {
                        $amount = $transaction_data->amount;
                        $total_amount = $total_amount + $amount;
                        if (isset($amount)) {
                            if ($transaction_data->topupType == OPERATOR_TYPE_ID_POSTPAID && $transaction_data->topupOperatorId == SERVICE_TYPE_ID_TOPUP_GP) {
                                if ($amount < (int)TOPUP_POSTPAID_GP_MINIMUM_CASH_IN_AMOUNT) {
                                    $response["message"] = "Please give GP postpaid minimum amount TK. " . TOPUP_POSTPAID_GP_MINIMUM_CASH_IN_AMOUNT . "! at serial number" . ($key + 1);
                                    echo json_encode($response);
                                    return;
                                }
                            } else if ($amount < (int)TOPUP_MINIMUM_CASH_IN_AMOUNT) {
                                $response["message"] = "Please give a minimum amount TK. " . TOPUP_MINIMUM_CASH_IN_AMOUNT . "! at serial number" . ($key + 1);
                                echo json_encode($response);
                                return;
                            }
                            if ($amount > (int)TOPUP_MAXIMUM_CASH_IN_AMOUNT) {
                                $response["message"] = "Please give a maximum amount TK." . TOPUP_MAXIMUM_CASH_IN_AMOUNT . "! at serial number" . ($key + 1);
                                echo json_encode($response);
                                return;
                            }
                        }
                        $topup_data_info['amount'] = $amount;
                    } else {
                        $response["message"] = "Please give an Amount! Amount is Required   at serial number " . ($key + 1);
                        echo json_encode($response);
                        return;
                    }
                    $transction_list[] = $topup_data_info;
                    $validation_result = $this->transaction_library->check_transaction_interval_validation($cell_number_list, $service_id_list, $where);
                    if ($validation_result['validation_flag'] != FALSE) {
                        $transction_info = $validation_result['transction_info'];
                        $response["message"] = "Sorry !!" . $service_info_list[$transction_info['service_id']]['title'] . " service is unavailable right now for this number " . $transction_info['cell_no'];
                        echo json_encode($response);
                        return;
                    }
                }

                $this->load->library("security");
                $transction_list = $this->security->xss_clean($transction_list);
                if ($this->transaction_library->add_multipule_transactions($transction_list, $user_assigned_service_id_list, $total_amount, $user_id) !== FALSE) {
                    $response['message'] = $this->transaction_library->messages_array();
                } else {
                    $response['message'] = $this->transaction_library->errors_array();
                }
                echo json_encode($response);
                return;
            } else {
                $response['message'] = "Sorry!! Please give a transaction Info";
                echo json_encode($response);
                return;
            }
        }
        //checking whether user has permission for topup transaction
        $permission_exists = FALSE;
        $service_list = $this->service_model->get_user_assigned_services($user_id)->result_array();
        foreach ($service_list as $service_info) {
            //if in future there is new service under topup then update the logic here
            $service_id = $service_info['service_id'];
            if ($service_id == SERVICE_TYPE_ID_TOPUP_GP || $service_id == SERVICE_TYPE_ID_TOPUP_ROBI || $service_id == SERVICE_TYPE_ID_TOPUP_BANGLALINK || $service_id == SERVICE_TYPE_ID_TOPUP_AIRTEL || $service_id == SERVICE_TYPE_ID_TOPUP_TELETALK) {
                $permission_exists = TRUE;
            }
        }
        if (!$permission_exists) {
            //you are not allowed to use topup transaction
            $this->data['app'] = TRANSCATION_APP;
            $this->data['error_message'] = "Sorry !! You are not allowed to use package recharge service.";
            $this->template->load(null, 'common/error_message', $this->data);
            return;
        }
        
        
        if (file_get_contents("php://input") != null) 
        {
            $response = array();            
            echo json_encode($response);
            return;
        }
        $this->load->library('transaction_library');
        $transaction_list_array = $this->transaction_library->get_user_transaction_list(array($user_id), array(SERVICE_TYPE_ID_TOPUP_GP, SERVICE_TYPE_ID_TOPUP_ROBI, SERVICE_TYPE_ID_TOPUP_BANGLALINK, SERVICE_TYPE_ID_TOPUP_AIRTEL, SERVICE_TYPE_ID_TOPUP_TELETALK), array(), '', 0, 0, TRANSACTION_PAGE_DEFAULT_LIMIT, 0);
        $transaction_list = array();
        if (!empty($transaction_list_array)) {
            $transaction_list = $transaction_list_array['transaction_list'];
        }
        $this->data['transaction_list'] = $transaction_list;
        $this->data['topup_type_list'] = $this->service_model->get_all_operator_types()->result_array();
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
        $this->template->load(null, 'transaction/package/index', $this->data);
    }
    
    /*
     * This method will return packages of an operator
     * @author nazmul hasan on 23rd november 2016
     */
    public function get_operator_packages() 
    {
        if (file_get_contents("php://input") != null) {
            $response = array();  
            $postdata = file_get_contents("php://input");
            $requestInfo = json_decode($postdata);
            $operator_id = 0;
            if (property_exists($requestInfo, "operatorId") != FALSE) {
                $operator_id = $requestInfo->operatorId;
            }
            else
            {
                $response['message'] = "Please select an operator.";
                echo json_encode($response);
                return;
            }
            if($operator_id == 0)
            {
                $response['message'] = "Invalid operator.";
                echo json_encode($response);
                return;
            }            
            $package_list = array();
            $package_list[] = array(
                'package_id' => 0,
                'title' => 'Select package',
                'selected' => true
            );
            $package_list_array = $this->package_library->get_all_packages($operator_id);
            foreach($package_list_array as $package_info)
            {
                $package_list[] = array(
                    'package_id' => $package_info['package_id'],
                    'title' => $package_info['package_title']
                );
            }
            $response['package_list'] = $package_list;
            echo json_encode($response);
            return;
        }
    }
    
    /*
     * This method will return packages info
     * @author nazmul hasan on 23rd november 2016
     */
    public function get_package_info()
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
                $response['message'] = "Invalid package.";
                echo json_encode($response);
                return;
            }
            $package_info = array();
            $package_info_array = $this->package_model->get_package_info($package_id)->result_array();
            if(!empty($package_info_array))
            {
                $package_info = $package_info_array[0];
            }
            $response['package_info'] = $package_info;
            echo json_encode($response);
            return;
        }
    }
}

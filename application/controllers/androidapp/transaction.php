<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Transaction extends CI_Controller {

    function __construct() {
        parent::__construct();
        $this->load->library('Utils');
    }

    public function topup() {
        $cell_no = $this->input->post('number');
        $amount = $this->input->post('amount');
        $user_id = $this->input->post('user_id');
        $session_id = $this->input->post('session_id');
        $topup_type_id = $this->input->post('operator_type_id');
        
        $response = array();
        
        //checking whether user has valid session or not
        $this->load->model('androidapp/app_reseller_model');
        $app_session_id_array = $this->app_reseller_model->get_app_session_id($user_id)->result_array();
        if (!empty($app_session_id_array) && $session_id != $app_session_id_array[0]['app_session_id']) {
            $response['response_code'] = ERROR_CODE_SESSION_EXPIRED;
            $response['message'] = "Sorry !! session id doesn't match !";
            echo json_encode($response);
            return;
        }
        
        $this->load->model('service_model');
        //identifying whether topup services are available or not
        $service_available = true;
        //topup services info are reading from db
        $service_info_list = array();
        $service_info_array = $this->service_model->get_service_info_list(array(SERVICE_TYPE_ID_TOPUP_GP, SERVICE_TYPE_ID_TOPUP_ROBI, SERVICE_TYPE_ID_TOPUP_BANGLALINK, SERVICE_TYPE_ID_TOPUP_AIRTEL, SERVICE_TYPE_ID_TOPUP_TELETALK))->result_array();
        foreach ($service_info_array as $service_info) {
            $service_info_list[$service_info['service_id']] = $service_info;
        }
        //identifying whether topup services are switched off or not
        if ($service_info_list[SERVICE_TYPE_ID_TOPUP_GP]['type_id'] == SERVICE_TYPE_ID_NOT_ALLOW_TRNASCATION && $service_info_list[SERVICE_TYPE_ID_TOPUP_ROBI]['type_id'] == SERVICE_TYPE_ID_NOT_ALLOW_TRNASCATION && $service_info_list[SERVICE_TYPE_ID_TOPUP_BANGLALINK]['type_id'] == SERVICE_TYPE_ID_NOT_ALLOW_TRNASCATION && $service_info_list[SERVICE_TYPE_ID_TOPUP_AIRTEL]['type_id'] == SERVICE_TYPE_ID_NOT_ALLOW_TRNASCATION && $service_info_list[SERVICE_TYPE_ID_TOPUP_TELETALK]['type_id'] == SERVICE_TYPE_ID_NOT_ALLOW_TRNASCATION) {
            $service_available = false;
        }
        
        $permission_exists = FALSE;
        $service_id_user_service_info_map = array();
        $user_assigned_service_id_list = array();
        $user_assigned_service_array = $this->service_model->get_user_assigned_services($user_id)->result_array();
        foreach ($user_assigned_service_array as $service_info) {
            $user_assigned_service_id_list[] = $service_info['service_id'];
            $service_id_user_service_info_map[$service_info['service_id']] = $service_info;
            $service_id = $service_info['service_id'];
            if ($service_id == SERVICE_TYPE_ID_TOPUP_GP || $service_id == SERVICE_TYPE_ID_TOPUP_ROBI || $service_id == SERVICE_TYPE_ID_TOPUP_BANGLALINK || $service_id == SERVICE_TYPE_ID_TOPUP_AIRTEL || $service_id == SERVICE_TYPE_ID_TOPUP_TELETALK) {
                $permission_exists = TRUE;
            }
        }
        
        //if all topup services are switched off
        //then showing proper message to the user
        if (!$service_available) {
            $response['response_code'] = ERROR_CODE_SERVICE_UNAVAILABLE;
            $response["message"] = "Sorry !! Topup service is unavailable right now! please try again later!.";
            echo json_encode($response);
            return;
        }
        //if user has no permission to use topup services
        //then showing proper message to the user
        if (!$permission_exists) {
            $response['response_code'] = ERROR_CODE_SERVICE_UNAVAILABLE;
            $response["message"] = "Sorry !! You have no permission to use this service. Please contact with system admin.";
            echo json_encode($response);
            return;
        }
        
        //cell number validation
        if (isset($cell_no)) {
            if ($this->utils->cell_number_validation($cell_no) == FALSE) {
                $response['response_code'] = ERROR_CODE_SERVICE_UNAVAILABLE;
                $response["message"] = "Please Enter a Valid Cell Number";
                echo json_encode($response);
                return;
            }
        } else {
            $response['response_code'] = ERROR_CODE_SERVICE_UNAVAILABLE;
            $response["message"] = "Cell number is required.";
            echo json_encode($response);
            return;
        }
        //topup type validation
        if (isset($topup_type_id)) {
            if ($topup_type_id != OPERATOR_TYPE_ID_PREPAID && $topup_type_id != OPERATOR_TYPE_ID_POSTPAID) {
                $response['response_code'] = ERROR_CODE_SERVICE_UNAVAILABLE;
                $response["message"] = "Please give valid Topup Type";
                echo json_encode($response);
                return;
            }
        } 
        else {
            $response['response_code'] = ERROR_CODE_SERVICE_UNAVAILABLE;
            $response["message"] = "Topup Type Id  is Required";
            echo json_encode($response);
            return;
        }
        //transaction amount validation
        if (isset($amount)) {
            if ($topup_type_id == OPERATOR_TYPE_ID_POSTPAID && $service_id == SERVICE_TYPE_ID_TOPUP_GP) {
                if ($amount < (int)TOPUP_POSTPAID_GP_MINIMUM_CASH_IN_AMOUNT) {
                    $response['response_code'] = ERROR_CODE_SERVICE_UNAVAILABLE;
                    $response["message"] = "Please give GP postpaid minimum amount TK. " . TOPUP_POSTPAID_GP_MINIMUM_CASH_IN_AMOUNT . "! at serial number" . ($key + 1);
                    echo json_encode($response);
                    return;
                }
            } else if ($amount < (int)TOPUP_MINIMUM_CASH_IN_AMOUNT) {
                $response['response_code'] = ERROR_CODE_SERVICE_UNAVAILABLE;
                $response["message"] = "Please give a minimum amount TK. " . TOPUP_MINIMUM_CASH_IN_AMOUNT . "! at serial number" . ($key + 1);
                echo json_encode($response);
                return;
            }
            if ($amount > (int)TOPUP_MAXIMUM_CASH_IN_AMOUNT) {
                $response['response_code'] = ERROR_CODE_SERVICE_UNAVAILABLE;
                $response["message"] = "Please give a maximum amount TK." . TOPUP_MAXIMUM_CASH_IN_AMOUNT . "! at serial number" . ($key + 1);
                echo json_encode($response);
                return;
            }
        }
        else {
            $response['response_code'] = ERROR_CODE_SERVICE_UNAVAILABLE;
            $response["message"] = "Amount is Required";
            echo json_encode($response);
            return;
        }
        
        $service_id = 0;
        if (strpos($cell_no, "+88017") === 0 || strpos($cell_no, "88017") === 0 || strpos($cell_no, "017") === 0) {
            $service_id = SERVICE_TYPE_ID_TOPUP_GP;
        } else if (strpos($cell_no, "+88018") === 0 || strpos($cell_no, "88018") === 0 || strpos($cell_no, "018") === 0) {
            $service_id = SERVICE_TYPE_ID_TOPUP_ROBI;
        }
        if (strpos($cell_no, "+88019") === 0 || strpos($cell_no, "88019") === 0 || strpos($cell_no, "019") === 0) {
            $service_id = SERVICE_TYPE_ID_TOPUP_BANGLALINK;
        }
        if (strpos($cell_no, "+88016") === 0 || strpos($cell_no, "88016") === 0 || strpos($cell_no, "016") === 0) {
            $service_id = SERVICE_TYPE_ID_TOPUP_AIRTEL;
        }
        if (strpos($cell_no, "+88015") === 0 || strpos($cell_no, "88015") === 0 || strpos($cell_no, "015") === 0) {
            $service_id = SERVICE_TYPE_ID_TOPUP_TELETALK;
        }

        $transaction_id = "";
        $mapping_id = $this->utils->get_random_mapping_id();
        $description = "test";
        $transaction_data = array(
            'user_id' => $user_id,
            'transaction_id' => $transaction_id,
            'mapping_id' => $mapping_id,
            'service_id' => $service_id,
            'operator_type_id' => $topup_type_id,
            'amount' => $amount,
            'cell_no' => $cell_no,
            'description' => $description
        );
        if ($service_info_list[$service_id]['type_id'] == SERVICE_TYPE_ID_ALLOW_TO_USE_LOCAL_SERVER) {
            $transaction_data['process_type_id'] = TRANSACTION_PROCESS_TYPE_ID_AUTO;
        } else if ($service_info_list[$service_id]['type_id'] == SERVICE_TYPE_ID_ALLOW_TO_USE_WEBSERVER) {
            $transaction_data['process_type_id'] = TRANSACTION_PROCESS_TYPE_ID_MANUAL;
        }
        if(array_key_exists($transaction_data['service_id'], $service_id_user_service_info_map) && $service_id_user_service_info_map[$transaction_data['service_id']]['rate'] != 0 )
        {
            $transaction_data['cost'] = (double)((double)$amount + (double)$amount / $service_id_user_service_info_map[$transaction_data['service_id']]['rate'] * $service_id_user_service_info_map[$transaction_data['service_id']]['charge']);
        }
        else
        {
            $response['response_code'] = ERROR_CODE_SERVICE_UNAVAILABLE;
            $response["message"] = "Invalid charge configuration for this service.";
            echo json_encode($response);
            return;
        }
        
        $this->load->library("security");
        $transaction_data = $this->security->xss_clean($transaction_data);
        $this->load->library('transaction_library');
        if ($this->transaction_library->add_multipule_transactions(array($transaction_data), $user_assigned_service_id_list, $transaction_data['cost']) !== FALSE) {
            $this->load->library('reseller_library');
            $current_balance = $this->reseller_library->get_user_current_balance($user_id);
            $response['current_balance'] = $current_balance;

            $response['message'] = $this->transaction_library->messages_array();
            $response['response_code'] = RESPONSE_CODE_SUCCESS;
        } else {
            $response['message'] = $this->transaction_library->errors_array();
            $response['response_code'] = ERROR_CODE_SERVER_EXCEPTION;
        }
        echo json_encode($response);
    }
    
    public function bkash() {
        $cell_no = $this->input->post('number');
        $amount = $this->input->post('amount');
        $service_id = $this->input->post('service_id');
        $user_id = $this->input->post('user_id');
        $session_id = $this->input->post('session_id');       
        
        $response = array();
        
        //checking whether user has valid session or not
        $this->load->model('androidapp/app_reseller_model');
        $app_session_id_array = $this->app_reseller_model->get_app_session_id($user_id)->result_array();
        if (!empty($app_session_id_array) && $session_id != $app_session_id_array[0]['app_session_id']) {
            $response['response_code'] = ERROR_CODE_SESSION_EXPIRED;
            $response['message'] = "Sorry !! session id doesn't match !";
            echo json_encode($response);
            return;
        }
        
        $this->load->model('service_model');
        //identifying whether both cashin and cashout services are available or not
        $service_available = true;
        //bkash cashin and cashout service info is reading from db
        $service_info_list = array();
        $service_info_array = $this->service_model->get_service_info_list(array(SERVICE_TYPE_ID_BKASH_CASHIN, SERVICE_TYPE_ID_BKASH_CASHOUT))->result_array();
        foreach ($service_info_array as $service_info) {
            $service_info_list[$service_info['service_id']] = $service_info;
        }
        //identifying whether both cashin and cashout services are switched off or not
        if ($service_info_list[SERVICE_TYPE_ID_BKASH_CASHIN]['type_id'] == SERVICE_TYPE_ID_NOT_ALLOW_TRNASCATION && $service_info_list[SERVICE_TYPE_ID_BKASH_CASHOUT]['type_id'] == SERVICE_TYPE_ID_NOT_ALLOW_TRNASCATION) {
            $service_available = false;
        }
        
        //checking whether user has permission to show this feature or not
        $permission_exists = FALSE;
        $user_service_info_list = array();
        $bkash_assigned_service_id_list = array();
        $user_service_array = $this->service_model->get_user_assigned_services($user_id)->result_array();
        foreach ($user_service_array as $service_info) {
            //if in future there is new service under bkash then update the logic here
            if ($service_info['service_id'] == SERVICE_TYPE_ID_BKASH_CASHIN || $service_info['service_id'] == SERVICE_TYPE_ID_BKASH_CASHOUT) {
                $permission_exists = TRUE;
                $user_service_info_list[$service_info['service_id']] = $service_info;
                $bkash_assigned_service_id_list[] = $service_info['service_id'];
            }
        }
        
        //if both cash in and cash out services are switched off
        //then showing proper message to the user
        if (!$service_available) {
            $response['response_code'] = ERROR_CODE_SERVICE_UNAVAILABLE;
            $response["message"] = "Sorry !! Bkash service is unavailable right now! please try again later!.";
            echo json_encode($response);
            return;
        }
        //if user has no permission to use both cash in and cash out services
        //then showing proper message to the user
        if (!$permission_exists) {
            $response['response_code'] = ERROR_CODE_SERVICE_UNAVAILABLE;
            $response["message"] = "Sorry !! You have no permission to use this service. Please contact with system admin.";
            echo json_encode($response);
            return;
        }
        
        //checking bkash cash in amout limit validation
        if (isset($amount) && $service_id == SERVICE_TYPE_ID_BKASH_CASHIN) {
            if ($amount < (int)BKASH_MINIMUM_CASH_IN_AMOUNT) {
                $response['response_code'] = ERROR_CODE_SERVICE_UNAVAILABLE;
                $response["message"] = "Cash In. Please give a minimum amount TK. " . BKASH_MINIMUM_CASH_IN_AMOUNT . "!";
                echo json_encode($response);
                return;
            }
            if ($amount > (int)BKASH_MAXIMUM_CASH_IN_AMOUNT) {
                $response['response_code'] = ERROR_CODE_SERVICE_UNAVAILABLE;
                $response["message"] = "Cash In. Please give a maximum amount TK." . BKASH_MAXIMUM_CASH_IN_AMOUNT . "!";
                echo json_encode($response);
                return;
            }
        }
        //checking bkash cashout amount limit validation
        if (isset($amount) && $service_id == SERVICE_TYPE_ID_BKASH_CASHOUT) 
        {
            if ($amount < (int)BKASH_MINIMUM_CASH_OUT_AMOUNT) {
                $response['response_code'] = ERROR_CODE_SERVICE_UNAVAILABLE;
                $response["message"] = "Cash Out. Please give a minimum amount TK. " . BKASH_MINIMUM_CASH_OUT_AMOUNT . "!";
                echo json_encode($response);
                return;
            }
            if ($amount > (int)BKASH_MAXIMUM_CASH_OUT_AMOUNT) {
                $response['response_code'] = ERROR_CODE_SERVICE_UNAVAILABLE;
                $response["message"] = "Cash Out. Please give a maximum amount TK." . BKASH_MAXIMUM_CASH_OUT_AMOUNT . "!";
                echo json_encode($response);
                return;
            }
        }
        
        //transaction type id cash in or cash out
        $type_id = "";
        if($service_id == SERVICE_TYPE_ID_BKASH_CASHIN)
        {
            $type_id = TRANSACTION_TYPE_ID_CASHIN;
        }
        else if($service_id == SERVICE_TYPE_ID_BKASH_CASHOUT)
        {
            $type_id = TRANSACTION_TYPE_ID_CASHOUT;
        }
        
        $transaction_id = "";
        $description = "test";
        $mapping_id = $this->utils->get_random_mapping_id();
        $transaction_data = array(
            'user_id' => $user_id,
            'transaction_id' => $transaction_id,
            'mapping_id' => $mapping_id,
            'service_id' => $service_id,
            'operator_type_id' => $type_id,
            'amount' => $amount,
            'cell_no' => $cell_no,
            'description' => $description
        );
        //calculating cost of this transaction
        if(array_key_exists($transaction_data['service_id'], $user_service_info_list) && $user_service_info_list[$transaction_data['service_id']]['rate'] != 0 )
        {
            $transaction_data['cost'] = (double)((double)$amount + (double)$amount / $user_service_info_list[$transaction_data['service_id']]['rate'] * $user_service_info_list[$transaction_data['service_id']]['charge']);
        }
        else
        {
            $response['response_code'] = ERROR_CODE_SERVICE_UNAVAILABLE;
            $response["message"] = "Invalid charge configuration for this service";
            echo json_encode($response);
            return;
        }
        //transaction process type by web server or local server
        if ($service_info_list[$service_id]['type_id'] == SERVICE_TYPE_ID_ALLOW_TO_USE_LOCAL_SERVER) {
            $transaction_data['process_type_id'] = TRANSACTION_PROCESS_TYPE_ID_AUTO;
        } else if ($service_info_list[$service_id]['type_id'] == SERVICE_TYPE_ID_ALLOW_TO_USE_WEBSERVER) {
            $transaction_data['process_type_id'] = TRANSACTION_PROCESS_TYPE_ID_MANUAL;
        }
        else
        {
            $response['response_code'] = ERROR_CODE_SERVICE_UNAVAILABLE;
            $response["message"] = "Invalid service configuration by the system. Please contact with system admin.";
            echo json_encode($response);
            return;
        }
        

        $this->load->library("security");
        $transaction_data = $this->security->xss_clean($transaction_data);
        $this->load->library('transaction_library');
        if ($this->transaction_library->add_multipule_transactions(array($transaction_data), $bkash_assigned_service_id_list, $transaction_data['cost']) !== FALSE) {
            $this->load->library('reseller_library');
            $current_balance = $this->reseller_library->get_user_current_balance($user_id);
            $response['current_balance'] = $current_balance;

            $response['message'] = $this->transaction_library->messages_array();
            $response['response_code'] = RESPONSE_CODE_SUCCESS;
        } else {
            $response['message'] = $this->transaction_library->errors_array();
            $response['response_code'] = ERROR_CODE_SERVER_EXCEPTION;
        }
        echo json_encode($response);
    }

    public function dbbl() {
        $cell_no = $this->input->post('number');
        $amount = $this->input->post('amount');
        $user_id = $this->input->post('user_id');
        $session_id = $this->input->post('session_id');
        $response = array();
        $service_status_type = SERVICE_TYPE_ID_ALLOW_TO_USE_LOCAL_SERVER;
        $this->load->model('service_model');
        $service_info_array = $this->service_model->get_service_info_list(array(SERVICE_TYPE_ID_DBBL_CASHIN))->result_array();
        if (!empty($service_info_array)) {
            $service_status_type = $service_info_array[0]['type_id'];
        }
        if ($service_status_type == SERVICE_TYPE_ID_NOT_ALLOW_TRNASCATION) {
            $response['response_code'] = ERROR_CODE_SERVICE_UNAVAILABLE;
            $response["message"] = "Sorry !! DBBL service is unavailable right now! please try again later!.";
            echo json_encode($response);
            return;
        }
        $this->load->model('androidapp/app_reseller_model');
        $app_session_id_array = $this->app_reseller_model->get_app_session_id($user_id)->result_array();
        if (!empty($app_session_id_array) && $session_id != $app_session_id_array[0]['app_session_id']) {
            $response['response_code'] = ERROR_CODE_SESSION_EXPIRED;
            $response['message'] = "Sorry !! session id doesn't match !";
            echo json_encode($response);
            return;
        }
        $api_key = API_KEY_DBBL_CASHIN;
        $transaction_id = "";
        $description = "test";
        $transaction_data = array(
            'user_id' => $user_id,
            'transaction_id' => $transaction_id,
            'service_id' => SERVICE_TYPE_ID_DBBL_CASHIN,
            'amount' => $amount,
            'cell_no' => $cell_no,
            'description' => $description
        );
        if ($service_status_type == SERVICE_TYPE_ID_ALLOW_TO_USE_LOCAL_SERVER) {
            $transaction_data['process_type_id'] = TRANSACTION_PROCESS_TYPE_ID_AUTO;
        } else if ($service_status_type == SERVICE_TYPE_ID_ALLOW_TO_USE_WEBSERVER) {
            $transaction_data['process_type_id'] = TRANSACTION_PROCESS_TYPE_ID_MANUAL;
        }
        $this->load->library("security");
        $transaction_data = $this->security->xss_clean($transaction_data);
        $this->load->library('transaction_library');
        if ($this->transaction_library->add_transaction($api_key, $transaction_data) !== FALSE) {
            $this->load->library('reseller_library');
            $current_balance = $this->reseller_library->get_user_current_balance($user_id);
            $response['current_balance'] = $current_balance;

            $response['message'] = $this->transaction_library->messages_array();
            $response['response_code'] = RESPONSE_CODE_SUCCESS;
        } else {
            $response['message'] = $this->transaction_library->errors_array();
            $response['response_code'] = ERROR_CODE_SERVER_EXCEPTION;
        }
        echo json_encode($response);
    }

    public function mcash() {
        $cell_no = $this->input->post('number');
        $amount = $this->input->post('amount');
        $user_id = $this->input->post('user_id');
        $session_id = $this->input->post('session_id');
        $response = array();
        $service_status_type = SERVICE_TYPE_ID_ALLOW_TO_USE_LOCAL_SERVER;
        $this->load->model('service_model');
        $service_info_array = $this->service_model->get_service_info_list(array(SERVICE_TYPE_ID_MCASH_CASHIN))->result_array();
        if (!empty($service_info_array)) {
            $service_status_type = $service_info_array[0]['type_id'];
        }
        if ($service_status_type == SERVICE_TYPE_ID_NOT_ALLOW_TRNASCATION) {
            $response['response_code'] = ERROR_CODE_SERVICE_UNAVAILABLE;
            $response["message"] = "Sorry !! Mcash service is unavailable right now! please try again later!.";
            echo json_encode($response);
            return;
        }
        $this->load->model('androidapp/app_reseller_model');
        $app_session_id_array = $this->app_reseller_model->get_app_session_id($user_id)->result_array();
        if (!empty($app_session_id_array) && $session_id != $app_session_id_array[0]['app_session_id']) {
            $response['response_code'] = ERROR_CODE_SESSION_EXPIRED;
            $response['message'] = "Sorry !! session id doesn't match !";
            echo json_encode($response);
            return;
        }
        $api_key = API_KEY_MKASH_CASHIN;
        $transaction_id = "";
        $description = "test";
        $transaction_data = array(
            'user_id' => $user_id,
            'transaction_id' => $transaction_id,
            'service_id' => SERVICE_TYPE_ID_MCASH_CASHIN,
            'amount' => $amount,
            'cell_no' => $cell_no,
            'description' => $description
        );
        if ($service_status_type == SERVICE_TYPE_ID_ALLOW_TO_USE_LOCAL_SERVER) {
            $transaction_data['process_type_id'] = TRANSACTION_PROCESS_TYPE_ID_AUTO;
        } else if ($service_status_type == SERVICE_TYPE_ID_ALLOW_TO_USE_WEBSERVER) {
            $transaction_data['process_type_id'] = TRANSACTION_PROCESS_TYPE_ID_MANUAL;
        }
        $this->load->library("security");
        $transaction_data = $this->security->xss_clean($transaction_data);
        $this->load->library('transaction_library');
        if ($this->transaction_library->add_transaction($api_key, $transaction_data) !== FALSE) {
            $this->load->library('reseller_library');
            $current_balance = $this->reseller_library->get_user_current_balance($user_id);
            $response['current_balance'] = $current_balance;

            $response['message'] = $this->transaction_library->messages_array();
            $response['response_code'] = RESPONSE_CODE_SUCCESS;
        } else {
            $response['message'] = $this->transaction_library->errors_array();
            $response['response_code'] = ERROR_CODE_SERVER_EXCEPTION;
        }
        echo json_encode($response);
    }

    public function ucash() {
        $cell_no = $this->input->post('number');
        $amount = $this->input->post('amount');
        $user_id = $this->input->post('user_id');
        $session_id = $this->input->post('session_id');
        $response = array();
        $service_status_type = SERVICE_TYPE_ID_ALLOW_TO_USE_LOCAL_SERVER;
        $this->load->model('service_model');
        $service_info_array = $this->service_model->get_service_info_list(array(SERVICE_TYPE_ID_UCASH_CASHIN))->result_array();
        if (!empty($service_info_array)) {
            $service_status_type = $service_info_array[0]['type_id'];
        }
        if ($service_status_type == SERVICE_TYPE_ID_NOT_ALLOW_TRNASCATION) {
            $response['response_code'] = ERROR_CODE_SERVICE_UNAVAILABLE;
            $response["message"] = "Sorry !! Ucash service is unavailable right now! please try again later!.";
            echo json_encode($response);
            return;
        }
        $this->load->model('androidapp/app_reseller_model');
        $app_session_id_array = $this->app_reseller_model->get_app_session_id($user_id)->result_array();
        if (!empty($app_session_id_array) && $session_id != $app_session_id_array[0]['app_session_id']) {
            $response['response_code'] = ERROR_CODE_SESSION_EXPIRED;
            $response['message'] = "Sorry !! session id doesn't match !";
            echo json_encode($response);
            return;
        }
        $api_key = API_KEY_UKASH_CASHIN;
        $transaction_id = "";
        $description = "test";
        $transaction_data = array(
            'user_id' => $user_id,
            'transaction_id' => $transaction_id,
            'service_id' => SERVICE_TYPE_ID_UCASH_CASHIN,
            'amount' => $amount,
            'cell_no' => $cell_no,
            'description' => $description
        );
        if ($service_status_type == SERVICE_TYPE_ID_ALLOW_TO_USE_LOCAL_SERVER) {
            $transaction_data['process_type_id'] = TRANSACTION_PROCESS_TYPE_ID_AUTO;
        } else if ($service_status_type == SERVICE_TYPE_ID_ALLOW_TO_USE_WEBSERVER) {
            $transaction_data['process_type_id'] = TRANSACTION_PROCESS_TYPE_ID_MANUAL;
        }
        $this->load->library("security");
        $transaction_data = $this->security->xss_clean($transaction_data);
        $this->load->library('transaction_library');
        if ($this->transaction_library->add_transaction($api_key, $transaction_data) !== FALSE) {
            $this->load->library('reseller_library');
            $current_balance = $this->reseller_library->get_user_current_balance($user_id);
            $response['current_balance'] = $current_balance;

            $response['message'] = $this->transaction_library->messages_array();
            $response['response_code'] = RESPONSE_CODE_SUCCESS;
        } else {
            $response['message'] = $this->transaction_library->errors_array();
            $response['response_code'] = ERROR_CODE_SERVER_EXCEPTION;
        }
        echo json_encode($response);
    }

    public function get_bkash_transaction_list() {
        $user_id = $this->input->post('user_id');
        $session_id = $this->input->post('session_id');
        $response = array(
            'response_code' => RESPONSE_CODE_SUCCESS,
            'transaction_list' => array()
        );
        $this->load->model('androidapp/app_reseller_model');
        $app_session_id_array = $this->app_reseller_model->get_app_session_id($user_id)->result_array();
        if (!empty($app_session_id_array) && $session_id != $app_session_id_array[0]['app_session_id']) {
            $response['response_code'] = ERROR_CODE_SESSION_EXPIRED;
            $response['message'] = "Sorry !! session id doesn't match !";
            echo json_encode($response);
            return;
        }
        $this->load->library('transaction_library');
        $transaction_list_array = $this->transaction_library->get_user_transaction_list(array($user_id), array(SERVICE_TYPE_ID_BKASH_CASHIN), array(), '', 0, 0,TRANSACTION_PAGE_DEFAULT_LIMIT, 0);
        $transaction_list = array();
        if (!empty($transaction_list_array)) {
            foreach ($transaction_list_array['transaction_list'] as $temp_transaction_info) {
                $transaction_info = array(
                    'cell_no' => $temp_transaction_info['cell_no'],
                    'amount' => $temp_transaction_info['amount'],
                    'title' => $temp_transaction_info['service_title'],
                    'status' => $temp_transaction_info['status']
                );
                $transaction_list[] = $transaction_info;
            }
            $response['transaction_list'] = $transaction_list;
            $response['response_code'] = RESPONSE_CODE_SUCCESS;
            $response['message'] = "Transaction list.";
        }
        echo json_encode($response);
    }

    public function get_dbbl_transaction_list() {
        $user_id = $this->input->post('user_id');
        $session_id = $this->input->post('session_id');
        $response = array(
            'response_code' => RESPONSE_CODE_SUCCESS,
            'transaction_list' => array()
        );
        $this->load->model('androidapp/app_reseller_model');
        $app_session_id_array = $this->app_reseller_model->get_app_session_id($user_id)->result_array();
        if (!empty($app_session_id_array) && $session_id != $app_session_id_array[0]['app_session_id']) {
            $response['response_code'] = ERROR_CODE_SESSION_EXPIRED;
            $response['message'] = "Sorry !! session id doesn't match !";
            echo json_encode($response);
            return;
        }
        $this->load->library('transaction_library');
        $transaction_list_array = $this->transaction_library->get_user_transaction_list(array($user_id), array(SERVICE_TYPE_ID_DBBL_CASHIN), array(), '', 0, 0, TRANSACTION_PAGE_DEFAULT_LIMIT, 0);
        $transaction_list = array();
        if (!empty($transaction_list_array)) {
            foreach ($transaction_list_array['transaction_list'] as $temp_transaction_info) {
                $transaction_info = array(
                    'cell_no' => $temp_transaction_info['cell_no'],
                    'amount' => $temp_transaction_info['amount'],
                    'title' => $temp_transaction_info['service_title'],
                    'status' => $temp_transaction_info['status']
                );
                $transaction_list[] = $transaction_info;
            }
            $response['transaction_list'] = $transaction_list;
            $response['response_code'] = RESPONSE_CODE_SUCCESS;
            $response['message'] = "Transaction list.";
        }
        echo json_encode($response);
    }

    public function get_mcash_transaction_list() {
        $user_id = $this->input->post('user_id');
        $session_id = $this->input->post('session_id');
        $response = array(
            'response_code' => RESPONSE_CODE_SUCCESS,
            'transaction_list' => array()
        );
        $this->load->model('androidapp/app_reseller_model');
        $app_session_id_array = $this->app_reseller_model->get_app_session_id($user_id)->result_array();
        if (!empty($app_session_id_array) && $session_id != $app_session_id_array[0]['app_session_id']) {
            $response['response_code'] = ERROR_CODE_SESSION_EXPIRED;
            $response['message'] = "Sorry !! session id doesn't match !";
            echo json_encode($response);
            return;
        }
        $this->load->library('transaction_library');
        $transaction_list_array = $this->transaction_library->get_user_transaction_list(array($user_id), array(SERVICE_TYPE_ID_MCASH_CASHIN), array(), '', 0, 0, TRANSACTION_PAGE_DEFAULT_LIMIT, 0);
        $transaction_list = array();
        if (!empty($transaction_list_array)) {
            foreach ($transaction_list_array['transaction_list'] as $temp_transaction_info) {
                $transaction_info = array(
                    'cell_no' => $temp_transaction_info['cell_no'],
                    'amount' => $temp_transaction_info['amount'],
                    'title' => $temp_transaction_info['service_title'],
                    'status' => $temp_transaction_info['status']
                );
                $transaction_list[] = $transaction_info;
            }
            $response['transaction_list'] = $transaction_list;
            $response['response_code'] = RESPONSE_CODE_SUCCESS;
            $response['message'] = "Transaction list.";
        }
        echo json_encode($response);
    }

    public function get_ucash_transaction_list() {
        $user_id = $this->input->post('user_id');
        $session_id = $this->input->post('session_id');
        $response = array(
            'response_code' => RESPONSE_CODE_SUCCESS,
            'transaction_list' => array()
        );
        $this->load->model('androidapp/app_reseller_model');
        $app_session_id_array = $this->app_reseller_model->get_app_session_id($user_id)->result_array();
        if (!empty($app_session_id_array) && $session_id != $app_session_id_array[0]['app_session_id']) {
            $response['response_code'] = ERROR_CODE_SESSION_EXPIRED;
            $response['message'] = "Sorry !! session id doesn't match !";
            echo json_encode($response);
            return;
        }
        $this->load->library('transaction_library');
        $transaction_list_array = $this->transaction_library->get_user_transaction_list(array($user_id), array(SERVICE_TYPE_ID_UCASH_CASHIN), array(), '', 0, 0,TRANSACTION_PAGE_DEFAULT_LIMIT, 0);
        $transaction_list = array();
        if (!empty($transaction_list_array)) {
            foreach ($transaction_list_array['transaction_list'] as $temp_transaction_info) {
                $transaction_info = array(
                    'cell_no' => $temp_transaction_info['cell_no'],
                    'amount' => $temp_transaction_info['amount'],
                    'title' => $temp_transaction_info['service_title'],
                    'status' => $temp_transaction_info['status']
                );
                $transaction_list[] = $transaction_info;
            }
            $response['transaction_list'] = $transaction_list;
            $response['response_code'] = RESPONSE_CODE_SUCCESS;
            $response['message'] = "Transaction list.";
        }
        echo json_encode($response);
    }

    public function get_topup_transaction_list() {
        $user_id = $this->input->post('user_id');
        $session_id = $this->input->post('session_id');
        $response = array(
            'response_code' => RESPONSE_CODE_SUCCESS,
            'transaction_list' => array()
        );
        $this->load->model('androidapp/app_reseller_model');
        $app_session_id_array = $this->app_reseller_model->get_app_session_id($user_id)->result_array();
        if (!empty($app_session_id_array) && $session_id != $app_session_id_array[0]['app_session_id']) {
            $response['response_code'] = ERROR_CODE_SESSION_EXPIRED;
            $response['message'] = "Sorry !! session id doesn't match !";
            echo json_encode($response);
            return;
        }
        $this->load->library('transaction_library');
        $transaction_list_array = $this->transaction_library->get_user_transaction_list(array($user_id), array(SERVICE_TYPE_ID_TOPUP_GP, SERVICE_TYPE_ID_TOPUP_ROBI, SERVICE_TYPE_ID_TOPUP_BANGLALINK, SERVICE_TYPE_ID_TOPUP_AIRTEL, SERVICE_TYPE_ID_TOPUP_TELETALK), array(), '', 0, 0, TRANSACTION_PAGE_DEFAULT_LIMIT, 0);
        $transaction_list = array();
        if (!empty($transaction_list_array)) {
            foreach ($transaction_list_array['transaction_list'] as $temp_transaction_info) {
                $transaction_info = array(
                    'cell_no' => $temp_transaction_info['cell_no'],
                    'amount' => $temp_transaction_info['amount'],
                    'title' => $temp_transaction_info['service_title'],
                    'status' => $temp_transaction_info['status']
                );
                $transaction_list[] = $transaction_info;
            }
            $response['transaction_list'] = $transaction_list;
            $response['response_code'] = RESPONSE_CODE_SUCCESS;
            $response['message'] = "Transaction list.";
        }
        echo json_encode($response);
    }

    public function get_payment_transaction_list() {
        $user_id = $this->input->post('user_id');
        $session_id = $this->input->post('session_id');
        $response = array(
            'response_code' => RESPONSE_CODE_SUCCESS
        );
        $this->load->model('androidapp/app_reseller_model');
        $app_session_id_array = $this->app_reseller_model->get_app_session_id($user_id)->result_array();
        if (!empty($app_session_id_array) && $session_id != $app_session_id_array[0]['app_session_id']) {
            $response['response_code'] = ERROR_CODE_SESSION_EXPIRED;
            $response['message'] = "Sorry !! session id doesn't match !";
            echo json_encode($response);
            return;
        }
        $where = array(
            'reference_id' => $user_id
        );
        $payment_list = array();
        $payment_type_id_list = array(
            PAYMENT_TYPE_ID_SEND_CREDIT,
            PAYMENT_TYPE_ID_RETURN_CREDIT,
            PAYMENT_TYPE_ID_RETURN_RECEIVE_CREDIT
        );
        $this->load->library('payment_library');
        $payment_info_send_list = $this->payment_library->get_payment_history($payment_type_id_list, array(), 0, 0, PAYMENT_LIST_DEAFULT_LIMIT, PAYMENT_LIST_DEAFULT_OFFSET, 'desc', $where);
        foreach ($payment_info_send_list['payment_list'] as $temp_payment_info) {
            $payment_info = array(
                'username' => $temp_payment_info['destination_username'],
                'amount' => $temp_payment_info['amount'],
                'date' => $temp_payment_info['created_on'],
                'description' => ''
            );
            //showing only yyyy-mm-dd
            $temp_date_array = explode(" ", $temp_payment_info['created_on']);
            if(count($temp_date_array) > 0)
            {
                $payment_info['date'] = $temp_date_array[0];
            }
            if($temp_payment_info['type_id'] == PAYMENT_TYPE_ID_SEND_CREDIT)
            {
                $payment_info['description'] = "Send";
            }
            else if($temp_payment_info['type_id'] == PAYMENT_TYPE_ID_RETURN_CREDIT || $temp_payment_info['type_id'] == PAYMENT_TYPE_ID_RETURN_RECEIVE_CREDIT)
            {
                $payment_info['description'] = "Return back";
            }
            $payment_list[] = $payment_info;
        }
        $where_receive = array(
            'user_id' => $user_id
        );
        $payment_info_receive_list = $this->payment_library->get_payment_history($payment_type_id_list, array(), 0, 0, PAYMENT_LIST_DEAFULT_LIMIT, PAYMENT_LIST_DEAFULT_OFFSET, 'desc', $where_receive);
        foreach ($payment_info_receive_list['payment_list'] as $temp_payment_info) {
            $payment_info = array(
                'username' => $temp_payment_info['source_username'],
                'amount' => $temp_payment_info['amount'],
                'date' => $temp_payment_info['created_on'],
                'description' => ''
            );
            //showing only yyyy-mm-dd
            $temp_date_array = explode(" ", $temp_payment_info['created_on']);
            if(count($temp_date_array) > 0)
            {
                $payment_info['date'] = $temp_date_array[0];
            }
            if($temp_payment_info['type_id'] == PAYMENT_TYPE_ID_SEND_CREDIT)
            {
                $payment_info['description'] = "Receive";
            }
            else if($temp_payment_info['type_id'] == PAYMENT_TYPE_ID_RETURN_CREDIT || $temp_payment_info['type_id'] == PAYMENT_TYPE_ID_RETURN_RECEIVE_CREDIT)
            {
                $payment_info['description'] = "Return to";
            }
            $payment_list[] = $payment_info;
        }
        $response['payment_list'] = $payment_list; 
        $response['response_code'] = RESPONSE_CODE_SUCCESS;
        $response['message'] = "";
        echo json_encode($response);
    }

}

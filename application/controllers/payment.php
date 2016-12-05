<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Payment extends Role_Controller {

    function __construct() {
        parent::__construct();
        $this->load->helper('url');
        $this->load->library('form_validation');
        $this->load->library('ion_auth');
        $this->load->library('payment_library');
        if (!$this->ion_auth->logged_in()) {
            redirect('auth/login', 'refresh');
        }
    }

    public function index() {
        
    }

    /*
     * This method will execute payment transaction from one user to another user
     * @param $child_id, child user id
     * @author nazmul hasan on 24th february 2016
     */

    public function create_payment($child_id = 0) {
        $parent_id = $this->session->userdata('user_id');
        $this->load->library('reseller_library');
        $successor_id_list = $this->reseller_library->get_successor_id_list($parent_id);
        if (!in_array($child_id, $successor_id_list)) {
            //you don't have permission to view details of this user
            $this->data['app'] = RESELLER_APP;
            $this->data['error_message'] = "Sorry !! You don't have permission to view details of this user.";
            $this->template->load(null, 'common/error_message', $this->data);
            return;
        }
        $response = array();
        if (file_get_contents("php://input") != null) {
            $postdata = file_get_contents("php://input");
            $requestInfo = json_decode($postdata);
            if (property_exists($requestInfo, "paymentInfo") != FALSE) {
                $paymentInfo = $requestInfo->paymentInfo;
                if (property_exists($paymentInfo, "amount")) {
                    $amount = $paymentInfo->amount;
                }
                if (property_exists($paymentInfo, "payment_type")) {
                    $payment_type_id = $paymentInfo->payment_type;
                }
                if (property_exists($paymentInfo, "description")) {
                    $description = $paymentInfo->description;
                }
                
                $payment_data = array(
                    'balance_in' => 0,
                    'balance_out' => 0
                );
                if (isset($description)) {
                    $payment_data['description'] = $description;
                }
                
                if ($payment_type_id == PAYMENT_TYPE_ID_SEND_CREDIT) 
                {
                    $payment_data['balance_in'] = $amount;
                } 
                else if ($payment_type_id == PAYMENT_TYPE_ID_RETURN_CREDIT) 
                {
                    //check if user has this amount available
                    $current_balance = $this->payment_library->get_user_current_balance($child_id);
                    if((float)$current_balance < (float)$amount)
                    {
                        $response['message'] = 'Insufficient balance to retrun from this user. His current balance is '.$current_balance;
                        echo json_encode($response);
                        return;
                    }
                    $payment_data['balance_out'] = $amount;
                }
                $payment_data['user_id'] = $child_id;
                $payment_data['reference_id'] = $parent_id;
                $payment_data['type_id'] = $payment_type_id;
                
                $this->load->library('utils');
                $transaction_id = $this->utils->get_transaction_id();
                $payment_data['transaction_id'] = $transaction_id;
                $this->load->library("security");
                $payment_data = $this->security->xss_clean($payment_data);
                if ($this->payment_model->transfer_user_payment($payment_data) !== FALSE) {
                    $response['message'] = 'Payment is updated successfully.';
                } else {
                    $response['message'] = 'Error while updating the payment. Please try later.';
                }
            }
            echo json_encode($response);
            return;
        }

        $payment_type_list = array(
            PAYMENT_TYPE_ID_SEND_CREDIT => 'Payment',
            PAYMENT_TYPE_ID_RETURN_CREDIT => 'Return'
        );
        $payment_type_id_list = array(
            PAYMENT_TYPE_ID_SEND_CREDIT,
            PAYMENT_TYPE_ID_RETURN_CREDIT,
            PAYMENT_TYPE_ID_RETURN_RECEIVE_CREDIT
        );
        $status_id_list = array(
            TRANSACTION_STATUS_ID_PENDING,
            TRANSACTION_STATUS_ID_SUCCESSFUL,
            TRANSACTION_STATUS_ID_FAILED,
            TRANSACTION_STATUS_ID_CANCELLED,
            TRANSACTION_STATUS_ID_PROCESSED);
        $offset = PAYMENT_LIST_DEAFULT_OFFSET;
        $limit = PAYMENT_LIST_CREATE_PAYMENT_DEAFULT_LIMIT;
        $where = array(
            'user_id' => $child_id
        );
        $payment_list_array = $this->payment_library->get_payment_history($payment_type_id_list, $status_id_list, 0, 0, $limit, $offset, 'desc', $where, array(), array());
        $payment_list = array();
        if (!empty($payment_list_array)) {
            $payment_list = $payment_list_array['payment_list'];
        }
        $this->data['payment_list'] = json_encode($payment_list);
        $this->data['payment_type_list'] = json_encode($payment_type_list);
        $this->data['user_id'] = $child_id;
        $this->data['app'] = PAYMENT_APP;
        $this->template->load(null, 'payment/create_payment', $this->data);
    }

    /**
     * this method return balance from child to parent
     * 
     * 
     *  */
    public function reseller_return_balance() {
        $user_id = $this->session->userdata('user_id');
        $this->load->library('reseller_library');
        $response = array();
        if (file_get_contents("php://input") != null) {
            $postdata = file_get_contents("php://input");
            $requestInfo = json_decode($postdata);
            if (property_exists($requestInfo, "paymentInfo") != FALSE) {
                $paymentInfo = $requestInfo->paymentInfo;

                if (property_exists($paymentInfo, "amount")) {
                    $amount = $paymentInfo->amount;
                }
                if (property_exists($paymentInfo, "description")) {
                    $description = $paymentInfo->description;
                }
                //revise this logic to get user current balance properly
                //
                //
                //
                if ($amount > $this->reseller_library->get_user_current_balance($user_id)) {
                    $response['message'] = 'Sorry! Insaficient Balance !';
                    echo json_encode($response);
                    return;
                };
                
                $parent_user_id = $this->reseller_library->get_parent_user_id($user_id);
                if ($parent_user_id == 0) {
                    $response['message'] = 'Error !Parent information not found  !';
                    echo json_encode($response);
                    return;
                }
                $payment_info = array(
                    'balance_in' => 0,
                    'balance_out' => $amount
                );
                $payment_info['user_id'] = $user_id;
                $payment_info['reference_id'] = $parent_user_id;
                $payment_info['type_id'] = PAYMENT_TYPE_ID_RETURN_RECEIVE_CREDIT;                
                if (isset($description)) {
                    $payment_info['description'] = $description;
                }
                $this->load->library('utils');
                $transaction_id = $this->utils->get_transaction_id();
                $payment_info['transaction_id'] = $transaction_id;
                $this->load->library("security");
                $p_info = $this->security->xss_clean($payment_info);
                $this->load->model('payment_model');
                if ($this->payment_model->transfer_user_payment($p_info) !== FALSE) {
                    $response['message'] = 'Return balance successfully.';
                } else {
                    $response['message'] = 'Error while returning the balance. Please try later.';
                }
            }
            echo json_encode($response);
            return;
        }
        $this->data['app'] = PAYMENT_APP;
        $this->template->load(null, 'reseller/return_balance', $this->data);
    }

}

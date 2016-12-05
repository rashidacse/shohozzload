<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
* Name:  Ion Auth Lang - English
*
* Author: Ben Edmunds
* 		  ben.edmunds@gmail.com
*         @benedmunds
*
* Location: http://github.com/benedmunds/ion_auth/
*
* Created:  03.14.2010
*
* Description:  English language file for Ion Auth messages and errors
*
*/

// Account Creation
$lang['account_creation_successful'] 	  	 = 'Account Successfully Created';
$lang['account_creation_unsuccessful'] 	 	 = 'Unable to Create Account';
$lang['account_creation_duplicate_email'] 	 = 'Email Already Used or Invalid';
$lang['account_creation_duplicate_username'] = 'Username Already Used or Invalid';

// Password
$lang['password_change_successful'] 	 	 = 'Password Successfully Changed';
$lang['password_change_unsuccessful'] 	  	 = 'Unable to Change Password';
$lang['forgot_password_successful'] 	 	 = 'Password Reset Email Sent';
$lang['forgot_password_unsuccessful'] 	 	 = 'Unable to Reset Password';

// Activation
$lang['activate_successful'] 		  	     = 'Account Activated';
$lang['activate_unsuccessful'] 		 	     = 'Unable to Activate Account';
$lang['deactivate_successful'] 		  	     = 'Account De-Activated';
$lang['deactivate_unsuccessful'] 	  	     = 'Unable to De-Activate Account';
$lang['activation_email_successful'] 	  	 = 'Activation Email Sent';
$lang['activation_email_unsuccessful']   	 = 'Unable to Send Activation Email';

// Login / Logout
$lang['login_successful'] 		  	         = 'Logged In Successfully';
$lang['login_unsuccessful'] 		  	     = 'Incorrect Login';
$lang['login_unsuccessful_not_active'] 		 = 'Account is inactive';
$lang['login_unsuccessful_user_suspended'] 		 = 'Account is suspended';
$lang['login_unsuccessful_user_deactivated'] 		 = 'Account is deactivated';
$lang['login_unsuccessful_user_blocked'] 		 = 'Account is blocked';
$lang['login_timeout']                       = 'Temporarily Locked Out.  Try again later.';
$lang['logout_successful'] 		 	         = 'Logged Out Successfully';

// Account Changes
$lang['update_successful'] 		 	         = 'Account Information Successfully Updated';
$lang['update_unsuccessful'] 		 	     = 'Unable to Update Account Information';
$lang['delete_successful']               = 'User Deleted';
$lang['delete_unsuccessful']           = 'Unable to Delete User';

// Groups
$lang['group_creation_successful']  = 'Group created Successfully';
$lang['group_already_exists']       = 'Group name already taken';
$lang['group_update_successful']    = 'Group details updated';
$lang['group_delete_successful']    = 'Group deleted';
$lang['group_delete_unsuccessful'] 	= 'Unable to delete group';
$lang['group_name_required'] 		= 'Group name is a required field';

// Email Subjects
$lang['email_forgotten_password_subject']    = 'Forgotten Password Verification';
$lang['email_new_password_subject']          = 'New Password';
$lang['email_activation_subject']            = 'Account Activation';

//Packages
$lang['create_package_successful']                  = 'Package is created successfully.';
$lang['create_package_unsuccessful']                = 'Error while creating a new package.';
$lang['update_package_successful']                  = 'Package is updated successfully.';
$lang['update_package_unsuccessful']                = 'Error while creating a package info.';
$lang['delete_package_successful']                  = 'Package is deleted successfully.';
$lang['delete_package_unsuccessful']                = 'Error while deleting the package.';


// Transaction
$lang['transaction_successful']                     = 'Your request is processing. Thank you.';
$lang['transaction_unsuccessful']                   = 'Sorry! Unable to process your request. Please try again later.';
$lang['error_webservice_unavailable']               = 'Server to process your request is unavailable. Please try again later.';
$lang['error_insufficient_balance']                 = 'Sorry! Insufficient Balance !';
$lang['error_no_transaction_id']                    = 'Sorry!! There was an error while processing your transaction.';
$lang['error_no_result_event']                      = 'Sorry!!! There was an error while processing your transaction.';
$lang['error_message_length_limit_cross']           = 'Message length exceeds allowed size.';
$lang['error_message_charge_empty']                 = 'Message charge is not set yet.';

$lang['error_code_5001']                            = '5001';
$lang['error_code_5002']                            = '5002';
$lang['error_code_5003']                            = '5003';
$lang['error_code_5004']                            = '5004';
$lang['error_code_5005']                            = '5005';
$lang['error_code_5006']                            = '5006';
$lang['error_code_5007']                            = '5007';
$lang['error_code_5008']                            = '5008';
$lang['error_code_5009']                            = '5009';
$lang['error_code_5010']                            = '5010';
$lang['error_code_5011']                            = '5011';
$lang['error_code_5012']                            = '5012';

$lang['error_user_rate_configuration']              = 'Please cofigure user rate.';
$lang['null_pointer_exception_while_processing_the_transaction'] = 'Null Pointer Exception While Processing the Transaction';

//Webservice Error messages

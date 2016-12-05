<script>

    $(function() {
        var error_message = '<?php
if (isset($error_message)) {
    echo $error_message;
}
?>';
        if (error_message != "") {
            $("#content").html(error_message);
            $('#common_modal').modal('show');
        }
    });

    function  getTopupOperatorId(number) {
        if (number.indexOf("88") == 0)
        {
            number = number.substr(2, number.length);
        }
        var operatorCode = number.substr(0, 3);
        if (operatorCode == '<?php echo OPERATOR_CODE_GP ?>') {
            return '<?php echo SERVICE_TYPE_ID_TOPUP_GP; ?>';
        } else if (operatorCode == '<?php echo OPERATOR_CODE_ROBI ?>') {
            return '<?php echo SERVICE_TYPE_ID_TOPUP_ROBI; ?>';
        } else if (operatorCode == '<?php echo OPERATOR_CODE_AIRTEL ?>') {
            return '<?php echo SERVICE_TYPE_ID_TOPUP_AIRTEL; ?>';
        } else if (operatorCode == '<?php echo OPERATOR_CODE_TELETALK ?>') {
            return '<?php echo SERVICE_TYPE_ID_TOPUP_TELETALK; ?>';
        } else if (operatorCode == '<?php echo OPERATOR_CODE_BANGLALINK ?>') {
            return '<?php echo SERVICE_TYPE_ID_TOPUP_BANGLALINK; ?>';
        }
        else
        {
            return "";
        }
    }
    function add_topup(topUpInfo, topupType) {
        if (typeof topUpInfo.number == "undefined" || topUpInfo.number.length == 0) {
            $("#content").html("Please give a TopUP Number");
            $('#common_modal').modal('show');
            return;
        }
        if (number_validation(topUpInfo.number) == false) {
            $("#content").html("Please give a valid TopUP Number");
            $('#common_modal').modal('show');
            return;
        }
        if (typeof topUpInfo.amount == "undefined" || topUpInfo.amount.length == 0) {
            $("#content").html("Please give an amount ");
            $('#common_modal').modal('show');
            return;
        }
        topUpInfo.topupType = topupType;
        topUpInfo.topupOperatorId = getTopupOperatorId(topUpInfo.number);
        if (topUpInfo.topupOperatorId == "")
        {
            $("#content").html("Invalid operator. Please assign a valid number.");
            $('#common_modal').modal('show');
            return;
        }
        if (topUpInfo.topupOperatorId == '<?php echo SERVICE_TYPE_ID_TOPUP_GP; ?>' && topUpInfo.topupType == '<?php echo OPERATOR_TYPE_ID_POSTPAID; ?>') {
            if (topUpInfo.amount < +('<?php echo TOPUP_POSTPAID_GP_MINIMUM_CASH_IN_AMOUNT ?>')) {
                $("#content").html("Please give minimum amount TK. " + '<?php echo TOPUP_POSTPAID_GP_MINIMUM_CASH_IN_AMOUNT ?>' + " for GP Postpaid");
                $('#common_modal').modal('show');
                return;
            }
        } else if (topUpInfo.amount < +('<?php echo TOPUP_MINIMUM_CASH_IN_AMOUNT ?>')) {
            $("#content").html("Please give a minimum amount TK. " + '<?php echo TOPUP_MINIMUM_CASH_IN_AMOUNT ?>');
            $('#common_modal').modal('show');
            return;
        }
        if (topUpInfo.amount > +('<?php echo TOPUP_MAXIMUM_CASH_IN_AMOUNT; ?>')) {
            $("#content").html("Please give a maximum amount TK. " + '<?php echo TOPUP_MAXIMUM_CASH_IN_AMOUNT; ?>');
            $('#common_modal').modal('show');
            return;
        }
        $("#confirmation_content").html("Number : " + topUpInfo.number + " and Amount : " + topUpInfo.amount);
        $('#common_confirmation_modal').modal('show');
        $('#modal_confirm_click_id').on("click", function() {
            angular.element($('#top_up_id')).scope().addTopUp(function(data) {
                $("#content").html(data.message);
                $('#common_modal').modal('show');
                $('#modal_ok_click_id').on("click", function() {
                    window.location = '<?php echo base_url() ?>transaction/topup';
                });
            });
        });
        $('#modal_cancel_click_id').on("click", function() {
            window.location = '<?php echo base_url() ?>transaction/topup';
        });
    }



    function add_multipule_topup(transctionDataList) {
        if (transctionDataList.length <= 0) {
            $("#content").html("Please add a topUp info/ XLSX file!  ");
            $('#common_modal').modal('show');
            return;
        } else {
            for (var i = 0; i < transctionDataList.length; i++) {
                var index = i + +1;
                var transationInfo = transctionDataList[i];
                if (typeof transationInfo.number == "undefined" || transationInfo.number.length == 0) {
                    $("#content").html("Please give a TopUP Number at transction index " + index);
                    $('#common_modal').modal('show');
                    return;
                }
                if (typeof transationInfo.amount == "undefined" || transationInfo.amount.length == 0) {
                    $("#content").html("Please give an amount at transction index " + index);
                    $('#common_modal').modal('show');
                    return;
                }
                if (typeof transationInfo.topupType == "undefined" || transationInfo.topupType.length == 0) {
                    $("#content").html("Please Select a Operator Type at transction index " + index)
                    $('#common_modal').modal('show');
                    return;
                }
                if (typeof transationInfo.topupOperatorId == "undefined" || transationInfo.topupOperatorId.length == 0) {
                    $("#content").html("Please Select an Operator at transction index " + index)
                    $('#common_modal').modal('show');
                    return;
                }
            }
        }

        $("#confirmation_content").html("Are you sure to submit topups?");
        $('#common_confirmation_modal').modal('show');
        $('#modal_confirm_click_id').on("click", function() {
            angular.element($('#multipule_top_up_id')).scope().multipuleTopup(function(data) {
                $("#content").html(data.message);
                $('#common_modal').modal('show');
                $('#modal_ok_click_id').on("click", function() {
                    //$(".loader").show();
                    window.location = '<?php echo base_url() ?>transaction/topup';
                });
            });
        });
        $('#modal_cancel_click_id').on("click", function() {
            window.location = '<?php echo base_url() ?>transaction/topup';
        });
    }
    $(function() {
        setInterval(callFunction, <?php echo TRANSACTION_LIST_CALLING_INTERVER; ?>);
    });
    function callFunction() {
        var serviceIdList = [<?php echo SERVICE_TYPE_ID_TOPUP_GP; ?>,<?php echo SERVICE_TYPE_ID_TOPUP_ROBI; ?>, <?php echo SERVICE_TYPE_ID_TOPUP_BANGLALINK; ?>, <?php echo SERVICE_TYPE_ID_TOPUP_AIRTEL; ?>, <?php echo SERVICE_TYPE_ID_TOPUP_TELETALK; ?>];
        angular.element($('#transaction_list_id')).scope().getAjaxTransactionList(serviceIdList);
    }
    function topup_confirmation(topUpInfo, topupType) {
        if (typeof topUpInfo.number == "undefined" || topUpInfo.number.length == 0) {
            $("#content").html("Please give a TopUP Number");
            $('#common_modal').modal('show');
            return;
        }
        if (number_validation(topUpInfo.number) == false) {
            $("#content").html("Please give a valid TopUP Number");
            $('#common_modal').modal('show');
            return;
        }
        if (typeof topUpInfo.amount == "undefined" || topUpInfo.amount.length == 0) {
            $("#content").html("Please give an amount ");
            $('#common_modal').modal('show');
            return;
        }
        topUpInfo.topupType = topupType;
        topUpInfo.topupOperatorId = getTopupOperatorId(topUpInfo.number);
        var serviceInfo = JSON.parse('<?php echo $service_id_user_service_info_map; ?>');
        var charge = serviceInfo[topUpInfo.topupOperatorId]['charge'];
        var rate = serviceInfo[topUpInfo.topupOperatorId]['rate'];
        topUpInfo.cost = parseFloat(topUpInfo.amount) + parseFloat(topUpInfo.amount/rate*charge);
        if (topUpInfo.topupOperatorId == "")
        {
            $("#content").html("Invalid operator. Please assign a valid number.");
            $('#common_modal').modal('show');
            return;
        }
        if (topUpInfo.topupOperatorId == '<?php echo SERVICE_TYPE_ID_TOPUP_GP; ?>' && topUpInfo.topupType == '<?php echo OPERATOR_TYPE_ID_POSTPAID; ?>') {
            if (topUpInfo.amount < +('<?php echo TOPUP_POSTPAID_GP_MINIMUM_CASH_IN_AMOUNT ?>')) {
                $("#content").html("Please give minimum amount TK. " + '<?php echo TOPUP_POSTPAID_GP_MINIMUM_CASH_IN_AMOUNT ?>' + " for GP Postpaid");
                $('#common_modal').modal('show');
                return;
            }
        } else if (topUpInfo.amount < +('<?php echo TOPUP_MINIMUM_CASH_IN_AMOUNT ?>')) {
            $("#content").html("Please give a minimum amount TK. " + '<?php echo TOPUP_MINIMUM_CASH_IN_AMOUNT ?>');
            $('#common_modal').modal('show');
            return;
        }
        if (topUpInfo.amount > +('<?php echo TOPUP_MAXIMUM_CASH_IN_AMOUNT; ?>')) {
            $("#content").html("Please give a maximum amount TK. " + '<?php echo TOPUP_MAXIMUM_CASH_IN_AMOUNT; ?>');
            $('#common_modal').modal('show');
            return;
        }
        $('#div_topup_info').hide();
        $('#div_topup_confimation').show();
    }
    function topup_section_confirm_button() {
        angular.element($('#top_up_id')).scope().addTopUp(function(data) {
            $("#content").html(data.message);
            $('#common_modal').modal('show');
            $('#modal_ok_click_id').on("click", function() {
                window.location = '<?php echo base_url() ?>transaction/topup';
            });
        });
        //$('#div_topup_confimation').hide();
        //$('#div_topup_info').show();

    }
    function topup_section_cancel_button() {
        $('#div_topup_confimation').hide();
        $('#div_topup_info').show();

    }

</script>
<div class="loader"></div>
<div class="ezttle"><span class="text">Topup</span></div>
<div class="mypage"  ng-controller="transctionController">
    <div class="row form-group" style="margin-top:5px;" id="div_topup_info">
        <div class="col-md-12 fleft">	
            <input name="elctkn" value="30dfe1ad62facbf8e5b1ec2e46f9f084" style="display:none;" type="hidden">
            <table style="width:100%;">
                <tbody><tr>
                        <td style="width: 50%; vertical-align: top; padding-right: 20px;">
                            <div class="row col-md-12" id="box_content_2" class="box-content" style="padding-top: 10px;">
                                <form>
                                    <div class ="row">
                                        <div class="col-md-12">  </div>
                                    </div>
                                    <div class="row form-group">
                                        <div class="col-md-5">
                                            <label for="number" class="col-md-6 control-label requiredField label_custom">
                                                Number
                                            </label>
                                        </div>
                                        <div class="col-md-7">
                                            <label for="number" class="control-label requiredField label_custom">
                                                <input type="text" name="number" id="number" ng-model="topUpInfo.number" class="form-control" placeholder='eg: 0171XXXXXXX'> 
                                            </label>
                                        </div>
                                    </div>
                                    <div class="row form-group">
                                        <div class="col-md-5">
                                            <label for="amount" class="col-md-6 control-label requiredField label_custom">
                                                Amount
                                            </label>
                                        </div>
                                        <div class="col-md-7">
                                            <label for="amount" class="control-label requiredField label_custom">
                                                <input type="text" name="amount" id="amount" ng-model="topUpInfo.amount" class="form-control"  placeholder='eg: 100'>  
                                            </label>
                                        </div>
                                    </div>
                                    <div class="row form-group">
                                        <div class="col-md-5">
                                            <label for="type" class="col-md-6 control-label requiredField label_custom">
                                                Type
                                            </label>
                                        </div>
                                        <div class="col-md-7">
                                            <select  class="form-control control-label requiredField" ng-init="setTopUpTypeList(<?php echo htmlspecialchars(json_encode($topup_type_list)); ?>)"
                                                     ng-options="topupType.title for topupType in topUpTypes.topupTypeList track by topupType.id"
                                                     ng-model="topUpTypes.selectedOption"></select>
                                        </div>
                                    </div>
                                    <div class="row form-group">
                                        <div for="submit_update_api" class="col-md-6 control-label requiredField label_custom"> </div>
                                        <div class ="col-md-6">
                                            <!--<button id="top_up_id" class="button-custom pull-right"  onclick="add_topup(angular.element(this).scope().topUpInfo, angular.element(this).scope().topUpTypes.selectedOption.id)">Send</button>-->
                                            <button id="top_up_id" class="button-custom pull-right"  onclick="topup_confirmation(angular.element(this).scope().topUpInfo, angular.element(this).scope().topUpTypes.selectedOption.id)">Send</button>
                                        </div> 
                                    </div>

                                </form>
                            </div>
                            <div class="row form-group"></div>
                            <div class="row form-group">
                                <div class="col-md-6">
                                    <a class="button-custom download-sample-button-shape" href="<?php echo base_url() . TOPUP_FILE_DOWNLOAD_DIRECTORY . SAMPLE_TOPUP_XLSX_FILE_NAME; ?>">Download sample</a>
                                </div>
                                <div class="col-md-6">
                                    <a href="<?php echo base_url() . "files/topup_readme_file_dowload" ?>"><label class="cursor_pointer">Help</label></a>
                                </div>
                            </div>
                            <div class="row">
                                <?php echo form_open_multipart('transaction/topup', array('name' => 'file_upload')); ?>
                                <div class="form-group">
                                    <label  class="col-md-2" for="fileupload">Upload:</label>
                                    <input class="col-md-4" id="fileupload" type="file" name="userfile">
                                    <div class="col-md-3"></div>
                                    <input id="submit_btn"  name="submit_btn" value="Upload" type="submit" class="col-md-2 button-custom"/>
                                </div>
                                <?php echo form_close(); ?>
                            </div>
                            <div class="row form-group">
                                <div class="col-md-6">
                                    <p class="help-block">Select ".XLSX" files only.</p>   
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <table class="table10 form-group" cellspacing="0">
                                        <thead>
                                            <tr>	
                                                <th>Serial</th>
                                                <th>Mobile Number</th>
                                                <th>Amount</th>
                                                <th>Operator </th>
                                                <th>type </th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <?php if (isset($transactions_data)) { ?>
                                            <div ng-init="setTransctionDataList(<?php echo htmlspecialchars(json_encode($transactions_data)); ?>)"></div>
                                        <?php } ?>
                                        <tbody> 
                                            <tr ng-repeat="(key, transactionInfo) in transactionDataList">
                                                <td>
                                                    {{key + +1}}
                                                </td>
                                                <td>{{transactionInfo.number}}</td>
                                                <td>{{transactionInfo.amount}}</td>
                                                <td ng-if="transactionInfo.topupOperatorId == '<?php echo SERVICE_TYPE_ID_TOPUP_GP; ?>'"> GP </td>
                                                <td ng-if="transactionInfo.topupOperatorId == '<?php echo SERVICE_TYPE_ID_TOPUP_ROBI; ?>'"> Robi </td>
                                                <td ng-if="transactionInfo.topupOperatorId == '<?php echo SERVICE_TYPE_ID_TOPUP_BANGLALINK; ?>'"> Banglalink </td>
                                                <td ng-if="transactionInfo.topupOperatorId == '<?php echo SERVICE_TYPE_ID_TOPUP_AIRTEL; ?>'"> Airtel </td>
                                                <td ng-if="transactionInfo.topupOperatorId == '<?php echo SERVICE_TYPE_ID_TOPUP_TELETALK; ?>'"> TeleTalk </td>
                                                <td ng-if="transactionInfo.topupType == '<?php echo OPERATOR_TYPE_ID_PREPAID ?>'">Prepaid </td>
                                                <td ng-if="transactionInfo.topupType == '<?php echo OPERATOR_TYPE_ID_POSTPAID ?>'">Postpaid </td>
                                                <td style="text-align: center; cursor: pointer;" ng-click="deleteTransction(transactionInfo)"><div class="glyphicon glyphicon-trash"></div></td>
                                            </tr>
                                        </tbody>

                                    </table>

                                    <div class="row">
                                        <div class="col-md-offset-10 col-md-2">
                                            <button id="multipule_top_up_id" class="button-custom pull-right" onclick="add_multipule_topup(angular.element(this).scope().transactionDataList)">Submit</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </td>

                        <td>
                        </td>
                        <td  id="transaction_list_id" style="width:50%;vertical-align:top;padding-right:15px;"  ng-init="setTransctionList(<?php echo htmlspecialchars(json_encode($transaction_list)); ?>)">
                            <p class="help-block">Last 10 Requests</p>
                            <div style="margin:0px;padding:0px;background:#fff;">
                                <table class="table10" cellspacing="0">
                                    <thead>
                                        <tr>	
                                            <th>Number</th>
                                            <th>Amount</th>
                                            <th>Cost</th>
                                            <th>Type</th>
                                            <th>Status</th>
                                            <th>Date</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr ng-repeat="transactionInfo in transctionList">	
                                            <td>{{transactionInfo.cell_no}}</td>
                                            <td>{{transactionInfo.amount}}</td>
                                            <td>{{transactionInfo.cost}}</td>
                                            <td>{{transactionInfo.type}}</td>

                                            <td ng-if="transactionInfo.status_id == <?php echo TRANSACTION_STATUS_ID_PENDING; ?>" style="color: violet; font-weight: bold">{{transactionInfo.status}}</td>
                                            <td ng-if="transactionInfo.status_id == <?php echo TRANSACTION_STATUS_ID_SUCCESSFUL; ?>" style="color: green; font-weight: bold">{{transactionInfo.status}}</td>
                                            <td ng-if="transactionInfo.status_id == <?php echo TRANSACTION_STATUS_ID_FAILED; ?>" style="color: red; font-weight: bold">{{transactionInfo.status}}</td>
                                            <td ng-if="transactionInfo.status_id == <?php echo TRANSACTION_STATUS_ID_CANCELLED; ?>" style="color: brown; font-weight: bold">{{transactionInfo.status}}</td>
                                            <td ng-if="transactionInfo.status_id == <?php echo TRANSACTION_STATUS_ID_PROCESSED; ?>" style="color: blue; font-weight: bold">{{transactionInfo.status}}</td>

                                            <td>{{transactionInfo.created_on}}</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div></td>
                    </tr>
                </tbody></table>

        </div> 
    </div>
    <div class="row  display-hidden" id="div_topup_confimation">
        <div class="col-sm-4 col-md-4">
            <div class="row form-group">
                <div class="col-sm-12">
                    <h4 class="heading_color">Sending Topup... Are you sure?</h4>
                    <hr>
                </div>
            </div>
            <div class="row form-group">
                <div class="col-md-3">
                    <label for="number" class="col-md-12 control-label requiredField label_custom">
                        Number:
                    </label>
                </div>
                <div class="col-md-6">
                    <label for="number" class="col-md-12 control-label requiredField label_custom green_color">
                        {{topUpInfo.number}}
                    </label>
                </div>
            </div>
            <div class="row form-group">
                <div class="col-md-3">
                    <label for="type" class="col-md-12 control-label requiredField label_custom ">
                        Type:
                    </label>
                </div>
                <div class="col-md-6">
                    <label for="type" class="col-md-12 control-label requiredField label_custom green_color">
                        <div ng-if="topUpInfo.topupType == <?php echo TRANSACTION_TYPE_ID_PREPAID;?>">Prepaid</div>
                        <div ng-if="topUpInfo.topupType == <?php echo TRANSACTION_TYPE_ID_POSTPAID;?>">Postpaid</div>
                    </label>
                </div>
            </div>
            <div class="row form-group">
                <div class="col-md-3">
                    <label for="amount" class="col-md-12 control-label requiredField label_custom">
                        Amount:
                    </label>
                </div>
                <div class="col-md-6">
                    <label for="amount" class="col-md-12 control-label requiredField label_custom green_color">
                        {{topUpInfo.amount}}
                    </label>
                </div>
            </div>
            <div class="row form-group">
                <div class="col-md-3">
                    <label for="cost" class="col-md-12 control-label requiredField label_custom">
                        Cost:
                    </label>
                </div>
                <div class="col-md-6">
                    <label for="cost" class="col-md-12 control-label requiredField label_custom red_color">
                        {{topUpInfo.cost}}
                    </label>
                </div>
            </div>
            <div class="row form-group">
                <div class="col-md-5">
                    <label for="amount" class="col-md-12 control-label requiredField label_custom">
                        Enter Pin:
                    </label>
                </div>
            </div>
            <div class="row form-group">
                <div class="col-md-5">
                    <label for="pin" class="control-label requiredField label_custom">
                        <input ng-model="topUpInfo.code" type="password" name="pin" id="amount" class="form-control"  placeholder="">  
                    </label>
                </div>
            </div>
            <div class="row form-group">
                <div class="col-sm-12">
                    <hr>
                    <button id="top_up_cancel_btn" class="button-danger pull-right"  onclick="topup_section_cancel_button()">Cancel</button>
                    <button id="top_up_confirm_btn" class="button-custom pull-right margin-right-10px"  onclick="topup_section_confirm_button()">Confirm</button>
                </div>
            </div>
        </div>
    </div>

</div>


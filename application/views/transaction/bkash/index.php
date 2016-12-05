<script type="text/javascript">
    function send_code()
    {
        angular.element($('#bkash_service_id')).scope().sendTransactionCode(function(data) {
            $("#content").html(data);
            $('#common_modal').modal('show');
        });
    }
    function numberValidation(phoneNumber) {
        var regexp = /^((^\880|0)[1][1|5|6|7|8|9])[0-9]{8}$/;
        var validPhoneNumber = phoneNumber.match(regexp);
        if (validPhoneNumber) {
            return true;
        }
        return false;
    }
    function bkash(bkashInfo, bkashServiceId) {
        if (typeof bkashInfo.number == "undefined" || bkashInfo.number.length == 0) {
            $("#content").html("Please give a bkash Number");
            $('#common_modal').modal('show');
            return;
        }
        if (numberValidation(bkashInfo.number) == false) {
            $("#content").html("Please give a valid bKash Number!");
            $('#common_modal').modal('show');
            return;
        }
        if (typeof bkashInfo.amount == "undefined" || bkashInfo.amount.length == 0) {
            $("#content").html("Please give an amount ");
            $('#common_modal').modal('show');
            return;
        }
        if (bkashInfo.amount < +('<?php echo BKASH_MINIMUM_CASH_IN_AMOUNT ?>')) {
            $("#content").html("Please give a minimum amount TK. " + '<?php echo BKASH_MINIMUM_CASH_IN_AMOUNT ?>');
            $('#common_modal').modal('show');
            return;
        }
        if (bkashInfo.amount > +('<?php echo BKASH_MAXIMUM_CASH_IN_AMOUNT; ?>')) {
            $("#content").html("Please give a maximum amount TK. " + '<?php echo BKASH_MAXIMUM_CASH_IN_AMOUNT; ?>');
            $('#common_modal').modal('show');
            return;
        }
        if (bkashServiceId != <?php echo SERVICE_TYPE_ID_BKASH_CASHIN; ?> && bkashServiceId != <?php echo SERVICE_TYPE_ID_BKASH_CASHOUT; ?>) 
        {
            $("#content").html("Please select either Cash In or Cash Out.");
            $('#common_modal').modal('show');
            return;
        } 
        bkashInfo.bkashServiceId = bkashServiceId;
        angular.element($('#bkash_service_id')).scope().bkash(function(data) {
            $("#content").html(data.message);
            $('#common_modal').modal('show');
            $('#modal_ok_click_id').on("click", function() {
                window.location = '<?php echo base_url() ?>transaction/bkash';
            });
        });
    }
    $(function() {
        setInterval(callFunction, <?php echo TRANSACTION_LIST_CALLING_INTERVER; ?>);
    });
    function callFunction() {
        var serviceIdList = [<?php echo SERVICE_TYPE_ID_BKASH_CASHIN; ?>];
        angular.element($('#transaction_list_id')).scope().getAjaxTransactionList(serviceIdList);
    }
    function bkash_confirmation(bkashInfo, bkashServiceId) 
    {
        if (typeof bkashInfo.number == "undefined" || bkashInfo.number.length == 0) {
            $("#content").html("Please give a bkash Number");
            $('#common_modal').modal('show');
            return;
        }
        if (numberValidation(bkashInfo.number) == false) {
            $("#content").html("Please give a valid bKash Number!");
            $('#common_modal').modal('show');
            return;
        }
        if (typeof bkashInfo.amount == "undefined" || bkashInfo.amount.length == 0) {
            $("#content").html("Please give an amount ");
            $('#common_modal').modal('show');
            return;
        }
        if (bkashInfo.amount < +('<?php echo BKASH_MINIMUM_CASH_IN_AMOUNT ?>')) {
            $("#content").html("Please give a minimum amount TK. " + '<?php echo BKASH_MINIMUM_CASH_IN_AMOUNT ?>');
            $('#common_modal').modal('show');
            return;
        }
        if (bkashInfo.amount > +('<?php echo BKASH_MAXIMUM_CASH_IN_AMOUNT; ?>')) {
            $("#content").html("Please give a maximum amount TK. " + '<?php echo BKASH_MAXIMUM_CASH_IN_AMOUNT; ?>');
            $('#common_modal').modal('show');
            return;
        }
        if (bkashServiceId != <?php echo SERVICE_TYPE_ID_BKASH_CASHIN; ?> && bkashServiceId != <?php echo SERVICE_TYPE_ID_BKASH_CASHOUT; ?>) 
        {
            $("#content").html("Please select either Cash In or Cash Out.");
            $('#common_modal').modal('show');
            return;
        }
        
        bkashInfo.bkashServiceId = bkashServiceId;
        var serviceInfo = JSON.parse('<?php echo $user_service_info_list; ?>');
        var charge = serviceInfo[bkashInfo.bkashServiceId]['charge'];
        var rate = serviceInfo[bkashInfo.bkashServiceId]['rate'];
        bkashInfo.cost = parseFloat(bkashInfo.amount) + parseFloat(bkashInfo.amount/rate*charge);    
        
        $('#label_cost').text(bkashInfo.cost);
        if(bkashServiceId == '<?php echo SERVICE_TYPE_ID_BKASH_CASHIN;?>')
        {
            $('#label_transaction_type').text("Cash In");
        }
        else if(bkashServiceId == '<?php echo SERVICE_TYPE_ID_BKASH_CASHOUT;?>')
        {
            $('#label_transaction_type').text("Cash Out");
        }
        
        $('#div_bkash_info').hide();
        $('#div_bkash_confimation').show();
    }
    function bkash_section_confirm_button() {
        angular.element($('#bkash_service_id')).scope().bkash(function(data) {
            $("#content").html(data.message);
            $('#common_modal').modal('show');
            $('#modal_ok_click_id').on("click", function() {
                window.location = '<?php echo base_url() ?>transaction/bkash';
            });
        });
    }
    function bkash_section_cancel_button() {
        $('#div_bkash_confimation').hide();
        $('#div_bkash_info').show();

    }
</script>

<div class="loader"></div>
<div class="ezttle"><span class="text">BKash</span></div>
<div class="mypage" ng-controller="transctionController">
    <div class="row" style="margin-top:5px;" id="div_bkash_info">
        <div class="col-md-12 fleft" ng-init="setBkashTransaction(<?php echo htmlspecialchars(json_encode($transaction_info)); ?>, '<?php echo htmlspecialchars(json_encode($bkash_service_list)) ?>')">	
            <input name="elctkn" value="30dfe1ad62facbf8e5b1ec2e46f9f084" style="display:none;" type="hidden">
            <table style="width:100%;">
                <tbody><tr>
                        <td style="width:50%;vertical-align:top;padding-right:20px;">
                <ng-form>
                    <div class="row col-md-12" id="box_content_2" class="box-content" style="padding-top: 10px;">
                        <div class ="row">
                            <div class="col-md-12"> </div>
                        </div>
                        <div class="form-group">
                            <label for="number" class="col-md-6 control-label requiredField">
                                Number
                            </label>
                            <label for="number" class="col-md-6 control-label requiredField">
                                <input type="text" name="number" ng-model="bkashInfo.number" class="form-control" placeholder='eg: 0171XXXXXXX'>              
                            </label>
                        </div>
                        <div class="form-group">
                            <label for="amount" class="col-md-6 control-label requiredField">
                                Amount
                            </label>
                            <label for="amount" class="col-md-6 control-label requiredField">
                                <input type="text" name="amount" ng-model="bkashInfo.amount" class="form-control"  placeholder='eg: 100'>  
                            </label>
                        </div>
                        <div class="form-group">
                            <label for="type" class="col-md-6 control-label requiredField">
                                Type
                            </label>
                            <label for="type" class="col-md-6 control-label requiredField">
                                <select  class="form-control control-label requiredField"
                                    ng-options="bkashService.title for bkashService in bkashServices.bkashServiceList track by bkashService.id"
                                    ng-model="bkashServices.selectedOption"></select>
                            </label>
                        </div>
                        <?php if ($sms_or_email_verification) { ?>
                            <div class="form-group">
                                <label for="code" class="col-md-6 control-label requiredField">

                                </label>
                                <label for="code" class="col-md-6 control-label requiredField">
                                    <a href="#" onclick="send_code()">Send Code</a>
                                </label>
                            </div>
                        <?php } ?>
                        <div class="form-group">
                            <label for="submit_update_api" class="col-md-6 control-label requiredField">

                            </label>
                            <div class ="col-md-3 pull-right">
                                <!--<button id="bkash_service_id" class="form-control button"  onclick="bkash(angular.element(this).scope().bkashInfo, angular.element(this).scope().bkashServices.selectedOption.id)">Send</button>-->
                                <button id="bkash_service_id" class="form-control button"  onclick="bkash_confirmation(angular.element(this).scope().bkashInfo, angular.element(this).scope().bkashServices.selectedOption.id)">Send</button>
                            </div> 
                        </div>
                    </div>
                </ng-form>
                </td>
                <td>
                </td><td id="transaction_list_id" style="width:50%;vertical-align:top;padding-right:15px;" ng-init="setTransctionList(<?php echo htmlspecialchars(json_encode($transaction_list)); ?>)">
                    <p class="help-block">Last 10 Requests</p>
                    <div style="margin:0px;padding:0px;background:#fff;">
                        <table class="table10" cellspacing="0">
                            <thead>
                                <tr>	
                                    <th>Sender</th>
                                    <th>Number</th>
                                    <th>Type</th>
                                    <th>Amount</th>
                                    <th>Cost</th>
                                    <th>Status</th>
                                    <th>Date</th>
                                    <!--<th>Edit</th>-->
                                </tr>
                            </thead>
                            <tbody>
                                <tr ng-repeat="transactionInfo in transctionList">	
                                    <td>{{transactionInfo.sender_cell_no}}</td>
                                    <td>{{transactionInfo.cell_no}}</td>
                                    <td>{{transactionInfo.type}}</td>
                                    <td>{{transactionInfo.amount}}</td>
                                    <td>{{transactionInfo.cost}}</td>
                                    
                                    <td ng-if="transactionInfo.status_id == <?php echo TRANSACTION_STATUS_ID_PENDING; ?>" style="color: violet; font-weight: bold">{{transactionInfo.status}}</td>
                                    <td ng-if="transactionInfo.status_id == <?php echo TRANSACTION_STATUS_ID_SUCCESSFUL; ?>" style="color: green; font-weight: bold">{{transactionInfo.status}}</td>
                                    <td ng-if="transactionInfo.status_id == <?php echo TRANSACTION_STATUS_ID_FAILED; ?>" style="color: red; font-weight: bold">{{transactionInfo.status}}</td>
                                    <td ng-if="transactionInfo.status_id == <?php echo TRANSACTION_STATUS_ID_CANCELLED; ?>" style="color: brown; font-weight: bold">{{transactionInfo.status}}</td>
                                    <td ng-if="transactionInfo.status_id == <?php echo TRANSACTION_STATUS_ID_PROCESSED; ?>" style="color: blue; font-weight: bold">{{transactionInfo.status}}</td>

                                    
                                    <td>{{transactionInfo.created_on}}</td>
                                    <!--<td ng-if="transactionInfo.editable == 1"><a href="<?php echo base_url() . 'transaction/bkash/'; ?>{{transactionInfo.transaction_id}}">Edit</a></td>-->
                                </tr>
                            </tbody>
                        </table>
                    </div></td>
                </tr>
                </tbody></table>
        </div> 
    </div>
    <div class="row  display-hidden" id="div_bkash_confimation">
        <div class="col-sm-4 col-md-4">
            <div class="row form-group">
                <div class="col-sm-12">
                    <h4 class="heading_color">Sending bKash... Are you sure?</h4>
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
                        {{bkashInfo.number}}
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
                    <label id="label_transaction_type" name="label_transaction_type" for="type" class="col-md-12 control-label requiredField label_custom green_color">
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
                        {{bkashInfo.amount}}
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
                    <label id="label_cost" name="label_cost" for="cost" class="col-md-12 control-label requiredField label_custom red_color">
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
                        <input ng-model="bkashInfo.code" type="password" name="pin" id="amount" class="form-control"  placeholder="">  
                    </label>
                </div>
            </div>
            <div class="row form-group">
                <div class="col-sm-12 col-md-12">
                    <hr>
                    <button id="bkash_cancel_btn" class="button-danger pull-right"  onclick="bkash_section_cancel_button()">Cancel</button>
                    <button id="bkash_confirm_btn" class="button-custom pull-right margin-right-10px"  onclick="bkash_section_confirm_button()">Confirm</button>
                </div>
            </div>
        </div>
    </div>
</div>                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                            
<script>
    function  getTopupOperatorId(number) {
        if(number.indexOf("88") == 0)
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
    function package_recharge(topUpInfo, topupType) {
        if (typeof topUpInfo.amount == "undefined" || topUpInfo.amount.length == 0) {
            $("#content").html("Please select a valid package.");
            $('#common_modal').modal('show');
            return;
        }
        if (typeof topUpInfo.number == "undefined" || topUpInfo.number.length == 0) {
            $("#content").html("Please assign a valid number.");
            $('#common_modal').modal('show');
            return;
        }
        if (number_validation(topUpInfo.number) == false) {
            $("#content").html("Please give a valid cell number");
            $('#common_modal').modal('show');
            return;
        }
        
        var topupOperatorId = getTopupOperatorId(topUpInfo.number);        
        if(topupOperatorId == "")
        {
            $("#content").html("Invalid operator. Please assign a valid number.");
            $('#common_modal').modal('show');
            return;
        }
        if(topupOperatorId != topUpInfo.topupOperatorId)
        {
            $("#content").html("Your assigned number is different compared to your selected operator.");
            $('#common_modal').modal('show');
            return;
        }
        if (topUpInfo.topupOperatorId == '<?php echo SERVICE_TYPE_ID_TOPUP_GP; ?>' && topUpInfo.topupTypeId == '<?php echo OPERATOR_TYPE_ID_POSTPAID; ?>') {
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
        topUpInfo.topupType = topupType;
        $("#confirmation_content").html("Number : "+topUpInfo.number+" and Amount : "+topUpInfo.amount);
        $('#common_confirmation_modal').modal('show');
        $('#modal_confirm_click_id').on("click", function() {
            angular.element($('#submit_package_transaction')).scope().packageRecharge(function(data) {
                $("#content").html(data.message);
                $('#common_modal').modal('show');
                $('#modal_ok_click_id').on("click", function() {
                    window.location = '<?php echo base_url() ?>package_recharge';
                });
            });
        });
        $('#modal_cancel_click_id').on("click", function() {
            window.location = '<?php echo base_url() ?>package_recharge';
        });
    }
    $(function() {
        setInterval(callFunction, <?php echo TRANSACTION_LIST_CALLING_INTERVER; ?>);
    });
    function callFunction() {
        var serviceIdList = [<?php echo SERVICE_TYPE_ID_TOPUP_GP; ?>,<?php echo SERVICE_TYPE_ID_TOPUP_ROBI; ?>, <?php echo SERVICE_TYPE_ID_TOPUP_BANGLALINK; ?>, <?php echo SERVICE_TYPE_ID_TOPUP_AIRTEL; ?>, <?php echo SERVICE_TYPE_ID_TOPUP_TELETALK; ?>];
        angular.element($('#transaction_list_id')).scope().getAjaxTransactionList(serviceIdList);
    }
</script>
<div class="loader"></div>
<div class="ezttle"><span class="text">Package Recharge</span></div>
<div class="mypage" ng-app="app.Package" ng-controller="packageController">
    <div class="row">
        <div class="col-md-6" ng-init="initPackageRecharge('<?php echo htmlspecialchars(json_encode($transaction_list)) ?>', '<?php echo htmlspecialchars(json_encode($operator_list)) ?>', '<?php echo htmlspecialchars(json_encode($topup_type_list)) ?>')">
            <div class="row form-group">
                <div class="col-md-5">	
                    <label for="type" class="col-md-6 control-label requiredField label_custom">
                        Operator
                    </label>
                </div>
                <div class="col-md-7">	
                    <select  for="type" id="type"  ng-change="getOperatorPackages(topUpInfo.topupOperatorId)"  ng-model="topUpInfo.topupOperatorId" class="form-control control-label requiredField">
                        <option ng-selected="operatorInfo.selected" class=form-control ng-repeat="operatorInfo in operatorList" value="{{operatorInfo.operator_id}}">{{operatorInfo.title}}</option>
                    </select>                    
                </div> 
            </div>
            <div class="row form-group">
                <div class="col-md-5">	
                    <label for="type" class="col-md-6 control-label requiredField label_custom">
                        Package
                    </label>
                </div>
                <div class="col-md-7">	
                    <select  for="type" id="type"  ng-change="getPackageInfo(topUpInfo.packageId)"  ng-model="topUpInfo.packageId" class="form-control control-label requiredField">
                        <option ng-selected="packageInfo.selected" class=form-control ng-repeat="packageInfo in packageList" value="{{packageInfo.package_id}}">{{packageInfo.title}}</option>
                    </select>
                </div> 
            </div>
            <div class="row form-group">
                <div class="col-md-5">	
                    <label for="type" class="col-md-6 control-label requiredField label_custom">
                        Amount
                    </label>
                </div>
                <div class="col-md-7">	
                    <label for="number" class="control-label requiredField label_custom">
                        <input type="text" readonly="readonly" name="" id=""  class="form-control" ng-model="topUpInfo.amount"> 
                    </label>
                </div> 
            </div>
            <div class="row form-group">
                <div class="col-md-5">	
                    <label for="type" class="col-md-6 control-label requiredField label_custom">
                        Number
                    </label>
                </div>
                <div class="col-md-7">	
                    <label for="number" class="control-label requiredField label_custom">
                        <input type="text" name="" id=""  class="form-control" placeholder="eg: 01XXXXXXXXX" ng-model="topUpInfo.number"> 
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
                    <select  class="form-control control-label requiredField"
                        ng-options="topupType.title for topupType in topupTypes.topupTypeList track by topupType.id"
                        ng-model="topupTypes.selectedOption"></select>
                </div> 
            </div>
            <div class="row form-group">
                <div class="col-md-12">
                    <button id="submit_package_transaction" name="submit_package_transaction" class="button-custom pull-right" onclick="package_recharge(angular.element(this).scope().topUpInfo, angular.element(this).scope().topupTypes.selectedOption.id)">Send</button>
                </div> 
            </div>
        </div>
        <div class="col-md-6">
            <p class="help-block">Last 10 Requests</p>
            <div id="transaction_list_id" style="margin:0px;padding:0px;background:#fff;">
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
            </div>
        </div>
    </div>
</div>
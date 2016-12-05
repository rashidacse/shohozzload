<script type="text/javascript">
    $(function () {
        $('#start_date').datepicker().on('changeDate', function(ev) {
            $('#start_date').text($('#start_date').data('date'));
            $('#start_date').datepicker('hide');
        });
        $('#end_date').datepicker().on('changeDate', function(ev) {
            $('#end_date').text($('#end_date').data('date'));
            $('#end_date').datepicker('hide');
        });
//        $('#start_date').Zebra_DatePicker();
        $('#start_date').val('<?php echo $current_date ?>');
//        $('#end_date').Zebra_DatePicker();
        $('#end_date').val('<?php echo $current_date ?>');
        $('#payment_type').val('0');
        $('#status_type').val('<?php echo TRANSACTION_STATUS_ID_SUCCESSFUL ?>');
    });
    function search_payment_history(searchInfo) {
        if(searchInfo.userGroupId == '<?php echo RESELLER_GROUP_ID_SHOW_ALL?>')
        {
            searchInfo.allSuccessors = 1;
        }
        else
        {
            searchInfo.allSuccessors = 0;
        }
        
        var startDate = $("#start_date").val();
        var endDate = $("#end_date").val();
        angular.element($("#search_submit_btn")).scope().getPaymentHistory(startDate, endDate);
    }
</script>


<div class="loader"></div>
<div class="ezttle"><span class="text">Payment History</span>
    <span class="acton"></span>
</div>

<div ng-controller="transctionController" class="mypage" ng-init="initTransactionPaymentHistory('<?php echo htmlspecialchars(json_encode($transction_status_list)) ?>', '<?php echo $user_id?>')">
    <ng-form>
        <ul class="list-unstyled paymentHistorySearch" ng-init="setPaymentTypeIds('<?php echo htmlspecialchars(json_encode($payment_type_ids)) ?>')">
            <li>Group:</li>
            <li ng-init="setSuccessorGroupList('<?php echo htmlspecialchars(json_encode($successor_group_list)) ?>')">
                <select  id="payment_type" ng-model="searchInfo.userGroupId" ng-change="getSuccessorListofGroup()" class="form-control input-xs customInputMargin">
                    <option  value="">Please select</option>
                    <option  ng-repeat="groupInfo in successorGroupList" value="{{groupInfo.id}}">{{groupInfo.title}}</option>
                </select>
            </li>
            <li>Reseller:</li>
            <li >
                <select ng-model="searchInfo.userId" class="form-control input-xs customInputMargin">
                    <option value="">Please select</option>
                    <option ng-repeat="resellerInfo in resellerList" value="{{resellerInfo.user_id}}">{{resellerInfo.username}}</option>
                </select>
            </li>
            <li>Start Date</li>
            <li><input data-date-format='yyyy-mm-dd' id="start_date" type="text" size="18" placeholder="Start Date"  name="start_date" class="form-control input-xs customInputMargin"></li>
            <li>End Date</li>
            <li><input data-date-format='yyyy-mm-dd' id="end_date" type="text" size="18" placeholder="End Date"  name="end_date" class="form-control input-xs customInputMargin"></li>
            <li>Type</li>
            <li> <select name="payment_type" id="payment_type" ng-model="paymentType.key">
                    <option  value="">Please select</option>
                    <option ng-repeat="(key, paymentType) in paymentTypeIds" value="{{key}}">{{paymentType}}</option>
                </select>
            </li>
            <li>Status Type</li>
            <li>
                 <select  ng-model='searchInfo.statusId' required ng-options='transactionStatus.id as transactionStatus.title for transactionStatus in transactionStatusList' class="form-control input-xs"></select>
            </li>
            <li>Show All</li>
            <li> <input type="checkbox" ng-model="allTransactions"></li>
            <li><input id="search_submit_btn" type="submit" size="18" value="Search" onclick="search_payment_history(angular.element(this).scope().searchInfo)" class="button-custom"></li>
        </ul>
    </ng-form>
    <table id="" class="table10" ng-init="setPaymentInfoList(<?php echo htmlspecialchars(json_encode($payment_info_list)) ?>, <?php echo htmlspecialchars(json_encode($total_transactions)) ?>, <?php echo htmlspecialchars(json_encode($total_amount)) ?>)">
        <thead>
            <tr>
                <th><a href="">Sender</a></th>
                <th><a href="">Receiver</a></th>
                <th><a href="">Amount</a></th>
                <th><a href="">Payment Type</a></th>
                <th><a href="">Description</a></th>
                <th><a href="">Date</a></th>
            </tr>
        </thead>
        <tbody>
        <li style="display: none" dir-paginate="paymentInfo in paymentInfoList|itemsPerPage:pageSize" current-page="currentPage"></li>
        <tr ng-repeat="paymentInfo in paymentInfoList">
            <th>{{paymentInfo.source_username}}</th>
            <th>{{paymentInfo.destination_username}}</th>
            <th>{{paymentInfo.amount}}</th>
            <th>
                <span ng-if="paymentInfo.type_id == '<?php echo PAYMENT_TYPE_ID_SEND_CREDIT ?>'">
                    Send Credit
                </span>
                <span ng-if="paymentInfo.type_id == '<?php echo PAYMENT_TYPE_ID_RETURN_CREDIT ?>'">
                    Return Credit
                </span>
                <span ng-if="paymentInfo.type_id == '<?php echo PAYMENT_TYPE_ID_RETURN_RECEIVE_CREDIT ?>'">
                    Return Credit Back
                </span>
            </th>
            <th>{{paymentInfo.description}}</th>
            <th>{{paymentInfo.created_on}}</th>
        </tr>
        </tbody>
    </table>
    <div class="form-group">
        <div class="col-md-12 fleft">
            <div class="summery">
                <p>Summary</p>
                <table>
                    <tbody>
                        <!--<tr><td>Current Page Payment :</td><td class="amt">{{currentPageAmount}}</td></tr>-->
                        <tr><td>Total Payment :</td><td class="amt">{{totalAmount}}</td></tr>
                    </tbody>
                </table>
            </div>
        </div> 
    </div>

    <div class="other-controller">
        <div class="text-center">
            <dir-pagination-controls boundary-links="true" on-page-change="getPaymentHistoryByPagination(newPageNumber)" template-url="<?php echo base_url(); ?>history/pagination_tmpl_load"></dir-pagination-controls>
        </div>
    </div>
</div>
<div class="row"></div>
<div class="row"></div>
<div class="row"></div>



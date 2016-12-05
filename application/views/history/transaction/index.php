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
        $('#repeatSelect').val('<?php echo TRANSACTION_STATUS_ID_SUCCESSFUL ?>');
    });
    function search_receive_history(searchInfo) {
        var startDate = $("#start_date").val();
        var endDate = $("#end_date").val();
        angular.element($("#search_submit_btn")).scope().getAllHistory(startDate, endDate);
    }
</script>

<div class="loader"></div>
<div class="ezttle"><span class="text"> Transaction History</span></div>
<div class="mypage" ng-controller="transctionController">
    <ul class="list-unstyled paymentHistorySearch" ng-init="setTransactionStatusList('<?php echo htmlspecialchars(json_encode($transction_status_list)) ?>')">
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
        <li>Cell No</li>
        <li> <input type="text" class="form-control input-xs customInputMargin" placeholder="01XXXXXXXXX" ng-model="searchInfo.cellNo"></li>
        <li>Status Type</li>
        <li>
            <select  ng-model='searchInfo.statusId' required ng-options='transactionStatus.id as transactionStatus.title for transactionStatus in transactionStatusList' class="form-control input-xs"></select>
        </li>
        <li>Show All</li>
        <li> <input type="checkbox" ng-model="allTransactions"></li>
        <li><input id="search_submit_btn" type="submit" size="18" value="Search" onclick="search_receive_history(angular.element(this).scope().searchInfo)" class="button-custom"></li>
    </ul>
    <table class="table10" ng-init="setTransactionInfoList(<?php echo htmlspecialchars(json_encode($transaction_list)) ?>, <?php echo htmlspecialchars(json_encode($total_transactions)) ?>, <?php echo htmlspecialchars(json_encode($total_amount)) ?>)">
        <thead>
            <tr>
                <th><a href="">Id</a></th>
                <th><a href="">Number</a></th>
                <th><a href="">Amount</a></th>
                <th><a href="">Cost</a></th>
                <th><a href="">Username</a></th>
                <th><a href="">Type</a></th>
                <th><a href="">Service</a></th>
                <th><a href="">Sender</a></th>                
                <th><a href="">Status</a></th>
                <th><a href="">Transaction Id</a></th>
                <th><a href="">Last Balance</a></th>
                <th><a href="">Date</a></th>
            </tr>
        </thead>
        <tbody>
        </tbody>
        <tfoot>
        <li style="display: none" dir-paginate="paymentInfo in transctionInfoList|itemsPerPage:pageSize" current-page="currentPage"></li>
        <tr ng-repeat="transctionInfo in transctionInfoList">
            <th>{{transctionInfo.t_id}}</th>
            <th>{{transctionInfo.cell_no}}</th>            
            <th>{{transctionInfo.amount}}</th>
            <th>{{transctionInfo.cost}}</th>
            <th>{{transctionInfo.username}}</th>
            <th>{{transctionInfo.type}}</th>
            <th>{{transctionInfo.service_title}}</th>
            <th>{{transctionInfo.sender_cell_no}}</th>            
            
            <th ng-if="transctionInfo.status_id == <?php echo TRANSACTION_STATUS_ID_PENDING; ?>" style="color: violet">{{transctionInfo.status}}</th>
            <th ng-if="transctionInfo.status_id == <?php echo TRANSACTION_STATUS_ID_SUCCESSFUL; ?>" style="color: green">{{transctionInfo.status}}</th>
            <th ng-if="transctionInfo.status_id == <?php echo TRANSACTION_STATUS_ID_FAILED; ?>" style="color: red">{{transctionInfo.status}}</th>
            <th ng-if="transctionInfo.status_id == <?php echo TRANSACTION_STATUS_ID_CANCELLED; ?>" style="color: brown">{{transctionInfo.status}}</th>
            <th ng-if="transctionInfo.status_id == <?php echo TRANSACTION_STATUS_ID_PROCESSED; ?>" style="color: blue">{{transctionInfo.status}}</th>
            
            <th>{{transctionInfo.trx_id_operator}}</th>
            <th>{{transctionInfo.last_balance}}</th>
            <th>{{transctionInfo.created_on}}</th>
        </tr>
        </tfoot>
    </table>
    <div class="form-group">
        <div class="col-md-12 fleft">
            <div class="summery">
                <p>Summary</p>
                <table>
                    <tbody>
                        <tr><td>Current Page Amount :</td><td class="amt">{{currentPageAmount}}</td></tr>
                        <tr><td>Total Amount :</td><td class="amt">{{totalAmount}}</td></tr>
                    </tbody>
                </table>
            </div>
        </div> 
    </div>

    <div class="other-controller">
        <div class="text-center">
            <dir-pagination-controls boundary-links="true" on-page-change="getTransctionByPagination(newPageNumber)" template-url="<?php echo base_url(); ?>history/pagination_tmpl_load"></dir-pagination-controls>
        </div>
    </div>
</div>
<div class="row"></div>
<div class="row"></div>
<div class="row"></div>

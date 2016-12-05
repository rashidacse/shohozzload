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
    });
    function search_sms(searchInfo) {
        if (typeof searchInfo.simNo != "undefined" && searchInfo.simNo.length != 0) {
            if (number_validation(searchInfo.simNo) == false) {
                $("#content").html("Please give a valid SIM Number");
                $('#common_modal').modal('show');
                return;
            }
        }
        var startDate = $("#start_date").val();
        var endDate = $("#end_date").val();
        angular.element($("#search_submit_btn")).scope().getSMSList(startDate, endDate);
    }
</script>
<div class="loader"></div>
<div class="ezttle"><span class="text">SMS History</span></div>
<div class="mypage" ng-controller="simController">
    <ul class="list-unstyled paymentHistorySearch">
        <li>Sim No</li>
        <li ng-init="setSimList(<?php echo htmlspecialchars(json_encode($sim_list)) ?>)">
            <select name="sender_cell_no"  ng-model="searchInfo.simNo">
                <option  value="">Please select</option>
                <option ng-repeat="simInfo in simList" value="{{simInfo.sim_no}}">{{simInfo.sim_no}}</option>
            </select>
        </li>
        <li>Start Date</li>
        <li><input data-date-format='yyyy-mm-dd' id="start_date" type="text" size="18" placeholder="Start Date"  name="start_date" class="form-control input-xs customInputMargin"></li>
        <li>End Date</li>
        <li><input data-date-format='yyyy-mm-dd' id="end_date" type="text" size="18" placeholder="End Date"  name="end_date" class="form-control input-xs customInputMargin"></li>
        <li>Show All</li>
        <li> <input type="checkbox" ng-model="searchInfo.selectAll"></li>

        <li><input id="search_submit_btn" type="submit" size="18" value="Search" onclick="search_sms(angular.element(this).scope().searchInfo)" class="button-custom"></li>
    </ul>
    <table class="table table-striped table-hover"> 
        <thead>
            <tr>
                <th><a href="">Id</a></th>
                <th><a href="">Sim Number</a></th>
                <th><a href="">Sender</a></th>
                <th><a href="">SMS</a></th>
                <th><a href="">Date</a></th>
            </tr>
        </thead>
        <tbody>
        </tbody>
        <tfoot ng-init="setSMSList('<?php echo htmlspecialchars(json_encode($sms_list)) ?>', '<?php echo htmlspecialchars(json_encode($total_counter)) ?>')">
            <tr ng-repeat="smsInfo in smsList">
                <th>{{smsInfo.id}}</th>
                <th>{{smsInfo.simNo}}</th>
                <th>{{smsInfo.sender}}</th>
                <th>{{smsInfo.sms}}</th>
                <th>{{smsInfo.createdOn}}</th>
            </tr>
        </tfoot>
    </table>
    <li style="display: none" dir-paginate="smsInfo in smsList|itemsPerPage:pageSize" current-page="currentPage"></li>
    <div class="other-controller">
        <div class="text-center">
            <dir-pagination-controls boundary-links="true" on-page-change="getSIMByPagination(newPageNumber)" template-url="<?php echo base_url(); ?>superadmin/transaction/pagination_tmpl_load"></dir-pagination-controls>
        </div>
    </div>
</div>

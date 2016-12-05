
<script>

    function create_payment(paymentInfo) {
        var userId = '<?php echo $user_id; ?>';
        if (typeof paymentInfo.amount == "undefined" || paymentInfo.amount.length == 0) {
            $("#content").html("Please give an amount !");
            $('#common_modal').modal('show');
            return;
        }
        if (typeof paymentInfo.payment_type == "undefined" || paymentInfo.payment_type.length == 0) {
            $("#content").html("Please Select Payment Type!");
            $('#common_modal').modal('show');
            return;
        }
        angular.element($('#submit_create_payment')).scope().createPayment(userId, function (data) {
            $("#content").html(data.message);
            $('#common_modal').modal('show');
            $('#modal_ok_click_id').on("click", function () {
                window.location = '<?php echo base_url() ?>reseller/get_reseller_list';
            });
        });
    }
</script>
<div class="loader"></div>
<div class="ezttle"><span class="text">Payment</span></div>
<div class="mypage"  ng-controller="paymentController">
    <div class="row" style="margin-top:5px;">
        <div class="col-md-12 fleft" ng-init="initCreatePayment(<?php echo htmlspecialchars(json_encode($payment_type_list)); ?>, <?php echo htmlspecialchars(json_encode($payment_list)); ?>)">	
            <input name="elctkn" value="30dfe1ad62facbf8e5b1ec2e46f9f084" style="display:none;" type="hidden">
            <table class="table10">
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
                </tbody>
                <tfoot>
                <tr ng-repeat="paymentInfo in paymentList">
                    <th>{{paymentInfo.source_username}}</th>
                    <th>{{paymentInfo.destination_username}}</th>
                    <th>{{paymentInfo.amount}}</th>
                    <th>
                        <span ng-if="paymentInfo.type_id == '<?php echo PAYMENT_TYPE_ID_LOAD_BALANCE ?>'">
                            Load Balance
                        </span>
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
                </tfoot>
            </table>
            <table style="width:100%;">
                <tbody><tr>
                        <td style="width:50%;vertical-align:top;padding-right:20px;">
                <ng-form>
                    <div class="row col-md-12" id="box_content_2" class="box-content" style="padding-top: 10px;">
                        <div class ="row">
                            <div class="col-md-12">  </div>
                        </div>
                        <div class=" row form-group">
                            <label for="amount" class="col-md-6 control-label requiredField">
                                Amount
                            </label>
                            <label for="amount" class="col-md-6 control-label requiredField">
                                <input type="text" name="amount" ng-model="paymentInfo.amount" class="form-control" placeholder='eg: 100'>              
                            </label>
                        </div>
                        <div class=" row form-group">
                            <div class="col-md-6">
                                <label for="payment_type_list" class="control-label requiredField">
                                    Type
                                </label>
                            </div>
                            <div class="col-md-6">
                                <select  class="form-control "name="seleted_payment_type" id="seleted_payment_type" ng-model="paymentInfo.payment_type">
                                    <option class="form-control" value="">Please select</option>
                                    <option class="form-control" ng-repeat="(key,paymentType) in paymentTypeList" value="{{key}}">{{paymentType}}</option>
                                </select>
                            </div>
                        </div>
                        <div class="row form-group">
                            <label for="description" class="col-md-6 control-label requiredField">
                                Description
                            </label>
                            <label for="description" class="col-md-6 control-label requiredField">
                                <input type="text" name="description" ng-model="paymentInfo.description" class="form-control" >              
                            </label>
                        </div>
                        <div class="row form-group">
                            <label for="submit_create_payment" class="col-md-6 control-label requiredField">

                            </label>
                            <div class ="col-md-3 pull-right">
                                <button id="submit_create_payment" class="form-control button"  onclick="create_payment(angular.element(this).scope().paymentInfo)">Send</button>
                            </div> 
                        </div>
                    </div>
                </ng-form>
                </td>
                <td>
                </td>
                </tr>
                </tbody></table>
        </div> 
    </div>
</div>
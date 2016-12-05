
<div class="loader"></div>
<div class="ezttle"><span class="text">Test Reseller</span>
    <span class="acton"></span>
</div>

<div ng-controller="resellerController" class="mypage" ng-init="">
    <div class="btn-group form-group">
        <a href="<?php echo base_url(); ?>reseller/create_reseller" class="button-custom"><span class="glyphicon glyphicon-plus-sign"></span> Add Reseller</a>
    </div>
    <ng-form>
        <ul class="list-unstyled paymentHistorySearch" ng-init="')">
            <li>Show:</li>
            <li ng-init="">
                <select  id="payment_type" ng-model="" class="form-control input-xs customInputMargin">
                    <option  value="">Please select</option>
                    <!--<option  ng-repeat="" value=""></option>-->
                    <option  value="">10</option>
                    <option  value="">50</option>
                    <option  value="">100</option>
                    <option  value="">150</option>
                    <option  value="">200</option>
                    <option  value="">400</option>
                    <option  value="">500</option>
                </select>
            </li>
            <li>Username:</li>
            <li><input type="text" placeholder="Username"  name="username" class="form-control input-xs customInputMargin"></li>
            <li>Name:</li>
            <li><input type="text" placeholder="Name"  name="name" class="form-control input-xs customInputMargin"></li>
            <li>Mobile:</li>
            <li><input type="text" placeholder="Mobile Number"  name="mobile" class="form-control input-xs customInputMargin"></li>
            <li>Email:</li>
            <li><input type="text" placeholder="Email Address"  name="emai" class="form-control input-xs customInputMargin"></li>
            <li><input id="search_submit_btn" ng-model="" type="submit" size="18" value="Filter" onclick="" class="button-custom"></li>
        </ul>
    </ng-form>
<!--    <table id="" class="table10" ng-init="">
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
        <li style="display: none" dir-paginate="" current-page="currentPage"></li>
        <tr ng-repeat="">
            <th></th>
            <th></th>
            <th></th>
            <th>
                <span ng-if="">
                    Send Credit
                </span>
                <span ng-if="">
                    Return Credit
                </span>
                <span ng-if="">
                    Return Credit Back
                </span>
            </th>
            <th></th>
            <th></th>
        </tr>
        </tbody>
    </table>-->
    <div class="top10">&nbsp;</div>
    <input type="hidden" style="display:none;" value="" name="elctkn">
    <table class="table10">
        <thead>
            <tr>
                <th><input type="checkbox"></th>
                <th><a href="">Username</a></th>
                <th><a href="">Password</a></th>
                <th><a href="">Mobile</a></th>
                <th><a href="">Balance</a></th>
                <th><a href="">Last Login</a></th>
                <th><a href="">Created</a></th>
                <th><a href="">Status</a></th>
                <th><a href="">Action</a></th>

            </tr>
        </thead>
        <tbody >
            <tr >
                <td><input type="checkbox"></td>
                <td>Md. Unknown Person</td>
                <td>***********</td>
                <td>01XXXXXXXXX</td>
                <td>1605.00</td>
                <td>13 Oct 16 5:28 PM</td>
                <td>16 Aug 16 12:00 AM</td>
                <td>Active</td>
                <td class="action">
                    <a href="">Payment</a>
                    | <a href="">View</a>
                    | <a href="">Rates</a>
                </td>
            </tr>
        </tbody>
    </table>	
    <li style="display: none" dir-paginate="" current-page=""></li>
    <div class="other-controller">
        <div class="text-center">
            <dir-pagination-controls boundary-links="true" on-page-change="" template-url=""></dir-pagination-controls>
        </div>
    </div>
    <div class="form-group">
        <div class="col-md-12 fleft">
            <div class="summery">
                <p>Summary</p>
                <table>
                    <tbody>
                        <tr><td>Page 1 of 1 (Showing 1 to 1 of 1 records)</td></tr>
                    </tbody>
                </table>
            </div>
        </div> 
    </div>

    <div class="other-controller">
        <div class="text-center">
            <dir-pagination-controls boundary-links="true" on-page-change="" template-url=""></dir-pagination-controls>
        </div>
    </div>
</div>



angular.module('services.Report', []).
        factory('reportService', function ($http, $location) {
            var $app_name = "/rechargeserver";
            //var $app_name = "";
            var reportService = {};

            reportService.bkash = function (bkashInfo) {

                return $http({
                    method: 'post',
                    url: $location.path() + $app_name + '/transaction/bkash',
                    data: {
                        bkashInfo: bkashInfo
                    }
                });
            }
            reportService.getDetailRepotHistory = function (searchInfo) {

                return $http({
                    method: 'post',
                    url: $location.path() + $app_name + '/report/get_detailed_report',
                    data: {
                        searchInfo: searchInfo
                    }
                });
            }
//            reportService.getResellerChildList = function (searchInfo) {
//
//                return $http({
//                    method: 'post',
//                    url: $location.path() + $app_name + '/reseller/get_reseller_child_list',
//                    data: {
//                        searchInfo: searchInfo
//                    }
//                });
//            }
            reportService.getSuccessorListofGroup = function (groupId) {

                return $http({
                    method: 'post',
                    url: $location.path() + $app_name + '/reseller/get_successor_list_of_group',
                    data: {
                        groupId: groupId
                    }
                });
            }
            reportService.getRepotHistory = function (searchInfo) {

                return $http({
                    method: 'post',
                    url: $location.path() + $app_name + '/report/get_total_report',
                    data: {
                        searchInfo: searchInfo
                    }
                });
            }
            reportService.getBkashTransactionList = function (searchInfo) {

                return $http({
                    method: 'post',
                    url: $location.path() + $app_name + '/report/get_total_report',
                    data: {
                        searchInfo: searchInfo
                    }
                });
            }
            reportService.getProfitLossHistory = function (searchInfo) {

                return $http({
                    method: 'post',
                    url: $location.path() + $app_name + '/report/get_user_profit_loss',
                    data: {
                        searchInfo: searchInfo
                    }
                });
            }
           
            return reportService;
        });


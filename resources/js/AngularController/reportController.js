angular.module('controller.Report', ['services.Report']).
        controller('reportController', function ($scope, reportService) {
            $scope.currentPage = 1;
            $scope.pageSize = 10;
            $scope.profitList = [];
            $scope.reportList = [];
            $scope.userProfits = [];
            $scope.serviceList = [];
            //$scope.resellerList = [];
            $scope.successorGroupList = [];
            $scope.reportSummery = {};
            $scope.transactionStatusList = [];
            //$scope.resellerChildList = [];
            $scope.resellerList = [];
            $scope.userTotalProfitInfo = {};
            $scope.searchInfo = {};
            $scope.allTransactions = false;
            $scope.setUserProfits = function (userProfits) {
                $scope.userProfits = JSON.parse(userProfits);
                $scope.userTotalProfitInfo.usertotalUsedAmount = 0;
                $scope.userTotalProfitInfo.userTotalProfit = 0;
                angular.forEach($scope.userProfits, function (profit) {
                    $scope.userTotalProfitInfo.usertotalUsedAmount = $scope.userTotalProfitInfo.usertotalUsedAmount + +profit.total_used_amount;
                    $scope.userTotalProfitInfo.userTotalProfit = $scope.userTotalProfitInfo.userTotalProfit + +profit.total_profit;

                });
            }

            $scope.setProfitList = function (profitList, collectionCounter) {
                $scope.profitList = JSON.parse(profitList);
                setCollectionLength(collectionCounter);
            }
//            $scope.setReseller = function (resellerList) {
//                $scope.resellerList = JSON.parse(resellerList);
//            }
            $scope.setSuccessorGroupList = function (successorGroupList) {
                $scope.successorGroupList = JSON.parse(successorGroupList);
            }
            $scope.setTransactionStatusList = function (transactionStatusList) {
                $scope.transactionStatusList = JSON.parse(transactionStatusList);
                $scope.searchInfo.statusId = 0;
            }
            $scope.setServiceIdList = function (serviceList) {
                $scope.serviceList = JSON.parse(serviceList);
                $scope.searchInfo.serviceId = 0;
            }
            $scope.setReportList = function (reportList, reportSummery) {
                $scope.reportList = JSON.parse(reportList);
                $scope.reportSummery = JSON.parse(reportSummery);
            }


            $scope.getRepotHistory = function (startDate, endDate) {

                $scope.searchInfo.limit = $scope.allTransactions;
                if (startDate != "" && endDate != "") {
                    $scope.searchInfo.fromDate = startDate;
                    $scope.searchInfo.toDate = endDate;
                }
                reportService.getRepotHistory($scope.searchInfo).
                        success(function (data, status, headers, config) {
                            $scope.profitList = data.profit_list;
                            if ($scope.allTransactions != false) {
                                $scope.pageSize = data.total_transactions;
                                $scope.allTransactions = false;
                            }
                            setCollectionLength(data.total_transactions);
                        });
            };
//            $scope.getResellerChildList = function () {
//                reportService.getResellerChildList($scope.searchInfo).
//                        success(function (data, status, headers, config) {
//                            $scope.resellerChildList = data.reseller_child_list;
//                        });
//            };
            $scope.getSuccessorListofGroup = function () {
                //resetting selected user from search param if the dropdown selected item is changed
                $scope.searchInfo.userId = 0;
                reportService.getSuccessorListofGroup($scope.searchInfo.userGroupId).
                        success(function (data, status, headers, config) {
                            //updating reseller list based on selected user group id
                            $scope.resellerList = data.reseller_list;
                        });
            };
            $scope.getDetailRepotHistory = function (startDate, endDate) {

                $scope.searchInfo.limit = $scope.allTransactions;
                if (startDate != "" && endDate != "") {
                    $scope.searchInfo.fromDate = startDate;
                    $scope.searchInfo.toDate = endDate;
                }
                reportService.getDetailRepotHistory($scope.searchInfo).
                        success(function (data, status, headers, config) {
                            $scope.reportList = data.report_list;
                            $scope.reportSummery = data.report_summary;
                            if ($scope.allTransactions != false) {
                                $scope.allTransactions = false;
                            }
                        });
            };
            $scope.getProfitLossHistory = function (startDate, endDate) {

                $scope.searchInfo.limit = $scope.allTransactions;
                if (startDate != "" && endDate != "") {
                    $scope.searchInfo.fromDate = startDate;
                    $scope.searchInfo.toDate = endDate;
                }
                reportService.getProfitLossHistory($scope.searchInfo).
                        success(function (data, status, headers, config) {
                            $scope.reportList = data.report_list;
                            $scope.reportSummery = data.report_summary;
                            if ($scope.allTransactions != false) {
                                $scope.allTransactions = false;
                            }
                        });
            };
            $scope.getProfitByPagination = function (num) {
                $scope.searchInfo.offset = getOffset(num);
                reportService.getBkashTransactionList($scope.searchInfo).
                        success(function (data, status, headers, config) {
                            $scope.profitList = data.profit_list;
                        });
            };
            function getOffset(number) {
                var initIndex;
                initIndex = $scope.pageSize * (number - 1);
                return initIndex;
            }

        });



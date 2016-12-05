angular.module('controller.Transction', ['service.Transction']).
        controller('transctionController', function ($scope, transctionService) {
            $scope.currentPage = 1;
            $scope.pageSize = 10;
            $scope.transactionInfo = {};
            $scope.searchInfo = {};
            $scope.loadBalanceInfo = {};
            $scope.serviceList = [];
            $scope.transctionInfoList = [];
            $scope.transactionStatusList = [];            
            $scope.simList = [];
            $scope.allow_action = true;
            $scope.totalAmount = 0;
            $scope.currentPageAmount = 0;
            $scope.allTransactions = false;
            //initializing transaction history page data
            $scope.initTransactionsHistory = function (fromDate, toDate, serviceList, transactionStatusList, transactionProcessTypeList) {
                $scope.searchInfo.fromDate = fromDate;
                $scope.searchInfo.toDate = toDate;
                $scope.serviceList = JSON.parse(serviceList);
                $scope.transactionStatusList = JSON.parse(transactionStatusList);
                $scope.transactionProcessTypeList = JSON.parse(transactionProcessTypeList);
                $scope.searchInfo.serviceId = 0;
                $scope.searchInfo.statusId = 0;
                $scope.searchInfo.processId = 0;
            };
            //setting service id list
            $scope.setServiceIdList = function (serviceList) {
                $scope.serviceList = JSON.parse(serviceList);
            };
            //setting transaction status list
            $scope.setTransactionStatusList = function (transactionStatusList) {
                $scope.transactionStatusList = JSON.parse(transactionStatusList);                
            };
            //setting sim list
            $scope.setSimList = function (simList) {
                $scope.simList = JSON.parse(simList);
            };
            //setting transaction process type list
            $scope.setTransactionProcessTypeList = function (transactionProcessTypeList) {
                $scope.transactionProcessTypeList = JSON.parse(transactionProcessTypeList);
            };
            //setting transaction list
            $scope.setTransctionList = function (transctionList, collectionCounter, totalAmount) {
                $scope.transctionInfoList = JSON.parse(transctionList);
                $scope.totalAmount = totalAmount;
                getCurrentPageTransctionAmount();
                setCollectionLength(collectionCounter);
            };
            //getting transaction list based on pagination
            $scope.getTransactionByPagination = function (num) {
                $scope.searchInfo.offset = getOffset(num);
                transctionService.getTransactionByPagination($scope.searchInfo).
                        success(function (data, status, headers, config) {
                            $scope.transctionInfoList = data.transaction_list;
                            //usually total amount is not changed for pagination, so this line has no actual effect unless anything is chnaged from server.
                            $scope.totalAmount = data.total_amount;
                            //updating current page tomar amount value based on transaction list at new page
                            getCurrentPageTransctionAmount();
                        });
            };
            //setting transaction info
            $scope.setTransactionInfo = function (transactionInfo) {
                $scope.transactionInfo = JSON.parse(transactionInfo);
            };
            //updating transaction info
            $scope.updateTransction = function (callbackFunction) {
                if ($scope.allow_action == false) {
                    return;
                }
                $scope.allow_action = false;
                transctionService.updateTransction($scope.transactionInfo).
                        success(function (data, status, headers, config) {
                            $scope.allow_action = true;
                            callbackFunction(data);
                        });
            };
            $scope.setServiceList = function (serviceList) {
                $scope.serviceList = JSON.parse(serviceList);
            };
            $scope.checkAll = function () {
                if ($scope.selectedAll) {
                    $scope.selectedAll = true;
                } else {
                    $scope.selectedAll = false;
                }
                angular.forEach($scope.serviceList, function (service) {
                    if (service.status != 0 || service.status != "0") {
                        service.selected = $scope.selectedAll;
                    }
                }, $scope.serviceList);
            };
            $scope.toggleSelection = function toggleSelection(service) {
                var serviceIndex = $scope.serviceList.indexOf(service);
                $scope.serviceList[serviceIndex] = service;
            };

            $scope.loadBalance = function (balanceInfo, callbackFunction) {
                if ($scope.allow_action == false) {
                    return;
                }
                $scope.allow_action = false;
                transctionService.loadBalance(balanceInfo).
                        success(function (data, status, headers, config) {

                            $scope.allow_action = true;
                            callbackFunction(data);
                        });
            };
            //getting transaction list
            $scope.getTransactionList = function (startDate, endDate) {
                if ($scope.allow_action == false) {
                    return;
                }
                $scope.allow_action = false;
                $scope.searchInfo.limit = $scope.allTransactions;
                if (startDate != "" && endDate != "") {
                    $scope.searchInfo.fromDate = startDate;
                    $scope.searchInfo.toDate = endDate;
                }
                transctionService.getTransactionList($scope.searchInfo).
                        success(function (data, status, headers, config) {
                            $scope.transctionInfoList = data.transaction_list;
                            if ($scope.allTransactions != false) {
                                $scope.pageSize = data.total_transactions;
                                $scope.allTransactions = false;
                            }
                            setCollectionLength(data.total_transactions);
                            $scope.allow_action = true;
                            $scope.totalAmount = data.total_amount;
                            getCurrentPageTransctionAmount();
                        });
            };

            //pagination
            function getOffset(number) {
                var initIndex;
                initIndex = $scope.pageSize * (number - 1);
                return initIndex;
            }
            //updating current page transaction amount value
            function getCurrentPageTransctionAmount() {
                var currentPageAmount = 0;
                for (var i = 0; i < $scope.transctionInfoList.length; i++) {
                    currentPageAmount = currentPageAmount + +$scope.transctionInfoList[i].amount;
                }
                $scope.currentPageAmount = currentPageAmount;
            }


        });



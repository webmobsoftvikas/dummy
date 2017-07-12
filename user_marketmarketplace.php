<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class user_marketmarketplace extends CI_Controller {

    function __construct() {
       
        error_reporting(0);

        parent::__construct();

        @session_start();

        $this->load->helper('url');

        $this->load->model('m_usermarketmarketplace');

        $this->load->library('pagination');

        if (!isset($_SESSION['language'])) {

            $_SESSION['language'] = defaultLanguage;
        }
        if (!isset($_SESSION['user_id'])) {

            redirect(base_url());
        }
        if (!isset($_SESSION['user_name'])) {

            redirect(base_url());
        }

        $this->lang->load('borrowerHome', $_SESSION['language']);
        date_default_timezone_set('Europe/Zurich');

        $dateTime = date("Y-m-d H:i:s");
        $time = date('h:i', strtotime($dateTime));

        if ($time == "22:00") {
            session_destroy();
        }
        unset($_SESSION['currencrType']);
        $_SESSION['currencrType'] = "CHF";
        
                $_SESSION['openTab'] = "MMmchf"; //for make button active
        
            $this->load->model("m_instimatch");
            $userId = $_SESSION['user_id'];
            $getFlagLoginNumber = $this->m_instimatch->checkLoginActive($userId);
            $login_flag  = $getFlagLoginNumber['login_flag'];
            $loginFlag   = $_SESSION['randomFlagNumber'];
            
            if($login_flag != $loginFlag)
            {
                session_destroy();
                //$this->load->view('v_login');
                echo "<script>location.reload(); </script>";
            }
        
        
    }

 function bid_spread() {
        $third_party = $_POST['offerId'];
        $bid_value = $_POST['bid_value'];
        $user_id = $_SESSION['user_id'];
        $cname = $_POST['cname'];
        $currencyType = $_SESSION['currencrType'];
        $resetOffer = $this->m_usermarketmarketplace->bid_spread($third_party,$user_id,$currencyType,$bid_value,$cname);
        echo json_encode($resetOffer);
    }

     function offer_spread() {
        $third_party = $_POST['offerId'];
        $bid_value = $_POST['bid_value'];
        $user_id = $_SESSION['user_id'];
        $cname = $_POST['cname'];
        $currencyType = $_SESSION['currencrType'];
        $resetOffer = $this->m_usermarketmarketplace->offer_spread($third_party,$user_id,$currencyType,$bid_value,$cname);
        echo json_encode($resetOffer);
    }

    function check_bid() {
        $third_party = $_POST['offerId'];
         $cname = $_POST['cname'];
        $user_id = $_SESSION['user_id'];
        $currencyType = $_SESSION['currencrType'];
        $resetOffer = $this->m_usermarketmarketplace->check_bid($third_party,$user_id,$currencyType,$cname);
        echo json_encode($resetOffer);
    }
     function uncheck_bid() {
        $third_party = $_POST['offerId'];
        $cname = $_POST['cname'];
        $user_id = $_SESSION['user_id'];
        $currencyType = $_SESSION['currencrType'];
        $resetOffer = $this->m_usermarketmarketplace->uncheck_bid($third_party,$user_id,$currencyType,$cname);
        echo json_encode($resetOffer);
    }
 function check_offer() {
        $third_party = $_POST['offerId'];
        $cname = $_POST['cname'];
        $user_id = $_SESSION['user_id'];
        $currencyType = $_SESSION['currencrType'];
        $resetOffer = $this->m_usermarketmarketplace->check_offer($third_party,$user_id,$currencyType,$cname);
        echo json_encode($resetOffer);
    }
     function uncheck_offer() {
        $third_party = $_POST['offerId'];
        $cname = $_POST['cname'];
        $user_id = $_SESSION['user_id'];
        $currencyType = $_SESSION['currencrType'];
        $resetOffer = $this->m_usermarketmarketplace->uncheck_offer($third_party,$user_id,$currencyType,$cname);
        echo json_encode($resetOffer);
    }
    public function index() {

    
    $user_id = $_SESSION['user_id'];

        $_SESSION['type'] = "MM";
        $buttonId = 0;

        $this->load->model('m_usermarketmarketplace');


        $checkUserStatus = $this->m_usermarketmarketplace->checkUserStatus($user_id);
        $userStatus = $checkUserStatus[0]['status'];

       $currencyType = $_SESSION['currencrType'];

        $result['marketRequests'] = $this->m_usermarketmarketplace->marketRequests($user_id, $currencyType);
        $result['allMarketMoney'] = $this->m_usermarketmarketplace->allMarketMoney();

        $result['getmarketMoney'] = $this->m_usermarketmarketplace->getmarketMoney($buttonId);

        foreach ($result['getmarketMoney'] as $marketMoney) {

            $result['countRequests'][] = $this->m_usermarketmarketplace->getRequestCount($marketMoney['value'], $currencyType);
            $result['currencyTValue'][] = $this->m_usermarketmarketplace->getCurrencyValueOfFirstRequest($marketMoney['value'], $currencyType);
            $result['allOffersForTerms'][] = $this->m_usermarketmarketplace->allOffersForTerms($marketMoney['value'], $currencyType);
        }

        $currencyType = $_SESSION['currencrType'];

        /* ------------------- all open requests/offers/bids start------------------- */

       // echo "<pre>";
       // print_r($result['allMarketMoney']);
       // die;
        
       $result['marketsettings'] = $this->m_usermarketmarketplace->marketsettings($user_id, $currencyType);
        
        
        $openOrder = $this->m_usermarketmarketplace->openOrders($user_id, $currencyType);

        //$myOpenResponcesForRequest       =  $this->m_usermarketmarketplace->myOpenResponcesForRequest($user_id,$currencyType);

        $myOpenRequestsForOffers = $this->m_usermarketmarketplace->myOpenRequestsForOffers($user_id, $currencyType);

        //$myRequestsForAmountOffers        = $this->m_usermarketmarketplace->myRequestsForAmountOffers($user_id,$currencyType); //borrower ask for money by offer

        if ($userStatus == 'y') {
            //$result['openOrders']            = array_merge($openOrder,$myOpenResponcesForRequest,$myOpenRequestsForOffers, $myRequestsForAmountOffers);
            //$result['openOrders'] = array_merge($openOrder, $myOpenRequestsForOffers);
            $result['openOrders'] = array_merge( $myOpenRequestsForOffers,$openOrder);
        } else {
            $result['openOrders'] = array();
        }
////keep offers

        $keepOrder = $this->m_usermarketmarketplace->keepOrders($user_id, $currencyType);

        //$myOpenResponcesForRequest       =  $this->m_usermarketmarketplace->myOpenResponcesForRequest($user_id,$currencyType);

        $keepOffers = $this->m_usermarketmarketplace->keepForOffers($user_id, $currencyType);

        //$myRequestsForAmountOffers        = $this->m_usermarketmarketplace->myRequestsForAmountOffers($user_id,$currencyType); //borrower ask for money by offer

        if ($userStatus == 'y') {
            //$result['openOrders']            = array_merge($openOrder,$myOpenResponcesForRequest,$myOpenRequestsForOffers, $myRequestsForAmountOffers);
            //$result['openOrders'] = array_merge($openOrder, $myOpenRequestsForOffers);
            $result['keepOrders'] = array_merge( $keepOrder,$keepOffers);
        } else {
            $result['keepOrders'] = array();
        }
     

        /* sort open array by request id and byOrder */
        $myarrayOpenOrder = $result['openOrders'];
        foreach ($myarrayOpenOrder as $c => $key) {
            $dateTime[] = $key['by_order'];
            $requestId[]   = $key['requestId'];
        }

        array_multisort($dateTime, SORT_ASC, 
                $requestId,SORT_DESC,
                $myarrayOpenOrder);

        $result['openOrders'] = $myarrayOpenOrder;
     
        
        
       
 
        /* ------------------- all open requests/offers/bids ends------------------- */

        /* -------------wait aacptance orders---------------------------- */
        $myWaitAccptance = $this->m_usermarketmarketplace->myOpenResponcesForRequest($user_id, $currencyType);

        $myRequestsForAmountOffers = $this->m_usermarketmarketplace->myRequestsForAmountOffers($user_id, $currencyType);

        $megeWaitAcceptanceArray = array_merge($myWaitAccptance, $myRequestsForAmountOffers);

        $result['myWaitAccptance'] = $megeWaitAcceptanceArray;

        /* -------------wait acceptance ends---------------------------- */

        /* ------------------- all completed requests/offers/bids start------------------- */
        $completedOrder = $this->m_usermarketmarketplace->acceptedRequests($user_id, $currencyType);

        $myAcceptedOrder = $this->m_usermarketmarketplace->myAcceptedOrder($user_id, $currencyType);


        if ($userStatus == 'y') {
            $result['completedOrders'] = array_merge($completedOrder, $myAcceptedOrder); // accepted request by me and for me
        } else {
            $result['completedOrders'] = array();
        }

        /* sort completed array by time descending */
        $myarray = $result['completedOrders'];


        // sort array by id descending
        foreach ($myarray as $key => $row) {
            $mid[$key] = $row['time_accept'];
        }

        array_multisort($mid, SORT_DESC, $myarray);


        $result['completedOrders'] = $myarray;

        /* ------------------- all completed requests/offers/bids ends------------------- */


        /* ------------------- all cancel requests/offers/bids start------------------- */
        $cancelMyRequests = $this->m_usermarketmarketplace->cancelMyRequests($user_id, $currencyType);

        $canceledMyOffers = $this->m_usermarketmarketplace->canceledMyOffers($user_id, $currencyType);
        //$borrowerDeniedToAccept          = $this->m_usermarketmarketplace->borrowerDeniedToAccept($user_id,$currencyType);
        //$lenderDealsDenied               = $this->m_usermarketmarketplace->lenderDealsDenied($user_id,$currencyType);      
        //$lenderDeniedOfferRequest        = $this->m_usermarketmarketplace->lenderDeniedOfferRequest($user_id,$currencyType);  //cancel offers requests details lender side
        //$borrowerDeniedOfferRequest      = $this->m_usermarketmarketplace->borrowerDeniedOfferRequest($user_id,$currencyType);  //cancel offers requests details borrower side
        if ($userStatus == 'y') {
            $canceledOrders = array_merge($cancelMyRequests, $canceledMyOffers);
        } else {
            $canceledOrders = array();
        }



        $myCancelArray = $canceledOrders;


        // sort array by time descending
        foreach ($myCancelArray as $key => $row) {
            $mid[$key] = $row['withdrawTime'];
        }

        array_multisort($mid, SORT_DESC, $myCancelArray);


        $result['cancelOrders'] = $myCancelArray;



        /* ------------------- all cancel requests/offers/bids ends------------------- */

        /* --------------------Denied or timed out start-------------------------------------- */

        //bid sides
        $borrowerDeniedToAccept = $this->m_usermarketmarketplace->borrowerDeniedToAccept($user_id, $currencyType);  // borrower denied to accept
        $lenderDealsDenied = $this->m_usermarketmarketplace->lenderDealsDenied($user_id, $currencyType);
        $timedOutOrdersForBorrower = $this->m_usermarketmarketplace->timedOutOrdersForBorrower($user_id, $currencyType);  //timed out bid side for borrower
        $timedOutOrdersForLender = $this->m_usermarketmarketplace->timedOutOrdersForLender($user_id, $currencyType);    //timed out bid side for lender
        //offer sides
        $timedOutOfferOrderForBorrower = $this->m_usermarketmarketplace->timedOutOfferOrderForBorrower($user_id, $currencyType);  //timed out bid side for borrower
        $timedOutOfferOrderForLender = $this->m_usermarketmarketplace->timedOutOfferOrderForLender($user_id, $currencyType);  //timed out bid side for lender
        //print_r($timedOutOfferOrderForLender);
        //die;
        $lenderDeniedOfferRequest = $this->m_usermarketmarketplace->lenderDeniedOfferRequest($user_id, $currencyType);  //cancel offers requests details lender side


        $borrowerDeniedOfferRequest = $this->m_usermarketmarketplace->borrowerDeniedOfferRequest($user_id, $currencyType);  //cancel offers requests details borrower side

        $mergeTimedOutArrays = array_merge($timedOutOrdersForBorrower, $timedOutOrdersForLender, $borrowerDeniedToAccept, $lenderDealsDenied, $lenderDeniedOfferRequest, $borrowerDeniedOfferRequest, $timedOutOfferOrderForBorrower, $timedOutOfferOrderForLender);

        if ($userStatus == 'y') {
            $result['timedOuts'] = $mergeTimedOutArrays;
        } else {
            $result['timedOuts'] = array();
        }



        $myToArray = $result['timedOuts'];


        // sort array by time descending
        foreach ($myToArray as $key => $row) {
            $mid[$key] = $row['deniedDate'];
        }

        array_multisort($mid, SORT_DESC, $myToArray);


        $result['timedOut'] = $myToArray;

        //echo "<pre>";
        //print_r($result['timedOut'] );
        //die;
        /* --------------------Denied or timed out ends -------------------------------------- */

        $result['publicHolidays'] = $this->m_usermarketmarketplace->publicHolidays();
        $result['publicHolidaysEur'] = $this->m_usermarketmarketplace->publicHolidaysEur();

        $this->load->model("m_vendor");
        $result['privilege'] = $this->m_vendor->userDetails();
        $result['formBtnPrivilege'] = $this->m_vendor->userFormBtnPrivileges();


        $dataValue = "Broken Date";
        $result['countBrokenRequests'] = $countRequests = $this->m_usermarketmarketplace->getRequestCount1($dataValue, $currencyType);
        $result['countBrokenoffers'] = $countRequests = $this->m_usermarketmarketplace->getOffersCount($dataValue, $currencyType);

        $this->load->view('header_market', $result);
        $this->load->view('v_vendorMarket', $result);
        $this->load->view('footer');
    }

    public function deleteResponceOffer() {
        $responseId = $_POST['responseId'];
        $deleteResponce = $this->m_usermarketmarketplace->deleteResponceOffer($responseId);
    }

    public function editMyResponseOffer() {
        $responseId = $_POST['responseId'];
        $responseDetail = $this->m_usermarketmarketplace->responseDetailOffer($responseId);
        echo json_encode($responseDetail);
    }

    public function editSubmitBidOffer() {

        $editedOfferResponceId = $_POST['editedOfferResponceId'];
        $amount = $_POST['bid'];
        $money_text = $_POST['money_text'];
        $updatedArray = array("amount_demand" => $amount . " " . $money_text);
        $updatedOfferResponse = $this->m_usermarketmarketplace->updatedOfferResponseupdatedOfferResponse($updatedArray, $editedOfferResponceId);
    }

    public function editRequest() {
        $requestId = $_POST['requestId'];
        $requestedCred = $this->m_usermarketmarketplace->editRequest($requestId);
        echo json_encode($requestedCred);
    }

    public function deniedRequestOffr() {

        $responceId = $_POST['responceId'];
        $responseDetail = $this->m_usermarketmarketplace->responseAmountDetail($responceId);

        $amountOfResponse = $responseDetail['amount'];
        $requestId = $responseDetail['request_id'];

        $amountOfRequestDetails = $this->m_usermarketmarketplace->amountOfRequestDetails($requestId);
       $amountOfRequestDetails1 = $this->m_usermarketmarketplace->amountOfRequestDetails1($requestId);
        $requestCurrentAmnt = $amountOfRequestDetails['amount'];

        $explodeAmountArry = explode(" ", $amountOfResponse);
        $amount = $explodeAmountArry[0];
        $currency = $explodeAmountArry[0];


        $currencyAfterDeny = $requestCurrentAmnt + $amount;

        $updateRequestAray = array("amount" => $currencyAfterDeny, "amount_display" => $currencyAfterDeny . " mio");

        $addCurrencyBackToRequestedAmnt = $this->m_usermarketmarketplace->updateRequestAmount($updateRequestAray, $requestId);

         echo json_encode($amountOfRequestDetails1);

       // print_r($amountOfRequestDetails1);
       
        $notificationDetails = $this->m_usermarketmarketplace->notificationDetails($responceId);
        $to = $notificationDetails[0]['from'];

        $from = $notificationDetails[0]['to'];
        $requestId = $notificationDetails[0]['req_id'];
        $responseId = $notificationDetails[0]['responce_id'];

        $dataVal = array("req_id" => $requestId, "responce_id" => $responseId, "to" => $to, "from" => $from);
        $deniedRequestOffr = $this->m_usermarketmarketplace->deniedRequestOffr($responceId);

        $notificationReaded = $this->m_usermarketmarketplace->notificationReaded($responceId);

        $insertNotification = $this->m_usermarketmarketplace->notificationSend($dataVal);
    }

    public function deniedRequestOffrResponce() {

        $responceId = $_POST['responceId'];

        $getAmountOfResponce = $this->m_usermarketmarketplace->getOfferIdOfResponse($responceId);
        if (count($getAmountOfResponce) > 0) {
            $dateTime = date("Y-m-d H:i:s");
            $offerId = $getAmountOfResponce['offer_id'];
            $totalAmountInOfferTable = $this->m_usermarketmarketplace->getTotalAmountInofferTbl($offerId);
            $amntInOfferTable = $totalAmountInOfferTable['amount'];

            $explodeAmntOffer = explode(" ", $amntInOfferTable);
            $amountOffered = $explodeAmntOffer[0];
            $amountInResponse = $getAmountOfResponce['amount_demand'];

            $explodeAmntResponse = explode(" ", $amountInResponse);
            $responseAmount = $explodeAmntResponse[0];

            $totalAmountAfterTimedOut = $amountOffered + $responseAmount;

            $updateAmountArray = array("amount" => $totalAmountAfterTimedOut . " mio");

            $updateAmountAfterTimeOut = $this->m_usermarketmarketplace->updateTblMarketOffer($updateAmountArray, $offerId);

            $timeOutArray = array("is_accepted" => "to", "status" => "closed", "deniedDate" => $dateTime);
            $responseTimeOutOffer = $this->m_usermarketmarketplace->responseTimeOutOffer($timeOutArray, $responceId);

            $deniedRequestOffr = $this->m_usermarketmarketplace->deniedRequestOffrResponce($responceId);
        }
    }

   

    public function fullyAcceptedOfferConfirm() {

        $dateTimeInsterd = date("Y-m-d H:i:s");

        $user_id = $_SESSION['user_id'];
        $responceId = $_POST['responceId'];

        $fullDetailOfOffer = $this->m_usermarketmarketplace->marketOfferDetail($responceId);



        $responseArr = array(
            "offer_id" => $responceId,
            "borrower_id" => $user_id,
            "lender_id" => $fullDetailOfOffer[0]['lender_id'],
            "amount_demand" => $fullDetailOfOffer[0]['amount'],
            "time_inserted" => $dateTimeInsterd
        );



        $this->m_usermarketmarketplace->submitPartiallyAmount($responseArr);
    }

    //function acceptDeal( function acceptOffer make acceptDeal)

    public function acceptDeal1() {

        $responceId = $_POST['responceId'];



        $maturyOfResponse = $this->m_usermarketmarketplace->maturyOfResponse($responceId);



        $responseTerm = $maturyOfResponse[0]['term'];

        $responseAmount = $maturyOfResponse[0]['amount'];

        $responseOfferRate = $maturyOfResponse[0]['offer_rate'] . "%";

        $lenderId = $maturyOfResponse[0]['lender_id'];

        $user_id = $_SESSION['user_id'];

        $date = date("d.m.Y");

        //get the request id if the borrower accepted the request 

        $requestIdForResponse = $this->m_usermarketmarketplace->requestIdForResponse($user_id, $responseTerm);





        $this->m_usermarketmarketplace->acceptedResponseOffer($responceId);

        $acceptedResponse = array("response_id" => $responceId, "borrower_id" => $user_id, "lender_id" => $lenderId, "ammount_accepted" => $responseAmount, "interest_rate" => $responseOfferRate, "accepted_term" => $responseTerm, "date_accepted" => $date);

        $this->m_usermarketmarketplace->accepetFullDeal($acceptedResponse);
    }

    function loanRequestMarket() {



        $user_id = $_SESSION['user_id'];

        $req_name = $_POST['req_name'];

        $currency = $_SESSION['currencrType'];
        
       $amount = $_POST['amount'];

        $money_text = $_POST['money_text'];



        $amountDisplay = $amount . " " . $money_text;

        $maturityMonthss = $_POST['maturityMonthss'];

        $maturity = $_POST['maturity'];

        $minimum_bid = $_POST['minimum_bid'];

        $notes = "";

        $today = date("Y-m-d H:i:s");


        $meturityType = $_POST['selectedTypeMuturity'];


        if ($meturityType == "0") {
            /* if user select date by selected dropdown */
            /* button id 0 for 1 week-3months............button id 2 for 4 months to 12 months  */
            $dataVAlues = array('borrower_id' => $user_id, 'req_name' => $req_name, 'amount' => $amount, 'amount_display' => $amountDisplay, 'currency' => $currency, 'maturity' => $maturity,
                'min_bid' => $minimum_bid, 'term' => $maturityMonthss, 'notes' => $notes, "on_date" => $today, 'currency_type' => $_SESSION['currencrType']);
        } else {
    /*broken dates */
            $valueDate  = strtotime($_POST['value_date']); // or your date as well
            $matDate    = strtotime($maturity);
            $datediff = $matDate - $valueDate;

            $numberOdDays =  floor($datediff / (60 * 60 * 24));


            $dataVAlues = array('borrower_id' => $user_id, 'req_name' => $req_name, 'amount' => $amount, 'amount_display' => $amountDisplay, 'currency' => $currency, 'maturity' => $maturity,
                'min_bid' => $minimum_bid, 'term' => "Broken Date", 'notes' => $notes, "on_date" => $today, 'currency_type' => $_SESSION['currencrType'], 'value_date' => $_POST['value_date'],"numberOfDays" => $numberOdDays);
        
            
        }


        $marketRequestInsert = $this->m_usermarketmarketplace->marketRequestInsert($dataVAlues);

        $checkExistingRequst = $this->m_usermarketmarketplace->checkExistingRequstForMaturity($maturityMonthss);



        if (count($checkExistingRequst) == "0") {

            $this->m_usermarketmarketplace->updateRequestForMaturity($marketRequestInsert);
        }



        $buttonsId = $this->m_usermarketmarketplace->getButtonId($maturityMonthss);

        $count = count($buttonsId);
        if ($count == "0") {
            $buttonId = "2";
        } else {
            $buttonId = $buttonsId[0]['buttons_id'];
        }
        $tabs = $buttonsId[0]['id'];
        // redirect(base_url() . "user_marketmarketplaceEur");

        $base_url = base_url();

        header('Location: ' . $base_url . 'user_marketmarketplace?buttonId=' . $buttonId . '&tab='.$tabs);
    }

    public function deleteRequest() {
        /*  archiev only (cancel request) */
        $reqId = $_POST['reqId'];

        $deleterequest = $this->m_usermarketmarketplace->deleteRequest($reqId);
    }

  public function keepbid() {
        /*  archiev only (cancel request) */
        $reqId = $_POST['reqId'];

        $keepit = $this->m_usermarketmarketplace->keepbid($reqId);
    }

    public function keepoffer() {
        /*  archiev only (cancel request) */
        $reqId = $_POST['reqId'];

        $keepit = $this->m_usermarketmarketplace->keepoffer($reqId);
    }

    public function updateMarketRequest() {



        $u_req_id = $_POST['u_req_id'];



        $currency = $_POST['u_currency'];

        $u_amount = $_POST['u_amount'];

        $u_money_text = $_POST['u_money_text'];

        $u_maturityMonthss = $_POST['u_maturityMonthss'];



        $u_maturity = $_POST['u_maturity'];

        $u_minimum_bid = $_POST['u_minimum_bid'];

        $notes = "";



        $termOfEditedRequest = $this->m_usermarketmarketplace->termOfEditedRequest($u_req_id);

        $term = $termOfEditedRequest[0]['term'];

        $isBestOffer = $termOfEditedRequest[0]['is_bestOffer'];

        $today = date("Y-m-d H:i:s");

        if ($isBestOffer == "y") {



            $nextTermBestOfferOn = $this->m_usermarketmarketplace->nextTermBestOffer($u_req_id, $term);



            $nextIdTerm = $nextTermBestOfferOn[1]['id'];



            if ($nextIdTerm == $u_req_id) {

                $nextID = $nextTermBestOfferOn[0]['id'];
            } else {

                $nextID = $nextTermBestOfferOn[1]['id'];
            }

            $makeNextTermAsBestOffer = $this->m_usermarketmarketplace->makeNextTermAsBestOffer($nextID);
        }



        $updateRequestBestOffer = $this->m_usermarketmarketplace->updateRequestBestOffer($u_req_id);


        $u_selectBd = $_POST['u_selectBd'];
        if ($u_selectBd == "0") {
            $dataValues = array("amount" => $u_amount
                , "amount_display" => $u_amount . " " . $u_money_text
                
                , "maturity" => $u_maturity
                , "min_bid" => $u_minimum_bid
                , "term" => $u_maturityMonthss
                , "notes" => $notes
                , "on_date" => $today
            );
        } else {
            $dataValues = array("amount" => $u_amount
                , "amount_display" => $u_amount . " " . $u_money_text
               
                , "maturity" => $u_maturity
                , "min_bid" => $u_minimum_bid
                , "term" => "Broken Date"
                , "notes" => $notes
                , "on_date" => $today
            );
        }



        $updateMarketRequest = $this->m_usermarketmarketplace->updateMarketRequest($u_req_id, $dataValues);



        $checkForBestOffer = $this->m_usermarketmarketplace->checkForBestOffer($u_maturityMonthss);

        if (count($checkForBestOffer) == "0") {

            $updateEditedBestOffer = $this->m_usermarketmarketplace->updateEditedBestOffer($u_req_id);
        }
    }

    public function submitBidOffer() {

        $valueDate = $_POST['myValueDate'];
        $maturityDate = $_POST['myMaturityDate'];

        $selectedMinutes = $_POST['validMinutes'];
        $selectedSeconds = $_POST['validSeconds'];
        $requestid = $_POST['rquest_id'];
        $checkBorrowerId = $this->m_usermarketmarketplace->checkBorrowerId($requestid);
        $borowerId = $checkBorrowerId[0]['borrower_id'];
        $requestAmount = $checkBorrowerId[0]['amount'];
        $interestRate = $_POST['offerinterestRate'];
        $bidAmount = $_POST['bid'];
        $money_text = $_POST['money_text'];
        $user_id = $_SESSION['user_id'];


        //$checkOffer = $this->m_usermarketmarketplace->checkOffer($user_id,$requestid);

        $today = date('d.m.Y');

        $startTime = date("Y-m-d H:i:s");
        $cenvertedTime = date('Y-m-d H:i:s', strtotime('+' . $selectedMinutes . ' minutes +' . $selectedSeconds . ' seconds', strtotime($startTime)));
        $dataValues = array(
            'request_id' => $requestid,
            'lender_id' => $user_id,
            'amount' => $bidAmount . " " . $money_text,
            'int_rate' => $interestRate,
            'date' => $today,
            'value_date' => $valueDate,
            'maturity_date' => $maturityDate,
            'time_inserted' => $startTime,
            'valid_until' => $cenvertedTime
        );

        $addOffer = $this->m_usermarketmarketplace->addOffer($dataValues);

        $_SESSION['responceId'] = $addOffer;

        $restingAmout = $requestAmount - $bidAmount;
        $updateRequestArray = array("amount" => $restingAmout, "amount_display" => $restingAmout . " mio");

        $updateRequestAmount = $this->m_usermarketmarketplace->updateRequestAmount($updateRequestArray, $requestid);

        $dataVal = array("req_id" => $requestid, "from" => $user_id, "msg" => "hello", "to" => $borowerId, "responce_id" => $addOffer);

        $notificationSend = $this->m_usermarketmarketplace->notificationSend($dataVal);


        //calculate the difference between times

        $start = date_create("$startTime");
        $end = date_create("$cenvertedTime");
        $diff = date_diff($end, $start);

        //$hour = $diff->h;
        $minutes = $diff->i;
        if ($minutes < 10) {
            $mnts = "0" . $minutes;
        } else {
            $mnts = $minutes;
        }
        $seconds = $diff->s;
        if ($seconds < 10) {
            $secnds = "0" . $seconds;
        } else {
            $secnds = $seconds;
        }

        echo $time = $mnts . ":" . $secnds;
    }

    function timedOutBid() {
        //timed out
        $dateTime = date("Y-m-d H:i:s");
        $responceId = $_SESSION['responceId'];
        $timedOutArray = array("status" => "closed", "request_status" => "to", "deniedDate" => $dateTime);

        $timedOutResponse = $this->m_usermarketmarketplace->timedOutResponse($responceId, $timedOutArray);
        if ($timedOutResponse != 0) {
            $responceId = $timedOutResponse;
            $ammountOfCurrentResponse = $this->m_usermarketmarketplace->ammountOfCurrentResponse($responceId);



            $requestId = $ammountOfCurrentResponse['request_id'];
            $amountOfrequest = $this->m_usermarketmarketplace->amountOfRequestDetails($requestId); // get amouunt of request whose response timed out
            $requestAmount = $amountOfrequest['amount'];


            $responseAmount = $ammountOfCurrentResponse['amount'];  // get amouunt of response which timed out
            $explodeAmount = explode(" ", $responseAmount);

            $amount = $explodeAmount[0];

            $addAmountAfterTimedOut = $requestAmount + $amount;

            $updateRequestArray = array("amount" => $addAmountAfterTimedOut, "amount_display" => $addAmountAfterTimedOut . " mio");


            $updateAmountRequest = $this->m_usermarketmarketplace->updateRequestAmount($updateRequestArray, $requestId);
        }
    }

    function timedOutForResponse() {
        $dateTime = date("Y-m-d H:i:s");

        $responceId = $_POST['responseId'];
        $timedOutArray = array("status" => "closed", "request_status" => "to", "deniedDate" => $dateTime);

        $timedOutResponse = $this->m_usermarketmarketplace->timedOutResponse($responceId, $timedOutArray);
        if ($timedOutResponse != 0) {
            $responceId = $timedOutResponse;
            $ammountOfCurrentResponse = $this->m_usermarketmarketplace->ammountOfCurrentResponse($responceId);



            $requestId = $ammountOfCurrentResponse['request_id'];
            $amountOfrequest = $this->m_usermarketmarketplace->amountOfRequestDetails($requestId); // get amouunt of request whose response timed out
            $requestAmount = $amountOfrequest['amount'];


            $responseAmount = $ammountOfCurrentResponse['amount'];  // get amouunt of response which timed out
            $explodeAmount = explode(" ", $responseAmount);

            $amount = $explodeAmount[0];

            $addAmountAfterTimedOut = $requestAmount + $amount;

            $updateRequestArray = array("amount" => $addAmountAfterTimedOut, "amount_display" => $addAmountAfterTimedOut . " mio");


            $updateAmountRequest = $this->m_usermarketmarketplace->updateRequestAmount($updateRequestArray, $requestId);
        }
    }

    function allTimedOutRequestsByAjax() {
        $currencyType = $_SESSION['currencrType'];
        $user_id = $_SESSION['user_id'];
        $borrowerDeniedToAccept = $this->m_usermarketmarketplace->borrowerDeniedToAccept($user_id, $currencyType);  // borrower denied to accept
        $lenderDealsDenied = $this->m_usermarketmarketplace->lenderDealsDenied($user_id, $currencyType);
        $timedOutOrdersForBorrower = $this->m_usermarketmarketplace->timedOutOrdersForBorrower($user_id, $currencyType);  //timed out bid side for borrower
        $timedOutOrdersForLender = $this->m_usermarketmarketplace->timedOutOrdersForLender($user_id, $currencyType);    //timed out bid side for lender
        //offer sides
        $timedOutOfferOrderForBorrower = $this->m_usermarketmarketplace->timedOutOfferOrderForBorrower($user_id, $currencyType);  //timed out bid side for borrower
        $timedOutOfferOrderForLender = $this->m_usermarketmarketplace->timedOutOfferOrderForLender($user_id, $currencyType);  //timed out bid side for lender
        //print_r($timedOutOfferOrderForLender);
        //die;
        $lenderDeniedOfferRequest = $this->m_usermarketmarketplace->lenderDeniedOfferRequest($user_id, $currencyType);  //cancel offers requests details lender side


        $borrowerDeniedOfferRequest = $this->m_usermarketmarketplace->borrowerDeniedOfferRequest($user_id, $currencyType);  //cancel offers requests details borrower side

        $mergeTimedOutArrays = array_merge($timedOutOrdersForBorrower, $timedOutOrdersForLender, $borrowerDeniedToAccept, $lenderDealsDenied, $lenderDeniedOfferRequest, $borrowerDeniedOfferRequest, $timedOutOfferOrderForBorrower, $timedOutOfferOrderForLender);





        $myToArray = $mergeTimedOutArrays;


        // sort array by time descending
        foreach ($myToArray as $key => $row) {
            $mid[$key] = $row['deniedDate'];
        }

        array_multisort($mid, SORT_DESC, $myToArray);


        $allTimedOutArrays = $myToArray;



        if (count($allTimedOutArrays) > 0) {
            foreach ($allTimedOutArrays as $order) {

                //timed out or denied
                if (isset($order['request_status']) && $order['request_status'] == "to") {
                    $responseStatus = "Time out";
                } else {
                    $responseStatus = "Denied";
                }

                //offer or bid
                if (isset($order['offerResponseId'])) {
                    $responseType = "offer";
                } else {
                    $responseType = "bid";
                }


                //market maker company
                if (isset($order['borrowerCompany'])) {
                    $marketMaker = $order['borrowerCompany'];
                } else {
                    $marketMaker = $_SESSION['company_name'];
                }


                if (isset($order['lenderCompany'])) {
                    $aggressor = $order['lenderCompany'];
                } else {
                    $aggressor = $_SESSION['company_name'];
                }








                $timedOutOrder = '<tr style="width:100% !important;text-align:center;">
                            <td  style="border:2px solid #fff;width:20%;">' . $responseStatus . '</td> 
                            <td  style="border:2px solid #fff;width:20%;">' . $order['term'] . '</td> 
                            <td  style="border:2px solid #fff;width:10%;">' . $responseType . '</td> 
                            <td  style="border:2px solid #fff;width:20%;" title="Market maker">' . $marketMaker . '</td> 
                            <td  style="border:2px solid #fff;width:30%;">' . $order['amount'] . '</td>
                            <td  style="border:2px solid #fff;width:25%;">' . str_replace('%', '', $order['int_rate']) . '%</td>
                            <td  style="border:2px solid #fff;width:30%;" title="Agressor">' . $aggressor . '</td>                          
                            <td  style="border:2px solid #fff;width:35%;">' . date("d.m.Y H:i:s", strtotime($order['deniedDate'])) . '</td>
                         </tr>';

                echo $timedOutOrder;
            }
        } else {
            echo "0";
        }
    }

    public function submitOfferByForm() {

    $user_id = $_SESSION['user_id'];
        $req_name = $_POST['offer_req_name'];
        $currency = $_SESSION['currencrType'];
        $amount = $_POST['offer_amount'];
        $money_text = $_POST['offer_money_text'];
        $offer_maturity = $_POST['offer_maturity'];
        $maturityMonthss = $_POST['offer_maturityMonthss'];
        $minimum_bid = $_POST['offer_minimum_bid'];

        $this->load->model('m_usermarketmarketplace');

        $date = date("d.m.Y");
        $offerBrokenDate = $_POST['offerBrokenDate'];

        if ($offerBrokenDate == "0") {
            /* if selected by select dropdown */
            $dataVal = array("lender_id" => $user_id,
                "lender_company" => $req_name,
                "term" => $maturityMonthss,
                "maturity_date" => $offer_maturity,
                "amount" => $amount . " " . $money_text,
                "currency" => $currency,
                "currency_type" => $_SESSION['currencrType'],
                "offer_rate" => $minimum_bid);
        } else {
            // broken date 
            
            
            $maturityDate = strtotime($offer_maturity);
            $valueDate    = strtotime($_POST['valueDateOffer']);

            $timeDiff = abs($maturityDate - $valueDate);

            $numberDays = $timeDiff/86400;  // 86400 seconds in one day

            // and you might want to convert to integer
            $numberDays = intval($numberDays);
            
            
            $dataVal = array("lender_id" => $user_id,
                "lender_company" => $req_name,
                "term" => "Broken Date",
                "maturity_date" => $offer_maturity,
                "amount" => $amount . " " . $money_text,
                "currency" => $currency,
                "currency_type" => $_SESSION['currencrType'],
        "value_date" => $_POST['valueDateOffer'],
                "offer_rate" => $minimum_bid ,
                "numberOfDays" => $numberDays
                    );
        }


        $submitOfferByForm = $this->m_usermarketmarketplace->submitOfferByForm($dataVal);
         $buttonsId = $this->m_usermarketmarketplace->getButtonId($maturityMonthss);

        $count = count($buttonsId);
        if ($count == "0") {
            $buttonId = "2";
        } else {
            $buttonId = $buttonsId[0]['buttons_id'];
        }
        $tabs = $buttonsId[0]['id'];
        // redirect(base_url() . "user_marketmarketplaceEur");

        $base_url = base_url();

        header('Location: ' . $base_url . 'user_marketmarketplace?buttonId=' . $buttonId . '&tab='.$tabs);
    }

    public function submitPartiallyAmount() {

        $valueDate = $_POST['myValueDate'];
        $maturityDate = $_POST['myMaturityDate'];

        $user_id = $_SESSION['user_id'];
        $responceId = $_POST['responceId'];
        $offerRate = $_POST['offerRate'];
        $partially_amount = $_POST['partially_amount'];
        $partialy_moneytext = $_POST['partialy_moneytext'];
        $lenderIdArr = $this->m_usermarketmarketplace->getLenderId($responceId);

        $lender_id = $lenderIdArr[0]['lender_id'];
        $totalAmountOffer = $lenderIdArr[0]['amount']; //yotal amount in tbl_offer
        $explodeTtlAmntOffer = explode(" ", $totalAmountOffer);
        $amountInOfferTable = $explodeTtlAmntOffer[0];

        $restAmountInOfferTAble = $amountInOfferTable - $partially_amount;


        $updateAmountArray = array("amount" => $restAmountInOfferTAble . " mio");


        $requestId = $responceId;
        $this->m_usermarketmarketplace->updateTblMarketOffer($updateAmountArray, $requestId);

        $selectedMinutes = $_POST['selectedMinutes'];
        $selectedSeconds = $_POST['selectedSeconds'];

        $startTime = date("Y-m-d H:i:s");
        $cenvertedTime = date('Y-m-d H:i:s', strtotime('+' . $selectedMinutes . ' minutes +' . $selectedSeconds . ' seconds', strtotime($startTime)));
        //echo $startTime.".........".$cenvertedTime;
        //die;
        $dataValues = array(
            "offer_id" => $responceId,
            "lender_id" => $lender_id,
            "amount_demand" => $partially_amount . " " . $partialy_moneytext,
            "amount_updated" => $partially_amount . " " . $partialy_moneytext,
            "borrower_id" => $user_id,
            "time_inserted" => $startTime,
            "value_date" => $valueDate,
            "maturity_date" => $maturityDate,
            "valid_until" => $cenvertedTime,
            "int_rate" => $offerRate
        );

        $submitPartialRequest = $this->m_usermarketmarketplace->submitPartiallyAmount($dataValues);
        $_SESSION['offerResponseId'] = $submitPartialRequest;
        $notificationReaded = $this->m_usermarketmarketplace->notificationReaded($responceId);



        //get the remaing time of this bid

        $start = date_create("$startTime");
        $end = date_create("$cenvertedTime");
        $diff = date_diff($end, $start);

        //$hour = $diff->h;
        $minutes = $diff->i;
        if ($minutes < 10) {
            $mnts = "0" . $minutes;
        } else {
            $mnts = $minutes;
        }
        $seconds = $diff->s;
        if ($seconds < 10) {
            $secnds = "0" . $seconds;
        } else {
            $secnds = $seconds;
        }

        echo $time = $mnts . ":" . $secnds;
    }

    function timedOutOffferBid() {
        $dateTime = date("Y-m-d H:i:s");
        $offerId = $_POST['offerId'];
        $responseId = $_SESSION['offerResponseId'];

        $totalAmountInOfferTable = $this->m_usermarketmarketplace->getTotalAmountInofferTbl($offerId);
        $amntInOfferTable = $totalAmountInOfferTable['amount'];

        $explodeAmntOffer = explode(" ", $amntInOfferTable);
        $amountOffered = $explodeAmntOffer[0];



        $getAmountOfResponce = $this->m_usermarketmarketplace->getAmountOfResponce($responseId);

        if (count($getAmountOfResponce) > 0) {
            $amountInResponse = $getAmountOfResponce['amount_demand'];

            $explodeAmntResponse = explode(" ", $amountInResponse);
            $responseAmount = $explodeAmntResponse[0];

            $totalAmountAfterTimedOut = $amountOffered + $responseAmount;

            $updateAmountArray = array("amount" => $totalAmountAfterTimedOut . " mio");

            $updateAmountAfterTimeOut = $this->m_usermarketmarketplace->updateTblMarketOffer($updateAmountArray, $offerId);

            $timeOutArray = array("is_accepted" => "to", "status" => "closed", "deniedDate" => $dateTime);
            $responseTimeOutOffer = $this->m_usermarketmarketplace->responseTimeOutOffer($timeOutArray, $responseId);
        }
    }

    function deniedAmendedAmount() {

        $responceId = $_POST['responceId'];

        $deniedResponce = $this->m_usermarketmarketplace->deniedAmendedAmount($responceId);
    }

    function checkMaturityDate() {

        $termMonths = $_POST['maturity'];
        if ($termMonths == "o/n") {
            $date = date("d.m.Y");
            echo $dateSelet = date('d.m.Y', strtotime($date . ' +1 Weekday'));
        } else if ($termMonths == "t/n") {
            $date = date("d.m.Y");
            echo $dateSelet = date('d.m.Y', strtotime($date . ' +2 Weekday'));
        } else if ($termMonths == "s/n") {
            $date = date("d.m.Y");

            $dateSelet = strtotime($date);
            $valueDates = strtolower(date("l", $dateSelet));  // today day name 

            if ($valueDates == "friday" || $valueDates == "saturday") {
                $dateSelet = date('d.m.Y', strtotime($date . ' +1 Weekday'));
            } else {

                $dateSelet = date('d.m.Y', strtotime($date . ' +2 Weekday'));
            }
            // echo $dateSelet;
            echo $dateSn = date('d.m.Y', strtotime($dateSelet . ' +1 Weekday'));
        } else {
            $termOfMont = str_replace('s', '', $_POST['maturity']);
            $termOfMonth = $termOfMont . "s";



            //$termOfMonth = "7 day";
            // 2weekend days after today

            $date = date("d.m.Y");      //today date

            $date1 = strtotime($date);  // coverted in to date format

            $date2 = date("l", $date1);  // today day name 

            $date3 = strtolower($date2);


            if ($date3 == "friday" || $date3 == "saturday") {

                // if today is friday or saturday then add 1 weekay i.e Monday

                $dateSelet = date('d.m.Y', strtotime($date . ' +1 Weekday'));
            } else {

                // for all other add 2 week days

                $dateSelet = date('d.m.Y', strtotime($date . ' +2 Weekday'));
            }



            $currency = $_SESSION['currencrType'];



            if ($currency == $_SESSION['currencrType']) {

                $publicHolidays = $this->m_usermarketmarketplace->publicHolidays();

                $allHolidaysArray = array_map('current', $publicHolidays);  // list of all  public holidays form database

                $allHolidayCount = count($allHolidaysArray); //count all the holidays
            }

            // calculating if the date exist in the public holidays array ..if it exist add 1 week day to it

            $twoDays = $dateSelet;

            $y = 1;

            while ($y <= $allHolidayCount) {

                if (in_array($twoDays, $allHolidaysArray)) {

                    $twoDays = date('d.m.Y', strtotime($twoDays . ' +1 Weekday'));
                }

                $y++;
            }



            //add 7 days form the value date  and check for saturday and sunday

            $dateMonth = date('d.m.Y', strtotime($twoDays . '+ ' . $termOfMonth . 's'));

            $dateMonthTotime = strtotime($dateMonth);

            $dateMonthMatUpper = date("l", $dateMonthTotime);

            $dateMonthMat = strtolower($dateMonthMatUpper);







            if ($dateMonthMat == "saturday" || $dateMonthMat == "sunday") {

                $maturityDay = date('d.m.Y', strtotime($dateMonth . ' +1 Weekday'));
            } else {

                $maturityDay = $dateMonth;
            }





            // check maturity for public holidays

            $maturity = $maturityDay;

            $z = 1;

            while ($z <= $allHolidayCount) {

                if (in_array($maturity, $allHolidaysArray)) {

                    $maturity = date('d.m.Y', strtotime($maturity . ' +1 Weekday'));
                }

                $z++;
            }

            echo $maturity;
        }
    }

    function createPdfForOffer($pdfData, $date, $maturities) {

       // print_r($pdfData);die;
        $data['pdfData'] = $pdfData;

        $data['date'] = $date;

        $data['maturities'] = $maturities;
        //load the view and saved it into $html variable

        $html = $this->load->view('mmLoanAcceptedPdf', $data, true);



        //this the the PDF filename that user will get to download

        $pdfFilePath = "assets/mm_marketFiles/mmPdf-" . $date . ".pdf";



        //load mPDF library

        $this->load->library('m_pdf');



        //generate the PDF from the given html

        $this->m_pdf->pdf->WriteHTML($html);



        //download it.

        $this->m_pdf->pdf->Output($pdfFilePath, "F");
    }

    function editBid() {

        $responseId = $_POST['responseId'];

        $responseDetails = $this->m_usermarketmarketplace->responseDetails($responseId);

        echo $responseDetails[0]['amount'];
    }

    function deleteBid() {
        /* cancel my responce for request  */

        $responceId = $_POST['responceId'];

        $deleteBid = $this->m_usermarketmarketplace->deleteBid($responceId);
    }

    function deleteOffer() {
        /* cancel offer  */

        $offerId = $_POST['offerId'];

        $deleteOffer = $this->m_usermarketmarketplace->deleteOffer($offerId);
    }

    function confirmEditBid() {

        $responseId = $_POST['rquest_id'];

        $amount = $_POST['bid'];

        $money_text = $_POST['money_text'];

        $ypdateArray = array("amount" => $amount . " " . $money_text);



        $updateResponseForBid = $this->m_usermarketmarketplace->updateResponseForBid($ypdateArray, $responseId);
    }

    function sendOffer() {

        $offerResponseId = $_POST['offerId'];
        $amount = $_POST['amount'] . " mio";

        $this->m_usermarketmarketplace->updateAmountOfferResponce($offerResponseId, $amount);

        $this->m_usermarketmarketplace->confirmEditOffer($offerResponseId);
    }

    function updateAmountOfferRes() {
        $offerResponseId = $_POST['offerId'];

        $amount = $_POST['amount'];

        $amountRequestedByBorrowerInOffer = $this->m_usermarketmarketplace->amountRequestedByBorrowerInOffer($offerResponseId);

        $amountByBorrowerInOffer = $amountRequestedByBorrowerInOffer['amount_demand'];
        $offerId = $amountRequestedByBorrowerInOffer['offer_id'];

        //Amount in offer table
        $getTotalAmountOfferTable = $this->m_usermarketmarketplace->getTotalAmountOfferTable($offerId);
        $offerAmount = $getTotalAmountOfferTable['amount'];
        $explodeOfferAmount = explode(" ", $offerAmount);
        $offerAmounts = $explodeOfferAmount[0];

        //Amount asked by borrower from offer
        $explodeRequestedByBorrowerInOffer = explode(" ", $amountByBorrowerInOffer);
        $amountByBorrower = $explodeRequestedByBorrowerInOffer[0];



        //Lender edited the bid amount
        $explodeLenderEditedAmount = explode(" ", $amount);
        $amountByLender = $explodeLenderEditedAmount[0];

        //$amountByLender."..........".$amountByBorrower;


        if ($amountByBorrower >= $amountByLender) {
            $restedAmount = $amountByBorrower - $amountByLender;

            $addRestedAmountInOffer = $offerAmounts + $restedAmount;
            $restAmmnt = $addRestedAmountInOffer . " mio";

            $updateEditedOfferResponse = $this->m_usermarketmarketplace->updateAmountOfferResponce($offerResponseId, $amount);


            //print_r($restAmmnt);
            //$updateOfferTable          = $this->m_usermarketmarketplace->updateAmountOffer($restAmmnt,$offerId);
            echo 1;
        } else {
            echo 0;
        }
    }

    function editOffer() {

        $offerId = $_POST['offerId'];

        $offetDetail = $this->m_usermarketmarketplace->marketOfferDetail($offerId);

        echo json_encode($offetDetail);
    }

    function offerUpdateRequestForm() {

        $offerId = $_POST['offerIdForEdit'];

        $offerd_currency = $_POST['offer_currency'];

        $offer_amount = $_POST['offer_amount'];

        $offer_money_text = $_POST['offer_money_text'];

        $offer_maturityMonthss = $_POST['offer_maturityMonthss'];

        $offer_maturity = $_POST['offer_maturity'];

        $offer_minimum_bid = $_POST['offer_minimum_bid'];

        $offerBrokenDate = $_POST['offerBrokenDate'];

        if ($offerBrokenDate == "0") {

            $updateOffer = array(
                "term" => $offer_maturityMonthss,
                "maturity_date" => $offer_maturity,
                "amount" => $offer_amount . " " . $offer_money_text,
                "currency" => $offerd_currency,
                "offer_rate" => $offer_minimum_bid
            );
        }
        if ($offerBrokenDate == "1") {
            $updateOffer = array(
                "term" => "Broken Date",
                "maturity_date" => $offer_maturity,
                "amount" => $offer_amount . " " . $offer_money_text,
                "currency" => $offerd_currency,
                "offer_rate" => $offer_minimum_bid
            );
        }

        $updateOffer = $this->m_usermarketmarketplace->updateOffers($offerId, $updateOffer);
    }

    function oKOffer() {

        $user_id = $_SESSION['user_id'];

        $offerId = $_POST['offerId'];  // offerResponse Id

       $currency   = $_POST['currency'];

        $responseId = $offerId;

        $getMaturitiesOfResponse = $this->m_usermarketmarketplace->getMaturitiesOfResponse($responseId);
        $value_date = $getMaturitiesOfResponse['value_date'];
        $maturity_date = $getMaturitiesOfResponse['maturity_date'];

        $offerDetails = $this->m_usermarketmarketplace->offerDetails($responseId); // from table response

        $borrowerAskedAmount = str_replace(" mio", "", $offerDetails[0]['amount_updated']); //original amount asked by borrower

        $lenderSendsTheAmount = str_replace(" mio", "", $offerDetails[0]['amount_demand']);

        $restedAmount = $borrowerAskedAmount - $lenderSendsTheAmount;


        $offer_id = $offerDetails[0]['offer_id'];
        $amounts = $offerDetails[0]['amount'];

        $explodeArr = explode(' ', $amounts);
        $amount = $explodeArr[0];

        $amountAfterAccept = $amount + $restedAmount;

        $updateOffer = array("amount" => $amountAfterAccept . " mio");
        $updateAmountInOffer = $this->m_usermarketmarketplace->updateOffers($offer_id, $updateOffer);



        $marketOfferDetail = $this->m_usermarketmarketplace->marketOfferDetail($offer_id); // get amount detail for the offer offered by lender

        $arrayMerge = array_merge($offerDetails, $marketOfferDetail);
        echo json_encode($arrayMerge);





        $offerDetails = $this->m_usermarketmarketplace->offerDetails($offerId);

        $offer_id = $offerDetails[0]['offer_id'];
        $marketOfferDetail = $this->m_usermarketmarketplace->marketOfferDetail($offer_id); // get amount detail from `tbl_market_offer`



        $offeredAmount = $marketOfferDetail[0]['amount'];

        $amountOffr = explode(" ", $offeredAmount);

        $amountOfferMoney = $amountOffr[0];

        $amountOfferText = "mio";


        $amountOffered = $amountOfferMoney;

        $responseId = $offerDetails[0]['id'];

        $borrower_id = $offerDetails[0]['borrower_id'];

        $lender_id = $offerDetails[0]['lender_id'];


        $amount_accept = $offerDetails[0]['amount_demand'];



        $amountAccpt = explode(" ", $amount_accept);

        $amountAccptMoney = $amountAccpt[0];



        $restedAmount = $amountOffered - $amountAccptMoney;

        $restAmmnt = $restedAmount . " mio";

        $term = $offerDetails[0]['term'];

        $offer_rate = $offerDetails[0]['offer_rate'] . "%";





        $dateAcceptance = date("d.m.Y");

        $accpetedArray = array(
            "borrower_id" => $borrower_id,
            "lender_id" => $lender_id,
            "response_id" => $offerId,
            "offer_id" => $offer_id,
            "ammount_accepted" => $amount_accept,
            "interest_rate" => $offer_rate,
            "accepted_term" => $term,
            "value_date" => $value_date,
            "maturity_date" => $maturity_date,
            "date_accepted" => $dateAcceptance,
            "accepted_by" => "offer",
            "currency_type" => $currency
        );


        $accepetFullDeal = $this->m_usermarketmarketplace->accepetFullDeal($accpetedArray);

        $responseForRequest = $this->m_usermarketmarketplace->responseForOfferPdf($accepetFullDeal);

        $offered_id = $responseForRequest[0]['offer_id'];

        $borrowerEmail = $responseForRequest[0]['borrowerEmail'];

        $responseLenderDetails = $this->m_usermarketmarketplace->responseLenderDetails($offered_id);
       //print_r($responseLenderDetails);
        $lenderEmail = $responseLenderDetails[0]['lenderEmail'];





        $adminDetails = $this->m_usermarketmarketplace->adminDetails();
        $adminEmail = $adminDetails[0]['email'];



        $updateOrferArr = array("status" => "closed", "lender_accepted" => "n", "is_accepted" => "y");

        $this->m_usermarketmarketplace->updateOfferResponceTbl($updateOrferArr, $offerId);

        //$this->m_usermarketmarketplace->updateAmountOffer($restAmmnt,$offer_id);







        if ($restedAmount <= 0) {

            // $this->m_usermarketmarketplace->closeOffer($offer_id);
        }

        $responseForRequest = $this->m_usermarketmarketplace->responseForOfferPdf($accepetFullDeal);

        // print_r($responseForRequest);

        $borowerAlternativeMail = $responseForRequest[0]['borowerAlternativeMail'];
        $offered_id = $responseForRequest[0]['offer_id'];

        $borrowerEmail = $responseForRequest[0]['borrowerEmail'];

        $responseLenderDetails = $this->m_usermarketmarketplace->responseLenderDetails($offered_id);

        $lenderEmail = $responseLenderDetails[0]['lenderEmail'];

        $lenderAlterNativeEmail = $responseLenderDetails[0]['lenderAlterNativeEmail'];
        //$accepetFullDeal = 1;

        $fullResponcePdf = array_merge($responseForRequest, $responseLenderDetails);

        $dateTm = date("YmdHis");


        $mergeArray = array_merge($offerDetails, $marketOfferDetail);


        $pdfInsertArr = array("pdf_name" => "mmPdf-" . $dateTm);



        $this->m_usermarketmarketplace->insertPdfName($pdfInsertArr, $accepetFullDeal);



        $this->createPdfForOffer($fullResponcePdf, $dateTm, $getMaturitiesOfResponse);



        $pdfDataForInserted = $this->m_usermarketmarketplace->pdfDataForInserted($accepetFullDeal);

        $pdfName = $pdfDataForInserted[0]['pdf_name'];







        $base_url = base_url();

        $to = "$borrowerEmail, $lenderEmail";

        // $to        = "vikas@webmobsoft.com";

        $subject = "Instimatch AG : Deal Confirmed";

        $message = "Hi <br/><br/>";

        $message .= "Please check below PDF as an details of deal<br/>";

        $message .= "<a href='" . $base_url . "assets/mm_marketFiles/" . $pdfName . ".pdf'>Clicks Here</a><br/><br/>";

        $message .= 'Best Regards <br/>';

        $message .= "Instimatch AG <br>";

        $message .= "Riedm&uuml;hlestrasse 8  <br>";

        $message .= "8305 Dietlikon  <br>";

        $message .= "+41 43 543 06 63  <br>";

        $message .= "admin@instimatch.ch";


        $header = "From: admin@instimatch.ch \r\n";

        if ($borowerAlternativeMail == "" && $lenderAlterNativeEmail == "") {
            $header .= "Cc: info@instimatch.ch \r\n";
        }
        if ($borowerAlternativeMail != "" && $lenderAlterNativeEmail == "") {
            $header .= "Cc: info@instimatch.ch , $borowerAlternativeMail \r\n";
        }
        if ($lenderAlterNativeEmail != "" && $borowerAlternativeMail == "") {
            $header .= "Cc: info@instimatch.ch , $lenderAlterNativeEmail \r\n";
        }
        if ($lenderAlterNativeEmail != "" && $borowerAlternativeMail != "") {
            $header .= "Cc: info@instimatch.ch , $lenderAlterNativeEmail ,$borowerAlternativeMail \r\n";
        }


        // $header .= "Bcc: $borowerAlternativeMail";
        //$header .= "Bcc: $lenderAlterNativeEmail";
        $header .= "MIME-Version: 1.0\r\n";

        $header .= "Content-type: text/html\r\n";



        $retval = mail($to, $subject, $message, $header);

        //echo json_encode($mergeArray );    
    }

    function amountDeniedOk() {

        $responseId = $_POST['responseId'];

        $offerDetails = $this->m_usermarketmarketplace->offerDetails($responseId); // from table response

        $offer_id = $offerDetails[0]['offer_id'];
        $amounts = $offerDetails[0]['amount'];

        $explodeArr = explode(' ', $amounts);



        if ($totalRows == 0) {
            //$this->m_usermarketmarketplace->closeOffer($offer_id);
        }

        $marketOfferDetail = $this->m_usermarketmarketplace->marketOfferDetail($offer_id); // get amount detail for the offer offered by lender

        $arrayMerge = array_merge($offerDetails, $marketOfferDetail);
        echo json_encode($arrayMerge);

        $this->m_usermarketmarketplace->amountDeniedOk($responseId);
    }

    
    function countAllNotificationsMM()
    {
        if(isset($_SESSION['user_id']) && $_SESSION['user_id'] != "")
        {
            $user_id = $_SESSION['user_id'];
            
            $currencyType = $_SESSION['currencrType'];
            $result['getNotification'] = $this->m_usermarketmarketplace->getNotifications($user_id, $currencyType);  /* notification to borrower when lender deal money onclick deal */

            
            $result['editedOfferNotificationForLender'] = $this->m_usermarketmarketplace->editedOfferNotificationForLender($user_id, $currencyType); /* offer notification to lender */

            
            $result['editedOfferNotificationForBorrower'] = $this->m_usermarketmarketplace->editedOfferNotificationForBorrower($user_id, $currencyType); /* offer notification to borrower */

            
            
            $result['dealAccepptedNotofication'] = $this->m_usermarketmarketplace->dealAccepptedNotofication($user_id, $currencyType);  /* notification to lender when borrower accpt the deal */

            
            
            $deniedNotificationToBorrowerDeal = $this->m_usermarketmarketplace->deniedNotificationToBorrowerDeal($user_id, $currencyType);

            $deniedNotificationToBorrowerOffer = $this->m_usermarketmarketplace->deniedNotificationToBorrowerOffer($user_id, $currencyType);

            $deniedNotificationToLenderOffer = $this->m_usermarketmarketplace->deniedNotificationToLenderOffer($user_id, $currencyType);


            $notificationOfferAccepedByBorrower = $this->m_usermarketmarketplace->notificationOfferAccepedByBorrower($user_id, $currencyType);


            $result['getNotifications'] = array_merge($result['getNotification'], $result['editedOfferNotificationForLender'], $result['editedOfferNotificationForBorrower'], $result['dealAccepptedNotofication'], $deniedNotificationToBorrowerDeal, $deniedNotificationToBorrowerOffer, $deniedNotificationToLenderOffer, $notificationOfferAccepedByBorrower);

        
            echo count($result['getNotifications']);
        }
        else
        {
            echo "-1";
        }
        
    }
    
    
    function getAllNotifications() {

        if(!isset($_SESSION['user_id']))
            {
            //echo "<script>location.reload(); </script>";
                  echo "-1";
                   die;
        }
            else
            {
        
    
                $user_id = $_SESSION['user_id'];

                $currencyType = $_SESSION['currencrType'];
                $result['getNotification'] = $this->m_usermarketmarketplace->getNotifications($user_id, $currencyType);  /* notification to borrower when lender deal money onclick deal */


                $result['editedOfferNotificationForLender'] = $this->m_usermarketmarketplace->editedOfferNotificationForLender($user_id, $currencyType); /* offer notification to lender */


                $result['editedOfferNotificationForBorrower'] = $this->m_usermarketmarketplace->editedOfferNotificationForBorrower($user_id, $currencyType); /* offer notification to borrower */



                $result['dealAccepptedNotofication'] = $this->m_usermarketmarketplace->dealAccepptedNotofication($user_id, $currencyType);  /* notification to lender when borrower accpt the deal */



                $deniedNotificationToBorrowerDeal = $this->m_usermarketmarketplace->deniedNotificationToBorrowerDeal($user_id, $currencyType);

                $deniedNotificationToBorrowerOffer = $this->m_usermarketmarketplace->deniedNotificationToBorrowerOffer($user_id, $currencyType);

                $deniedNotificationToLenderOffer = $this->m_usermarketmarketplace->deniedNotificationToLenderOffer($user_id, $currencyType);


                $notificationOfferAccepedByBorrower = $this->m_usermarketmarketplace->notificationOfferAccepedByBorrower($user_id, $currencyType);



                $result['getNotifications'] = array_merge($result['getNotification'], $result['editedOfferNotificationForLender'], $result['editedOfferNotificationForBorrower'], $result['dealAccepptedNotofication'], $deniedNotificationToBorrowerDeal, $deniedNotificationToBorrowerOffer, $deniedNotificationToLenderOffer, $notificationOfferAccepedByBorrower);



//print_r($notificationOfferAccepedByBorrower);



                if (count($result['getNotifications']) > 0) {
                            $i = 1;
                            $k = 1;
                            foreach ($result['getNotifications'] as $notification) {


                                        if($notification['currency'] == "CHF")
                                        {
                                                $currencyCode = "&#8355;";  //swiss franc
                                        }
                                        if($notification['currency'] == "EUR")
                                        {
                                                $currencyCode = "&#128;";  //Euro
                                        }
                                        if($notification['currency'] == "USD")
                                        {
                                                $currencyCode = "&#36;"; //USD
                                        }
                                //notification to lender when borrower ask for money by offer

                                if ($notification['lender_accepted'] == "n") {

                                    if ($notification['is_accepted'] == "d") {

                                        $not = '<li style="border-bottom:1px solid #cbcbcb;height:50px;padding:10px 10px;"><span style="float:left;width:80%;" class="notificationMessage"> Your offer is denied by ' . $notification['company_name'] . ' in ' . $notification['currency'] . ' ' . $notification['amount_demand'] . ' ' . $notification['term'] . '

                at ' . $notification['offer_rate'] . '%</span><span style="float:right;"><button type="button" style="background-color:#7ab648;color:#fff;background-color: #7ab648;border: 0px solid;height: 30px; width: 60px;" onclick="amountDeniedOk(' . $notification['offerResponseId'] . ')">Ok</button> </span></li>';
                                    } else if ($notification['is_accepted'] == "y") {

if ($notification['term'] == "Broken Date") {
                                        $not = '<li style="border-bottom:1px solid #cbcbcb;height:50px;padding:10px 10px;"><span style="float:left;width:80%;" class="notificationMessage"> Your offer has been accepted by ' . $notification['company_name'] . ' in ' . $notification['currency'] . ' ' . $notification['amount_demand'] . ' ' . $notification['vd4'] .' - '. $notification['md4'].'

                at ' . $notification['offer_rate'] . '%</span><span style="float:right;"><button type="button" style="background-color:#7ab648;color:#fff;background-color: #7ab648;border: 0px solid;height: 30px; width: 60px;" onclick="amountAcceptedOk(' . $notification['offerResponseId'] . ')">Ok</button> </span></li>';

            }
            else {
$not = '<li style="border-bottom:1px solid #cbcbcb;height:50px;padding:10px 10px;"><span style="float:left;width:80%;" class="notificationMessage"> Your offer has been accepted by ' . $notification['company_name'] . ' in ' . $notification['currency'] . ' ' . $notification['amount_demand'] . ' ' . $notification['term'] . '

                at ' . $notification['offer_rate'] . '%</span><span style="float:right;"><button type="button" style="background-color:#7ab648;color:#fff;background-color: #7ab648;border: 0px solid;height: 30px; width: 60px;" onclick="amountAcceptedOk(' . $notification['offerResponseId'] . ')">Ok</button> </span></li>';

            }
                                    } else {
                                        //offer biding

                                        $startTime = date("Y-m-d H:i:s");
                                        $start = date_create("$startTime");
                                        $cenvertedTime = $notification['valid_until'];
                                        $end = date_create("$cenvertedTime");



                                        $diff = date_diff($end, $start);

                                        //$hour = $diff->h;
                                        $minutes = $diff->i;
                                        if ($minutes < 10) {
                                            $mnts = "0" . $minutes;
                                        } else {
                                            $mnts = $minutes;
                                        }
                                        $seconds = $diff->s;
                                        if ($seconds < 10) {
                                            $secnds = "0" . $seconds;
                                        } else {
                                            $secnds = $seconds;
                                        }

                                        $time = $mnts . ":" . $secnds;

                                        if ($time == "00:00" || $startTime > $cenvertedTime) {
                                            echo "<script language=\"javascript\">timeOutRequest();</script>";

                                            $dateTime = date("Y-m-d H:i:s");
                                            $time = "Timed out";
                                            $offerResponseId = $notification['offerResponseId'];
                                            $getAmountOfResponce = $this->m_usermarketmarketplace->getOfferIdOfResponse($offerResponseId);

                                            if (count($getAmountOfResponce) > 0) {

                                                $offerId = $getAmountOfResponce['offer_id'];
                                                $totalAmountInOfferTable = $this->m_usermarketmarketplace->getTotalAmountInofferTbl($offerId);
                                                $amntInOfferTable = $totalAmountInOfferTable['amount'];

                                                $explodeAmntOffer = explode(" ", $amntInOfferTable);
                                                $amountOffered = $explodeAmntOffer[0];
                                                $amountInResponse = $getAmountOfResponce['amount_demand'];

                                                $explodeAmntResponse = explode(" ", $amountInResponse);
                                                $responseAmount = $explodeAmntResponse[0];

                                                $totalAmountAfterTimedOut = $amountOffered + $responseAmount;

                                                $updateAmountArray = array("amount" => $totalAmountAfterTimedOut . " mio");

                                                $updateAmountAfterTimeOut = $this->m_usermarketmarketplace->updateTblMarketOffer($updateAmountArray, $offerId);

                                                $timeOutArray = array("is_accepted" => "to", "status" => "closed", "deniedDate" => $dateTime);
                                                $responseTimeOutOffer = $this->m_usermarketmarketplace->responseTimeOutOffer($timeOutArray, $offerResponseId);
                                            }
                                        }
if ($notification['term'] == "Broken Date") {
                                        $not = '<li class="notificaionDet notificationdetls' . $k . '" id="notificationOffer' . $notification['offerResponseId'] . '" style="border-bottom:1px solid #cbcbcb;height:90px;padding:10px 10px;"><span style="float:left;width:80%;" ><span class="notificationMessage"> Your offer has been taken by ' . $notification['company_name'] . ' in ' . $notification['currency'] . ' ' . $notification['amount_demand'] . ' ' . $notification['vd1'] .' - '. $notification['md1'].'

                at ' . $notification['offer_rate'] . ' %.<br/> Please check limit availability and enter amount if limit available</span> ( <span id="timeChecked' . $k . '">' . $time . '</span>)<br/><b>Amount : </b><input type="text" onfocus="limitCheck()" onblur="limitChecked()" placeholder = "Offered Amount" value="' . trim(str_replace("mio", "", $notification['amount_demand'])) . '" name="updateAmountRequested' . $notification['offerResponseId'] . '" id ="updateAmountRequested' . $notification['offerResponseId'] . '" onkeyup = "updateAmountOfferRes(' . $notification['offerResponseId'] . ')"> mio</span><span style="float:right;"><button type="button" style="background-color:#7ab648;color:#fff;background-color: #7ab648;border: 0px solid;height: 30px; width: 60px;" onclick="oKOffer(' . $notification['offerResponseId'] .",'".$notification['currency']."'". ')">Accept</button> <button type="button" style="background-color:#7ab648;color:#fff;background-color: #ff0000;border: 0px solid;height: 30px; width: 60px;" onclick="deniedRequestOffrResponce(' . $notification['offerResponseId'] . ')">Deny</button></span>
                <br/><span id="amountUpdatdErr' . $notification['offerResponseId'] . '" class="error_strings loanForm_maturity_errorloc text-danger col-md-12" style="text-align:center;position:relative;top:-14px;"></span></li>';
            }
            else {

                 $not = '<li class="notificaionDet notificationdetls' . $k . '" id="notificationOffer' . $notification['offerResponseId'] . '" style="border-bottom:1px solid #cbcbcb;height:90px;padding:10px 10px;"><span style="float:left;width:80%;" ><span class="notificationMessage"> Your offer has been taken by ' . $notification['company_name'] . ' in ' . $notification['currency'] . ' ' . $notification['amount_demand'] . ' ' . $notification['term'] . '

                at ' . $notification['offer_rate'] . ' %.<br/> Please check limit availability and enter amount if limit available</span> ( <span id="timeChecked' . $k . '">' . $time . '</span>)<br/><b>Amount : </b><input type="text" onfocus="limitCheck()" onblur="limitChecked()" placeholder = "Offered Amount" value="' . trim(str_replace("mio", "", $notification['amount_demand'])) . '" name="updateAmountRequested' . $notification['offerResponseId'] . '" id ="updateAmountRequested' . $notification['offerResponseId'] . '" onkeyup = "updateAmountOfferRes(' . $notification['offerResponseId'] . ')"> mio</span><span style="float:right;"><button type="button" style="background-color:#7ab648;color:#fff;background-color: #7ab648;border: 0px solid;height: 30px; width: 60px;" onclick="oKOffer(' . $notification['offerResponseId'] .",'".$notification['currency']."'". ')">Accept</button> <button type="button" style="background-color:#7ab648;color:#fff;background-color: #ff0000;border: 0px solid;height: 30px; width: 60px;" onclick="deniedRequestOffrResponce(' . $notification['offerResponseId'] . ')">Deny</button></span>
                <br/><span id="amountUpdatdErr' . $notification['offerResponseId'] . '" class="error_strings loanForm_maturity_errorloc text-danger col-md-12" style="text-align:center;position:relative;top:-14px;"></span></li>';
            }

                                        $k++;
                                    }
                                }

                                //notification to borrower when lender accepts the borrower request for money
                                else if ($notification['lender_accepted'] == "y") {

                                    if ($notification['is_accepted'] == "d") {

                                        $not = '<li style="border-bottom:1px solid #cbcbcb;height:50px;padding:10px 10px;"><span style="float:left;width:80%;" class="notificationMessage"> Your offer is denied by ' . $notification['company_name'] . ' in ' . $notification['currency'] . ' ' . $notification['amount_demand'] . ' ' . $notification['term'] . 'at ' . $notification['offer_rate'] . '%</span><span style="float:right;"><button type="button" style="background-color:#7ab648;color:#fff;background-color: #7ab648;border: 0px solid;height: 30px; width: 60px;" onclick="amountDeniedOk(' . $notification['offerResponseId'] . ')">Ok</button> </span></li>';
                                    } else {

                                        $not = '<li id="notificationOk' . $notification['offerResponseId'] . '" style="border-bottom:1px solid #cbcbcb;height:50px;padding:10px 10px;"><span style="float:left;width:80%;" class="notificationMessage"> You are being

                offered ' . $notification['currency'] . ' ' . $notification['amount_demand'] . ' mio ' . $notification['term'] . ' at ' . $notification['offer_rate'] . '% from

                lender ' . $notification['company_name'] . '</span><span style="float:right;"><button type="button" style="background-color:#7ab648;color:#fff;background-color: #7ab648;border: 0px solid;height: 30px; width: 60px;" onclick="oKOffer(' . $notification['offerResponseId'] . ')">Ok</button> <button type="button" style="background-color:#7ab648;color:#fff;background-color: #ff0000;border: 0px solid;height: 30px; width: 60px;" onclick="deniedRequestOffrResponce(' . $notification['offerResponseId'] . ')">Deny</button></span></li>';
                                    }
                                }

                                //notification to borrower when lender bids on requests
                                else {

                                    if ($notification['responceStatus'] == "a") {

                                         if ($notification['term'] == "Broken Date") {

                                        $not = '<li id="notificationId' . $notification['notificationId'] . '" style="border-bottom:1px solid #cbcbcb;height:50px;padding:10px 10px;"><span style="float:left;width:80%;" class="notificationMessage"> Your offer in ' . $notification['currency'] . '  ' . $notification['offeredAmount'] . ' mio at ' . $notification['offerRate'] . ' ' . $notification['vd'] .'-'. $notification['md'] .'  has been accepted by ' . $notification['company_name'] . '</span>



                                        <span style="float:right;"><button type="button" style="background-color:#7ab648;color:#fff;background-color: #7ab648;border: 0px solid;height: 30px; width: 60px;" onclick="dealAccepted(' . $notification['notificationId'] . ')">Ok</button></span>

                                <li>';

                            }
                            else {
                                $not = '<li id="notificationId' . $notification['notificationId'] . '" style="border-bottom:1px solid #cbcbcb;height:50px;padding:10px 10px;"><span style="float:left;width:80%;" class="notificationMessage"> Your offer in ' . $notification['currency'] . '  ' . $notification['offeredAmount'] . ' mio at ' . $notification['offerRate'] . ' ' . $notification['term'] . ' has been accepted by ' . $notification['company_name'] . '</span>



                                        <span style="float:right;"><button type="button" style="background-color:#7ab648;color:#fff;background-color: #7ab648;border: 0px solid;height: 30px; width: 60px;" onclick="dealAccepted(' . $notification['notificationId'] . ')">Ok</button></span>

                                <li>'; 
                            }
                                    } else if ($notification['responceStatus'] == "d") {

                                        $not = '<li id="notificationId' . $notification['notificationId'] . '" style="border-bottom:1px solid #cbcbcb;height:50px;padding:10px 10px;"><span style="float:left;width:80%;" class="notificationMessage"> Your offer in ' . $notification['currency'] . '  ' . $notification['offeredAmount'] . ' mio at ' . $notification['offerRate'] . ' ' . $notification['term'] . ' has been denied by ' . $notification['company_name'] . '</span>

                                        <span style="float:right;"><button type="button" style="background-color:#7ab648;color:#fff;background-color: #7ab648;border: 0px solid;height: 30px; width: 60px;" onclick="dealDenied(' . $notification['notificationId'] . ')">Ok</button></span>

                                <li>';
                                    } else {

                                        $startTime = date("Y-m-d H:i:s");
                                        $start = date_create("$startTime");
                                        $cenvertedTime = $notification['valid_until'];
                                        $end = date_create("$cenvertedTime");



                                        $diff = date_diff($end, $start);

                                        //$hour = $diff->h;
                                        $minutes = $diff->i;
                                        if ($minutes < 10) {
                                            $mnts = "0" . $minutes;
                                        } else {
                                            $mnts = $minutes;
                                        }
                                        $seconds = $diff->s;
                                        if ($seconds < 10) {
                                            $secnds = "0" . $seconds;
                                        } else {
                                            $secnds = $seconds;
                                        }

                                        $time = $mnts . ":" . $secnds;

                                        if ($time == "00:00" || $startTime > $cenvertedTime) { //offer expire  
                                            echo "<script language=\"javascript\">timeOutRequest();</script>";

                                            $time = "Timed out";
                                            $dateTime = date("Y-m-d H:i:s");
                                            $timedOutArray = array("status" => "closed", "request_status" => "to", "deniedDate" => $dateTime);
                                            $responceId = $notification['responceId'];

                                            $timedOutResponse = $this->m_usermarketmarketplace->timedOutResponse($responceId, $timedOutArray);

                                            if ($timedOutResponse != 0) {
                                                $responceId = $timedOutResponse;
                                                $ammountOfCurrentResponse = $this->m_usermarketmarketplace->ammountOfCurrentResponse($responceId);



                                                $requestId = $ammountOfCurrentResponse['request_id'];
                                                $amountOfrequest = $this->m_usermarketmarketplace->amountOfRequestDetails($requestId); // get amouunt of request whose response timed out
                                                $requestAmount = $amountOfrequest['amount'];


                                                $responseAmount = $ammountOfCurrentResponse['amount'];  // get amouunt of response which timed out
                                                $explodeAmount = explode(" ", $responseAmount);

                                                $amount = $explodeAmount[0];

                                                $addAmountAfterTimedOut = $requestAmount + $amount;

                                                $updateRequestArray = array("amount" => $addAmountAfterTimedOut, "amount_display" => $addAmountAfterTimedOut . " mio");


                                                $updateAmountRequest = $this->m_usermarketmarketplace->updateRequestAmount($updateRequestArray, $requestId);
                                            }
                                        }


                                        //lendder send ammount to borrower
 if ($notification['term'] == "Broken Date") {
                                        $not = '<li class="notificaionDet notificationdetls' . $k . '" id="notificationId' . $notification['responceId'] . $notification['requestedId'] . '" style="border-bottom:1px solid #cbcbcb;height:50px;padding:10px 10px;"><span style="float:left;width:80%;" class="notificationMessage"> You are being

                offered ' . $notification['currency'] . ' ' . $notification['offeredAmount'] . ' mio ' . $notification['vd2'] .' - '. $notification['md2'] .' at ' . $notification['offerRate'] . ' from

                lender ' . $notification['company_name'] . ' (<span id="timeChecked' . $k . '">' . $time . '</span>) </span><span style="float:right;"><button type="button" style="background-color:#7ab648;color:#fff;background-color: #7ab648;border: 0px solid;height: 30px; width: 60px;" onclick="acceptFullDeal(' . $notification['responceId'] . "," . $notification['requestedId'] .",'". $notification['currency']."'".')">Accept</button> <button type="button" style="background-color:#7ab648;color:#fff;background-color: #ff0000;border: 0px solid;height: 30px; width: 60px;" onclick="deniedRequestOffr(' . $notification['responceId'] . ')">Deny</button></span></li>';
            }
            else {
$not = '<li class="notificaionDet notificationdetls' . $k . '" id="notificationId' . $notification['responceId'] . $notification['requestedId'] . '" style="border-bottom:1px solid #cbcbcb;height:50px;padding:10px 10px;"><span style="float:left;width:80%;" class="notificationMessage"> You are being

                offered ' . $notification['currency'] . ' ' . $notification['offeredAmount'] . ' mio ' . $notification['term'] . ' at ' . $notification['offerRate'] . ' from

                lender ' . $notification['company_name'] . ' (<span id="timeChecked' . $k . '">' . $time . '</span>) </span><span style="float:right;"><button type="button" style="background-color:#7ab648;color:#fff;background-color: #7ab648;border: 0px solid;height: 30px; width: 60px;" onclick="acceptFullDeal(' . $notification['responceId'] . "," . $notification['requestedId'] .",'". $notification['currency']."'".')">Accept</button> <button type="button" style="background-color:#7ab648;color:#fff;background-color: #ff0000;border: 0px solid;height: 30px; width: 60px;" onclick="deniedRequestOffr(' . $notification['responceId'] . ')">Deny</button></span></li>';

            }
                                        $i++;
                                        $k++;
                                    }
                                }

                                echo $not;
                            }
                        } else {

                            echo "0";
                        }
            }
    }

    

    function countNotifications() {

        $user_id = $_SESSION['user_id'];
        $currencyType = $_SESSION['currencrType'];
        $result['getNotification'] = $this->m_usermarketmarketplace->getNotifications($user_id, $currencyType);  /* notification to borrower when lender deal money onclick deal */



        $result['editedOfferNotificationForLender'] = $this->m_usermarketmarketplace->editedOfferNotificationForLender($user_id, $currencyType); /* offer notification to lender */



        $result['editedOfferNotificationForBorrower'] = $this->m_usermarketmarketplace->editedOfferNotificationForBorrower($user_id, $currencyType); /* offer notification to borrower */



        $result['dealAccepptedNotofication'] = $this->m_usermarketmarketplace->dealAccepptedNotofication($user_id, $currencyType);  /* notification to lender when borrower accpt the deal */





        $deniedNotificationToBorrowerDeal = $this->m_usermarketmarketplace->deniedNotificationToBorrowerDeal($user_id, $currencyType);



        $deniedNotificationToBorrowerOffer = $this->m_usermarketmarketplace->deniedNotificationToBorrowerOffer($user_id, $currencyType);

        $deniedNotificationToLenderOffer = $this->m_usermarketmarketplace->deniedNotificationToLenderOffer($user_id, $currencyType);



        $notificationOfferAccepedByBorrower = $this->m_usermarketmarketplace->notificationOfferAccepedByBorrower($user_id, $currencyType);





        $result['getNotifications'] = array_merge($result['getNotification'], $result['editedOfferNotificationForLender'], $result['editedOfferNotificationForBorrower'], $result['dealAccepptedNotofication'], $deniedNotificationToBorrowerDeal, $deniedNotificationToBorrowerOffer, $deniedNotificationToLenderOffer, $notificationOfferAccepedByBorrower);




        echo count($result['getNotifications']);
    }

    function checkMinOffer() {

        $user_id = $_SESSION['user_id'];
        $currencyType = $_SESSION['currencrType'];
        $result['getmarketMoney'] = $this->m_usermarketmarketplace->getmarketMoney();





        foreach ($result['getmarketMoney'] as $marketMoney) {

            $moneyMarketid[] = $result['getmarketMoney'][] = $marketMoney['id'] - 1;



            $allOffersForTerms[] = $result['allOffersForTerms'][] = $this->m_usermarketmarketplace->allOffersForTerms($marketMoney['value'], $currencyType);
        }



        //print_r( $allOffersForTerms);



        echo json_encode($allOffersForTerms);
    }

    //all the requests and offers with ajax



    function allRequestsAjax() {

        $currencyType = $_SESSION['currencrType'];
        $user_id = $_SESSION['user_id'];
        $buttonsId = $_POST['buttonsId'];

        /* Market bids ------------ */
        $result['marketRequests'] = $marketRequests = $this->m_usermarketmarketplace->marketRequests($user_id, $currencyType,$buttonsId);

        $result['getmarketMoney'] = $getmarketMoney = $this->m_usermarketmarketplace->getmarketMoney($buttonsId);
        $result['publicHolidays'] = $publicHolidays = $this->m_usermarketmarketplace->publicHolidays();
        $result['publicHolidaysEur'] = $publicHolidaysEur = $this->m_usermarketmarketplace->publicHolidaysEur();



        foreach ($result['getmarketMoney'] as $data) {

            $result['countRequests'][] = $countRequests = $this->m_usermarketmarketplace->getRequestCount($data['value'], $currencyType);
            $result['currencyTValue'][] = $currencyTValue[] = $this->m_usermarketmarketplace->getCurrencyValueOfFirstRequest($data['value'], $currencyType);
            $result['allOffersForTerms'][] = $allOffersForTerms = $this->m_usermarketmarketplace->allOffersForTerms($data['value'], $currencyType,$buttonsId);



            $moneyMarketid = $data['id'] - 1;

            $totalRequest = $countRequests[0]['totalRequests'];

            if ($totalRequest == "0") {

                $totalRequests = "";
                $totalRequestsShow = "";
            } else {

                $totalRequests = $totalRequest;
                $totalRequestsShow = "(" . $totalRequest . ")";
            }



            if (strtolower($data['value']) == "o/n") {
                $date = date("d.m.Y");
                $twoDays = $date;
                $maturityDay = date('d.m.Y', strtotime($twoDays . ' +1 Weekday'));
            } else if (strtolower($data['value']) == "t/n") {
                $date = date("d.m.Y");
                $twoDays = date('d.m.Y', strtotime($date . ' +1 Weekday'));
                $maturityDay = date('d.m.Y', strtotime($twoDays . ' +1 Weekday'));
            } else if (strtolower($data['value']) == "s/n") {
                $date = date("d.m.Y");

               $dateSelet = strtotime($date);
               
                $valueDates = strtolower(date("l", $dateSelet));  // today day name 

                
                
                if ($valueDates == "friday" || $valueDates == "saturday") {
                    $dateSelet = date('d.m.Y', strtotime($date . ' +2 Weekday'));
                } else {

                    $dateSelet = date('d.m.Y', strtotime($date . ' +2 Weekday'));
                }
                $twoDays = $dateSelet;
                // echo $dateSelet;
                $maturityDay = date('d.m.Y', strtotime($dateSelet . ' +1 Weekday'));
            } else {
                
                $termOfMont = str_replace('s', '', $data['value']);

                $termOfMonth = $termOfMont . "s";



                $date = date("d.m.Y"); //today

                $date1 = strtotime($date);  // coverted in to date format

                $date2 = date("l", $date1);  // today day name 

                $date3 = strtolower($date2);



                if ($date3 == "friday" || $date3 == "saturday") {
                    $dateSelet = date('d.m.Y', strtotime($date . ' +2 Weekday'));
                } else {
                    $dateSelet = date('d.m.Y', strtotime($date . ' +2 Weekday'));
                }



                $currency = $_SESSION['currencrType'];

                if ($currency == $_SESSION['currencrType']) {

                    $publicHolidays = $this->m_usermarketmarketplace->publicHolidays();

                    $allHolidaysArray = array_map('current', $publicHolidays);  // list of all  public holidays form database

                    $allHolidayCount = count($allHolidaysArray); //count all the holidays
                }

                // calculating if the date exist in the public holidays array ..if it exist add 1 week day to it

                $twoDays = $dateSelet;

                $y = 1;

                while ($y <= $allHolidayCount) {

                    if (in_array($twoDays, $allHolidaysArray)) {

                        $twoDays = date('d.m.Y', strtotime($twoDays . ' +1 Weekday'));
                    }

                    $y++;
                }
                /* calculating if the date exist in the public holidays array ..if it exist add 1 week day to it */



                /* add 7 days form the value date  and check for saturday and sunday */
                $dateMonth = date('d.m.Y', strtotime($twoDays . '+ ' . $termOfMonth . 's'));

                $dateMonthTotime = strtotime($dateMonth);

                $dateMonthMatUpper = date("l", $dateMonthTotime);

                $dateMonthMat = strtolower($dateMonthMatUpper);



                if ($dateMonthMat == "saturday" || $dateMonthMat == "sunday") {

                    $maturityDay = date('d.m.Y', strtotime($dateMonth . ' +1 Weekday'));
                } else {

                    $maturityDay = $dateMonth;
                }


                $maturity = $maturityDay;

                $z = 1;

                while ($z <= $allHolidayCount) {

                    if (in_array($maturity, $allHolidaysArray)) {

                        $maturity = date('d.m.Y', strtotime($maturity . ' +1 Weekday'));
                    }

                    $z++;
                }

                $maturityDay = $maturity;




                $manualSelectedDate = $data["start_date"];  //if date selected by manual

                $manualdate1 = strtotime($manualSelectedDate);  // coverted in to date format

                $manualdate2 = date("l", $manualdate1);  // today day name 

                $manualdate3 = strtolower($manualdate2);



                if ($manualdate3 == "friday" || $manualdate3 == "saturday") {

                    // if today is friday or saturday then add 1 weekay i.e Monday

                    $dateSeleted = date('d.m.Y', strtotime($manualSelectedDate . ' +1 Weekday'));
                } else {

                    // for all other add 2 week days

                    $dateSeleted = date('d.m.Y', strtotime($manualSelectedDate . ' +2 Weekday'));
                }


                $twoDaysManual = $dateSeleted;

                if ($data["start_date"] == "") {
                    $manualToday = "N/A";

                    $twoDaysManual = "N/A";
                } else {
                    $manualToday = $data["start_date"];
                    $twoDaysManual = $twoDaysManual;
                }

                if ($data['value_date'] == "") {
                    $valueDAte = "N/A";
                } else {
                    $valueDAte = $data['value_date'];
                }

                if ($data["maturity_date"] == "") {
                    $manualMat = "N/A";
                } else {
                    $manualMat = $data["maturity_date"];
                }
            }



            if ($data['showDatesManual'] == "n") {
                if ($data['buttons_id'] == "2") {
                    $showMarketTabs = '<div class="col-md-12 rowTab"><span id="marketBtn" class="tableSuperMenuAddAtiveClass btn   col-md-3 col-sm-3 hidehover1" >' . $data['value'] . ' ' . $totalRequestsShow . '<br/>&nbsp;</span><a id="marketBtn" class="tableSuperMenuAddAtiveClass btn  col-md-3 col-sm-3 hidehover1">Deal date<br/>' . $date . '</span></a><a id="marketBtn" class="tableSuperMenuAddAtiveClass btn  col-md-3 col-sm-3 hidehover1">Value date<br/><span id="myValueDate' . $data['id'] . '">' . $twoDays . '</span></a></div>';
                } else {
                    $showMarketTabs = '<div class="col-md-12 rowTab"><span id="marketBtn" class="tableSuperMenuAddAtiveClass btn   col-md-3 col-sm-3 hidehover" >' . $data['value'] . ' ' . $totalRequestsShow . '<br/>&nbsp;</span><a id="marketBtn" class="tableSuperMenuAddAtiveClass btn  col-md-3 col-sm-3 hidehover">Deal date<br/>' . $date . '</span></a><a id="marketBtn" class="tableSuperMenuAddAtiveClass btn  col-md-3 col-sm-3 hidehover">Value date<br/><span id="myValueDate' . $data['id'] . '">' . $twoDays . '</span></a><a id="marketBtn" class="tableSuperMenuAddAtiveClass btn  col-md-3 col-sm-3 hidehover">Maturity<br/><span id="myMaturityDate' . $data['id'] . '">' . $maturityDay . '</span></a></div>';
                }
            }


            if ($data['showDatesManual'] == "y") {
                if ($data['buttons_id'] == "2") {
                    $showMarketTabs = '<div class="col-md-12 rowTab"><span id="marketBtn" class="tableSuperMenuAddAtiveClass btn   col-md-3 col-sm-3 hidehover1" >' . $data['value'] . ' ' . $totalRequestsShow . '<br/>&nbsp;</span><a id="marketBtn" class="tableSuperMenuAddAtiveClass btn   col-md-3 col-sm-3 hidehover1">Deal date<br/>' . $manualToday . '</span></a><a id="marketBtn" class="tableSuperMenuAddAtiveClass btn   col-md-3 col-sm-3 hidehover1">Value date<br/><span id="myValueDate' . $data['id'] . '">' . $valueDAte . '</span></a></div>';
                } else {
                    $showMarketTabs = '<div class="col-md-12 rowTab"><span id="marketBtn" class="tableSuperMenuAddAtiveClass btn   col-md-3 col-sm-3 hidehover" >' . $data['value'] . ' ' . $totalRequestsShow . '<br/>&nbsp;</span><a id="marketBtn" class="tableSuperMenuAddAtiveClass btn  col-md-3 col-sm-3 hidehover">Deal date<br/>' . $manualToday . '</span></a><a id="marketBtn" class="tableSuperMenuAddAtiveClass btn   col-md-3 col-sm-3 hidehover">Value date<br/><span id="myValueDate' . $data['id'] . '">' . $valueDAte . '</span></a><a id="marketBtn" class="tableSuperMenuAddAtiveClass btn  col-md-3 col-sm-3 hidehover">Maturity<br/><span id="myMaturityDate' . $data['id'] . '">' . $manualMat . '</span></a></div>';
                }
            }


            if ($totalRequests >= "4" && $data['buttons_id'] != "2") {
                echo "<style>#tabAllBttns" . $moneyMarketid . "{overflow-y:scroll !important;}</style>";
            }

            // check maturity for public holidays

            $dataToFeed = '<input type="hidden" id="thisTabId' . $data['id'] . '" value="' . $data['value'] . '"/>';

            $dataToFeed .= '<div class="col-md-12 col-sm-12" id ="tabAllBttns' . $moneyMarketid . '" style="height:250px;margin-bottom:10px;">' . $showMarketTabs ;

            
            if ($data['buttons_id'] != "2") 
            {
                $dataToFeed .= '<div id="btnAllDiv" >';
            }
            else
            {
                $dataToFeed .= '<div id="btnAllDivBroken" style="width:100%;text-align:center;">';
            }
            
            
            if ($data['buttons_id'] == "2") 
            {
                $dataToFeed .= '<div id="allRequestsBrokenDates" style="float:left;margin-left:20px;background-color:#DEE8C9;width:95.5%;height:40px;color:#000;font-size:16px;text-align:left;padding-top:10px;padding-left:10px;">BROKEN DATES BIDS</div>';
            }
        
        if (count($marketRequests) > 0)
        {
            
            foreach ($marketRequests as $request) {

                if (trim($data['value']) == trim($request['term'])) {

                   



                    $getUserFedafinRating = $this->m_usermarketmarketplace->getUserFedafinRating($request['borrower_id']);

                    
                    if ($getUserFedafinRating['LT_Mnemonic'] != "") {
                        $rating = $getUserFedafinRating['LT_Mnemonic'];
                    } else {
                        $rating = "N/A";
                    }
                    
                    
                    if ($getUserFedafinRating['rating_name'] != "") {
                        $fRating = $getUserFedafinRating['rating_name'];
                    } else {
                        $fRating = "N/A";
                    }
                    

                    if ($data['buttons_id'] != "2") {

                        if ($request['city'] != "") {
                            $cityName = $request['city'];
                        } else {
                            $cityName = "N/A";
                        }

                    if ($request['borrower_id'] == $_SESSION['user_id']) {
                        $onclickBidRequests = "";
                        $myRequestsClass = "myRequestClass";
                    } else {
                        $onclickBidRequests = "openBidOfferPopUp(".$request['id']. "," .$data['id'].",0)";
                        $myRequestsClass = "";
                    }


                        $dataToFeed .= '<div style="width:100%" class="col-md-12">
                            <div  class="tooltip comany_name' . $request['id'] . ' ' . $myRequestsClass . '" title="' . $request['amount_display'] . '" style="color:#fff;border:2px solid #01182a;border-radius:2px;height:44px;width:25%;float:left; padding:7px 0px;background-color:#ccd1d4; margin-top:5px;font-size:13px;color:#000d16;text-align:center;word-wrap:break-word"><b>' . $request['req_name'] . '</b></div>

                    <div id="marketButton3 padding" class="bidOffer" style="padding:10px 0px; border:2px solid #01182a;border-radius:2px;height:44px;width:15%;float:left;background-color:#ccd1d4;float:left ;margin-top:5px; font-size:15px;color:#000d16;"><a style="font-size:10px;color:#3c8af5;position:relative;bottom:8px;bottom:3px;float:left;width:100%;">RATING<a/>' . $rating . '</div> 
                                <div id="marketButton1 padding" style="border:2px solid #01182a;height:44px;width:14%;float:left; padding:10px 0px;background-color:#61b9dd;cursor:pointer ;margin-top:5px; font-size:15px;color:#000d16;"  title="Enter offer" onclick="' . $onclickBidRequests . '">Deal</div>        

                                <div id="marketButton2 padding" style="border:2px solid #01182a;height:44px;width:15%;float:left; padding:10px 0px;background-color:#ccd1d4 ;margin-top:5px; color:#000d16; font-size:13px;" class="" >Bid

                                 <span>  <img src="' . base_url() . 'assets/img/green-box.png" /> </span></div>

                                
                                
                                <div id="marketButton2 padding" style="padding:10px 0px; border:2px solid #01182a;border-radius:2px;height:44px;width:18%;float:left;background-color:#ccd1d4;float:left ;margin-top:5px; font-size:15px;color:#000d16;">' . $request['amount_display'] . '</div> 

                                <div id="marketButton3 padding" class="bidOffer' . $request['id'] . '" style="padding:10px 0px; border:2px solid #01182a;border-radius:2px;height:44px;width:13%;float:left;background-color:#ccd1d4;float:left ;margin-top:5px; font-size:15px;color:#000d16;">' . $request['min_bid'] . '%</div> 

                            

                            
                            <input type="hidden" name ="requestsId" id="requestsId" value="' . $request['id'] . '"/> 

                <input type="hidden" name ="displayamont' . $request['id'] . '" id="displayamont' . $request['id'] . '" value="' . $request['amount_display'] . '"/> 

                            <input type="hidden" name ="compname' . $request['id'] . '" id="compname' . $request['id'] . '" value="' . $request['company_name'] . '"/> 

                            <input type="hidden" name ="onDate' . $request['id'] . '" id="onDate' . $request['id'] . '" value="' . $request['on_date'] . '"/>  

                            <input type="hidden" name ="minBid' . $request['id'] . '" id="minBid' . $request['id'] . '" value="' . $request['min_bid'] . '"/> 

                            <input type="hidden" name ="notes' . $request['id'] . '" id="notes' . $request['id'] . '" value="' . $request['notes'] . '"/>  

                            <input type="hidden" name ="reqName' . $request['id'] . '" id="reqName' . $request['id'] . '" value="' . $request['req_name'] . '"/></div>';
                    } else {

                    
                    
                        if ($request['city'] != "") {
                            $cityName = $request['city'];
                        } else {
                            $cityName = "N/A";
                        }

                    if ($request['borrower_id'] == $_SESSION['user_id']) {
                        $onclickBidRequests = "";
                        $myRequestsClass = "myRequestClass";
                    } else {
                        $onclickBidRequests = "openBidOfferPopUp(".$request['id']. "," .$data['id'].",1)";
                        $myRequestsClass = "";
                    }
                        
                        $valueDate    = date("Y-m-d",strtotime($request['value_date']));
                        $maturityDate = date("Y-m-d",strtotime($request['maturity']));
                        
                        $from = date_create($valueDate);
                        $to   = date_create($maturityDate);
                        $diff1  = date_diff($from,$to);
                        //print_r($diff);
                        $diff=  $diff1->format('%a');
                        
                        $dataToFeed .= '<div style="width:100%" class="col-md-12"><div  class="tooltip comany_name' . $request['id'] . ' ' . $myRequestsClass . '" title="' . $request['amount_display'] . '" style="color:#fff;border:2px solid #01182a;border-radius:2px;height:44px;width:16%;float:left; padding:7px 0px;background-color:#ccd1d4; margin-top:5px;font-size:12px;color:#000d16;text-align:center;word-wrap:break-word"><b>' . $request['company_name'] . '</b>
                         <!-----<span class="tooltiptext" style="width:200px;"> <a style="text-decoration:none;color:#61B9DD">' . $request['company_name'] . ' Details</a><hr>( ' . $cityName . ' ) ' . $getUserFedafinRating['rating_name'] . '  ' . $getUserFedafinRating['LT_Mnemonic'] . ' <br/>' . $request['currency'] . ' ' . $request['amount_display'] . '</span>---->
                                        </div>
                                        <div id="marketButton3 padding" class="bidOffer" style="text-align:center;padding:10px 0px; border:2px solid #01182a;border-radius:2px;height:44px;width:10%;float:left;background-color:#ccd1d4;float:left ;margin-top:5px;font-size:15px;color:#000d16;padding-bottom:5px;"><a style="font-size:10px;position:relative;bottom:3px;float:left;width:100%;font-weight:bold;color:#000;text-decoration:none;">'.$fRating.'<a/>' .$rating . '</div> 
                    <div id="marketButton1 padding"  style="border:2px solid #01182a;height:44px;width:9%;float:left; padding:10px 0px;background-color:#ccd1d4 ;margin-top:5px; color:#000d16; font-size:15px;" class="" >Bid
                                                <span>  <img src="' . base_url() . 'assets/img/green-box.png" /> </span>
                                        </div>
                                        <div id="marketButton2 padding" style="border:2px solid #01182a;height:44px;width:8%;float:left; padding:10px 0px;background-color:#61b9dd;cursor:pointer ;margin-top:5px; font-size:15px;color:#000d16;"  title="Enter offer" onclick="' . $onclickBidRequests . '">Deal</div>  
                                        <div id="marketButton3 padding" class="bidOffer' . $request['id'] . '" style="padding:10px 0px; border:2px solid #01182a;border-radius:2px;height:44px;width:11%;float:left;background-color:#ccd1d4;float:left ;margin-top:5px; font-size:14px;color:#000d16;">' . $request['amount_display'] . '</div> 
                    <div id="marketButton4 padding" class="bidOffer' . $request['id'] . '" style="padding:10px 0px; border:2px solid #01182a;border-radius:2px;height:44px;width:9%;float:left;background-color:#ccd1d4;float:left ;margin-top:5px; font-size:14px;color:#000d16;">' . $request['min_bid'] . '%</div> 
                    <div id="marketButton3 padding" style="text-align:center;padding:10px 0px; border:2px solid #01182a;border-radius:2px;height:44px;width:13%;float:left;background-color:#ccd1d4;float:left ;margin-top:5px; font-size:15px;color:#000d16;padding-bottom:5px;"><a style="font-size:11px;position:relative;bottom:3px;float:left;width:100%;font-weight:bold;color:#000;text-decoration:none;">Value date<a/><span id="valueDateForBroken'.$request['id'].'">' .$request['value_date']. '</span></div>    
                    <div id="marketButton5 padding" style="padding:10px 0px; border:2px solid #01182a;border-radius:2px;height:44px;width:13%;float:left;background-color:#ccd1d4;float:left ;margin-top:5px; font-size:13px;color:#000d16;"><span style="font-size:10px;margin-bottom:10px;">Maturity</span><br/><span id="maturityDateForBroken'.$request['id'].'">' . $request['maturity'] . '</div> 
                                        <div id="marketButton5 padding" style="padding:10px 0px; border:2px solid #01182a;border-radius:2px;height:44px;width:11%;float:left;background-color:#ccd1d4;float:left ;margin-top:5px; font-size:13px;color:#000d16;"><span style="font-size:10px;margin-bottom:10px;">Nr. of days</span><br/>' . $diff . '</div> 
                            

                                        <input type="hidden" name ="requestsId" id="requestsId" value="' . $request['id'] . '"/> 
                                        <input type="hidden" name ="displayamont' . $request['id'] . '" id="displayamont' . $request['id'] . '" value="' . $request['amount_display'] . '"/> 
                                        <input type="hidden" name ="compname' . $request['id'] . '" id="compname' . $request['id'] . '" value="' . $request['company_name'] . '"/> 
                                        <input type="hidden" name ="onDate' . $request['id'] . '" id="onDate' . $request['id'] . '" value="' . $request['on_date'] . '"/>  
                                        <input type="hidden" name ="minBid' . $request['id'] . '" id="minBid' . $request['id'] . '" value="' . $request['min_bid'] . '"/> 
                                        <input type="hidden" name ="notes' . $request['id'] . '" id="notes' . $request['id'] . '" value="' . $request['notes'] . '"/>  
                                        <input type="hidden" name ="reqName' . $request['id'] . '" id="reqName' . $request['id'] . '" value="' . $request['req_name'] . '"/></div>';
                    }
                }
              }
            }
        else
        {
               
        if ($data['buttons_id'] == "2") {
            $dataToFeed .= '<br/><div class="col-md-12" style="width:100%;font-size:13px;position:relative;right:39%;"></div>'; 
        }
        }




            $dataToFeed .= '</div>'; // requests ajax end
            //best 3 offers here

                 if ($data['buttons_id'] == "2") 
         {
             $dataToFeed .= '<div id="allRequestsBrokenDates" style="margin-top:30px;float:left;margin-left:20px;background-color:#ECC6C5;width:96%;height:40px;color:#000;font-size:16px;text-align:left;padding-top:10px;padding-left:10px;margin-right:17px;">BROKEN DATES OFFER</div>';
         }
            
                 if ($data['buttons_id'] != "2") 
          {
                     $dataToFeed .= '<div id="offersDiv"  style="width:45%;height:200px !important;float:left;font-size:13px;color:white;text-align:center; ">';
          }
          else
          {
             $dataToFeed .= '<div id="offersDivBroken"  style="width:100%;height:200px !important;float:left;font-size:13px;color:white;text-align:center;margin-left:15px; ">';
          }

         
            if ($data['buttons_id'] != "2") {


                for ($i = 0; $i < 3; $i++) {
                    $offerRateCheck = $allOffersForTerms[$i]['offer_rate'];

                    if ($offerRateCheck != "") {

                        $offerShow = $offerRateCheck . "%";

                        if ($allOffersForTerms[$i]['lender_id'] !== $user_id) {
                            $onclickDealOffer = "acceptDeal(" . $allOffersForTerms[$i]['id'] . "," . $data['id'] . ",0)";
                            $myOfferClass = "";
                        } else {

                            $onclickDealOffer = "";
                            $myOfferClass = "myOfferClass";
                        }


                        $dataToFeed .= '<div class="bestOffers1" style="width:100%;">
                                    <div id="marketButton5 padding" style="padding:12px 0px; border:2px solid #01182a; height:44px;width:20%;float:left;background-color:#ccd1d4 ;margin-top:5px; color:#0e0e0e; font-size:15px;"  >' . $offerShow . '</div> 
                                    <div id="marketButton5 padding" style="padding:12px 0px; border:2px solid #01182a; height:44px;width:25%;float:left;background-color:#ccd1d4 ;margin-top:5px; color:#0e0e0e; font-size:15px;"  >' . $allOffersForTerms[$i]['amount'] . '</div> 
                                   ';
                        //if($i == "0")
                        // {
                        // $dataToFeed .= '<div id="marketButton4 padding" style="padding:12px 0px; border:2px solid #01182a; color:#0e0e0e;    height:44px;width:24%;float:left;background-color:#ccd1d4;margin-top:5px; color:#000d16; font-size:13px;" class="'.$myOfferClass.'">Best Offer<span>  <img src="'.base_url().'assets/img/red-box.png" /> </span></div>';
                        // }
                        // else
                        // {
                        //  $dataToFeed .= '<div id="marketButton4 padding" style="padding:12px 0px; border:2px solid #01182a; color:#0e0e0e;   height:44px;width:24%;float:left;background-color:#ccd1d4;margin-top:5px; color:#000d16; font-size:13px;" class="'.$myOfferClass.'">Offer<span>  <img src="'.base_url().'assets/img/red-box.png" /> </span></div>';
                        //}


                        $dataToFeed .= '<div id="marketButton4 padding" style="padding:12px 0px; border:2px solid #01182a; color:#0e0e0e;   height:44px;width:24%;float:left;background-color:#ccd1d4;margin-top:5px; color:#000d16; font-size:13px;" class="' . $myOfferClass . '">Offer<span>  <img src="' . base_url() . 'assets/img/red-box.png" /> </span></div>';
                        $dataToFeed .= '<div class="tooltip"  id="marketButton2 padding" style="padding:7px 0px;text-align:center;font-size:15px !important;width:20%;cursor:pointer;"  onclick="' . $onclickDealOffer . '"><b>Deal</b>
            <!---<span class="tooltiptext">' . $allOffersForTerms[$i]['amount'] . '</span>------></div>  
                            </div>

                         

                            <input type="hidden" name="amountOfferedForrequest" id="amountOfferedForrequest' . $allOffersForTerms[$i]['id'] . '" value="' . $allOffersForTerms[$i]['amount'] . '"/> 

                            <input type="hidden" name="rateOfferedForrequest" id="rateOfferedForrequest' . $allOffersForTerms[$i]['id'] . '" value="' . $allOffersForTerms[$i]['offer_rate'] . '"/> 

                            <input type="hidden" name="lenderCompanyDetail" id="lenderCompanyDetail' . $allOffersForTerms[$i]['id'] . '" value="' . $allOffersForTerms[$i]['lender_company'] . '"/> ';
                    }
                }
            } else {
                
                
                
                
                
                
            if(count($allOffersForTerms) > 0)
            {       
                
                
                for ($i = 0; $i < 100; $i++) {

                    $valueDate    = date("Y-m-d",strtotime($allOffersForTerms[$i]['value_date']));
                    $maturityDate = date("Y-m-d",strtotime($allOffersForTerms[$i]['maturity_date']));
                        
                    $from   = date_create($valueDate);
                    $to     = date_create($maturityDate);
                    $diff1  = date_diff($from,$to);
                    $diff   =  $diff1->format('%a');
                
                    $offerRateCheck = $allOffersForTerms[$i]['offer_rate'];

                    if ($offerRateCheck != "") {



                        $offerShow = $offerRateCheck . "%";

                       if ($allOffersForTerms[$i]['lender_id'] !== $user_id) {
                            $onclickDealOffer = "acceptDeal(" . $allOffersForTerms[$i]['id'] . "," . $data['id'] . ",1)";
                            $myOfferClass = "";
                        } else {

                            $onclickDealOffer = "";
                            $myOfferClass = "myOfferClass";
                        }
                        
                        $dataToFeed .= '<div class="bestOffers1" style="width:100%;">';
                        
                            $dataToFeed .= '<div id="marketButton1 padding" style="padding:12px 0px; border:2px solid #01182a; color:#0e0e0e;height:44px;width:15%;float:left;background-color:#ccd1d4;margin-top:5px; color:#000d16; font-size:12px;" class="' . $myOfferClass . '">Offer<span>  <img src="' . base_url() . 'assets/img/red-box.png" /> </span></div>
                                            <div class="tooltip"  id="marketButton2 padding" style="padding:7px 0px;text-align:center;font-size:14px !important;width:10%;cursor:pointer"  onclick="' . $onclickDealOffer . '"><b>Deal</b><!--<span class="tooltiptext">' . $allOffersForTerms[$i]['amount'] . '</span>-----></div> 
                                            <div id="marketButton5 padding" style="padding:12px 0px; border:2px solid #01182a; height:44px;width:14%;float:left;background-color:#ccd1d4 ;margin-top:5px; color:#0e0e0e; font-size:14px;"  class="">' . $allOffersForTerms[$i]['amount'] . '</div>';
                            
                            $dataToFeed .= '<div id="marketButton5 padding" style="padding:12px 0px; border:2px solid #01182a; height:44px;width:13%;float:left;background-color:#ccd1d4 ;margin-top:5px; color:#0e0e0e; font-size:14px;"  class="">' . $offerShow . '</div> 
                                ';
                        //if($i == "0")
                        //{
                        //$dataToFeed .= '<div id="marketButton1 padding" style="padding:12px 0px; border:2px solid #01182a; color:#0e0e0e; height:44px;width:24%;float:left;background-color:#ccd1d4;margin-top:5px; color:#000d16; font-size:10px;" class="'.$myOfferClass.'">Best Offer<span>  <img src="'.base_url().'assets/img/red-box.png" /> </span></div>';
                        //     }
                        // else
                        //{
                        //$dataToFeed .= '<div id="marketButton1 padding" style="padding:12px 0px; border:2px solid #01182a; color:#0e0e0e; height:44px;width:24%;float:left;background-color:#ccd1d4;margin-top:5px; color:#000d16; font-size:10px;" class="'.$myOfferClass.'">Offer<span>  <img src="'.base_url().'assets/img/red-box.png" /> </span></div>';
                        // }

                      $dataToFeed .=    '<div id="marketButton3 padding"  style="padding:10px 0px; border:2px solid #01182a;border-radius:2px;height:44px;width:15%;float:left;background-color:#ccd1d4;float:left ;margin-top:5px; font-size:13px;color:#000d16;"><span style="font-size:12px;margin-bottom:10px;">Value date</span><br/><span id="valueDateOfOffers'.$allOffersForTerms[$i]['id'].'">' .$allOffersForTerms[$i]['value_date']. '</span></div>
                                        <div id="marketButton3 padding"  style="padding:10px 0px; border:2px solid #01182a;border-radius:2px;height:44px;width:15%;float:left;background-color:#ccd1d4;float:left ;margin-top:5px; font-size:13px;color:#000d16;"><span style="font-size:12px;margin-bottom:10px;">Maturity</span><br/><span id="matDateOfOffers'.$allOffersForTerms[$i]['id'].'">' . $allOffersForTerms[$i]['maturity_date'] . '</span></div>                              
                                        <div id="marketButton3 padding"  style="padding:10px 0px; border:2px solid #01182a;border-radius:2px;height:44px;width:15%;float:left;background-color:#ccd1d4;float:left ;margin-top:5px; font-size:13px;color:#000d16;"><span style="font-size:12px;margin-bottom:10px;">Nr. of days</span><br/>' .$diff. '</div>                                                          
                         </div>

                         

                            <input type="hidden" name="amountOfferedForrequest" id="amountOfferedForrequest' . $allOffersForTerms[$i]['id'] . '" value="' . $allOffersForTerms[$i]['amount'] . '"/> 

                            <input type="hidden" name="rateOfferedForrequest" id="rateOfferedForrequest' . $allOffersForTerms[$i]['id'] . '" value="' . $allOffersForTerms[$i]['offer_rate'] . '"/>

                            <input type="hidden" name="lenderCompanyDetail" id="lenderCompanyDetail' . $allOffersForTerms[$i]['id'] . '" value="' . $allOffersForTerms[$i]['lender_company'] . '"/> ';
                    }
                }
                }
                else
                {
                    $dataToFeed .=  '<div style="text-align:left;position:relative;right:-4%;"></div>';
                }
            }






            $dataToFeed .= '</div>

                 

                 </div>';



            echo $dataToFeed;
        }
    }

    function seeRequestsDetail() {

        $requestId = $_POST['requestId'];

        $getDetailsOfRequestForPopUp = $this->m_usermarketmarketplace->getDetailsOfRequestForPopUp($requestId);



        echo json_encode($getDetailsOfRequestForPopUp);
    }

    function viewDealDetail() {
        $offerId = $_POST['offerId'];
        $responseId = $_POST['responseId'];

        $offerDetail = $this->m_usermarketmarketplace->viewDealDetail($offerId, $responseId);

        $borrowerId = $offerDetail[0]['borrower_id'];
        $borrowerDetails = $this->m_usermarketmarketplace->borrowerDetails($borrowerId);

        if ($borrowerId == $_SESSION['user_id']) {
            $dealMsg = "You have asked " . $offerDetail[0]['currency'] . " " . $offerDetail[0]['amount_demand'] . " at " . $offerDetail[0]['offer_rate'] . "%";
        } else {
            $dealMsg = $borrowerDetails['company_name'] . "";
        }
        echo $dealMsg;
    }

    function resetOffer() {
        $offerId = $_POST['offerId'];
        $resetOffer = $this->m_usermarketmarketplace->resetOffer($offerId);
    }

    function deleteKeptoffers() {
        $requestId = $_POST['requestId'];
        $resetOffer = $this->m_usermarketmarketplace->deleteKeptoffers($requestId);
    }
    function deleteKeptbids() {
        $requestId = $_POST['requestId'];
        $resetOffer = $this->m_usermarketmarketplace->deleteKeptbids($requestId);
    }
    function resetBid() {
        $requestId = $_POST['requestId'];
        $resetOffer = $this->m_usermarketmarketplace->resetBid($requestId);
    }

    function withdrawAllRequests() {
        $withDrawAllCurrentlyOpenRequests = $this->m_usermarketmarketplace->withDrawAllCurrentlyOpenRequests();
        $withDrawAllCurrentlyOpenOffers = $this->m_usermarketmarketplace->withDrawAllCurrentlyOpenOffers();

        echo "All bids and offers are canceled";
    }

  
function allCurrentBestBids() {
        $allMarketMoney = $this->m_usermarketmarketplace->allMarketMoney();
        $currencyType = $_SESSION['currencrType'];
        $user_id   = $_SESSION['user_id'];
        foreach ($allMarketMoney as $marketValue) {

            $marketValue = $marketValue['value'];
            $allBestRequests = $this->m_usermarketmarketplace->allBestValuesRequest($marketValue, $currencyType);

            $allBestOffers = $this->m_usermarketmarketplace->allBestOffers($marketValue, $currencyType);

            $merge[] = array_merge($allBestRequests, $allBestOffers);
        }

        
       $tableDate = '<table class="col-lg-12 col-sm-12 col-md-12 mytables" >
        <tr ><td colspan="5"><b style="display:none;">Best Offers<b></td></tr>
        <tr>
        <td >Term</td>
        <td >Amount</td>
        <td class="bidClass">Bid</td>
        <td class="offerClass">Offer</td>
        <td >Amount</td>
      </tr><tbody class="bestBidsBody">';
        $i = 0;
        foreach ($merge as $best) {
            
             $result1 = $marketRequests = $this->m_usermarketmarketplace->mymarketsettings($user_id,$currencyType,$best['borrower_id']);
             if(count($result1) > 0) {
              //echo "hi";
             if($result1[0]['bid'] =="Y") {
                
             $min_bid= $request['min_bid'] + ($result1[0]['Bid-Spread']/100);
                } 
                else {
                    $min_bid= $request['min_bid']; 
                    
                }
              }
              else {
               
               $min_bid= $request['min_bid']; 
              }


           
            if($best['requestedAmount'] > "0")
            {
                $requestAmount = $best['requestedAmount'];
                $percentageSign = "%";
                $minBid  = $best['min_bid'] ;
            }
            else
            {
                $requestAmount = "";
                $percentageSign = "";
                $minBid  = "" ;
            }
            
            
            if($best['offerAmount'] > "0" )
            {
                $offerAmnt = $best['offerAmount'];
                $offerRate         = $best['offer_rate'];
                $percentageSignOffr = "%";
            }
            else
            {
                $offerAmnt = "";
                $offerRate         = "";
                $percentageSignOffr = "";
            }
            
           
                $tableDate .= '<tr>
                <td >' . $allMarketMoney[$i]['value'] . '</td>

                <td >' . $requestAmount . '</td>
                <td >' . $minBid. $percentageSign . '</td>
                <td >' . $offerRate . $percentageSignOffr . '</td>
                <td >' . $offerAmnt. '</td>
              </tr>';

            
          

            $i++;
        }

        $tableDate .= '</tbody></table> ';

        echo $tableDate;
    }
    function timedOutForOfferResponse() {
        $offerResponseId = $_POST['offerResponseId'];

        $getAmountOfResponce = $this->m_usermarketmarketplace->getOfferIdOfResponse($offerResponseId);

        if (count($getAmountOfResponce) > 0) {
            $dateTime = date("Y-m-d H:i:s");
            //echo "here";
            $offerId = $getAmountOfResponce['offer_id'];
            $totalAmountInOfferTable = $this->m_usermarketmarketplace->getTotalAmountInofferTbl($offerId);
            $amntInOfferTable = $totalAmountInOfferTable['amount'];

            $explodeAmntOffer = explode(" ", $amntInOfferTable);
            $amountOffered = $explodeAmntOffer[0];
            $amountInResponse = $getAmountOfResponce['amount_demand'];

            $explodeAmntResponse = explode(" ", $amountInResponse);
            $responseAmount = $explodeAmntResponse[0];

            $totalAmountAfterTimedOut = $amountOffered + $responseAmount;

            $updateAmountArray = array("amount" => $totalAmountAfterTimedOut . " mio");

            $updateAmountAfterTimeOut = $this->m_usermarketmarketplace->updateTblMarketOffer($updateAmountArray, $offerId);

            $timeOutArray = array("is_accepted" => "to", "status" => "closed", "deniedDate" => $dateTime);
            $responseTimeOutOffer = $this->m_usermarketmarketplace->responseTimeOutOffer($timeOutArray, $offerResponseId);
        }
    }

    //advance search
    function searchStatusBy() {
        $searchById = $_POST['searchById'];
        $currencyType = $_SESSION['currencrType'];
        $user_id = $_SESSION['user_id'];

        if ($searchById == "1") {
            //not used
            // open orders 
            $openOrder = $this->m_usermarketmarketplace->openOrders($user_id, $currencyType);
            $myOpenRequestsForOffers = $this->m_usermarketmarketplace->myOpenRequestsForOffers($user_id, $currencyType);


            $result['openOrders'] = array_merge($openOrder, $myOpenRequestsForOffers);

            $myarrayOpenOrder = $result['openOrders'];
            foreach ($myarrayOpenOrder as $c => $key) {
                $dateTime[] = $key['by_order'];
            }

            array_multisort($dateTime, SORT_ASC, $myarrayOpenOrder);

            $openOrders = $myarrayOpenOrder;

            if (count($openOrders) > 0) {
                foreach ($openOrders as $requests) {
                    $listOfOpenOrder = "<tr style='width:100%;border: 1px solid black;'>";

                    if ($requests['term'] != "Broken Date") {
                        $listOfOpenOrder .= '<td  style="border:2px solid #000;width:19%;word-wrap:break-word">' . $requests['term'] . '</td>';
                    } else {
                        if ($requests['maturity'] != "") {
                            $maturityDate = $requests['maturity'];
                        } else {
                            $maturityDate = $requests['maturity_date'];
                        }
                        $listOfOpenOrder .= '<td class="tooltip1" style="border:2px solid #000;cursor:pointer;width:19%;word-wrap:break-word ">' . $requests['term'] . '<span class="tooltiptext1" style=""><a style="text-decoration:none;">Maturity Date </a></br>' . $maturityDate . '</span></td>';
                    }

                    if ($requests['borrower_id'] == $_SESSION['user_id']) {
                        $requestType = "bid";
                    } else {
                        $requestType = "offer";
                    }

                    $listOfOpenOrder .= '<td  style="border:2px solid #000;width:16%;">' . $requestType . '</td>';



                    if ($requests["min_bid"] != "") {
                        $minimumBid = $requests["min_bid"] . "%";
                    } else {
                        if ($requests["int_rate"] != "") {
                            $minimumBid = $requests["int_rate"];
                        } else {
                            $minimumBid = $requests["offer_rate"] . "%";
                        }
                    }

                    $listOfOpenOrder .= '<td  style="border:2px solid #000;width:13%;">' . $minimumBid . '</td>';


                    if (isset($requests['amount_display']) && $requests['amount_display'] != "") {
                        $amountOfRequest = $requests['amount_display'];
                    } else {
                        $amountOfRequest = $requests['amount'];
                    }

                    $listOfOpenOrder .= '<td  style="border:2px solid #000;width:16%;">' . $amountOfRequest . '</td>';

                    $listOfOpenOrder .= "</tr>";
                    echo $listOfOpenOrder;
                }
            } else {
                $listOfCompOrder = '<tr><td><i>No record found</i></td></tr>';
                echo $listOfCompOrder;
            }
        }
        if ($searchById == "2") {
            //completed orders
            $completedOrder = $this->m_usermarketmarketplace->AllAcceptedRequests($user_id, $currencyType);
            $myAcceptedOrder = $this->m_usermarketmarketplace->allMyAcceptedOrder($user_id, $currencyType);
            $result['completedOrders'] = array_merge($completedOrder, $myAcceptedOrder); // accepted request by me and for me
            $myarray = $result['completedOrders'];

            foreach ($myarray as $key => $row) {
                $mid[$key] = $row['time_accept'];
            }

            array_multisort($mid, SORT_DESC, $myarray);

            $completedOrders = $myarray;

                if (count($completedOrders) > "0") {
               
                    
                 foreach ($completedOrders as $requests) {
                     
                    $listOfCompOrder = '<tr style="width:100% !important;text-align:center;">';
                    $listOfCompOrder .= '<td  style="border:2px solid #000;width:30%;">' . $requests['accepted_term'] . '</td> ';

                    if ($requests['borrower_id'] == $_SESSION['user_id']) {
                        $requestType = "borrowed";
                    } else {
                        $requestType = "lent";
                    }

                    $listOfCompOrder .= '<td class="col-md-1" style="border:2px solid #000;">' . $requestType . '</td>';

                    if ($requests['company_name'] != "") {
                        $companyName = $requests['company_name'];
                    } else {
                        $companyName = $requests['borrowerCompanyName'];
                    }

                    $listOfCompOrder .= '<td  style="border:2px solid #000;width:20%;">' . $companyName . '</td>';
                    $listOfCompOrder .= '<td  style="border:2px solid #000;width:30%;">' . $requests['ammount_accepted'] . '</td>';
                    $listOfCompOrder .= '<td  style="border:2px solid #000;width:20%;">' . $requests['interest_rate'] . '</td>';

                    $listOfCompOrder .= '<td  style="border:2px solid #000;width:20%;" title="Value date">' . $requests['value_date'] . '</td>';
                    $listOfCompOrder .= '<td  style="border:2px solid #000;width:20%;" title="Maturity date">' . $requests['maturity_date'] . '</td>';
                    
                    
                    
                    $time_accept = $requests['time_accept'];
                    $timeAccepted = date("d.m.Y H:i:s", strtotime($time_accept));
                    $listOfCompOrder .='<td  style="border:2px solid #000;width:20%;">' . $timeAccepted . '</td>';
                    $listOfCompOrder .= '</tr>';
                    echo $listOfCompOrder;
                   
                }
            } else {
                $listOfCompOrder = '<tr><td><i>No record found</i></td></tr>';
                echo $listOfCompOrder;
            }
        }

        if ($searchById == "3") {
            $cancelMyRequests = $this->m_usermarketmarketplace->allCancelMyRequests($user_id, $currencyType);
            $canceledMyOffers = $this->m_usermarketmarketplace->allCanceledMyOffers($user_id, $currencyType);


            $canceledOrders = array_merge($cancelMyRequests, $canceledMyOffers);
            $myCancelArray = $canceledOrders;


            // sort array by time descending
            foreach ($myCancelArray as $key => $row) {
                $mid[$key] = $row['withdrawTime'];
            }

            array_multisort($mid, SORT_DESC, $myCancelArray);
            $cancelOrders = $myCancelArray;

            if (count($cancelOrders) > "0") {
                foreach ($cancelOrders as $requests) {
                    $listOfCancelOrder = '<tr style="width:90% !important;text-align:center;">
                                      <td  style="border:2px solid #000;width:30%;">' . $requests['term'] . '</td>';

                    if (isset($requests['borrowerId'])) {
                        $requestType = "bid";
                    } else {
                        $requestType = "offer";
                    }

                    $listOfCancelOrder .= '<td class="col-md-1" style="border:2px solid #000;">' . $requestType . '</td>';

                    if (isset($requests['borrowerCompany'])) {
                        $company = $requests['borrowerCompany'];
                    } if (isset($requests['lenderCompany'])) {
                        $company = $requests['lenderCompany'];
                    }


                    $listOfCancelOrder .= '<td  style="border:2px solid #000;width:20%;">' . $company . '</td>';
                    $listOfCancelOrder .='<td  style="border:2px solid #000;width:30%;">' . $requests['amount'] . '</td>';

                    $listOfCancelOrder .='<td  style="border:2px solid #000;width:20%;">' . str_replace('%', '', $requests['int_rate']) . '%</td>';

                    $listOfCancelOrder .='<td  style="border:2px solid #000;width:30%;">' . date("d.m.Y H:i:s", strtotime($requests['withdrawTime'])) . '</td>';

                    $listOfCancelOrder .= '</tr>';
                    echo $listOfCancelOrder;
                }
            } else {
                $listOfCancelOrder = '<tr><td><i>No record found</i></td></tr>';
                echo $listOfCancelOrder;
            }
        }
        if ($searchById == "4") {
            $borrowerDeniedToAccept = $this->m_usermarketmarketplace->allBorrowerDeniedToAccept($user_id, $currencyType);  // borrower denied to accept
           
            $lenderDealsDenied = $this->m_usermarketmarketplace->allLenderDealsDenied($user_id, $currencyType);
            $timedOutOrdersForBorrower = $this->m_usermarketmarketplace->allTimedOutOrdersForBorrower($user_id, $currencyType);  //timed out bid side for borrower
            $timedOutOrdersForLender = $this->m_usermarketmarketplace->allTimedOutOrdersForLender($user_id, $currencyType);    //timed out bid side for lender


            $timedOutOfferOrderForBorrower = $this->m_usermarketmarketplace->allTimedOutOfferOrderForBorrower($user_id, $currencyType);  //timed out bid side for borrower
            $timedOutOfferOrderForLender = $this->m_usermarketmarketplace->allTimedOutOfferOrderForLender($user_id, $currencyType);  //timed out bid side for lender

            $lenderDeniedOfferRequest = $this->m_usermarketmarketplace->allLenderDeniedOfferRequest($user_id, $currencyType);  //cancel offers requests details lender side


            $borrowerDeniedOfferRequest = $this->m_usermarketmarketplace->allBorrowerDeniedOfferRequest($user_id, $currencyType);  //cancel offers requests details borrower side

            $mergeTimedOutArrays = array_merge($timedOutOrdersForBorrower, $timedOutOrdersForLender, $borrowerDeniedToAccept, $lenderDealsDenied, $lenderDeniedOfferRequest, $borrowerDeniedOfferRequest, $timedOutOfferOrderForBorrower, $timedOutOfferOrderForLender);


            $result['timedOuts'] = $mergeTimedOutArrays;
            $myToArray = $result['timedOuts'];


            // sort array by time descending
            foreach ($myToArray as $key => $row) {
                $mid[$key] = $row['deniedDate'];
            }

            array_multisort($mid, SORT_DESC, $myToArray);


            $timedOut = $myToArray;

            if (count($timedOut) > 0) {
                foreach ($timedOut as $order) {
                    $listOfToOrder = '<tr style="width:100% !important;text-align:center;">';

                    if (isset($order['request_status']) && $order['request_status'] == "to") {
                        $responseStatus = "Time out";
                    } else {
                        $responseStatus = "Denied";
                    }


                    $listOfToOrder .= '<td  style="border:2px solid #000;width:20%;">' . $responseStatus . '</td> ';
                    $listOfToOrder .= '<td  style="border:2px solid #000;width:20%;">' . $order['term'] . '</td>';
                    if (isset($order['offerResponseId'])) {
                        $requestType = "offer";
                    } else {
                        $requestType = "bid";
                    }
                    $listOfToOrder .= '<td  style="border:2px solid #000;width:10%;">' . $requestType . '</td>';

                    if (isset($order['borrowerCompany'])) {
                        $marketMaker = $order['borrowerCompany'];
                    } else {
                        $marketMaker = $_SESSION['company_name'];
                    }
                    $listOfToOrder .= '<td  style="border:2px solid #000;width:20%;" title="Market maker">' . $marketMaker . '</td>';
                    $listOfToOrder .= '<td  style="border:2px solid #000;width:20%;">' . $order['amount'] . '</td>';
                    $listOfToOrder .= '<td  style="border:2px solid #000;width:30%;">' . str_replace('%', '', $order['int_rate']) . '%</td>';


                    if (isset($order['lenderCompany'])) {
                        $aggressor = $order['lenderCompany'];
                    } else {
                        $aggressor = $_SESSION['company_name'];
                    }
                    $listOfToOrder .= '<td  style="border:2px solid #000;width:30%;" title="Agressor">' . $aggressor . '</td>';
                    
                    $listOfToOrder .= '<td  style="border:2px solid #000;width:30%;" title="Value date">' . $order['value_date'] . '</td>';
                    $listOfToOrder .= '<td  style="border:2px solid #000;width:30%;" title="Maturity date">' . $order['maturity_date'] . '</td>';
                    
                    $listOfToOrder .= '<td  style="border:2px solid #000;width:30%;">' . date("d.m.Y H:i:s", strtotime($order['deniedDate'])) . '</td>';
                    $listOfToOrder .= '</tr>';
                    echo $listOfToOrder;
                }
            } else {
                $listOfToOrder = '<tr><td><i>No record found</i></td></tr>';
                echo $listOfToOrder;
            }
        }
    }

    function searchByDate() {
        $status = $_GET['status'];
        $startDate = date("Y-m-d", strtotime($_GET['startingDate']));

        $startDate = $startDate . " 00:00:00";  //to get all records of the day

        $endDat = date("Y-m-d", strtotime($_GET['endingDate']));
        $endDate = $endDat . " 23:59:59";  //to get all records of the day

        $currencyType = $_SESSION['currencrType'];
        $user_id = $_SESSION['user_id'];

        if ($status == "1") {
            // open orders 
            $openOrder = $this->m_usermarketmarketplace->openOrders($user_id, $currencyType);
            $myOpenRequestsForOffers = $this->m_usermarketmarketplace->myOpenRequestsForOffers($user_id, $currencyType);


            $result['openOrders'] = array_merge($openOrder, $myOpenRequestsForOffers);

            $myarrayOpenOrder = $result['openOrders'];
            foreach ($myarrayOpenOrder as $c => $key) {
                $dateTime[] = $key['by_order'];
            }

            array_multisort($dateTime, SORT_ASC, $myarrayOpenOrder);

            $openOrders = $myarrayOpenOrder;

            if (count($openOrders) > 0) {
                foreach ($openOrders as $requests) {
                    $listOfOpenOrder = "<tr style='width:100%;border: 1px solid black;'>";

                    if ($requests['term'] != "Broken Date") {
                        $listOfOpenOrder .= '<td  style="border:2px solid #000;width:19%;word-wrap:break-word">' . $requests['term'] . '</td>';
                    } else {
                        if ($requests['maturity'] != "") {
                            $maturityDate = $requests['maturity'];
                        } else {
                            $maturityDate = $requests['maturity_date'];
                        }
                        $listOfOpenOrder .= '<td class="tooltip1" style="border:2px solid #000;cursor:pointer;width:19%;word-wrap:break-word ">' . $requests['term'] . '<span class="tooltiptext1" style=""><a style="text-decoration:none;">Maturity Date </a></br>' . $maturityDate . '</span></td>';
                    }

                    if ($requests['borrower_id'] == $_SESSION['user_id']) {
                        $requestType = "bid";
                    } else {
                        $requestType = "offer";
                    }

                    $listOfOpenOrder .= '<td  style="border:2px solid #000;width:16%;">' . $requestType . '</td>';



                    if ($requests["min_bid"] != "") {
                        $minimumBid = $requests["min_bid"] . "%";
                    } else {
                        if ($requests["int_rate"] != "") {
                            $minimumBid = $requests["int_rate"];
                        } else {
                            $minimumBid = $requests["offer_rate"] . "%";
                        }
                    }

                    $listOfOpenOrder .= '<td  style="border:2px solid #000;width:13%;">' . $minimumBid . '</td>';


                    if (isset($requests['amount_display']) && $requests['amount_display'] != "") {
                        $amountOfRequest = $requests['amount_display'];
                    } else {
                        $amountOfRequest = $requests['amount'];
                    }

                    $listOfOpenOrder .= '<td  style="border:2px solid #000;width:16%;">' . $amountOfRequest . '</td>';




                    $listOfOpenOrder .= "</tr>";

                    echo $listOfOpenOrder;
                }
            } else {
                $listOfOpenOrder = "<tr style='width:100%;border: 1px solid black;'><i>No record found</i></tr>";
                echo $listOfOpenOrder;
            }
        }


        if ($status == "2") {

            //completed orders
            $completedOrder = $this->m_usermarketmarketplace->AllAcceptedRequestsForDates($user_id, $currencyType, $startDate, $endDate);

            $myAcceptedOrder = $this->m_usermarketmarketplace->allMyAcceptedOrderForDates($user_id, $currencyType, $startDate, $endDate);

            $result['completedOrders'] = array_merge($completedOrder, $myAcceptedOrder); // accepted request by me and for me
            $myarray = $result['completedOrders'];

            foreach ($myarray as $key => $row) {
                $mid[$key] = $row['time_accept'];
            }

            array_multisort($mid, SORT_DESC, $myarray);

            $completedOrders = $myarray;

            if (count($completedOrders) > "0") {
                
                
                foreach ($completedOrders as $requests) {
                    $listOfCompOrder = '<tr style="width:100% !important;text-align:center;">';
                    $listOfCompOrder .= '<td  style="border:2px solid #000;width:30%;">' . $requests['accepted_term'] . '</td> ';

                    if ($requests['borrower_id'] == $_SESSION['user_id']) {
                        $requestType = "borrowed";
                    } else {
                        $requestType = "lent";
                    }

                    $listOfCompOrder .= '<td class="col-md-1" style="border:2px solid #000;">' . $requestType . '</td>';

                    if ($requests['company_name'] != "") {
                        $companyName = $requests['company_name'];
                    } else {
                        $companyName = $requests['borrowerCompanyName'];
                    }

                    $listOfCompOrder .= '<td  style="border:2px solid #000;width:20%;">' . $companyName . '</td>';
                    $listOfCompOrder .= '<td  style="border:2px solid #000;width:30%;">' . $requests['ammount_accepted'] . '</td>';
                    $listOfCompOrder .= '<td  style="border:2px solid #000;width:20%;">' . $requests['interest_rate'] . '</td>';

                    
                    $listOfCompOrder .= '<td  style="border:2px solid #000;width:20%;" title="Value date">' . $requests['value_date'] . '</td>';
                    $listOfCompOrder .= '<td  style="border:2px solid #000;width:20%;" title="Maturity date">' . $requests['maturity_date'] . '</td>';
                    
                    $time_accept = $requests['time_accept'];
                    $timeAccepted = date("d.m.Y H:i:s", strtotime($time_accept));
                    $listOfCompOrder .='<td  style="border:2px solid #000;width:20%;">' . $timeAccepted . '</td>';
                    $listOfCompOrder .= '</tr>';
                    echo $listOfCompOrder;
                }
            } else {
                $listOfCompOrder = '<tr><td><i>No record found</i></td></tr>';
                echo $listOfCompOrder;
            }
        }

        if ($status == "3") {
            $cancelMyRequests = $this->m_usermarketmarketplace->allCancelMyRequestsForDates($user_id, $currencyType, $startDate, $endDate);
            $canceledMyOffers = $this->m_usermarketmarketplace->allCanceledMyOffersForDates($user_id, $currencyType, $startDate, $endDate);

            $canceledOrders = array_merge($cancelMyRequests, $canceledMyOffers);
            $myCancelArray = $canceledOrders;

            foreach ($myCancelArray as $key => $row) {
                $mid[$key] = $row['withdrawTime'];
            }

            array_multisort($mid, SORT_DESC, $myCancelArray);

            $cancelOrders = $myCancelArray;

            if (count($cancelOrders) > "0") {
                foreach ($cancelOrders as $requests) {
                    $listOfCancelOrder = '<tr style="width:90% !important;text-align:center;">
                                      <td  style="border:2px solid #000;width:30%;">' . $requests['term'] . '</td>';

                    if (isset($requests['borrowerId'])) {
                        $requestType = "bid";
                    } else {
                        $requestType = "offer";
                    }

                    $listOfCancelOrder .= '<td class="col-md-1" style="border:2px solid #000;">' . $requestType . '</td>';

                    if (isset($requests['borrowerCompany'])) {
                        $company = $requests['borrowerCompany'];
                    } if (isset($requests['lenderCompany'])) {
                        $company = $requests['lenderCompany'];
                    }


                    $listOfCancelOrder .= '<td  style="border:2px solid #000;width:20%;">' . $company . '</td>';
                    $listOfCancelOrder .='<td  style="border:2px solid #000;width:30%;">' . $requests['amount'] . '</td>';

                    $listOfCancelOrder .='<td  style="border:2px solid #000;width:20%;">' . str_replace('%', '', $requests['int_rate']) . '%</td>';

                    $listOfCancelOrder .='<td  style="border:2px solid #000;width:30%;">' . date("d.m.Y H:i:s", strtotime($requests['withdrawTime'])) . '</td>';

                    $listOfCancelOrder .= '</tr>';
                    echo $listOfCancelOrder;
                }
            } else {
                $listOfCancelOrder = '<tr><td><i>No record found</i></td></tr>';
                echo $listOfCancelOrder;
            }
        }

        if ($status == "4") {

            $borrowerDeniedToAccept = $this->m_usermarketmarketplace->allBorrowerDeniedToAcceptforDates($user_id, $currencyType, $startDate, $endDate);  // borrower denied to accept
            $lenderDealsDenied = $this->m_usermarketmarketplace->allLenderDealsDeniedForDates($user_id, $currencyType, $startDate, $endDate);

            $timedOutOrdersForBorrower = $this->m_usermarketmarketplace->allTimedOutOrdersForBorrowerForDates($user_id, $currencyType, $startDate, $endDate);  //timed out bid side for borrower
            $timedOutOrdersForLender = $this->m_usermarketmarketplace->allTimedOutOrdersForLender($user_id, $currencyType, $startDate, $endDate);    //timed out bid side for lender


            $timedOutOfferOrderForBorrower = $this->m_usermarketmarketplace->allTimedOutOfferOrderForBorrowerForDates($user_id, $currencyType, $startDate, $endDate);  //timed out bid side for borrower
            $timedOutOfferOrderForLender = $this->m_usermarketmarketplace->allTimedOutOfferOrderForLenderForDates($user_id, $currencyType, $startDate, $endDate);  //timed out bid side for lender

            $lenderDeniedOfferRequest = $this->m_usermarketmarketplace->allLenderDeniedOfferRequestForDates($user_id, $currencyType, $startDate, $endDate);  //cancel offers requests details lender side


            $borrowerDeniedOfferRequest = $this->m_usermarketmarketplace->allBorrowerDeniedOfferRequestForDates($user_id, $currencyType, $startDate, $endDate);  //cancel offers requests details borrower side

            $mergeTimedOutArrays = array_merge($timedOutOrdersForBorrower, $timedOutOrdersForLender, $borrowerDeniedToAccept, $lenderDealsDenied, $lenderDeniedOfferRequest, $borrowerDeniedOfferRequest, $timedOutOfferOrderForBorrower, $timedOutOfferOrderForLender);


            $result['timedOuts'] = $mergeTimedOutArrays;
            $myToArray = $result['timedOuts'];


            // sort array by time descending
            foreach ($myToArray as $key => $row) {
                $mid[$key] = $row['deniedDate'];
            }

            array_multisort($mid, SORT_DESC, $myToArray);


            $timedOut = $myToArray;


            if (count($timedOut) > 0) {
                foreach ($timedOut as $order) {
                    $listOfToOrder = '<tr style="width:100% !important;text-align:center;">';

                    if (isset($order['request_status']) && $order['request_status'] == "to") {
                        $responseStatus = "Time out";
                    } else {
                        $responseStatus = "Denied";
                    }


                    $listOfToOrder .= '<td  style="border:2px solid #000;width:20%;">' . $responseStatus . '</td> ';
                    $listOfToOrder .= '<td  style="border:2px solid #000;width:20%;">' . $order['term'] . '</td>';
                    if (isset($order['offerResponseId'])) {
                        $requestType = "offer";
                    } else {
                        $requestType = "bid";
                    }
                    $listOfToOrder .= '<td  style="border:2px solid #000;width:10%;">' . $requestType . '</td>';

                    if (isset($order['borrowerCompany'])) {
                        $marketMaker = $order['borrowerCompany'];
                    } else {
                        $marketMaker = $_SESSION['company_name'];
                    }
                    $listOfToOrder .= '<td  style="border:2px solid #000;width:20%;" title="Market maker">' . $marketMaker . '</td>';
                    $listOfToOrder .= '<td  style="border:2px solid #000;width:30%;">' . $order['amount'] . '</td>';
                    $listOfToOrder .= '<td  style="border:2px solid #000;width:25%;">' . str_replace('%', '', $order['int_rate']) . '%</td>';


                    if (isset($order['lenderCompany'])) {
                        $aggressor = $order['lenderCompany'];
                    } else {
                        $aggressor = $_SESSION['company_name'];
                    }
                    $listOfToOrder .= '<td  style="border:2px solid #000;width:30%;" title="Agressor">' . $aggressor . '</td>';
                    
                    $listOfToOrder .= '<td  style="border:2px solid #000;width:30%;" title="Value date">' . $order['value_date'] . '</td>';
                    $listOfToOrder .= '<td  style="border:2px solid #000;width:30%;" title="Maturity date">' . $order['maturity_date'] . '</td>';
                    
                    $listOfToOrder .= '<td  style="border:2px solid #000;width:30%;">' . date("d.m.Y H:i:s", strtotime($order['deniedDate'])) . '</td>';
                    $listOfToOrder .= '</tr>';
                    echo $listOfToOrder;
                }
            } else {
                $listOfToOrder = '<tr><td><i>No record found</i></td></tr>';
                echo $listOfToOrder;
            }
        }
    }
    
    
    







    
    /* notification popup all controller function for all type newnotification---------------------*/
     public function acceptDeal() {

    
        $user_id = $_SESSION['user_id'];

        $responceId = $_POST['responceId'];
        $currencyType = $_POST['currency'];
    
        $getMarityDatesOfResponse = $this->m_usermarketmarketplace->getMarityDatesOfResponse($responceId);
        $value_date = $getMarityDatesOfResponse['value_date'];
        $maturity_date = $getMarityDatesOfResponse['maturity_date'];


        $allDetailForRequestRes = $this->m_usermarketmarketplace->allDetailForRequestRes($responceId);

        $amountInMarketRequest = $allDetailForRequestRes[0]['amount_display'];

        $explodeAmount = explode(' ', $amountInMarketRequest);

        $amountRequestedHere = $explodeAmount[0];

        $requestId = $_POST['requestId'];



        $requestedAmount = $this->m_usermarketmarketplace->checkRequestedAmount($requestId);

        $totalAmount = $requestedAmount[0]['amount_display'];

        $amountRequest = explode(" ", $totalAmount);

        $amountRequestMoney = $amountRequest[0];

        $amountRequestText = $amountRequest[1];


        $amountRequestText = "mio";

        if ($amountRequestText == "mio") {

            $amountRequested = $amountRequestMoney * 1000000;
        }


        $offeredAmount = $this->m_usermarketmarketplace->offeredAmount($responceId);
        $totalOfferedAmount = $offeredAmount[0]['amount'];
        $amountOffer = explode(" ", $totalOfferedAmount);

        $amountOfferMoney = $amountOffer[0];

        $amountOfferText = $amountOffer[1];


        if ($amountOfferText == "mio") {

            $amountOfferedMoney = $amountOfferMoney * 1000000;
        }




        $responsefullDetails = $this->m_usermarketmarketplace->responsefullDetails($responceId);



        $request_id = $responsefullDetails[0]['request_id'];

        $lender_id = $responsefullDetails[0]['lender_id'];

        $amount = $responsefullDetails[0]['amount'];

        $int_rate = $responsefullDetails[0]['int_rate'];

        $term = $responsefullDetails[0]['term'];



        $dateAcceptance = date("d.m.Y");

        $accpetedArray = array(
            "borrower_id" => $user_id,
            "lender_id" => $lender_id,
            "response_id" => $responceId,
            "ammount_accepted" => $amount,
            "interest_rate" => $int_rate,
            "accepted_term" => $term,
            "value_date" => $value_date,
            "maturity_date" => $maturity_date,
            "date_accepted" => $dateAcceptance,
            "accepted_by" => "deal",
            "currency_type" => $currencyType
        );





        $accepetFullDeal = $this->m_usermarketmarketplace->accepetFullDeal($accpetedArray);

        //$offerAccpt                 =  $this->m_usermarketmarketplace->acceptDeal($requestId);

        $acceptedResponse = $this->m_usermarketmarketplace->acceptedResponse($responceId);

        $this->m_usermarketmarketplace->allNotificationReaded($responceId);

        $acceptedDataForPdf = $this->m_usermarketmarketplace->acceptedDealDataForPdf($responceId);

        $requestId = $acceptedDataForPdf[0]['requestId'];

        $borowwerDetailForDeal = $this->m_usermarketmarketplace->borowwerDetailForDeal($requestId);

        $notificationArray = array("req_id" => $requestId, "responce_id" => $responceId, "to" => $lender_id, 'from' => $user_id, "msg" => "");

        $this->m_usermarketmarketplace->notificationSend($notificationArray);

        echo json_encode($allDetailForRequestRes);


        //to make pdf
        // $accepetFullDeal = 1;
        $responseForRequestLender = $this->m_usermarketmarketplace->responseForBidPdf($accepetFullDeal);

        $lenderEmail = $responseForRequestLender[0]['lenderEmail'];


        //$responseLenderDetails         = $this->m_usermarketmarketplace->responseLenderDetails($offered_id); 

        $response_id = $responseForRequestLender[0]['response_id'];

        $getRequestIdOfResponse = $this->m_usermarketmarketplace->getRequestIdOfResponse($response_id);
        $requestId = $getRequestIdOfResponse['request_id'];

        $getRequestDetail = $this->m_usermarketmarketplace->getRequestDetail($requestId);

        //$responseLenderDetails         = $this->m_usermarketmarketplace->responseLenderBidDetails($response_id); 
        $borrowerEmail = $getRequestDetail[0]['borrowerEmail'];

        //$lenderEmail."/////".$borrowerEmail;

        $fullResponcePdf = array_merge($responseForRequestLender, $getRequestDetail);
        $dateTm = date("YmdHis");
        $this->createPdfForOffer($fullResponcePdf, $dateTm, $getMarityDatesOfResponse);

        $pdfInsertArr = array("pdf_name" => "mmPdf-" . $dateTm);
        $this->m_usermarketmarketplace->insertPdfName($pdfInsertArr, $accepetFullDeal);

        $pdfDataForInserted = $this->m_usermarketmarketplace->pdfDataForInserted($accepetFullDeal);

        $pdfName = $pdfDataForInserted[0]['pdf_name'];


        $base_url = base_url();

        $to = "$borrowerEmail, $lenderEmail";

        // $to        = "vikas@webmobsoft.com";

        $subject = "Instimatch AG : Deal Confirmed";

        $message = "Hi <br/><br/>";

        $message .= "Please check below PDF as an details of deal<br/>";

        $message .= "<a href='" . $base_url . "assets/mm_marketFiles/" . $pdfName . ".pdf'>Click Here</a><br/><br/>";

        $message .= 'Thanks and Best Regards <br/>';

        $message .= "Instimatch AG <br>";

        $message .= "Riedm&uuml;hlestrasse 8  <br>";

        $message .= "8305 Dietlikon  <br>";

        

        $message .= "admin@instimatch.ch";


        $header = "From: admin@instimatch.ch \r\n";

        if ($borowerAlternativeMail == "" && $lenderAlterNativeEmail == "") {
            $header .= "Cc: info@instimatch.ch \r\n";
        }
        if ($borowerAlternativeMail != "" && $lenderAlterNativeEmail == "") {
            $header .= "Cc: info@instimatch.ch , $borowerAlternativeMail \r\n";
        }
        if ($lenderAlterNativeEmail != "" && $borowerAlternativeMail == "") {
            $header .= "Cc: info@instimatch.ch , $lenderAlterNativeEmail \r\n";
        }
        if ($lenderAlterNativeEmail != "" && $borowerAlternativeMail != "") {
            $header .= "Cc: info@instimatch.ch , $lenderAlterNativeEmail ,$borowerAlternativeMail \r\n";
        }


        // $header .= "Bcc: $borowerAlternativeMail";
        //$header .= "Bcc: $lenderAlterNativeEmail";
        $header .= "MIME-Version: 1.0\r\n";

        $header .= "Content-type: text/html\r\n";
        $retval = mail($to, $subject, $message, $header);
    }
    function dealAccepted() {

        $notificationId = $_POST['notificationId'];

        $notificationDeatil = $this->m_usermarketmarketplace->notificationDeatil($notificationId);
        $requestId = $notificationDeatil[0]['req_id'];
        $requestsAmountDetail = $this->m_usermarketmarketplace->requestsAmountDetail($requestId);
        $amontReq = $requestsAmountDetail['amount_display'];
        $explodeRequestedAmnt = explode(" ", $amontReq);

        $amount = $explodeRequestedAmnt[0];

        if ($amount <= 0) {
            //$offerAccpt     =  $this->m_usermarketmarketplace->acceptDeal($requestId);
        }


        $responceId = $notificationDeatil[0]['responce_id'];
        $fullDetails = $this->m_usermarketmarketplace->allDetailForRequestRes($responceId);
        $this->m_usermarketmarketplace->notificationsReaded($notificationId);
        echo json_encode($fullDetails);
    }
    
    
    
    
    /*--------------------------------new functions to be uploaded---------------------------------------*/
    function allRequestsTopButtons()
    {
        $currencyType = $_SESSION['currencrType'];
        $user_id = $_SESSION['user_id'];
        
        //$buttonsId = 0;
        
        for($buttonsId =0 ; $buttonsId <3 ;$buttonsId++)
        {
            $allRequestsTopButtons[] =  $this->m_usermarketmarketplace->allRequestsTopButtons($user_id,$currencyType,$buttonsId);
        }
      
       echo $str = implode(',',array_map('implode',$allRequestsTopButtons));
        
   }
   function clickTab()
   {
       $tabId = $_POST['tabId'];
      $cId = $_POST['cId'];
       
       $getAllMarketTabsByTabId = $this->m_usermarketmarketplace->getAllMarketTabsByTabId($tabId);
      
       $currencyType = $_SESSION['currencrType'];
       $user_id = $_SESSION['user_id'];
       
       if($tabId != "2")
       {
           $width = 90/ count($getAllMarketTabsByTabId);
       }
       else
       {
           $width= 20;
       }
       
       
      $subButtons = '<div id="weeks-btn">';
       foreach($getAllMarketTabsByTabId as $marketSubTabs)
       {
         $subTabId   =  $marketSubTabs['id'];
         $subTabName =  $marketSubTabs['value'];
         $getTotalRequestsBySubTabs = $this->m_usermarketmarketplace->getTotalRequestsBySubTabs($currencyType,$subTabName);
         $getTotalOfferBySubTabs = $this->m_usermarketmarketplace->getTotalOfferBySubTabs($currencyType,$subTabName);
         if($subTabId !='15'){
if($getTotalOfferBySubTabs['totaloffers'] > 3){
    $data=$getTotalRequestsBySubTabs['totalRequests']+3;
}
else {
    $data=$getTotalOfferBySubTabs['totaloffers'] + $getTotalRequestsBySubTabs['totalRequests'];
}
}
else {
     $data=$getTotalOfferBySubTabs['totaloffers'] + $getTotalRequestsBySubTabs['totalRequests'];
}
         
         //$subButtons = "<button class='subTab".$subTabId."' style='float:left' onclick='openRequestsBySubTab(".$subTabId.")'>".$subTabName.' '."(".$getTotalRequestsBySubTabs['totalRequests'].")</button>";
         echo "<input type='hidden' id='brokenid' val='".$data."'>";
        if($subTabName !="Broken Date"){
         if($cId==$subTabId){

          $subButtons = ' <div class="many-bts">
                <a onclick="" class="week-vbtn" href="#">
                  <button style="width:'.$width.'%" class="subTab'.$subTabId.' activeTab1" onclick="openRequestsBySubTab('.$subTabId.')"> '.$subTabName.' <br/>('.$data.')</button>
                </a>
                          </div>'; 
         }
        else {
          
             $subButtons = ' <div class="many-bts">
                <a onclick="" class="week-vbtn" href="#">
                  <button style="width:'.$width.'%" class="subTab'.$subTabId.'" onclick="openRequestsBySubTab('.$subTabId.')"> '.$subTabName.' <br/>('.$data.')</button>
                </a>
                          </div>'; 
        }
          }
         
         //$subButons()
        echo $subButtons;
       }
       
   }
    function subTabDates()
   {
       $subTabId     = $_POST['subTabId'];
       $currencyType = $_SESSION['currencrType'];
       $user_id      = $_SESSION['user_id'];
       
       
       $getMaketSubTabName = $this->m_usermarketmarketplace->getMaketSubTabName($subTabId);
       
       
       $data['showDatesManual'] = $getMaketSubTabName['showDatesManual'];
       $data['value']           = $getMaketSubTabName['value'];


      
           
            
              if($data['showDatesManual'] == "n")
              {
                
           $termOfMont = str_replace('s', '', $data['value']);

                $termOfMonth = $termOfMont . "s";



                $date = date("d.m.Y"); //today

                $date1 = strtotime($date);  // coverted in to date format

                $date2 = date("l", $date1);  // today day name 

                $date3 = strtolower($date2);



                if ($date3 == "friday" || $date3 == "saturday") {
                    $dateSelet = date('d.m.Y', strtotime($date . ' +2 Weekday'));
                } else {
                    $dateSelet = date('d.m.Y', strtotime($date . ' +2 Weekday'));
                }



                $currency = $_SESSION['currencrType'];

                if ($currency == $_SESSION['currencrType']) {

                    $publicHolidays = $this->m_usermarketmarketplace->publicHolidays($currency);

                    $allHolidaysArray = array_map('current', $publicHolidays);  // list of all  public holidays form database

                    $allHolidayCount = count($allHolidaysArray); //count all the holidays
                }

                // calculating if the date exist in the public holidays array ..if it exist add 1 week day to it

                $twoDays = $dateSelet;

                $y = 1;

                while ($y <= $allHolidayCount) {

                    if (in_array($twoDays, $allHolidaysArray)) {

                        $twoDays = date('d.m.Y', strtotime($twoDays . ' +1 Weekday'));
                    }

                    $y++;
                }
                /* calculating if the date exist in the public holidays array ..if it exist add 1 week day to it */



                /* add 7 days form the value date  and check for saturday and sunday */
                $dateMonth = date('d.m.Y', strtotime($twoDays . '+ ' . $termOfMonth . 's'));

                $dateMonthTotime = strtotime($dateMonth);

                $dateMonthMatUpper = date("l", $dateMonthTotime);

                $dateMonthMat = strtolower($dateMonthMatUpper);



                if ($dateMonthMat == "saturday" || $dateMonthMat == "sunday") {

                    $maturityDay = date('d.m.Y', strtotime($dateMonth . ' +1 Weekday'));
                } else {

                    $maturityDay = $dateMonth;
                }


                $maturity = $maturityDay;

                $z = 1;

                while ($z <= $allHolidayCount) {

                    if (in_array($maturity, $allHolidaysArray)) {

                        $maturity = date('d.m.Y', strtotime($maturity . ' +1 Weekday'));
                    }

                    $z++;
                }

                $maturityDay = $maturity;
      

              }
              else
              {

            $getManualDates = $this->m_usermarketmarketplace->getManualDates($subTabId);
             // print_r($getManualDates);
                $data["start_date"] =  $getManualDates['start_date'];
                $data['value_date'] = $getManualDates['value_date'];
                $data['maturity_date'] = $getManualDates['maturity_date'];
                $manualSelectedDate = $data["start_date"];  //if date selected by manual

                $manualdate1 = strtotime($manualSelectedDate);  // coverted in to date format

                $manualdate2 = date("l", $manualdate1);  // today day name 

                $manualdate3 = strtolower($manualdate2);



                if ($manualdate3 == "friday" || $manualdate3 == "saturday") {

                    // if today is friday or saturday then add 1 weekay i.e Monday

                    $dateSeleted = date('d.m.Y', strtotime($manualSelectedDate . ' +1 Weekday'));
                } else {

                    // for all other add 2 week days

                    $dateSeleted = date('d.m.Y', strtotime($manualSelectedDate . ' +2 Weekday'));
                }


                $twoDaysManual = $dateSeleted;

                if ($data["start_date"] == "") {
                    $manualToday = "N/A";

                    $twoDaysManual = "N/A";
                } else {
                    $manualToday = $data["start_date"];
                    $twoDaysManual = $twoDaysManual;
                }

                if ($data['value_date'] == "") {
                    $valueDAte = "N/A";
                } else {
                    $valueDAte = $data['value_date'];
                }

                if ($data["maturity_date"] == "") {
                    $manualMat = "N/A";
                } else {
                    $manualMat = $data["maturity_date"];
                }
             }
             
              if (strtolower($data['value']) == "o/n") 
            {
                $date = date("d.m.Y");
                $twoDays = $date;
                $maturityDay = date('d.m.Y', strtotime($twoDays . ' +1 Weekday'));
                
            }
            if (strtolower($data['value']) == "t/n") 
            {
                $date = date("d.m.Y");
                $twoDays = date('d.m.Y', strtotime($date . ' +1 Weekday'));
                $maturityDay = date('d.m.Y', strtotime($twoDays . ' +1 Weekday'));
            }
          if (strtolower($data['value']) == "s/n") 
            {
                $date = date("d.m.Y");

                $dateSelet = strtotime($date);
               
                $valueDates = strtolower(date("l", $dateSelet));  // today day name 

                
                
                if ($valueDates == "friday" || $valueDates == "saturday") {
                    $dateSelet = date('d.m.Y', strtotime($date . ' +2 Weekday'));
                } else {

                    $dateSelet = date('d.m.Y', strtotime($date . ' +2 Weekday'));
                }
                $twoDays = $dateSelet;
                // echo $dateSelet;
                $maturityDay = date('d.m.Y', strtotime($dateSelet . ' +1 Weekday'));
            }
            
             
           
            
             if ($data['showDatesManual'] == "n") {
                 
                if ($subTabId == "15") {
                   
                    $showMarketTabs = '';
                } else {
                   echo "<input type='hidden'   id='middle' value='" . $data['value'] . "' />";
                     echo "<input type='hidden'   id='mySelectedMaturityDate' value='" . $maturityDay . "' />";
                     echo "<input type='hidden'   id='mySelectedValueDate' value='" . $twoDays . "' />";
                     $showMarketTabs = '<div class="col-md-12 datesTab"><span class="col-md-4">Deal Date:' . $date . '</span><span class="col-md-4">Value Date:' . $twoDays . '</span><span class="col-md-4">Maturity Date:' . $maturityDay . '</span></div>';
                }
            }


            if ($data['showDatesManual'] == "y") {
                if ($subTabId == "15") {
                      
                    $showMarketTabs = '';
                } else {
                    
                     echo "<input type='hidden'   id='middle' value='" . $data['value'] . "' />";
                     echo "<input type='hidden'   id='mySelectedMaturityDate' value='" . $manualMat . "' />";
                     echo "<input type='hidden'   id='mySelectedValueDate' value='" . $valueDAte  . "' />";
                    $showMarketTabs = '<div class="col-md-12 datesTab"><span class="col-md-4">Deal Date:' . $manualToday . '</span><span class="col-md-4">Value Date:' . $valueDAte . '</span><span class="col-md-4">Maturity Date:' . $manualMat . '</span></div>';
                }
            }
             echo $showMarketTabs;
            
    }
   function subTabDates1()
   {
       $subTabId     = $_POST['subTabId'];
       $currencyType = $_SESSION['currencrType'];
       $user_id      = $_SESSION['user_id'];
       
       
       $getMaketSubTabName = $this->m_usermarketmarketplace->getMaketSubTabName($subTabId);
       
       
       $data['showDatesManual'] = $getMaketSubTabName['showDatesManual'];
       $data['value']           = $getMaketSubTabName['value'];
       
      
            if (strtolower($data['value']) == "o/n") 
            {
                $date = date("d.m.Y");
                $twoDays = $date;
                $maturityDay = date('d.m.Y', strtotime($twoDays . ' +1 Weekday'));
            }
            else if (strtolower($data['value']) == "t/n") 
            {
                $date = date("d.m.Y");
                $twoDays = date('d.m.Y', strtotime($date . ' +1 Weekday'));
                $maturityDay = date('d.m.Y', strtotime($twoDays . ' +1 Weekday'));
            }
            else if (strtolower($data['value']) == "s/n") 
            {
                $date = date("d.m.Y");

                $dateSelet = strtotime($date);
               
                $valueDates = strtolower(date("l", $dateSelet));  // today day name 

                
                
                if ($valueDates == "friday" || $valueDates == "saturday") {
                    $dateSelet = date('d.m.Y', strtotime($date . ' +2 Weekday'));
                } else {

                    $dateSelet = date('d.m.Y', strtotime($date . ' +2 Weekday'));
                }
                $twoDays = $dateSelet;
                // echo $dateSelet;
                $maturityDay = date('d.m.Y', strtotime($dateSelet . ' +1 Weekday'));
            }
            else 
            {
              if($data['showDatesManual'] == "n")
              {
                
           $termOfMont = str_replace('s', '', $data['value']);

                $termOfMonth = $termOfMont . "s";



                $date = date("d.m.Y"); //today

                $date1 = strtotime($date);  // coverted in to date format

                $date2 = date("l", $date1);  // today day name 

                $date3 = strtolower($date2);



                if ($date3 == "friday" || $date3 == "saturday") {
                    $dateSelet = date('d.m.Y', strtotime($date . ' +2 Weekday'));
                } else {
                    $dateSelet = date('d.m.Y', strtotime($date . ' +2 Weekday'));
                }



                $currency = $_SESSION['currencrType'];

                if ($currency == $_SESSION['currencrType']) {

                    $publicHolidays = $this->m_usermarketmarketplace->publicHolidays($currency);

                    $allHolidaysArray = array_map('current', $publicHolidays);  // list of all  public holidays form database

                    $allHolidayCount = count($allHolidaysArray); //count all the holidays
                }

                // calculating if the date exist in the public holidays array ..if it exist add 1 week day to it

                $twoDays = $dateSelet;

                $y = 1;

                while ($y <= $allHolidayCount) {

                    if (in_array($twoDays, $allHolidaysArray)) {

                        $twoDays = date('d.m.Y', strtotime($twoDays . ' +1 Weekday'));
                    }

                    $y++;
                }
                /* calculating if the date exist in the public holidays array ..if it exist add 1 week day to it */



                /* add 7 days form the value date  and check for saturday and sunday */
                $dateMonth = date('d.m.Y', strtotime($twoDays . '+ ' . $termOfMonth . 's'));

                $dateMonthTotime = strtotime($dateMonth);

                $dateMonthMatUpper = date("l", $dateMonthTotime);

                $dateMonthMat = strtolower($dateMonthMatUpper);



                if ($dateMonthMat == "saturday" || $dateMonthMat == "sunday") {

                    $maturityDay = date('d.m.Y', strtotime($dateMonth . ' +1 Weekday'));
                } else {

                    $maturityDay = $dateMonth;
                }


                $maturity = $maturityDay;

                $z = 1;

                while ($z <= $allHolidayCount) {

                    if (in_array($maturity, $allHolidaysArray)) {

                        $maturity = date('d.m.Y', strtotime($maturity . ' +1 Weekday'));
                    }

                    $z++;
                }

                $maturityDay = $maturity;


              }
              else
              {
                  
                $getManualDates = $this->m_usermarketmarketplace->getManualDates($subTabId);
                $data["start_date"] =  $getManualDates['start_date'];
                $data['value_date'] = $getManualDates['value_date'];
                
                $manualSelectedDate = $data["start_date"];  //if date selected by manual

                $manualdate1 = strtotime($manualSelectedDate);  // coverted in to date format

                $manualdate2 = date("l", $manualdate1);  // today day name 

                $manualdate3 = strtolower($manualdate2);



                if ($manualdate3 == "friday" || $manualdate3 == "saturday") {

                    // if today is friday or saturday then add 1 weekay i.e Monday

                    $dateSeleted = date('d.m.Y', strtotime($manualSelectedDate . ' +1 Weekday'));
                } else {

                    // for all other add 2 week days

                    $dateSeleted = date('d.m.Y', strtotime($manualSelectedDate . ' +2 Weekday'));
                }


                $twoDaysManual = $dateSeleted;

                if ($data["start_date"] == "") {
                    $manualToday = "N/A";

                    $twoDaysManual = "N/A";
                } else {
                    $manualToday = $data["start_date"];
                    $twoDaysManual = $twoDaysManual;
                }

                if ($data['value_date'] == "") {
                    $valueDAte = "N/A";
                } else {
                    $valueDAte = $data['value_date'];
                }

                if ($data["maturity_date"] == "") {
                    $manualMat = "N/A";
                } else {
                    $manualMat = $data["maturity_date"];
                }
             }
             
             
            
             
            }
            
             if ($data['showDatesManual'] == "n") {
                if ($subTabId == "15") {
                    echo "<input type='hidden'   id='mySelectedMaturityDate' value='" . $maturityDay . "' />";
                     echo "<input type='hidden'   id='mySelectedValueDate' value='" . $twoDays . "' />";
                    $showMarketTabs = '<div class="col-md-12 datesTab"><span class="col-md-6">Deal Date: '.$date . '</span><span class="col-md-6">Value Date: ' . $twoDays . '</span></div>';
                } else {
                    echo "<input type='hidden'   id='mySelectedMaturityDate' value='" . $maturityDay . "' />";
                     echo "<input type='hidden'   id='mySelectedValueDate' value='" . $twoDays . "' />";
                    $showMarketTabs = '<div class="col-md-12 datesTab"><span class="col-sm-4">Deal Date: ' . $date . '</span><span class="col-sm-4">Value Date: ' . $twoDays . '</span><span class="col-sm-4">Maturity Date: ' . $maturityDay . '</span></div>';
                }
            }


            if ($data['showDatesManual'] == "y") {
                if ($subTabId == "15") {
                    echo "<input type='hidden'   id='mySelectedMaturityDate' value='" . $maturityDay . "' />";
                     echo "<input type='hidden'   id='mySelectedValueDate' value='" . $twoDays . "' />";
                    $showMarketTabs = '<div class="col-md-12 datesTab"><span class="col-md-6">Deal Date:' . $manualToday . '</span>class="col-md-6">Value Date:' . $valueDAte . '</span></div>';
                } else {
                     echo "<input type='hidden'   id='mySelectedMaturityDate' value='" . $manualMat . "' />";
                     echo "<input type='hidden'   id='mySelectedValueDate' value='" . $valueDAte . "' />";
                    $showMarketTabs = '<div class="col-md-12 datesTab"><span class="col-md-4">Deal Date:' . $manualToday . '</span><span class="col-md-4">Value Date:' . $valueDAte . '</span><span class="col-md-4">Maturity Date:' . $manualMat . '</span></div>';
                }
            }
             echo $showMarketTabs;
            
    }
    function getAllRequestsShow()
    {
        $currencyType = $_SESSION['currencrType'];
        $user_id   = $_SESSION['user_id'];
        $subTabId  = $_POST['subTabId'];
        $cname1=  $_SESSION['company_name'];
        /* Market bids ------------ */
        
        
        $getMaketSubTabName = $this->m_usermarketmarketplace->getMaketSubTabName($subTabId);
        $subTabName = $getMaketSubTabName['value'];
        $result['marketRequests'] = $marketRequests = $this->m_usermarketmarketplace->allMarketRequests($user_id, $currencyType,$subTabName,$subTabId);

  //   print_r($marketRequests);

    if(count($marketRequests) > 0)
    {
        foreach ($marketRequests as $request) 
        {

          
              $hide="";
             $result1 = $marketRequests = $this->m_usermarketmarketplace->mymarketsettings($cname1, $currencyType,$request['borrower_id']);
//print_r($result1);
//echo count($result1);

             if(count($result1) > 0) {
              
             if($result1[0]['bid'] =="Y") {
                $hide="";
             $min_bid= $request['min_bid'] + ($result1[0]['Bid-Spread']/100);
                } 
                else {
                    $min_bid= $request['min_bid']; 
                     $hide="hideme1";
                }
              }
              else {
                $hide="hideme1";
               $min_bid= $request['min_bid']; 
              }

             $getUserFedafinRating = $this->m_usermarketmarketplace->getUserFedafinRating($request['borrower_id']);
             
             
             if ($getUserFedafinRating['LT_Mnemonic'] != "") 
             {
                   $rating = $getUserFedafinRating['LT_Mnemonic'];
             } else 
             {
                    $rating = "N/A";
             }
                    
                    
         if ($getUserFedafinRating['rating_name'] != "") 
             {
                 $fRating = $getUserFedafinRating['rating_name'];
             } else 
             {
                 $fRating = "N/A";
             }
             
             if($subTabId =="15"){
            // echo $request['borrower_id']."...".$_SESSION['user_id'];
             if ($request['borrower_id'] == $_SESSION['user_id']) 
                 {
                        $onclickBidRequests = "";
                        $myRequestsClass = "myRequestClass1";
                        $biddingClass    = "gredOutBid";
//echo $request['maturity_date']."gfddf";
         $dataToFeed = '<div style="font-size: 13px; margin-top: 2px; height: 35px; width: 100%;" class="col-md-12 mycreatedbids">
                         <div  class="comany_name1 bankz-name mioz1' . $request['id'] . ' ' . $myRequestsClass . '" >' . $request['req_name'] . '</div>
                         <div  class="rating nnaz mioz1" style="display:none">' . $rating.'<br/>'. $fRating. '</div>
                               
                         <div  class="amountDisplay mioz mioz1 text-center" style="">' . $request['amount_display'] . '</div>
                          <div  class="valueDate mioz mioz1 vd text-center" style="">' . $request['value_date'] . '</div>
                        <div  class="maturity_date mioz mioz1 vd text-center" style="">' . $request['maturity'] . '</div>
                         <div  class="rating prsentaz mioz1 text-center" style="">' .$request['min_bid']  . '%</div>
                         <div class="bidding padding dealButton dealzx '.$biddingClass.' mioz1 text-center" style=""  title="Enter offer" onclick="' . $onclickBidRequests . '">Deal</div>  
                         
                         
            </div>
            

                        <input type="hidden" name ="requestsId" id="requestsId" value="' . $request['id'] . '"/> 
                        <input type="hidden" name ="displayamont' . $request['id'] . '" id="displayamont' . $request['id'] . '" value="' . $request['amount_display'] . '"/> 
                        <input type="hidden" name ="compname' . $request['id'] . '" id="compname' . $request['id'] . '" value="' . $request['company_name'] . '"/> 
                        <input type="hidden" name ="onDate' . $request['id'] . '" id="onDate' . $request['id'] . '" value="' . $request['on_date'] . '"/>  
                        <input type="hidden" name ="minBid' . $request['id'] . '" id="minBid' . $request['id'] . '" value="' . $request['min_bid'] . '"/> 
                        <input type="hidden" name ="notes' . $request['id'] . '" id="notes' . $request['id'] . '" value="' . $request['notes'] . '"/>  
                        <input type="hidden" name ="reqName' . $request['id'] . '" id="reqName' . $request['id'] . '" value="' . $request['req_name'] . '"/>
                        <input type="hidden" id="thisTabId' . $subTabId . '" value="' . $subTabName. '"/>

    <input type="hidden" name ="valdate1' . $request['id'] . '" id="valdate1' . $request['id'] . '" value="' . $request['value_date'] . '"/> 
    <input type="hidden" name ="matdate1' . $request['id'] . '" id="matdate1' . $request['id'] . '" value="' . $request['maturity'] . '"/> 
                        </div>
                       

                ';

                 } else 
                 {
                    
                        $onclickBidRequests = "openBidOfferPopUp(".$request['id']. "," .$subTabId.",1)";
                        $myRequestsClass = "";
                        $biddingClass    = "";

                         $dataToFeed = '<div style="font-size: 13px; margin-top: 2px; height: 35px; width: 100%;" class="col-md-12 '.$hide.'">
                         <div  class="comany_name1 bankz-name mioz1' . $request['id'] . ' ' . $myRequestsClass . '" >' . $request['req_name'] . '</div>
                         <div  class="rating nnaz mioz1" style="display:none">' . $rating.'<br/>'. $fRating. '</div>
                               
                         <div  class="amountDisplay mioz mioz1" style="">' . $request['amount_display'] . '</div>
                          <div  class="valueDate mioz mioz1 vd" style="">' . $request['value_date'] . '</div>
                        <div  class="maturity_date mioz mioz1 vd" style="">' . $request['maturity'] . '</div>
                         <div  class="rating prsentaz mioz1" style="">' . $min_bid . '%</div>
                         <div class="bidding padding dealButton dealzx '.$biddingClass.' mioz1" style=""  title="Enter offer" onclick="' . $onclickBidRequests . '">Deal</div>  
                         
                         
            </div>
            

                        <input type="hidden" name ="requestsId" id="requestsId" value="' . $request['id'] . '"/> 
                        <input type="hidden" name ="displayamont' . $request['id'] . '" id="displayamont' . $request['id'] . '" value="' . $request['amount_display'] . '"/> 
                        <input type="hidden" name ="compname' . $request['id'] . '" id="compname' . $request['id'] . '" value="' . $request['company_name'] . '"/> 
                        <input type="hidden" name ="onDate' . $request['id'] . '" id="onDate' . $request['id'] . '" value="' . $request['on_date'] . '"/>  
                        <input type="hidden" name ="minBid' . $request['id'] . '" id="minBid' . $request['id'] . '" value="' . $min_bid . '"/> 
                        <input type="hidden" name ="notes' . $request['id'] . '" id="notes' . $request['id'] . '" value="' . $request['notes'] . '"/>  
                        <input type="hidden" name ="reqName' . $request['id'] . '" id="reqName' . $request['id'] . '" value="' . $request['req_name'] . '"/>
                        <input type="hidden" id="thisTabId' . $subTabId . '" value="' . $subTabName. '"/>
                         <input type="hidden" name ="valdate1' . $request['id'] . '" id="valdate1' . $request['id'] . '" value="' . $request['value_date'] . '"/> 
    <input type="hidden" name ="matdate1' . $request['id'] . '" id="matdate1' . $request['id'] . '" value="' . $request['maturity'] . '"/> </div>';
                 } 
             }
             else {
  if ($request['borrower_id'] == $_SESSION['user_id']) 
                 {
                    
                        $onclickBidRequests = "";
                        $myRequestsClass = "myRequestClass1";
                        $biddingClass    = "gredOutBid";

         $dataToFeed = '<div style="width:83.5%;font-size:13px;margin-top:2px;height:35px;" class="col-md-12 mycreatedbids">
                         <div style="width: 112px; text-align: left; font-size: 13px; padding-top: 5.5px;"  class="text-center comany_name comany_name bankz-name' . $request['id'] . ' ' . $myRequestsClass . '" >' . $request['req_name'] . '</div>
                         <div  class="rating nnaz text-center" style="text-align:center !important;display:none">' . $rating.'<br/>'. $fRating. '</div>
                         <div class="text-center bidding padding dealButton dealzx '.$biddingClass.'" style="text-align: center !important; width: 70px; margin-left: -17px;"  title="Enter offer" onclick="' . $onclickBidRequests . '">Deal</div>        
                         <div  class="text-center amountDisplay mioz" style="float: left; text-align: center; width: 23%;">' . $request['amount_display'] . '</div>
                         <div  class="text-center rating prsentaz" style="text-align: center !important; padding-left: 7%; width: 32%;">' . $request['min_bid'] . '%</div>
                         
                         
            </div>
            

                        <input type="hidden" name ="requestsId" id="requestsId" value="' . $request['id'] . '"/> 
                        <input type="hidden" name ="displayamont' . $request['id'] . '" id="displayamont' . $request['id'] . '" value="' . $request['amount_display'] . '"/> 
                        <input type="hidden" name ="compname' . $request['id'] . '" id="compname' . $request['id'] . '" value="' . $request['company_name'] . '"/> 
                        <input type="hidden" name ="onDate' . $request['id'] . '" id="onDate' . $request['id'] . '" value="' . $request['on_date'] . '"/>  
                        <input type="hidden" name ="minBid' . $request['id'] . '" id="minBid' . $request['id'] . '" value="' . $request['min_bid'] . '"/> 
                        <input type="hidden" name ="notes' . $request['id'] . '" id="notes' . $request['id'] . '" value="' . $request['notes'] . '"/>  
                        <input type="hidden" name ="reqName' . $request['id'] . '" id="reqName' . $request['id'] . '" value="' . $request['req_name'] . '"/></div>
                        <input type="hidden" id="thisTabId' . $subTabId . '" value="' . $subTabName. '"/>
                ';

                 } else 
                 {

             
                        $onclickBidRequests = "openBidOfferPopUp(".$request['id']. "," .$subTabId.",0)";
                        $myRequestsClass = "";
                        $biddingClass    = "";

                         $dataToFeed = '<div style="width:83.5%;font-size:13px;margin-top:2px;height:35px;background:#051522" class="col-md-12 '.$hide.'">
                         <div  class="comany_name comany_name bankz-name ' . $request['id'] . ' ' . $myRequestsClass . '" style="width: 112px; text-align: left; font-size: 13px; padding-top: 5.5px;" >' . $request['req_name'] . '</div>
                         <div  class="rating nnaz" style="display:none">' . $rating.'<br/>'. $fRating. '</div>
                         <div class="bidding padding dealButton dealz '.$biddingClass.'" style="text-align: center !important; width: 70px; margin-left: -17px;"  title="Enter offer" onclick="' . $onclickBidRequests . '">Deal</div>        
                         <div  class="amountDisplay mioz" style="float: left; text-align: center; width: 23%;">' . $request['amount_display'] . '</div>
                         <div  class="rating prsentaz" style="text-align: center !important; padding-left: 7%; width: 32%;">' . $min_bid . '%</div>
                         
                         
            </div>
            

                        <input type="hidden" name ="requestsId" id="requestsId" value="' . $request['id'] . '"/> 
                        <input type="hidden" name ="displayamont' . $request['id'] . '" id="displayamont' . $request['id'] . '" value="' . $request['amount_display'] . '"/> 
                        <input type="hidden" name ="compname' . $request['id'] . '" id="compname' . $request['id'] . '" value="' . $request['company_name'] . '"/> 
                        <input type="hidden" name ="onDate' . $request['id'] . '" id="onDate' . $request['id'] . '" value="' . $request['on_date'] . '"/>  
                        <input type="hidden" name ="minBid' . $request['id'] . '" id="minBid' . $request['id'] . '" value="' . $min_bid . '"/> 
                        <input type="hidden" name ="notes' . $request['id'] . '" id="notes' . $request['id'] . '" value="' . $request['notes'] . '"/>  
                        <input type="hidden" name ="reqName' . $request['id'] . '" id="reqName' . $request['id'] . '" value="' . $request['req_name'] . '"/></div>
                        <input type="hidden" id="thisTabId' . $subTabId . '" value="' . $subTabName. '"/>
                ';
                 
         }

             }
                 
                 
                 
                
             
             echo $dataToFeed;
        }
    }
           
  }
 
   function getAllOffersShow()
  {
        $currencyType = $_SESSION['currencrType'];
        $user_id      = $_SESSION['user_id'];
          $cname1=  $_SESSION['company_name'];
     $subTabId     = $_POST['subTabId'];
        $getMaketSubTabName = $this->m_usermarketmarketplace->getMaketSubTabName($subTabId);
        $subTabName = $getMaketSubTabName['value'];
        
        
        
        $result['allOffersForTerms'][] = $allOffersForTerms = $this->m_usermarketmarketplace->getAllOffersShow($subTabName, $currencyType,$subTabId);
      // print_r($allOffersForTerms);
       
        $dataToFeed = "";
        //comment by vikas
         // if ($subTabId != "15")
        //  {
              if ($subTabId == "15") {
               for ($i = 0; $i < 10; $i++) {
                $hide="";
                $result1 = $marketRequests = $this->m_usermarketmarketplace->mymarketsettings($cname1, $currencyType,$allOffersForTerms[$i]['lender_id']);

             if(count($result1) > 0) {
             if($result1[0]['offer'] =="Y") {
                $hide="";
             $min_bid= $allOffersForTerms[$i]['offer_rate'] + ($result1[0]['Offer-Spread']/100);
                } 
                else {
                    $min_bid= $allOffersForTerms[$i]['offer_rate']; 
                     $hide="hideme1";
                }
              }
              else {
                 $hide="hideme1";
               $min_bid= $allOffersForTerms[$i]['offer_rate']; 
              }

                  $offerRateCheck = $allOffersForTerms[$i]['offer_rate'];
                    $company = $allOffersForTerms[$i]['company_name'];

                    if ($offerRateCheck != "") {

                        $offerShow1 = $offerRateCheck . "%";
                        $offerShow = $min_bid . "%";
                        $offerShow2 = $min_bid ;

                        if ($allOffersForTerms[$i]['lender_id'] != $user_id) {

                              if(count($result1) > 0) {
                           if($result1[0]['offer'] =="Y") {
                            $onclickDealOffer = "acceptDeal(" . $allOffersForTerms[$i]['id'] . "," . $subTabId . ",1)";
                            $myOfferClass = "";
                            $biddingClass    = "";

  
                        $dataToFeed .= '<div class="bestOffers1" style="width:100%;font-size:13px;margin-top:2px;background-color:#051522;height:35px;float:left;">
                                   <div id="marketButton5 padding " class="moz2" style="">' . $allOffersForTerms[$i]['lender_company'] . '</div> 
                                  
                            <div id="marketButton5 padding "  class="moz2" >' . $allOffersForTerms[$i]['amount'] . '</div>  <div id="marketButton5 padding moz2"  class="moz2 vd">' . $allOffersForTerms[$i]['value_date'] . '</div>  <div id="marketButton5 padding moz2 vd" class="moz2 vd">' . $allOffersForTerms[$i]['maturity_date'] . '</div>   <div id="marketButton5 padding moz2 vd" class="moz2"  >' . $offerShow . '</div> 
                                   ';
                      
                       
                        $dataToFeed .= '<div class="'.$biddingClass.' dealButton moz2"  id="marketButton2" style="cursor:pointer;" onclick="' . $onclickDealOffer . '"><b>Deal</b></div>  

                           

                           <input type="hidden" name="amountOfferedForrequest" id="amountOfferedForrequest' . $allOffersForTerms[$i]['id'] . '" value="' . $allOffersForTerms[$i]['amount'] . '"/> 
                                <input type="hidden" name="rateOfferedForrequest" id="rateOfferedForrequest' . $allOffersForTerms[$i]['id'] . '" value="' . $offerShow2 . '"/> 
                                 <input type="hidden" name="valdates" id="valdates' . $allOffersForTerms[$i]['id'] . '" value="' . $allOffersForTerms[$i]['value_date'] . '"/> 
                                  <input type="hidden" name="matdates" id="matdates' . $allOffersForTerms[$i]['id'] . '" value="' . $allOffersForTerms[$i]['maturity_date'] . '"/> 



                                <input type="hidden" name="lenderCompanyDetail" id="lenderCompanyDetail' . $allOffersForTerms[$i]['id'] . '" value="' . $allOffersForTerms[$i]['lender_company'] . '"/>'
                                . '</div> ';
                       }} } else {

                            $onclickDealOffer = "";
                            $myOfferClass = "myOfferClass";
                            $biddingClass    = "gredOutBid";


                        $dataToFeed .= '<div class="bestOffers1 mycreatedoffers" style="width:100%;font-size:13px;margin-top:2px;height:35px;float:left;">
                        <div id="marketButton5 padding " class="moz2" >' . $allOffersForTerms[$i]['lender_company'] . '</div> 
                                  
                            <div id="marketButton5 padding "  class="moz2" >' . $allOffersForTerms[$i]['amount'] . '</div>  <div id="marketButton5 padding moz2"  class="moz2 vd">' . $allOffersForTerms[$i]['value_date'] . '</div>  <div id="marketButton5 padding moz2" class="moz2 vd">' . $allOffersForTerms[$i]['maturity_date'] . '</div>   <div id="marketButton5 padding moz2" class="moz2"  >' . $offerShow1 . '</div> 
                                   ';
                      
                       
                        $dataToFeed .= '<div class="'.$biddingClass.' dealButton moz2"  id="marketButton2" style="cursor:pointer;" onclick="' . $onclickDealOffer . '"><b>Deal</b></div>
                    <input type="hidden" name="amountOfferedForrequest" id="amountOfferedForrequest' . $allOffersForTerms[$i]['id'] . '" value="' . $allOffersForTerms[$i]['amount'] . '"/> 
                                <input type="hidden" name="rateOfferedForrequest" id="rateOfferedForrequest' . $allOffersForTerms[$i]['id'] . '" value="' . $offerShow1 . '"/> 

                                 <input type="hidden" name="valdates" id="valdates' . $allOffersForTerms[$i]['id'] . '" value="' . $allOffersForTerms[$i]['value_date'] . '"/> 
                                  <input type="hidden" name="matdates" id="matdates' . $allOffersForTerms[$i]['id'] . '" value="' . $allOffersForTerms[$i]['maturity_date'] . '"/> 

                                <input type="hidden" name="lenderCompanyDetail" id="lenderCompanyDetail' . $allOffersForTerms[$i]['id'] . '" value="' . $allOffersForTerms[$i]['lender_company'] . '"/>'
                                . '</div> ';
                        }


                    }
                }}
                else {
  for ($i = 0; $i < 11; $i++) {

     $hide="";
                $result1 = $marketRequests = $this->m_usermarketmarketplace->mymarketsettings($cname1, $currencyType,$allOffersForTerms[$i]['lender_id']);

             if(count($result1) > 0) {
             if($result1[0]['offer'] =="Y") {
                $hide="";
             $min_bid= $allOffersForTerms[$i]['offer_rate'] + ($result1[0]['Offer-Spread']/100);
                } 
                else {
                    $min_bid= $allOffersForTerms[$i]['offer_rate']; 
                     $hide="hideme1";
                }
              }
              else {
                 $hide="hideme1";
               $min_bid= $allOffersForTerms[$i]['offer_rate']; 
              }


                    $offerRateCheck = $allOffersForTerms[$i]['offer_rate'];
                            $company = $allOffersForTerms[$i]['company_name'];
                    if ($offerRateCheck != "") {

                        $offerShow1 = $offerRateCheck . "%";
                        $offerShow = $min_bid . "%";
                        $offerShow2 = $min_bid;

                        if ($allOffersForTerms[$i]['lender_id'] != $user_id) {
 

                            $onclickDealOffer = "acceptDeal(" . $allOffersForTerms[$i]['id'] . "," . $subTabId . ",0)";
                            $myOfferClass = "";
                            $biddingClass    = "";


                        $dataToFeed .= '<div class="bestOffers1 '.$hide.' " style="font-size:13px;margin-top:2px;background-color:#051522;font-size: 13px; margin-top: 2px; height: 35px; float: left; width: 125%;">
                                    <div id="marketButton5 padding" style="float: left; padding: 10px 7% 10px 0px; width: 26%; text-align: center;"  >' . $offerShow . '</div> 
                            <div id="marketButton5 padding" style="float: left; width: 26%; text-align: center; padding: 10px 4% 10px 0px;"  >' . $allOffersForTerms[$i]['amount'] . '</div> 
                                   ';
                      
                       
                        $dataToFeed .= '<div class="'.$biddingClass.' dealButton"  id="marketButton2" style="float: left; width: 26%; text-align: center; padding: 10px 4% 10px 0px;cursor:pointer;"  onclick="' . $onclickDealOffer . '"><b>Deal</b></div>
                        <div class="'.$biddingClass.' moz2"  id="marketButton2" style="text-align: left; font-size: 13px; padding-top: 5.5px; overflow-wrap: break-word; width: 93px !important;" >' . $company . '</div>
                    <input type="hidden" name="amountOfferedForrequest" id="amountOfferedForrequest' . $allOffersForTerms[$i]['id'] . '" value="' . $allOffersForTerms[$i]['amount'] . '"/> 
                                <input type="hidden" name="rateOfferedForrequest" id="rateOfferedForrequest' . $allOffersForTerms[$i]['id'] . '" value="' . $offerShow2 . '"/> 
                                <input type="hidden" name="lenderCompanyDetail" id="lenderCompanyDetail' . $allOffersForTerms[$i]['id'] . '" value="' . $allOffersForTerms[$i]['lender_company'] . '"/>'
                                . '</div> ';
                        } else {

                            $onclickDealOffer = "";
                            $myOfferClass = "myOfferClass";
                            $biddingClass    = "gredOutBid";


                        $dataToFeed .= '<div class="bestOffers1 mycreatedoffers" style="font-size: 13px; margin-top: 2px; height: 35px; float: left; width: 125%;">
                                    <div id="marketButton5 padding" style="float: left; padding: 10px 7% 10px 0px; width: 26%; text-align: center;"  >' . $offerShow1 . '</div> 
                            <div id="marketButton5 padding prsentaz" style="float: left; width: 26%; text-align: center; padding: 10px 4% 10px 0px;"  >' . $allOffersForTerms[$i]['amount'] . '</div> 

                            <div class="'.$biddingClass.' dealButton"  id="marketButton2" style="float: left; width: 26%; text-align: center; padding: 10px 4% 10px 0px;cursor:pointer;"  onclick="' . $onclickDealOffer . '"><b>Deal</b></div>

                        <div class="'.$biddingClass.' moz2"  id="marketButton2"  style="text-align: left; font-size: 13px; padding-top: 5.5px; overflow-wrap: break-word; width: 93px !important;"  >' . $company . '</div>
                                   ';
                      
                       
                        $dataToFeed .= '<input type="hidden" name="amountOfferedForrequest" id="amountOfferedForrequest' . $allOffersForTerms[$i]['id'] . '" value="' . $allOffersForTerms[$i]['amount'] . '"/> 
                                <input type="hidden" name="rateOfferedForrequest" id="rateOfferedForrequest' . $allOffersForTerms[$i]['id'] . '" value="' .$offerShow1 . '"/> 
                                <input type="hidden" name="lenderCompanyDetail" id="lenderCompanyDetail' . $allOffersForTerms[$i]['id'] . '" value="' . $allOffersForTerms[$i]['lender_company'] . '"/>'
                                . '</div> ';
                        }


                    }
                }

                }
         
        echo $dataToFeed;
 // }
       
   
}

   
   
}
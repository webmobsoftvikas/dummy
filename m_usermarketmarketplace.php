<?php if (!defined('BASEPATH')) exit('No direct script access allowed'); ?>

<?php


 
class m_usermarketmarketplace extends CI_Model {



    function __construct() {

        parent::__construct();

        $this->load->database();

    }

    public function checkUserStatus($user_id)
    {
        $this->db->select("status");
        $this->db->where('id',$user_id);
        $this->db->from("tbl_users");
        $query = $this->db->get();

        $array = $query->result_array();

	    return   $array;
    }

      public function mymarketsettings($cname1,$currency,$setter)
    {
        $this->db->select("*");
        $this->db->where('company_name',$cname1);
        $this->db->where('setter_user_id',$setter);
        $this->db->where('currency',$currency);
        $this->db->from("tbl_marketsettings");
        $query = $this->db->get();

        $array = $query->result_array();
        //echo $this->db->last_query();
      return   $array;
    }

    public function insertBidOffer($dataValue)

    {

        $this->db->insert('tbl_market_response',$dataValue);

        return $this->db->insert_id();

    }

	public function myRequestsForAmountOffers($user_id,$currType)

	{

		$this->db->select("tbl_market_offers_responces.*,tbl_market_offers_responces.id as offerResponseId, tbl_market_offer.term,tbl_market_offers_responces.int_rate as int_rate,tbl_market_offers_responces.amount_demand as amount,tbl_market_offer.lender_company  as borrowerName");

        $this->db->from('tbl_market_offers_responces');
 
	    $this->db->join("tbl_market_offer","tbl_market_offer.id = tbl_market_offers_responces.offer_id");

		$this->db->where("tbl_market_offers_responces.borrower_id",$user_id);
        $this->db->where("tbl_market_offer.currency_type",$currType);
        $this->db->where("tbl_market_offer.status !=","archieve");
		$this->db->where("tbl_market_offers_responces.is_accepted","n");

	    $query = $this->db->get();

        $array = $query->result_array();

	    return   $array;

	}

    public function getRequestCount($marketMoney,$cardType)
    {

        $this->db->select("count(tbl_market_request.id) as totalRequests");
        $this->db->from('tbl_market_request');
        $this->db->join("tbl_users","tbl_users.id = tbl_market_request.borrower_id");
        $this->db->where('tbl_market_request.term',$marketMoney);
		$this->db->where('tbl_market_request.is_accepted',"n");
		$this->db->where('tbl_market_request.currency_type',$cardType);
		$this->db->where('tbl_market_request.status ',"open");
		$this->db->where('tbl_market_request.is_archieve',"n");
        $this->db->where("tbl_market_request.amount !=","0");
        $this->db->where('tbl_users.status',"y");
		$query = $this->db->get();
		$array= $query->result_array();
		return $array;
    }
     public function getRequestCount1($marketMoney,$cardType)
    {

        $this->db->select("count(tbl_market_request.id) as totalRequests");
        $this->db->from('tbl_market_request');
        $this->db->join("tbl_users","tbl_users.id = tbl_market_request.borrower_id");
        $this->db->where('tbl_market_request.term',$marketMoney);
    $this->db->where('tbl_market_request.is_accepted',"n");
    $this->db->where('tbl_market_request.currency_type',$cardType);
    $this->db->where('tbl_market_request.status ',"open");
    $this->db->where('tbl_market_request.is_archieve',"n");
        $this->db->where("tbl_market_request.amount !=","0");
        $this->db->where('tbl_users.status',"y");
    $query = $this->db->get();
    $array= $query->result();
    return $array;
    }
    function getOffersCount($marketMoney,$cardType)
	{
		$this->db->select("count(tbl_market_offer.id) as totalOffers");
		$this->db->from('tbl_market_offer');
		$this->db->join("tbl_users","tbl_users.id = tbl_market_offer.lender_id");
		$this->db->where('tbl_market_offer.term',$marketMoney);
		$this->db->where('tbl_market_offer.status',"open");
		$this->db->where('tbl_market_offer.is_archieve','n');
		$this->db->where('tbl_market_offer.currency_type',$cardType);
		$this->db->where("tbl_market_offer.amount !=","0");
		$this->db->where('tbl_users.status',"y");
		$query = $this->db->get();
		//echo $this->db->last_query();
		//die;
		$array= $query->result();
		return $array;
	}
	function getCurrencyValueOfFirstRequest($marketMoney,$currency_type)

	{

		      $this->db->select("currency,term as requestedTerm");

                $this->db->where('term',$marketMoney);

                $this->db->where('is_accepted',"n");

				$this->db->where('is_bestOffer',"y");

				$this->db->where('status',"open");
                $this->db->where('currency_type',$currency_type);
                $this->db->from('tbl_market_request');

                 $query = $this->db->get();

                  $array= $query->result_array();

	          return $array;

	}

    public function checkExistBid($requestid)

    {

        $this->db->select("*");

        $this->db->where('request_id',$requestid);

        $this->db->from('tbl_market_response');

        $query = $this->db->get();

        

        $array= $query->result_array();

	return $array;

    }

    public function updateBid($dataValue ,$requestid )

    {

        $this->db->where("request_id", $requestid);

        $this->db->update("tbl_market_response",$dataValue);

       

    }

	public function deleteResponceOffer($requestId)

	{
          $array = array("is_accepted"=>"d","lender_accepted"=>'y',"status"=>"closed");
		$this->db->where('id',$requestId);

		$this->db->update("tbl_market_offers_responces",$array);

	}
	public function getButtonId($maturityMonthss)
	{
		$this->db->select("buttons_id,id");
		$this->db->where("value", $maturityMonthss);
		$this->db->from("tbl_money_market");
		$query = $this->db->get();

        

        $array= $query->result_array();

	    return $array;
	}

    public function updateAmount($bid ,$requestid)

    {

        $this->db->select("amount_display");

        $this->db->where("id",$requestid);

        $this->db->from('tbl_market_request');

         $query = $this->db->get();

        

        $array= $query->result_array();

	return $array;

    }

    public function updateAmountForRequest($updateAmount ,$requestid)

    {

         $this->db->where("id", $requestid);

        $this->db->update("tbl_market_request",$updateAmount);

    }

   public function seeLendersOffers($reqId)

   {

       $this->db->select("tbl_market_lender_response.*,tbl_users.company_name,tbl_users.fname,tbl_users.lname");

       $this->db->from('tbl_market_lender_response');

       $this->db->join('tbl_users','tbl_users.id = tbl_market_lender_response.lender_id');

       $this->db->where('request_id',$reqId);

        $query = $this->db->get();

        

        $array= $query->result_array();

	return $array;

   }

   public function editRequest($requestId)

   {

        $this->db->select("*");

        $this->db->where("id",$requestId);

        $this->db->from('tbl_market_request');

         $query = $this->db->get();

        

        $array= $query->result_array();

	    return $array;   

   }

   public function getNotifications($user_id,$currencyType)
   {

       $this->db->select("tbl_market_notifications.id as notificationId,tbl_market_request.currency,tbl_market_request.term,tbl_market_notifications.responce_id as responceId,tbl_market_lender_response.amount as offeredAmount,tbl_market_lender_response.int_rate as offerRate,tbl_market_request.id as requestedId,tbl_market_request.min_bid,tbl_users.company_name, tbl_market_lender_response.request_status as responceStatus,tbl_market_lender_response.valid_until,tbl_market_request.value_date as vd2,tbl_market_request.maturity as md2");

       $this->db->from('tbl_market_notifications'); 

       $this->db->join("tbl_market_lender_response","tbl_market_lender_response.id = tbl_market_notifications.responce_id");

       $this->db->join("tbl_market_request","tbl_market_request.id = tbl_market_notifications.req_id");

       $this->db->join("tbl_users","tbl_users.id = tbl_market_notifications.from");

       $this->db->where("tbl_market_notifications.to",$user_id);
       $this->db->where("tbl_market_request.status","open");
	   $this->db->where("tbl_market_request.is_archieve","n");
       
	   //$this->db->where("tbl_market_request.currency_type",$currencyType);
	   $this->db->where("((tbl_market_request.currency_type = 'CHF') OR (tbl_market_request.currency_type = 'EUR') OR (tbl_market_request.currency_type = 'USD'))");
       
	   $this->db->where("tbl_market_notifications.readed","n");
	   $this->db->where("tbl_market_lender_response.request_status","n");



       $query = $this->db->get();

       $array = $query->result_array();

       return $array;

   }

   public function dealAccepptedNotofication($user_id,$currencyType)

   {

	   $this->db->select("tbl_market_notifications.id as notificationId,tbl_market_request.currency,tbl_market_request.term,tbl_market_notifications.responce_id as responceId,tbl_market_lender_response.amount as offeredAmount,tbl_market_lender_response.request_status as responceStatus,tbl_market_lender_response.int_rate as offerRate,tbl_market_request.id as requestedId,tbl_market_request.min_bid,tbl_users.company_name,tbl_market_request.value_date as vd,tbl_market_request.maturity as md");

       $this->db->from('tbl_market_notifications'); 

       $this->db->join("tbl_market_lender_response","tbl_market_lender_response.id = tbl_market_notifications.responce_id");

       $this->db->join("tbl_market_request","tbl_market_request.id = tbl_market_notifications.req_id");

       $this->db->join("tbl_users","tbl_users.id = tbl_market_notifications.from");

       $this->db->where("tbl_market_notifications.to",$user_id);
	   
      // $this->db->where("tbl_market_request.currency_type",$currencyType);
	  $this->db->where("((tbl_market_request.currency_type = 'CHF') OR (tbl_market_request.currency_type = 'EUR') OR (tbl_market_request.currency_type = 'USD'))");
	  
        $this->db->where("tbl_market_request.status","open");
		$this->db->where("tbl_market_request.is_archieve","n");
       $this->db->where("tbl_market_notifications.readed","n");

	   $this->db->where("tbl_market_lender_response.request_status","a");

       $query = $this->db->get();

       $array= $query->result_array();

       return $array;

   }

   public function deniedNotificationToBorrowerDeal($user_id,$currType)

   {

	$this->db->select("tbl_market_notifications.id as notificationId,tbl_market_request.currency,tbl_market_request.term,tbl_market_notifications.responce_id as responceId,tbl_market_lender_response.amount as offeredAmount,tbl_market_lender_response.int_rate as offerRate,tbl_market_request.id as requestedId,tbl_market_request.min_bid,tbl_users.company_name, tbl_market_lender_response.request_status as responceStatus");

       $this->db->from('tbl_market_notifications'); 

       $this->db->join("tbl_market_lender_response","tbl_market_lender_response.id = tbl_market_notifications.responce_id");

       $this->db->join("tbl_market_request","tbl_market_request.id = tbl_market_notifications.req_id");

       $this->db->join("tbl_users","tbl_users.id = tbl_market_notifications.from");

       $this->db->where("tbl_market_notifications.to",$user_id);
	   
       //$this->db->where("tbl_market_request.currency_type",$currType);
	   $this->db->where("((tbl_market_request.currency_type = 'CHF') OR (tbl_market_request.currency_type = 'EUR') OR (tbl_market_request.currency_type = 'USD'))");
	   
       $this->db->where("tbl_market_request.status","open");
	   $this->db->where("tbl_market_request.is_archieve","n");
       $this->db->where("tbl_market_notifications.readed","n");

       $this->db->where("tbl_market_lender_response.request_status","d");



       $query = $this->db->get();

       $array = $query->result_array();

       return $array;

   }

   function amountDeniedOk($responseId)

   {

	  $update = array("is_seen"=>"y");

      $this->db->where("id",$responseId);

      $this->db->update("tbl_market_offers_responces",$update);

   }

   public function deniedNotificationToBorrowerOffer($user_id,$currType)

   {

	      $this->db->select("tbl_market_offers_responces.id as offerResponseId, tbl_market_offers_responces.amount_demand,tbl_market_offer.currency,tbl_market_offers_responces.int_rate as offer_rate,tbl_users.company_name,tbl_market_offers_responces.lender_accepted,tbl_market_offer.term,tbl_market_offers_responces.is_accepted");

		  $this->db->from('tbl_market_offers_responces');

		  $this->db->join('tbl_users','tbl_users.id = tbl_market_offers_responces.lender_id');

		  $this->db->join('tbl_market_offer','tbl_market_offer.id = tbl_market_offers_responces.offer_id');

		  $this->db->where('tbl_market_offers_responces.borrower_id',$user_id);
		  
          //$this->db->where('tbl_market_offer.currency_type',$currType);
          $this->db->where("((tbl_market_offer.currency_type = 'CHF') OR (tbl_market_offer.currency_type = 'EUR') OR (tbl_market_offer.currency_type = 'USD'))");
		  
		  $this->db->where('tbl_market_offer.status',"open");
		  $this->db->where('tbl_market_offer.is_archieve',"n");
		  $this->db->where('tbl_market_offers_responces.is_accepted','d');

	           $this->db->where('tbl_market_offers_responces.lender_accepted','n');

		  $this->db->where('tbl_market_offers_responces.is_seen','n');

		   	

		  $result = $this->db->get();

         return $result->result_array();

	   

   }

   public function deniedNotificationToLenderOffer($user_id,$currType)

   {

	         $this->db->select("tbl_market_offers_responces.id as offerResponseId, tbl_market_offers_responces.amount_demand,tbl_market_offers_responces.int_rate as offer_rate,tbl_users.company_name,tbl_market_offers_responces.lender_accepted,tbl_market_offer.term,tbl_market_offers_responces.is_accepted,tbl_market_offer.maturity_date as matdate ");

		  $this->db->from('tbl_market_offers_responces');

		  $this->db->join('tbl_users','tbl_users.id = tbl_market_offers_responces.borrower_id');

		  $this->db->join('tbl_market_offer','tbl_market_offer.id = tbl_market_offers_responces.offer_id');

		  $this->db->where('tbl_market_offers_responces.lender_id',$user_id);

		  $this->db->where('tbl_market_offers_responces.is_accepted','d');
                  //$this->db->where('tbl_market_offer.currency_type',$currType);
				  $this->db->where("((tbl_market_offer.currency_type = 'CHF') OR (tbl_market_offer.currency_type = 'EUR') OR (tbl_market_offer.currency_type = 'USD'))");
				  
                  $this->db->where('tbl_market_offer.status',"open");
				  $this->db->where('tbl_market_offer.is_archieve',"n");
	          $this->db->where('tbl_market_offers_responces.lender_accepted','y');

		  $this->db->where('tbl_market_offers_responces.is_seen','n');

		   	

		  $result = $this->db->get();

          return $result->result_array(); 

   }

   public function notificationOfferAccepedByBorrower($user_id,$currencyType)

   {

	      $this->db->select("tbl_market_offers_responces.id as offerResponseId, tbl_market_offers_responces.amount_demand,tbl_market_offers_responces.int_rate as offer_rate,tbl_users.company_name,tbl_market_offer.currency,tbl_market_offers_responces.lender_accepted,tbl_market_offer.term,tbl_market_offers_responces.is_accepted,tbl_market_offer.maturity_date as matdate1,tbl_market_offer.value_date as vd4, tbl_market_offer.maturity_date as md4");

		  $this->db->from('tbl_market_offers_responces');

		  $this->db->join('tbl_users','tbl_users.id = tbl_market_offers_responces.lender_id');

		  $this->db->join('tbl_market_offer','tbl_market_offer.id = tbl_market_offers_responces.offer_id');

		  $this->db->where('tbl_market_offers_responces.borrower_id',$user_id);
		  
		  //$this->db->where('tbl_market_offer.currency_type',$currencyType);
		  $this->db->where("((tbl_market_offer.currency_type = 'CHF') OR (tbl_market_offer.currency_type = 'EUR') OR (tbl_market_offer.currency_type = 'USD'))");
		  
            $this->db->where('tbl_market_offer.status',"open");
			$this->db->where('tbl_market_offer.is_archieve',"n");
		  $this->db->where('tbl_market_offers_responces.is_accepted','y');

	      $this->db->where('tbl_market_offers_responces.lender_accepted','n');

		  $this->db->where('tbl_market_offers_responces.is_seen','n');

		   	

		  $result = $this->db->get();

          return $result->result_array(); 

   }

   public function responseDetailOffer($responseId)

   {

	   $this->db->select("tbl_market_offers_responces.*,tbl_market_offers_responces.int_rate as offer_rate");

	    $this->db->from('tbl_market_offers_responces');

		$this->db->join("tbl_market_offer","tbl_market_offer.id = tbl_market_offers_responces.offer_id");

	   $this->db->where("tbl_market_offers_responces.id",$responseId);

	  

	   $result = $this->db->get();

       return $result->result_array();

	   

   }

   function updatedOfferResponseupdatedOfferResponse($updatedArray,$editedOfferResponceId)

   {

	   $this->db->where("id",$editedOfferResponceId);

	   $this->db->update("tbl_market_offers_responces",$updatedArray);

   }

   public function editedOfferNotificationForLender($user_id,$currType)
   {

	    $this->db->select("tbl_market_offers_responces.id as offerResponseId, tbl_market_offers_responces.amount_demand,tbl_market_offers_responces.int_rate as offer_rate,tbl_market_offer.currency,tbl_users.company_name,tbl_market_offers_responces.lender_accepted,tbl_market_offer.term,tbl_market_offers_responces.valid_until,tbl_market_offer.value_date as vd1, tbl_market_offer.maturity_date as md1");

		$this->db->from('tbl_market_offers_responces');

		$this->db->join('tbl_users','tbl_users.id = tbl_market_offers_responces.borrower_id');

		$this->db->join('tbl_market_offer','tbl_market_offer.id = tbl_market_offers_responces.offer_id');

		$this->db->where('tbl_market_offers_responces.lender_id',$user_id);

		$this->db->where('tbl_market_offers_responces.lender_accepted','n');
        //$this->db->where('tbl_market_offer.currency_type',$currType);
		$this->db->where("((tbl_market_offer.currency_type = 'CHF') OR (tbl_market_offer.currency_type = 'EUR') OR (tbl_market_offer.currency_type = 'USD'))");
		
        $this->db->where('tbl_market_offer.status',"open");
		 $this->db->where('tbl_market_offer.is_archieve',"n");
		$this->db->where('tbl_market_offer.is_archieve','n');
	    $this->db->where('tbl_market_offers_responces.status','open');

		$result = $this->db->get();

        return $result->result_array();

   }

    public function editedOfferNotificationForBorrower($user_id,$currType)

   {

	      $this->db->select("tbl_market_offers_responces.id as offerResponseId, tbl_market_offers_responces.amount_demand,tbl_market_offers_responces.int_rate as offer_rate,tbl_users.company_name,tbl_market_offers_responces.lender_accepted,tbl_market_offer.term,tbl_market_offer.currency");

		  $this->db->from('tbl_market_offers_responces');

		  $this->db->join('tbl_users','tbl_users.id = tbl_market_offers_responces.lender_id');

		  $this->db->join('tbl_market_offer','tbl_market_offer.id = tbl_market_offers_responces.offer_id');

		  $this->db->where('tbl_market_offers_responces.borrower_id',$user_id);
		  
          //$this->db->where('tbl_market_offer.currency_type',$currType);
		  $this->db->where("((tbl_market_offer.currency_type = 'CHF') OR (tbl_market_offer.currency_type = 'EUR') OR (tbl_market_offer.currency_type = 'USD'))");
		  
          $this->db->where('tbl_market_offer.status',"open");
		  $this->db->where('tbl_market_offer.is_archieve','n');
		  $this->db->where('tbl_market_offers_responces.lender_accepted','y');

	      $this->db->where('tbl_market_offers_responces.status','open');

		  $result = $this->db->get();

         return $result->result_array();

   }

   

   public function deniedRequestOffr($responceId)

   {
      $dateTime = date("Y-m-d H:i:s");
      $update = array("status"=>"closed","request_status"=>"d","deniedDate" => $dateTime);

      $this->db->where("id",$responceId);

      $this->db->update("tbl_market_lender_response",$update);

   }

   public function deniedRequestOffrResponce($responceId)

   {

	   $update = array("status"=>"closed","is_accepted"=>"d");

	   $this->db->where("id",$responceId);

       $this->db->update("tbl_market_offers_responces",$update);

   }

   //////offer denied////////////
   
   
    public function allLenderDeniedOfferRequestForDates($user_id,$currencyType,$startDate,$endDate)
   {

	      $this->db->select("tbl_market_offers_responces.id as offerResponseId,tbl_market_offers_responces.value_date,tbl_market_offers_responces.maturity_date, tbl_market_offers_responces.amount_demand as amount, tbl_market_offers_responces.int_rate as int_rate , tbl_market_offer.term, tbl_market_offer.lender_id as lenderId,tbl_users.company_name as lenderCompany,tbl_market_offers_responces.deniedDate");
          $this->db->from('tbl_market_offers_responces');
		  $this->db->join('tbl_market_offer','tbl_market_offer.id = tbl_market_offers_responces.offer_id');
          $this->db->join('tbl_users','tbl_users.id = tbl_market_offers_responces.borrower_id');
		  $this->db->where('tbl_market_offers_responces.lender_id',$user_id);
		  $this->db->where('tbl_market_offer.currency_type',$currencyType);
		  $this->db->where('tbl_market_offers_responces.deniedDate >=',$startDate);
          $this->db->where('tbl_market_offers_responces.deniedDate <=',$endDate);
		  $this->db->where('tbl_users.status','y');
		  $this->db->where('tbl_market_offers_responces.is_accepted','d');

	      $result = $this->db->get();

          return $result->result_array();

   }
  public function allLenderDeniedOfferRequest($user_id,$currencyType)
  {

	    $this->db->select("tbl_market_offers_responces.id as offerResponseId,tbl_market_offers_responces.value_date,tbl_market_offers_responces.maturity_date, tbl_market_offers_responces.amount_demand as amount, tbl_market_offers_responces.int_rate as int_rate , tbl_market_offer.term, tbl_market_offer.lender_id as lenderId,tbl_users.company_name as lenderCompany,tbl_market_offers_responces.deniedDate");
          $this->db->from('tbl_market_offers_responces');

		  $this->db->join('tbl_market_offer','tbl_market_offer.id = tbl_market_offers_responces.offer_id');
          $this->db->join('tbl_users','tbl_users.id = tbl_market_offers_responces.borrower_id');

		  $this->db->where('tbl_market_offers_responces.lender_id',$user_id);
		  $this->db->where('tbl_market_offer.currency_type',$currencyType);
		  //$this->db->where('tbl_market_offer.is_archieve !=','y');
		  $this->db->where('tbl_users.status','y');
		  $this->db->where('tbl_market_offers_responces.is_accepted','d');

	      $result = $this->db->get();

          return $result->result_array();

   }
   public function lenderDeniedOfferRequest($user_id,$currencyType)
  {

	      $this->db->select("tbl_market_offers_responces.id as offerResponseId, tbl_market_offers_responces.amount_demand as amount, tbl_market_offers_responces.int_rate as int_rate , tbl_market_offer.term, tbl_market_offer.lender_id as lenderId,tbl_users.company_name as lenderCompany,tbl_market_offers_responces.deniedDate");
          $this->db->from('tbl_market_offers_responces');

		  $this->db->join('tbl_market_offer','tbl_market_offer.id = tbl_market_offers_responces.offer_id');
          $this->db->join('tbl_users','tbl_users.id = tbl_market_offers_responces.borrower_id');

		  $this->db->where('tbl_market_offers_responces.lender_id',$user_id);
		  $this->db->where('tbl_market_offer.currency_type',$currencyType);
		  $this->db->where('tbl_market_offer.is_archieve !=','y');
		  $this->db->where('tbl_users.status','y');
		  $this->db->where('tbl_market_offers_responces.is_accepted','d');

	      $result = $this->db->get();

          return $result->result_array();

   }
   
   public function allBorrowerDeniedOfferRequestForDates($user_id,$currencyType,$startDate,$endDate)
	{
		$this->db->select("tbl_market_offers_responces.amount_demand as amount,tbl_market_offers_responces.value_date,tbl_market_offers_responces.maturity_date, tbl_market_offers_responces.int_rate as int_rate , tbl_market_offer.term, tbl_market_offers_responces.borrower_id as borrowerId,tbl_users.company_name as borrowerCompany,tbl_market_offers_responces.deniedDate,tbl_users.CHFInstructions,tbl_users.EURInstructions,tbl_users.USDInstructions");
		$this->db->from('tbl_market_offers_responces');
		$this->db->join('tbl_market_offer','tbl_market_offer.id = tbl_market_offers_responces.offer_id');
		$this->db->join('tbl_users','tbl_users.id = tbl_market_offers_responces.lender_id');
        $this->db->where('tbl_market_offer.currency_type',$currencyType);
		$this->db->where('tbl_market_offer.is_archieve !=','y');
		$this->db->where('tbl_market_offers_responces.borrower_id',$user_id);
		$this->db->where('tbl_market_offers_responces.deniedDate >=',$startDate);
		$this->db->where('tbl_market_offers_responces.deniedDate <=',$endDate);
		$this->db->where('tbl_users.status','y');
		$this->db->where('tbl_market_offers_responces.is_accepted','d');
		$result = $this->db->get();
		return $result->result_array();
	}
    public function allBorrowerDeniedOfferRequest($user_id,$currencyType)
    {
		$this->db->select("tbl_market_offers_responces.amount_demand as amount,tbl_market_offers_responces.value_date,tbl_market_offers_responces.maturity_date, tbl_market_offers_responces.int_rate as int_rate , tbl_market_offer.term, tbl_market_offers_responces.borrower_id as borrowerId,tbl_users.company_name as borrowerCompany,tbl_market_offers_responces.deniedDate");
		$this->db->from('tbl_market_offers_responces');
		$this->db->join('tbl_market_offer','tbl_market_offer.id = tbl_market_offers_responces.offer_id');
		$this->db->join('tbl_users','tbl_users.id = tbl_market_offers_responces.lender_id');
           $this->db->where('tbl_market_offer.currency_type',$currencyType);
		$this->db->where('tbl_market_offer.is_archieve !=','y');
		$this->db->where('tbl_market_offers_responces.borrower_id',$user_id);
		$this->db->where('tbl_users.status','y');
		$this->db->where('tbl_market_offers_responces.is_accepted','d');
		$result = $this->db->get();
		return $result->result_array();
	}
   public function borrowerDeniedOfferRequest($user_id,$currencyType)
	{
		$this->db->select("tbl_market_offers_responces.amount_demand as amount, tbl_market_offers_responces.int_rate as int_rate , tbl_market_offer.term, tbl_market_offers_responces.borrower_id as borrowerId,tbl_users.company_name as borrowerCompany,tbl_market_offers_responces.deniedDate,tbl_users.CHFInstructions,tbl_users.EURInstructions,tbl_users.USDInstructions");
		$this->db->from('tbl_market_offers_responces');
		$this->db->join('tbl_market_offer','tbl_market_offer.id = tbl_market_offers_responces.offer_id');
		$this->db->join('tbl_users','tbl_users.id = tbl_market_offers_responces.lender_id');
        $this->db->where('tbl_market_offer.currency_type',$currencyType);
		$this->db->where('tbl_market_offer.is_archieve !=','y');
		$this->db->where('tbl_market_offers_responces.borrower_id',$user_id);
		$this->db->where('tbl_users.status','y');
		$this->db->where('tbl_market_offers_responces.is_accepted','d');
		$result = $this->db->get();
		return $result->result_array();
	}

   

   ///////////////////////////

   //deals denied/////
   
  
  public function cancelMyRequests($user_id,$currType)
  {
	 
	  $this->db->select("tbl_market_request.*,tbl_market_request.borrower_id as borrowerId ,tbl_market_request.amount_display as amount,tbl_market_request.min_bid as int_rate, tbl_users.company_name as borrowerCompany,tbl_money_market.id as mid,tbl_money_market.buttons_id ");
	  $this->db->from("tbl_market_request,tbl_money_market");
	  $this->db->join("tbl_users","tbl_users.id = tbl_market_request.borrower_id");
	  $this->db->where("tbl_users.status","y");
	  $this->db->where("tbl_market_request.currency_type", $currType);
	  $this->db->where("tbl_market_request.status","withdraw");
     $this->db->where('tbl_money_market.value = tbl_market_request.term');
	  $this->db->where("tbl_market_request.is_archieve",'n');
	  $this->db->where("tbl_market_request.borrower_id", $user_id);
	  $resiult = $this->db->get();
	  return $resiult->result_array();
  }
   function amountOfRequestDetails1($requestId)
  {
    $this->db->select("tbl_market_request.currency,tbl_money_market.id as mid,tbl_money_market.buttons_id");
     $this->db->from("tbl_market_request,tbl_money_market");
    $this->db->where("tbl_market_request.id",$requestId);
     $this->db->where('tbl_money_market.value = tbl_market_request.term');
   
    $result = $this->db->get();
   // echo $this->db->last_query();
     return $result->row_array();
  }
   public function allCanceledMyOffersForDates($user_id,$currType,$startDate,$endDate)
  {
	  $this->db->select("tbl_market_offer.*,tbl_market_offer.lender_company as lenderCompany, tbl_market_offer.offer_rate as int_rate");
	  $this->db->from("tbl_market_offer");
      $this->db->join("tbl_users","tbl_users.id = tbl_market_offer.lender_id");
	  $this->db->where("tbl_market_offer.status","withdraw");
	 
	  $this->db->where("tbl_market_offer.withdrawTime >=",$startDate);
	  $this->db->where("tbl_market_offer.withdrawTime <=",$endDate);
	 
	 
	  $this->db->where("tbl_market_offer.currency_type",$currType);
      $this->db->where("tbl_users.status","y");
	  $this->db->where("tbl_market_offer.lender_id", $user_id);
	  $resiult = $this->db->get();
	 
	  return $resiult->result_array();
  }
  
   public function allCanceledMyOffers($user_id,$currType)
  {
	  $this->db->select("tbl_market_offer.*,tbl_market_offer.lender_company as lenderCompany, tbl_market_offer.offer_rate as int_rate");
	  $this->db->from("tbl_market_offer");
      $this->db->join("tbl_users","tbl_users.id = tbl_market_offer.lender_id");
	  $this->db->where("tbl_market_offer.status","withdraw");
	  //$this->db->where("tbl_market_offer.is_archieve",'n');
	  $this->db->where("tbl_market_offer.currency_type",$currType);
      $this->db->where("tbl_users.status","y");
	  $this->db->where("tbl_market_offer.lender_id", $user_id);
	  $resiult = $this->db->get();
	  return $resiult->result_array();
  }
  public function canceledMyOffers($user_id,$currType)
  {
	  $this->db->select("tbl_market_offer.*,tbl_market_offer.lender_company as lenderCompany, tbl_market_offer.offer_rate as int_rate,tbl_money_market.id as mid,tbl_money_market.buttons_id ");
	  $this->db->from("tbl_market_offer,tbl_money_market");
      $this->db->join("tbl_users","tbl_users.id = tbl_market_offer.lender_id");
	  $this->db->where("tbl_market_offer.status","withdraw");
	  $this->db->where("tbl_market_offer.is_archieve",'n');
	  $this->db->where("tbl_market_offer.currency_type",$currType);
    $this->db->where('tbl_money_market.value = tbl_market_offer.term');
      $this->db->where("tbl_users.status","y");
	  $this->db->where("tbl_market_offer.lender_id", $user_id);
	  $resiult = $this->db->get();
	  return $resiult->result_array();
  }

public function allCancelMyRequestsForDates($user_id,$currType,$startDate,$endDate)
   {
    $this->db->select("tbl_market_request.*,tbl_market_request.borrower_id as borrowerId ,tbl_market_request.amount_display as amount,tbl_market_request.min_bid as int_rate, tbl_users.company_name as borrowerCompany");
    $this->db->from("tbl_market_request");
    $this->db->join("tbl_users","tbl_users.id = tbl_market_request.borrower_id");
    $this->db->where("tbl_users.status","y");
    $this->db->where("tbl_market_request.currency_type", $currType);
    $this->db->where("tbl_market_request.status","withdraw");
    
    $this->db->where("tbl_market_request.withdrawTime >=",$startDate);
    $this->db->where("tbl_market_request.withdrawTime <=",$endDate);
    $this->db->where("tbl_market_request.borrower_id", $user_id);
    $resiult = $this->db->get();
    return $resiult->result_array();
    }

    public function canceledMyOffers1($user_id,$currType)
  {
    $this->db->select("tbl_market_offer.*,tbl_market_offer.lender_company as lenderCompany, tbl_market_offer.offer_rate as int_rate");
    $this->db->from("tbl_market_offer");
      $this->db->join("tbl_users","tbl_users.id = tbl_market_offer.lender_id");
    $this->db->where("tbl_market_offer.status","withdraw");
    $this->db->where("tbl_market_offer.is_archieve",'n');
    $this->db->where("tbl_market_offer.currency_type",$currType);
      $this->db->where("tbl_users.status","y");
    $this->db->where("tbl_market_offer.lender_id", $user_id);
    $resiult = $this->db->get();
    return $resiult->result_array();
  }
   
   public function allBorrowerDeniedToAcceptforDates($user_id,$currType,$startDate,$endDate)
	{
		$this->db->select("tbl_market_lender_response.amount, tbl_market_lender_response.value_date,tbl_market_lender_response.maturity_date,tbl_market_lender_response.int_rate, tbl_market_request.term, tbl_market_request.borrower_id as borrowerId, tbl_users.company_name as lenderCompany, tbl_market_lender_response.deniedDate");
		$this->db->from('tbl_market_lender_response');
		$this->db->join('tbl_market_request','tbl_market_request.id = tbl_market_lender_response.request_id');
		$this->db->join('tbl_users','tbl_users.id = tbl_market_lender_response.lender_id');
		$this->db->where('tbl_market_request.borrower_id',$user_id);
		$this->db->where('tbl_market_request.currency_type',$currType);
		$this->db->where('tbl_market_lender_response.deniedDate >=',$startDate);
		$this->db->where('tbl_market_lender_response.deniedDate <=',$endDate);
		$this->db->where('tbl_users.status','y');
		$this->db->where('tbl_market_lender_response.request_status','d');
        $this->db->order_by("tbl_market_lender_response.id","DESC");
	    $result = $this->db->get();
		return $result->result_array();
	}
   public function allBorrowerDeniedToAccept($user_id,$currType)
	{
		$this->db->select("tbl_market_lender_response.amount, tbl_market_lender_response.value_date,tbl_market_lender_response.maturity_date,tbl_market_lender_response.int_rate, tbl_market_request.term, tbl_market_request.borrower_id as borrowerId, tbl_users.company_name as lenderCompany, tbl_market_lender_response.deniedDate");
		$this->db->from('tbl_market_lender_response');
		$this->db->join('tbl_market_request','tbl_market_request.id = tbl_market_lender_response.request_id');
		$this->db->join('tbl_users','tbl_users.id = tbl_market_lender_response.lender_id');
		$this->db->where('tbl_market_request.borrower_id',$user_id);
		$this->db->where('tbl_market_request.currency_type',$currType);
		//$this->db->where('tbl_market_request.is_archieve != ','y');
		$this->db->where('tbl_users.status','y');
		$this->db->where('tbl_market_lender_response.request_status','d');
        $this->db->order_by("tbl_market_lender_response.id","DESC");
	    $result = $this->db->get();
		return $result->result_array();
	}
    public function borrowerDeniedToAccept($user_id,$currType)
	{
		$this->db->select("tbl_market_lender_response.amount,tbl_market_lender_response.int_rate, tbl_market_request.term, tbl_market_request.borrower_id as borrowerId, tbl_users.company_name as lenderCompany, tbl_market_lender_response.deniedDate");
		$this->db->from('tbl_market_lender_response');
		$this->db->join('tbl_market_request','tbl_market_request.id = tbl_market_lender_response.request_id');
		$this->db->join('tbl_users','tbl_users.id = tbl_market_lender_response.lender_id');
		$this->db->where('tbl_market_request.borrower_id',$user_id);
		$this->db->where('tbl_market_request.currency_type',$currType);
		$this->db->where('tbl_market_request.is_archieve != ','y');
		$this->db->where('tbl_users.status','y');
		$this->db->where('tbl_market_lender_response.request_status','d');
        $this->db->order_by("tbl_market_lender_response.id","DESC");
	    $result = $this->db->get();
		return $result->result_array();
	}
	public function allLenderDealsDeniedForDates($user_id,$currencyType,$startDate,$endDate)
	{

	    $this->db->select("tbl_market_lender_response.amount,tbl_market_lender_response.value_date, tbl_market_lender_response.maturity_date, tbl_market_lender_response.lender_id as lenderId , tbl_market_lender_response.int_rate, tbl_market_request.term, tbl_users.company_name as borrowerCompany, tbl_market_lender_response.deniedDate");
		$this->db->from('tbl_market_lender_response');
		$this->db->join('tbl_market_request','tbl_market_request.id = tbl_market_lender_response.request_id');
		$this->db->join('tbl_users','tbl_users.id = tbl_market_request.borrower_id');
		$this->db->where('tbl_market_lender_response.lender_id',$user_id);
		$this->db->where('tbl_market_request.currency_type',$currencyType);
        
		$this->db->where('tbl_market_lender_response.deniedDate >=',$startDate);
		$this->db->where('tbl_market_lender_response.deniedDate <=',$endDate);
		
        $this->db->where('tbl_users.status','y');
		$this->db->where('tbl_market_lender_response.request_status','d');
		$result = $this->db->get();
		return $result->result_array();
	}
    public function allLenderDealsDenied($user_id,$currencyType)
	{

	    $this->db->select("tbl_market_lender_response.amount,tbl_market_lender_response.value_date, tbl_market_lender_response.maturity_date, tbl_market_lender_response.lender_id as lenderId , tbl_market_lender_response.int_rate, tbl_market_request.term, tbl_users.company_name as borrowerCompany, tbl_market_lender_response.deniedDate");
		$this->db->from('tbl_market_lender_response');
		$this->db->join('tbl_market_request','tbl_market_request.id = tbl_market_lender_response.request_id');
		$this->db->join('tbl_users','tbl_users.id = tbl_market_request.borrower_id');
		$this->db->where('tbl_market_lender_response.lender_id',$user_id);
		$this->db->where('tbl_market_request.currency_type',$currencyType);
        //$this->db->where('tbl_market_request.is_archieve != ','y');
        $this->db->where('tbl_users.status','y');
		$this->db->where('tbl_market_lender_response.request_status','d');
		$result = $this->db->get();
		return $result->result_array();
	}
   public function lenderDealsDenied($user_id,$currencyType)
	{

	    $this->db->select("tbl_market_lender_response.amount, tbl_market_lender_response.lender_id as lenderId , tbl_market_lender_response.int_rate, tbl_market_request.term, tbl_users.company_name as borrowerCompany, tbl_market_lender_response.deniedDate");
		$this->db->from('tbl_market_lender_response');
		$this->db->join('tbl_market_request','tbl_market_request.id = tbl_market_lender_response.request_id');
		$this->db->join('tbl_users','tbl_users.id = tbl_market_request.borrower_id');
		$this->db->where('tbl_market_lender_response.lender_id',$user_id);
		$this->db->where('tbl_market_request.currency_type',$currencyType);
        $this->db->where('tbl_market_request.is_archieve != ','y');
        $this->db->where('tbl_users.status','y');
		$this->db->where('tbl_market_lender_response.request_status','d');
		$result = $this->db->get();
		return $result->result_array();
	}

   ///////////////////////////////////////

    public function notificationReaded($responceId)
	{

       $update = array("readed"=>"y");

       $this->db->where("responce_id",$responceId);

       $this->db->update("tbl_market_notifications",$update);

    }

   public function notificationDetails($responceId)

   {

	   $this->db->select("*");

	   $this->db->where("responce_id",$responceId);

	   $this->db->from("tbl_market_notifications");

	   $result = $this->db->get();

       return $result->result_array();

   }

  

    function updateAmountOfferResponce($offerResponseId,$amount)
    {
        $updated = array("amount_demand"=>$amount);
        $this->db->where("id",$offerResponseId);
        $this->db->update("tbl_market_offers_responces",$updated);
        
    }
	public function allMyAcceptedOrderForDates($user_id,$curType,$startDate,$endDate)
	{
		$this->db->select('tbl_market_loan_accepted.*,tbl_users.company_name,');
		$this->db->from('tbl_market_loan_accepted');
		$this->db->join('tbl_users','tbl_users.id = tbl_market_loan_accepted.borrower_id');
		$this->db->where('tbl_market_loan_accepted.lender_id',$user_id);
		$this->db->where('tbl_market_loan_accepted.time_accept >=',$startDate);
		$this->db->where('tbl_market_loan_accepted.time_accept <=',$endDate);
		$this->db->where('tbl_users.status',"y");
		$this->db->where('tbl_market_loan_accepted.currency_type',$curType);
		$this->db->order_by("tbl_market_loan_accepted.time_accept","desc");
		$result = $this->db->get();
		
		return $result->result_array();
	}
	public function allMyAcceptedOrder($user_id,$curType)
	{
		$this->db->select('tbl_market_loan_accepted.*,tbl_users.company_name,');
		$this->db->from('tbl_market_loan_accepted');
		$this->db->join('tbl_users','tbl_users.id = tbl_market_loan_accepted.borrower_id');
		$this->db->where('tbl_market_loan_accepted.lender_id',$user_id);
		//$this->db->where('tbl_market_loan_accepted.is_archieve','n');
		$this->db->where('tbl_users.status',"y");
		$this->db->where('tbl_market_loan_accepted.currency_type',$curType);
		$this->db->order_by("tbl_market_loan_accepted.time_accept","desc");
		$result = $this->db->get();
		return $result->result_array();
	}
    public function myAcceptedOrder($user_id,$curType)

    {

         $this->db->select('tbl_market_loan_accepted.*,tbl_users.company_name,');

          $this->db->from('tbl_market_loan_accepted');

          $this->db->join('tbl_users','tbl_users.id = tbl_market_loan_accepted.borrower_id');

       

          $this->db->where('tbl_market_loan_accepted.lender_id',$user_id);
          $this->db->where('tbl_market_loan_accepted.is_archieve','n');
          $this->db->where('tbl_users.status',"y");
          $this->db->where('tbl_market_loan_accepted.currency_type',$curType);
           $this->db->order_by("tbl_market_loan_accepted.time_accept","desc");

          $result = $this->db->get();

          return $result->result_array();

    }
   
    public function acceptedRequests($user_id,$currencyType)
   {

          $this->db->select('tbl_market_loan_accepted.*,tbl_users.company_name');

          $this->db->from('tbl_market_loan_accepted');

          $this->db->join('tbl_users','tbl_users.id = tbl_market_loan_accepted.lender_id');

          $this->db->where('tbl_market_loan_accepted.borrower_id',$user_id);
          $this->db->where('tbl_market_loan_accepted.is_archieve', 'n');
          $this->db->where('tbl_users.status','y');
          $this->db->where('tbl_market_loan_accepted.currency_type',$currencyType);
          $this->db->order_by("tbl_market_loan_accepted.time_accept","desc");
          $result = $this->db->get();

          return $result->result_array();

    }
	function AllAcceptedRequestsForDates($user_id,$currencyType,$startDate,$endDate)
	{
		$this->db->select('tbl_market_loan_accepted.*,tbl_users.company_name');
		$this->db->from('tbl_market_loan_accepted');
		$this->db->join('tbl_users','tbl_users.id = tbl_market_loan_accepted.lender_id');
		$this->db->where('tbl_market_loan_accepted.borrower_id',$user_id);
		$this->db->where('tbl_market_loan_accepted.time_accept >=', $startDate);
        $this->db->where('tbl_market_loan_accepted.time_accept <=', $endDate);
        $this->db->where('tbl_market_loan_accepted.currency_type',$currencyType);
        $this->db->order_by("tbl_market_loan_accepted.time_accept","desc");
		$result = $this->db->get();
		return $result->result_array();
	}
	function AllAcceptedRequests($user_id,$currencyType)
	{
		$this->db->select('tbl_market_loan_accepted.*,tbl_users.company_name');
		$this->db->from('tbl_market_loan_accepted');
		$this->db->join('tbl_users','tbl_users.id = tbl_market_loan_accepted.lender_id');
		$this->db->where('tbl_market_loan_accepted.borrower_id',$user_id);
          //$this->db->where('tbl_market_loan_accepted.is_archieve', 'n');
        $this->db->where('tbl_users.status','y');
        $this->db->where('tbl_market_loan_accepted.currency_type',$currencyType);
        $this->db->order_by("tbl_market_loan_accepted.time_accept","desc");
        $result = $this->db->get();
		return $result->result_array();
	}
  

    public function allNotificationReaded($responceId)
   {

       $update = array("readed"=>"y");
       $this->db->where("responce_id",$responceId);
       $this->db->update("tbl_market_notifications",$update);
    }

   public function acceptedOffr($requestId,$lenderId)

   {

        $update = array("status"=>"closed","request_status"=>"a");

        $this->db->where("lender_id",$lenderId);

       

        $this->db->update("tbl_market_lender_response",$update);

   }

   public function acceptDeal($requestId)

   {

        $update = array("is_accepted"=>"y","status"=>"closed");

        $this->db->where("id",$requestId);

        $this->db->update("tbl_market_request",$update);

    }

    

     function marketRequestInsert($dataVAlues)

    {
		
        $this->db->insert('tbl_market_request',$dataVAlues);

        return $this->db->insert_id();

    }

    function marketRequests($user_id,$currencyType,$buttonsId)
    {

          $this->db->select('tbl_market_request.*,tbl_users.company_name, tbl_users.city , tbl_market_lender_response.lender_id as `lenderId`,tbl_market_request.is_bestOffer as `showBestOffr`');
          $this->db->from('tbl_market_request');
          $this->db->join('tbl_users','tbl_users.id = tbl_market_request.borrower_id');
          $this->db->join('tbl_market_lender_response','tbl_market_lender_response.request_id = tbl_market_request.id','left');
          $this->db->where('tbl_market_request.is_accepted','n');
          $this->db->where('tbl_market_request.status','open');
          $this->db->where('tbl_users.status','y');
	  $this->db->where('tbl_market_request.currency_type',$currencyType);
	  $this->db->where('tbl_market_request.amount !=','0');
	  $this->db->where('tbl_market_request.is_archieve','n');
          
          if($buttonsId != "2")
          {
              $this->db->order_by('tbl_market_request.min_bid',"desc");
          }
          else
          {
             $this->db->order_by('tbl_market_request.numberOfDays',"asc"); 
          }
          

          $this->db->group_by('tbl_market_request.id');
          $result = $this->db->get();
          return $result->result_array();

    }
	function getUserFedafinRating($userId)
	{
		
		$this->db->select("tbl_users.shortTermRatingAgency as LT_Mnemonic,tbl_users.ShortTermRating as rating_name");
		$this->db->from("tbl_users");
	  $this->db->where("tbl_users.id",$userId);
		$result = $this->db->get();
		//echo $this->db->last_query();
                 return $result->row_array();
	}

  function getUserFedafinRating1($userId)
  {
    
    $this->db->select("tbl_users.shortTermRatingAgency as LT_Mnemonic,");
    $this->db->from("tbl_users");
    $this->db->join("tbl_fedafin_rating","tbl_fedafin_rating.id = tbl_users.ShortTermRating","left");
    $this->db->where("tbl_users.id",$userId);
    $result = $this->db->get();
    //echo $this->db->last_query();
                 return $result->row_array();
  }

    function openOrders($user_id,$currencyType)

    {

          $this->db->select('tbl_market_request.*,tbl_market_request.id as requestId, tbl_users.company_name,tbl_market_lender_response.lender_id as lenderId,tbl_market_request.is_bestOffer as showBestOffr,tbl_money_market.by_order,tbl_money_market.id as mid, tbl_money_market.buttons_id');

          $this->db->from('tbl_market_request');

          $this->db->join('tbl_users','tbl_users.id = tbl_market_request.borrower_id');

          $this->db->join('tbl_market_lender_response','tbl_market_lender_response.request_id = tbl_market_request.id','left');
          $this->db->join("tbl_money_market","tbl_money_market.value = tbl_market_request.term");
          $this->db->where('tbl_market_request.status','open');
	   $this->db->where('tbl_market_request.currency',$currencyType);
	   $this->db->where('tbl_market_request.amount !=',"0");
	   $this->db->where('tbl_market_request.is_archieve',"n");
          $this->db->where('tbl_users.status','y');
          $this->db->where('tbl_market_request.borrower_id',$user_id);

          $this->db->group_by('tbl_market_request.id');
          $this->db->order_by('tbl_market_request.id',"DESC");
          $result = $this->db->get();

          return $result->result_array();

    }

    function marketsettings($user_id,$currencyType)

    {

          // $sql  = "SELECT `tbl_users`.`company_name`,`tbl_users`.`id` as cmpany_id,tbl_marketsettings.*,`tbl_users`.`ShortTermRating`,`tbl_users`.`shortTermRatingAgency` FROM (`tbl_users`) LEFT JOIN `tbl_marketsettings` ON `tbl_users`.`id`= `tbl_marketsettings`.`third_party_user` WHERE `tbl_users`.`status` = 'y' AND (`tbl_marketsettings`.`setter_user_id` = '$user_id' or `tbl_marketsettings`.`setter_user_id` IS NULL ) AND (`tbl_marketsettings`.`currency` = '$currencyType' or `tbl_marketsettings`.`currency` IS NULL) and ( FIND_IN_SET('3', privilege) or FIND_IN_SET('1', privilege) or FIND_IN_SET('2', privilege)) GROUP by company_name";
   
             // $sql  = "SELECT `tbl_users`.`company_name`,`tbl_users`.`id` as cmpany_id,tbl_marketsettings.*,`tbl_users`.`ShortTermRating`,`tbl_users`.`shortTermRatingAgency` FROM (`tbl_users`) LEFT JOIN `tbl_marketsettings` ON `tbl_users`.`id`= `tbl_marketsettings`.`third_party_user` WHERE `tbl_users`.`status` = 'y' AND (`tbl_marketsettings`.`currency` = '$currencyType' or `tbl_marketsettings`.`currency` IS NULL) and ( FIND_IN_SET('3', privilege) or FIND_IN_SET('1', privilege) or FIND_IN_SET('2', privilege)) GROUP by company_name";
          $sql ="SELECT `tbl_users`.`company_name` as cname,`tbl_users`.`id` as cmpany_id,tbl_marketsettings.*,`tbl_users`.`ShortTermRating`,`tbl_users`.`shortTermRatingAgency` FROM (`tbl_users`) LEFT OUTER JOIN `tbl_marketsettings` ON `tbl_users`.`company_name`= `tbl_marketsettings`.`company_name` and `tbl_marketsettings`.`setter_user_id` = '$user_id' WHERE `tbl_users`.`status` = 'y' AND (`tbl_marketsettings`.`currency` = '$currencyType' or `tbl_marketsettings`.`currency` IS NULL) and ( FIND_IN_SET('3', privilege) or FIND_IN_SET('1', privilege) or FIND_IN_SET('2', privilege)) group by `tbl_users`.`company_name`";
 

                 $query = $this->db->query($sql);
              //   echo $this->db->last_query();
                 
                 return $query->result_array();

    }

    function keepOrders($user_id,$currencyType)

    {

          $this->db->select('tbl_market_request.*,tbl_market_request.id as requestId, tbl_users.company_name,tbl_market_lender_response.lender_id as lenderId,tbl_market_request.is_bestOffer as showBestOffr,tbl_money_market.by_order,tbl_money_market.id as mid, tbl_money_market.buttons_id');

          $this->db->from('tbl_market_request');

          $this->db->join('tbl_users','tbl_users.id = tbl_market_request.borrower_id');

          $this->db->join('tbl_market_lender_response','tbl_market_lender_response.request_id = tbl_market_request.id','left');
          $this->db->join("tbl_money_market","tbl_money_market.value = tbl_market_request.term");
          $this->db->where('tbl_market_request.status','keep');
     $this->db->where('tbl_market_request.currency',$currencyType);
     $this->db->where('tbl_market_request.amount !=',"0");
     $this->db->where('tbl_market_request.is_archieve',"n");
          $this->db->where('tbl_users.status','y');
          $this->db->where('tbl_market_request.borrower_id',$user_id);

          $this->db->group_by('tbl_market_request.id');
          $this->db->order_by('tbl_market_request.id',"DESC");
          $result = $this->db->get();

          return $result->result_array();

    }

    function myOpenResponcesForRequest($user_id,$currencyType)

    {

          $this->db->select('tbl_market_lender_response.*,tbl_market_lender_response.id as responseId, tbl_market_request.term, tbl_market_request.req_name as borrowerName,tbl_money_market.id as mid,tbl_money_market.buttons_id');

          $this->db->from('tbl_market_lender_response,tbl_money_market');

          $this->db->join('tbl_users','tbl_users.id = tbl_market_lender_response.lender_id');

           $this->db->join('tbl_market_request','tbl_market_lender_response.request_id = tbl_market_request.id');

          $this->db->where('tbl_market_lender_response.status','open');
          $this->db->where('tbl_users.status','y');
          $this->db->where('tbl_market_lender_response.lender_id',$user_id);
          $this->db->where('tbl_market_request.currency_type',$currencyType);
          $this->db->where('tbl_money_market.value = tbl_market_request.term');
          $this->db->where('tbl_market_request.is_archieve !=',"y");
		  
          $this->db->group_by('tbl_market_lender_response.id');

          $result = $this->db->get();

          return $result->result_array();

    }
public function allCancelMyRequests($user_id,$currType)
  {
   
    $this->db->select("tbl_market_request.*,tbl_market_request.borrower_id as borrowerId ,tbl_market_request.amount_display as amount,tbl_market_request.min_bid as int_rate, tbl_users.company_name as borrowerCompany,tbl_money_market.id as mid,tbl_money_market.buttons_id ");
    $this->db->from("tbl_market_request,tbl_money_market");
    $this->db->join("tbl_users","tbl_users.id = tbl_market_request.borrower_id");
    $this->db->where("tbl_users.status","y");
    $this->db->where("tbl_market_request.currency_type", $currType);
    $this->db->where("tbl_market_request.status","withdraw");
    $this->db->where('tbl_money_market.value = tbl_market_request.term');
    //$this->db->where("tbl_market_request.is_archieve",'n');
    $this->db->where("tbl_market_request.borrower_id", $user_id);
    $resiult = $this->db->get();
    return $resiult->result_array();
  }
	function myOpenRequestsForOffers($user_id,$currencyType)
	{

	    $this->db->select('tbl_market_offer.*,tbl_market_offer.id as offerId, tbl_money_market.by_order, tbl_money_market.id as mid,tbl_money_market.buttons_id');
		$this->db->from('tbl_market_offer');
		$this->db->join('tbl_users','tbl_users.id = tbl_market_offer.lender_id');
		$this->db->join('tbl_money_market','tbl_money_market.value = tbl_market_offer.term');
		$this->db->where('tbl_market_offer.status','open');
		$this->db->where('tbl_market_offer.is_archieve','n');
	    $this->db->where('tbl_market_offer.currency_type',$currencyType);
        $this->db->where('tbl_users.status','y');
		$this->db->where('tbl_market_offer.amount != ',"0 mio");
        $this->db->where('tbl_market_offer.lender_id',$user_id);
		//$this->db->group_by('tbl_market_lender_response.id');
		$result = $this->db->get();
		return $result->result_array();
	}

   function keepForOffers($user_id,$currencyType)
  {

      $this->db->select('tbl_market_offer.*,tbl_market_offer.id as offerId, tbl_money_market.by_order, tbl_money_market.id as mid,tbl_money_market.buttons_id');
    $this->db->from('tbl_market_offer');
    $this->db->join('tbl_users','tbl_users.id = tbl_market_offer.lender_id');
    $this->db->join('tbl_money_market','tbl_money_market.value = tbl_market_offer.term');
    $this->db->where('tbl_market_offer.status','keep');
    $this->db->where('tbl_market_offer.is_archieve','n');
      $this->db->where('tbl_market_offer.currency_type',$currencyType);
        $this->db->where('tbl_users.status','y');
    $this->db->where('tbl_market_offer.amount != ',"0 mio");
        $this->db->where('tbl_market_offer.lender_id',$user_id);
    //$this->db->group_by('tbl_market_lender_response.id');
    $result = $this->db->get();
    return $result->result_array();
  }

     function getmarketMoney($buttonId) 

    {

        $this->db->select("*");

		 $this->db->where('buttons_id',$buttonId);

        $this->db->from("tbl_money_market");

		 $this->db->order_by("by_order","asc");

	     $result = $this->db->get();

       return $result->result_array();

    }

	function allMarketMoney()

	{

		$this->db->select("*");

		$this->db->where("id != 15");
		$this->db->from("tbl_money_market");

		$this->db->order_by("by_order","asc");

		$result = $this->db->get();

        return $result->result_array();

		

	}

    function checkExistingRequstForMaturity($maturityMonthss)

    {

        $this->db->select('id');

        $this->db->where("term",$maturityMonthss);

        //$this->db->where("borrower_id",$user_id);

        $this->db->where("is_bestOffer","y");

        $this->db->where("is_accepted","n");

        

        $this->db->from("tbl_market_request");

       

        $result = $this->db->get();

        return $result->result_array();

    }

    public function updateRequestForMaturity($marketRequestInsert)

    {

        $array = array("is_bestOffer"=>"y");

        $this->db->where("id",$marketRequestInsert);

        $this->db->update("tbl_market_request",$array);

    }

    function termOfRequest($requestId)

    {

        $this->db->select("term");

        $this->db->where("id",$requestId);

        $this->db->from("tbl_market_request");

        $result = $this->db->get();

        return $result->result_array();

    }

    

    public function updateRequestTableBestOffer($term)

    {

        $this->db->select("id");

        $this->db->where("term",$term);

        $this->db->where("status","open");

        $this->db->where("is_bestOffer","n");

        $this->db->from("tbl_market_request");

        $this->db->limit(1); 

        $result = $this->db->get();

        return $result->result_array();

    }

    public function makeBestOfferActive($nextId)

    {

        $array = array("is_bestOffer"=>"y");

        $this->db->where("id",$nextId);

        $this->db->update("tbl_market_request",$array);

    }

    public function deleteRequest($reqId)

    {
		$withDrawTime = date("Y-m-d H:i:s");
        $array = array("status"=>'withdraw',"withdrawTime"=> $withDrawTime);
 
        $this->db->where("id",$reqId);
        $this->db->update("tbl_market_request",$array);
        
    }

     public function keepbid($reqId)

    {
    
        $array = array("status"=>'keep');
 
        $this->db->where("id",$reqId);
        $this->db->update("tbl_market_request",$array);
        
    }


 public function keepoffer($reqId)

    {
    
        $array = array("status"=>'keep');
 
        $this->db->where("id",$reqId);
        $this->db->update("tbl_market_offer",$array);
        
    }


    public function updateMarketRequest($u_req_id,$dataValues)

    {

       

          $this->db->where("id",$u_req_id);

        $this->db->update("tbl_market_request",$dataValues);

    }

    public function checkRequestedAmount($requestId)

    {

        $this->db->select("amount_display");

        $this->db->where("id",$requestId);

       

        $this->db->from("tbl_market_request");

        $this->db->limit(1); 

        $result = $this->db->get();

        return $result->result_array();

    }

    public function offeredAmount($responceId)

    {

        $this->db->select("amount");

        $this->db->where("id",$responceId);
        $this->db->from("tbl_market_lender_response");

        $this->db->limit(1); 

        $result = $this->db->get();

        return $result->result_array();  

    }

    public function updateRequestedAmount($newAmount,$amountInserted,$requestId)

    {

        $array  = array("amount"=>$newAmount,"amount_display"=>$amountInserted);

         $this->db->where("id",$requestId);

        $this->db->update("tbl_market_request",$array); 

    }

    public function acceptedResponse($responceId)
    {
         $array   = array("status"=>"closed","request_status"=>"a");
	     $this->db->where("id",$responceId);
         $this->db->update("tbl_market_lender_response",$array); 
    }

	public function acceptedResponseOffer($responceId)

    {

            $array   = array("status"=>"closed");

            $this->db->where("id",$responceId);

            $this->db->update("tbl_market_offer",$array); 

    }

   

    public function deniedAmendedAmount($responceId)

    {

        $array = array("status"=>"closed","is_update"=>"n","request_status"=>"d");

        $this->db->where("id",$responceId);

        $this->db->update("tbl_market_lender_response",$array); 

        return $this->db->affected_rows();

    }

    public function checkBorrowerId($requestid)

    {

        $this->db->select("id,borrower_id,amount");

        $this->db->where("id",$requestid);

        $this->db->from("tbl_market_request");

        $result = $this->db->get();

        return $result->result_array();  

    }

    

     public function checkOffer($user_id,$requestid)

    {

        $this->db->select("id");

        $this->db->where("request_id",$requestid);

        $this->db->where("lender_id",$user_id);

        $this->db->from("tbl_market_lender_response");

        $result = $this->db->get();

        return $result->result_array();

    }

    public function addOffer($dataValues)

    {

        $this->db->insert('tbl_market_lender_response',$dataValues);

        return $this->db->insert_id();

    }

     public function notificationSend($dataVal)

    {

        $this->db->insert('tbl_market_notifications',$dataVal);

        return $this->db->insert_id();

    }

	public function notificationsReaded($notificationId)

	{

	   $array = array("readed"=>'y');

	   $this->db->where("id",$notificationId);

       $this->db->update("tbl_market_notifications",$array);

	}

    public function updateOffer($dataValues, $requestid,$user_id)

    {

        $this->db->where('request_id',$requestid);

        $this->db->where('lender_id',$user_id);

        $this->db->update('tbl_market_lender_response',$dataValues);

        return $this->db->affected_rows();

    }

	public function updateOffers($offerId,$updateOffer)

	{
         $this->db->where('id',$offerId);
         $this->db->update('tbl_market_offer',$updateOffer);
    }

   

    public function submitOfferByForm($dataVal)

    {

         $this->db->insert('tbl_market_offer',$dataVal);

         return $this->db->insert_id();

    }

    public function allOffersForTerms($term,$currType,$buttonsId)
    {

	 $this->db->select('tbl_market_offer.*');
         $this->db->from("tbl_market_offer");
         $this->db->join("tbl_users" , "tbl_users.id = tbl_market_offer.lender_id");
	 $this->db->where('tbl_market_offer.currency_type',$currType);
         $this->db->where('tbl_market_offer.term',$term);
         $this->db->where('tbl_market_offer.status',"open");
	 $this->db->where('tbl_market_offer.is_archieve','n');
	  $this->db->where('tbl_users.status',"y");
         $this->db->where('tbl_market_offer.amount != ',"0 mio");
         if($buttonsId != "2")
         {
              $this->db->order_by("tbl_market_offer.offer_rate","asc");
         }
        else
        {
            $this->db->order_by("tbl_market_offer.numberOfDays","asc");
        }
        //$this->db->limit(3);
	 $result = $this->db->get();
         return $result->result_array();

	}

	function updateResponseForBid($ypdateArray,$responseId)

	{

		 $this->db->where('id',$responseId);

        $this->db->update('tbl_market_lender_response',$ypdateArray);

		

	}

	function deleteBid($responceId)

	{

	     $array = array("request_status"=>'d',"status" => "closed");
         $this->db->where("id",$responceId);

         $this->db->update('tbl_market_lender_response',$array);		

	}

	function deleteOffer($offerId)

	{
		$withDrawTime = date("Y-m-d H:i:s");
          $array = array("status" => "withdraw","withdrawTime" => $withDrawTime);
		 $this->db->where("id",$offerId);

         $this->db->update('tbl_market_offer',$array);		

	}

	public function termOFRequestForId($name)

	{

		$this->db->select('*');

        $this->db->where('id',$name);

		$this->db->from("tbl_money_market");

		$result = $this->db->get();

		// echo $this->db->last_query();

         return $result->result_array();

	}

     public function insertNotifications($notificationValues)

    {

        $this->db->insert("tbl_market_notifications",$notificationValues);

    }

   

   

    public function termOfEditedRequest($u_req_id)

    {

         $this->db->select('term,is_bestOffer');

         $this->db->where('id',$u_req_id);

         $this->db->from("tbl_market_request");

         $result = $this->db->get();

         return $result->result_array();

    }

    public function updateRequestBestOffer($u_req_id)

    {

        $array = array("is_bestOffer"=>"n");

        $this->db->where('id',$u_req_id);

        $this->db->update('tbl_market_request',$array);

       // return $this->db->affected_rows();

    }

    public function checkForBestOffer($u_maturityMonthss)

    {

         $this->db->select('id');

         $this->db->where('is_bestOffer','y');

         $this->db->where('term',$u_maturityMonthss);

         $this->db->where('is_accepted','n');

         $this->db->from("tbl_market_request");

         $result = $this->db->get();

         return $result->result_array(); 

    }

    public function updateEditedBestOffer($u_req_id)

    {

         $array = array("is_bestOffer"=> "y");

         $this->db->where('id',$u_req_id);

         $this->db->update('tbl_market_request',$array);

    }

    public function nextTermBestOffer($u_req_id,$term)

    {
		$this->db->select('id');
		$this->db->where('term',$term);
		$this->db->where('is_accepted',"n");
        $this->db->where('status','open');
		$this->db->from("tbl_market_request");
		$this->db->limit(2);
	$result = $this->db->get();

         return $result->result_array(); 

        

    }

    public function makeNextTermAsBestOffer($nextID)

    {

         $array = array("is_bestOffer"=> "y");

         $this->db->where('id',$nextID);

         $this->db->update('tbl_market_request',$array);

    }

   public function submitPartiallyAmount($dataValues)

   {
        $this->db->insert("tbl_market_offers_responces",$dataValues);
         return $this->db->insert_id();
   }

   

   public function responseDetail($responceId)

   {
	  
         $this->db->select('amount_demand,request_id,amount,int_rate,offer_rate');

         $this->db->where('id',$responceId);

         $this->db->from("tbl_market_lender_response");

         $result = $this->db->get();

         return $result->result_array(); 

   }

    public function allDetailOfLenderResponce($responceId)

   {

         $this->db->select('*');

         $this->db->where('id',$responceId);

         $this->db->from("tbl_market_lender_response");

         $result = $this->db->get();

         return $result->result_array(); 

   }

   function accepetFullDeal($accpetedArray)

   {

      $this->db->insert('tbl_market_loan_accepted',$accpetedArray);

	  return $this->db->insert_id();

   }

   function updatedResponceAmountLeft($responceId,$amountLeftForLender)

   {

       $amountArray = array("amount"=>$amountLeftForLender,"is_update"=>"n");

       $this->db->where('id',$responceId);

       $this->db->update('tbl_market_lender_response',$amountArray);

   }

   function publicHolidays($currency)

   {

        $this->db->select('date_select');
         $this->db->where("holidayFor",$currency);

         $this->db->from("tbl_calender");

         $result = $this->db->get();

         return $result->result_array(); 

   }

   function publicHolidaysEur()

   {

	$this->db->select('date_select_eur');
        $this->db->where(array('date_select_eur != '=> '' ));
        $this->db->from("tbl_calender");

         $result = $this->db->get();

         return $result->result_array();

   }

   function dataForPdf($requestId,$responceId)

   {

	     $this->db->select('tbl_market_request.id as requestId, tbl_market_request.amount_display as requestedmount, tbl_market_request.min_bid as minBid, tbl_market_request.term, tbl_market_request.req_name');

		 $this->db->from("tbl_market_request");

         $this->db->where('id',$requestId);

         $result = $this->db->get();

         return $result->result_array();

   }

   function responseForRequestPdf($responceId)

   {

	     $this->db->select('tbl_market_loan_accepted.id as responseId,tbl_market_loan_accepted.ammount_accepted as acceptedAmount,tbl_market_loan_accepted.interest_rate as offeredRate, tbl_users.company_name as lenderName');

		 $this->db->from("tbl_market_loan_accepted");

		 $this->db->join("tbl_market_lender_response","tbl_market_lender_response.id = tbl_market_loan_accepted.response_id");

		 $this->db->join("tbl_users","tbl_users.id = tbl_market_lender_response.lender_id");

		 

         $this->db->where('response_id',$responceId);

         $result = $this->db->get();

         return $result->result_array();

   }

   function responseForOfferPdf1($tbl_market_loan_accepted)

   {

	   $this->db->select("tbl_market_loan_accepted.* , tbl_users.company_name as borrowerCompany ,tbl_users.street as borrowerStreet ,tbl_users.zip_code as borrowerZipCode ,tbl_users.city as borrowerCity ,tbl_users.country as borrowerCountry , tbl_users.iban_number as borrowerIban,tbl_users.alternate_email as borowerAlternativeMail, tbl_users.beneficiary_name as bowrrowerBenificiaryName, tbl_users.fname as borrowerFname, tbl_users.lname as borrowerLname,

	   tbl_users.bank_account as borrowerAccountNo ,tbl_users.email as borrowerEmail,tbl_users.CHFInstructions,tbl_users.EURInstructions,tbl_users.USDInstructions");

	   $this->db->from("tbl_market_loan_accepted");

	   $this->db->join("tbl_users","tbl_users.id = tbl_market_loan_accepted.borrower_id");

	   $this->db->where('tbl_market_loan_accepted.id',$tbl_market_loan_accepted);

	   $this->db->where('tbl_market_loan_accepted.accepted_by',"offer");

       $result = $this->db->get();

       return $result->result_array();

   }
   
    function responseForOfferPdf($tbl_market_loan_accepted)

   {

     $this->db->select("tbl_market_loan_accepted.* , tbl_users.company_name as borrowerCompany ,tbl_users.street as borrowerStreet ,tbl_users.zip_code as borrowerZipCode ,tbl_users.city as borrowerCity ,tbl_users.country as borrowerCountry , tbl_users.iban_number as borrowerIban,tbl_users.alternate_email as borowerAlternativeMail, tbl_users.beneficiary_name as bowrrowerBenificiaryName, tbl_users.fname as borrowerFname, tbl_users.lname as borrowerLname,

     tbl_users.bank_account as borrowerAccountNo ,tbl_users.email as borrowerEmail,tbl_users.CHFInstructions as borrowerCHFInstructions,tbl_users.EURInstructions as borrowerEURInstructions,tbl_users.USDInstructions as borrowerUSDInstructions");

     $this->db->from("tbl_market_loan_accepted");

     $this->db->join("tbl_users","tbl_users.id = tbl_market_loan_accepted.borrower_id");

     $this->db->where('tbl_market_loan_accepted.id',$tbl_market_loan_accepted);

     $this->db->where('tbl_market_loan_accepted.accepted_by',"offer");

       $result = $this->db->get();

       return $result->result_array();

   }
    function responseForOfferPdfEur($tbl_market_loan_accepted)

   {

	   $this->db->select("tbl_market_loan_accepted.* , tbl_users.company_name as borrowerCompany ,tbl_users.street as borrowerStreet ,tbl_users.zip_code as borrowerZipCode ,tbl_users.city as borrowerCity ,tbl_users.country as borrowerCountry , tbl_users.eur_iban_num as borrowerIban,tbl_users.alternate_email as borowerAlternativeMail, tbl_users.eur_beneficiary_name as bowrrowerBenificiaryName, tbl_users.fname as borrowerFname, tbl_users.lname as borrowerLname,

	   tbl_users.eur_bank_acc as borrowerAccountNo ,tbl_users.email as borrowerEmail,tbl_users.CHFInstructions,tbl_users.EURInstructions,tbl_users.USDInstructions");

	   $this->db->from("tbl_market_loan_accepted");

	   $this->db->join("tbl_users","tbl_users.id = tbl_market_loan_accepted.borrower_id");

	   $this->db->where('tbl_market_loan_accepted.id',$tbl_market_loan_accepted);

	   $this->db->where('tbl_market_loan_accepted.accepted_by',"offer");

       $result = $this->db->get();

       return $result->result_array();

   }
   
   
   
   

   function responseLenderDetails($offered_id)

   {

	    $this->db->select("tbl_users.company_name as lenderCompany, tbl_users.street as lenderStreet, tbl_users.zip_code as lenderZipcode,  tbl_users.city as lenderCity,  tbl_users.country as lenderCountry, tbl_users.beneficiary_name as lenderBenificiaryName,tbl_users.iban_number as lenderIban , tbl_users.fname as lenderFname, tbl_users.lname as lenderLname,tbl_users.alternate_email as lenderAlterNativeEmail,
	   tbl_users.bank_account as lenderAccountNo ,tbl_users.email as lenderEmail, tbl_market_offer.currency as currrencyText, tbl_market_offer.maturity_date as maturity,tbl_users.CHFInstructions as LenderCHFInstructions,tbl_users.EURInstructions as LenderEURInstructions,tbl_users.USDInstructions as LenderUSDInstructions");

	   $this->db->from("tbl_market_offer");

	   $this->db->join("tbl_users","tbl_users.id = tbl_market_offer.lender_id");

	   $this->db->where('tbl_market_offer.id',$offered_id);

	   

       $result = $this->db->get();

       return $result->result_array();

   }
    function responseLenderDetailsEur($offered_id)

   {

	    $this->db->select("tbl_users.company_name as lenderCompany, tbl_users.street as lenderStreet, tbl_users.zip_code as lenderZipcode,  tbl_users.city as lenderCity,  tbl_users.country as lenderCountry, tbl_users.eur_beneficiary_name as lenderBenificiaryName, tbl_users.eur_iban_num as lenderIban , tbl_users.fname as lenderFname, tbl_users.lname as lenderLname,tbl_users.alternate_email as lenderAlterNativeEmail,

	   tbl_users.eur_bank_acc as lenderAccountNo ,tbl_users.email as lenderEmail, tbl_market_offer.currency as currrencyText, tbl_market_offer.maturity_date as maturity");

	   $this->db->from("tbl_market_offer");

	   $this->db->join("tbl_users","tbl_users.id = tbl_market_offer.lender_id");

	   $this->db->where('tbl_market_offer.id',$offered_id);

	   

       $result = $this->db->get();

       return $result->result_array();

   }

   function responseDetails($responseId)

   {

	   $this->db->select("amount");

	   $this->db->where("id",$responseId);

	   $this->db->from("tbl_market_lender_response");

	   $result = $this->db->get();

       return $result->result_array();

   }

   function maturyOfResponse($responceId)

   {

	   $this->db->select("term,amount,offer_rate,lender_id");

	   $this->db->where("id",$responceId);

	   $this->db->from("tbl_market_offer");

	   $result = $this->db->get();

       return $result->result_array();

   }

   function requestIdForResponse($user_id, $responseTerm)

   {

	   $this->db->select("id,amount_display,currency");

	   $this->db->where("term",$responseTerm);

	   $this->db->where("borrower_id",$user_id);

	   $this->db->where("status","open");

	   $this->db->from("tbl_market_request");

	   $result = $this->db->get();

       return $result->result_array();

   }

     

	function confirmEditOffer($offerId)

	{

		$array = array("lender_accepted"=>'y');

		$this->db->where("id",$offerId);

		$this->db->update("tbl_market_offers_responces",$array);

	}

	function getLenderId($responceId)

	{

	   $this->db->select("lender_id,amount");
       $this->db->where("id",$responceId);
       $this->db->from("tbl_market_offer");
       $result = $this->db->get();
       return $result->result_array();

	}

	function responsefullDetails($responceId)

	{

	    $this->db->select("tbl_market_lender_response.*,tbl_market_request.term");

	    $this->db->from("tbl_market_lender_response");

		$this->db->join("tbl_market_request","tbl_market_request.id = tbl_market_lender_response.request_id");

	    $this->db->where("tbl_market_lender_response.id",$responceId);

	    $result = $this->db->get();

        return $result->result_array();

	}

	

	function marketOfferDetail($offer_id)

	{

		$this->db->select("*");

	  

	   $this->db->where("id",$offer_id);

	   $this->db->from("tbl_market_offer");

	   $result = $this->db->get();

       return $result->result_array();

	}

	function updateOfferResponceTbl($updateOrferArr,$offerId)

	{

		$this->db->where("id",$offerId);

		$this->db->update("tbl_market_offers_responces",$updateOrferArr);

	}

	function updateAmountOffer($restAmmnt,$offer_id)
    {

		$array = array("amount"=>$restAmmnt);

		$this->db->where("id",$offer_id);

		$this->db->update("tbl_market_offer",$array);

	}

	function closeOffer($offer_id)

	{

		$array = array("status"=>"closed","is_acepted_fully"=>"y");

		$this->db->where("id",$offer_id);

		$this->db->update("tbl_market_offer",$array);

	}

	function acceptedDealDataForPdf($responceId)

	{

		$this->db->select("tbl_market_lender_response.amount as ammount_accepted,tbl_market_lender_response.request_id as requestId,tbl_market_lender_response.int_rate as interest_rate,tbl_market_request.term as accepted_term ,tbl_users.company_name as lenderCompany , tbl_users.beneficiary_name as lenderBenificiaryName , tbl_users.email as lenderEmail,

		tbl_users.bank_account as lenderAccountNo , tbl_users.fname as lenderFname, tbl_users.lname as lenderLnamelenderLname");

	    $this->db->from("tbl_market_lender_response");

	    $this->db->join("tbl_market_request","tbl_market_request.id = tbl_market_lender_response.request_id");

	    $this->db->join("tbl_users","tbl_users.id = tbl_market_lender_response.lender_id");

	    $this->db->where("tbl_market_lender_response.id",$responceId);

	   

	    $result = $this->db->get();

        return $result->result_array();

	}

	function borowwerDetailForDeal($requestId)

	{

		$this->db->select("tbl_users.company_name as borrowerCompany , tbl_users.beneficiary_name as bowrrowerBenificiaryName , tbl_users.email as borrowerEmail,

		tbl_users.bank_account as borrowerAccountNo , tbl_users.fname as borrowerFname, tbl_users.lname as borrowerLname,tbl_users.CHFInstructions,tbl_users.EURInstructions,tbl_users.USDInstructions");

	    $this->db->from("tbl_market_request");

	  

	    $this->db->join("tbl_users","tbl_users.id = tbl_market_request.borrower_id");

	    $this->db->where("tbl_market_request.id",$requestId);

	   

	    $result = $this->db->get();

        return $result->result_array();

	}

	function insertPdfName($pdfInsertArr,$accepetFullDeal)

	{

		$this->db->where("id",$accepetFullDeal);

		$this->db->update("tbl_market_loan_accepted",$pdfInsertArr);

	}

    function pdfDataForInserted($accepetFullDeal)

    {

        $this->db->select("pdf_name");

	    $this->db->where("id",$accepetFullDeal);

	    $this->db->from("tbl_market_loan_accepted");

	    $result = $this->db->get();

        return $result->result_array();

    }

	public function adminDetails()

	{

	   $this->db->select("*");

	   $this->db->where("user_type","admin");

	   $this->db->from("tbl_users");

	   $result = $this->db->get();

       return $result->result_array();

	}

        function getDetailsOfRequestForPopUp($requestId)

        {

            $this->db->select("tbl_market_request.amount_display,tbl_market_request.currency,tbl_users.LT_Mnemonic as rating,tbl_fedafin_rating.rating_name");

            $this->db->from("tbl_market_request");

            $this->db->join("tbl_users","tbl_users.id = tbl_market_request.borrower_id");

            $this->db->join("tbl_fedafin_rating","tbl_fedafin_rating.id = tbl_users.fedafin_rating","left outer");

            $this->db->where("tbl_market_request.id",$requestId);

              $result = $this->db->get();

             return $result->result_array();

        }
		function allDetailForRequestRes($responceId)
		{
			$this->db->select("tbl_market_lender_response.*,tbl_market_request.amount_display,tbl_market_request.currency,tbl_market_request.term,tbl_money_market.buttons_id,tbl_money_market.id as mid");
			$this->db->from("tbl_market_lender_response");
			$this->db->join('tbl_market_request','tbl_market_request.id = tbl_market_lender_response.request_id');
      $this->db->join("tbl_money_market","tbl_money_market.value = tbl_market_request.term","left outer");

			$this->db->where('tbl_market_lender_response.id',$responceId);
			
			 $result = $this->db->get();

             return $result->result_array();

			
		}

    function offerDetails($offerId)
  {

     $this->db->select("tbl_market_offers_responces.*,tbl_market_offer.term,tbl_market_offers_responces.int_rate as offer_rate, tbl_market_offer.amount,tbl_money_market.buttons_id,tbl_money_market.id as mid");

     $this->db->from("tbl_market_offers_responces");

      $this->db->join("tbl_market_offer","tbl_market_offer.id = tbl_market_offers_responces.offer_id");
        $this->db->join("tbl_money_market","tbl_money_market.value = tbl_market_offer.term","left outer");
    $this->db->where("tbl_market_offers_responces.id",$offerId);

      $result = $this->db->get();
    return $result->result_array();
  }
		function requestsAmountDetail($requestId)
		{
			
			$this->db->select("amount_display");
			$this->db->where("id",$requestId);
			$this->db->from("tbl_market_request");
			 $result = $this->db->get();

             return $result->row_array();

		}
		function notificationDeatil($notificationId)
		{
			$this->db->select("*");
			$this->db->where("id",$notificationId);
			$this->db->from("tbl_market_notifications");
			 $result = $this->db->get();

             return $result->result_array();
		}

                 function resetOffer($offerId)
                {
                    $array = array("status" => 'open');
                    $this->db->where("id",$offerId);
                    $this->db->update("tbl_market_offer",$array);
                            
                }

                 function bid_spread($third_party,$user_id,$currencyType,$bid_value,$cname)
                {

                  $this->db->select("*");
                  $this->db->where("setter_user_id",$user_id);
                  $this->db->where("company_name",$cname);
                   $this->db->where("currency",$currencyType);
                  $this->db->from("tbl_marketsettings");
                  $result = $this->db->get();
                  $rowcount = $result->num_rows();
                  if($rowcount ==0){
                    $data = array(
               'setter_user_id'=>$user_id,
               'company_name'=>$cname,'currency'=>$currencyType,'Bid-Spread'=>$bid_value
                );

                $this->db->insert('tbl_marketsettings',$data);
                  }
                 else {
                    $data = array( 'Bid-Spread'=>$bid_value);
                    $this->db->where("setter_user_id",$user_id);
                   $this->db->where("company_name",$cname);
                   $this->db->where("currency",$currencyType);
                    $this->db->update("tbl_marketsettings",$data);

                 }
                   
                   //echo $this->db->last_query();         
                }

                function offer_spread($third_party,$user_id,$currencyType,$bid_value,$cname)
                {

                  $this->db->select("*");
                  $this->db->where("setter_user_id",$user_id);
                  $this->db->where("company_name",$cname);
                   $this->db->where("currency",$currencyType);
                  $this->db->from("tbl_marketsettings");
                  $result = $this->db->get();
                  $rowcount = $result->num_rows();
                  if($rowcount ==0){
                    $data = array(
               'setter_user_id'=>$user_id,
               'company_name'=>$cname,'currency'=>$currencyType,'Offer-Spread'=>$bid_value
                );

                $this->db->insert('tbl_marketsettings',$data);
                  }
                 else {
                    $data = array('Offer-Spread'=>$bid_value);
                    $this->db->where("setter_user_id",$user_id);
                  $this->db->where("company_name",$cname);
                   $this->db->where("currency",$currencyType);
                    $this->db->update("tbl_marketsettings",$data);

                 }
                   
                   //echo $this->db->last_query();         
                }

                 function check_bid($third_party,$user_id,$currencyType,$cname)
                {

                  $this->db->select("*");
                  $this->db->where("setter_user_id",$user_id);
                 // $this->db->where("third_party_user",$third_party);
                   $this->db->where("company_name",$cname);
                   $this->db->where("currency",$currencyType);
                  $this->db->from("tbl_marketsettings");
                  $result = $this->db->get();
                  $rowcount = $result->num_rows();
                  if($rowcount ==0){
                    $data = array(
               'setter_user_id'=>$user_id,
               'company_name'=>$cname,'currency'=>$currencyType,'bid'=>'Y'
                );

                $this->db->insert('tbl_marketsettings',$data);
                  }
                 else {
                    $data = array('bid'=>'Y');
                    $this->db->where("setter_user_id",$user_id);
                 // $this->db->where("third_party_user",$third_party);
                   $this->db->where("company_name",$cname);
                   $this->db->where("currency",$currencyType);
                    $this->db->update("tbl_marketsettings",$data);

                 }
                   
                  
                }

                 function uncheck_bid($third_party,$user_id,$currencyType,$cname)
                {

                  $this->db->select("*");
                  $this->db->where("setter_user_id",$user_id);
                 // $this->db->where("third_party_user",$third_party);
                   $this->db->where("company_name",$cname);
                   $this->db->where("currency",$currencyType);
                  $this->db->from("tbl_marketsettings");
                  $result = $this->db->get();
                  $rowcount = $result->num_rows();
                  if($rowcount ==0){
                    $data = array(
               'setter_user_id'=>$user_id,
               'company_name'=>$cname,'currency'=>$currencyType,'bid'=>'N'
                );

                $this->db->insert('tbl_marketsettings',$data);
                  }
                 else {
                    $data = array('bid'=>'N');
                    $this->db->where("setter_user_id",$user_id);
                //  $this->db->where("third_party_user",$third_party);
                   $this->db->where("company_name",$cname);
                   $this->db->where("currency",$currencyType);
                    $this->db->update("tbl_marketsettings",$data);

                 }
                   
                        
                }

                  function check_offer($third_party,$user_id,$currencyType,$cname)
                {

                  $this->db->select("*");
                  $this->db->where("setter_user_id",$user_id);
                 // $this->db->where("third_party_user",$third_party);
                   $this->db->where("company_name",$cname);
                   $this->db->where("currency",$currencyType);
                  $this->db->from("tbl_marketsettings");
                  $result = $this->db->get();
                  $rowcount = $result->num_rows();
                  if($rowcount ==0){
                    $data = array(
               'setter_user_id'=>$user_id,
               'company_name'=>$cname,'currency'=>$currencyType,'offer'=>'Y'
                );

                $this->db->insert('tbl_marketsettings',$data);
                  }
                 else {
                    $data = array('offer'=>'Y');
                    $this->db->where("setter_user_id",$user_id);
                 // $this->db->where("third_party_user",$third_party);
                   $this->db->where("currency",$currencyType);
                    $this->db->update("tbl_marketsettings",$data);

                 }
                   
                  
                }

                 function uncheck_offer($third_party,$user_id,$currencyType,$cname)
                {

                  $this->db->select("*");
                  $this->db->where("setter_user_id",$user_id);
                  //$this->db->where("third_party_user",$third_party);
                   $this->db->where("company_name",$cname);
                   $this->db->where("currency",$currencyType);
                  $this->db->from("tbl_marketsettings");
                  $result = $this->db->get();
                  $rowcount = $result->num_rows();
                  if($rowcount ==0){
                    $data = array(
               'setter_user_id'=>$user_id,
               'company_name'=>$cname,'currency'=>$currencyType,'offer'=>'N'
                );

                $this->db->insert('tbl_marketsettings',$data);
                  }
                 else {
                    $data = array('offer'=>'N');
                    $this->db->where("setter_user_id",$user_id);
                 // $this->db->where("third_party_user",$third_party);
                   $this->db->where("company_name",$cname);
                   $this->db->where("currency",$currencyType);
                    $this->db->update("tbl_marketsettings",$data);

                 }
                   
                        
                }



                 function deleteKeptoffers($offerId)
                {
                    $array = array("is_archieve" => 'y');
                    $this->db->where("id",$offerId);
                    $this->db->update("tbl_market_offer",$array);
                            
                }
                 function deleteKeptbids($offerId)
                {
                    $array = array("is_archieve" => 'y');
                    $this->db->where("id",$offerId);
                    $this->db->update("tbl_market_request",$array);
                            
                }


                function resetBid($requestId)
                {
                   $array = array("status" => 'open');
                  
                    $this->db->where("id",$requestId);
                    $this->db->update("tbl_market_request",$array); 
                }
                function withDrawAllCurrentlyOpenRequests()
                {
                    $array = array("status"=>"withdraw");
                    $this->db->where("status","open");
                     $this->db->update("tbl_market_request",$array);
                    return $this->db->affected_rows();
                }
                function withDrawAllCurrentlyOpenOffers()
                {
                    $array = array("status"=>"withdraw");
                    $this->db->where("status","open");
                     $this->db->update("tbl_market_offer",$array);
                    return $this->db->affected_rows();
                }
    function allBestValuesRequest($marketValue, $currencyType)
    {

          $this->db->select('tbl_market_request.amount_display as requestedAmount,tbl_market_request.min_bid,tbl_market_request.borrower_id');
		  $this->db->from('tbl_market_request');
		  $this->db->join('tbl_users','tbl_users.id = tbl_market_request.borrower_id');
		  $this->db->where('tbl_market_request.is_accepted','n');
		  $this->db->where('tbl_market_request.term',$marketValue);
		  
		  $this->db->where('tbl_market_request.amount !=','0');
          $this->db->where('tbl_market_request.is_archieve','n');
		  $this->db->where('tbl_market_request.status','open');
          $this->db->where('tbl_users.status','y');
	      $this->db->where('tbl_market_request.currency_type',$currencyType);
          $this->db->order_by('tbl_market_request.min_bid',"desc");
          $this->db->group_by('tbl_market_request.id');
          $this->db->limit(1);
          $result = $this->db->get();
		  $status = $result->row_array();
     // echo $this->db->last_query();
          return($status);
    }
     public function allBestOffers($marketValue,$currType)

	{
        $this->db->select('tbl_market_offer.term,tbl_market_offer.amount as offerAmount,tbl_market_offer.offer_rate,tbl_market_offer.lender_id');
         $this->db->from("tbl_market_offer");
         $this->db->join("tbl_users" , "tbl_users.id = tbl_market_offer.lender_id");
		 $this->db->where('tbl_market_offer.currency_type',$currType);
         $this->db->where('tbl_market_offer.term',$marketValue);
         $this->db->where('tbl_market_offer.status',"open");
		 $this->db->where('tbl_market_offer.is_archieve','n');
		 $this->db->where('tbl_market_offer.amount !=',"0 mio");
		 $this->db->where('tbl_users.status',"y");
         $this->db->order_by("tbl_market_offer.offer_rate","asc");

		 $this->db->limit(1);
          $result = $this->db->get();
          return $result->row_array();

	}   
    
    function updateRequestAmount($updateRequestArray,$requestid)
	{
		$this->db->where("id",$requestid);
		$this->db->update("tbl_market_request",$updateRequestArray);
	}	
    function responseAmountDetail($responceId)
	{
		$this->db->select("amount,request_id");
		$this->db->where("id",$responceId);
		$this->db->from("tbl_market_lender_response");
		$result = $this->db->get();
        return $result->row_array();
	}
	function amountOfRequestDetails($requestId)
	{
		$this->db->select("amount");
		$this->db->where("id",$requestId);
		$this->db->from("tbl_market_request");
		$result = $this->db->get();
        return $result->row_array();
	}

	function timedOutResponse($responceId, $timedOutArray)
	{
		$this->db->where("id",$responceId);
		$this->db->where("request_status","n");
		$this->db->update("tbl_market_lender_response",$timedOutArray);
		$updated_status = $this->db->affected_rows();

        if($updated_status)
		{
			return $responceId;
		}
        else
		{
			return 0;
		}
          
	}
	function ammountOfCurrentResponse($responceId)
	{
		$this->db->select("amount,request_id");
		$this->db->where("id" , $responceId);
		$this->db->from("tbl_market_lender_response");
		$result = $this->db->get();
		return $result->row_array();
	}
	
	function allTimedOutOrdersForBorrowerForDates($user_id,$currencyType,$startDate,$endDate)
	{
		  $this->db->select("tbl_market_lender_response.amount,tbl_market_lender_response.value_date, tbl_market_lender_response.maturity_date,tbl_market_lender_response.int_rate, tbl_market_request.term, tbl_market_request.borrower_id as borrowerId, tbl_users.company_name as lenderCompany, tbl_market_lender_response.deniedDate,tbl_market_lender_response.request_status");

		  $this->db->from('tbl_market_lender_response');
		  $this->db->join('tbl_market_request','tbl_market_request.id = tbl_market_lender_response.request_id');
		  $this->db->join('tbl_users','tbl_users.id = tbl_market_lender_response.lender_id');

		  $this->db->where('tbl_market_request.borrower_id',$user_id);
		  $this->db->where('tbl_market_request.currency_type',$currencyType);
          
		  $this->db->where('tbl_market_lender_response.deniedDate >=',$startDate);
		  $this->db->where('tbl_market_lender_response.deniedDate <=',$endDate);
		  $this->db->where('tbl_users.status','y');
		  $this->db->where('tbl_market_lender_response.request_status','to');
		  $this->db->order_by("tbl_market_lender_response.id","DESC");
		  $result = $this->db->get();
          return $result->result_array();
	}
	function allTimedOutOrdersForBorrower($user_id,$currencyType)
	{
		  $this->db->select("tbl_market_lender_response.amount,tbl_market_lender_response.value_date, tbl_market_lender_response.maturity_date,tbl_market_lender_response.int_rate, tbl_market_request.term, tbl_market_request.borrower_id as borrowerId, tbl_users.company_name as lenderCompany, tbl_market_lender_response.deniedDate,tbl_market_lender_response.request_status");

		  $this->db->from('tbl_market_lender_response');
		  $this->db->join('tbl_market_request','tbl_market_request.id = tbl_market_lender_response.request_id');
		  $this->db->join('tbl_users','tbl_users.id = tbl_market_lender_response.lender_id');

		  $this->db->where('tbl_market_request.borrower_id',$user_id);
		  $this->db->where('tbl_market_request.currency_type',$currencyType);
          //$this->db->where('tbl_market_request.is_archieve !=','y');
          $this->db->where('tbl_users.status','y');
		  $this->db->where('tbl_market_lender_response.request_status','to');
		  $this->db->order_by("tbl_market_lender_response.id","DESC");
		  $result = $this->db->get();
          return $result->result_array();
	}
    function timedOutOrdersForBorrower($user_id,$currencyType)
	{
		  $this->db->select("tbl_market_lender_response.amount,tbl_market_lender_response.int_rate, tbl_market_request.term, tbl_market_request.borrower_id as borrowerId, tbl_users.company_name as lenderCompany, tbl_market_lender_response.deniedDate,tbl_market_lender_response.request_status");

		  $this->db->from('tbl_market_lender_response');
		  $this->db->join('tbl_market_request','tbl_market_request.id = tbl_market_lender_response.request_id');
		  $this->db->join('tbl_users','tbl_users.id = tbl_market_lender_response.lender_id');

		  $this->db->where('tbl_market_request.borrower_id',$user_id);
		  $this->db->where('tbl_market_request.currency_type',$currencyType);
          $this->db->where('tbl_market_request.is_archieve !=','y');
          $this->db->where('tbl_users.status','y');
		  $this->db->where('tbl_market_lender_response.request_status','to');
		  $this->db->order_by("tbl_market_lender_response.id","DESC");
		  $result = $this->db->get();
          return $result->result_array();
	}
	
	function allTimedOutOrdersForLenderForDates($user_id,$currencyType,$startDate,$endDate)
	{
		  $this->db->select("tbl_market_lender_response.amount,tbl_market_lender_response.int_rate,tbl_market_lender_response.value_date,tbl_market_lender_response.maturity_date, tbl_market_request.term, tbl_market_request.borrower_id as borrowerId, tbl_users.company_name as borrowerCompany, tbl_market_lender_response.deniedDate,tbl_market_lender_response.request_status,tbl_users.CHFInstructions,tbl_users.EURInstructions,tbl_users.USDInstructions");
		  $this->db->from('tbl_market_lender_response');
		  $this->db->join('tbl_market_request','tbl_market_request.id = tbl_market_lender_response.request_id');
		  $this->db->join('tbl_users','tbl_users.id = tbl_market_request.borrower_id');

		  $this->db->where('tbl_market_lender_response.lender_id',$user_id);
		  $this->db->where('tbl_market_request.currency_type',$currencyType);
          $this->db->where('tbl_market_lender_response.deniedDate >=',$startDate);
		  $this->db->where('tbl_market_lender_response.deniedDate <=',$endDate);
          $this->db->where('tbl_users.status','y');
		  $this->db->where('tbl_market_lender_response.request_status','to');
		  $this->db->order_by("tbl_market_lender_response.id","DESC");
		  $result = $this->db->get();
          return $result->result_array();
	}
	function allTimedOutOrdersForLender($user_id,$currencyType)
	{
		  $this->db->select("tbl_market_lender_response.amount,tbl_market_lender_response.int_rate,tbl_market_lender_response.value_date,tbl_market_lender_response.maturity_date, tbl_market_request.term, tbl_market_request.borrower_id as borrowerId, tbl_users.company_name as borrowerCompany, tbl_market_lender_response.deniedDate,tbl_market_lender_response.request_status,tbl_users.CHFInstructions,tbl_users.EURInstructions,tbl_users.USDInstructions");

		  $this->db->from('tbl_market_lender_response');
		  $this->db->join('tbl_market_request','tbl_market_request.id = tbl_market_lender_response.request_id');
		  $this->db->join('tbl_users','tbl_users.id = tbl_market_request.borrower_id');

		  $this->db->where('tbl_market_lender_response.lender_id',$user_id);
		  $this->db->where('tbl_market_request.currency_type',$currencyType);
          //$this->db->where('tbl_market_request.is_archieve !=','y');
          $this->db->where('tbl_users.status','y');
		  $this->db->where('tbl_market_lender_response.request_status','to');
		  $this->db->order_by("tbl_market_lender_response.id","DESC");
		  $result = $this->db->get();
          return $result->result_array();
	}
	function timedOutOrdersForLender($user_id,$currencyType)
	{
		  $this->db->select("tbl_market_lender_response.amount,tbl_market_lender_response.int_rate, tbl_market_request.term, tbl_market_request.borrower_id as borrowerId, tbl_users.company_name as borrowerCompany, tbl_market_lender_response.deniedDate,tbl_market_lender_response.request_status,tbl_users.CHFInstructions,tbl_users.EURInstructions,tbl_users.USDInstructions");

		  $this->db->from('tbl_market_lender_response');
		  $this->db->join('tbl_market_request','tbl_market_request.id = tbl_market_lender_response.request_id');
		  $this->db->join('tbl_users','tbl_users.id = tbl_market_request.borrower_id');

		  $this->db->where('tbl_market_lender_response.lender_id',$user_id);
		  $this->db->where('tbl_market_request.currency_type',$currencyType);
          $this->db->where('tbl_market_request.is_archieve !=','y');
          $this->db->where('tbl_users.status','y');
		  $this->db->where('tbl_market_lender_response.request_status','to');
		  $this->db->order_by("tbl_market_lender_response.id","DESC");
		  $result = $this->db->get();
          return $result->result_array();
	}
	
	
	function allTimedOutOfferOrderForBorrowerForDates($user_id,$currencyType,$startDate,$endDate)
	{
		$this->db->select("tbl_market_offers_responces.amount_demand as amount,tbl_market_offers_responces.maturity_date,tbl_market_offers_responces.value_date, tbl_market_offers_responces.int_rate as int_rate , tbl_market_offer.term, tbl_market_offers_responces.borrower_id as borrowerId,tbl_users.company_name as borrowerCompany,tbl_market_offers_responces.deniedDate, tbl_market_offers_responces.is_accepted as request_status,tbl_users.CHFInstructions,tbl_users.EURInstructions,tbl_users.USDInstructions");
        $this->db->from('tbl_market_offers_responces');
        $this->db->join('tbl_market_offer','tbl_market_offer.id = tbl_market_offers_responces.offer_id');
        $this->db->join('tbl_users','tbl_users.id = tbl_market_offers_responces.lender_id');
        $this->db->where('tbl_market_offer.currency_type',$currencyType);
		$this->db->where('tbl_market_offers_responces.deniedDate >=',$startDate);
		$this->db->where('tbl_market_offers_responces.deniedDate <=',$endDate);
        $this->db->where('tbl_market_offers_responces.borrower_id',$user_id);
        $this->db->where('tbl_users.status','y');
		$this->db->where('tbl_market_offers_responces.is_accepted','to');
	    $result = $this->db->get();
		return $result->result_array();
	}
	function allTimedOutOfferOrderForBorrower($user_id,$currencyType)
	{
		$this->db->select("tbl_market_offers_responces.amount_demand as amount,tbl_market_offers_responces.maturity_date,tbl_market_offers_responces.value_date, tbl_market_offers_responces.int_rate as int_rate , tbl_market_offer.term, tbl_market_offers_responces.borrower_id as borrowerId,tbl_users.company_name as borrowerCompany,tbl_market_offers_responces.deniedDate, tbl_market_offers_responces.is_accepted as request_status,tbl_users.CHFInstructions,tbl_users.EURInstructions,tbl_users.USDInstructions");
        $this->db->from('tbl_market_offers_responces');
        $this->db->join('tbl_market_offer','tbl_market_offer.id = tbl_market_offers_responces.offer_id');
        $this->db->join('tbl_users','tbl_users.id = tbl_market_offers_responces.lender_id');
        $this->db->where('tbl_market_offer.currency_type',$currencyType);
		//$this->db->where('tbl_market_offer.is_archieve !=','y');
        $this->db->where('tbl_market_offers_responces.borrower_id',$user_id);
        $this->db->where('tbl_users.status','y');
		$this->db->where('tbl_market_offers_responces.is_accepted','to');
	    $result = $this->db->get();
		return $result->result_array();
	}
	function timedOutOfferOrderForBorrower($user_id,$currencyType)
	{
		$this->db->select("tbl_market_offers_responces.amount_demand as amount, tbl_market_offers_responces.int_rate as int_rate , tbl_market_offer.term, tbl_market_offers_responces.borrower_id as borrowerId,tbl_users.company_name as borrowerCompany,tbl_market_offers_responces.deniedDate, tbl_market_offers_responces.is_accepted as request_status,tbl_users.CHFInstructions,tbl_users.EURInstructions,tbl_users.USDInstructions");
        $this->db->from('tbl_market_offers_responces');
        $this->db->join('tbl_market_offer','tbl_market_offer.id = tbl_market_offers_responces.offer_id');
        $this->db->join('tbl_users','tbl_users.id = tbl_market_offers_responces.lender_id');
        $this->db->where('tbl_market_offer.currency_type',$currencyType);
		$this->db->where('tbl_market_offer.is_archieve !=','y');
        $this->db->where('tbl_market_offers_responces.borrower_id',$user_id);
        $this->db->where('tbl_users.status','y');
		$this->db->where('tbl_market_offers_responces.is_accepted','to');
	    $result = $this->db->get();
		return $result->result_array();
	}
	
	function allTimedOutOfferOrderForLenderForDates($user_id,$currencyType,$startDate,$endDate)
	{
		$this->db->select("tbl_market_offers_responces.id as offerResponseId,tbl_market_offers_responces.value_date, tbl_market_offers_responces.maturity_date, tbl_market_offers_responces.amount_demand as amount, tbl_market_offers_responces.int_rate as int_rate , tbl_market_offer.term, tbl_market_offer.lender_id as lenderId,tbl_users.company_name as lenderCompany,tbl_market_offers_responces.deniedDate, tbl_market_offers_responces.is_accepted as request_status");
        $this->db->from('tbl_market_offers_responces');
		$this->db->join('tbl_market_offer','tbl_market_offer.id = tbl_market_offers_responces.offer_id');
        $this->db->join('tbl_users','tbl_users.id = tbl_market_offers_responces.borrower_id');
		$this->db->where('tbl_market_offers_responces.lender_id',$user_id);
		$this->db->where('tbl_market_offer.currency_type',$currencyType);
		$this->db->where('tbl_market_offers_responces.deniedDate >=',$startDate);
		$this->db->where('tbl_market_offers_responces.deniedDate <=',$endDate);
		$this->db->where('tbl_users.status','y');
		$this->db->where('tbl_market_offers_responces.is_accepted','to');
		$result = $this->db->get();
		return $result->result_array();
	}
	function allTimedOutOfferOrderForLender($user_id,$currencyType)
	{
		$this->db->select("tbl_market_offers_responces.id as offerResponseId,tbl_market_offers_responces.value_date, tbl_market_offers_responces.maturity_date, tbl_market_offers_responces.amount_demand as amount, tbl_market_offers_responces.int_rate as int_rate , tbl_market_offer.term, tbl_market_offer.lender_id as lenderId,tbl_users.company_name as lenderCompany,tbl_market_offers_responces.deniedDate, tbl_market_offers_responces.is_accepted as request_status");
        $this->db->from('tbl_market_offers_responces');
		$this->db->join('tbl_market_offer','tbl_market_offer.id = tbl_market_offers_responces.offer_id');
        $this->db->join('tbl_users','tbl_users.id = tbl_market_offers_responces.borrower_id');
		$this->db->where('tbl_market_offers_responces.lender_id',$user_id);
		  $this->db->where('tbl_market_offer.currency_type',$currencyType);
		  //$this->db->where('tbl_market_offer.is_archieve !=','y');
		  $this->db->where('tbl_users.status','y');
		  $this->db->where('tbl_market_offers_responces.is_accepted','to');
		$result = $this->db->get();

          return $result->result_array();
		  
	}
	function timedOutOfferOrderForLender($user_id,$currencyType)
	{
		$this->db->select("tbl_market_offers_responces.id as offerResponseId, tbl_market_offers_responces.amount_demand as amount, tbl_market_offers_responces.int_rate as int_rate , tbl_market_offer.term, tbl_market_offer.lender_id as lenderId,tbl_users.company_name as lenderCompany,tbl_market_offers_responces.deniedDate, tbl_market_offers_responces.is_accepted as request_status");
        $this->db->from('tbl_market_offers_responces');
		$this->db->join('tbl_market_offer','tbl_market_offer.id = tbl_market_offers_responces.offer_id');
        $this->db->join('tbl_users','tbl_users.id = tbl_market_offers_responces.borrower_id');
		$this->db->where('tbl_market_offers_responces.lender_id',$user_id);
		  $this->db->where('tbl_market_offer.currency_type',$currencyType);
		  $this->db->where('tbl_market_offer.is_archieve !=','y');
		  $this->db->where('tbl_users.status','y');
		  $this->db->where('tbl_market_offers_responces.is_accepted','to');
		$result = $this->db->get();

          return $result->result_array();
		  
	}
	
	function updateTblMarketOffer($updateAmountArray , $requestId)
	{
		$this->db->where("id", $requestId);
		$this->db->update("tbl_market_offer",$updateAmountArray);
	}
	function getTotalAmountInofferTbl($offerId)
	{
		$this->db->select("amount");
		$this->db->where("id",$offerId);
		$this->db->from("tbl_market_offer");
		$result = $this->db->get();
		return $result->row_array();
	}
	function getAmountOfResponce($responseId)
	{
		$this->db->select("amount_demand");
		$this->db->where("id",$responseId);
		$this->db->where("is_accepted",'n');
		$this->db->from("tbl_market_offers_responces");
		$result = $this->db->get();
		return $result->row_array();
	}
	function responseTimeOutOffer($timeOutArray,$responseId)
	{
		$this->db->where("id", $responseId);
		$this->db->update("tbl_market_offers_responces",$timeOutArray);
	}
	function getOfferIdOfResponse($offerResponseId)
	{
		$this->db->select("offer_id,amount_demand");
		$this->db->where("id",$offerResponseId);
		$this->db->where("is_accepted",'n');
		$this->db->from("tbl_market_offers_responces");
		$result = $this->db->get();
		return $result->row_array();
	}
	
	function responseForBidPdf($tbl_market_loan_accepted)

   {
	   $this->db->select("tbl_market_loan_accepted.* , tbl_users.company_name as lenderCompany ,tbl_users.street as lenderStreet ,tbl_users.zip_code as lenderZipCode ,tbl_users.city as lenderCity ,tbl_users.country as lenderCountry , tbl_users.iban_number as lenderIban,tbl_users.alternate_email as lenderAlternativeMail, tbl_users.beneficiary_name as lenderBenificiaryName, tbl_users.fname as lenderFname, tbl_users.lname as lenderLname,

	   tbl_users.bank_account as lenderAccountNo ,tbl_users.email as lenderEmail,tbl_users.CHFInstructions as LenderCHFInstructions,tbl_users.EURInstructions as LenderEURInstructions,tbl_users.USDInstructions as LenderUSDInstructions");

	   $this->db->from("tbl_market_loan_accepted");

	   $this->db->join("tbl_users","tbl_users.id = tbl_market_loan_accepted.lender_id");

	   $this->db->where('tbl_market_loan_accepted.id',$tbl_market_loan_accepted);

	   $this->db->where('tbl_market_loan_accepted.accepted_by',"deal");

       $result = $this->db->get();

       return $result->result_array();

   }
   function getRequestDetail($requestId)
   {
	   $this->db->select("tbl_users.company_name as borrowerCompany ,tbl_users.street as borrowerStreet ,tbl_users.zip_code as borrowerZipCode ,tbl_users.city as borrowerCity ,tbl_users.country as borrowerCountry , tbl_users.iban_number as borrowerIban,tbl_users.alternate_email as borrowerAlternativeMail, tbl_users.beneficiary_name as borrowerBenificiaryName, tbl_users.fname as borrowerFname, tbl_users.lname as borrowerLname,

	   tbl_users.bank_account as borrowerAccountNo ,tbl_users.email as borrowerEmail,tbl_market_request.maturity,tbl_market_request.currency as currrencyText,tbl_users.CHFInstructions as borrowerCHFInstructions,tbl_users.EURInstructions as borrowerEURInstructions,tbl_users.USDInstructions as borrowerUSDInstructions");
		
	   $this->db->from("tbl_market_request");
	   $this->db->join("tbl_users","tbl_users.id = tbl_market_request.borrower_id");
	   $this->db->where("tbl_market_request.id",$requestId);
	   $result = $this->db->get();

       return $result->result_array();
   }
   function getRequestIdOfResponse($response_id)
   {
	   $this->db->select("request_id");
	   $this->db->from("tbl_market_lender_response");
	   $this->db->where("id",$response_id);
	   $result  = $this->db->get();
	   return $result->row_array();
   }
   function amountRequestedByBorrowerInOffer($offerResponseId)
   {
	   $this->db->select("amount_updated as amount_demand,offer_id");
	   $this->db->where("id",$offerResponseId);
	   $this->db->from("tbl_market_offers_responces");
	   $result = $this->db->get();
	   return $result->row_array();
   }
   function getTotalAmountOfferTable($offerId)
   {
	   $this->db->select("amount");
	   $this->db->where("id",$offerId);
	   $this->db->from(" tbl_market_offer");
	   $result = $this->db->get();
	   return $result->row_array();
   }
  
   function getOpenedResponseCount($requestId)
   {
	   $this->db->select("count(id) as totalRows");
	   $this->db->where("request_id",$requestId);
	   $this->db->where("status","open");
	   $this->db->from("tbl_market_lender_response");
	   $result = $this->db->get();
	   return $result->row_array();
   }
   function getMarityDatesOfResponse($responceId)
   {
	   $this->db->select("value_date,maturity_date");
	   $this->db->where("id",$responceId);
	   $this->db->from("tbl_market_lender_response");
	   $result = $this->db->get();
	   return $result->row_array();
   }
   function getMaturitiesOfResponse($responseId)
   {
	   $this->db->select("value_date,maturity_date");
	   $this->db->where("id",$responseId);
	   $this->db->from("tbl_market_offers_responces");
	   $result = $this->db->get();
	   return $result->row_array();
   }
   
   
   
   function getAllValuesOfMoneyMorket()
   {
       $this->db->select("buttons_id");
       $this->db->from("tbl_money_market");
       $this->db->group_by("buttons_id");
       $result = $this->db->get();
       return $result->result_array();
   }
   function getValueOfMarketMoney($i)
   {
       $this->db->select("value");
       $this->db->from("tbl_money_market");
       $this->db->where("buttons_id",$i);
       $result = $this->db->get();
       return $result->result_array();
   }
    function allRequestsTopButtons($user_id,$currencyType,$buttonsId)

    {

          $this->db->select('count(tbl_market_request.id) as totalRequests');
          $this->db->from('tbl_market_request');
          $this->db->join('tbl_users','tbl_users.id = tbl_market_request.borrower_id');

          $this->db->join('tbl_money_market','tbl_money_market.value = tbl_market_request.term');

          $this->db->where('tbl_market_request.is_accepted','n');
          $this->db->where('tbl_market_request.status','open');
          $this->db->where('tbl_users.status','y');
	  $this->db->where('tbl_market_request.currency_type',$currencyType);
	  $this->db->where('tbl_market_request.amount !=','0');
	  $this->db->where('tbl_market_request.is_archieve','n');
          $this->db->where('tbl_market_request.is_archieve','n');
          
          $this->db->where("tbl_money_market.buttons_id",$buttonsId);
          $this->db->group_by('tbl_money_market.buttons_id');
          $result = $this->db->get();
        //  echo $this->db->last_query();
          return $result->row_array();

    }
    function alloffersTopButtons($user_id,$currencyType,$buttonsId)

    {

          $this->db->select('count(tbl_market_offer.id) as totalRequests');
          $this->db->from('tbl_market_offer');
          $this->db->join('tbl_users','tbl_users.id = tbl_market_offer.lender_id');

          $this->db->join('tbl_money_market','tbl_money_market.value = tbl_market_offer.term');

          $this->db->where('tbl_market_offer.is_accepted','n');
          $this->db->where('tbl_market_offer.status','open');
          $this->db->where('tbl_users.status','y');
    $this->db->where('tbl_market_offer.currency_type',$currencyType);
    $this->db->where('tbl_market_offer.amount !=','0');
    $this->db->where('tbl_market_offer.is_archieve','n');
      
          
          $this->db->where("tbl_money_market.buttons_id",$buttonsId);
          $this->db->group_by('tbl_money_market.buttons_id');
          $result = $this->db->get();
         echo $this->db->last_query();
          return $result->row_array();

    }
    function getAllMarketTabsByTabId($tabId)
    {
        $this->db->select("value,id");
        $this->db->where("buttons_id",$tabId);
        $this->db->from('tbl_money_market');
        $this->db->order_by("by_order","asc");
        $result = $this->db->get();
        return $result->result_array();
    }
    function getTotalRequestsBySubTabs($currencyType,$subTabName)
    {
        //echo $currencyType."...".$subTabName;
        $this->db->select('count(tbl_market_request.id) as totalRequests');
        $this->db->from('tbl_market_request');
        $this->db->join('tbl_users','tbl_users.id = tbl_market_request.borrower_id');
        $this->db->where('tbl_market_request.is_accepted','n');
        $this->db->where('tbl_market_request.status','open');
        $this->db->where('tbl_users.status','y');
        $this->db->where('tbl_market_request.currency_type',$currencyType);
	      $this->db->where('tbl_market_request.amount !=','0');
	      $this->db->where('tbl_market_request.is_archieve','n');
        $this->db->where('tbl_market_request.is_archieve','n');
        $this->db->where("tbl_market_request.term",$subTabName);
        $result = $this->db->get();
       //echo $this->db->last_query();
       
        return $result->row_array();
    }
    function getTotalOfferBySubTabs($currencyType,$subTabName)
    {
        //echo $currencyType."...".$subTabName;
        $this->db->select('count(tbl_market_offer.id) as totaloffers');
        $this->db->from('tbl_market_offer');
        $this->db->join('tbl_users','tbl_users.id = tbl_market_offer.lender_id');
        $this->db->where('tbl_market_offer.is_acepted_fully','n');
        $this->db->where('tbl_market_offer.status','open');
        $this->db->where('tbl_users.status','y');
        $this->db->where('tbl_market_offer.currency_type',$currencyType);
        $this->db->where('tbl_market_offer.amount !=','0');
        $this->db->where('tbl_market_offer.is_archieve','n');
        
        $this->db->where("tbl_market_offer.term",$subTabName);
        $result = $this->db->get();
     // echo $this->db->last_query();
      //  die;
        return $result->row_array();
    }
    function getMaketSubTabName($subTabId)
    {
        $this->db->select("value,showDatesManual");
        $this->db->where("id",$subTabId);
        $this->db->from('tbl_money_market');
        $result = $this->db->get();
        return $result->row_array();
    }
    function getManualDates($subTabId)
    {
        $this->db->select("start_date,value_date,maturity_date");
        $this->db->where("id",$subTabId);
        $this->db->from('tbl_money_market');
        $result = $this->db->get();
        return $result->row_array();
    }
    function getManualDatesEur($subTabId)
    {
        $this->db->select("eur_start_date,eur_value_date,eur_maturity_date");
        $this->db->where("id",$subTabId);
        $this->db->from('tbl_money_market');
        $result = $this->db->get();
        return $result->row_array();
    }
    function getManualDatesUsd($subTabId)
    {
        $this->db->select("usd_start_date,usd_value_date,usd_maturity_date");
        $this->db->where("id",$subTabId);
        $this->db->from('tbl_money_market');
        $result = $this->db->get();
        return $result->row_array();
    }
    function allMarketRequests($user_id,$currencyType,$subTabName,$subTabId)
    {

          $this->db->select('tbl_market_request.*,tbl_users.company_name, tbl_users.city , tbl_market_request.is_bestOffer as `showBestOffr`');
          $this->db->from('tbl_market_request');
          $this->db->join('tbl_users','tbl_users.id = tbl_market_request.borrower_id');
          //$this->db->join('tbl_market_lender_response','tbl_market_lender_response.request_id = tbl_market_request.id','left');
          $this->db->where('tbl_market_request.is_accepted','n');
          $this->db->where('tbl_market_request.status','open');
          $this->db->where('tbl_market_request.term',$subTabName);
          $this->db->where('tbl_users.status','y');
	  $this->db->where('tbl_market_request.currency_type',$currencyType);
	  $this->db->where('tbl_market_request.amount !=','0');
	  $this->db->where('tbl_market_request.is_archieve','n');
          
          if($subTabId != "15")
          {
              $this->db->order_by('tbl_market_request.min_bid',"desc");
          }
          else
          {
             $this->db->order_by('tbl_market_request.numberOfDays',"asc"); 
          }
          

          $this->db->group_by('tbl_market_request.id');
          $result = $this->db->get();
          //echo $this->db->last_query();
          //die;
          return $result->result_array();
    }
    
    
    public function getAllOffersShow($subTabName, $currType,$subTabId)
    {

	 $this->db->select('tbl_market_offer.*,tbl_users.company_name');
         $this->db->from("tbl_market_offer");
         $this->db->join("tbl_users" , "tbl_users.id = tbl_market_offer.lender_id");
	 $this->db->where('tbl_market_offer.currency_type',$currType);
         $this->db->where('tbl_market_offer.term',$subTabName);
         $this->db->where('tbl_market_offer.status',"open");
	 $this->db->where('tbl_market_offer.is_archieve','n');
	  $this->db->where('tbl_users.status',"y");
         $this->db->where('tbl_market_offer.amount != ',"0 mio");
         if($subTabId != "15")
         {
              $this->db->order_by("tbl_market_offer.offer_rate","asc");
              //$this->db->limit(3);
         }
        else
        {
            $this->db->order_by("tbl_market_offer.numberOfDays","asc");
            //$this->db->limit(3);
        }
        
	 $result = $this->db->get();
         return $result->result_array();

      }
    
}
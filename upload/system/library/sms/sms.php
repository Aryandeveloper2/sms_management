<?php
/*
  $Id: sms.php $
   opncart Open Source Shopping Cart Solutions
  http://www.opencart-ir.com
  version:3.8
*/
//require_once(DIR_SYSTEM.'library/nuSoap/nusoap.php');   


 final class Sms {
      private     $to;
      private   $body;
      private $username ;
	  private  $sample;
      private  $password;
      private  $from;
	  private $sms_getway;
      public $flash = false;
 
    function send_sms($to_mobile_number = null,$sms_text = '',$username = '',$password = '',$sms_getway = '',$from = '',$voice=null) {

//echo $to_mobile_number."  -<br>  ".$sms_text."  _<br>  ".$username."  =<br>  ".$password."  |<br>  ".$sms_getway."  *<br>  ".$from."  /<br>  ".$voice;
      if ( (!empty($to_mobile_number)) && 11 <= strlen( $to_mobile_number ) && substr($to_mobile_number,0,2)=="09" ||  substr($to_mobile_number,0,4)=="+989" ) {
         if($voice){
		 
		    return $this->sendvoice($to_mobile_number,$sms_text,$username,$password,$sms_getway,$from);
		 }else {
		
		    return $this->send($to_mobile_number,$sms_text,$username,$password,$sms_getway,$from);
		}
		
		 }
    }

    function send($to,$body,$username,$password,$sms_getway,$from) {
    	$this->password=$password;
    	$this->from=$from;
    	$this->username=$username;	
    	$this->to=$to;
    	$this->body=$body;
    	$this->sms_getway=$sms_getway;
    	
    	ini_set("soap.wsdl_cache_enabled",0);		
    	$client = new SoapClient("http://api.payamak-panel.com/post/Send.asmx?wsdl",array("encoding"=>"UTF-8"));
    
    	$parameters['username'] =$this->username;
    	$parameters['password'] = $this->password;
    	
    	$parameters['to'] =array($this->to);
    	$parameters['from'] = $this->from;
    	
    	$parameters['text'] =$this->body;
    	$parameters['isflash'] =false;
    	
        $parameters['udh'] = "";
        $parameters['recId'] = array(0);
        $parameters['status'] = 0x0;
    
        $result = $client->SendSms($parameters)->SendSmsResult;

    	return $result;
	}
   

    function sendvoice($to,$body,$username,$password,$sms_getway,$from) {
        
        $this->username=$username;	
        $this->password=$password;
        
        $this->to=$to;
        $this->from=$from;
        $this->body=$body;
        $this->sms_getway=$sms_getway;
        
        $sms_client = new SoapClient('http://api.payamak-panel.com/post/voice.asmx?wsdl', array('encoding'=>'UTF-8'));		
        $parameters['username'] = $this->username;
        $parameters['password'] = $this->password;
        $parameters['smsBody'] =$this->body;
        $parameters['speechBody'] =$this->body;
        $parameters['from'] =  $this->from;
        $parameters['to'] = $this->to;
        
        
        $result =  $sms_client->SendSMSWithSpeechText($parameters)->SendSMSWithSpeechTextResult;
        return     $result;
	}

	
	public function getSharedServiceBody($username,$password) {

        $client = new SoapClient("https://api.payamak-panel.com/post/SharedService.asmx?wsdl",array("encoding"=>"UTF-8"));
        $data = array(
        	"username" => $username,
        	"password" => $password,
        	
        );
        
        $Result= $client->GetSharedServiceBody($data)->GetSharedServiceBodyResult;

        return $Result;
	}
	
	public function sharedServiceBodyAdd($username,$password, $title, $body) {

        $client = new SoapClient("https://api.payamak-panel.com/post/SharedService.asmx?wsdl",array("encoding"=>"UTF-8"));
        $data = array(
        	"username" => $username,
        	"password" => $password,
        	"title" => $title,
        	"body" => $body,
        	"blackListId" => 1
        );
        $result = $client->SharedServiceBodyAdd($data)->SharedServiceBodyAddResult;
        return $result;
	}
	
	public function sharedServiceBodyEdit($username,$password, $bodyId,  $body) {

        $client = new SoapClient("https://api.payamak-panel.com/post/SharedService.asmx?wsdl",array("encoding"=>"UTF-8"));
        $data = array(
        	"username" => $username,
        	"password" => $password,
        	"bodyId" => $bodyId,
        	"body" => $body
        );
        $result = $client->SharedServiceBodyEdit($data)->SharedServiceBodyEditResult;
        return $result;
	}
	
	public function sendSmsPattern($data) {
        $sms = new SoapClient("http://api.payamak-panel.com/post/Send.asmx?wsdl", array("encoding"=>"UTF-8"));
   
        $send_Result = $sms->SendByBaseNumber([
            "username"=>$data['username'],
            "password"=>$data['password'],
            "text"=>$data['text'],
            "to"=>$data['to'],
            "bodyId"=>$data['bodyId']    
        ])->SendByBaseNumberResult;
        return $send_Result;
	}


	public function setsms( $txtmsg, $shop = "", $url = "", $username = "", $password = "", $orderid = "",$status_order="" )
	{
		$txt = str_replace( "#shop#", $shop, $txtmsg );
		$txt = str_replace( "#url#", $url, $txt );
		$txt = str_replace( "#email#", $username, $txt );
		$txt = str_replace( "#pass#", $password, $txt );
		$txt = str_replace( "#ip#", $_SERVER['REMOTE_ADDR'], $txt );
		$txt = str_replace( "#orderid#", $orderid, $txt );
		$txt = str_replace( "#status_order#", $status_order, $txt );
		$txt = str_replace( "#nl#", "\r\n", $txt);
		return $txt;
	}
	
    public function setsmsorder( $txtmsg= '', $shop = "", $url = "", $username = "", $password = "", $orderid = "",$status_order="",$order_tedad="",$pro_name="",$pro_price="",$pro_total="",$pro_rah= '' )
	{
		$txt = str_replace( "#shop#", $shop, $txtmsg );
		$txt = str_replace( "#url#", $url, $txt );
		$txt = str_replace( "#email#", $username, $txt );
		$txt = str_replace( "#pass#", $password, $txt );
		$txt = str_replace( "#ip#", $_SERVER['REMOTE_ADDR'], $txt );
		$txt = str_replace( "#orderid#", $orderid, $txt );
		$txt = str_replace( "#order_tedad#", $order_tedad, $txt );
		$txt = str_replace( "#pro_name#", $pro_name, $txt );
		$txt = str_replace( "#pro_price#", $pro_price, $txt );
		$txt = str_replace( "#pro_total#", $pro_total, $txt );
		$txt = str_replace( "#pro_rah#", $pro_rah, $txt );
		$txt = str_replace( "#status_order#", $status_order, $txt );
		$txt = str_replace( "#nl#", "\r\n", $txt);
		return $txt;
	}
	
	public function setsmspay( $txtmsg, $shop = "",  $telephone = "", $name = "", $amount = "",$bank="" ,$msg="",$rah="" )
	{
		$txt = str_replace( "#shop#", $shop, $txtmsg );
		$txt = str_replace( "#telephone#", $telephone, $txt );
		$txt = str_replace( "#name#", $name, $txt );
		$txt = str_replace( "#price_pay#", $amount, $txt );
		$txt = str_replace( "#bank#", $bank, $txt );
		$txt = str_replace( "#des_pay#", $msg, $txt );
		$txt = str_replace( "#order_rah#", $rah, $txt );
		return $txt;
	}
	
	public function setsmsverify($txtmsg, $verify="" )
	{
		$txt = str_replace( "#verify_code#", $verify,$txtmsg );
		return $txt;
	}

    public function setusername($txtmsg, $username="" )
	{
		$txt = str_replace( "#username#", $username,$txtmsg );
		return $txt;
	}
	
	public function setsmsfish( $txtmsg ="", $shop = "",  $telephone = "", $name = "", $amount = "",$bank="" ,$msg="",$order_id = "" )
	{
		$txt = str_replace( "#shop#", $shop, $txtmsg );
		$txt = str_replace( "#telephone#", $telephone, $txt );
		$txt = str_replace( "#name#", $name, $txt );
		$txt = str_replace( "#price_order#", $amount, $txt );
		$txt = str_replace( "#bank#", $bank, $txt );
		$txt = str_replace( "#des_fish#", $msg, $txt );
		$txt = str_replace( "#order_id#", $order_id, $txt );
		return $txt;
	}
	
	public function setsmsfish_edit( $txtmsg, $fish_id )
	{
		$txt = str_replace( "#shop#", $shop, $txtmsg );
		$txt = str_replace( "#telephone#", $telephone, $txt );
		$txt = str_replace( "#name#", $name, $txt );
		$txt = str_replace( "#price_order#", $amount, $txt );
		$txt = str_replace( "#bank#", $bank, $txt );
		$txt = str_replace( "#des_fish#", $msg, $txt );
		$txt = str_replace( "#order_id#", $order_id, $txt );
		return $txt;
	}
	
	public function setsmsletme($txtmsg, $product_name,$product_url )
	{
		$txt = str_replace( "#product_name#", $product_name, $txtmsg );
		$txt = str_replace( "#productLink#", $product_url, $txt );
		
		return $txt;
	}
	
	public function setsmsnewslater( $txtmsg, $mobile)
	{
		$txt = str_replace( "#mobile#", $mobile, $txtmsg );
	
		return $txt;
	}

	
  }
  
?>

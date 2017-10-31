<?php
defined( 'ABSPATH' ) or die( 'No script kiddies please!' );

class AccountAPI {

    function __construct($username, $appkey, $company_code) {
        $this->username = $username;
        $this->appkey   = $appkey;
        $this->company_code  = $company_code;
		$this->api_url  = "*********************************"; //secret for security
    }

    function filterData($data) {
        return json_decode( str_replace(array('(', ')'), "", $data), true );
    }

    function getCurl($url) {
        $user_agent='Mozilla/5.0 (Windows NT 6.1; rv:8.0) Gecko/20100101 Firefox/8.0';

        $options = array(

            CURLOPT_CUSTOMREQUEST  => "GET",        //set request type post or get
            CURLOPT_POST           => false,        //set to GET
            CURLOPT_USERAGENT      => $user_agent, //set user agent
            CURLOPT_COOKIEFILE     => "cookie.txt", //set cookie file
            CURLOPT_COOKIEJAR      => "cookie.txt", //set cookie jar
            CURLOPT_RETURNTRANSFER => true,     // return web page
            CURLOPT_HEADER         => false,    // don't return headers
            CURLOPT_FOLLOWLOCATION => true,     // follow redirects
            CURLOPT_ENCODING       => "",       // handle all encodings
            CURLOPT_AUTOREFERER    => true,     // set referer on redirect
            CURLOPT_CONNECTTIMEOUT => 120,      // timeout on connect
            CURLOPT_TIMEOUT        => 120,      // timeout on response
            CURLOPT_MAXREDIRS      => 10,       // stop after 10 redirects
        );

        $ch      = curl_init( $url );
        curl_setopt_array( $ch, $options );
        $content = curl_exec( $ch );
        curl_close( $ch );
        return $content;
    }

    function authCheck() {
        //$str = "http://test.accountit.co.il/AccountIT/api.php?action=Login&username=$this->username&appKey=$this->appkey";
        $str = "$this->api_url?action=Login&username=$this->username&appKey=$this->appkey";
        $result = self::getCurl($str);
        return self::filterData($result)['sid'];
    }

    function getData($serial = '9999') {
        $auth_check = self::authCheck();
        $str = "$this->api_url?action=Get&company_code=".$this->company_code."&jsoncallback=jcb&data=Document&num=$serial";

        if( $auth_check == -1 ):
            return;
        endif;

        $result = self::getCurl($str);
        return self::filterData($result);
    }

    function putData($data) {
        $auth_check = self::authCheck();
        $pre_str = "$this->api_url?action=New&company_code=".$this->company_code."&jsoncallback=jcb&data=Document&account=113&";
        $str = http_build_query($data);
        $result = self::getCurl($pre_str.$str);
        return self::filterData($result);
    }

    function __destruct() {
        $str = "$this->api_url?action=Logout";
        self::getCurl($str);
    }
}

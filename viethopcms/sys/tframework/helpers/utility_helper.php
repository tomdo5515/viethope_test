<?php

function assets_folder(){

    $_ci =& get_instance();
    $_ci->load->config('twig');
    $_config = $_ci->config->item('twig');
    return base_url() . 'themes/' . $_config['default_theme'];
    
}

function assets_backend_folder(){

    $_ci =& get_instance();
    $_ci->load->config('twig');
    $_config = $_ci->config->item('twig');
    return base_url() . 'themes/' . $_config['default_backend_theme'];
}

/**
 * Current Module
 *
 * Just a fancier way of getting the current module
 * if we have support for modules
 *
 * @access public
 * @return string
 */
function current_menu($i=NULL)
{
    if(NULL!=$i)
    {
        // Modular Separation / Modular Extensions has been detected
        $segment = get_instance()->uri->segment($i);
        if($segment=='' || $segment==FALSE)
        {
            return 'trang-chu';
        }
    }else {
        $segment = get_instance()->uri->segment_array();
    }
    return $segment;
}

// Method: POST, PUT, GET etc
// Data: array("param" => "value") ==> index.php?param=value
function CallAPI_BASICAUTH($url, $user='', $pass='')
{
    $user = ($user == '') ? 'fortest' : $user;
    $pass = ($pass == '') ? 'sa4442332&%@)5' : $pass;
    
    try {
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_COOKIESESSION,true);
        curl_setopt($curl, CURLOPT_HEADER, false);
        curl_setopt($curl, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows; U; Windows NT 6.0; en-US; rv:1.9.0.3) Gecko/2008092417 Firefox/3.0.4");
        curl_setopt($curl, CURLOPT_TIMEOUT, 15);
        curl_setopt($curl, CURLOPT_ENCODING, "gzip");
        curl_setopt($curl, CURLOPT_HTTPHEADER, array('Accept-Language: vi-VN,vi;q=0.8,en-US;q=0.5,en;q=0.3'));
        // Optional Authentication:
        curl_setopt($curl, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
        curl_setopt($curl, CURLOPT_USERPWD, $user.':'.$pass);
        
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);

        $result = curl_exec($curl);
        
        curl_close($curl);
    } catch (Exception $e) {
        $result = $e->getMessage();
    }
    return $result;
}


// Method: POST, PUT, GET etc
// Data: array("param" => "value") ==> index.php?param=value
function Call_API_AUTH($url, $token , $data = false)
{
    $result = '';
    try {
        $curl = curl_init();
        $method = 'GET';
        switch ($method)
        {
            case "POST":
                curl_setopt($curl, CURLOPT_POST, 1);
                if ($data)
                    curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
                break;
            case "PUT":
                curl_setopt($curl, CURLOPT_PUT, 1);
                break;
            default:
                if ($data)
                    $url = sprintf("%s?%s", $url, http_build_query($data));
        }
        curl_setopt($curl, CURLOPT_COOKIESESSION,true);
        curl_setopt($curl, CURLOPT_HEADER, false);
        curl_setopt($curl, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows; U; Windows NT 6.0; en-US; rv:1.9.0.3) Gecko/2008092417 Firefox/3.0.4");
        curl_setopt($curl, CURLOPT_TIMEOUT, 15);
        curl_setopt($curl, CURLOPT_ENCODING, "gzip");
        curl_setopt($curl, CURLOPT_HTTPHEADER, array("Authorization:JWT $token",'Accept-Language: vi-VN,vi;q=0.8,en-US;q=0.5,en;q=0.3'));
        // Optional Authentication:
        // curl_setopt($curl, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
        // curl_setopt($curl, CURLOPT_USERPWD, "username:password");
        
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);

        $result = curl_exec($curl);
        
        curl_close($curl);
    } catch (Exception $e) {
        $result = $e->getMessage();
    }
    return $result;
}


function filterLink($Content) 
{
    // $_regex = @'<a[^>]* href="([^"]*)"([^>]*)>|<\/a>|@(?:[\w-]+\.)+[\w-]+';// @'href="[^"]*[^>]*|@(?:[\w-]+\.)+[\w-]+'; //@'<a[^>]* href="([^"]*)"([^>]*)>|<\/a>';
    
    // return preg_replace('/'.$_regex.'/', "", $Content); 
    // 
    // step 1 remove inline style
    $_regex = @'(<[a-z ]*)(style=("|\')(.*?)("|\'))([a-z ]*>)';
    $Content = preg_replace('/'.$_regex.'/', '\\1\\6', $Content);
    // step 2 remove html tag
    return strip_tags($Content, '<p><div><iframe><h1><h2><h3><h4><h5><h6><strong><style><img>');
}

function filterLinkKeepTag($Content)
{
    $_regex = @'href="[^"]*[^>]*|@(?:[\w-]+\.)+[\w-]+'; //@'<a[^>]* href="([^"]*)"([^>]*)>|<\/a>';
    
    return preg_replace('/'.$_regex.'/', "", $Content); 
}

///
//Get inner HTML in a node
//Return string
///
function DOMinnerHTML(DOMNode $element) 
{ 
    $innerHTML = ""; 
    $children  = $element->childNodes;

    foreach ($children as $child) 
    { 
        $innerHTML .= $element->ownerDocument->saveHTML($child);
    }

    return $innerHTML; 
}

//if input date is UTC
function getTimeGMT0($date='')
{
    $result = 0;
    if ($date=='') {
        $_date = DateTime::createFromFormat('Y-m-d\TH:i:s\Z', gmdate("Y-m-d\TH:i:s\Z"), new DateTimeZone('UTC'));
        $result = $_date->getTimestamp();
    }else
    {
        $_date = DateTime::createFromFormat('Y-m-d\TH:i:s\Z', $date, new DateTimeZone('UTC'));
        $result = $_date->getTimestamp();
    }
    return $result;
}

//if input time is UTC
function getTimeGMT7($time='')
{
    $result = 0;
    if ($time=='') {
        $result = getTimeGMT0() + 25200; //GMT 0 + 7 h
    }else
    {
        $result = $time + 25200; //GMT 0 + 7 h
    }
    return $result;
}

//elapsed following timezone indochina +7
function time_elapsed_string($ptime)
{
    $etime = GMT0toGMT7() - GMT0toGMT7($ptime);
    if ($etime < 1)
    {
        return '0 seconds';
    }

    $a = array( 365 * 24 * 60 * 60  =>  'year',
                 30 * 24 * 60 * 60  =>  'month',
                      24 * 60 * 60  =>  'day',
                           60 * 60  =>  'hour',
                                60  =>  'minute',
                                 1  =>  'second'
                );
    $a_plural = array( 'year'   => @'năm',
                       'month'  => @'tháng',
                       'day'    => @'ngày',
                       'hour'   => @'giờ',
                       'minute' => @'phút',
                       'second' => @'giây'
                );
    foreach ($a as $secs => $str)
    {
        $d = $etime / $secs;
        if ($d >= 1)
        {
            $r = round($d);
            return $r . ' ' . ($r > 1 ? $a_plural[$str] : $str) . @' trước';
        }
    }
}


function slugify($string)
{
    
    $a=array('à','á','ạ','ả','ã','â','ầ','ấ','ậ','ẩ','ẫ','ă','ằ','ắ','ặ','ẳ','ẵ',
            'è','é','ẹ','ẻ','ẽ','ê','ề','ế','ệ','ể','ễ',
            'ì','í','ị','ỉ','ĩ',
            'ò','ó','ọ','ỏ','õ','ô','ồ','ố','ộ','ổ','ỗ','ơ','ờ','ớ','ợ','ở','ỡ',
            'ù','ú','ụ','ủ','ũ','ư','ừ','ứ','ự','ử','ữ',
            'ỳ','ý','ỵ','ỷ','ỹ',
            'đ',
            'À','Á','Ạ','Ả','Ã','Â','Ầ','Ấ','Ậ','Ẩ','Ẫ','Ă','Ằ','Ắ','Ặ','Ẳ','Ẵ',
            'È','É','Ẹ','Ẻ','Ẽ','Ê','Ề','Ế','Ệ','Ể','Ễ',
            'Ì','Í','Ị','Ỉ','Ĩ',
            'Ò','Ó','Ọ','Ỏ','Õ','Ô','Ồ','Ố','Ộ','Ổ','Ỗ','Ơ','Ờ','Ớ','Ợ','Ở','Ỡ',
            'Ù','Ú','Ụ','Ủ','Ũ','Ư','Ừ','Ứ','Ự','Ử','Ữ',
            'Ỳ','Ý','Ỵ','Ỷ','Ỹ',
            'Đ');
    $b=array('a','a','a','a','a','a','a','a','a','a','a','a','a','a','a','a','a',
            'e','e','e','e','e','e','e','e','e','e','e',
            'i','i','i','i','i',
            'o','o','o','o','o','o','o','o','o','o','o','o','o','o','o','o','o',
            'u','u','u','u','u','u','u','u','u','u','u',
            'y','y','y','y','y',
            'd',
            'A','A','A','A','A','A','A','A','A','A','A','A','A','A','A','A','A',
            'E','E','E','E','E','E','E','E','E','E','E',
            'I','I','I','I','I',
            'O','O','O','O','O','O','O','O','O','O','O','O','O','O','O','O','O',
            'U','U','U','U','U','U','U','U','U','U','U',
            'Y','Y','Y','Y','Y',
            'D');
    $string = strtolower(str_replace($a, $b, $string)); 

    return preg_replace('/[^A-Za-z0-9]+/', '-', $string);
    
}



function getDescription($content, $limit=25)
{
    if(IsNullOrEmpty($content)){
       return ''; 
    }
    $content = strip_tags($content);
    if (str_word_count($content, 0) > $limit) {
        $words = str_word_count($content, 2);
        $pos = array_keys($words);
        $content = substr($content, 0, $pos[$limit]) . '...';
    }
    return $content;
}


// Check

function isPost()
{
    return ($_SERVER['REQUEST_METHOD'] === 'POST');
}

function isGet()
{
    return ($_SERVER['REQUEST_METHOD'] === 'GET');
}

function isMobile()
{
    return get_instance()->agent->is_mobile();
}

/*
check string is null or empty
*/ 
function IsNullOrEmpty($input){
    return (!isset($input) || trim($input)==='');
}

function debug($input){
    if(in_array($_SERVER['REMOTE_ADDR'], [
        '2405:4800:529f:5ec5:a4da:bae3:63b7:a487',
        '127.0.0.1'
        ]))
    {
        var_dump($input);
        die();
    }
}

function MD5HASH($input){
    $input = strtolower($input);
    return md5($input);
}

function shortens($text, $limit){
    //Set Up
    $array = [];
    $count = -1;
    $text = preg_replace(array('/\s{2,}/', '/[\t\n]/'), ' ', $text);
    $text = rtrim($text);
    //Turning String into an Array
    $split_text = explode(" ", $text);
    //Loop for the length of words you want
    $num_words = sizeof($split_text);
    if($num_words < $limit)
        $limit = $num_words;

    while($count < $limit - 1){
      $count++;
      $array[] = $split_text[$count];
    }
    //Converting Array back into a String
    $text = implode(" ", $array);

    return $text."...";

}
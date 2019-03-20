<?php

// Method: POST, PUT, GET etc
// Data: array("param" => "value") ==> index.php?param=value
function Call_API($url, $data = false)
{
    $result['errorcode'] = 0;
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
        
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);

        $result['result'] = curl_exec($curl);
        
        curl_close($curl);
    } catch (Exception $e) {
        $result['errorcode'] = 100;
        $result['errormessage'] = $e->getMessage();
    }

    return $result;
}
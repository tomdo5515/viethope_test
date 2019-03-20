<?php defined('BASEPATH') OR exit('No direct script access allowed');

//Thay đổi 3 thông tin ở phía dưới
$config['NGANLUONG']['MERCHANT_ID'] = "47684";

$config['NGANLUONG']['MERCHANT_PASSWORD'] = "edc0db668edc9745e4ec39ebbb797e07";

$config['NGANLUONG']['EMAIL_RECEIVE_MONEY'] = "andreajasson1@gmail.com";


$config['NGANLUONG']['NGANLUONG_URL_CARD_POST'] = 'https://www.nganluong.vn/mobile_card.api.post.v2.php';

$config['NGANLUONG']['NGANLUONG_URL_CARD_SOAP'] = 'https://nganluong.vn/mobile_card_api.php?wsdl';
	
$config['NGANLUONG']['FUNCTION'] = "CardCharge";

$config['NGANLUONG']['VERSION'] = "2.0";
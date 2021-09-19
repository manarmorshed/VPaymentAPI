<?php
require_once ("controller/Api/ValidateCustomerPayment.php");


/*
 * end url:
 localhost/paymentapi/Api/ValidateCustomerPayment
 --------------- test mobile-------------
//$type=2;
ex.json mobile
-----------------json------------
 {
    "mobile":"01289683787"
 }

ex.xml mobile
---------------xml------------
<?xml version="1.0" encoding="utf-8"?>
<request>
<mobile>01289683787</mobile>
</request>
 -------------test credite-------------
//$type=1;
ex.json credit
-----------------json------------
{
"Creditnumber":"4111111111111111",
"date":"1221",
"cvv":"2553",
"email":"xxx@xx.com"
}
---------------xml------------
ex. xml credit
<?xml version="1.0" encoding="utf-8"?>
<request>
<Creditnumber>400000000000</Creditnumber>
<date>0321</date>
<cvv>2553</cvv>
<email>xxx@xx.com</email>
</request>
*/


$data = file_get_contents("php://input");

$content_type = '';
$request_arr=array();

if(isset($_SERVER['CONTENT_TYPE'])) {
    $content_type = $_SERVER['CONTENT_TYPE'];
}
if($content_type=="application/json")
{
//params sent as json
    $data_params = json_decode($data,true);
    $sendmethod='json';


}elseif ($content_type=="application/xml") {
    //params sent as xml
    $sendmethod = 'xml';
//  xml to object
    $new = simplexml_load_string($data);

// obj into json
    $con = json_encode($new);

// json into  array
    $data_params = json_decode($con, true);
}


if($data_params) {
    foreach ($data_params as $key => $value) {
        if($key=='mobile')  {  $type=2;
        }elseif ($key=='Creditnumber')
        {
            $type=1;
        }
        $request_arr[$key] = $value;
    }
}
   // print_r($data_params);


$result=new ValidateCustomerPayment($type,$request_arr);



//echo('<pre>');print_r($result->response);die;

$response=$result->response;

if($sendmethod=='json')
{
    header('Content-Type:application/json');
    echo(json_encode($response));
}elseif ($sendmethod=='xml')
{
    $ErrorCodelist=$response['ErrorCode'];
    $ErrorCodexml="";
    foreach($ErrorCodelist as $oneErrorCode)
    {
                $ErrorCodexml.= "<error>
        <code>$oneErrorCode[code]</code>
        <desc>$oneErrorCode[desc]</desc>
        
        </error>";
    }

    header('Content-Type: text/xml; charset=utf-8');
    echo <<<EOF
<?xml version="1.0" encoding="utf-8"?>
<Response>
<Valid>$response[Valid]</valid>
<errors>
$ErrorCodexml
</errors>
</Response>
EOF;

}




?>
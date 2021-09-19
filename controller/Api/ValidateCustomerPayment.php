<?php


class ValidateCustomerPayment{

//1-Credit card
//2-Mobile

    public  $type;
    public $response;


    public function __construct($type,$request_arr)
    {
        //bool valid [1,0]
       //list of error codes based on validation
        /*
         *
         //--------sendmethod----
           xml
           json
         //---------type---------
           credite-->1
           mobile-->2

        //----------ErrorCode------
           1-Invalid email format
           2-Credit card Expired
           3-Credit card number is invalid
           5- mobile numb is not valide

        */
        $this->type=$type;
        $result=array();
        if($type==1)
        {
           $result= $this->ValidateCredit($request_arr);
            //bool valid [1,0]


        }elseif ($type==2)
        {
            $result = $this->ValidateMobile($request_arr);
            //bool valid [1,0]



        }



        $this->response=$result;

    }

    public function ValidateCredit($request_arr)
    {
        /*
        //----------ErrorCode------
        //1-Invalid email format
        //2-Credit card Expired
         //3-Credit card number is invalid
        //5- mobile numb is not valide

        */

        $all_result=array();
        $final_result=array();

        $Creditnumber=$request_arr['Creditnumber'];
        $date=$request_arr['date'];
        $cvv=$request_arr['cvv'];
        $email=$request_arr['email'];

        $all_result[]= $this->Validate_CreditNumber($Creditnumber);
        $all_result[]= $this->Validate_ExpirationDate($date);
        $all_result[]= $this->Validate_cvv($cvv);
        $all_result[]= $this->Validate_Email($email);



        $fvalid=1;
        $error_list=array();
        foreach( $all_result as $one_result)
        {
            $valid=$one_result['Valid'];
            $ErrorCode=$one_result['ErrorCode'];
            $fvalid=$fvalid*$valid;
             if($ErrorCode['code']>0) {
                 $error_list[]= $ErrorCode;
                }
        }


        $final_result['Valid']=$fvalid;
        $final_result['ErrorCode']=$error_list;



        return($final_result);

    }



    public function ValidateMobile($request_arr)
    {


        $mobile=$request_arr['mobile'];
        if(isset($mobile)) {
            //should be digit from 11 to 14
            if( preg_match("/^[0-9]{11,14}$/", $mobile))
            {
                //mobile numb is valide
                $result['Valid'] = '1';

                $result['ErrorCode'] = array();

            }else
            {

                //mobile numb is not valide

                $result['Valid'] = '0';

                $result['ErrorCode'][0]['code'] = '5';
                $result['ErrorCode'][0]['desc'] = 'mobile numb is not valide';
            }


        }else
        {
            //mobile numb is not valide

            $result['Valid'] = '0';

            $result['ErrorCode'][0]['code'] = '5';
            $result['ErrorCode'][0]['desc'] = 'mobile numb is not valide';
        }
        return($result);
    }

// Validate Email
    public function Validate_Email($email)
    {
        //ex.  xxx@xx.com
            if (isset($email))
            {
                if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                    //Invalid email format
                    $result['Valid'] = '0';


                    $result['ErrorCode']['code'] = '1';
                    $result['ErrorCode']['desc'] = 'Invalid email format';
                } else {
                    //valid email format
                    $result['Valid'] = '1';


                    $result['ErrorCode']['code'] = '0';
                    $result['ErrorCode']['desc'] = '';
                }

            }else
            {
                $result['Valid'] = '0';

                $result['ErrorCode']['code'] = '1';
                $result['ErrorCode']['desc'] = 'Invalid email format';
            }
        return($result);
    }
//Credit card Expiration date
    public function Validate_ExpirationDate($date)
    {
       //ex. 0620
        if(isset($date))
        {
                    $expires = date_format(\DateTime::createFromFormat('my', $date), "my");

                    $now = date_format(new \DateTime(), "my");

                    if ($expires < $now) {

                        $result['Valid'] = '0';
                        //Credit card Expired

                        $result['ErrorCode']['code'] = '2';
                        $result['ErrorCode']['desc'] = 'Credit card Expired';

                    } else {
                        $result['Valid'] = '1';
                        //Credit card Expired
                        $result['ErrorCode']['code'] = '0';
                        $result['ErrorCode']['desc'] = '';
                         }
        }else
        {
                    $result['Valid'] = '0';
                    //Credit card Expired
                   $result['ErrorCode']['code'] = '2';
                   $result['ErrorCode']['desc'] = 'Credit card Expired';
        }
        return($result);
    }

//Credit card number with Luhn's algorithm

    public function Validate_CreditNumber($number) {
    //ex. 	4111111111111111
        if(isset($number)) {
            // Strip any non-digits (useful for credit card numbers with spaces and hyphens)
            $number = preg_replace('/\D/', '', $number);

            // Set the string length and parity
            $number_length = strlen($number);


            // Loop through each digit and do the maths
            $total = 0;
            for ($i = 0; $i < $number_length; $i++) {
                $digit = $number[$i];
                // Multiply odd digits by two
                if ($i % 2 == 0) {
                    $digit *= 2;
                    // If the sum is two digits (add them ->10=1 or 16-->7)
                    if ($digit > 9) {
                        $digit -= 9;
                    }
                }
                // Total up the digits
                $total += $digit;
            }

            // If the total mod 10 equals 0, the number is valid


            if($total % 10 == 0)
            {
                //Credit card number is valid
                $result['Valid'] = '1';

                $result['ErrorCode']['code'] = '0';
                $result['ErrorCode']['desc'] = '';
            }else
            {
               //Credit card number is invalid
                $result['Valid'] = '0';

                $result['ErrorCode']['code'] = '3';
                $result['ErrorCode']['desc'] = 'Credit card number is invalid';

            }



        }else
        {


            $result['Valid'] = '0';

            $result['ErrorCode']['code'] = '3';
            $result['ErrorCode']['desc'] = 'Credit card number is invalid';
        }

        return($result);

    }

//validate cvv
public function Validate_cvv($cvv)
{

    if(isset($cvv))
    {

        if( preg_match("/^[0-9]{3,4}$/", $cvv))
        {


            //cvv numb is valide
            $result['Valid'] = '1';


            $result['ErrorCode']['code'] = '0';
            $result['ErrorCode']['desc'] = '';
        }else
        {

            //cvv numb is not valide

            $result['Valid'] = '0';
            $result['ErrorCode']['code'] = '4';
            $result['ErrorCode']['desc'] = 'cvv numb is not valide';

        }

    }else{
        //cvv numb is not valide

        $result['Valid'] = '0';

        $result['ErrorCode']['code'] = '4';
        $result['ErrorCode']['desc'] = 'cvv numb is not valide';
    }
    return($result);

}

}
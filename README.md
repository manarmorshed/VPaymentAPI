# VPaymentAPI
rest API to validate payment information 

1-end url:
 localhost/paymentapi/Api/ValidateCustomerPayment
 
 2-Supported payment types(mobile/Credit card )
 3-support data in 2 formats: JSON and XML 

 
4- test mobile

ex.json mobile

 {
    "mobile":"01289683787"
 }

ex.xml mobile

<?xml version="1.0" encoding="utf-8"?>
<request>
<mobile>01289683787</mobile>
</request>


5-test credite

ex.json credit

{
"Creditnumber":"4111111111111111",
"date":"1221",
"cvv":"2553",
"email":"xxx@xx.com"
}


ex. xml credit
<?xml version="1.0" encoding="utf-8"?>
<request>
<Creditnumber>400000000000</Creditnumber>
<date>0321</date>
<cvv>2553</cvv>
<email>xxx@xx.com</email>
</request>


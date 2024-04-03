<?php

namespace App\Http\Controllers\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class KhaltiPaymentController extends Controller
{
    public function pay()
    {
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://a.khalti.com/api/v2/epayment/initiate/',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => '{
        "return_url": "http://localhost/payment-info",
        "website_url": "http://localhost/",
        "amount": "1000",
        "purchase_order_id": "Order01",
            "purchase_order_name": "test",
    
        "customer_info": {
            "name": "Test Bahadur",
            "email": "test@khalti.com",
            "phone": "9800000001"
        }
        }
        ',
            CURLOPT_HTTPHEADER => array(
                'Authorization: key 47f3741d81de478d929ff10213a5a8c0',
                'Content-Type: application/json',
            ),
        ));

        $response = curl_exec($curl);

        curl_close($curl);
        // echo $response;
        return $response;
    }

    public function lookup(Request $request)
    {
        $pidx = (string)$request->pidx;
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://a.khalti.com/api/v2/epayment/lookup/',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => `{
                "pidx": $pidx
            }`,
            CURLOPT_HTTPHEADER => array(
                'Authorization: key 47f3741d81de478d929ff10213a5a8c0',
                'Content-Type: application/json',
            ),
        ));

        $response = curl_exec($curl);

        curl_close($curl);
        // echo $response;
        return $response;
    }
}

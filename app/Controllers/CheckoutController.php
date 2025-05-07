<?php

namespace App\Controllers;
use App\Models\Product;
use App\Models\CustomSession;
use App\Models\Cart;
use Config\Database;
use App\Models\User;
use App\Models\Invoice_Delivery;

class CheckoutController extends BaseController
{
    function index()
    {
        $curr_session = new CustomSession(null);
        if ($curr_session->isSessionSet())
        {
            $curr_cart = new Cart($curr_session->get_id());
            $cart_items = $curr_cart->get();
            if (empty($cart_items)) return redirect() -> to ('/user/cart');
            else 
            {
                $user = new User();
                $info = $user->get_info($curr_session->get_id());
                if($info == false)
                {
                    return view('checkout', [
                        'cart_items' => json_encode($cart_items),
                        'total_price' => $curr_cart->get_price()
                    ]);    
                }
                else
                {
                    return view('checkout', [
                        'cart_items' => json_encode($cart_items),
                        'total_price' => $curr_cart->get_price(),
                        'info' => $info,
                    ]);    
                }
            }
        }
        else return redirect() -> to ('/login');
    }
    
    function check_discount()
    {
        $code = $this->request->getPost('voucher_code');
        $db = Database::connect();
        $query = "SELECT Discount_Value, ID
                  FROM voucher 
                  WHERE Name = '{$code}' 
                    AND End_Date > CURRENT_DATE
                    AND CURRENT_DATE > Start_Date
                    AND Current_Usage < Max_Usage";
        $result = $db->query($query)->getResult();
    
        if (!empty($result)) {
            $discount_value = $result[0]->Discount_Value;
            $voucher_id = $result[0]->ID;
        } else {
            $discount_value = 0;
            $voucher_id = 0;
        }
    
        $response = [
            'discount_value' => $discount_value,
            'voucher_id' => $voucher_id,
        ];
    
        $this->response->setContentType('application/json');
        return $this->response->setBody(json_encode($response));
    }

    function vnpay_generate()
    {
        error_reporting(E_ALL & ~E_NOTICE & ~E_DEPRECATED);
        date_default_timezone_set('Asia/Ho_Chi_Minh');
        /**
         * 
         *
        * @author CTT VNPAY
        */
        require_once 'C:\xampp\htdocs\pepicase\app\Config\Config_VNPAY.php';

        $vnp_TxnRef = rand(1,10000); //Mã giao dịch thanh toán tham chiếu của merchant
        $vnp_Amount = $this->request->getPost('amount'); // Số tiền thanh toán
        $vnp_BankCode = $this->request->getPost('bankCode'); //Mã phương thức thanh toán
        $vnp_IpAddr = $this->request->getIPAddress(); //IP Khách hàng thanh toán

        $inputData = array(
            "vnp_Version" => "2.1.0",
            "vnp_TmnCode" => $vnp_TmnCode,
            "vnp_Amount" => $vnp_Amount * 100,
            "vnp_Command" => "pay",
            "vnp_CreateDate" => date('YmdHis'),
            "vnp_CurrCode" => "VND",
            "vnp_IpAddr" => $vnp_IpAddr,
            "vnp_Locale" => 'en',
            "vnp_OrderInfo" => "Thanh toan GD:". $vnp_TxnRef,
            "vnp_OrderType" => "other",
            "vnp_ReturnUrl" => $vnp_Returnurl,
            "vnp_TxnRef" => $vnp_TxnRef,
            "vnp_ExpireDate"=>$expire
        );

        if (isset($vnp_BankCode) && $vnp_BankCode != "") {
            $inputData['vnp_BankCode'] = $vnp_BankCode;
        }

        ksort($inputData);
        $query = "";
        $i = 0;
        $hashdata = "";
        foreach ($inputData as $key => $value) {
            if ($i == 1) {
                $hashdata .= '&' . urlencode($key) . "=" . urlencode($value);
            } else {
                $hashdata .= urlencode($key) . "=" . urlencode($value);
                $i = 1;
            }
            $query .= urlencode($key) . "=" . urlencode($value) . '&';
        }

        $vnp_Url = $vnp_Url . "?" . $query;
        if (isset($vnp_HashSecret)) {
            $vnpSecureHash =   hash_hmac('sha512', $hashdata, $vnp_HashSecret);//  
            $vnp_Url .= 'vnp_SecureHash=' . $vnpSecureHash;
        }
        header('Location: ' . $vnp_Url);
        $response = [
            'url' => $vnp_Url,
            'method_id' => $vnp_TxnRef,
        ];
        $this->response->setContentType('application/json');
        return $this->response->setBody(json_encode($response));
    }
    
    function generate_invoice()
    {
        $user = $this->request->getPost('user');
        $total_price = $this->request->getPost('Total_Price');
        $actual_price = $this->request->getPost('Actual_Price');
        $voucher_id = $this->request->getPost('Voucher_ID');
        $note = $this->request->getPost('Note');
        $method = $this->request->getPost('Method');
        $method_id = $this->request->getPost('Method_ID');

        if($method_id == '') $method_id = 0;
        
        if($voucher_id !== 0)
        {
            $db = Database::connect();
            $query = 
            "UPDATE voucher
            SET Current_Usage = Current_Usage + 1
            WHERE ID = {$voucher_id}";
            $db->query($query);
        }

        $invoice = new Invoice_Delivery();
        $new_invoice_id = $invoice->generate_invoice($user, $total_price, $actual_price, $voucher_id, $note, $method, $method_id);
        return $this->response->setJSON([
            'new_invoice_id' => $new_invoice_id,
        ]);
    }

    function create_delivery()
    {
        $invoice_id = $this->request->getPost('Invoice_ID');
        $firstName = $this->request->getPost('firstName');
        $lastName = $this->request->getPost('lastName');
        $address = $this->request->getPost('Address');
        $apartment = $this->request->getPost('Apartment');
        $country = $this->request->getPost('Country');
        $city = $this->request->getPost('City');
        $zipcode = $this->request->getPost('Zipcode');
        $phone = $this->request->getPost('Phone');
        $ship = $this->request->getPost('Ship');

        $invoice = new Invoice_Delivery();
        $invoice->create_delivery($invoice_id, $firstName, $lastName, $address, $apartment, $country, $zipcode, $phone, $ship, $city);
        return $this->response->setJSON([
            'url' => 'http://localhost/pepicase/public/checkout/done',
            'url_vnpay' => 'http://localhost/pepicase/public/checkout/pending',
        ]);
    }

    function vnpay_return()
    {
        $request = \Config\Services::request();

        $vnp_ResponseCode = $request->getGet('vnp_ResponseCode');
        $vnp_TxnRef = $request->getGet('vnp_TxnRef');

        $result = '';
        $invoice = new Invoice_Delivery();
        if($invoice->check_api_payment($vnp_TxnRef))
        {
            if($vnp_ResponseCode == '00') 
            {
                $invoice->confirm_api_payment($vnp_TxnRef);
                $result = 0;
                $curr_session = new CustomSession();
                $cart = new Cart($curr_session->get_id());
                $cart->clear();
            }
            else
            {
                $result = 2;
                $invoice->cancel_api_payment($vnp_TxnRef);
            }
    
            return view('checkoutDone', [
                'protocol' => 'vnpay',
                'result' => $result,
            ]);    
        }
        else return redirect() -> to ('/');
    }
}
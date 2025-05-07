<?php
namespace App\Models;
use Config\Database;
use App\Models\Cart;

class Invoice_Delivery
{
    function generate_invoice($user_id, $total_price, $actual_price, $voucher_id, $note, $method, $method_id)
    {
        $db = Database::connect();
        if($voucher_id == 0) $voucher_id = 'NULL';
        $query = "INSERT INTO invoice (User_ID, Voucher_ID, Total_Price, Actual_Price, Status, Note, Method, Method_ID)
                    VALUES ({$user_id}, {$voucher_id}, {$total_price}, {$actual_price}, 0, '{$note}', '{$method}', {$method_id})";
        
        $db->query($query);
        $new_invoice_id = $db->insertID();
        $cart = new Cart($user_id);
        $item_arrays = $cart->get();
        foreach ($item_arrays as $item) {
            /*
            echo $item['Image'] . "\n";
            echo $item['Name'] . "\n";
            echo $item['Price'] . "\n";
            echo $item['Product_ID'] . "\n";
            echo $item['Quantity'] . "\n";
            echo $item['Size'] . "\n";
            echo $item['User_ID'] . "\n\n";
            */

            $cart_details_query = "INSERT INTO invoice_details (Invoice_ID, Product_ID, Name_Product, Quantity, Price, Size) 
                                    VALUES ({$new_invoice_id}, {$item->Product_ID}, '{$item->Name}', {$item->Quantity}, {$item->Price}, '{$item->Size}')";
            $db->query($cart_details_query);
        }
        $cart->clear();
        return $new_invoice_id;
    }

    function create_delivery($invoice_id, $firstName, $lastName, $address, $apartment, $country, $zipcode, $phone, $ship, $city)
    {
        $db = Database::connect();
        $query = "INSERT INTO delivery (Invoice_ID, First_Name, Last_Name, Address, Apartment, Country, Zipcode, Phone, Shipping_Method, Status, City)
                    VALUES ({$invoice_id}, '{$firstName}' , '{$lastName}' , '{$address}' , '{$apartment}' , '{$country}' , '{$zipcode}' , '{$phone}', '{$ship}', 0, '{$city}')";
        $db->query($query);
    }

    function confirm_api_payment($method_id)
    {
        $db = Database::connect();
        $query = "UPDATE invoice SET Status = 1 WHERE Method_ID = '{$method_id}'";
        $db->query($query);
    }

    function cancel_api_payment($method_id)
    {
        $db = Database::connect();
        $query = "SELECT ID, Voucher_ID FROM invoice WHERE Method_ID = '{$method_id}'";
        $row = $db->query($query)->getResult();
        $invoice_id = $row[0]->ID;
        $voucher_id = $row[0]->Voucher_ID;
        $delivery_deletion = "DELETE FROM delivery WHERE Invoice_ID = '{$invoice_id}'";
        $db->query($delivery_deletion);
        $invoice_details_deletion = "DELETE FROM invoice_details WHERE Invoice_ID = '{$invoice_id}'";
        $db->query($invoice_details_deletion);
        $invoice_deletion = "DELETE FROM invoice WHERE ID = '{$invoice_id}'";
        $db->query($invoice_deletion);
        $voucher_restoration = "UPDATE voucher SET Current_Usage = Current_Usage - 1 WHERE ID = '{$voucher_id}'";
        $db->query($voucher_restoration);
    }

    function check_api_payment($method_id)
    {
        $db = Database::connect();
        $query = "SELECT * FROM invoice WHERE Method_ID = '{$method_id}'";
        $result = $db->query($query);
        if(!empty($result)) return true;
        else return false;
    }
}
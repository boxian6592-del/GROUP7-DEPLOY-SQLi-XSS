<?php
namespace App\Models;
use Config\Database;

class Cart
{
    public $user_id = null;
    // các giá trị như tổng số lượng, tổng giá trị giỏ hàng đều sẽ được database chạy trigger để tự tính hết
    public function __construct($userID) // hàm khởi tạo đối tượng cart
    {
        $db = Database::connect();
        $this->user_id = $userID; // set user_id của đối tượng = đối số $userID
        $query = "SELECT * FROM cart WHERE User_ID = {$this->user_id}";
        // tiến hành kiểm tra giỏ hàng này đã có tồn tại chưa
        $result = $db->query($query)->getResult();
        if (empty($result)) // nếu chưa tồn tại, tiến hành khởi tạo
        {
            $new_query = "INSERT INTO cart (User_ID, Total_Amount, Total_Price) VALUES ({$this->user_id} ,0 ,0)";
            $db->query($new_query);
        }
    }

    public function add_item($productID, $name, $size, $quantity, $price)
    {
        $db = Database::connect();
        //kiểm tra xem trong cart đã có item loại đó size đó chưa
        $check = "SELECT * FROM cart_details WHERE User_ID = {$this->user_id} AND Product_ID = {$productID} and Size = '{$size}'";
        $result = $db->query($check)->getResult();
        //kiểm tra xem trong cart đã có item loại đó size đó chưa
        if (empty($result)) { // nếu k tìm thấy hàng nào thì insert item đó
            // query insert
            $insert = "INSERT INTO cart_details (User_ID, Product_ID, Name, Size, Quantity, Price) VALUES ({$this->user_id}, {$productID}, '{$name}', '{$size}', ".$quantity.", ".$price.")";
            $check_result = $db->query($insert);
            if($check_result) return true; else return false;
            // query insert
        }
        else{ // nếu có tìm thấy, thay vì thêm sẽ update quantity của item đó
            $row = $result[0];
            $existingQuantity = $row->Quantity;
            $new_quantity = $existingQuantity + $quantity; // lấy giá trị cũ cộng thêm quantity mới
            if($this->updateCartItem($productID, $size, $new_quantity)) return true; else return false;
        }
    }

    public function delete_item($productID, $size)
    {
        $db = Database::connect();
        $query = "DELETE FROM cart_details WHERE User_ID = {$this->user_id} AND Product_ID = {$productID} AND Size = '{$size}'";
        $result = $db->query($query);
        if ($result == 0) 
        return false;
        else return true;
    }
    public function updateCartItem($productID,$size,$newQuantity)
    {
        $db = Database::connect();
        $query = "UPDATE cart_details SET Quantity = {$newQuantity} WHERE User_ID = {$this->user_id} AND Product_ID = {$productID} AND Size = '{$size}'";
        $db->query($query);
        $affectedRows = $db->affected_rows;
        if ($affectedRows > 0) return true;
        else return false;
    }

    public function get()
    {
        $db = Database::connect();
        $query = "
        SELECT cart_details.User_ID, cart_details.Product_ID, cart_details.Name, Size, Quantity, cart_details.Price, product.Image
        FROM cart_details left join product ON cart_details.Product_ID = product.ID
        WHERE User_ID = {$this->user_id};
        ";
        $result = $db->query($query)->getResult();
        if(!empty($result)) return $result;
        else return [];
    }

    public function get_amount()
    {
        $db = Database::connect();
        $query = "SELECT Total_Amount FROM cart WHERE User_ID = {$this->user_id}";
        $result = $db->query($query)->getRow();
        if ($result) {
            $totalQuantity = $result->Total_Amount;
        } else {
            $totalQuantity = 0;
        } return $totalQuantity;
    }

    public function get_price()
    {
        $db = Database::connect();
        $query = "SELECT Total_Price FROM cart WHERE User_ID = {$this->user_id}";
        $result = $db->query($query)->getRow();
        if ($result){
            $total_price = $result->Total_Price;
        } else {
            $total_price = 0;
        } return $total_price;
    }

    public function get_amount_for_item($productID)
    {
        $db = Database::connect();
        $query = "SELECT Sum(Quantity) as QuantitySum FROM cart_details WHERE User_ID = {$this->user_id} AND Product_ID = {$productID}";
        $result = $db->query($query)->getRow();
        if ($result) {
            $indivQuantity = $result->QuantitySum;
        } else {
            $indivQuantity = 0; // or handle the case where the result is null
        }
        return $indivQuantity;
    }

    public function clear()
    {
        $db = Database::connect();
        $query1 = "DELETE FROM cart_details WHERE User_ID = {$this->user_id}";
        $db->query($query1);
        $query2 = "DELETE FROM cart WHERE User_ID = {$this->user_id}";
        $db->query($query2);
    }
}
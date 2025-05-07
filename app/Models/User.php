<?php
namespace App\Models;

use CodeIgniter\Model;
use Config\Database;


class User extends Model
{
    protected $table = 'user';
    protected $primaryKey = 'id';
    protected $email = null;
    protected $password = null;
    public $id = null;
    protected $oauth_id = null;
    protected $isAdmin = null;
    protected $returnType = 'array';
    protected $useSoftDeletes = false;
    protected $allowedFields = ['email', 'password', 'oauth_id'];

    protected $skipValidation = false;
    protected $cleanValidationRules = true;

    
    public function __construct($email = null, $password = null, $oauth_id = null )
    {
        $db = Database::connect();
        if ($oauth_id !== null) {
            $result = $db->query("SELECT * FROM $this->table WHERE oauth_id = ?", [$oauth_id])->getResult();            
            if (empty($result)) {
                $this->create($email, 'matkhau', $oauth_id);
                $result = $db->query("SELECT * FROM $this->table WHERE oauth_id = '{$oauth_id}'")->getResult();            
                $row = $result[0];
                $this->id = $row->ID;
                $this->email = $row->Email;
                $this->password = $row->Password;
                $this->oauth_id  = $row->oauth_id;
                $this->isAdmin = $row->Is_Admin; 
            } else {
                $row = $result[0];
                $this->id = $row->ID;
                $this->email = $row->Email;
                $this->password = $row->Password;
                $this->oauth_id  = $row->oauth_id;
                $this->isAdmin = $row->Is_Admin; 
            }
        } else {
            $result = $db->query("SELECT * FROM $this->table WHERE email = ? AND password = ?", [$email, $password])->getResult();
            if (empty($result)) {
                
            } else {
                $row = $result[0];
                $this->id = $row->ID;
                $this->email = $row->Email;
                $this->password = $row->Password;
                $this->isAdmin = $row->Is_Admin;
                $this->oauth_id = $row->oauth_id;
            }
        }
    }

    public function check_if_authorized()
    {
        if (empty($this->id) && empty($this->oauth_id)) return false;
        else return true;
    }

    public function getPurchases($id) {
        $db = Database::connect();
        $sql = "
        SELECT order_date, invoice.id, product_id, name_product, invoice_details.price, invoice_details.size, image, quantity
        FROM invoice_details
        INNER JOIN invoice ON invoice_details.invoice_id = invoice.id
        INNER JOIN product ON product.id = invoice_details.product_id
        WHERE user_id = ? ORDER BY order_date DESC
    ";
    $result = $db->query($sql, [$id])->getResultArray();
    if (empty($result)) return null;
    return $result;
    }


    public function create($mail, $pass = null, $oauth_id = null)
    {
        $db = Database::connect();
        $result = $db->query("INSERT INTO user (Email, Password, oauth_id, Is_Admin) VALUES (?, ?, ?, 0);", [$mail, $pass, $oauth_id]);
        if ($result !== null)
        {
            $new_user = new User($mail, $pass, $oauth_id);
            return $new_user->id;
        }
        else return 0;
    } 

    public function check_email($mail)
    {
        $db = Database::connect();
        $result = $db->query("SELECT * FROM user WHERE Email = ?", [$mail])->getResult();
        return ($result !== []);
    }

    public function get_email($id)
    {
        $db = Database::connect();
        $result = $db->query("SELECT Email FROM user WHERE ID = ?", [$id])->getRow();
        return $result->Email;
    }

    public function fetch_wishlist($id)
    {
        $db = Database::connect();
        $sql = "SELECT Product_ID, Name, Price, Image FROM wishlist 
        LEFT JOIN product ON wishlist.Product_ID = product.ID 
        WHERE User_ID = ?";
        $wishlist_items = $db->query($sql, [$id])->getResult();


        if (empty($wishlist_items)) return [];
        else return $wishlist_items;
    }

    public function testdb() {
        $db = Database::connect();
        $sql = "SELECT * from cart";
        $query = $db->query($sql);
        if (!$query) {
            echo "Error: " . $db->error();
            return false;
        }
        $result = $query->getResult();
        if (empty($result)) return false;
        return $result;
    }

    function isAlreadyRegister($authid){ //check đã đăng nhập oauth_id chưa
        return $this->where('oauth_id', $authid)->first() !== null;
        }
        function updateUserData($userdata){ 
            $db = Database::connect();
            $sql1 = "SELECT * FROM user_info WHERE User_ID = ?";
            $result = $db->query($sql1, [$userdata['userid']])->getResult();
            
            if (empty($result)) {
                $sql1 = "INSERT INTO user_info (User_ID, First_Name, Last_Name) VALUES (?, ?, ?)";
                $db->query($sql1, [
                    $userdata['userid'],
                    $userdata['First_Name'],
                    $userdata['Last_Name'],
                ]);
            } else {
                $sql1 = "UPDATE user_info SET First_Name = ?, Last_Name = ? WHERE User_ID = ?";
                $db->query($sql1, [
                    $userdata['First_Name'],
                    $userdata['Last_Name'],
                    $userdata['userid']
                ]);
            }
        }
        
        function insertUserData($userdata){ //thêm thông tin
            $db = Database::connect();
            $sql1 = "INSERT INTO user_info(User_ID, First_Name, Last_Name) VALUES (?, ?, ?)";
            $db->query($sql1, [$userdata->userid, $userdata->First_Name, $userdata->Last_Name]);
        }
    
        function deletePurchases($invoiceId) {
            $db = Database::connect();
            $sql = "DELETE FROM invoice_details WHERE invoice_id = ?";
            $db->query($sql, [$invoiceId]);
            $sql = "DELETE FROM invoice WHERE id = ?";
            $db->query($sql, [$invoiceId]);
        }

    public function update_info($id, $isFound, $object)
    {
        $db = Database::connect();
        $sql = "SELECT * FROM user_info WHERE User_ID = {$id}";
        $result = $db->query($sql)->getResult();
        if(empty($result))
        {
            $add = "INSERT INTO user_info 
        (User_ID, First_Name, Last_Name, Area_Code, Phone, Address, Apartment, Country, City, Zipcode)
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $db->query($add, [
    $id,
    $object->firstName,
    $object->lastName,
    $object->areaCode,
    $object->phone,
    $object->address,
    $object->apartment,
    $object->country,
    $object->city,
    $object->zipCode
]);
  $update = "UPDATE user_info SET 
            First_Name = ?, Last_Name = ?, Area_Code = ?, Phone = ?, 
            Address = ?, Apartment = ?, Country = ?, City = ?, Zipcode = ? 
            WHERE User_ID = ?";
            $db->query($update, [
    $object->firstName,
    $object->lastName,
    $object->areaCode,
    $object->phone,
    $object->address,
    $object->apartment,
    $object->country,
    $object->city,
    $object->zipCode,
    $id
]);

}
    }

    public function get_info($id)
    {
        $db = Database::connect();
        $sql = "SELECT * FROM user_info WHERE User_ID = {$id}";
        $result = $db->query($sql)->getResult();
        if(!empty($result)) return $result;
        else return false;
    }

    public function add_comment($id ,$product_id, $comment, $stars)
    {
        $db = Database::connect();
        $query = $db->table('feedback')
                ->insert([
                    'User_ID' => $id,
                    'Product_ID' => $product_id,
                    'Comment' => $comment,
                    'Star' => $stars
                ]);
        if ($query) {
            return true;
        } else {
            return false;
        }
    }

    public function clear($id)
    {
        $db = Database::connect();
        $clear_comments = "DELETE FROM feedback WHERE User_ID = {$id}";
        $db->query($clear_comments);
        $clear_delivery = "DELETE FROM delivery WHERE Invoice_ID IN (SELECT ID FROM invoice WHERE User_ID = {$id})";
        $db->query($clear_delivery);
        $clear_invoice_details = "DELETE FROM invoice_details WHERE Invoice_ID IN (SELECT ID FROM invoice WHERE User_ID = {$id})";
        $db->query($clear_invoice_details);
        $clear_invoice = "DELETE FROM invoice WHERE User_ID = {$id}";
        $db->query($clear_invoice);
        $clear_info = "DELETE FROM user_info WHERE User_ID = '{$id}'";
        $db->query($clear_info);
        $clear_user = "DELETE FROM user WHERE ID = '{$id}'";
        $db->query($clear_user);
    }
}
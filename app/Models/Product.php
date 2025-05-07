<?php
namespace App\Models;

use CodeIgniter\Model;
use Config\Database;

// model Sản phẩm

class Product extends Model
{
    protected $table = 'product'; // giá trị default: bảng product
    protected $primaryKey = 'ID'; // giá trị default: khóa chính ID
    protected $name = null; // tên sp
    protected $price = null; // giá
    protected $productID = null; // ID sản phẩm
    protected $imgPath = null; // dường dẫn đến ảnh

    public function __construct($id = null) // hàm khởi tạo đối tượng
    {
        parent::__construct();
        if($id != null)  // nếu có id
        {
            $db = Database::connect(); // nối đến database
            $result = $db->query("SELECT * FROM $this->table WHERE $this->primaryKey = ".$id)->getResult(); // truy vấn
            if (empty($result)) // nếu truy vấn rỗng
            {
                $this->productID = "not_found"; // k tìm thấy sản phẩm, cho productID = "not_found"
            }
            else // nếu truy vấn k rỗng
            {
                // có đối tượng, gán data vào các thuộc tính
                $row = $result[0];
                $this->productID = $row->ID;
                $this->price = $row->Price;
                $this->name = $row->Name;
                $this->imgPath = $row->Image;
            }
        }
        // còn nếu id = null thì sẽ không làm gì cả, chỉ khởi tạo đối tượng
    }

    public function check_if_found() // kiểm tra xem spham có được tìm thấy không
    {
        if ($this->productID == "not_found") return false; // nếu ID = not found, trả false
        else return true; // ngược lại
    }

    public function check_favorited($user_id) // hàm kiểm tra xem sp này có đc favorite bởi user không
    {
        $db = Database::connect(); // nối database
        $query = "SELECT * FROM wishlist WHERE Product_ID = '{$this->productID}' AND User_ID = '{$user_id}'"; // tra bảng wishlist
        $result = $db->query($query)->getResult();
    
        if (empty($result)) return false; // nếu kết quả rỗng, return false,
        else return true; // ngược lại
    }

    public function toggleFavorite($user_id) // hàm chỉnh lại status favorite
    {
        $db = Database::connect(); // nối database
        if($this->check_favorited($user_id)) // nếu hiện đang favorite
        {
            $query = "DELETE FROM wishlist WHERE Product_ID = '{$this->productID}' AND User_ID = '{$user_id}'"; // truy vấn xóa hàng
            $db->query($query)->getResult(); // chạy truy vấn
        }
        else // nếu không (tức là chưa favorite)
        {
            $query = "INSERT INTO wishlist (User_ID, Product_ID) VALUES ('{$user_id}','{$this->productID}')"; // truy vấn thêm hàng
            $db->query($query)->getResult(); // chạy truy vấn
        }
    }

    public function getFullInfo() // hàm trả vệ một object chứa đủ thông tin (cho user xem)
    {
        $data_bundle = // tạo 1 biến dạng object
        [
            'id' => $this->productID,
            'name' => $this->name,
            'price'=> $this->price,
            'path' => $this->imgPath,
            'favorite' => '',
        ];
        return $data_bundle; // trả về biến đó
    }
    
    public function getProducts($limit = 6)
    {
        return $this->limit($limit)->find();
    }
    public function getProductsWithOffset($limit, $offset)
    {
        return $this->findAll($limit, $offset);
    }
    public function filterProducts($collections, $materials, $colors)
    {
        $db = Database::connect();
        $builder = $db->table($this->table);

        if (!empty($collections)) {
            $builder->whereIn('Collect_ID', $collections);
        }
        if (!empty($colors)) {
            $builder->whereIn('Color_ID', $colors);
        }

        return $builder->get()->getResultArray();
    }

    public function get_comments()
    {
        $db = Database::connect();
        $query = "SELECT Created_At, Comment, Star FROM feedback WHERE Product_ID = '{$this->productID}' ORDER BY Created_At DESC";
        $result = $db->query($query)->getResult();
        if(!empty($result)) return $result;
        else return [];
    }
}

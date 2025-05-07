<?php
namespace App\Models;

use CodeIgniter\Services;
use CodeIgniter\Session\Session;
use CodeIgniter\Encryption\Encryption;


// là hàm khởi tạo session riêng, lưu trữ thông tin session = cookie trên phía người dùng
// đồng bộ thông tin về phía server và người dùng mỗi khi load site
// xem qua top-header để xem luận lý khởi tạo session mỗi lúc 1 trang đc load
class CustomSession
{
    public function encrypt($data) // hàm mã hóa 
    {
        $key = 'session-suck@123'; // Replace with your encryption key
        $cipher = 'AES-256-CBC';
        $ivLength = openssl_cipher_iv_length($cipher);
        $iv = openssl_random_pseudo_bytes($ivLength);
        $encrypted = openssl_encrypt($data, $cipher, $key, OPENSSL_RAW_DATA, $iv);
        $result = base64_encode($iv . $encrypted);
        return $result;
    }

    public function decrypt($encryptedData) // hàm gỡ mã hóa
    {
        $key = 'session-sucks@123';
        $cipher = 'AES-256-CBC';
        $ivLength = openssl_cipher_iv_length($cipher);
        $encryptedData = base64_decode($encryptedData);
        $iv = substr($encryptedData, 0, $ivLength);
        $encrypted = substr($encryptedData, $ivLength);
        $decrypted = openssl_decrypt($encrypted, $cipher, $key, OPENSSL_RAW_DATA, $iv);
        return $decrypted;
    }

    public function __construct($id = null) // hàm khởi tạo đối tượng, có hai hướng
    {
        if ($id === null) // nếu không để $id vào mà để null
        {
            \Config\Services::session(); // khởi tạo rỗng, để chạy được hàm con
        }
        else // nếu không rỗng, có 1 $id user đã được cho vào ()
        {
            $session = \Config\Services::session(); 
            helper('cookie'); // gọi library cookie
            $identifier = $this->encrypt($id); // mã hóa $id
            $session->set('identifier', $id); // gắn $id *CHƯA* mã hóa cho session
            set_cookie('id', $identifier, 3600 * 12); //khởi tạo và gán $id *ĐÃ* mã hóa cho cookie trên máy
        }
    }

    public function isSessionSet() // hàm bool để xem trang đã có phiên chưa
    {
        $session = \Config\Services::session();
        return ($session->get('identifier') !== null); // nếu get -> identifier = null, tức là identifier k đc set từ đầu, trả false
    }

    public function get_id() // hàm lấy identifier ra (có thể merge vs isSessionSet nhma ừ)
    {
        $session = \Config\Services::session();
        return $session->get('identifier'); 
    }

    public function fetch_session_cookie() // hàm này kiểm tra và nhập id từ cookie trên máy
    {
        helper('cookie'); // gọi library cookie
        $cookie = get_cookie('id'); // gọi thử cookie 'id'
        if ($cookie === null) { // nếu không tồn tại
            return null; // trả về null
        }
        else // nếu không
        {
            $identifier = $this->decrypt($cookie); // gỡ mã hóa
            delete_cookie('id'); // xóa cookie ban đầu đó
            $this->__construct($identifier); // tạo session, cùng lúc đó khởi tạo lại cookie
            return $identifier; // trả id
        }     
    }
    
    public function delete_session_cookie() // xóa session lẫn cookie
    {
        helper('cookie');
        delete_cookie('id');
        $session = \Config\Services::session();
        $session->destroy();
    } 

    public function set_field($field, $value)
    {
        $session = \Config\Services::session();
        $session->set($field, $value);
    }

    public function get_field($field)
    {
        $session = \Config\Services::session();
        return $session->get($field);
    }

    public function set_previous_url($redirectUrl)
    {
        $session = \Config\Services::session();
        $session->set('redirect_url', $redirectUrl);
    }

    public function get_previous_url()
    {
        $session = \Config\Services::session();
        $url = $session->get('redirect_url');
        return $url;
    }
}
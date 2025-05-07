<?php

namespace App\Controllers;

require_once 'C:\xampp\htdocs\pepicase\app\Libraries\PHPMailer\src\PHPMailer.php';
require_once 'C:\xampp\htdocs\pepicase\app\Libraries\PHPMailer\src\Exception.php';
require_once 'C:\xampp\htdocs\pepicase\app\Libraries\PHPMailer\src\SMTP.php';

use App\Models\User;
use App\Models\CustomSession;

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

class SignUpController extends BaseController
{
    private function encrypt($data) // hàm encrypt custom
    {
        $key = 'encryption-key'; // Replace with your encryption key
        $iv = 'd2a1b9c0e9f7a5de';
        $encrypted = openssl_encrypt($data, 'AES-256-CBC', $key, 0, $iv);
        $encoded = str_replace(['+', '/', '='], ['-', '_', ''], base64_encode($encrypted));
        return $encoded;
    }

    private function decrypt($encryptedData) // hàm decrypt custom
    {
        $iv = 'd2a1b9c0e9f7a5de';
        $key = 'encryption-key'; // Replace with your encryption key
        $decoded = base64_decode(str_replace(['-', '_'], ['+', '/'], $encryptedData));
        $decrypted = openssl_decrypt($decoded, 'AES-256-CBC', $key, 0, $iv);
        return $decrypted;
    }

    public function send_signup_email() // hàm gửi mail signup
    {
        // lấy data từ post
        $mail = $this->request->getPost('email');
        $password = $this->request->getPost('password');
        $confirm_password = $this->request->getPost('confirm_password');
        // lấy data từ post

        // luật (FE lẫn BE)
        $rules = [
            'email' => 'valid_email',
        ];

        $data = [
            'validation' => '',
        ];
        // luật (FE lẫn BE)

        if($this->validate($rules)) //nếu thỏa luật, tiếp tục
        {
            $user = new User($mail, $password); // chạy hàm khởi tạo để kiểm tra user đã tồn tại chưa

            if ($user->check_if_authorized()) //nếu user đã tồn tại
            {
                $message = [
                    "error"=> "User already exist!",
                ];
                return view('signup', $message); // chạy message "Đã tồn tại user"
            }
            else // nếu user chưa tồn tại, thỏa các đkiện và chạy mail xác nhận
            {
                $encrypted_password = $this->encrypt($password);
                $encrypted_email = $this->encrypt($mail);
                $auth_email = new PHPMailer(true);
                $auth_email->SMTPDebug = SMTP::DEBUG_SERVER;
                $auth_email->Debugoutput = 'html'; 
                $auth_email->isSMTP();
                $auth_email->CharSet = "utf-8";
                $auth_email->Host = 'smtp.gmail.com';
                $auth_email->Port = 465;
                $auth_email->SMTPAuth = true;
                $auth_email->Username = 'ndat34035@gmail.com'; 
                $auth_email->Password = 'nnvr tkbp yzlc kbny';  
                $auth_email->SMTPSecure = 'ssl';  
                $auth_email->setFrom('ndat34035@gmail.com', 'Pepicase');
                $auth_email->addAddress($mail, 'Recipient Name');
                $auth_email->isHTML(true);
                $auth_email->Subject = 'PEPICASE - SIGN UP VERIFICATION';
                $content ='
<html>
    <body>
        <div style="font-family: Arial, sans-serif; line-height: 1.6; color: black; max-width: 50vw; margin: auto; padding: 20px; border: 1px solid black; border-radius: 10px; background-color: white;">
            <div style="background-color: #FFF3C0; color: black; padding: 10px 0; text-align: center; border-radius: 10px 10px 0 0;">
                <h1>You have attempted to register for an account!</h1>
            </div>
            <div style="padding: 20px;">

                <p>PEPICASE has detected that you are signing up for an account. If this is not you, <strong>ignore this message!</strong></p>
                <p>Otherwise, please click the link below to confirm your signup!</p>

                <div style="display: flex; justify-content: center; align-items: center; margin: 20px 0;">
                        <a style="text-decoration: none; color: black;" href="http://localhost/pepicase/public/signup/confirmed/'.$encrypted_email.'/'.$encrypted_password.'">
                        <button style="background-color: #FFF3C0; height: 100%; font-size:20px">Sign up here!</button>
                        </a>
                </div>
            </div>
        </div>
        <div class="footer" style="text-align: center; padding: 10px 0; color: #777; font-size: 12px;">
            <p>© Pepicase</p>
        </div>
    </body>
</html>';
                $auth_email->Body = $content;
                try 
                {
                    $auth_email->smtpConnect(array(
                        "ssl" => array(
                            "verify_peer" => true,
                            "verify_peer_name" => false,
                            "allow_self_signed" => false)));
                if ($auth_email->send()) // nếu đã gửi thành công, redirect user về pending
                    {
                        return redirect()->to('/signup/pending');
                    } 
                    else //nếu đã gửi KHÔNG thành công, trả lỗi tại signup new để debug
                    {
                        $error = [
                            'error' => $auth_email->ErrorInfo,
                            ];
                        return view('signup', $error);
                    }
                } 
                catch (Exception $e)  // nếu có lỗi trước khi gửi đc request
                {
                     $error = [
                        'error' => $e->getMessage(),
                    ];
                    return view('signup', $error); // trả lỗi
                }
            };
        }
        else // nếu không thỏa mail
        {
            $error = [
                "error"=> "Please enter a valid email!",
            ];
            return view('signup', $error); // trả lỗi
        }

    }

    public function signup($encrypted_email, $encrypted_password) // hàm kích hoạt sau khi ấn vào link xác nhận
    {
        // mở gói mail đã mã hóa và pass đã mã hóa
        $email = $this->decrypt($encrypted_email);
        $password = $this->decrypt($encrypted_password);
        // mở gói mail đã mã hóa và pass đã mã hóa

        $new_user = new User($email,$password); // khởi tạo user với hai thứ trên
        if( $new_user->check_if_authorized()) // nếu user đã tồn tại
        {
            return redirect() -> to ('/'); // redirect về trang chủ
        }
        else // nếu user chưa tồn tại 
        {
            $outcome = $new_user->create($email, $password, null); // gọi hàm khởi tạo
            if( $outcome ) // nếu outcome không rỗng
            {
                $new_session = new CustomSession($outcome); 
                return redirect() -> to ('/');
                // khởi tạo session mới cho người dùng (gồm session + cookie), rồi
                // chuyển hướng về trang chủ
            }
            else return redirect()->to('/signup');
            // nếu đã rỗng, tức không thành công, trả lỗi
        }
    }

    public function index() // hàm trả về giao diện signup
    {
        $curr_session = new CustomSession(null); // khởi tạo đổi tượng session rỗng để sử dụng hàm con
        if ($curr_session->isSessionSet()) return redirect() -> to('/');
        // nếu session đã có, người dùng đã sign in rồi, thì về trang chủ
        return view ('signup');
        // nếu không, sẽ trả về trang signup như bth
    }

    public function pending() // hàm trả qua trang pending
    {
        return view('email_pending');
    }
}
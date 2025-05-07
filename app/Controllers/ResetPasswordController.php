<?php

namespace App\Controllers;

use App\Models\User;
use Config\Database;
require_once 'C:\xampp\htdocs\pepicase\app\Libraries\PHPMailer\src\PHPMailer.php';
require_once 'C:\xampp\htdocs\pepicase\app\Libraries\PHPMailer\src\Exception.php';
require_once 'C:\xampp\htdocs\pepicase\app\Libraries\PHPMailer\src\SMTP.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

use App\Models\CustomSession;

class ResetPasswordController extends BaseController
{
    private function encrypt($data) // hàm encrypt custom
    {
        $key = 'encryption-key'; // có thể đổi key
        $iv = 'd2a1b9c0e9f7a5de';
        $encrypted = openssl_encrypt($data, 'AES-256-CBC', $key, 0, $iv);
        $encoded = str_replace(['+', '/', '='], ['-', '_', ''], base64_encode($encrypted));
        return $encoded;
    }

    private function decrypt($encryptedData) // hàm decrypt custom
    {
        $iv = 'd2a1b9c0e9f7a5de';
        $key = 'encryption-key'; // key phải khớp với ở trên
        $decoded = base64_decode(str_replace(['-', '_'], ['+', '/'], $encryptedData));
        $decrypted = openssl_decrypt($decoded, 'AES-256-CBC', $key, 0, $iv);
        return $decrypted;
    }

    public function index() // vào trang reset password sẽ được dẫn về trang reset password
    {
        return view("resetPassword1");
    }

    public function check_and_send() // function kiểm tra người dùng có trên hệ thống hay không
    {
        $mail = $this->request->getPost("email"); // lấy email từ input

        // đặt luật về mail
        $rules = [
            'email' => 'valid_email',
        ];

        $data = [
            'validation' => '',
        ];
        // đặt luật về mail

        if ($this->validate($rules)) // nếu mail hợp lệ, qua chốt 1
        {
            $curr = new User(null, null); // gọi model User rỗng để chỉ chạy các function
            if ($curr->check_email($mail)) // nếu tồn tại User có mail = $mail, bắt đầu gửi mail xác nhận
            {
                $encrypted_email = $this->encrypt($mail); // email đã nhập sẽ được mã hóa và đính vào URL
                $auth_email = new PHPMailer(true);
                $auth_email->SMTPDebug = SMTP::DEBUG_SERVER;
                $auth_email->Debugoutput = 'html';
                $auth_email->isSMTP();
                $auth_email->CharSet = "utf-8";
                $auth_email->Host = 'smtp.gmail.com'; // gửi qua GMAIL
                $auth_email->Port = 465;
                $auth_email->SMTPAuth = true;
                $auth_email->Username = 'ndat34035@gmail.com'; // đang sử dụng acc clone của Đạt
                $auth_email->Password = 'nnvr tkbp yzlc kbny';   // đây là password do Google cung cấp cho các app
                $auth_email->SMTPSecure = 'ssl'; 
                $auth_email->setFrom('ndat34035@gmail.com', 'Pepicase'); 
                $auth_email->addAddress($mail, 'Recipient Name');
                $auth_email->isHTML(true);  // định dạng mail là HTML nên sẽ dễ design
                $auth_email->Subject = 'PEPICASE - RESET PASSWORD VERIFICATION';
                $content ='
<html>
<body>
<div style="font-family: Arial, sans-serif; line-height: 1.6; color: black; max-width: 50vw; margin: auto; padding: 20px; border: 1px solid black; border-radius: 10px; background-color: white;">
    <div style="background-color: #FFF3C0; color: black; padding: 10px 0; text-align: center; border-radius: 10px 10px 0 0;">
        <h1>You have initiated a password reset attempt.</h1>
    </div>
    <div style="padding: 20px;">
        <p>PEPICASE has received a reset password request from a user with this email. If this is not you, <strong>ignore this message!</strong></p>

        <p>Otherwise, please click the link below to initiate a password reset.</p>

        <div style="display: flex; justify-content: center; align-items: center; margin: 20px 0;">
            <a style="text-decoration: none; color: black;" href="http://localhost/pepicase/public/resetPassword/confirmed/'.$encrypted_email.'">
                <button style="background-color: #FFF3C0; height: 100%; font-size: 20px;">Reset password here!</button>
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
                try {
                    $auth_email->smtpConnect(
                        array(
                            "ssl" => array(
                                "verify_peer" => true,
                                "verify_peer_name" => false,
                                "allow_self_signed" => false
                            )
                        )
                    );
                    if ($auth_email->send()) // nếu mail báo gửi thành công
                    {
                        return redirect()->to('/resetPassword/pending'); // trả về trang giao diện pending
                    } 
                    else // nếu mail báo gửi KHÔNG thành công
                    {
                        $error = [
                            'error' => $auth_email->ErrorInfo,
                        ];
                        return view('resetPassword1', $error);
                        // trả về resetPassword1 cùng lỗi
                    }
                } 
                catch (Exception $e) // trường hợp lỗi trước khi request đến được Google 
                {
                    $error = [
                        'error' => $e->getMessage(),
                    ];
                    return view('resetPassword1', $error);
                    // trả về resetPassword1 cùng lỗi đó
                }
            } 
            else // còn nếu mail không tồn tại trong hệ thống
            {
                $message = [
                    'msg' => '',
                ];
                return view('ResetPassword1', $message);
                // báo lỗi
            }
        } 
        else // còn nếu không thỏa luật
        {
            $data['validation'] = $this->validator;
            return view('ResetPassword1', $data);
            // báo lỗi
        }
    }

    public function pending() // dẫn về trang pending_email
    {
        return view('email_pending');
    }

    public function showResetPassword($encrypted_email) // trường hợp routes nghe được get request to link đính trong mail
    {
        $data = [
            'encrypted_email' => $encrypted_email,//đóng gói encrypted_email để cho vào view tiếp theoi
        ];
        return view('resetPassword3',$data); 
        // show giao diện ResetPassword3, cho phép người dùng nhập pass mới, ấn reset sẽ nối đến ResetPassword ngay dưới
    }

    public function ResetPassword($encrypted_email) // hàm reset password sau khi đã điền pass mới
    {
        $new_password = $this->request->getPost("password"); // lấy pass từ post
        $decrypted_email = $this->decrypt($encrypted_email); // mở gói encrypted_mail đc pass từ đầu

        // chạy query db để update password
        $db = Database::connect();
        $updatePassword = "UPDATE user SET Password = '" . $new_password . "' WHERE Email = '" . $decrypted_email . "'";
        $db->query($updatePassword);
        // chạy query db để update password


        // chạy query 1 lần nữa để lấy ID người dùng
        $get_id = "SELECT ID FROM user WHERE Email = '$decrypted_email'";

        $result = $db->query($get_id)->getResult();
        if(count($result) == 1) // nếu chỉ có đúng 1 user thì oke, trả về login vs message là pass reset thành công, login lại
        {
            $row = $result[0];
            $new_session = new CustomSession($row->ID);
            return redirect()->to("/login")-> with("okay", "Password reset successfully, login with new credentials.");
        }
        else {return redirect()->to("/login")-> with("okay", "Something went wrong.");}
        // nếu không thì đã có bug, sẽ trả về Somethin went wrong. để nhận biết
    }

    public function check_and_send_user() // function kiểm tra người dùng có trên hệ thống hay không
    {
        $session = new CustomSession();
        $user = new User();
        $mail = $user->get_email($session->get_id());
        $encrypted_email = $this->encrypt($mail); // email đã nhập sẽ được mã hóa và đính vào URL
        $auth_email = new PHPMailer(true);
        $auth_email->SMTPDebug = SMTP::DEBUG_SERVER; // Keep the debug level as needed
        $auth_email->Debugoutput = function($str, $level) {
        // Do nothing, suppress the output
        };
        $auth_email->isSMTP();
        $auth_email->CharSet = "utf-8";
        $auth_email->Host = 'smtp.gmail.com'; // gửi qua GMAIL
        $auth_email->Port = 465;
        $auth_email->SMTPAuth = true;
        $auth_email->Username = 'ndat34035@gmail.com'; // đang sử dụng acc clone của Đạt
        $auth_email->Password = 'nnvr tkbp yzlc kbny';   // đây là password do Google cung cấp cho các app
        $auth_email->SMTPSecure = 'ssl'; 
        $auth_email->setFrom('ndat34035@gmail.com', 'Pepicase'); 
        $auth_email->addAddress($mail, 'Recipient Name');
        $auth_email->isHTML(true);  // định dạng mail là HTML nên sẽ dễ design
        $auth_email->Subject = 'PEPICASE - RESET PASSWORD VERIFICATION';
        $content ='
<html>
<body>
<div style="font-family: Arial, sans-serif; line-height: 1.6; color: black; max-width: 50vw; margin: auto; padding: 20px; border: 1px solid black; border-radius: 10px; background-color: white;">
    <div style="background-color: #FFF3C0; color: black; padding: 10px 0; text-align: center; border-radius: 10px 10px 0 0;">
        <h1>You have initiated a password reset attempt.</h1>
    </div>
    <div style="padding: 20px;">
        <p>PEPICASE has received a reset password request from a user with this email. If this is not you, <strong>ignore this message!</strong></p>

        <p>Otherwise, please click the link below to initiate a password reset.</p>

        <div style="display: flex; justify-content: center; align-items: center; margin: 20px 0;">
            <a style="text-decoration: none; color: black;" href="http://localhost/pepicase/public/resetPassword/confirmed/'.$encrypted_email.'">
                <button style="background-color: #FFF3C0; height: 100%; font-size: 20px;">Reset password here!</button>
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
        try {
            $auth_email->smtpConnect(
                array(
                    "ssl" => array(
                        "verify_peer" => true,
                        "verify_peer_name" => false,
                        "allow_self_signed" => false
                    )
                )
            );            if ($auth_email->send()) // nếu mail báo gửi thành công
            {
                return view('email_pending'); // trả về trang giao diện pending
            } 
            else // nếu mail báo gửi KHÔNG thành công
            {
                $error = [
                    'error' => $auth_email->ErrorInfo,
                ];
                return view('email_pending', $error);
            }
        } 
        catch (Exception $e) // trường hợp lỗi trước khi request đến được Google 
        {
            $error = 
            [
                'error' => $e->getMessage(),
            ];
            return view('email_pending', $error);
        }
    }
}
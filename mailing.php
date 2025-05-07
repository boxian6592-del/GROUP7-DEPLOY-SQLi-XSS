<?php
require "PHPMailer-master/src/PHPMailer.php"; 
    require "PHPMailer-master/src/SMTP.php"; 
    require 'PHPMailer-master/src/Exception.php'; 
    $mail = new PHPMailer\PHPMailer\PHPMailer(true);//true:enables exceptions
    try {
        $mail->SMTPDebug = 0; //0,1,2: chế độ debug
        $mail->isSMTP();  
        $mail->CharSet  = "utf-8";
        $mail->Host = 'smtp.gmail.com';  //SMTP servers
        $mail->SMTPAuth = true; // Enable authentication
        $mail->Username = 'ndat34035@gmail.com'; // SMTP username
        $mail->Password = 'losy dwwt mytk alya';   // SMTP password
        $mail->SMTPSecure = 'ssl';  // encryption TLS/SSL 
        $mail->Port = 465;  // port to connect to                
        $mail->setFrom('ndat34035@gmail.com', 'Tổng đài trúng thưởng' ); 
        $mail->addAddress('22520097@gm.uit.edu.vn', 'Hello'); 
        $mail->isHTML(true);  // Set email format to HTML
        $mail->Subject = 'HELLO';
        $noidungthu =
        $noidungthu ='
        <html>
    <head>
        <style>
            .email-container {
                font-family: Arial, sans-serif;
                line-height: 1.6;
                color: #333;
                max-width: 600px;
                margin: auto;
                padding: 20px;
                border: 1px solid #ddd;
                border-radius: 10px;
                background-color: #f9f9f9;
            }
            .header {
                background-color: #4CAF50;
                color: white;
                padding: 10px 0;
                text-align: center;
                border-radius: 10px 10px 0 0;
            }
            .content {
                padding: 20px;
            }
            .content p {
                margin: 10px 0;
            }
            .button-container {
                display: flex;
                justify-content: center;
                margin: 20px 0;
            }
            .button {
                display: inline-block;
                padding: 10px 20px;
                background-color: #4CAF50;
                color: white;
                text-decoration: none;
                border-radius: 5px;
            }
            .footer {
                text-align: center;
                padding: 10px 0;
                color: #777;
                font-size: 12px;
            }
        </style>
    </head>
    <body>
        <div class="email-container">
            <div class="header">
                <h1>Chúc mừng bạn!</h1>
            </div>
            <div class="content">
                <p>Bạn đã may mắn trúng <strong>Iphone 15 Pro Max</strong>!</p>
                <p>Vui lòng nhấn vào liên kết dưới đây để nhận phần thưởng của bạn:</p>
                <div class="button-container">
                    <a class="button" href="https://courses.uit.edu.vn/">Nhận phần thưởng</a>
                </div>
            </div>
            <div class="footer">
                <p>© 2024 Tổng đài trúng thưởng. Tất cả các quyền được bảo lưu.</p>
            </div>
        </div>
    </body>
    </html>';
        $mail->Body = $noidungthu;
        $mail->smtpConnect( array(
            "ssl" => array(
                "verify_peer" => false,
                "verify_peer_name" => false,
                "allow_self_signed" => true
            )
        ));
        $mail->send();
        echo 'Đã gửi mail xong';
    } catch (Exception $e) {
        echo 'Error: ', $mail->ErrorInfo;
    }
?>
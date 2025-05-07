<?php include(APPPATH.'views/components/usual-links.php'); 
use App\Models\CustomSession;?>
<link rel="stylesheet" href="/pepicase/public/css/login.css">
<?php include(APPPATH.'views/components/top-header.php'); ?>

<?php 
    $curr_session = new CustomSession();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Form</title>
</head>
<body>
    <div class="wrapper">
        <div class="form-container">
        <h2 class="text-center" style="margin: 0px; margin-bottom: 20px; color: #1F3E97; font-family: 'Londrina Solid';"><b>LOGIN</b></h2>
            <form method="post" action="/pepicase/public/login" onsubmit="return validateEmail()">
                <div class="form-group">
                    <label style="color: #1F3E97; " for="email"><b>Email</b></label>
                    <input type="text" class="form-control" id="email" name="email" placeholder="Abc123@gmail.com">
                    <div id="emailError" style="color: red; display: none;">Please enter a valid email address.</div>                   
                </div>
                <div class="form-group">
                    <label for="password" style="color:#1F3E97;"><b>Password</b></label>
                    <input type="password" class="form-control" id="password" name="password" placeholder="Abc123">
                    <span class="input-group-text" id="toggle_password">
                            <img style="margin: 0px;" src="/pepicase/public/pics/Ellipse.svg" alt="">
                    </span>
                </div>
                <div class="text-center">
                    <input class="btn btn-primary form-control" type="submit" name="signup" value="SIGN UP" 
                        style = "width: 100%; margin-top: 10px; background-color: #FFE57A; border-color: black; transition: background-color 0.3s; color:black; font-weight:bold; font-family: 'Lexend'">
                </div>  
                <div class="text-center" style = "color:red">
                    <strong><?php if(isset($msg)) echo $msg; else if(isset($validation)) echo $validation;?></strong>

                </div>
                <div class="d-flex align-items-center mt-3">
                    <hr class="flex-grow-1">
                    <span style="padding: 5px;">OR</span>
                    <hr class="flex-grow-1">
                </div>
                <div class="d-flex justify-content-between">
                        <button id="facebook-button" type="button" class="button">
                            <img style="width: 24px; height: 24px; margin-right: 5px" src="/pepicase/public/pics/Facebook_icon.svg" alt="facebook">
                        Facebook</button>                   

                        <button id="google-button" class="button" style="text-decoration: none;">
                            <img style="width: 26px; height: 26px; margin-right: 5px" src="/pepicase/public/pics/Google_Icons-09-512.webp" alt="google">
                        Google</button>
                    </div>
                <div class="d-flex justify-content-between mt-3">
                    <a style="color: #1F3E97; font-size:14px" href="/pepicase/public/resetPassword">Forgot your password?</a>
                    <a style="color: #1F3E97; font-size:14px" href="/pepicase/public/signup">Create an account</a>
                </div>
            </form>
        </div>
    </div>

    <script>
    var url = 'lmao';
    function validateEmail() {
        var email = document.getElementById('email').value;
        var emailError = document.getElementById('emailError');
        var emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

        if (!emailPattern.test(email)) {
            emailError.style.display = 'block';
            return false;
        }
        emailError.style.display = 'none';
        return true;
    }
    document.getElementById('toggle_password').addEventListener('click', function() {
                var passwordInput = document.getElementById('password');
                if (passwordInput.type == 'password') {
                    passwordInput.type = 'text';
                } else {
                    passwordInput.type = 'password';
                }
            }); 

    document.getElementById('facebook-button').addEventListener('click', function() {
        window.location.href = '<?= $fb_btn ?>';
    })

    document.getElementById('google-button').addEventListener('click', function() {
        window.location.href = '<?= $googleButton ?>';
    })

    </script>
</body>
</html>

<?php include(APPPATH.'views/components/usual-links.php'); ?>
    <link rel="stylesheet" href="/pepicase/public/css/login.css">
<?php include(APPPATH.'views/components/top-header.php'); ?>

        
        <div class="lexend d-flex align-items-center justify-content-center" style ="height: 50vh;">
            <form method = "post" action="/pepicase/public/login">
                <h2 class="text-center">LOGIN</h2>
                <table>
                    <tr>
                        <td>
                            <p>Phone number/Email</p>
                            <input style="margin-right:50px;" type ="text" name ="infor" placeholder="09xxxxxxxx / Abc123@gmail.com" required>
                        </td>
                        <td>
                            <p>Password</p>
                            <input type ="text" name ="Password" placeholder="Abc123" required>
                        </td>
                    </tr>
                    <tr>
                        <td align="center" colspan="2">
                            <button class="login-button">LOGIN</button>
                        </td>
                    </tr>
                    <?php
                    if(isset($validation))
                    {
                        echo "<td style ='color: red;'>";
                        echo $validation->listErrors();
                        echo "</td>";
                    }
                    else
                    {
                        echo "<td>";
                        echo "Incorrect email or password!";
                        echo "</td>";
                    }
                    ?>
                    <tr >
                        <td>
                            <a style=" color: #1F3E97" href="/pepicase/public/resetPassword">Forgot your password?</a>
                        </td>
                        <td style="text-align:right;">
                            <a style="color: #1F3E97" href="/pepicase/public/signup">Create an account</a>
                        </td>
                    </tr>
                </table>

                <div class="d-flex">
                    <hr class="before">
                    <text style="padding: 5px;">OR</text>
                    <hr class="last">
                </div>
                <div class="d-flex justify-content-between">
                    <a href="https://www.facebook.com/login">
                        <button type="button" class="button">
                            <img src="/pepicase/public/pics/Facebook_icon.svg" alt="Your Image">Facebook</button>
                    </a>
                    <a href="https://www.facebook.com/login">
                        <button type="button" class="button">
                            <img src="/pepicase/public/pics/Google_Icons-09-512.webp" alt="Your Image">Google</button>
                    </a> 
                </div>
            </form>
        </div>


<?php include(APPPATH.'views/components/bottom-footer.php'); ?>
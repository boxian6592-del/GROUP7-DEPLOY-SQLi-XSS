<?php include(APPPATH.'views/components/usual-links.php'); ?>
    <link rel="stylesheet" href="/pepicase/public/css/resetpassword1.css">
<?php include(APPPATH.'views/components/top-header.php'); ?>



<div class="lexend d-flex justify-content-center" style ="min-height: 60vh;">
    <form id ="form" method="post" action="/pepicase/public/resetPassword">
        <h2 class="text-center">Reset Password</h2>
        <hr>
        <div class="mb-3">
            <label for="infor" class="form-label">Enter your account's email</label>
            <input type="text" class="form-control" id="infor" name="email" placeholder="example@email.com" required>
        </div>
        <?php
                    if(isset($validation))
                    {
                        echo "<td style ='color: red;'>";
                        echo $validation->listErrors();
                        echo "</td>";
                    }
                    if(isset($msg))
                    {
                        echo "<td style ='color: red;'>";
                        echo "This account doesn't exist!";
                        echo "</td>";
                    }
                    if(isset($error))
                    {
                        echo "<td style ='color: red;'>";
                        echo $error;
                        echo "</td>";
                    }
        ?>
        <div class="d-grid gap-2">
            <a class="btn btn-primary" onclick="document.getElementById('form').submit()">NEXT</a>
            <a href="/pepicase/public/login" style ="color:black;">Go back</a>
        </div>
    </form>
</div>


<?php include(APPPATH.'views/components/bottom-footer.php'); ?>
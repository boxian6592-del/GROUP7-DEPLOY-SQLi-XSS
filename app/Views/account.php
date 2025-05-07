<?php include(APPPATH.'views/components/usual-links.php'); ?>
<?php include(APPPATH.'views/components/top-header.php'); ?>

<div style="background-color: #FFFAE3; height: 200px; display: flex; align-items: center; justify-content: center;">
    <b style="font-size: 40px; font-family: 'Lexend Tera';">My Account</b>
</div>

<div class="flex-container" style="display: flex; margin-top: 50px;">
<section style="margin-left: 10%; margin-right: 5%">
    <div style="margin-bottom: 40px;">
        <b style="font-size: 50px; font-family: 'Lexend';">Account</b>
        <div class="flex-container" style="margin-top: 10px;">
            <a style ="text-decoration:none;" href="">
                <img src="/pepicase/public/pics/user.svg" alt="">
            </a>
            <a href="/pepicase/public/logout" style="color: #838383; margin-left: 6px; font-size: 20px; font-family: 'Lexend';">Log out</a>
            <div id="delete" onclick="delete_account()" style="text-decoration: underline;color: #838383; margin-left: 6px; margin-top: 10px; font-size: 20px; font-family: 'Lexend';">Delete account</div>
            <div id="delete_alert" style="color: red; margin-left: 6px; font-size: 20px; font-family: 'Lexend';"></div>
        </div>
    </div>

    <div style="margin-bottom: 40px;">
        <b style="font-size: 50px; font-family: 'Lexend';">Order History</b>
        <div class="flex-container" style="color: #838383; font-size: 20px; font-family: 'Lexend';margin-top: 10px;">
            <text style="margin-top: 15px;">View history</text><a href="/pepicase/public/purchases" style="color: #838383; font-size: 20px; margin-left: 6px; font-family: 'Lexend';">here</a>
        </div>
    </div>

    <div>
        <b style="font-size: 50px; font-family: 'Lexend';">Wishlist</b>
        <div class="flex-container" style="color: #838383; font-size: 20px; font-family: 'Lexend';margin-top: 10px;">
            
                <img src="/pepicase/public/pics/black_heart.svg" alt="">

            <text style="margin-top: 15px;">View your wishlist</text><a href="/pepicase/public/wishlist" style="color: #838383; font-size: 20px; margin-left: 6px; font-family: 'Lexend';">here</a>
        </div>
    </div>
</section>

<aside style="margin-left: 3.5%; width: fit-content">
    <div>
        <b style="font-size: 40px;">Account details</b>
        <p style="font-size:20px; margin-top:10px; color: #838383; font-family: 'Lexend';">Manage and protect your account</p>
    </div>
    <hr style="color: #838383; margin-bottom: 30px">
    <form action="" style="font-family: 'Lexend';">
    <div style="display: flex; margin-bottom: 20px">
        <div style="margin-right: 10px; width: 35%">
            <input class="form-control" type="text" id ="fname" placeholder="First Name">
        </div>
        <div style="width: 65%">
            <input class="form-control" type="text" id="lname" placeholder="Last Name">
        </div>
    </div>

    <div style="margin-bottom: 20px">
        <input class="form-control" type="text" id="address" placeholder="Address" class="full-width">
        <div id="check_address">
            <span style="color: #838383; font-family: 'Lexend'">Please enter your street address with district, P.O. box, company name, c/o!</span>
        </div>
    </div>

    <div style="margin-bottom: 20px">
        <input class="form-control" type="text" id="apartment" placeholder="Apartment, suite, etc (optional)" class="full-width">
        <div id="check_apartment">
            <span style="color: #838383; font-family: 'Lexend'">Please enter your apartment, suite, unit, building, floor, etc. (optional)!</span>
        </div>
    </div>

    <div style="display: flex; margin-bottom: 20px">
        <div style="margin-right: 10px;">
            <input class="form-control" id="country" type="text" placeholder="Country">
        </div>
        <div style="margin-right: 10px;">
            <input class="form-control" id="city" type="text" placeholder="City">
        </div>
        <div>
            <input class="form-control" id="zipcode" type="text" placeholder="Zipcode">
        </div>
    </div>

    <div style="display: flex; margin-bottom: 20px">
        <div style="margin-right: 10px; width: 50%">
            <input class="form-control" id="area_code" type="text" placeholder="Area Code (e.g +84)" class="full-width">
        </div>
        <div style="width: 50%;">
            <input class="form-control" id="phone" type="text" placeholder="Telephone (e.g 0932456783)" class="full-width">
        </div>
    </div>
    </form>

    <button id="save" class="btn btn-dark" style="width: 50%; font-family: 'Lexend'; font-size: 20px;" onclick="check_inf()">Save Changes</button>
    <div id="inform" class ="d-flex" style="font-size: 20px; font-family: 'Lexend'; margin-top: 20px"></div>
    <div class="form-group"><a href="http://localhost/pepicase/public/user/changePassword/pending" style="font-size: 20px; color: #838383; text-decoration-line: underline; font-family: 'Lexend';">Change your password</a></div>
    <hr style="margin-top: 30px; color: #838383">
</aside>
</div>

<script src="/pepicase/public/js/jquery.js"></script>
<script>
    var user = <?= $user_id ?>;
    var info = <?php if(isset($info)) echo json_encode($info); else echo 'null';?>
</script>
<script src="/pepicase/public/js/account.js"></script>
<?php include(APPPATH.'views/components/bottom-footer.php'); ?>
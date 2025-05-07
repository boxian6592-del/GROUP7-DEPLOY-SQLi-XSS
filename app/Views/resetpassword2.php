<?php include(APPPATH.'views/components/usual-links.php'); ?>
    <link rel="stylesheet" href="/pepicase/public/css/resetpassword2.css">
<?php include(APPPATH.'views/components/top-header.php'); ?>
        
        <div class="d-flex align-items-center justify-content-center">
    <form action="">
        <h2 class="text-center">Reset Password</h2>
        <hr>
        <div class="mb-3">
            <label for="infor" class="form-label">Enter Verification Code</label>
            <input type="text" class="form-control" id="infor" name="infor" placeholder="123456" required>
        </div>
        <div class="d-grid gap-2">
            <a href="/pepicase/resetpassword3.php" class="btn btn-primary">NEXT</a>
            <a href="/pepicase/resetpassword1.php" >Cancel</a>
        </div>
    </form>
</div>

<?php include(APPPATH.'views/components/bottom-footer.php'); ?>
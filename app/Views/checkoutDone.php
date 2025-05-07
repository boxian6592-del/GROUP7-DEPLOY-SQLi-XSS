<?php include(APPPATH.'views/components/usual-links.php'); ?>
<?php include(APPPATH.'views/components/top-header.php'); ?>

    <div class="lexend d-flex flex-column justify-content-center align-items-center" style ="height:50vh;">
        <h1 style ="font-size: 50px">
            <?php 
                if($protocol == 'cash') echo 'A delivery order has been generated for your purchase!'; 
                if($protocol == 'vnpay')
                {
                    if($result == 0) echo 'Transaction via VNPAY successfully made!';
                    else if($result == 1) echo 'Transaction via VNPAY interrupted!';
                    else echo 'An error has occured to the VNPAY transaction!';
                };
            ?>
        </h1>
        <h2 style ="font-size: 50px">
            <?php 
                if($protocol == 'cash') echo 'A consultant will call you to further <br> confirm the delivery information!'; 
                if($protocol == 'vnpay' && isset($result)) 
                {
                    if($result !== 0) echo 'Please try checking out again.';
                    else echo 'A consultant will call you to further <br> confirm the deliver information!';
                };
            ?>
        </h2>
        <div class="lexend d-flex flex-row justify-content-center align-items-center">
            <a href="/pepicase/public/ <?php if(isset($result)) if($result !== 0) echo 'checkout' ?>" style ="color:black">
                <?php if(isset($result)) 
                    {
                        if($result !== 0) echo 'Try checking out again!'; 
                        else echo 'Go back to the home page here';
                    }
                ?>
            </a>
        </div>
    </div>
<?php include(APPPATH.'views/components/bottom-footer.php'); ?>
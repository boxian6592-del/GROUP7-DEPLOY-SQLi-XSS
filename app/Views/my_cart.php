<?php include(APPPATH.'views/components/usual-links.php'); ?>
<?php include(APPPATH.'views/components/top-header.php'); ?>


<div class ="container-fluid d-flex flex-column align-items-center" style = "margin: 0; padding: 0;">
    <div class = "lexend-tera container-fluid d-flex flex-column justify-content-center align-items-center" style = "height:200px; background-color:#FFFAE3; font-weight: 600;">
        <div class = "lexend-tera container-fluid d-flex flex-column justify-content-center align-items-center" style = "font-size: 50px; font-weight: 600">My Cart </div><br>
        <div class="lexend container-fluid d-flex justify-content-center align-items-center" style="font-size:20px;" >
            <p style="font-weight: 500"> Not ready to checkout? 
            <a style="color:#844700; font-weight: 300" href="/pepicase/public/product"> Continue Shopping </a><p>        
        </div>
    </div>
</div>

<div id="body" class="d-flex justify-content-center flex-row" style = "min-height: 80vh;">

    <div id="cart-items" class="d-flex flex-column align-items-center lexend" style = "width: 40vw;">
    </div>


    <div id="summary" class="lexend" style="width: 30vw; height: 45vh; border: 2px solid black; border-radius: 10px; margin-left: 10vw; margin-top: 3vh; padding: 1vw;">
        <h2 style="font-weight: 600;">Cart Summary</h2>

        <div class="d-flex" style="font-weight: 300; line-height: 40px;">
            <div style="width: 30%;">Subtotal:</div>
            <div id ="subTotal" class="d-flex flex-row justify-content-end" style ="width:70%;"></div>
        </div>

        <div class="d-flex" style="font-weight: 300; line-height: 40px;">
            <div style="width: 30%;">Shipping:</div>
            <div class="d-flex flex-row justify-content-end" style ="width:70%;">Calculated next step</div>
        </div>

        <hr>

        <div class="d-flex" style="width: 100%;" >
            <div style="width: 50%;">Have a voucher?</div>
            <div class="d-flex flex-row justify-content-end" style ="width:50%;">Apply in Checkout!</div>
        </div>


        <div class="d-flex" style="font-weight: 600; line-height: 40px; width: 100%;" >
            <div style="width: 30%;">Total (for now):</div>
            <div id ="totalPrice" class="d-flex flex-row justify-content-end" style ="width:70%;"></div>
        </div>

        <a href = "/pepicase/public/checkout" style = "text-decoration: none;">
            <div class="container-fluid d-flex justify-content-center align-items-center btn btn-dark" 
            style="font-weight: 600; height: 7vh; border-radius: 10px;">Continue to checkout</div>
        </a>
            <div style = "width:100%; font-size:13px;">You will only be able to edit your cart here, not during checkout!</div>
        
    </div>

</div>

<script src="/pepicase/public/js/jquery.js"></script>
<script>
    var cart_items = JSON.parse('<?= $cart_items ?>');
    var user = <?= $user_id ?>;
</script>
<script src="/pepicase/public/js/mycart.js"></script>


 <?php include(APPPATH.'views/components/bottom-footer.php'); ?>
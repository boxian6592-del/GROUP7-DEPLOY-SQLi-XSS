<?php include(APPPATH.'views/components/usual-links.php'); ?>
<link rel="stylesheet" href="/pepicase/public/css/checkout.css">
<?php include(APPPATH.'views/components/top-header.php'); ?>



    <div class="wrapper">
        <div class="content-top1">
            <p class="check-out-title1">Checkout</p>
            <div class="steps">
                <p>Address</p>
                <hr>
                <p>Shipping</p>
                <hr>
                <p>Payment</p>
            </div>
        </div>   
        <section class="wrapper-content1">
            <section class="content1">
                <article class="top-content1">
                    <h2>Shipping Information</h2>
                    <table>
                        <tr>
                            <td><input type="text" id ="fname" placeholder="First Name"></td>
                            <td colspan="2"><input type="text" id="lname" placeholder="Last Name"></td>
                        </tr>
                        <tr>
                            <td colspan="3"><input type="text" id="address" placeholder="Address" class="full-width"></td>
                        </tr>
                        <tr>
                            <td colspan="3"><input type="text" id="apartment" placeholder="Apartment, suite, etc (optional)" class="full-width"></td>
                        </tr>
                        <tr>
                            <td><input type="text" id="country" placeholder="Country"></td>
                            <td><input type="text" id="city" placeholder="City"></td>
                            <td><input type="text" id="zipcode" placeholder="Zipcode"></td>
                        </tr>
                        <tr>
                            <td colspan="1"><input type="text" id="area_code" placeholder="Area Code (e.g +84)" class="full-width"></td>
                            <td colspan="2"><input type="text" id="phone" placeholder="Telephone (e.g 0932456783)" class="full-width"></td>
                        </tr>
                    </table>
                    <div class = "d-flex flex-row">
                        <input type="checkbox" id="save-contact">
                        <div style = "margin-left: 10px;">Save contact information</div>
                    </div>
                </article>
                <div class="middle-content1">
                    <h2>Shipping Method</h2>

                    <div class="shipping-option">
                        <div class="d-flex justify-content-center align-items-start" style="width: 5%;">
                            <input type="radio" id="express-ship" name="shipping" style="margin-top: 5.5px;">
                        </div>
                        
                        <div class="d-flex flex-column" style="width: 95%;">
                            <div><strong>Express Shipping</strong></div>
                            <div>Estimated delivery in 3 - 5 Business Days</div>
                        </div>

                    </div>

                    <div class="shipping-option">
                        <div class="d-flex justify-content-center align-items-start" style="width: 5%;">
                            <input type="radio" id="standard-ship" name="shipping" style="margin-top: 5.5px;">
                        </div>
                        
                        <div class="d-flex flex-column" style="width: 95%;">
                            <div><strong>Standard Shipping</strong></div>
                            <div>Estimated delivery in 7 - 9 Business Days</div>
                        </div>

                    </div>

                    <!--
                    <div class="shipping-option">
                        <div class="d-flex flex-row">
                            <input type="radio" id="standard-ship" name="shipping" required>
                            <div><strong>Standard shipping</strong></div>
                        </div>
                        <div style = "margin-left: 25px;">Estimated delivery in 7 - 9 Business Days</div>
                    </div>
                    -->
                </div>
                <div class="bottom-content1">
                    <h2>Payment</h2>
                    <p>All transactions are secure.</p>
                        <table style="width:100%">
                            <tr>
                                <td><button type="button" id="credit-card-btn">VNPay</button></td>
                                <td><button type="button" id="cod-btn">Cash On Delivery</button></td>
                                <td><button type="button" id="momo-btn">Momo</button></td>
                        </table>
                        <!-- <p id="save-card-container" class="hidden"><input type="checkbox" id="save-card"><label for="save-card"> Save card data for future payments</label></p> -->
                </div>
            </section>
            <aside class="sidebar1">
                <h2 align="center">My Cart</h2>
                <div id="item_div" style = "min-height: 10vh;">
            
                </div>
                <div class="cart-summary">
                    <div class="input-discount">
                        <input type="text" id="discount-code"class="form-control" placeholder="Discount Code" aria-label="Discount Code" aria-describedby="button-addon2" style="width:200%; margin-right:20px">
                        <button class="btn btn-dark" type="button" id="apply-discount" style="width:50%">Apply</button>
                    </div>

                    <div id="discount_alert"></div>

                    <div class="d-flex" style="font-weight: 600; line-height: 40px;">
                        <div style="width: 30%;">Subtotal:</div>
                        <div id ="subTotal" class="d-flex flex-row justify-content-end" style ="width:70%;"><?= $total_price ?>$</div>
                    </div>

                    <div class="d-flex" style="font-weight: 600; line-height: 40px;">
                        <div style="width: 30%;">Shipping:</div>
                        <div id ="shipping" class="d-flex flex-row justify-content-end" style ="width:70%;"></div>
                    </div>

                    <div id="discount" class ="d-flex" style="font-weight: 600; line-height: 40px; color: green;">
                    </div>

                    <div class="d-flex" style="font-weight: 600; line-height: 40px;">
                        <div style="width: 30%;">Total:</div>
                        <div id ="Total" class="d-flex flex-row justify-content-end" style ="width:70%; font-size: 30px;"></div>
                    </div>
                </div>
                <button id="buy" class="buy-btn">Buy Now</button>
                <div id = "detail-alert" class ="d-flex justify-content-center align-items-center" style ="color:red;"></div>
            </aside>
        </section>
    </div>
<script src="/pepicase/public/js/jquery.js"></script>
<script>
    var cart_items = JSON.parse('<?= $cart_items ?>');
    console.log(cart_items);
    var user = <?= $user_id ?>;
    var total_price = <?= $total_price ?>;
    var info = <?php if(isset($info)) echo json_encode($info); else echo 'null';?>
</script>
<script src="/pepicase/public/js/checkout.js"></script>
<?php include(APPPATH.'views/components/bottom-footer.php'); ?>
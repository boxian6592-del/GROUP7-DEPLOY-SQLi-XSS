<?php include(APPPATH.'views/components/usual-links.php'); ?>
<?php include(APPPATH.'views/components/top-header.php'); ?>
        
        <div id ="page-body" class ="container-fluid d-flex flex-column align-items-center" style = "margin: 0; padding: 0; min-height: 60vh; margin-bottom: 3vh;">
            <div class = "lexend-tera container-fluid d-flex flex-column justify-content-center align-items-center" style = "height:40vh; background-color:#FFFAE3; font-size:4.5vw; font-weight: 600;">
                Purchases History
                <button class="lexend" style ="height:10vh; width:18vw; font-size:20px; background-color:inherit; border-radius:20px; margin-top: 50px;"><b>SHOP ALL</b></button>
            </div>
        </div>

<script>
    var data = <?php echo json_encode($purchases); ?>;
</script>
<script src = '/pepicase/public/js/jquery.js'></script>
<script src = '/pepicase/public/js/mypurchases.js'></script>


<?php include(APPPATH.'views/components/bottom-footer.php'); ?>
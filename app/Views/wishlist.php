<?php include(APPPATH.'views/components/usual-links.php'); ?>
  <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
<?php include(APPPATH.'views/components/top-header.php'); ?>



<div class = "lexend-tera container-fluid d-flex flex-column justify-content-center align-items-center" style = "height:40vh; background-color:#FFFAE3; font-size:4.5vw; font-weight: 600;">
    <p>Wishlist</p>
    <a href = "/pepicase/public/">
        <button class="btn" style ="height: 10vh; width:18vw; font-size:20px; background-color: inherit; border-radius:20px; margin-top: 30px; border: 1px solid black"><b>SHOP ALL</b></button>
    </a>
</div>


<div id ="page-body" class ="container-fluid d-flex justify-content-center align-items-center" style = "margin: 0; padding: 0; margin-top: 10px; flex-wrap: wrap; min-height: 40vh;">

<?php if(isset($empty)) echo 
'
<div class = "d-flex flex-column justify-content-center align-items-center lexend" style="font-size: 40px;">
    <p style="color:grey;">You have not wishlisted anything!</p>
    <p style="color:grey;"><a href="/pepicase/public/product/">Go out there</a> and wishlist a few, champ!</p>
</div>
';
?>


<!-- 
    
    <div class ="d-flex flex-column" style = "width: 25vw; height: 71vh; margin-left: 3vw; margin-right: 3vw; margin-bottom: 3vw;">
        <div id = "image-box" class ="shadow d-flex justify-content-center align-items-center" style = "height: 80%; width: 100%; background-color:#FFFAE3; border-radius: 20px;">
            <img src ="/pepicase/public/pics/testing.svg" style ="height: 100%; width: auto;">
        </div>
        <div class="d-flex flex-row align-items-center">
            <div style = "font: 400 25px Lexend; width: 85%">Pompompurin My Melody whatever god help me</div>
            <div class = "d-flex justify-content-center align-items-center" style = "width: 15%; height: 100%;">
                <img src = "/pepicase/public/pics/favorite_icon_shaded.svg" style ="width: 60%; height: auto;">
            </div>
        </div>
        <div class = "lexend-tera" style = "font-size: 25px;">
            9.99$
        </div>
    </div>

-->
</div>
<?php 
if(isset($wishlist_array)) echo
'
<script src="/pepicase/public/js/jquery.js"></script>
<script>
    var wishlist_array = JSON.parse(\''. $wishlist_array .'\');
    var user = '.$user_id.';
</script>
<script src = "/pepicase/public/js/wishlist.js"></script>

';
?>

<?php include(APPPATH.'views/components/bottom-footer.php'); ?>
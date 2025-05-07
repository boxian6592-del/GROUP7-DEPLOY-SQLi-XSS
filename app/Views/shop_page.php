<?php include(APPPATH.'views/components/usual-links.php'); ?>
<link rel="stylesheet" href="/pepicase/public/css/shop_page.css">
<?php include(APPPATH.'views/components/top-header.php'); ?>

<img class="banner img-fluid" src="/pepicase/public/pics/shop_banner_2.svg" alt="banner" style="width: 100%; height:auto; max-height: 200px; object-fit:cover;">

<div class="container">
    <aside class="filters-container">
        <header class="filters-header">
            <h2 class="filters-title">Filters</h2>
            <button class="clear-filters" id="clear-filters">Clear filters</button>
        </header>

        <section class="collection-section">
            <h3 class="collection-title">Collection</h3>
            <div class="collection-item">
                <input type="checkbox" class="collection-checkbox" id="2">
                <label for="pompompurin" class="collection-label">Pompompurin</label>
            </div>
            <div class="collection-item">
                <input type="checkbox" class="collection-checkbox" id="3">
                <label for="my-melody" class="collection-label">My Melody</label>
            </div>
            <div class="collection-item">
                <input type="checkbox" class="collection-checkbox" id="1">
                <label for="cinnamonroll" class="collection-label">Cinnamonroll</label>
            </div>
            <div class="collection-item">
                <input type="checkbox" class="collection-checkbox" id="4">
                <label for="pochacco" class="collection-label">Pochacco</label>
            </div>
        </section>

        <hr class="divider">

        <section class="color-patterns-section">
            <h3 class="color-patterns-title">Color & Patterns</h3>
            <div class="color-row">
                <div class="color-item">
                    <div id="1" class="color color-white">
                    <span class="check-mark">&#10003;</span>
                    </div>
                    <span class="color-label">White</span>
                </div>
                <div class="color-item">
                    <div id="2" class="color color-clear">
                    <span class="check-mark">&#10003;</span>
                    </div>
                    <span class="color-label">Clear</span>
                </div>
                <div class="color-item">
                    <div id="3" class="color color-yellow">
                    <span class="check-mark">&#10003;</span>
                    </div>
                    <span class="color-label">Yellow</span>
                </div>
                </div>
                <div class="color-row">
                <div class="color-item">
                    <div id="4" class="color color-blue">
                    <span class="check-mark">&#10003;</span>
                    </div>
                    <span class="color-label">Blue</span>
                </div>
                <div class="color-item">
                    <div id="5" class="color color-pink">
                    <span class="check-mark">&#10003;</span>
                    </div>
                    <span class="color-label">Pink</span>
                </div>
                <div class="color-item">
                    <div id="6" class="color color-multicolor">
                    <span class="check-mark">&#10003;</span>
                    </div>
                    <span class="color-label">Multicolor</span>
                </div>
                </div>

        </section>
        <hr class="divider">
        <button class="apply-filters">APPLY</button>
    </aside>
    <div class="product-list">
        <?php foreach ($products as $product): ?>
            <article class="product-container">
                <figure class="image-wrapper">
                    <a href="<?= base_url('/product/'.$product['ID']); ?>">
                        <img loading="lazy" src="<?php echo ($product['Image']); ?>" class="product-image" alt="<?php echo $product['Name']; ?>" />
                    </a>
                </figure>
                <h2 class="product-name">
                    <a href="<?= base_url('/product/'.$product['ID']); ?>">
                        <?php echo $product['Name']; ?>
                    </a>
                </h2>
                <p class="product-price">
                    <a href="<?= base_url('/product/'.$product['ID']); ?>">
                        $<?php echo $product['Price']; ?> USD
                    </a>
                </p>
            </article>
        <?php endforeach; ?>
    </div>

</div>
<button id="load-more" class="load-more-btn" style = "margin-bottom: 5vh;">Load More Products</button>
<script src = "/pepicase/public/js/shop_page.js"></script>
<?php include(APPPATH.'views/components/bottom-footer.php'); ?>


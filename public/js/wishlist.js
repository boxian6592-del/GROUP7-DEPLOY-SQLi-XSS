var index = 0;
var unfavorited_arr = [];

wishlist_array.forEach(item => {
    index++;
    print_item(item.Name, item.Price, item.Image, item.Product_ID);
});

function toggleFavorite(id) 
{
    var favorite_string = ".favorite_button_" + id.toString();
    var fav_icon = $(favorite_string);
    if (fav_icon.attr("src") === "http://localhost/pepicase/public/pics/favorite_icon_shaded.svg") 
    {
        fav_icon.attr("src", "http://localhost/pepicase/public/pics/favorite_icon.svg")
        unfavorited_arr.push(id);
    }
    else 
    {
        fav_icon.attr("src", "http://localhost/pepicase/public/pics/favorite_icon_shaded.svg");
        var index = unfavorited_arr.indexOf(id);
        unfavorited_arr.splice(index, 1);
    }
};

function print_item(name, price, path, id)
{
    var product_div = $('<div>')
    product_div.html
(`
    <div class="d-flex flex-column" style="width: 25vw; height: 71vh; margin-left: 3vw; margin-right: 3vw; margin-bottom: 3vw;">
        <a href ="/pepicase/public/product/`+ id +`" style = "text-decoration: none; color: black; height: 80%;">
            <div id="image-box" class="shadow d-flex justify-content-center align-items-center" style="height: 100%;width: 100%; background-color:#FFFAE3; border-radius: 20px;">
                <img src="` + path + `" style="height: 100%; width: auto;">
            </div>
        </a>

        <div class="d-flex flex-row align-items-center">
            <a href ="/pepicase/public/product/`+ id +`" style = "text-decoration: none; color: black; width: 85%;">
                <div style="font: 400 25px Lexend;">` + name + `</div>
            </a>

            <div class="d-flex justify-content-center align-items-center" style="width: 15%; height: 100%;">
                <img class ="favorite_button_`+id+`" onclick = "toggleFavorite(`+ id +`)" src="http://localhost/pepicase/public/pics/favorite_icon_shaded.svg" style="width: 60%; height: auto;">
            </div>
        </div>
        <a href ="/pepicase/public/product/`+ id +`" style = "text-decoration: none; color: black;">
            <div class="lexend-tera" style="font-size: 25px; width: fit-content;">`+ price +`$</div>
        </a>
    </div>
`);
    $('#page-body').append(product_div);
}

$(window).on('beforeunload', function() 
{
    if(unfavorited_arr!== 0)
        for(var i = 0; i < unfavorited_arr.length; i++)
        {
            $.post('http://localhost/pepicase/public/product/toggleFavorite', {
                product: unfavorited_arr[i],
                user_id: user,
            })        
        }
});
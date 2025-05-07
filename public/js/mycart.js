var totalPrice = 0;
var item_count = 0;
$(document).ready(function() {
  cart_items.forEach(item =>
    {
      print(item.Product_ID, item.Name, item.Price, item.Image, item.Quantity,item.Size);
    }
  )

  console.log(user);
});

function print(id, name, price, pathing, quantity, model)
{
/*
    var product_div = $('<div>')
    product_div.html(`
<div class="d-flex" style ="width: 100%; height: 20vh; text-align: left; margin-top: 3vh;">

    <div class ="d-flex align-items-center justify-content-center" style = "height: 100%; width: 26%; background-color: #C4C4C4; border-radius: 10px;">
        <a href = "/pepicase/public/product/${id}" style = "height: 95%;">
            <img src="`+ pathing +`" style= "height: 100%; width:auto;">
        </a>
    </div>

    <div style = "margin-left: 3%; width: 71%; height: 100%;">
        <a href="http://localhost/pepicase/product/${id}" style="color: black; text-decoration:none;">
            <div style ="font-size: 25px;"><b>${name}</b></div>
        </a>
        <div style = "font-size: 15px;">Model: ${id}</div>
        <div class ="d-flex flex-row" style = "font-size: 15px;">Quantity: <div>${quantity}</div></div>
        <div class="lexend-tera" style ="font-size: 20px;">
            ${price}$
        </div>
        <div class="d-flex flex-row justify-content-end align-items-end" style="width: 100%; margin-top: 1%;">
            <div style="text-decoration:underline; height: fit-content;">Remove</div>
        </div>
    </div>

</div>`
)*/
    var model_value = model.toString();
    console.log(model_value);
    var quantity_value = parseInt(quantity);
    var price_value = parseFloat(price);
    var product_div = $('<div>').addClass('d-flex product-item')
    .css({
      'width': '100%',
      'height': '25vh',
      'text-align': 'left',
      'margin-top': '3vh'
    });
  
    var image_div = $('<div>').addClass('d-flex align-items-center justify-content-center image-container')
    .css({
      'height': '100%',
      'width': '26%',
      'background-color': '#C4C4C4',
      'border-radius': '10px'
    });
  
    var image_link = $('<a>').attr('href', `http://localhost/pepicase/public/product/${id}`)
    .addClass('image-link')
    .css('height', '95%');
  
    var image = $('<img>').attr('src', pathing)
    .addClass('product-image')
    .css({
      'height': '100%',
      'width': 'auto'
    });
  
    var info_div = $('<div>').addClass('product-info')
    .css({
      'margin-left': '3%',
      'width': '71%',
      'height': '100%',
      'display': 'flex',
      'flex-direction': 'column',
      'justify-content': 'space-between'
    });
  
    var product_name = $('<a>').attr('href', `http://localhost/pepicase/public/product/${id}`)
    .addClass('product-name')
    .css('color', 'black')
    .css('text-decoration', 'none')
    .css('font-size', '22px')
    .text(name);
  
    var model = $('<div>').addClass('product-model')
    .css('font-size', '15px')
    .html(`Model: <strong>${model}</strong> <> Price of each: <strong>${price}$</strong>`);
  
    var quantityDiv = $('<div>').addClass('product-quantity d-flex flex-row')
    .css('font-size', '15px');

    var decreaseBtn = $('<div>').addClass('btn btn-dark').text('-');
    var quantityText = $('<div>').addClass('quantity-text d-flex justify-content-center align-items-center')
      .css('width', '10%')
      .css('border-top', '2px solid black')
      .css('border-bottom', '2px solid black')
      .text(quantity);
    var increaseBtn = $('<div>').addClass('btn btn-dark').text('+');

    quantityDiv.append(increaseBtn, quantityText, decreaseBtn);
  
    var priceDiv = $('<div>').addClass('product-price')
    .addClass('lexend-tera')
    .css('font-size', '20px')
    .text(`${parseFloat(price) * quantity_value}$`);
  
    var remove_link = $('<div>').addClass('remove-link')
    .css({
      'text-decoration': 'underline',
      'height': 'fit-content',
      'margin-top': '1%',
      'margin-left': 'auto'
    })
    .text('Remove');
  
    image_link.append(image);
    image_div.append(image_link);
    info_div.append(product_name, model, quantityDiv, priceDiv);
    info_div.append(remove_link);
    product_div.append(image_div, info_div);

    totalPrice += price_value * quantity_value;

    $('#cart-items').append(product_div);
    $('#totalPrice').text(totalPrice.toFixed(2) + "$");
    $('#subTotal').text(totalPrice.toFixed(2) + "$");

    item_count += quantity_value;
    remove_link.click(function()
    {
        item_count -= quantity_value;
        $('#cart_amount').text(item_count);

        product_div.remove();
        totalPrice -= price_value * quantity_value;

        var protocol = 'delete';
        initiate_post_request(protocol, id, model_value, quantity_value);

        $('#subTotal').text(totalPrice.toFixed(2) + "$");
        $('#totalPrice').text(totalPrice.toFixed(2) + "$");
        if(item_count == 0){
          $('#cart-items').html(`<h1 style = "margin-top: 10vh;">Your cart has been emptied!</h1>`);
        }
    })

    increaseBtn.click(function()
    {
        quantity_value++;
        item_count ++;
        $('#cart_amount').text(item_count);

        totalPrice += price_value;
        var protocol = 'add';
        initiate_post_request(protocol, id, model_value, quantity_value);

        quantityText.text(quantity_value);
        priceDiv.text((price_value * quantity_value) + "$");
        $('#totalPrice').text(totalPrice.toFixed(2) + "$");
        $('#subTotal').text(totalPrice.toFixed(2) + "$");    
    });

    decreaseBtn.click(function()
    {
        quantity_value--;
        item_count --;
        totalPrice -= price_value;
        $('#cart_amount').text(item_count);

        if(quantity_value == 0) 
        {
            var protocol = 'delete';
            initiate_post_request(protocol, id, model_value, quantity_value);
            product_div.remove();
        }
        else
        {
          var protocol = 'remove';
          initiate_post_request(protocol, id, model_value, quantity_value);
            quantityText.text(quantity_value);
            priceDiv.text((price_value * quantity_value) + "$");
        }

        $('#totalPrice').text(totalPrice.toFixed(2) + "$");
        $('#subTotal').text(totalPrice.toFixed(2) + "$");

        if(item_count == 0)
        {
            $('#cart-items').html(`<h1 style = "margin-top: 10vh;">Your cart has been emptied!</h1>`);
        }
    });
}


function initiate_post_request(protocol, id, model, quantity) {
  console.log(protocol.toString());
  console.log(user.toString());
  console.log(id.toString());
  console.log(model.toString());
  console.log(quantity.toString());

  $.ajax({
    type: "POST",
    url: "http://localhost/pepicase/public/user/cart",
    data: {
      protocol: protocol.toString(),
      user_id: user.toString(),
      product_id: id.toString(),
      model: model.toString(),
      quantity: quantity.toString(),
    },
    success: function(response) {
      // Handle the successful response
      console.log(response);
    },
    error: function(xhr, status, error) {
      // Handle the error response
      console.log("can't");
    },
  });
}
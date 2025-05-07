$(document).ready(function() {
    // Giả sử data đã được khai báo và khởi tạo
    if (data && data.length > 0) {
        data.forEach(purchase => {
            let block_purchases;
            if (!document.getElementsByClassName(`purchases-container-id-${purchase.id}`).length) {
                block_purchases = createPurchaseBlock(purchase.id, purchase.order_date); 
                const block = createItemBlock(purchase.id, purchase.name_product, purchase.price, purchase.image, purchase.quantity, purchase.size);
                block_purchases.appendChild(block); 
                document.getElementById("page-body").appendChild(block_purchases); 
            } else {
                block_purchases = document.getElementsByClassName(`purchases-container-id-${purchase.id}`)[0];
                const block = createItemBlock(purchase.id, purchase.name_product, purchase.price, purchase.image, purchase.quantity, purchase.size);
                block_purchases.appendChild(block); 
            }
        });
        
    } else {
        let block = document.createElement("div");
        block.innerHTML = `
            <div style="font-size: 20px;">There are no purchases... Go and shop now!</div>
        `;
        document.getElementById("page-body").appendChild(block);
    }

    
    document.querySelectorAll('.cancel-order-btn').forEach(button => {
        button.addEventListener('click', function() {
            const orderId = this.dataset.orderId;
            deleteOrder(orderId);
        });
    })
    
});

function createItemBlock(id, name, price, pathing, quantity, size) {
    let block = document.createElement("div");
    block.className = "item";
    block.innerHTML = 
    `<div class="d-flex lexend" style="width: 100%; height:25vh;">
        <div class="d-flex align-items-center justify-content-center" style="height: 100%; width: 150px; background-color: #C4C4C4; border-radius: 10px;">
            <img src="http://localhost/${pathing}" style="height: 90%; width:auto;">
        </div>
        <div style="padding-left: 20px;">
            <a href="http://localhost/pepicase/product/${id}" style="color: black; text-decoration:none;">
                <div style="font-size: 25px;"><b>${name}</b></div>
            </a>
            <div>
                <span class="model" style ="font-size: 20px;">Model: ${size} <br></span>
                <span class="quantity" style ="font-size: 20px;">Quantity: ${quantity}</span>
            </div>
            <div class="lexend-tera" style="font-size: 25px;">
                <span class="price">${price}$ <br></span>
            </div>
        </div>
    </div>`;
    return block;
}

function createPurchaseBlock(id, order_date) {
    let block = document.createElement("div");
    let deleteOrder = document.createElement("div");
    let orderDate = new Date(order_date);
    let currentDate = new Date(); 
    let threeDaysLater = new Date(orderDate);
    threeDaysLater.setDate(threeDaysLater.getDate() + 3); 
    
    block.id = `purchases-${id}`;
    block.className = `purchases-container-id-${id} lexend`;
    block.style.cssText = "width: 80%; height: fit-content; padding: 0; margin: 0; margin-top: 5vh;";
    
    let orderInfo = document.createElement("div");
    orderInfo.style.cssText = "display: flex; justify-content: space-between; font-size: 20px;";
    
    let orderDateDiv = document.createElement("div");
    orderDateDiv.className = `order-id-${id} lexend`;
    orderDateDiv.textContent = `Date of order: ${order_date}`;
    
    orderInfo.appendChild(orderDateDiv);
    
    if (threeDaysLater < currentDate) {
        let button = document.createElement("button");
        button.type = "button";
        button.className = "btn btn-primary cancel-order-btn";
        button.setAttribute("data-order-id", id);
        button.textContent = "Hủy đơn hàng";
        deleteOrder.appendChild(button);
        orderInfo.appendChild(deleteOrder);
    }
    block.appendChild(orderInfo);
    block.innerHTML += `<hr style="height: 10px; margin-top:10px; width: 100%;">`;
    return block;
}

function deleteOrder(orderId) {
    console.log(`Hủy đơn hàng với id: ${orderId}`);
    console.log(typeof(orderId));
    fetch(`/purchases/deleteOrder`, {
        method: 'POST',
        headers: ({
            "Access-Control-Allow-Origin": "*",
      "Access-Control-Allow-Methods": "GET,HEAD,POST,OPTIONS", 
      "access-control-allow-credentials": true,
      "access-control-allow-origin": "http://localhost"
           }),
        body: JSON.stringify({ invoice_id: orderId })
    })
    .then(response => {
        if (!response.ok) {
            throw new Error('Network response was not ok');
        }
        return response.json();
    })
    .then(data => {
        console.log(data);
        if (data.success) {
            // Xóa block khi đơn hàng được xóa thành công
            const block = document.getElementById(`purchases-${orderId}`);
            if (block) {
                block.remove();
            }
        } else {
            console.error("Error deleting order:", data.error);
        }
    })
    .catch(error => {
        console.error('There was a problem with the fetch operation:', error);
    });
}
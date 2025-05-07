<?php

namespace App\Controllers;
use App\Models\Product;
use App\Models\CustomSession;
use App\Models\Cart;
use App\Models\User;

class CartController extends BaseController
{
    public function index(){
        $curr_session = new CustomSession(null);
        $result = $curr_session->isSessionSet();
        if($result)
        {
            $curr_cart = new Cart($curr_session->get_id());
            $cart_items = $curr_cart->get();
            if(empty($cart_items)) return view('emptycart');
            else return view('my_cart', ['cart_items' => json_encode($cart_items)]);
        }
        else
        {
            $returnUrl = 'http://localhost/pepicase/public/user/cart';
            $curr_session->set_previous_url((string)$returnUrl);
            return redirect() -> to ('/login');
        } 
    }

    public function checkout(){
        return view('checkout');
    }

    public function process_cart_edit()
    {   
        $user_id = $this->request->getPost('user_id');
        $cart = new Cart($user_id);
        $product_id = $this->request->getPost('product_id');
        $model = $this->request->getPost('model');
        $protocol = $this->request->getPost('protocol');
        if($protocol == 'delete')
        {
            $cart->delete_item($product_id, $model);
        }
        else
        {
            $new_quantity = $this->request->getPost('quantity');
            $cart->updateCartItem($product_id,$model,$new_quantity);
        }
    }
}

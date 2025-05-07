<?php

namespace App\Controllers;
use App\Models\CustomSession;
use App\Models\User;
use App\Models\Cart;

class StaticPageController extends BaseController
{
    // trang để trả view các trang tĩnh
    public function Policy(): string
    {
        return view('policy');
    }
    
    public function AboutUs(): string
    {
        return view('about-us');
    }

    public function TermsOfUse():string
    {
        return view('terms-of-use');
    }

    public function account()
    {
        $curr_session = new CustomSession(null);
        $curr_session->fetch_session_cookie();
        if($curr_session->isSessionSet()) // nếu có
        {
            $user = new User();
            $info = $user->get_info($curr_session->get_id());
            if($info == false) return view ('account');
            else return view('account', [ 'info' => $info ]);
        }
        else return redirect() -> to ('/login');
    }

    public function account_info_update()
    {
        $message = $this->request->getJSON();
        $isFound = $message->isFound;
        $data = $message->data;
        $curr_session = new CustomSession(null);
        $user = new User();
        $user->update_info($curr_session->get_id(), $isFound, $data);
        return $this->response->setJSON([
            'message' => 'Updated information successfully!'
        ]);
    }


    public function checkout_done_cash()
    {
        $curr_session = new CustomSession(null);
        if($curr_session->isSessionSet())
        return view('checkoutDone', ['protocol' => 'cash']);
        else return redirect() -> to ('/');
    }

    public function await_payment()
    {
        $curr_session = new CustomSession(null);
        if($curr_session->isSessionSet())
        return view('await_payment');
        else return redirect() -> to ('/');
    }

    public function delete_user()
    {
        $id = $this->request->getPost('user_id');
        $cart = new Cart($id);
        $cart->clear();
        $user = new User();
        $user->clear($id);
        $curr_session = new CustomSession();
        $curr_session->delete_session_cookie();
        return 'http://localhost/pepicase/public';
    }

    public function purchases()
    {
        $curr_session = new CustomSession(null);
        if ($curr_session->isSessionSet()) {
            $userModel = new User();
            $purchases = $userModel->getPurchases($curr_session->get_id());
            if ($purchases == null) {
                $purchases = [];
                return view('purchases', ['purchases' => $purchases]);
            }
            return view('purchases', ['purchases' => $purchases]);
        }
        else {
            return redirect() -> to ('/login');
        }
    }

    public function deletePurchase() {
        $curr_session = new CustomSession(null);
        if (isset($_POST['invoice_id'])) {
            $invoiceId = $_POST['invoice_id'];
            if ($curr_session->isSessionSet()) {
                $userModel = new User();
                $result = $userModel->deletePurchase($invoiceId);
                if ($result) {
                    return $this->response->setJSON([
                        'success' => true,
                    ]);
                } else {
                    return $this->response->setJSON([
                        'success' => false,
                        'error' => 'Could not delete purchase'
                    ]);
                }
            } else {
                return $this->response->setJSON([
                    'success' => false,
                    'error' => 'Session not set'
                ]);
            }
        } else {
            return $this->response->setJSON([
                'success' => false,
                'error' => 'Missing invoice_id'
            ]);
        }
    }
}


<?php

namespace App\Controllers;
use App\Models\CustomSession;
use App\Models\User;
use App\Models\Session;
use CodeIgniter\Services;
use Google\Service\Oauth2 as Google_Service_Oauth2;
use Facebook\Facebook as Facebook;
 
class LoginController extends BaseController
{
    private $userModel = null;
    private $facebook = null;
    private $fb_helper = null;
    private $googleClient = null;
    function __construct()
    {
        if ($this->facebook === null) {
            require ROOTPATH . 'vendor/autoload.php';
            $this->facebook = new \Facebook\Facebook([
                'app_id' => getenv('FB_APP_ID'),
                'app_secret' => getenv('FB_APP_SECRET'),
                'default_graph_version' => 'v5.1'
            ]);
            $this->fb_helper = $this->facebook->getRedirectLoginHelper();
        }

        $this->userModel = new User();
        if ($this->googleClient === null) {
            $this->googleClient = new \Google_Client();
            $this->googleClient->setClientId(getenv('GOOGLE_CLIENT_ID'));
            $this->googleClient->setClientSecret(getenv('GOOGLE_CLIENT_SECRET')); //for example, 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
            $this->googleClient->setRedirectUri('http://localhost/pepicase/public/loginWithGoogle'); //for example, https://localhost/pepicase/loginWithGoogle
            $this->googleClient->addScope("email");
            $this->googleClient->addScope("profile");
        }
    }
    
    public function login() // hàm chạy khi người dùng ấn "Login"
    {
        // lấy thông tin từ 2 thanh mail vs pass từ login
        $email = $this->request->getPost("email");
        $password = $this->request->getPost("password");

        // luật về email
        $rules = [
            'email' => 'valid_email',
        ];

        $data = [
            'validation' => '',
        ];
        // luật về email

        $user = new User($email, $password); //gọi hàm khởi tạo User
        if($this->validate($rules)) // nếu thỏa luật thì qua chốt 1
        {
            if ($user->id != null) { // nếu sau khởi tạo user->id khác null thì đích thị là user đó
                $new_session = new CustomSession($user->id);
                $result = $new_session->get_previous_url();
                if($result == null) return redirect() -> to('/');
                else return redirect() -> to ($result);
            }
            else // nếu không thì không phải, return view login với data tạm là họ đã sai 
            {
                $message = [
                    'msg' => 'Wrong email or password!',
                ];
                return view('login', $message);
            } 
        }
        
        else // không thỏa luật email thì sẽ báo họ phải nhập email đúng
        {
            $data['validation'] = $this->validator;
            return view('login', $data);
        }
    }

    public function logout() // sau khi ấn nút Log Out
    {
        $current_session = new CustomSession(null); // khởi tạo new CustomSession để chỉ chạy các function
        $current_session->delete_session_cookie(); // gọi hàm xóa session + xóa cookie
        return redirect() -> to('/'); // redirect về trang chủ
    }


    public function index() // khi vào trang login
    {
        $curr_session = new CustomSession(null); // khởi tạo new CustomSession để chỉ chạy các function
        if ($curr_session->isSessionSet()) return redirect() -> to('/'); // nếu đã có session (tương đương đã login) thì redirect về trang chủ
        else 
        {
            $previousUrl = $this->request->getServer('HTTP_REFERER');
            if (strpos($previousUrl, '/pepicase/public/product/') === 0)
            $curr_session->set_previous_url((string)$previousUrl);
            $fb_permission = ['email'];
            $data['fb_btn'] = $this->fb_helper->getLoginUrl('http://localhost/pepicase/public/loginWithFacebook?', $fb_permission);
            $data['googleButton'] = (string)$this->googleClient->createAuthUrl();
            return view('login', $data);
        }
    }

    public function loginWithFB()
	{
		if($this->request->getVar('state')){
			$this->fb_helper->getPersistentDataHandler()->set('state', $this->request->getVar('state'));
		}
		if($this->request->getVar('code')){
			if(session()->get("access_token")){
				$access_token = session()->get('access_token');
			}else{
                $access_token = $this->fb_helper->getAccessToken();
				session()->set("access_token", $access_token);
				$this->facebook->setDefaultAccessToken(session()->get('access_token'));
			}
			$graph_response = $this->facebook->get('/me?fields=name,email', 'EAALAbceFIAwBOx6DlP5P45ykxQZArletHnPbM9h2BcTvH8i1DgyMFukdXS9BZAuF5XIZAMUjDujE8BNNkTMQy5GTMtmi33Tb3YU2CIOuHhTIXSzbbaw0nQQ0I1ek27zPuDzbtRT19szFVD8M5fgCkmyOc16ZCqDup28R8gELUga2h7G5kXJ3MBKWNemPeGyZA6uzLNDyWZAH6CW2kivv8ajcMtPe3qiBTqAhRdmxXg9CuKQx1rRLDR9CqL8ejPsgZDZD');
			$fb_user_info = $graph_response->getGraphUser();
			if(!empty($fb_user_info)){
				$fbdata = array(
					'authid'=>$fb_user_info['id'],
					'profile_pic' => 'http://graph.facebook.com/'.$fb_user_info['id'].'/picture',
					'user_name' => $fb_user_info['name']
				);

				//here you can insert user data in database
				
				session()->set('LoggedUser', $fbdata);
			}
		}else{
			session()->setFlashData('error', 'Something Wrong');
			return redirect()->to(base_url());
		}
		return redirect()->to(base_url().'/');
	}

    public function loginWithGoogle()
    {
        $token = $this->googleClient->fetchAccessTokenWithAuthCode($this->request->getVar('code'));
        if (!isset($token['error'])) {
            $this->googleClient->setAccessToken($token['access_token']);
            
            // Sử dụng CustomSession để lưu AccessToken
            //$customSession = new CustomSession(null);
            //$customSession->set_field("AccessToken", $token['access_token']);
            
            $googleService = new \Google\Service\Oauth2($this->googleClient);
            $data = $googleService->userinfo->get();
            //print_r($data); die;
            $userdata = array();
            $userModel = new User();
            $userModel->create($data['email'], null, $data['id']);
            $userId = $userModel->id;
            
            //$userModel = new User($data['id']);
            //print_r($userId); die; //vượt qua

            if ($userModel->check_if_authorized()) {
                $userdata = [
                    'userid' => $userModel->id,
                    'First_Name' => $data['givenName'],
                    'Last_Name' => $data['familyName'],
                    'email' => $data['email'],
                ];
                $userModel->updateUserData($userdata);
                $customSession = new CustomSession($userId);
                //print_r($customSession->isSessionSet()); die;
                $customSession->set_field("LoggedUserData", $userdata);
                return redirect()->to('/');
            } 
        } else {
            session()->setFlashData("Error", "Something went wrong");
            return redirect()->to('/login');
        }
    }
}
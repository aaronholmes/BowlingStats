<?php

App::uses('Sanitize', 'Utility');
App::import('Vendor', 'oauth2-php/lib/OAuth2');
App::import('Vendor', 'oauth2-php/lib/IOAuth2Storage');
App::import('Vendor', 'oauth2-php/lib/IOAuth2GrantCode');
App::import('Vendor', 'oauth2-php/lib/IOAuth2RefreshTokens');

class UsersController extends AppController {

	var $name = 'Users';
	var $components = array('Email', 'email', 'Auth', 'Session', 'OAuth.OAuth', 'BowlingStatsAPIAuth');
	var $helpers = array("Form", "Session", "Html");
	
	function beforeFilter() {
		$this->Auth->Allow('index', 'register', 'thanks', 'login', 'activate', 'logout');
		$this->OAuth->allow(array('index', 'register', 'thanks', 'login', 'logout', 'activate', 'dashboard', 'bowlers'));
		$this->Auth->autoRedirect = false;
	}

	function index() {
		$this->redirect('/users/login');
	}
	
	function login() {
		if ($this->request->is('post')) {
			if ($this->Auth->login()) {
				if(!$this->User->isUserActivated($this->data['User']['username'])) {
					$this->Session->setFlash('Your account has not been activated yet!');
                    $this->Auth->logout();
                    $this->redirect('/users/login');
				} else if ($this->User->isUserBanned($this->data['User']['username'])) {
					$this->Session->setFlash('Your account has been banned');
                    $this->Auth->logout();
                    $this->redirect('/users/login');
				} else {
					// Successful login
					$this->User->updateLastLogin($this->data['User']['username']);
					$this->redirect($this->Auth->redirect('/users/dashboard'));
				}            
	        } else {
	            $this->Session->setFlash(__('Invalid username or password, try again'));
	        }
		}
	}

	function logout() {
		$this->redirect($this->Auth->logout());
	}

	function register() {
		if ($this->request->is('post')) {
			$data = $this->data;
	    	$data['User']['password'] = $this->Auth->password($this->data['User']['passwrd']);
	    	$this->data = $data;
	       	$this->User->data = Sanitize::clean($this->data);
	        
	       	//Check if unique username
	       	if($this->User->isUniqueUsername($this->User->data['User']['username'])) {
	       		if ($this->User->validates()) {
	           		if ($this->User->save()) {
	       				$this->__sendActivationEmail($this->User->getLastInsertID());
	           			$this->redirect('/users/thanks');
	    			}
	     			else {
	            		$this->Session->setFlash('There has been an error. Please try again later.');
	         		}
	       		} else {
	       			$this->Session->setFlash('Please correct the following errors.');
	       		}
	       	} else {
	       		$this->Session->setFlash('This username has already been taken');
	       	}
	       	$data = $this->data;

	       	unset($data['User']['passwrd']);
	       	unset($data['User']['passwrd2']);

	       	$this->data = $data;
		}
	}
	
	function thanks() {
		
	}
	
	function dashboard() {
		
	}

	function profile() {
		$user = $this->User->getUserInfo($this->Session->read('Auth.User.id'));

		if ($this->request->is('post')) {
			$this->User->id = $this->Session->read('Auth.User.id');
			if ($this->User->save($this->request->data)) {
	            // Set a session flash message and redirect.
	            $this->Session->setFlash("Your Profile has been saved!");
	            $this->redirect('/users/profile');
	        }
		} 
		
		$this->set('user', $user);
	}

	function bowlers() {
		$location = "/api/1/bowlers.xml";
		$response = $this->BowlingStatsAPIAuth->fetchData($location);
		try {
		    $bowlers = Xml::toArray(Xml::build($response->body));
		    if(!empty($bowlers['response']['data'])) {
		    	$this->set('bowlers', $bowlers['response']['data']['bowlers']['bowler']);
		    }
		} catch (XmlException $e) {
		    $bowlers = null;
		    $this->set('bowlers', $bowlers);	
		}
		
		
					
	}

	/**
	 * Activates a user account from an incoming link
	 *
	 *  @param Int $user_id User.id to activate
	 *  @param String $in_hash Incoming Activation Hash from the email
	*/
	function activate($user_id = null, $in_hash = null) {
		$this->User->id = $user_id;

		if ($this->User->exists() && ($in_hash == $this->User->getActivationHash()))
		{
	        if($this->User->activateUser($this->User->id)) {

	        	// Create the OAuth api account and keys
	        	$client = $this->OAuth->Client->add(
	        			$data['Client']['redirect_uri'] = 'http://www.bowlingstats.com',
	        			$data['Client']['user_id'] = $user_id);

	        	App::uses('HttpSocket', 'Network/Http');
				$http = new HttpSocket();

				$authCode = $this->OAuth->createAuthCode($client['Client']['client_id'], $user_id, $data['Client']['redirect_uri'], $scope = NULL);
				
				$accessToken = $http->get('www.bowlingstats.com/oauth/token?grant_type=authorization_code&code='.$authCode.'&client_id='.$client['Client']['client_id'].'&client_secret='.$client['Client']['client_secret']);
				$data = json_decode($accessToken->body, true);

				$userInfo['User'] = array(
					'access_token' => $data['access_token'],
					'auth_code' => $authCode,
					'refresh_token' => $data['refresh_token'],
					'client_id' => $client['Client']['client_id'],
					'client_secret' => $client['Client']['client_secret']);

				$this->User->save($userInfo);
				
	        	// Let the user know they can now log in!
	        	$this->Session->setFlash('Your account has been activated, please log in below');
	        	$this->redirect('/users/login');
	        }
		}

		// Activation failed, render ‘/views/user/activate.ctp’ which should tell the user.
		$this->set('error', "We're sorry, your account could not be validated. Please ensure you have entered the correct link.");
	}
	
	function __sendActivationEmail($user_id)  {
		
		$user = $this->User->getUserInfo($user_id);

	    if ($user === false) {
	            debug(__METHOD__." failed to retrieve User data for user.id: {$user_id}");
	            return false;
	    }
	    
	    // Set data for the "view" of the Email
	    $this->set('activate_url', 'http://' . env('SERVER_NAME') . '/users/activate/' . $user['User']['id'] . '/' . $this->User->getActivationHash());
	    $this->set('username', $this->data['User']['username']);

	    $this->Email->to = $user['User']['email'];
	    $this->Email->subject = env('SERVER_NAME') . ' – Please confirm your email address';
	    $this->Email->from = 'noreply@' . env('SERVER_NAME');
	    $this->Email->template = 'user_confirm';
	    $this->Email->sendAs = 'text';   // you probably want to use both :)   
	    return $this->Email->send();
	}

}
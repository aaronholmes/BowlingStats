<?php

App::uses('Sanitize', 'Utility');


class UsersController extends AppController {

	var $name = 'Users';
	var $components = array('Email', 'email', 'Auth', 'Session');
	var $helpers = array("Form", "Session", "Html");
	
	function beforeFilter() {
		$this->Auth->Allow('index', 'register', 'thanks', 'login', 'activate');
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

		$login['user'] ='a';
		$login['pass'] ='b';
		
		$c = curl_init('http://www.bowlingstats.dev/api/1/bowlers.xml');

		curl_setopt($c, CURLOPT_RETURNTRANSFER, 1);
		//curl_setopt($c, CURLOPT_USERPWD, $login['user'] . ':' . $login['pass']);
		//curl_setopt($c, CURLOPT_HTTPAUTH, CURLAUTH_DIGEST);
		//curl_setopt($c, CURLOPT_SSL_VERIFYPEER, false);
		//curl_setopt($c, CURLOPT_FOLLOWLOCATION, true);

		$response = curl_exec($c);
		$info = curl_getinfo($c);

print_r($response);
//print_r($info);
		curl_close($c);

		if($info['http_code'] == $this->_http_codes['OK']) {
		    //success
		    if($this->_format == 'xml')
		        $response = Xml::toArray(Xml::build($response));
		    else//JSON
		        $response = json_decode($response);
		        return $response['response']['data'];
		} else if($info['http_code'] == $this->_http_codes['Unauthorized']) {
		    return false;
		} else {
		    return null;
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
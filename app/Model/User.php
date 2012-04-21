<?php
class User extends AppModel {
	var $name = "User";
	
	var $validate = array(
		'username' => array
		(
			'alphanumeric' => array
				(
					'rule' => 'alphaNumeric',
					'message' => 'Only the letters A-z and digits 0-9 are allowed',
                ),
          	'length' => array
              	(
                 	'rule' => array('between', 4, 20),
                   	'message' => "Your username must be between 4 and 20 characters long",
           		),
     	 ),
         'passwrd' => array
         (
         	'passlength' => array (
         		'rule' => array('minLength', 6),
            	'message' => 'Your password must be at least 6 characters long'
         	),
         	'passconfirm' => array('rule' =>'checkpasswords','message' => 'Passwords do not match')
           	
         ),
         'passwrd2' => array
         (
         	'passlength' => array (
         		'rule' => array('minLength', 6),
            	'message' => 'Your password must be at least 6 characters long'
         	)
           	
         ),
         'email' => array (
         		'rule' => array('email'),
            	'message' => 'Please provide a valid email address.'
         	)
        );
	
	function checkpasswords()
	{
	   return strcmp($this->data['User']['passwrd'],$this->data['User']['passwrd2']) == 0;
	}  
      
	public function isUserBanned($username) {
		return $this->field('banned', array('User.username' => $username));
	}
	
	public function isUserActivated($username) {
		return $this->field('activated', array('User.username' => $username));
	}
	
	public function updateLastLogin($username, $time=null) {	
		$this->data = $this->findByUsername($username);
		$time ? $this->data['User']['lastlogin'] = $time : $this->data['User']['lastlogin'] = date('Y-m-d H:i:s');	
		
		return $this->save($this->data);
	}
	
	public function isUniqueUsername($username) {
		$user = $this->find('count', array('conditions' => array('User.username' => $username)));
		
		return $user == 0;
	}

	/**
	* Creates an activation hash for the current user.
	*
	*      @param Void
	*      @return String activation hash.
	*/
	function getActivationHash()
	{
		if (!isset($this->id)) {
	    	return false;
	  	}
		return substr(Security::hash(Configure::read('Security.salt') . $this->field('created') . date('Ymd')), 0, 8);
	}

	function activateUser($userID) {
		// Update the active flag in the database
		$this->read(null, $userID);
		$this->set('activated', 1);
		if($this->save()) {
			return true;
		}
	}

	function getUserInfo($userID) {
		return $this->find('first', array('conditions' => array('User.id' => $userID)));
	}
}
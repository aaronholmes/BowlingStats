<?php

class BowlingStatsAPIAuthComponent extends Component {

	var $components = array('Auth', 'Session', 'OAuth.OAuth');
	var $helpers = array("Session");

    public function fetchData($location) {

    	//Load the user model, apparently this may be bad form
    	//TODO: see if theres a better way to use models / if component should not be used

    	$this->User = ClassRegistry::init('User');

    	$user_id = $this->Session->read('Auth.User.id');
		$user = $this->User->find('first', array(
			'conditions' => array(
				'id' => $user_id)));

		// Fetch data from bowling stats API
		App::uses('HttpSocket', 'Network/Http');
		$http = new HttpSocket();
		
		$response = $http->get('www.bowlingstats.com'.$location.'?access_token='.$user['User']['access_token']);

		$data = json_decode($response->body, true);

		// Refresh the access token using the refresh token and resend request
		if($data['error_description'] == "The access token provided has expired.") {
			$accessToken = $http->get('www.bowlingstats.com/oauth/token?grant_type=refresh_token&refresh_token='.$user['User']['refresh_token'].'&client_id='.$user['User']['client_id'].'&client_secret='.$user['User']['client_secret']);

			$data = json_decode($accessToken->body, true);

			$this->User->id = $user_id;
			$userInfo['User'] = array(
				'access_token' => $data['access_token'],
				'refresh_token' => $data['refresh_token']);

			$this->User->save($userInfo);

			$response = $http->get('www.bowlingstats.com'.$location.'?access_token='.$data['access_token']);
		}
		return $response;
    }
}
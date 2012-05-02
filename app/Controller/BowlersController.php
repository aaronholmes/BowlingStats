<?php

/**
 * Bowlers Controller
 *
 * This controller holds rest API functionality for bowler objects
 *
 * Here are the tags:
 * 
 * @package     bowlingstats-api
 * @access      public
 * @author      Aaron Holmes <aholmes@pureguru-software.com>
 * @copyright   Aaron Holmes April 25th, 2012
 * @version     1.0
 */

class BowlersController extends AppController {
	
	public $components = array('RequestHandler', 'Auth', 'Session', 'OAuth.OAuth');
    public $helpers = array("Session");
    public $uses = array("User", "Bowler");
	
    function beforeFilter() {
        $this->Auth->Allow('*');
        $this->Auth->autoRedirect = false;
    }

    /**
     * Lists all bowlers
     *
     * @return  xml List of bowlers
     */
	public function index() {
        //$this->layout = false;
        //TODO: Update api logs
        //$this->User->id = $user['id'];
        //$this->User->saveField('last_login', date('Y-m-d H:i:s'));

        $user = $this->User->find('first', array(
            'conditions' => array(
                'access_token' => $_REQUEST['access_token'])));

        $bowlers = $this->Bowler->find('all', array(
            'conditions' => array(
                'user_id' => $user['User']['id'])));
    
        if(!empty($bowlers)) {
            foreach($bowlers as $bowler) {
                $bowlerData['bowlers']['bowler'][] = $bowler['Bowler'];
            }
        } else {
            $bowlerData = '';
        }
        

        $this->set('response', array(
            'response' => array(
                'code' => 'users_auth_success',
                'message' => 'User has passed authentication',
                'data' => $bowlerData
            )
        ));
        //will serialize to xml or json based on extension
        $this->set('_serialize', 'response');
    }

    /**
     * Lists a bowler with a given id
     *
     * @var     int Bowler ID
     * @return  xml Bowler
     */
    public function view($id) {
    	$this->layout = false;
        $bowler = $this->Bowler->findById($id);

        $bowlerData['bowlers']['bowler'][] = $bowler['Bowler'];

        $this->set('bowlerData', $bowlerData);
    }

    public function add() {
        $this->layout = false;
        //var_dump($_REQUEST);
        //echo "test";

print_r($this->request->data);
unset($this->request->data['User']['Profile Image:']);
print_r($this->request->data['User']);

        if ($this->Bowler->save($this->request->data)) {
            $this->set('response', array('success' => true));
        } else {
            $this->set('response', array('success' => false));
        }

        $this->set('response', array('success' => true));
        $this->set('_serialize', 'response');
    }
}
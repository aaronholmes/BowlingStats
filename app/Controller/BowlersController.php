<?php

/**
 * Bowlers Controller
 *
 * This controller holds rest API functionality for bowler objects
 *
 * Here are the tags:
 *
 * @package     bowlingstats-admin
 * @access      public
 * @author      Aaron Holmes <aholmes@pureguru-software.com>
 * @copyright   Aaron Holmes April 25th, 2012
 * @version     1.0
 */

//App::uses('DigestAuthenticate');

class BowlersController extends AppController {
	
	public $components = array('RequestHandler', 'Auth', 'Session', 'OAuth.OAuth');
    public $helpers = array("Session");
	

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

        //$this->Auth->loginAction('/bowlers/login');

        /*
$user = $this->Auth->user();
if empty()
        */


            //TODO: Update api logs
            //$this->User->id = $user['id'];
            //$this->User->saveField('last_login', date('Y-m-d H:i:s'));
            $bowlers = $this->Bowler->find('all');

            foreach($bowlers as $bowler) {
                $bowlerData['bowlers']['bowler'][] = $bowler['Bowler'];
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

        /*
		$this->layout = false;
        $bowlers = $this->Bowler->find('all');

        foreach($bowlers as $bowler) {
        	$bowlerData['bowlers']['bowler'][] = $bowler['Bowler'];
        }

        $this->set('bowlerData', $bowlerData);
        */
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
}
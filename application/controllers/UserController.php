<?php

class UserController extends Zend_Controller_Action
{

    public function init()
    {
        /* Initialize action controller here */

    }

    // ------------------------------------------------------------------------

    /*
     * Index Page
     *
     * @author		Jacy Gao
     * @return		void
     * @param	    void
     */

    public function indexAction()
    {
        $this->session = new Zend_Session_Namespace('user_session');
        if($this->session->username || $this->session->userId)
        {
            $this->redirect('/calendar/');
        }
        $this->redirect('/user/login');
    }

    // ------------------------------------------------------------------------

    /*
     * Login Page
     *
     * @author		Jacy Gao
     * @return		void
     * @param	    void
     */

    public function loginAction()
    {
        $request = $this->getRequest();
        $form = new Application_Form_Login();

        if ($this->getRequest()->isPost()) {

            if ($form->isValid($request->getPost())) {
                $user = new Application_Model_User($form->getValues());

                $mapper  = new Application_Model_UserMapper();
                $user = $mapper->find($form->getValues()['username'],$form->getValues()['password']);
                if(!$user)
                {
                    exit('username/password is incorrect!');
                }
                else
                {
                    // Initialize user session
                    $userSession = new Zend_Session_Namespace('user_session');
                    $userSession->userId = $user['id'];
                    $userSession->username = $user['name'];

                    // Insert session record to dababase
                    $sessionMapper  = new Application_Model_SessionMapper();
                    $insertSession = $sessionMapper->save($user['id'],$user['name']);

                    $this->redirect('/calendar/');
                }
            }
            else
            {
                exit('The data entered is invalid!');
            }
        }

        $this->view->form = $form;
    }

    // ------------------------------------------------------------------------

    /*
     * Logout
     *
     * @author		Jacy Gao
     * @return		void
     * @param	    void
     */

    public function logoutAction()
    {
        $this->session = new Zend_Session_Namespace('user_session');
        unset($this->session->username);
        unset($this->session->userId);

        $this->redirect('/calendar/');
    }

    // ------------------------------------------------------------------------

    /*
     * Export to ics
     *
     * @author		Jacy Gao
     * @return		void
     * @param	    void
     */

    public function exportAction()
    {
        $this->_helper->layout()->disableLayout();

        // Get user ID from URL
        $uri = Zend_Controller_Front::getInstance()->getRequest()->getRequestUri();
        $filename = substr( $uri, strrpos( $uri, '/' ) + 1 );
        $userid = preg_replace('/\\.[^.\\s]{3,4}$/', '', $filename);

        $cache = Zend_Registry::get('cache');

        // Define ics file header

        header("Content-type: text/x-vcalendar");
        header("Cache-Control: max-age=7200, private, must-revalidate");
        header('Content-Disposition: attachment; filename="' . $userid . '.ics"');

        $appointment = new Application_Model_Appointment();
        $calendar = new Application_Model_Calendar();

        $calendarMapper  = new Application_Model_CalendarMapper();
        $appointmentMapper = new Application_Model_AppointmentMapper();

        $calendar = unserialize($cache->load("user".$userid));
        $appointment = unserialize($cache->load("appointment"));

        //$appointment = $appointmentMapper->fetchAll('export');
        //$calendar = $calendarMapper -> fetchByUserId($userid);

        $exportData = array();


        foreach($calendar as $c)
        {
            foreach($appointment as $a)
            {
                if($c['id'] == $a['cid'])
                {
                    $exportData[] = $a;
                }
            }
        }

        $this->view->data = $exportData;
    }

}

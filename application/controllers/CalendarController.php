<?php

class CalendarController extends Zend_Controller_Action
{

    public $_cache;
    public $_userId;
    public $_userName;

    public function init()
    {
        /* Initialize action controller here */

        // Check if user session has been set-up
        $this->session = new Zend_Session_Namespace('user_session');
        if(!$this->session->username || !$this->session->userId)
        {
            $this->redirect('/user/login');
        }

        $this->_userId = $this->session->userId;
        $this->_UserName = $this->session->username;
        $this->_cache = Zend_Registry::get('cache');
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
        $userid = $this->session->userId;

        $calendar = new Application_Model_CalendarMapper();
        $allAppointment = new Application_Model_AppointmentMapper();

        if(!$result = $this->_cache->load("user".$this->_userId))
        {
            $this->_cache->save(serialize($calendar->fetchAll()),"user".$this->_userId);
        }
        if(!$result = $this->_cache->load("appointment"))
        {
            $data = serialize($allAppointment->fetchAll('export'));
            $this->_cache->save($data, "appointment");
        }

        $this->view->entries = unserialize($this->_cache->load("user".$this->_userId));
        $this->view->userid = $userid;
    }

    // ------------------------------------------------------------------------

    /*
     * Add Calendar
     *
     * @author		Jacy Gao
     * @return		void
     * @param	    void
     */

    public function addAction()
    {

        $request = $this->getRequest();
        $form    = new Application_Form_Calendar();

        if ($this->getRequest()->isPost()) {
            if ($form->isValid($request->getPost())) {

                // Insert new document to Calendar collection
                $calendar = new Application_Model_Calendar($form->getValues());
                $mapper  = new Application_Model_CalendarMapper();
                $mapper->save($calendar);

                // clean cache
                $this->_cache->remove('user'.$this->_userId);

                return $this->_helper->redirector('index');
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
     * Delete Calendar
     *
     * @author		Jacy Gao
     * @return		void
     * @param	    void
     */

    public function deleteAction()
    {
        $request = $this->getRequest();

        $mapper  = new Application_Model_CalendarMapper();
        $mapper->remove($request->id);

        // clean cache
        $this->_cache->remove('user'.$this->_userId);

        return $this->_helper->redirector('index');
    }

    // ------------------------------------------------------------------------

    /*
     * Edit Calendar
     *
     * @author		Jacy Gao
     * @return		void
     * @param	    void
     */

    public function editAction()
    {
        $request = $this->getRequest();

        $mapper  = new Application_Model_CalendarMapper();

        $data = $mapper->fetch($request->id);

        $form = new Application_Form_Calendar();

        if ($this->getRequest()->isPost()) {

            $values = array();
            $values['id'] = $request->id;
            $values['name'] = $request->getPost()['name'];

            $calendar = new Application_Model_Calendar($values);
            $mapper  = new Application_Model_CalendarMapper();
            $mapper->save($calendar);

            // clean cache
            $this->_cache->remove('user'.$this->_userId);

            return $this->_helper->redirector('index');

        }

        $this->view->form = $form;
        $this->view->data = $data;
    }

    // ------------------------------------------------------------------------

    /*
     * Enter Calendar
     *
     * @author		Jacy Gao
     * @return		void
     * @param	    void
     */

    public function entryAction()
    {
        $request = $this->getRequest();
        $calendar = new Zend_Session_Namespace('user_session');
        $calendar->id = $request->id;

        $this->redirect('/appointment/');
    }

    // ------------------------------------------------------------------------

}








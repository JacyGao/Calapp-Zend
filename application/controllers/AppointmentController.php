<?php

class AppointmentController extends Zend_Controller_Action
{

    public $_cache;
    public $_cid;

    public function init()
    {
        /* Initialize action controller here */

        // Check if Calendar session has been set-up
        $this->session = new Zend_Session_Namespace('user_session');
        if(!$this->session->id)

        $this->redirect('/calendar/');

        $this->_cid = $this->session->id;
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
        $appointment = new Application_Model_AppointmentMapper();

        if(!$result = $this->_cache->load("calendar".$this->_cid))
        {
            $data = serialize($appointment->fetchAll());
            $this->_cache->save($data, "calendar".$this->_cid);
        }

        if(!$result = $this->_cache->load("appointment"))
        {
            $data = serialize($appointment->fetchAll('export'));
            $this->_cache->save($data, "appointment");
        }

        $this->view->entries = unserialize($this->_cache->load("calendar".$this->_cid));
    }

    // ------------------------------------------------------------------------

    /*
     * Add Appointment
     *
     * @author		Jacy Gao
     * @return		void
     * @param	    void
     */

    public function addAction()
    {
        $request = $this->getRequest();
        $form    = new Application_Form_Appointment();

        if ($this->getRequest()->isPost()) {
            if ($form->isValid($request->getPost())) {

                // Retrive Post Value array
                $values = $form->getValues();

                // Date Time Validation
                if($values['startDate'] > $values['endDate'])
                {
                    exit('The data entered is invalid!');
                }
                if($values['startDate'] == $values['endDate'])
                {
                    if($values['startTime'] >= $values['endTime'])
                    {
                        exit('The data entered is invalid!');
                    }
                }

                // success
                $appointment = new Application_Model_Appointment($values);
                $mapper  = new Application_Model_AppointmentMapper();
                $mapper->save($appointment);

                // clean cache
                $this->_cache->remove("calendar".$this->_cid);
                $this->_cache->remove("appointment");

                return $this->_helper->redirector('index');
            }
            else
            {
                // fail, return error
                exit('The data entered is invalid!');
            }
        }

        $this->view->form = $form;
    }

    // ------------------------------------------------------------------------

    /*
     * Delete Appointment
     *
     * @author		Jacy Gao
     * @return		void
     * @param	    void
     */

    public function deleteAction()
    {
        $request = $this->getRequest();

        $mapper  = new Application_Model_AppointmentMapper();
        $mapper->remove($request->id);

        // clean cache
        $this->_cache->remove("calendar".$this->_cid);
        $this->_cache->remove("appointment");

        return $this->_helper->redirector('index');
    }

    // ------------------------------------------------------------------------

    /*
     * Edit Appointment
     *
     * @author		Jacy Gao
     * @return		void
     * @param	    void
     */

    public function editAction()
    {
        $request = $this->getRequest();

        $mapper  = new Application_Model_AppointmentMapper();

        $data = $mapper->fetch($request->id);
        $form = new Application_Form_Appointment();

        if ($this->getRequest()->isPost()) {

            $values = array();
            $values['id'] = $request->id;
            $values['title'] = $request->getPost()['title'];
            $values['location'] = $request->getPost()['location'];
            $values['startDate'] = $request->getPost()['startDate'];
            $values['startTime'] = $request->getPost()['startTime'];
            $values['endDate'] = $request->getPost()['endDate'];
            $values['endTime'] = $request->getPost()['endTime'];
            $values['notes'] = $request->getPost()['notes'];

            $appointment = new Application_Model_Appointment($values);
            $mapper  = new Application_Model_AppointmentMapper();
            $mapper->save($appointment);

            // clean cache
            $this->_cache->remove("calendar".$this->_cid);
            $this->_cache->remove("appointment");

            return $this->_helper->redirector('index');

        }
        $this->view->form = $form;
        $this->view->data = $data;

    }

    // ------------------------------------------------------------------------

    /*
     * Exit the current Calendar
     *
     * @author		Jacy Gao
     * @return		void
     * @param	    void
     */

    public function exitAction()
    {
        $this->session = new Zend_Session_Namespace('user_session');
        unset($this->session->id);

        $this->redirect('/calendar/');
    }

    // ------------------------------------------------------------------------
}
<?php

class AppointmentController extends Zend_Controller_Action
{

    public function init()
    {
        /* Initialize action controller here */
        $this->session = new Zend_Session_Namespace('user_session');
        if(!$this->session->id)

        $this->_redirect('/calendar/');
    }

    public function indexAction()
    {
        $appointment = new Application_Model_AppointmentMapper();
        $this->view->entries = $appointment->fetchAll();
    }

    public function addAction()
    {
        $request = $this->getRequest();
        $form    = new Application_Form_Appointment();

        if ($this->getRequest()->isPost()) {
            if ($form->isValid($request->getPost())) {

                $appointment = new Application_Model_Appointment($form->getValues());
                $mapper  = new Application_Model_AppointmentMapper();
                $mapper->save($appointment);
                return $this->_helper->redirector('index');
            }
            else
            {
                exit('The data entered is invalid!');
            }
        }

        $this->view->form = $form;
    }

    public function deleteAction()
    {
        $request = $this->getRequest();

        $mapper  = new Application_Model_AppointmentMapper();
        $mapper->remove($request->id);
        return $this->_helper->redirector('index');
    }

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

            return $this->_helper->redirector('index');

        }
        $this->view->form = $form;
        $this->view->data = $data;

    }

    public function exitAction()
    {
        $this->session = new Zend_Session_Namespace('user_session');
        unset($this->session->id);

        $this->_redirect('/calendar/');
    }
}
<?php

class CalendarController extends Zend_Controller_Action
{

    public function init()
    {
        /* Initialize action controller here */

    }

    public function indexAction()
    {
        $calendar = new Application_Model_CalendarMapper();
        $this->view->entries = $calendar->fetchAll();
    }

    public function addAction()
    {

        $request = $this->getRequest();
        $form    = new Application_Form_Calendar();

        if ($this->getRequest()->isPost()) {
            if ($form->isValid($request->getPost())) {
                $calendar = new Application_Model_Calendar($form->getValues());
                $mapper  = new Application_Model_CalendarMapper();
                $mapper->save($calendar);
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

        $mapper  = new Application_Model_CalendarMapper();
        $mapper->remove($request->id);
        return $this->_helper->redirector('index');
    }

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

            return $this->_helper->redirector('index');

        }

        $this->view->form = $form;
        $this->view->data = $data;
    }

    public function entryAction()
    {
        $request = $this->getRequest();
        $calendar = new Zend_Session_Namespace('user_session');
        $calendar->id = $request->id;

        $this->_redirect('/appointment/');
    }

}








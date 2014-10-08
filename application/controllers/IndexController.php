<?php

class IndexController extends Zend_Controller_Action
{

    public function init()
    {
        /* Initialize action controller here */
        $this->session = new Zend_Session_Namespace('user_session');
        if(!$this->session->username || !$this->session->userId)
        {
            $this->redirect('/user/login');
        }

        $this->redirect('/calendar/');
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
        // action body
    }

    // ------------------------------------------------------------------------

}






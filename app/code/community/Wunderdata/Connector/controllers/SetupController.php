<?php

class Wunderdata_Connector_SetupController extends Mage_Adminhtml_Controller_Action
{
    public function indexAction()
    {
        $model = Mage::getModel('wunderdata_connector/setup');
        $report = $model->checkEnvironment();
        $this->loadLayout();
        $this->renderLayout();
    }

    public function apiAction()
    {
        $this->loadLayout();

        if ($this->getRequest()->isPost()) {
            $apikey = $this->getRequest()->getPost('apikey');
            if (!empty($apikey)) {
                Mage::getModel('core/config')->saveConfig('wunderdata/api/key', $apikey);
                $this->_redirect('*/*/env');
                return;
            }
            $this->getLayout()->getBlock('wunderdata_setup_api')->setData('error', 'empty apikey');
        }

        $this->renderLayout();
    }

    public function envAction()
    {
        $this->loadLayout();

        $model = Mage::getModel('wunderdata_connector/setup');
        $report = $model->checkEnvironment();
        $this->getLayout()->getBlock('wunderdata_setup_env')->setData('report', $report);

        $this->renderLayout();
    }
}
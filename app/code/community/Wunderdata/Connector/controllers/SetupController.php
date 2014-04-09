<?php

class Wunderdata_Connector_SetupController extends Mage_Adminhtml_Controller_Action
{
    public function indexAction()
    {
        // firewall test
        $httpClient = new Zend_Http_Client('https://etl.wunderdata.com/ping', array(
            'timeout' => 6
        ));
        $reponse = $httpClient->request(Zend_Http_Client::GET);
        if ($reponse->isSuccessful() && $reponse->getBody() == 'pong') {
            // redirect to api key
        }

        // try to access google
        $httpClient->setUri('https://www.google.de');
        $response = $httpClient->request(Zend_Http_Client::GET);
        if ($reponse->isSuccessful() || $reponse->isRedirect()) {
            // problem on our side. retry button or contact us
        } else {
            // firewall/vpn problem
        }
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
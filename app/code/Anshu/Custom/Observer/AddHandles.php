<?php

namespace Anshu\Custom\Observer;

use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;
use Magento\Customer\Model\Session as CustomerSession;

class AddHandles implements ObserverInterface
{
    protected $_customerSession;

    public function __construct(CustomerSession $_customerSession)
    {
        $this->_customerSession = $_customerSession;
    }

    public function execute(Observer $observer)
    {
        $layout = $observer->getEvent()->getLayout();

        if (!$this->_customerSession->isLoggedIn())
        {
            $layout->getUpdate()->addHandle('customer_logged_out');
        }
    }
}
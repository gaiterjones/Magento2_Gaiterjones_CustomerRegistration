<?php
/**
 * Copyright ©  All rights reserved.
 * See COPYING.txt for license details.
 */
declare(strict_types=1);

namespace Gaiterjones\CustomerRegistration\Observer;

use Magento\Framework\Event\Observer as EventObserver;
use Magento\Framework\Event\ObserverInterface;

class CustomerRegister implements ObserverInterface
{
    /**
     * @var \Magento\Customer\Model\CustomerFactory
     */
    protected $_customerFactory;
    protected $_customerRepository;

    function __construct(
        \Magento\Customer\Model\CustomerFactory $customerFactory,
        \Psr\Log\LoggerInterface $logger
    ) {
        $this->_customerFactory = $customerFactory;
        $this->_logger = $logger;
    }


    public function execute(EventObserver  $observer)
    {
        $event = $observer->getEvent();
        $customerData = $event->getCustomer();

        // DO SOMETHING COOL HERE
        // 
        $_enabled=false;
        if ($_enabled)
        {
            if($_POST['gj_custom_text_attribute']) {
                $customer = $this->_customerFactory->create()->load($customerData->getId());
                $customer->setData('gj_custom_text_attribute', $_POST['gj_custom_text_attribute']);
                $customer->save();
                $this->_logger->info("Observer CustomerRegister saved gj_custom_text_attribute : ". $_POST['gj_custom_text_attribute']);
            } else {
                $this->_logger->info("Observer CustomerRegister gj_custom_text_attribute NOT FOUND");
            }
        }
    }
}

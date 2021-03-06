<?php
namespace Magento\Quote\Model\QuoteManagement;

/**
 * Interceptor class for @see \Magento\Quote\Model\QuoteManagement
 */
class Interceptor extends \Magento\Quote\Model\QuoteManagement implements \Magento\Framework\Interception\InterceptorInterface
{
    use \Magento\Framework\Interception\Interceptor;

    public function __construct(\Magento\Framework\Event\ManagerInterface $eventManager, \Magento\Quote\Model\QuoteValidator $quoteValidator, \Magento\Sales\Api\Data\OrderInterfaceFactory $orderFactory, \Magento\Sales\Api\OrderManagementInterface $orderManagement, \Magento\Quote\Model\CustomerManagement $customerManagement, \Magento\Quote\Model\Quote\Address\ToOrder $quoteAddressToOrder, \Magento\Quote\Model\Quote\Address\ToOrderAddress $quoteAddressToOrderAddress, \Magento\Quote\Model\Quote\Item\ToOrderItem $quoteItemToOrderItem, \Magento\Quote\Model\Quote\Payment\ToOrderPayment $quotePaymentToOrderPayment, \Magento\Authorization\Model\UserContextInterface $userContext, \Magento\Quote\Api\CartRepositoryInterface $quoteRepository, \Magento\Customer\Api\CustomerRepositoryInterface $customerRepository, \Magento\Customer\Model\CustomerFactory $customerModelFactory, \Magento\Quote\Model\Quote\AddressFactory $quoteAddressFactory, \Magento\Framework\Api\DataObjectHelper $dataObjectHelper, \Magento\Store\Model\StoreManagerInterface $storeManager, \Magento\Checkout\Model\Session $checkoutSession, \Magento\Customer\Model\Session $customerSession, \Magento\Customer\Api\AccountManagementInterface $accountManagement, \Magento\Quote\Model\QuoteFactory $quoteFactory, ?\Magento\Quote\Model\QuoteIdMaskFactory $quoteIdMaskFactory = null, ?\Magento\Customer\Api\AddressRepositoryInterface $addressRepository = null)
    {
        $this->___init();
        parent::__construct($eventManager, $quoteValidator, $orderFactory, $orderManagement, $customerManagement, $quoteAddressToOrder, $quoteAddressToOrderAddress, $quoteItemToOrderItem, $quotePaymentToOrderPayment, $userContext, $quoteRepository, $customerRepository, $customerModelFactory, $quoteAddressFactory, $dataObjectHelper, $storeManager, $checkoutSession, $customerSession, $accountManagement, $quoteFactory, $quoteIdMaskFactory, $addressRepository);
    }

    /**
     * {@inheritdoc}
     */
    public function createEmptyCart()
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'createEmptyCart');
        if (!$pluginInfo) {
            return parent::createEmptyCart();
        } else {
            return $this->___callPlugins('createEmptyCart', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function createEmptyCartForCustomer($customerId)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'createEmptyCartForCustomer');
        if (!$pluginInfo) {
            return parent::createEmptyCartForCustomer($customerId);
        } else {
            return $this->___callPlugins('createEmptyCartForCustomer', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function assignCustomer($cartId, $customerId, $storeId)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'assignCustomer');
        if (!$pluginInfo) {
            return parent::assignCustomer($cartId, $customerId, $storeId);
        } else {
            return $this->___callPlugins('assignCustomer', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function placeOrder($cartId, ?\Magento\Quote\Api\Data\PaymentInterface $paymentMethod = null)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'placeOrder');
        if (!$pluginInfo) {
            return parent::placeOrder($cartId, $paymentMethod);
        } else {
            return $this->___callPlugins('placeOrder', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getCartForCustomer($customerId)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'getCartForCustomer');
        if (!$pluginInfo) {
            return parent::getCartForCustomer($customerId);
        } else {
            return $this->___callPlugins('getCartForCustomer', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function submit(\Magento\Quote\Model\Quote $quote, $orderData = array())
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'submit');
        if (!$pluginInfo) {
            return parent::submit($quote, $orderData);
        } else {
            return $this->___callPlugins('submit', func_get_args(), $pluginInfo);
        }
    }
}

<?php

namespace Upnance\Gateway\Controller\Payment;

class Redirect extends \Magento\Framework\App\Action\Action
{
    /**
     * @var \Psr\Log\LoggerInterface
     */
    protected $_logger;

    /**
     * @var \Magento\Sales\Api\OrderRepositoryInterface
     */
    protected $orderRepository;

    /**
     * @var \Upnance\Gateway\Model\Adapter\UpnanceAdapter
     */
    protected $_adapter;

    /**
     * @var \Magento\Checkout\Model\Session
     */
    protected $_checkoutSession;

    /**
     * @var \Magento\Sales\Model\OrderFactory
     */
    protected $_orderFactory;

    /**
     * Class constructor
     * @param \Magento\Framework\App\Action\Context              $context
     * @param \Psr\Log\LoggerInterface                           $logger
     */
    public function __construct(
        \Magento\Framework\App\Action\Context $context,
        \Psr\Log\LoggerInterface $logger,
        \Magento\Sales\Api\Data\OrderInterface $orderRepository,
        \Upnance\Gateway\Model\Adapter\UpnanceAdapter $adapter,
        \Magento\Checkout\Model\Session $checkoutSession,
        \Magento\Sales\Model\OrderFactory $orderFactory
    )
    {
        $this->_logger = $logger;
        $this->orderRepository = $orderRepository;
        $this->_adapter = $adapter;
        $this->_checkoutSession = $checkoutSession;
        $this->_orderFactory = $orderFactory;

        parent::__construct($context);
    }

    /**
     * @return \Magento\Checkout\Model\Session
     */
    protected function _getCheckout()
    {
        return $this->_checkoutSession;
    }

    /**
     * @return bool|\Magento\Sales\Model\Order
     */
    public function getOrder()
    {
        if ($this->_getCheckout()->getLastRealOrderId()) {
            $order = $this->_orderFactory->create()->loadByIncrementId($this->_getCheckout()->getLastRealOrderId());
            return $order;
        }
        return false;
    }

    /**
     * Redirect to to Upnance
     *
     * @return string
     */
    public function execute()
    {
        try {
            $order = $this->getOrder();

            if($order) {
                //Save quote id in session for retrieval later
                $this->_getCheckout()->setUpnanceQuoteId($this->_getCheckout()->getQuoteId());

                $response = $this->_adapter->CreatePaymentLink($order);

                if (isset($response['message'])) {
                    $this->messageManager->addError($response['message']);
                    $this->_getCheckout()->restoreQuote();
                    $this->_redirect($this->_redirect->getRefererUrl());
                } else {
                    //SAVE PAYMENT URL
                    $payment = $order->getPayment();
                    $additional = $payment->getAdditionalData();
                    if(!$additional){
                        $additional = [];
                    }
                    $additional['payment_link'] = $response['url'];

                    $payment->setAdditionalData(json_encode($additional));
                    $payment->save();

                    $this->_redirect($response['url']);
                }
            } else {
                $this->messageManager->addError('Error');
                $this->_getCheckout()->restoreQuote();
                $this->_redirect($this->_redirect->getRefererUrl());
            }
        } catch (\Exception $e) {
            $this->messageManager->addException($e, __('Something went wrong, please try again later'));
            $this->_getCheckout()->restoreQuote();
            $this->_redirect('checkout/cart');
        }
    }
}

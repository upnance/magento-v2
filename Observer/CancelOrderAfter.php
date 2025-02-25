<?php

namespace Upnance\Gateway\Observer;
use Upnance\Gateway\Model\Ui\ConfigProvider;
use Magento\Framework\Event\ObserverInterface;
use Magento\Framework\Exception\LocalizedException;

class CancelOrderAfter implements ObserverInterface
{
    /**
     * @var Upnance\Gateway\Model\Adapter\UpnanceAdapter
     */
    protected $adapter;

    public function __construct(
        \Upnance\Gateway\Model\Adapter\UpnanceAdapter $adapter
    )
    {
        $this->adapter = $adapter;
    }

    /**
     * @param \Magento\Framework\Event\Observer $observer
     */
    public function execute(\Magento\Framework\Event\Observer $observer)
    {
        $order = $observer->getEvent()->getOrder();

        $payment = $order->getPayment();
        if (in_array($payment->getMethod(),[
        	ConfigProvider::CODE,
	        ConfigProvider::CODE_KLARNA,
	        ConfigProvider::CODE_MOBILEPAY,
	        ConfigProvider::CODE_VIPPS,
	        ConfigProvider::CODE_PAYPAL,
	        ConfigProvider::CODE_VIABILL,
	        ConfigProvider::CODE_SWISH,
	        ConfigProvider::CODE_TRUSTLY,
            ConfigProvider::CODE_ANYDAY,
            ConfigProvider::CODE_APPLEPAY,
            ConfigProvider::CODE_GOOGLEPAY
	        ])) {
            $parts = explode('-', $payment->getLastTransId() ?? '');
            $order = $payment->getOrder();
            $transaction = $parts[0];

            if($transaction) {
                try {
                    $this->adapter->cancel($order, $transaction);
                } catch (LocalizedException $e) {
                    throw new LocalizedException(__($e->getMessage()));
                }
            }
        }
    }
}

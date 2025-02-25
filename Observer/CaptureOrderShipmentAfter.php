<?php

namespace Upnance\Gateway\Observer;
use Upnance\Gateway\Model\Ui\ConfigProvider;
use Magento\Framework\Event\ObserverInterface;
use Magento\Framework\Exception\LocalizedException;

class CaptureOrderShipmentAfter implements ObserverInterface
{
    const SHIPMENT_AUTO_CAPTURE_XML_PATH     = 'payment/upnance_gateway/autocapture_shipment';

    /**
     * @var Upnance\Gateway\Model\Adapter\UpnanceAdapter
     */
    protected $adapter;

    /**
     * @var \Magento\Framework\App\Config\ScopeConfigInterface
     */
    protected $scopeConfig;

    public function __construct(
        \Upnance\Gateway\Model\Adapter\UpnanceAdapter $adapter,
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig
    )
    {
        $this->adapter = $adapter;
        $this->scopeConfig = $scopeConfig;
    }

    public function execute(\Magento\Framework\Event\Observer $observer)
    {
        $autocapture = $this->scopeConfig->getValue(self::SHIPMENT_AUTO_CAPTURE_XML_PATH, \Magento\Store\Model\ScopeInterface::SCOPE_STORE);

        if($autocapture) {
            $shipment = $observer->getEvent()->getShipment();
            /** @var \Magento\Sales\Model\Order $order */
            $order = $shipment->getOrder();

            $payment = $order->getPayment();
            if (in_array($payment->getMethod(), [
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

                try {
                    $this->adapter->capture($order, $transaction, $order->getGrandTotal());
                } catch (LocalizedException $e) {
                    //throw new LocalizedException(__($e->getMessage()));
                }
            }
        }
    }
}

<?php

namespace Upnance\Gateway\Observer;
use Upnance\Gateway\Model\Ui\ConfigProvider;
use Magento\Framework\Event\ObserverInterface;
use Magento\Framework\Exception\LocalizedException;

class CaptureOrderInvoiceAfter implements ObserverInterface
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
        $invoice = $observer->getEvent()->getInvoice();
        $order = $invoice->getOrder();
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
            $captureCase = $invoice->getRequestedCaptureCase();
            if ($payment->canCapture() && !$order->hasInvoices()) {
                if ($captureCase == \Magento\Sales\Model\Order\Invoice::CAPTURE_ONLINE) {
                    $parts = explode('-', $payment->getLastTransId() ?? '');
                    $transaction = $parts[0];

                    try {
                        $this->adapter->capture($order, $transaction, $order->getGrandTotal());
                    } catch (LocalizedException $e) {
                        throw new LocalizedException(__($e->getMessage()));
                    }
                }
            }
        }
    }
}

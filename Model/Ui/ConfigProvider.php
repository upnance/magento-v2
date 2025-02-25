<?php
namespace Upnance\Gateway\Model\Ui;

use Magento\Checkout\Model\ConfigProviderInterface;

/**
 * Class ConfigProvider
 */
final class ConfigProvider implements ConfigProviderInterface
{
    const CODE = 'upnance_gateway';
    const CODE_KLARNA = 'upnance_klarna';
    const CODE_APPLEPAY = 'upnance_applepay';
    const CODE_MOBILEPAY = 'upnance_mobilepay';
    const CODE_VIPPS= 'upnance_vipps';
    const CODE_PAYPAL = 'upnance_paypal';
    const CODE_VIABILL = 'upnance_viabill';
    const CODE_SWISH = 'upnance_swish';
    const CODE_TRUSTLY = 'upnance_trustly';
    const CODE_ANYDAY = 'upnance_anyday';
    const CODE_GOOGLEPAY = 'upnance_googlepay';

    const XML_PATH_CARD_LOGO = 'payment/upnance_gateway/cardlogos';
    const XML_PATH_DESCRIPTION = 'payment/%s/description';

    protected $scopeConfig;

    protected $assetRepo;

    public function __construct(
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig,
        \Magento\Framework\View\Asset\Repository $assetRepo
    ){
        $this->scopeConfig = $scopeConfig;
        $this->assetRepo = $assetRepo;
    }

    /**
     * Retrieve assoc array of checkout configuration
     *
     * @return array
     */
    public function getConfig()
    {
        return [
            'payment' => [
                self::CODE => [
                    'redirectUrl' => 'upnancegateway/payment/redirect',
                    'paymentLogo' => $this->getUpnanceCardLogo(),
                    'description' => $this->getDescription(self::CODE)
                ],
                self::CODE_KLARNA => [
                    'paymentLogo' => $this->getKlarnaLogo(),
                    'description' => $this->getDescription(self::CODE_KLARNA)
                ],
                self::CODE_APPLEPAY => [
                    'paymentLogo' => $this->getApplePayLogo(),
                    'description' => $this->getDescription(self::CODE_APPLEPAY)
                ],
                self::CODE_MOBILEPAY => [
                    'paymentLogo' => $this->getMobilePayLogo(),
                    'description' => $this->getDescription(self::CODE_MOBILEPAY)
                ],
                self::CODE_VIPPS => [
                    'paymentLogo' => $this->getVippsLogo(),
                    'description' => $this->getDescription(self::CODE_VIPPS)
                ],
                self::CODE_PAYPAL => [
                    'paymentLogo' => $this->getPaypalLogo(),
                    'description' => $this->getDescription(self::CODE_PAYPAL)
                ],
                self::CODE_VIABILL => [
                    'paymentLogo' => $this->getViaBillLogo(),
                    'description' => $this->getDescription(self::CODE_VIABILL)
                ],
                self::CODE_SWISH => [
                    'paymentLogo' => $this->getSwishLogo(),
                    'description' => $this->getDescription(self::CODE_SWISH)
                ],
                self::CODE_TRUSTLY => [
                    'paymentLogo' => $this->getTrustlyLogo(),
                    'description' => $this->getDescription(self::CODE_TRUSTLY)
                ],
                self::CODE_ANYDAY => [
                    'paymentLogo' => $this->getAnydayLogo(),
                    'description' => $this->getDescription(self::CODE_ANYDAY)
                ],
                self::CODE_GOOGLEPAY => [
                    'paymentLogo' => $this->getGooglePayLogo(),
                    'description' => $this->getDescription(self::CODE_GOOGLEPAY)
                ],
            ]
        ];
    }

    public function getUpnanceCardLogo(){
        $storeScope = \Magento\Store\Model\ScopeInterface::SCOPE_STORE;
        $cards = explode(',', $this->scopeConfig->getValue(self::XML_PATH_CARD_LOGO, $storeScope) ?? '');
        $cardsSvg = ['maestro', 'mastercard', 'visa'];

        $items = [];

        if(count($cards)) {
            foreach ($cards as $card) {
                if($card) {
                    if(in_array($card, $cardsSvg)){
                        $ext = 'svg';
                    } else {
                        $ext = 'png';
                    }
                    $items[] = $this->assetRepo->getUrl("Upnance_Gateway::images/logo/{$card}.{$ext}");
                }
            }
        }

        return $items;
    }

    public function getDescription($method){
        $storeScope = \Magento\Store\Model\ScopeInterface::SCOPE_STORE;
        return $this->scopeConfig->getValue(sprintf(self::XML_PATH_DESCRIPTION, $method), $storeScope);
    }

    public function getKlarnaLogo(){
        $items = [];

        $items[] = $this->assetRepo->getUrl("Upnance_Gateway::images/klarna.svg");

        return $items;
    }

    public function getApplePayLogo(){
        $items = [];

        $items[] = $this->assetRepo->getUrl("Upnance_Gateway::images/apple-pay.svg");

        return $items;
    }

    public function getMobilePayLogo(){
        $items = [];

        $items[] = $this->assetRepo->getUrl("Upnance_Gateway::images/mobilepay_payment.png");

        return $items;
    }

    public function getVippsLogo(){
        $items = [];

        $items[] = $this->assetRepo->getUrl("Upnance_Gateway::images/vipps.png");

        return $items;
    }

    public function getPaypalLogo(){
        $items = [];

        $items[] = $this->assetRepo->getUrl("Upnance_Gateway::images/paypal.svg");

        return $items;
    }

    public function getViaBillLogo(){
        $items = [];

        $items[] = $this->assetRepo->getUrl("Upnance_Gateway::images/viabill.png");

        return $items;
    }

    public function getSwishLogo(){
        $items = [];

        $items[] = $this->assetRepo->getUrl("Upnance_Gateway::images/swish.png");

        return $items;
    }

    public function getTrustlyLogo(){
        $items = [];

        $items[] = $this->assetRepo->getUrl("Upnance_Gateway::images/trustly.svg");

        return $items;
    }

    public function getAnydayLogo(){
        $items = [];

        $items[] = $this->assetRepo->getUrl("Upnance_Gateway::images/anydaysplit.svg");

        return $items;
    }

    public function getGooglePayLogo(){
        $items = [];

        $items[] = $this->assetRepo->getUrl("Upnance_Gateway::images/google-pay.svg");

        return $items;
    }
}

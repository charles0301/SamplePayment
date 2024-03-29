<?php
 
namespace SamplePayment\Methods;
 
use Plenty\Plugin\ConfigRepository;
use Plenty\Modules\Payment\Method\Contracts\PaymentMethodService;
use Plenty\Modules\Basket\Contracts\BasketRepositoryContract;
use Plenty\Modules\Basket\Models\Basket;
 
/**
 * Class SamplePaymentPaymentMethod
 * @package SamplePayment\Methods
 */
class SamplePaymentPaymentMethod extends PaymentMethodService
{
    /**
     * Check the configuration if the payment method is active
     * Return true if the payment method is active, else return false
     *
     * @param ConfigRepository $configRepository
     * @param BasketRepositoryContract $basketRepositoryContract
     * @return bool
     */
    public function isActive( ConfigRepository $configRepository,
                              BasketRepositoryContract $basketRepositoryContract):bool
    {
        /** @var bool $active */
        $active = true;
 
        /** @var Basket $basket */
        $basket = $basketRepositoryContract->load();
 
        /**
         * Check the shipping profile ID. The ID can be entered in the config.json.
         */
        if( $configRepository->get('SamplePayment.shippingProfileId') != $basket->shippingProfileId)
        {
            $active = false;
        }
 
        return $active;
    }
 
    /**
     * Get the name of the payment method. The name can be entered in the config.json.
     *
     * @param ConfigRepository $configRepository
     * @return string
     */
    public function getName( ConfigRepository $configRepository ):string
    {
        $name = $configRepository->get('SamplePayment.name');
 
        if(!strlen($name))
        {
            $name = 'Sample Payment';
        }
 
        return $name;
 
    }
 
    /**
     * Get the path of the icon. The URL can be entered in the config.json.
     *
     * @param ConfigRepository $configRepository
     * @return string
     */
    public function getIcon( ConfigRepository $configRepository ):string
    {
        if($configRepository->get('SamplePayment.logo') == 1)
        {
            return $configRepository->get('SamplePayment.logo.url');
        }
        return '';
    }
 
    /**
     * Get the description of the payment method. The description can be entered in the config.json.
     *
     * @param ConfigRepository $configRepository
     * @return string
     */
    public function getDescription( ConfigRepository $configRepository ):string
    {
        if($configRepository->get('SamplePayment.infoPage.type') == 1)
        {
            return $configRepository->get('SamplePayment.infoPage.intern');
        }
        elseif ($configRepository->get('SamplePayment.infoPage.type') == 2)
        {
            return $configRepository->get('SamplePayment.infoPage.extern');
        }
        else
        {
          return '';
        }
    }
}

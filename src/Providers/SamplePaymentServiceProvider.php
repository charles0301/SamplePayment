<?php
 
namespace SamplePayment\Providers;
 
use Plenty\Plugin\ServiceProvider;
use Plenty\Plugin\Events\Dispatcher;
use Plenty\Modules\Payment\Events\Checkout\ExecutePayment;
use Plenty\Modules\Payment\Events\Checkout\GetPaymentMethodContent;
use Plenty\Modules\Basket\Events\Basket\AfterBasketCreate;
use Plenty\Modules\Basket\Events\Basket\AfterBasketChanged;
use Plenty\Modules\Basket\Events\BasketItem\AfterBasketItemAdd;
use Plenty\Modules\Payment\Method\Contracts\PaymentMethodContainer;
use SamplePayment\Helper\SamplePaymentHelper;
use SamplePayment\Methods\SamplePaymentPaymentMethod;
 
/**
 * Class SamplePaymentServiceProvider
 * @package SamplePayment\Providers
 */
class SamplePaymentServiceProvider extends ServiceProvider
{
    public function register()
    {
 
    }
 
    /**
     * Boot additional services for the payment method
     *
     * @param SamplePaymentHelper $paymentHelper
     * @param PaymentMethodContainer $payContainer
     * @param Dispatcher $eventDispatcher
     */
    public function boot( SamplePaymentHelper $paymentHelper,
                          PaymentMethodContainer $payContainer,
                          Dispatcher $eventDispatcher)
    {
        // Create the ID of the payment method if it doesn't exist yet
        $paymentHelper->createMopIfNotExists();
 
        // Register the Sample Payment payment method in the payment method container
        $payContainer->register('plenty_SamplePayment::SamplePayment', SamplePaymentPaymentMethod::class,
                              [ AfterBasketChanged::class, AfterBasketItemAdd::class, AfterBasketCreate::class ]
        );
 
        // Listen for the event that gets the payment method content
        $eventDispatcher->listen(GetPaymentMethodContent::class,
                function(GetPaymentMethodContent $event) use( $paymentHelper)
                {
                    if($event->getMop() == $paymentHelper->getPaymentMethod())
                    {
                       $event->setValue('');
                       $event->setType('continue');
                    }
                });
 
        // Listen for the event that executes the payment
        $eventDispatcher->listen(ExecutePayment::class,
           function(ExecutePayment $event) use( $paymentHelper)
           {
               if($event->getMop() == $paymentHelper->getPaymentMethod())
               {
                   $event->setValue('<h1>Sample Payment<h1>');
                   $event->setType('htmlContent');
               }
           });
   }
}

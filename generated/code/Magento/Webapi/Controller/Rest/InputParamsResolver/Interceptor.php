<?php
namespace Magento\Webapi\Controller\Rest\InputParamsResolver;

/**
 * Interceptor class for @see \Magento\Webapi\Controller\Rest\InputParamsResolver
 */
class Interceptor extends \Magento\Webapi\Controller\Rest\InputParamsResolver implements \Magento\Framework\Interception\InterceptorInterface
{
    use \Magento\Framework\Interception\Interceptor;

    public function __construct(\Magento\Framework\Webapi\Rest\Request $request, \Magento\Webapi\Controller\Rest\ParamsOverrider $paramsOverrider, \Magento\Framework\Webapi\ServiceInputProcessor $serviceInputProcessor, \Magento\Webapi\Controller\Rest\Router $router, \Magento\Webapi\Controller\Rest\RequestValidator $requestValidator, ?\Magento\Webapi\Model\UrlDecoder $urlDecoder = null)
    {
        $this->___init();
        parent::__construct($request, $paramsOverrider, $serviceInputProcessor, $router, $requestValidator, $urlDecoder);
    }

    /**
     * {@inheritdoc}
     */
    public function resolve()
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'resolve');
        if (!$pluginInfo) {
            return parent::resolve();
        } else {
            return $this->___callPlugins('resolve', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getRoute()
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'getRoute');
        if (!$pluginInfo) {
            return parent::getRoute();
        } else {
            return $this->___callPlugins('getRoute', func_get_args(), $pluginInfo);
        }
    }
}

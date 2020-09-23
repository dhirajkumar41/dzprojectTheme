<?php
/**
 * Plugin for Magento\ConfigurableProduct\Block\Product\View\Type\Configurable
 */
namespace Swissup\Ajaxpro\Plugin\Block\Product\View\Type;

class ConfigurablePlugin
{
    /**
     *
     * @var \Swissup\Ajaxpro\Helper\Config
     */
    private $helper;

    /**
     * @var \Magento\Framework\Serialize\Serializer\Json $serializer
     */
    private $serializer;

    /**
     * @param \Swissup\Ajaxpro\Helper\Config $helper
     * @param \Magento\Framework\Serialize\Serializer\Json $serializer
     */
    public function __construct(
        \Swissup\Ajaxpro\Helper\Config $helper,
        \Magento\Framework\Serialize\Serializer\Json $serializer
    ) {
        $this->helper = $helper;
        $this->serializer = $serializer;
    }



    /**
     * Add containerId
     *
     * @param  \Magento\ConfigurableProduct\Block\Product\View\Type\Configurable $subject
     * @param  string                               $result
     * @return string
     */
    public function afterGetJsonConfig(
        \Magento\ConfigurableProduct\Block\Product\View\Type\Configurable $subject,
        $result
    ) {
        $request = $subject->getRequest();
        if (!$request->isAjax()) {
            return $result;
        }

        $sectionNames = $request->getParam('sections');
        $sectionNames = $sectionNames ? array_unique(\explode(',', $sectionNames)) : [];
        if (!in_array('ajaxpro-product', $sectionNames)) {
            return $result;
        }

        $ajaxproParam = $request->getParam('ajaxpro');
        if (!isset($ajaxproParam['product_id'])) {
            return $result;
        }

        json_decode($result, true);
        if (json_last_error() !== JSON_ERROR_NONE) {
            return $result;
        }
        $jsonConfig = $this->serializer->unserialize($result, true);
        $isEnable = $this->helper->isProductViewEnable();

        if (!isset($jsonConfig['containerId']) && $isEnable) {
            $jsonConfig['containerId'] = '#ajaxpro-catalog\\.product\\.view';
            $result = $this->serializer->serialize($jsonConfig);
        }

        return $result;
    }
}

<?php
/**
 * Copyright © 2016-2020 Swissup. All rights reserved.
 */
namespace Swissup\Ajaxpro\Block;

class Init extends \Magento\Framework\View\Element\Template
{
    /**
     * Customer session
     *
     * @var \Magento\Customer\Model\Session
     */
    protected $customerSession;

    /**
     * @var \Magento\Framework\Serialize\Serializer\Json $serializer
     */
    private $serializer;

    /**
     * @var \Magento\Framework\Module\PackageInfo
     */
    protected $packageInfo;

    /**
     * @var \Magento\Framework\Url\Helper\Data
     */
    protected $urlHelper;

    /**
     * @var \Swissup\Ajaxpro\Helper\Config
     */
    private $configHelper;


    /**
     * @param \Magento\Framework\View\Element\Template\Context $context
     * @param \Magento\Customer\Model\Session $customerSession
     * @param \Magento\Framework\Serialize\Serializer\Json $serializer
     * @param \Magento\Framework\Module\PackageInfo $packageInfo
     * @param \Magento\Framework\Url\Helper\Data $urlHelper
     * @param \Swissup\Ajaxpro\Helper\Config $configHelper
     * @param array $data
     */
    public function __construct(
        \Magento\Framework\View\Element\Template\Context $context,
        \Magento\Customer\Model\Session $customerSession,
        \Magento\Framework\Serialize\Serializer\Json $serializer,
        \Magento\Framework\Module\PackageInfo $packageInfo,
        \Magento\Framework\Url\Helper\Data $urlHelper,
        \Swissup\Ajaxpro\Helper\Config $configHelper,
        array $data = []
    ) {
        parent::__construct($context, $data);

        $this->customerSession = $customerSession;
        $this->serializer = $serializer;
        $this->packageInfo = $packageInfo;
        $this->urlHelper = $urlHelper;
        $this->configHelper = $configHelper;
    }

    /**
     *
     * @return bool
     */
    public function isLoggedIn()
    {
        return $this->customerSession->isLoggedIn();
    }

    /**
     *
     * @return bool
     */
    public function isQuickViewEnabled()
    {
        return $this->configHelper->isQuickViewEnabled();
    }

    /**
     *
     * @return bool
     */
    public function isAnimationEnabled()
    {
        return $this->configHelper->isAnimationEnabled();
    }

    /**
     *
     * @return string
     */
    public function getCustomerSectionLoadUrl()
    {
        return $this->getUrl(
            'customer/section/load',
            ['_secure' => $this->getRequest()->isSecure()]
        );
    }

    /**
     *
     * @return string
     */
    public function getJsonEncodedWidgetConfig()
    {
        $sectionLoadUrl = $this->getCustomerSectionLoadUrl();
        $uenc = $this->urlHelper->getEncodedUrl();

        $config = [
            'sectionLoadUrl' => $sectionLoadUrl,
            'refererParam' => \Magento\Framework\App\ActionInterface::PARAM_NAME_URL_ENCODED,
            'refererValue' => $uenc
        ];

        return $this->serializer->serialize($config);
    }

    /**
     * Retrieve serialized JS layout configuration ready to use in template
     *
     * @return string
     */
    public function getJsLayout()
    {
        $jsLayout = $this->jsLayout;
        $package = 'swissup/ajaxpro';
        $module = $this->packageInfo->getModuleName($package);
        $version = $this->packageInfo->getVersion($module);

        if (isset($jsLayout['components']['ajaxpro'])) {
            $jsLayout['components']['ajaxpro']['version'] = $version;
        }
        return $this->serializer->serialize($jsLayout);
    }
}

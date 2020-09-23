<?php
namespace Swissup\Amp\Plugin\Controller;

class AbstractPlugin
{
    /**
     * @var \Swissup\Amp\Helper\Data
     */
    protected $helper;

    /**
     * @var \Swissup\Amp\Helper\Message
     */
    protected $messageHelper;

    /**
     * @var \Magento\Store\Model\StoreManagerInterface
     */
    protected $storeManager;

    /**
     * @var \Magento\Catalog\Api\ProductRepositoryInterface
     */
    protected $productRepository;

    /**
     * @param \Swissup\Amp\Helper\Data $helper
     * @param \Swissup\Amp\Helper\Message $messageHelper
     * @param \Magento\Store\Model\StoreManagerInterface $storeManager
     * @param \Magento\Catalog\Api\ProductRepositoryInterface $productRepository
     */
    public function __construct(
        \Swissup\Amp\Helper\Data $helper,
        \Swissup\Amp\Helper\Message $messageHelper,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \Magento\Catalog\Api\ProductRepositoryInterface $productRepository
    ) {
        $this->helper = $helper;
        $this->messageHelper = $messageHelper;
        $this->storeManager = $storeManager;
        $this->productRepository = $productRepository;
    }

    /**
     * Load product by id
     * @param  int $id
     * @return \Magento\Catalog\Api\Data\ProductInterface
     */
    public function getProductById($id)
    {
        return $this->productRepository->getById(
            (int)$id,
            false,
            $this->storeManager->getStore()->getId()
        );
    }
}

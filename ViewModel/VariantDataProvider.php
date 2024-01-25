<?php
declare(strict_types=1);

namespace Kadoco\Variants\ViewModel;

use Magento\Catalog\Api\Data\ProductInterface;
use Magento\Catalog\Api\ProductRepositoryInterface;
use Magento\ConfigurableProduct\Model\Product\Type\Configurable;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\View\Element\Block\ArgumentInterface;

class VariantDataProvider implements ArgumentInterface
{
    /**
     * @var Configurable
     */
    private Configurable $configurable;
    /**
     * @var ProductRepositoryInterface
     */
    private ProductRepositoryInterface $productRepository;

    public function __construct(
        Configurable $configurable,
        ProductRepositoryInterface $productRepository
    ) {
        $this->configurable = $configurable;
        $this->productRepository = $productRepository;
    }

    public function getData(int $productId):?array
    {
        $parent = $this->getParent($productId);
        if (!$parent) {
            return null;
        }

        $attributes = $this->configurable->getConfigurableAttributesAsArray($parent);
        try {
            $simple = $this->productRepository->getById($productId);
        } catch (NoSuchEntityException $e) {
            return null;
        }

        $options = [];
        foreach ($attributes as $attribute) {
            $id = $attribute['attribute_id'];
            $value = $simple->getData($attribute['attribute_code']);
            $options[$id] = $value;
        }
        $options = http_build_query($options);
        $hash = $options ? '#' . $options : '';

        return [
            'url' => $parent->getProductUrl() . $hash,
        ];
    }

    public function getParent(int $productId):?ProductInterface
    {
        $data = $this->configurable->getParentIdsByChild($productId);
        $parentId = reset($data);
        if (!ctype_digit($parentId)) {
            return null;
        }
        try {
            return $this->productRepository->getById($parentId);
        } catch (NoSuchEntityException $e) {
            return null;
        }
    }
}

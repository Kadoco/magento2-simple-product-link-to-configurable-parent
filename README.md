# Kadoco simple product link to configurable parent for Magento 2

## Overview

This module, developed by Kadoco, provides a link for simple products that leads to their configurable parent, when the link is clicked the configurable product is open with the options preselected that leads to the simple child.

## Features

In your list.phtml for your simple product you can use:

In top:

    <?php
    /** @var \Kadoco\Variants\ViewModel\VariantDataProvider $variantProvider */
    $variantProvider = $block->getData('VariantDataProvider');

In the product for loop:

    <?php
        $parent = $variantProvider->getData((int)$_product->getId());
        if (!$parent) {
            $url = $_product->getProductUrl();
        } else {
            $url = $parent['url'];
        }
    ?>

This will provide you with a url that will lead to the configurable product here when the configurable products open it will automatically make the selection so that this simple product is selected.

You then replace the urls in the list.phtml with the $url variable:

e.g. like this:

    <a href="<?= $escaper->escapeUrl($url) ?>"
       class="product photo product-item-photo"
       tabindex="-1">
        <?= $productImage->toHtml() ?>
    </a>


## Contributing

For any contributions, please make a pull request. We appreciate any contributions to improve this project.

## License

This project is licensed under the APACHE-2.0.

## Support

If you encounter any issues or require further information, please contact hej@kadoco.se.





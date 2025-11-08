<?php

declare(strict_types=1);

class MaturinBridge extends BridgeAbstract
{
    const NAME = 'maturin.ca (soldes,Nouveauté)';
    const URI = 'https://www.maturin.ca/';
    const DESCRIPTION = 'Les produits sur le site de Maturin';

    public function collectData()
    {
        $url = self::URI . 'soldes';
        $itemSelector = 'div.mobile_product_box';
        $itemTitleSelector = 'div.masonry-product div.product div.product-title a';
        $ItemContentSelector = 'div.masonry-product';

        $dom = getSimpleHTMLDOM($url);
        foreach ($dom->find($itemSelector) as $item) {
            $itemTitle = $item->find($itemTitleSelector, 0);
            $itemContent = $item->find($ItemContentSelector, 0);

            $content = '';

            // si $itemContent existe faire la transformation
            if ($itemContent) {
                $itemProductGroup = $itemContent->find('div.product-group a img', 0);
                if ($itemProductGroup) {
                    $content .= '<img src="https://www.maturin.ca' . $itemProductGroup->{'data-src'} . '"><br />';
                }

                $itemSubtitle = $itemContent->find('div.product div.product-subtitle a', 0);
                if ($itemSubtitle) {
                    $content .= "<a href=\"https://www.maturin.ca{$itemSubtitle->href}\" target=\"_blank\">{$itemSubtitle->plaintext}</a><br />";
                }

                $itemPrice = $itemContent->find('div.product div.product-price', 0);
                if ($itemPrice) {
                    $price = $itemPrice->plaintext;
                    // si commence par un saut de ligne le supprimer
                    if (strpos($price, "\n") === 0) {
                        $price = substr($price, 1);
                    }

                    // remplacer les sauts de ligne par des br
                    $price = str_replace("\n", '<br />', $price);

                     $content .= "Prix :<br />{$price}<br />";
                }
            }

            $this->items[] = [
                'title' => $itemTitle->plaintext,
                'uri' => "https://www.maturin.ca{$itemTitle->href}",
                'content' => $content,
            ];
        }
    }
}
<?php

declare(strict_types=1);

class LeDevoirBridge extends BridgeAbstract
{
    const NAME = 'ledevoir.com (caricatures)';
    const URI = 'https://www.ledevoir.com/photos/galeries-photos/les-caricatures-de-godin-et-de-chloe';
 
    public function collectData()
    {
        $itemSelector = 'div.slide.col-11 article.card-caricature';
        $itemTitleSelector = 'h5';
        $ItemContentSelector = 'div.card-body';
        $LinkSelector = 'div.card-body > a';

        $dom = getSimpleHTMLDOM(self::URI);
        foreach ($dom->find($itemSelector) as $item) {
            $itemTitle = $item->find($itemTitleSelector, 0);
            $itemContent = $item->find($ItemContentSelector, 0);
            $itemLink = $item->find($LinkSelector, 0);

            $url = "https://www.ledevoir.com{$itemLink->href}";

            // aller chercher les informations dans la page liée
            $itemPageDom = getSimpleHTMLDOM($url);

            $content = '';

            // trouver le nom de l'auteur
            $author = '';
            $authorSelector = 'div.credit';
            $itemAuthor = $itemPageDom->find($authorSelector, 0);
            if ($itemAuthor) {
                $author = $itemAuthor->plaintext;
            }

            // trouver l'image principale
            $mainImageSelector = 'div.caricature > figure > picture > img';
            $itemImage = $itemPageDom->find($mainImageSelector, 0);
            if ($itemImage) {
                $content .= "<img src=\"{$itemImage->src}\" alt=\"$itemImage->alt\">";
            }

            $this->items[] = [
                'title' => $itemTitle->plaintext,
                'uri' => $url,
                'content' => $content,
                'author' => $author,
            ];
        }
    }
}
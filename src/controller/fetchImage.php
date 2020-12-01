<?php

use GuzzleHttp\Client;

require "../vendor/autoload.php";
require "./simple_html_dom.php";

session_start();

if (isset($_POST["clientUrl"]) && isset($_POST["imageQuantity"])) {
    $clientUrl = $_POST["clientUrl"];
    $imageQuantity = $_POST["imageQuantity"];
    $res = getImagesFromPage($clientUrl, $imageQuantity);
    if ($res['status'] === "success") {
        addImagesLinkToSession($res['msg']);
    }
    echo json_encode($res);
    exit();
}

function addImagesLinkToSession($imageLinks)
{
    $imgLinks = [];
    $imgLinks = json_decode($_SESSION['imgLinks']);
    foreach ($imageLinks as $link) {
        $imgLinks[] = $link;
    }
    $_SESSION['imgLinks'] = json_encode($imgLinks);
}

function getImagesFromPage($url, $quantity)
{
    $client = new \GuzzleHttp\Client();
    $response = $client->get($url);
    $pageHtml = $response->getBody()->getContents();

    $dom = file_get_html($url);
    $images = domGetImages($dom, $url, $quantity);
    return $images;
}

/**
 * Get the full url of a source path relative to a base url.
 *
 * Takes a source path (ie. an image src from an html page), and an
 * associated URL (ie. the page that the image appears on), and returns the
 * absolute source (including url & protocol) path.
 *
 * @param string $srcPath The source path to make absolute (if not absolute already).
 * @param string $url The full url to the page containing the src reference.
 * @return string Absolute source path.
 */
function absoluteSource($srcPath, $url)
{
    // If there is a scheme in the srcpath already, just return it.
    if (!is_null(parse_url($srcPath, PHP_URL_SCHEME))) {
        return $srcPath;
    }
    // Does SrcPath assume root?
    if (in_array(substr($srcPath, 0, 1), ['/', '\\'])) {
        return parse_url($url, PHP_URL_SCHEME)
            . '://'
            . parse_url($url, PHP_URL_HOST)
            . $srcPath;
    }

    // Work with the path in the url & the provided src path to backtrace if necessary
    $urlPathParts = explode('/', str_replace('\\', '/', parse_url($url, PHP_URL_PATH)));
    $srcParts = explode('/', str_replace('\\', '/', $srcPath));
    $result = [];
    foreach ($srcParts as $part) {
        if (!$part || $part == '.') {
            continue;
        }
        if ($part == '..') {
            array_pop($urlPathParts);
        } else {
            $result[] = $part;
        }
    }

    // Put it all together & return
    return parse_url($url, PHP_URL_SCHEME)
        . '://'
        . parse_url($url, PHP_URL_HOST)
        . '/' . implode('/', array_filter(array_merge($urlPathParts, $result)));
}

/**
 * Get the images from a DOM.
 *
 * @param pQuery $dom The DOM to search.
 * @param string $url The URL of the document to add to relative URLs.
 * @param int $quantity The maximum number of images to return.
 * @return array Returns an array in the form: `[['Src' => '', 'Width' => '', 'Height' => ''], ...]`.
 */

function domGetImages($dom, $url, $quantity = 1)
{
    $images = [];
    $imgElements = $dom->find('img');
    foreach ($imgElements as $element) {
        $images[] = [
            'Src' => absoluteSource($element->src, $url),
            'Width' => $element->width,
            'Height' => $element->height,
        ];
    }
    // Sort by size, biggest one first
    $imageSort = [];
    $i = 0;
    foreach ($images as $imageInfo) {
        $image = $imageInfo['Src'];
        if (empty($image) || strpos($image, 'doubleclick.') !== false) {
            continue;
        }

        try {
            if ($imageInfo['Height'] && $imageInfo['Width']) {
                $height = $imageInfo['Height'];
                $width = $imageInfo['Width'];
            } else {
                list($width, $height) = getimagesize($image);
            }

            $diag = (int)floor(sqrt(($width * $width) + ($height * $height)));
            if (!$width || !$height) {
                continue;
            }

            // Require min 100x100 dimension image.
            if ($width < 100 && $height < 100) {
                continue;
            }

            if (!array_key_exists($diag, $imageSort)) {
                $imageSort[$diag] = [$image];
            } else {
                $imageSort[$diag][] = $image;
            }

            $i++;
            if ($i > $quantity) {
                break;
            }
        } catch (Exception $ex) {
            // do nothing
        }
    }

    krsort($imageSort);
    $goodImages = [];
    foreach ($imageSort as $diag => $arr) {
        $goodImages = array_merge($goodImages, $arr);
    }

    $res = [];
    $res['status'] = "success";
    $res['msg'] = $goodImages;
    return $res;
}

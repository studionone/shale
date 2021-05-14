<?php

declare(strict_types=1);

namespace Shale\Schema\Type;

use cebe\markdown\MarkdownExtra;

/**
 * Class ShortcodeMarkdown
 *
 * @package Shale\Schema\Type
 */
abstract class ShortcodeMarkdown extends MarkdownExtra
{
    /**
     * Preg matches the passed in shortcode from renderShortcode and then loops
     * through and index's each optional value into shortcodeArray based
     * on the array $values.
     * It then performs a switch case depending on the type found in the shortcode.
     * @param String $data
     * @return null|string
     */
    public function shortcodeHandler(string $data)
    {
        preg_match_all("/([^\s]+)='([^']+)'/i", $data, $shortcodeMatch, PREG_SET_ORDER);

        $attributes = [];
        foreach ($shortcodeMatch as $params) {
            $attributes[$params[1]] = $params[2];
        }

        $type = $attributes['type'] ?? '';

        switch ($type) {
            case 'youtube':
                return $this->embeddedYoutube($attributes);
            case 'google-maps':
                return $this->embeddedGoogle($attributes);
            case 'thinglink':
                return $this->embeddedThingLink($attributes);
        }
        return null;
    }

    /**
     * Assigns src, height and width that returns html that gets rendered by renderShortcode.
     * If no height or width are supplied, sets default values.
     * To allow for flexibility the youtube id is preg matched from the src and then
     * assigned to a custom embedded youtube url.
     * @param array $shortcodeArray
     * @return string
     */
    public function embeddedYoutube(array $shortcodeArray): string
    {
        preg_match('/(https?:)?\/\/(?:[0-9A-Z-]+\.)?(?:youtu\.be\/|youtube(?:-nocookie)?\.com\S*?[^\w\s-])([\w-]{11})(?=[^\w-]|$)(?![?=&+%\w.-]*(?:[\'".\'"][^<>]*>|<\/a>))[?=&+%\w.-]*/i', $shortcodeArray['src'], $youtubeId);
        $src = 'https://youtube.com/embed/'.$youtubeId[2];
        $height = $this->getSize($shortcodeArray)['height'];
        $width = $this->getSize($shortcodeArray)['width'];
        $class = $this->getClass($shortcodeArray);
        return '<div class="' . $class . '"><iframe data-height="'.$height.'" data-width="'.$width.'"  src="' . $src . '" height="' . $height . '" width="' . $width . '" frameborder="0"></iframe></div><br>';
    }

    /**
     * Assigns src, height and width that returns html that gets rendered by renderShortcode.
     * If no height or width are supplied, sets default values.
     * @param array $shortcodeArray
     * @return string
     */

    public function embeddedGoogle(array $shortcodeArray): string
    {
        $src = $shortcodeArray['src'];
        $height = $this->getSize($shortcodeArray)['height'];
        $width = $this->getSize($shortcodeArray)['width'];
        $class = $this->getClass($shortcodeArray);
        return '<div class="' . $class . '"><iframe data-height="'.$height.'" data-width="'.$width.'"  src="' . $src . '" height="' . $height . '" width="' . $width . '" frameborder="0"></iframe></div><br>';
    }

    /**
     * Assigns src, height and width that returns html that gets rendered by renderShortcode.
     * If no height or width are supplied, sets default values.
     * @param array $shortcodeArray
     * @return string
     */
    public function embeddedThingLink(array $shortcodeArray): string
    {
        $src = $shortcodeArray['src'];
        $scrolling = $this->getScrolling($shortcodeArray);
        $height = $this->getSize($shortcodeArray)['height'];
        $width = $this->getSize($shortcodeArray)['width'];
        $class = $this->getClass($shortcodeArray);
        return '<div class="' . $class . '"><iframe data-height="'.$height.'" data-width="'.$width.'" src="' . $src . '" height="' . $height . '" width="' . $width . '" scrolling="' . $scrolling . '"  allowfullscreen frameborder="0"></iframe></div><br>';
    }

    /**
     * Sets the width and height provided in the shortcode.
     * If no width or height is provided then they are set to a default value.
     * @param array $shortcode
     * @return array
     */
    public function getSize(array $shortcode): array
    {
        $width = '100%';
        $height = '600';

        if (!empty($shortcode['width'])
            && !empty($shortcode['height'])
        ) {
            $width = $shortcode['width'];
            $height = $shortcode['height'];
        }

        return [
            'width' => $width,
            'height' => $height,
        ];
    }

    /**
     * Sets the class provided in the shortcode.
     * If no class is provided then they are set to a default value
     * @param array $shortcode
     * @return string
     */
    public function getClass(array $shortcode): string
    {
        return $shortcode['class'] ?? 'shortcode-container';
    }

    /**
     * Sets the scrolling provided in the shortcode.
     * If no scrolling is provided then they are set to a default value
     * @param array $shortcode
     * @return string
     */
    public function getScrolling(array $shortcode): string
    {
        return $shortcode['scrolling'] ?? 'no';
    }
}

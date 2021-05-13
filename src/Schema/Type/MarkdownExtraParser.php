<?php

declare(strict_types=1);

namespace Shale\Schema\Type;

/**
 * Class MarkdownExtraParser
 *
 * @package Shale\Schema\Type
 */
class MarkdownExtraParser extends ShortcodeMarkdown
{
    /**
     * Documentation of extending markdown can be found at https://github.com/cebe/markdown
     */

    /**
     * @inheritDoc
     */
    protected $escapeCharacters = [
        // from Markdown
        '\\', // backslash
        '`', // backtick
        '*', // asterisk
        '_', // underscore
        '{', '}', // curly braces
        '[', ']', // square brackets
        '(', ')', // parentheses
        '#', // hash mark
        '+', // plus sign
        '-', // minus sign (hyphen)
        '.', // dot
        '!', // exclamation mark
        // added by MarkdownExtra
        ':', // colon
        '|', // pipe
        '<', '>',
    ];

    /**
     * identify a figure element
     */
    protected function identifyFigure($line, $lines, $current): bool
    {
        return (preg_match("/<figure[^>]*>/i", $line) === 1);
    }

    /**
     * Consume figure
     */
    protected function consumeFigure($lines, $current): array
    {
        $block = [
            'figure',
            'content' => [],
        ];

        $line = rtrim($lines[$current]);
        $fence = '</figure>';

        for ($i = $current + 1, $count = count($lines); $i < $count; $i++) {
            if (rtrim($line = $lines[$i]) === $fence) {
                break;
            }
            $block['content'][] = $line;
        }

        return [$block, $i];
    }

    /**
     * Render figure
     */
    protected function renderFigure($block): string
    {
        $block['content'] = array_filter($block['content'], 'strlen');

        $newContent = [];
        foreach ($block['content'] as $content) {
            $newContent[] = $this->parseParagraph($content);
        }

        return "<figure>\n" . implode("\n", $newContent) . "\n</figure>\n";
    }

    /**
     * Beginning of shortcode markdown extension.
     * Uses inline element to find the shortcode within the markdown body text
     * and allows explore to interrupt and render the shortcode properly.
     * @marker [embed
     */
    protected function parseShortcode($markdown): array
    {
        if (preg_match("/\[embed\s([^\]]*?)\]/i", $markdown, $match)) {
            return [
                ['Shortcode', $this->parseInline($match[1])],
                strlen($match[0]),
            ];
        }
        return [['text', '[code'], 6];
    }

    /**
     * Receives an array of the preg_match code from parseShortcode.
     * The function then calls the abstract function shortcodeHandle from ShortcodeMarkdown.php.
     * Render Shortcode'
     * @param array $element
     * @return string
     */
    protected function renderShortcode(array $element): string
    {
        $response = $this->shortcodeHandler($this->renderAbsy($element[1]));
        if (empty($response)) {
            return '';
        }
        return $response;
    }

    /**
     * Beginning of iframe purifier.
     * Uses inline element to find any iframe tags within the markdown body text
     * removing all iframe tags it picks up.
     * @marker <iframe
     */
    protected function parsePurifyIframe($markdown): array
    {
        if (preg_match("/\<iframe\s([^\]]*?)iframe>/i", $markdown, $match)) {
            return [
                ['PurifyIframe', $this->parseInline($match[1])],
                strlen($match[0]),
            ];
        }
        return [['text', '<iframe'], 7];
    }

    /**
     * Returns null which removes any iframe tags it initially picked up from
     * parsePurifyIframe.
     * Render Purifier
     */
    protected function renderPurifyIframe()
    {
        return null;
    }
}

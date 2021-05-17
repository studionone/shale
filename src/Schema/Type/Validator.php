<?php

declare(strict_types=1);

namespace Shale\Schema\Type;

use Exception;
use Shale\Exception\Schema\DataEncode\ValueWasWrongTypeException;
use Shale\Exception\Schema\DataWasWrongTypeException;
use Shale\Schema\TypeRegistry;
use HTMLPurifier;
use HTMLPurifier_Config;

/**
 * Trait Validator
 *
 * @package Shale\Schema\Type
 */
trait Validator
{
    /**
     * @param $data
     * @param TypeRegistry $typeRegistry
     * @return mixed|string
     * @throws DataWasWrongTypeException
     * @throws ValueWasWrongTypeException
     */
    public function getValueFromData($data, TypeRegistry $typeRegistry)
    {
        $data = $this->validate($data, __FUNCTION__);
        $parser = $this->getParser();
        if (!$parser) {
            return $data;
        }

        $parser->html5 = true; // it is not default
        $parser->keepListStartNumber = true;

        // Purify html
        return $this
            ->getPurifier()
            ->purify($parser->parse($data));
    }

    /**
     * @param $value
     * @param TypeRegistry $typeRegistry
     * @return mixed|string
     * @throws DataWasWrongTypeException
     * @throws ValueWasWrongTypeException
     */
    public function getDataFromValue($value, TypeRegistry $typeRegistry)
    {
        $value = $this->validate($value, __FUNCTION__);
        $parser = $this->getParser();
        if (!$parser) {
            return $value;
        }

        return $parser->parse($value);
    }

    /**
     * @param $data
     * @param $method
     * @return mixed
     * @throws DataWasWrongTypeException
     * @throws ValueWasWrongTypeException
     */
    public function validate($data, $method)
    {
        foreach ($this->getValidators() as $validator) {
            if ($validator::validate($data) === false) {
                throw $this->getException($data, $method);
            }
        }

        return $data;
    }

    /**
     * @return mixed
     */
    public function getValidators()
    {
        return $this->validators;
    }

    /**
     * @return mixed
     */
    public function getExceptionMessage()
    {
        return $this->exceptionMessage;
    }

    /**
     * @param $dataValue
     * @param $method
     * @return Exception|ValueWasWrongTypeException|DataWasWrongTypeException
     */
    protected function getException($dataValue, $method)
    {
        $message = $this->getExceptionMessage();
        switch ($method) {
            case 'getValueFromData':
                return new DataWasWrongTypeException($message, $dataValue);
                break;
            case 'getDataFromValue':
                return new ValueWasWrongTypeException($message, $dataValue);
                break;
            default:
                return new Exception('Invalid method');
                break;
        }
    }

    /**
     * create htmlpurifier xss for markdown
     * @return object
     */
    public function getPurifier()
    {
        $config = HTMLPurifier_Config::createDefault();
        $config->set('HTML.Doctype', 'HTML 4.01 Transitional');
        $config->set('HTML.SafeIframe', true);
        // Set some HTML5 properties
        $config->set('HTML.DefinitionID', 'html5-definitions'); // unique id
        $config->set('HTML.DefinitionRev', 1);
        $config->set('Cache.DefinitionImpl', null);
        $config->set('Core.Encoding', 'UTF-8');

        // Allow purify tags
        $allowed = $this->getAllowed();
        $config->set('HTML.Allowed', implode(',', $allowed));

        // Insert new elements not supported by purify
        // check example doc https://github.com/kennberg/php-htmlpurfier-html5/blob/master/htmlpurifier_html5.php
        if ($def = $config->maybeGetRawHTMLDefinition()) {
            $schemaTypes = [
                'itemtype'  => 'URI',
                'itemscope' => 'Bool',
                'itemprop' => 'Text',
                'content' => 'URI',
            ];

            $elements = [
                ['figure', 'Block', 'Flow', 'Common'],
                ['figcaption', 'Inline', 'Flow', 'Common'],
                ['div', 'Block', 'Flow', 'Common', $schemaTypes],
                ['span', 'Block', 'Flow', 'Common', $schemaTypes],
                ['p', 'Block', 'Flow', 'Common', $schemaTypes],
            ];

            $attributes = [
                ['figure', 'data-markdown', 'Text'],
                ['img', 'loading', 'Text'],
                ['img', 'srcset', 'Text'],
                ['img', 'sizes', 'Text'],
                ['a', 'rel', 'Text'],
                ['a', 'target', 'Text'],
                ['iframe', 'src', 'Text'],
                ['iframe', 'height', 'Text'],
                ['iframe', 'width', 'Text'],
                ['iframe', 'scrolling', 'Text'],
                ['iframe', 'frameborder', 'Text'],
                ['iframe', 'allowfullscreen', 'Text'],
                ['iframe', 'data-height', 'Text'],
                ['iframe', 'data-width', 'Text'],
                ['iframe', 'data-src', 'Text'],
                ['iframe', 'loading', 'Text'],
            ];

            // Adding elements
            foreach ($elements as $key => $element) {
                // elementName, type, contents, attrCollections, array
                if (!empty($element[4])) {
                    $def->addElement($element[0], $element[1], $element[2], $element[3], $element[4]);
                    continue;
                }
                $def->addElement($element[0], $element[1], $element[2], $element[3]);
            }

            // adding attributes
            foreach ($attributes as $key => $attribute) {
                // elementName, attrName, def
                $def->addAttribute($attribute[0], $attribute[1], $attribute[2]);
            }
        }

        return new HTMLPurifier($config);
    }

    /**
     * @return mixed
     */
    public function getParser()
    {
        return null;
    }

    /**
     * This function return the base config for HTML_purify
     *
     * @return array
     */
    protected function getAllowed(): array
    {
        $globalAttributes = ['class', 'id', 'title'];

        $allowed = [
            'div' => ['itemscope', 'itemtype', 'itemprop'],
            'span' => ['itemscope', 'itemtype', 'itemprop'],
            'p' => ['itemscope', 'itemtype', 'itemprop'],
            'figure' => ['data-markdown'],
            'figcaption' => [],
            'h1' => [],
            'h2' => [],
            'h3' => [],
            'h4' => [],
            'h5' => [],
            'h6' => [],
            'a' => ['href', 'rel', 'target'],
            'img' => ['src', 'alt', 'loading', 'srcset', 'sizes'],
            'ul' => [],
            'ol' => [],
            'li' => [],
            'pre' => [],
            'br' => [],
            'em' => [],
            'blockquote' => [],
            'iframe' => ['src', 'loading', 'height', 'width', 'frameborder', 'scrolling', 'allowfullscreen', 'data-height', 'data-width', 'data-src'],
        ];

        return array_map(function($element, $elementAttributes) use ($globalAttributes) {
            $mergedAttributes = array_merge($globalAttributes, $elementAttributes);
            if (empty($mergedAttributes)) {
                return $element;
            }

            $attributes = implode('|', $mergedAttributes);
            return $element . '[' . $attributes . ']';
        }, array_keys($allowed), $allowed);
    }
}

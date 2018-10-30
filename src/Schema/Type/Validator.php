<?php declare(strict_types=1);

namespace Shale\Schema\Type;

use Exception;
use Shale\Exception\Schema\DataEncode\ValueWasWrongTypeException;
use Shale\Exception\Schema\DataWasWrongTypeException;
use Shale\Schema\TypeRegistry;
use HTMLPurifier;
use HTMLPurifier_Config;

trait Validator
{

    /**
     * @param $data
     * @param TypeRegistry $typeRegistry
     * @return mixed
     */
    public function getValueFromData($data, TypeRegistry $typeRegistry)
    {
        $data = $this->validate($data, __FUNCTION__);
        $parser = $this->getParser();
        if ($parser == false) {
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
     * @return mixed
     */
    public function getDataFromValue($value, TypeRegistry $typeRegistry)
    {
        $value = $this->validate($value, __FUNCTION__);
        $parser = $this->getParser();
        if ($parser == false) {
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
        $config->set('HTML.AllowedAttributes', $allowed['attributes']);
        $config->set('HTML.Allowed', $allowed['allowed']);

        // Insert new elements not supported by purify
        // check example doc https://github.com/kennberg/php-htmlpurfier-html5/blob/master/htmlpurifier_html5.php
        if ($def = $config->maybeGetRawHTMLDefinition()) {
            $def->addElement(
                'figure',
                'Block',
                'Flow',
                'Common'
            );
            $def->addAttribute(
                'figure',
                'data-markdown',
                'Text'
            );
            $def->addElement(
                'figcaption',
                'Inline',
                'Flow',
                'Common'
            );
            $def->addAttribute(
                'iframe',
                'src',
                'Text'
            );

            $def->addAttribute(
                'iframe',
                'height',
                'Text'
            );
            $def->addAttribute(
                'iframe',
                'width',
                'Text'
            );
            $def->addAttribute(
                'iframe',
                'scrolling',
                'Text'
            );
            $def->addAttribute(
                'iframe',
                'frameborder',
                'Text'
            );
            $def->addAttribute(
                'iframe',
                'allowfullscreen',
                'Text'
            );
        }

        return new HTMLPurifier($config);
    }

    /**
     * @return bool
     */
    public function getParser()
    {
        return false;
    }

    /**
     * This function return the base config for HMLT_purify
     * @return array
     */
    protected function getAllowed() : array
    {
        return [
            'allowed' => 'figure, figcaption, pre, h1, h2, h3, h4, h5, h6, p, a, img, ul, ol, li, blockquote, br, em',
            'attributes' => '*.class, *.id, img.src, iframe.src, iframe.height, iframe.width, iframe.frameborder, iframe.scrolling, iframe.allowfullscreen, *.attr, a.href, *.alt, *.data'
        ];
    }
}

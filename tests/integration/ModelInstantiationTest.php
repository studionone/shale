<?php

declare(strict_types=1);

use Doctrine\Common\Annotations\AnnotationReader;
use Shale\Schema\Type\StringPrimitive;
use Shale\Schema\Type\NumberPrimitive;
use Shale\Schema\TypeRegistry;
use Shale\Schema\Engine;
use Shale\Schema\FqcnLoader;
use PHPUnit\Framework\TestCase;
use Shale\Exception\Schema\{
    RequiredPropertyMissingException,
    RequiredPropertyWasNullException
};
use Shale\Util\ClassLoader;

class ModelInstantiationTest extends TestCase
{
    public $schemaEngine;

    public function setUp(): void
    {
        $stringPrimitive = new StringPrimitive();
        $numberPrimitive = new NumberPrimitive();

        $typeRegistry = new TypeRegistry($stringPrimitive, $numberPrimitive);
        $annotationReader = new AnnotationReader();
        $this->schemaEngine = new Engine($typeRegistry, $annotationReader);
        $modelFqcns = ClassLoader::getClassesInPath(__DIR__ . '/../support/Mock/Model/');

        $this->schemaEngine->loadSchemaForModels($modelFqcns);
    }

    public function loadDataFromJsonFile(string $filename)
    {
        $json_string = file_get_contents(__DIR__ . '/data/' . $filename);
        $json_data = json_decode($json_string);

        if ($json_data === null) {
            throw new Exception('Error parsing JSON from the filesystem');
        }

        return $json_data;
    }

    /**
     * Test we can load JSON representing a single TagModel object
     */
    public function testLoadingTagObjectJson()
    {
        $jsonData = $this->loadDataFromJsonFile(
            'sample_tag_object.json');
        $rootModelFqcn = 'Shale\\Test\\Support\\Mock\\Model\\TagModel';

        $modelInstance = $this
            ->schemaEngine
            ->createModelInstanceFromData($rootModelFqcn, $jsonData);

        // Created object should be a TagModel instance
        $this->assertInstanceOf($rootModelFqcn, $modelInstance);

        $this->assertEquals(6001, $modelInstance->getId());
        $this->assertEquals("beach", $modelInstance->getName());
    }

    /**
     * Test we can load JSON representing a ResponseModel, following our
     * current "reduced" schema.
     *
     * The reduced schema includes the basic structure of a response,
     * including a payload with a list of modules. However, the included
     * "article module" doesn't have the full set of properties (eg
     * author, chapters, images) seen in the sample data from Tourism
     * Media
     */
    public function testLoadingReducedJson()
    {
        $jsonData = $this->loadDataFromJsonFile(
            'reduced_sample_article_response_001.json');
        $responseModelFqcn = 'Shale\\Test\\Support\\Mock\\Model\\ResponseModel';

        $modelInstance = $this
            ->schemaEngine
            ->createModelInstanceFromData($responseModelFqcn, $jsonData);

        // Created object should be a ResponseModel instance
        $this->assertInstanceOf($responseModelFqcn, $modelInstance);

        $this->assertEquals(200, $modelInstance->getStatus());
        $this->assertEquals("OK", $modelInstance->getMessage());

        // Response should have a payload of PayloadModel instance)
        $payload = $modelInstance->getPayload();
        $this->assertInstanceOf('Shale\\Test\\Support\\Mock\\Model\\PayloadModel', $payload);

        // Payload should have a modules array, with exactly 1 item
        $modules = $payload->getModules();
        $this->assertEquals(1, count($modules));

        // The item should be an article module (ArticleModel instance)
        $article = $modules[0];
        $this->assertInstanceOf(
            'Shale\\Test\\Support\\Mock\\Model\\Module\\ArticleModel', $article);
        // Article's ID and region ID should have certain values
        $this->assertEquals(1001, $article->getId());
        $this->assertEquals("2001", $article->getRegionId());

        // Article's tags list should have a single tag
        $tags = $article->getTags();
        $this->assertEquals(1, count($tags));
        // The single tag should have ID "6001" and name "beach"
        $this->assertEquals(6001, $tags[0]->getId());
        $this->assertEquals("beach", $tags[0]->getName());
    }

    /**
     * Tests we can handle an empty typed array.
     *
     * We test this by loading JSON representing an article with no
     * tags.
     *
     */
    public function testEmptyTypedArray()
    {
        $jsonData = $this->loadDataFromJsonFile(
            'article_module_with_empty_tags.json');
        $articleModelFqcn = 'Shale\\Test\\Support\\Mock\\Model\\Module\\ArticleModel';

        $article = $this
            ->schemaEngine
            ->createModelInstanceFromData($articleModelFqcn, $jsonData);
        $this->assertInstanceOf(
            'Shale\\Test\\Support\\Mock\\Model\\Module\\ArticleModel', $article);

        // Article's ID and region ID should have certain values
        $this->assertEquals(1004, $article->getId());
        $this->assertEquals("2007", $article->getRegionId());

        // Article's tags list should be empty
        $tags = $article->getTags();
        $this->assertEquals(0, count($tags));
    }

    /**
     * Tests we can handle a typed array with multiple items.
     *
     * We test this by loading JSON representing an article with
     * multiple tags.
     *
     */
    public function testTypedArrayWithMultipleItems()
    {
        $jsonData = $this->loadDataFromJsonFile(
            'article_module_with_multiple_tags.json');
        $articleModelFqcn = 'Shale\\Test\\Support\\Mock\\Model\\Module\\ArticleModel';

        $article = $this
            ->schemaEngine
            ->createModelInstanceFromData($articleModelFqcn, $jsonData);
        $this->assertInstanceOf(
            'Shale\\Test\\Support\\Mock\\Model\\Module\\ArticleModel', $article);

        // Article's ID and region ID should have certain values
        $this->assertEquals(1003, $article->getId());
        $this->assertEquals("2005", $article->getRegionId());

        // Article's tags list should have 3 tags
        $tags = $article->getTags();
        $this->assertEquals(3, count($tags));
        // The first tag should have ID 6001 and name "beach"
        $this->assertEquals(6001, $tags[0]->getId());
        $this->assertEquals("beach", $tags[0]->getName());
        // The second tag should have ID 6002 and name "summer"
        $this->assertEquals(6002, $tags[1]->getId());
        $this->assertEquals("summer", $tags[1]->getName());
        // The second tag should have ID 6002 and name "summer"
        $this->assertEquals(6017, $tags[2]->getId());
        $this->assertEquals("colorful", $tags[2]->getName());
    }

    /**
     * Test we can handle an empty mixed object array.
     *
     * We test this by loading JSON representing a payload with no
     * modules.
     */
    public function testEmptyMixedObjectArray()
    {
        $jsonData = $this->loadDataFromJsonFile(
            'payload_with_no_modules.json');
        $payloadModelFqcn = 'Shale\\Test\\Support\\Mock\\Model\\PayloadModel';

        $payload = $this
            ->schemaEngine
            ->createModelInstanceFromData($payloadModelFqcn, $jsonData);
        $this->assertInstanceOf(
            'Shale\\Test\\Support\\Mock\\Model\\PayloadModel', $payload);

        // Payload's "modules" list should be empty
        $modules = $payload->getModules();
        $this->assertEquals(0, count($modules));
    }

    /**
     * Test we can handle a mixed object array with multiple items.
     *
     * We test this by loading JSON representing a payload with
     * multiple items in its "modules" property.
     *
     * One of these items is an article module, the other two are tag
     * objects. No, this structure doesn't make much sense in our real
     * app, but it gives us what we need to test our schema / model
     * hydrator system.
     */
    public function testMixedObjectArrayWithMultipleItems()
    {
        $jsonData = $this->loadDataFromJsonFile(
            'payload_with_multiple_modules.json');
        $payloadModelFqcn = 'Shale\\Test\\Support\\Mock\\Model\\PayloadModel';

        $payload = $this
            ->schemaEngine
            ->createModelInstanceFromData($payloadModelFqcn, $jsonData);
        $this->assertInstanceOf(
            'Shale\\Test\\Support\\Mock\\Model\\PayloadModel', $payload);

        // Payload's "modules" list should contain 3 objects
        $modules = $payload->getModules();
        $this->assertEquals(3, count($modules));

        // First object should be an article module
        $this->assertInstanceOf(
            'Shale\\Test\\Support\\Mock\\Model\\Module\\ArticleModel',
            $modules[0]);
        $this->assertEquals(1004, $modules[0]->getId());
        // Second should be a tag object
        $this->assertInstanceOf(
            'Shale\\Test\\Support\\Mock\\Model\\TagModel',
            $modules[1]);
        $this->assertEquals(6004, $modules[1]->getId());
        // Third should be a tag object
        $this->assertInstanceOf(
            'Shale\\Test\\Support\\Mock\\Model\\TagModel',
            $modules[2]);
        $this->assertEquals(6006, $modules[2]->getId());
    }

    /**
     * Test we can handle an object with no properties.
     *
     * We test this by loading a payload with a single item in its
     * "modules" array: an object of the specially created EmptyModel
     * type.
     */
    public function testObjectWithNoProperties()
    {
        $jsonData = $this->loadDataFromJsonFile(
            'payload_with_empty_model_object.json');
        $payloadModelFqcn = 'Shale\\Test\\Support\\Mock\\Model\\PayloadModel';

        $payload = $this
            ->schemaEngine
            ->createModelInstanceFromData($payloadModelFqcn, $jsonData);
        $this->assertInstanceOf(
            'Shale\\Test\\Support\\Mock\\Model\\PayloadModel', $payload);

        // Payload's "modules" list should contain 1 object
        $modules = $payload->getModules();
        $this->assertEquals(1, count($modules));

        // The single object should be an EmptyModel instance
        $this->assertInstanceOf(
            'Shale\\Test\\Support\\Mock\\Model\\EmptyModel',
            $modules[0]);
    }

    public function testObjectWithRequiredPropertyNotGiven()
    {
        $this->expectException(RequiredPropertyMissingException::class);
        $jsonData = $this->loadDataFromJsonFile(
            'article_module_with_no_id.json');
        $articleModelFqcn = 'Shale\\Test\\Support\\Mock\\Model\\Module\\ArticleModel';

        $article = $this
            ->schemaEngine
            ->createModelInstanceFromData($articleModelFqcn, $jsonData);
    }

    public function testObjectWithRequiredPropertySetToNull()
    {
        $this->expectException(RequiredPropertyWasNullException::class);
        $jsonData = $this->loadDataFromJsonFile(
            'article_module_with_id_set_to_null.json');
        $articleModelFqcn = 'Shale\\Test\\Support\\Mock\\Model\\Module\\ArticleModel';

        $article = $this
            ->schemaEngine
            ->createModelInstanceFromData($articleModelFqcn, $jsonData);
    }

    /**
     *
     */
    public function testObjectWithOptionalPropertyNotGiven()
    {
        $jsonData = $this->loadDataFromJsonFile(
            'article_module_with_no_regionId.json');
        $articleModelFqcn = 'Shale\\Test\\Support\\Mock\\Model\\Module\\ArticleModel';

        $article = $this
            ->schemaEngine
            ->createModelInstanceFromData($articleModelFqcn, $jsonData);
        $this->assertInstanceOf(
            'Shale\\Test\\Support\\Mock\\Model\\Module\\ArticleModel', $article);

        // Article's ID should have a value
        $this->assertEquals("1003", $article->getId());

        // Article's region ID should be null
        $this->assertNull($article->getRegionId());

        // Article's tags list should have a value
        $tags = $article->getTags();
        $this->assertEquals(3, count($tags));
    }

    /**
     *
     */
    public function testObjectWithOptionalPropertySetToNull()
    {
        $jsonData = $this->loadDataFromJsonFile(
            'article_module_with_regionId_set_to_null.json');
        $articleModelFqcn = 'Shale\\Test\\Support\\Mock\\Model\\Module\\ArticleModel';

        $article = $this
            ->schemaEngine
            ->createModelInstanceFromData($articleModelFqcn, $jsonData);
        $this->assertInstanceOf(
            'Shale\\Test\\Support\\Mock\\Model\\Module\\ArticleModel', $article);

        // Article's ID should have a value
        $this->assertEquals(1003, $article->getId());

        // Article's region ID should be null
        $this->assertNull($article->getRegionId());

        // Article's tags list should have a value
        $tags = $article->getTags();
        $this->assertEquals(3, count($tags));
    }
}

<?php
declare(strict_types = 1);

namespace PTS\DataTransformer;

use PHPUnit\Framework\TestCase;
use PTS\JsonSchema;

class ValidatorTest extends TestCase
{
    /** @var JsonSchema */
    protected $validator;

    public function setUp()
    {
        $this->validator = new JsonSchema;
    }

    public function testConstructor()
    {
        self::assertInstanceOf(JsonSchema::class, $this->validator);
        self::assertEquals('http://demo.org', $this->validator->getBaseUri());
    }

    public function testLoadSchemas()
    {
        $schemasDir =  dirname(__DIR__). '/schemas/';
        $this->validator->loadAllSchemas($schemasDir);
        $schema = $this->validator->resolveSchemaByPath('v1/regions/get.json');

        self::assertInstanceOf(\stdClass::class, $schema);
        self::assertEquals('http://demo.org/v1/regions/get.json', $schema->id);
    }

    public function testPrepareSchemaDirPath()
    {
        $schemasDir =  dirname(__DIR__). '/schemas';
        $this->validator->loadAllSchemas($schemasDir);
        $schema = $this->validator->resolveSchemaByPath('v1/regions/region-model.json');
        self::assertEquals('http://demo.org/v1/regions/region-model.json', $schema->id);
    }

    public function testValidateResponse()
    {
        $schemasDir =  dirname(__DIR__). '/schemas/';
        $this->validator->loadAllSchemas($schemasDir);
        $responseBody = \json_encode([
            'id' => '123',
            'name' => 'Penza'
        ]);

        $errors = $this->validator->validateJsonSchema($responseBody, 'v1/regions/region-model.json');
        self::assertNull($errors);
    }

    public function testValidateResponseWithError()
    {
        $schemasDir =  dirname(__DIR__). '/schemas/';
        $this->validator->loadAllSchemas($schemasDir);
        $responseBody = \json_encode([
            'id' => '123',
        ]);

        $errorsMessage = $this->validator->validateJsonSchema($responseBody, 'v1/regions/region-model.json');
        self::assertNotNull($errorsMessage);
        self::assertEquals('[name] Is missing and it is required', $errorsMessage);
    }

    public function testValidateInvalidResponse()
    {
        $schemasDir =  dirname(__DIR__). '/schemas/';
        $this->validator->loadAllSchemas($schemasDir);
        $responseBody = \json_encode('');

        $errorsMessage = $this->validator->validateJsonSchema($responseBody, 'v1/regions/region-model.json');
        self::assertNotNull($errorsMessage);
        self::assertEquals('[] String value found, but an object is required', $errorsMessage);
    }

    public function testSetBaseUri()
    {
        $this->validator->setBaseUri('http://other.org');
        $schemasDir =  dirname(__DIR__). '/schemas/';
        $this->validator->loadAllSchemas($schemasDir);

        $schema = $this->validator->resolveSchemaByPath('v1/uri.json');
        self::assertEquals('http://other.org/v1/uri.json', $schema->id);
    }

}
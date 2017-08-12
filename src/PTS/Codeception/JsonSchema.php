<?php
namespace PTS\modules;

use Codeception\Configuration;
use Codeception\Module;

/**
 * Json schema module for codeception
 */
class JsonSchema extends Module
{
    const SCHEMA_URI = 'http://demo.org';

    /** @var \PTS\JsonSchema */
    protected $validator;

    public function _beforeSuite($settings = [])
    {
        $this->init();
    }

    protected function init()
    {
        if (!array_key_exists('schemaDir', $this->config)) {
            throw new \Exception('Config `schemaDir` is required ');
        }

        $this->validator = new \PTS\JsonSchema;

        if (!array_key_exists('baseUri', $this->config)) {
            $this->validator->setBaseUri($this->config['baseUri']);
        }

        $schemasDir = realpath(Configuration::testsDir() . '/' . $this->config['schemaDir']);
        $this->validator->loadAllSchemas($schemasDir);
    }

    public function jsonSchema(string $response, string $pathSchema): void
    {
        $errors = $this->validator->validateJsonSchema($response, $pathSchema);
        $this->assertTrue($errors !== null, $errors);
    }
}
<?php
namespace PTS;

use FilesystemIterator;
use JsonSchema\Constraints\Factory;
use JsonSchema\SchemaStorage;
use JsonSchema\Validator;
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;

/**
 * Json schema module for codeception
 */
class JsonSchema
{
    protected $baseUri = 'http://demo.org';

    /** @var Validator */
    protected $validator;
    /** @var SchemaStorage */
    protected $schemaStorage;

    public function __construct()
    {
        $this->schemaStorage = new SchemaStorage;
        $this->validator = new Validator( new Factory($this->schemaStorage));
    }

    public function setBaseUri(string $baseUri = 'http://demo.org'): void
    {
        $this->baseUri = $baseUri;
    }

    public function getBaseUri(): string
    {
        return $this->baseUri;
    }

    public function loadAllSchemas(string $schemaDir): void
    {
        $schemaDir = $this->prepareDirPath($schemaDir);

        $iterator = new RecursiveDirectoryIterator($schemaDir, FilesystemIterator::SKIP_DOTS);
        foreach (new RecursiveIteratorIterator($iterator) as $path) {
            $relPath = str_replace($schemaDir, '', $path);
            $schema = json_decode(file_get_contents($path));
            $this->schemaStorage->addSchema($this->baseUri . $relPath, $schema);
        }
    }

    protected function prepareDirPath(string $schemaDir): string
    {
        if (substr($schemaDir, -1) !== DIRECTORY_SEPARATOR) {
            $schemaDir .= DIRECTORY_SEPARATOR;
        }

        return $schemaDir;
    }

    public function resolveSchemaByPath(string $path): \stdClass
    {
        return $this->schemaStorage->getSchema($this->baseUri . $path);
    }

    public function validateJsonSchema(string $response, string $pathSchema): ?string
    {
        $responseData = json_decode($response);
        $schema = $this->resolveSchemaByPath($pathSchema);

        $this->validator->reset();
        $this->validator->validate($responseData, $schema);
        $isValid = $this->validator->isValid();

        return $this->getErrorMessage($isValid);
    }

    protected function getErrorMessage(bool $isValid): ?string
    {
        if ($isValid) {
            return null;
        }

        $message = [];
        foreach ($this->validator->getErrors() as $error) {
            $message[] = sprintf("[%s] %s", $error['property'], $error['message']);
        }

        return implode('; ', $message);
    }
}

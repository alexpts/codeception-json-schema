# json-schema

[![SensioLabsInsight](https://insight.sensiolabs.com/projects/de0407d9-12fe-4d3d-a688-9b29b10a0e46/big.png)](https://insight.sensiolabs.com/projects/de0407d9-12fe-4d3d-a688-9b29b10a0e46)

[![Build Status](https://travis-ci.org/alexpts/codeception-json-schema.svg?branch=master)](https://travis-ci.org/alexpts/codeception-json-schema)
[![Code Coverage](https://scrutinizer-ci.com/g/alexpts/codeception-json-schema/badges/coverage.png?b=master)](https://scrutinizer-ci.com/g/alexpts/codeception-json-schema/?branch=master)
[![Code Climate](https://codeclimate.com/github/alexpts/codeception-json-schema/badges/gpa.svg)](https://codeclimate.com/github/alexpts/codeception-json-schema)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/alexpts/codeception-json-schemar/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/alexpts/codeception-json-schema/?branch=master)


Simple validator json schema + module for codeception.


Example:

```php
use \PTS\JsonSchema;

$validator = new JsonSchema;
$schemasDir = dirname(__DIR__). '/schemas/';
$validator->loadAllSchemas($schemasDir);
$responseBody = \json_encode(['id' => 1, 'name' => 'Alex']);

$errorsMessage = $validator->validateJsonSchema($responseBody, 'v1/users/user-model.json');
if (null !== $errorsMessage) {
    throw \Exception($errorsMessage);
}

```

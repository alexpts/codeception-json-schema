# json-schema

[![SensioLabsInsight](https://insight.sensiolabs.com/projects/20e239e7-e00e-46a0-b328-a2a31864b841/big.png)](https://insight.sensiolabs.com/projects/20e239e7-e00e-46a0-b328-a2a31864b841)

[![Build Status](https://travis-ci.org/alexpts/codeception-json-schema.svg?branch=master)](https://travis-ci.org/alexpts/codeception-json-schema)
[![Code Coverage](https://scrutinizer-ci.com/g/alexpts/codeception-json-schema/badges/coverage.png?b=master)](https://scrutinizer-ci.com/g/alexpts/codeception-json-schema/?branch=master)
[![Code Climate](https://codeclimate.com/github/alexpts/codeception-json-schema/badges/gpa.svg)](https://codeclimate.com/github/alexpts/codeception-json-schema)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/alexpts/codeception-json-schema/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/alexpts/codeception-json-schema/?branch=master)


Simple validator json schema + module for codeception.


Example (without codeception):

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

Example (codeception module): 
...

```php
class RegionsCest
{
    public function _before(FunctionalTester $I)
    {
        $I->haveHttpHeader('Authorization', 'Bearer xxx');
    }

    public function getRegionsList(FunctionalTester $I)
    {
        $I->sendGET('/v1/regions/');
        $I->seeResponseCodeIs(200);
        $I->validateJsonSchema($I->grabResponse(), '/v1/regions/get.json');
    }
}
```

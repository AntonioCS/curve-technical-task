<?php

namespace App\Tests;

use App\Service\ExchangeService\ExchangeAPIService;
use BenMajor\ExchangeRatesAPI\ExchangeRatesAPI;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use App\DTO\Rate;

class ExchangeAPIServiceTest extends TestCase
{
    private ExchangeAPIService $obj;
    private const JSON_DATA = <<<JSON
{
   "statusCode":200,
   "timestamp":"2020-10-09T11:44:12+00:00",
   "baseCurrency":"EUR",
   "rates":{
      "2020-10-08":{
         "USD":1.1765,
         "GBP":0.91035
      },
      "2020-10-05":{
         "USD":1.1768,
         "GBP":0.9081
      },
      "2020-10-07":{
         "USD":1.177,
         "GBP":0.91413
      },
      "2020-10-06":{
         "USD":1.1795,
         "GBP":0.91058
      },
      "2020-10-02":{
         "USD":1.173,
         "GBP":0.90673
      }
   }
}
JSON;


    public function setUp() {
        $this->obj = new ExchangeAPIService($this->correctMock());
    }

    public function testFetch() : void
    {
        $result = $this->obj->fetch();
        self::assertCount(10, $result);
    }

    public function testFetchRates() : void
    {
        $result = $this->obj->fetch();
        foreach ($result as $rate) {
            self::assertInstanceOf(Rate::class, $rate);
        }
    }

    private function correctMock() : MockObject {
        $mock = $this->createMock(ExchangeRatesAPI::class);

        $mock->method('addDateFrom')
            ->willReturn($mock);

        $mock->method('addDateTo')
            ->willReturn($mock);

        $mock->method('addRates')
            ->willReturn($mock);

        $mock->method('fetch')
            ->willReturn(self::JSON_DATA);

        return $mock;
    }
}

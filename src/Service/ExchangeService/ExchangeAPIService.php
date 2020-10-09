<?php
declare(strict_types=1);

namespace App\Service\ExchangeService;

use App\DTO\Rate;
use \BenMajor\ExchangeRatesAPI\ExchangeRatesAPI;
use \BenMajor\ExchangeRatesAPI\Exception as ExchangeRatesAPIException;

class ExchangeAPIService
{
    private array $default_rates = ['GBP','USD'];
    private ExchangeRatesAPI $service;


    public function __construct(ExchangeRatesAPI $exchangeRatesAPI)
    {
        $this->service = $exchangeRatesAPI;
    }

    public function fetch() : array {
        $data = \json_decode($this->fetchDataFromExternalSource(), true, 512, JSON_THROW_ON_ERROR);
        $result = [];

        if (isset($data['rates'])) {
            $this->convertToEntities($data['rates'], $result);
        }

        return $result;
    }

    /**
     * @TODO: In future iteration conver this using symfony serializer
     */
    private function convertToEntities(array $data, array &$result) : void {
        foreach ($data as $date => $rates) {
            foreach ($rates as $currency => $value) {
                $result[] = new Rate(new \DateTime($date), $currency, $value);
            }
        }
    }

    private function getDataRange() : array
    {
        $dateTime = new \DateTime();
        $today = $dateTime->format("Y-m-d");
        $dateTime->modify('-7 day');
        $oneWeekAgo = $dateTime->format("Y-m-d");

        return [
            'one_week_ago' => $oneWeekAgo,
            'today' => $today
        ];
    }

    private function fetchDataFromExternalSource() : string
    {
        $dates = $this->getDataRange();

        try {
            $result =  $this->service
                ->addDateFrom($dates['one_week_ago'])
                ->addDateTo($dates['today'])
                ->addRates($this->default_rates)
                ->fetch(true, false)
            ;

            return $result;
        } catch (ExchangeRatesAPIException $exception) {
            throw new \RuntimeException('Exchange-error: '  . $exception->getMessage());
        }
    }

}

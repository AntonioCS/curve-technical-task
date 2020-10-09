<?php
declare(strict_types=1);

namespace App\Service;

use App\DTO\Rate;
use App\Service\ExchangeService\ExchangeAPIService;
use App\Service\ExchangeMoneyService\ExchangeMoneyService;

class Manager
{
    private ExchangeAPIService $exchangeService;
    private ExchangeMoneyService $exchangeMoneyService;

    public function __construct(ExchangeAPIService $exchangeService, ExchangeMoneyService $exchangeMoneyService)
    {
        $this->exchangeService = $exchangeService;
        $this->exchangeMoneyService = $exchangeMoneyService;
    }

    public function process() : array {
        $data = $this->exchangeService->fetch();

        if (empty($data)) {
            throw new \RuntimeException("No data available");
        }

        /* @var $data_split array */
        $data_split = $this->splitData($data);

        /* @var $ratesCollection Rate[] */
        /* @var $currency string */
        foreach ($data_split as $currency => $ratesCollection) {
            //as I am using references I must pass data using the keys and not the variable php created
            //(as it would overwrite the data)
            $this->sortData($data_split[$currency]);
        }

        $result_latest = $this->getLatestCurrencyValues($data_split);
        $result_analyse = $this->exchangeMoneyService->analyse($data_split);


        return [
            'Currency-Values' => $result_latest,
            'Exchange' => $result_analyse
        ];
    }

    private function getLatestCurrencyValues(array $data) : array {
        $result = [];

        /* @var $ratesCollection Rate[] */
        /* @var $currency string */
        foreach ($data as $currency => $ratesCollection) {

            /* @var $lastRate Rate */
            $lastRate = \end($ratesCollection);
            $result[$currency] = $lastRate->getValue();
        }

        return $result;
    }

    private function sortData(array &$data) : void {
        \usort($data, static function(Rate $a, Rate $b) {
            if ($a->getDate() == $b->getDate()) {
                return 0;
            }
            return ($a->getDate() < $b->getDate()) ? -1 : 1;
        });
    }

    private function splitData(array $data) : array {
        $result = [];

        /** @var Rate $rate */
        foreach ($data as $rate) {
            $result[$rate->getCurrency()][] = $rate;
        }

        return $result;
    }
}

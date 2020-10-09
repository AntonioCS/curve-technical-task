<?php

declare(strict_types=1);

namespace App\Service\ExchangeMoneyService;

use App\DTO\Rate;

class ExchangeMoneyService
{
    /**
     * @param array $data Sorted data
     */
    public function analyse(array $data) : array {
        $result = [];

        foreach ($data as $currency => $ratesCollection) {
            $currenciesValues = $this->extractCurrencyValues($ratesCollection);
            $result[$currency] = $this->isThisAGoodTimeToExchange($currenciesValues);
        }

        return $result;
    }

    private function isThisAGoodTimeToExchange(array $data) : bool {
        $loopsMax = \count($data);

        for ($i = 0, $yes = 0; $i < $loopsMax; $i++) {
            if ($i === 0) {
                continue;
            }

            if ($data[$i] > $data[$i-1]) {
                $yes++;
            }
        }

        return ($yes >= ($loopsMax / 2));
    }

    private function extractCurrencyValues(array $ratesCollection) : array {
        $result = [];

        /** @var Rate $rate */
        foreach ($ratesCollection as $rate) {
            $result[] = $rate->getValue();
        }

        return $result;
    }

}

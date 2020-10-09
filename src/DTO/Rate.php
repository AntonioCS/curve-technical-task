<?php


namespace App\DTO;


use Symfony\Component\Validator\Constraints as Assert;

class Rate
{
    /**
     * @Assert\Date
     */
    private \DateTime $date;

    /**
     * @Assert\Currency
     */
    private string $currency;

    /**
     * @Assert\Type("float")
     */
    private float $value;

    public function __construct(\DateTime $date, string $currency, float $value)
    {
        $this->date = $date;
        $this->currency = $currency;
        $this->value = $value;
    }

    public function getDate() : \DateTime
    {
        return $this->date;
    }

    public function getCurrency() : string
    {
        return $this->currency;
    }

    public function getValue() : float
    {
        return $this->value;
    }

    public function __toString() : string
    {
        return $this->getDate()->format('Y-m-d') . ' - ' . $this->getCurrency() . ' - ' . $this->getValue();
    }
}

<?php

namespace App\Services;

class NetPayCalculationsService
{
    public $gross_salary_monthly;

    public function __construct($gross_salary_monthly)
    {
        $this->gross_salary_monthly = $gross_salary_monthly;
    }

    public function getEpfDeduction()
    {
        return round($this->gross_salary_monthly * 0.08, 2);
    }

    public function getEtfDeduction()
    {
        return round($this->gross_salary_monthly * 0.03, 2);
    }

    public function getTaxableIncomeAnnual()
    {
        $annualGross = $this->gross_salary_monthly * 12;
        $annualDeductions = ($this->getEpfDeduction() + $this->getEtfDeduction()) * 12;
        return max($annualGross - $annualDeductions, 0);
    }

    public function getAnnualPIT()
    {
        $tax = 0;
        $remainingIncome = $this->gross_salary_monthly;


        $taxBrackets = [
            [150000, 0.00],      // Up to 150,000 @ 0%
            [233333.33, 0.06],   // Next 83,333.33 @ 6%
            [275000, 0.18],      // Next 41,666.67 @ 18%
            [316666.66, 0.24],   // Next 41,666.66 @ 24%
            [358333.33, 0.30],  // Next 41,666.67 @ 30%
            [INF, 0.36]         // Above @ 36%
        ];

        $prevLimit = 0;

        foreach ($taxBrackets as [$limit, $rate]) {
            if ($remainingIncome <= 0) {
                break;
            }

            $bracketWidth = $limit - $prevLimit;
            $taxableAmount = min($remainingIncome, $bracketWidth);

            $tax += $taxableAmount * $rate;
            $remainingIncome -= $taxableAmount;
            $prevLimit = $limit;
        }

        return round($tax, 2);
    }

    public function getMonthlyPIT()
    {
        return round($this->getAnnualPIT(), 2);
    }

    public function getDeductionsMonthly()
    {
        return round(
            $this->getEpfDeduction() +
                $this->getEtfDeduction() +
                $this->getMonthlyPIT(),
            2
        );
    }

    public function getNetPayMonthly()
    {
        return round($this->gross_salary_monthly - $this->getDeductionsMonthly(), 2);
    }
}

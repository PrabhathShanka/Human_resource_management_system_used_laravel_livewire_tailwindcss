<?php

namespace App\Services;

class NetPayCalculationsService
{
    public $gross_salary_monthly;

    public function __construct($gross_salary_monthly)
    {
        $this->gross_salary_monthly = $gross_salary_monthly;
    }

    /**
     * EPF deduction (Employee) - 8% of gross salary
     */
    public function getEpfDeduction()
    {
        return round($this->gross_salary_monthly * 0.08, 2);
    }

    /**
     * ETF deduction (Employee) - 3% of gross salary
     */
    public function getEtfDeduction()
    {
        return round($this->gross_salary_monthly * 0.03, 2);
    }

    /**
     * Annual taxable income (after EPF + ETF)
     */
    public function getTaxableIncomeAnnual()
    {
        $annualGross = $this->gross_salary_monthly * 12;
        $annualDeductions = ($this->getEpfDeduction() + $this->getEtfDeduction()) * 12;
        return max($annualGross - $annualDeductions, 0);
    }

    /**
     * Annual Personal Income Tax (PIT / APIT)
     * Based on Sri Lanka Inland Revenue Dept. tax brackets from 01 April 2025
     */
    public function getAnnualPIT()
    {
        $income = $this->getTaxableIncomeAnnual();
        $tax = 0;

        // Tax-free threshold
        if ($income <= 1800000) {
            return 0;
        }

        // Define brackets (limit, rate)
        $brackets = [
            [2800000, 0.06],  // First 1,000,000 after threshold @ 6%
            [3300000, 0.18],  // Next 500,000 @ 18%
            [3800000, 0.24],  // Next 500,000 @ 24%
            [4300000, 0.30],  // Next 500,000 @ 30%
            [INF,      0.36]  // Above @ 36%
        ];

        $prev_limit = 1800000;

        foreach ($brackets as [$limit, $rate]) {
            if ($income > $prev_limit) {
                $band = min($income, $limit) - $prev_limit;
                $tax += $band * $rate;
                $prev_limit += $band;
            }
        }

        return round($tax, 2);
    }

    /**
     * Monthly PIT
     */
    public function getMonthlyPIT()
    {
        return round($this->getAnnualPIT() / 12, 2);
    }

    /**
     * Total deductions for the month
     */
    public function getDeductionsMonthly()
    {
        return round(
            $this->getEpfDeduction() +
                $this->getEtfDeduction() +
                $this->getMonthlyPIT(),
            2
        );
    }

    /**
     * Net salary for the month
     */
    public function getNetPayMonthly()
    {
        return round($this->gross_salary_monthly - $this->getDeductionsMonthly(), 2);
    }
}

<div>
    <div class="relative w-full mb-6">
        <flux:heading size="xl" level="1">Payrolls</flux:heading>
        <flux:subheading size="lg" class="mb-6">
            Payrolls Breakdown for {{ getCompany()->name }}
            during {{ \Carbon\Carbon::createFromDate($payroll->year, $payroll->month, 1)->format('F Y') }}
        </flux:subheading>
        <flux:separator />
    </div>

    <div class="flex flex-col">
        <div class="overflow-x-auto sm:-mx-6 lg:-mx-8">
            <div class="inline-block min-w-full py-2 align-middle sm:px-6 lg:px-8">
                <div class="overflow-hidden border-b border-gray-200 shadow sm:rounded-lg">
                    <table class="table min-w-full">
                        <thead class="bg-nos-200 dark:bg-nos-950">
                            <tr>
                                <th>#</th>
                                <th>Employee Details</th>
                                <th>Gross Salary</th>
                                <th>EPF</th>
                                <th>ETF</th>
                                <th>PAYE</th>
                                <th>Net Pay</th>
                                <th>Actions</th>
                            </tr>
                        </thead>

                        <tbody>
                            @foreach ($payroll->salaries as $key => $salary)
                                <tr class="text-center bg-nos-100 hover:bg-nos-50 dark:bg-nos-900 dark:hover:bg-nos-700">
                                    <td>{{ $key + 1 }}</td>

                                    <td class="flex flex-col items-center text-zinc-900 dark:text-white justify-left">
                                        <h3 class="text-zinc-900 dark:text-zinc-200">{{ $salary->employee->name }}</h3>
                                        <h5 class="text-sm text-zinc-700 dark:text-zinc-300">
                                            {{ $salary->employee->designation->name }}
                                        </h5>
                                    </td>

                                    <td>
                                        <sup>Rs</sup> {{ number_format($salary->gross_salary, 2) }}
                                    </td>

                                    <td>
                                        <sup>Rs</sup> {{ number_format($salary->breakdown->getEpfDeduction(), 2) }}
                                    </td>

                                    <td>
                                        <sup>Rs</sup> {{ number_format($salary->breakdown->getEtfDeduction(), 2) }}
                                    </td>

                                    <td>
                                        <sup>Rs</sup> {{ number_format($salary->breakdown->getMonthlyPIT(), 2) }}
                                    </td>

                                    <td>
                                        <sup>Rs</sup> {{ number_format($salary->breakdown->getNetPayMonthly(), 2) }}
                                    </td>

                                    <td>
                                        <flux:tooltip content="Download Payslip">
                                            <flux:button
                                                variant="filled"
                                                icon="document-arrow-down"
                                                wire:click="generatePayslip({{ $salary->id }})"
                                            />
                                        </flux:tooltip>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

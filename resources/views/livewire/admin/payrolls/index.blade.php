<div>
    <div class="relative w-full mb-6">
        <flux:heading size="xl" level="1">Payrolls</flux:heading>
        <flux:subheading size="lg" class="mb-6">Payrolls for {{ getCompany()->name }}</flux:subheading>
        <flux:separator />
    </div>

  <div class="flex items-center justify-between">
    <div class="w-full pr-4">
        <flux:input type="month" name="month" wire:model="monthYear" placeHolder="Choose a month"
            :invalid="$errors->has('monthYear')" />
        @error('monthYear')
            <div class="mt-1 text-sm text-red-600">{{ $message }}</div>
        @enderror
    </div>
    <div>
        <flux:button variant="primary" wire:click="generatePayroll">Generate Payroll</flux:button>
    </div>
</div>

    <div class="grid grid-cols-1 gap-6 mt-4 md:grid-cols-2 lg:grid-cols-4 lg:gap-4">
        @foreach ($payrolls as $payroll)
            <div class="p-6 transition duration-300 ease-in-out rounded-lg shadow-md bg-nos-100 dark:bg-nos-900 text-nos-900 dark:text-white hover:shadow-lg hover:bg-nos-300" wire:click="viewPayroll({{ $payroll->id }})">
                <div class="mb-4">
                    <h2 class="font-lg semibold text-">
                         {{ \Carbon\Carbon::createFromDate($payroll->year, $payroll->month, 1)->format('F Y') }}
                    </h2>
                    <p class="text-lg text-zinc-500 dark:text-zinc-400">
                        {{ getCompany()->name }}
                    </p>
                </div>
                <div class="flex flex-col justify-end text-right text-green-700">
                    <sup>RS</sup><span class ="text-xl font-bold"></span>{{ number_format(num: $payroll->salaries?->sum('gross_salary')) }} </span>
                </div>
            </div>
        @endforeach
    </div>
    <div class="mt-6">
        {{ $payrolls->links() }}
    </div>
</div>

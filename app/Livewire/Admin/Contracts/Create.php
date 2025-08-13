<?php

namespace App\Livewire\Admin\Contracts;

use App\Models\Contract;
use App\Models\Department;
use App\Models\Designation;
use App\Models\Employee;
use Illuminate\Validation\ValidationException;
use Livewire\Component;

class Create extends Component
{

    public $contract;
    public $search = '';
    public $department_id;

    public function rules()
    {
        return [
            'contract.designation_id' => 'required',
            'contract.employee_id' => 'required',
            'contract.start_date' => 'required|date',
            'contract.end_date' => 'required|date|after:contract.start_date',
            'contract.rate_type' => 'required',
            'contract.rate' => 'required',
        ];
    }

    public function mount()
    {
        $this->contract = new Contract();
    }

    public function selectEmployee($id)
    {
        $employee = Employee::with(['designation.department'])->findOrFail($id);

        $this->contract->employee_id = $employee->id;
        $this->search = $employee->name;

        // Auto-fill department and designation
        $this->department_id = $employee->designation?->department_id;
        $this->contract->designation_id = $employee->designation_id;
    }

    public function save()
    {
        $this->validate();
        if ($this->contract->employee->getActiveContract($this->contract->start_date, $this->contract->end_date)) {
            throw ValidationException::withMessages(['contract.start_date' => 'This employee already has a contract.']);
        }
        $this->contract->save();
        session()->flash('success', 'Contract created successfully');
        return $this->redirectIntended(route('contracts.index'), true);
    }

    public function render()
    {
        $employees = Employee::inCompany()->searchByName($this->search)->get();
        $departments = Department::inCompany()->get();
        $designations = Designation::inCompany()->get();

        return view('livewire.admin.contracts.create', [
            'employees' => $employees,
            'departments' => $departments,
            'designations' => $designations,
        ]);
    }
}

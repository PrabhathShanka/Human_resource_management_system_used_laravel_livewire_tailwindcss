<?php

namespace App\Livewire\Admin\Companies;

use App\Models\Company;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Livewire\Component;
use Livewire\WithFileUploads;

class Create extends Component
{
    use  WithFileUploads;
    public $company;
    public $logo;

    public function rules()
    {
        return [
            'company.name' => 'required|string|max:255',
            'company.email' => 'required|email|max:255',
            'company.website' => 'required|url|max:255',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ];
    }

    public function mount()
    {
        $this->company = new Company();
    }

    public function save()
    {
        $this->validate();
        if($this->logo){
            $this->company->logo = $this->logo->store('companies');
        }
        $this->company->save();
        $this->company->users()->attach(Auth::user()->id);
        session()->flash('success', 'Company created successfully');
        return $this->redirectIntended(route('companies.index'),true);
    }

    public function render()
    {
        return view('livewire.admin.companies.create');
    }
}


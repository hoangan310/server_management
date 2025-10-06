<?php

namespace App\Livewire\Admin\Companies;

use App\Models\Company;
use Illuminate\Contracts\View\View;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Attributes\Layout;
use Livewire\Component;

class ViewCompany extends Component
{
    use LivewireAlert;
    
    public Company $company;

    public function mount(Company $company): void
    {
        $this->authorize('view companies');

        $this->company = $company;
    }

    #[Layout('components.layouts.admin')]
    public function render(): View
    {
        return view('livewire.admin.companies.view-company');
    }
}

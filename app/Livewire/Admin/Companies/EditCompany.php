<?php

namespace App\Livewire\Admin\Companies;

use App\Models\Company;
use Illuminate\Contracts\View\View;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Validate;
use Livewire\Component;
use Livewire\Features\SupportRedirects\HandlesRedirects;

class EditCompany extends Component
{
    use HandlesRedirects;
    use LivewireAlert;

    public Company $company;

    #[Validate(['required', 'string', 'max:255'])]
    public string $name = '';

    public function mount(Company $company): void
    {
        $this->authorize('update companies');

        $this->company = $company;
        $this->name = $this->company->name;
    }

    public function updateCompany(): void
    {
        $this->authorize('update companies');

        $this->validate();

        $this->company->update([
            'name' => $this->name,
        ]);

        $this->flash('success', __('companies.company_updated'));

        $this->redirect(route('admin.companies.index'), true);
    }

    #[Layout('components.layouts.admin')]
    public function render(): View
    {
        return view('livewire.admin.companies.edit-company');
    }
}

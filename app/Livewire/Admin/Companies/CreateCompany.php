<?php

namespace App\Livewire\Admin\Companies;

use App\Models\Company;
use Illuminate\Contracts\View\View;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Validate;
use Livewire\Component;
use Livewire\Features\SupportRedirects\HandlesRedirects;

class CreateCompany extends Component
{
    use HandlesRedirects;
    use LivewireAlert;

    #[Validate('required|string|max:255')]
    public string $name = '';

    public function mount(): void
    {
        $this->authorize('create companies');
    }

    public function createCompany(): void
    {
        $this->validate();

        Company::query()->create([
            'name' => $this->name,
        ]);

        $this->flash('success', __('companies.company_created'));

        $this->redirect(route('admin.companies.index'), true);
    }

    #[Layout('components.layouts.admin')]
    public function render(): View
    {
        return view('livewire.admin.companies.create-company');
    }
}

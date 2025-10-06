<?php

namespace App\Livewire\Admin;

use App\Models\Company;
use Illuminate\Contracts\View\View;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Session;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

class Companies extends Component
{
    use LivewireAlert;
    use WithPagination;

    #[Session]
    public int $perPage = 10;

    #[Url]
    public string $search = '';

    public bool $isShowModal = false;
    public ?int $confirmingCompanyId = null;

    /** @var array<int,string> */
    public array $searchableFields = ['name'];

    protected $listeners = [
        'companyDeleted' => '$refresh',
        'companyNotDeleted' => '$refresh',
    ];

    public function mount(): void
    {
        $this->authorize('view companies');
    }

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function confirmDelete(int $companyId): void
    {
        $this->isShowModal = true;
        $this->confirmingCompanyId = $companyId;
    }

    public function afterDeleteCompany(): void
    {
        $this->isShowModal = false;
        $this->confirmingCompanyId = null;
    }

    public function deleteCompany(): void
    {
        if (!$this->confirmingCompanyId) {
            return;
        }

        $this->authorize('delete companies');

        $company = Company::findOrFail($this->confirmingCompanyId);
        $company->delete();

        $this->alert('success', __('companies.company_deleted'));

        $this->confirmingCompanyId = null;
        $this->resetPage();
        $this->afterDeleteCompany();
    }

    #[Layout('components.layouts.admin')]
    public function render(): View
    {
        $companies = Company::query()
            ->when($this->search, function ($query) {
                foreach ($this->searchableFields as $field) {
                    $query->orWhere($field, 'LIKE', "%{$this->search}%");
                }
            })
            ->paginate($this->perPage);

        return view('livewire.admin.companies', compact('companies'));
    }
}

<?php

namespace App\Livewire\Admin;

use App\Models\Category;
use Illuminate\Contracts\View\View;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Session;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

class Categories extends Component
{
    use LivewireAlert;
    use WithPagination;

    #[Session]
    public int $perPage = 10;

    #[Url]
    public string $search = '';

    public ?int $confirmingCategoryId = null;

    /** @var array<int,string> */
    public array $searchableFields = ['name'];

    protected $listeners = [
        'categoryDeleted' => '$refresh',
        'categoryNotDeleted' => '$refresh',
    ];

    public function mount(): void
    {
        $this->authorize('view categories');
    }

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function confirmDelete(int $categoryId): void
    {
        $this->confirmingCategoryId = $categoryId;
    }

    public function afterDeleteCategory(): void
    {
        $this->confirmingCategoryId = null;
    }

    public function deleteCategory(): void
    {
        if (!$this->confirmingCategoryId) {
            return;
        }

        $this->authorize('delete categories');

        $category = Category::findOrFail($this->confirmingCategoryId);
        $category->delete();

        $this->alert('success', __('categories.category_deleted'));

        $this->dispatch('categoryDeleted');

        $this->resetPage();
        $this->afterDeleteCategory();
    }

    #[Layout('components.layouts.admin')]
    public function render(): View
    {
        $categories = Category::query()
            ->when($this->search, function ($query) {
                foreach ($this->searchableFields as $field) {
                    $query->orWhere($field, 'LIKE', "%{$this->search}%");
                }
            })
            ->paginate($this->perPage);

        return view('livewire.admin.categories', compact('categories'));
    }
}

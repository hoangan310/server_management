<?php

namespace App\Livewire\Admin\Categories;

use App\Enums\AdminCategoriesEnum;
use App\Models\Category;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Str;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Validate;
use Livewire\Component;
use Livewire\Features\SupportRedirects\HandlesRedirects;

class EditCategory extends Component
{
    use HandlesRedirects;
    use LivewireAlert;

    public Category $category;

    #[Validate(['required', 'string', 'max:255'])]
    public string $name = '';
    #[Validate(['nullable', 'string', 'max:5000'])]
    public string $description = '';
    #[Validate(['required', 'string', 'max:255'])]
    public string $status = '';

    public function mount(Category $category): void
    {
        $this->authorize('update categories');

        $this->category = $category;
        $this->name = $this->category->name;
        $this->description = $this->category->description;
        $this->status = $this->category->status;
    }

    public function updateCategory(): void
    {
        $this->authorize('update categories');

        $this->validate();

        $this->category->update([
            'name' => $this->name,
            'slug' => Str::slug($this->name, '-'),
            'description' => $this->description,
            'status' => $this->status,
        ]);

        $this->flash('success', __('categories.category_updated'));

        $this->redirect(route('admin.categories.index'), true);
    }

    #[Layout('components.layouts.admin')]
    public function render(): View
    {
        return view('livewire.admin.categories.edit-category', [
            'statuses' => AdminCategoriesEnum::cases(),
        ]);
    }
}

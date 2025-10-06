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

class CreateCategory extends Component
{
    use HandlesRedirects;
    use LivewireAlert;

    #[Validate('required|string|max:255')]
    public string $name = '';

    #[Validate('nullable|string|max:5000')]
    public string $description = '';

    #[Validate('required|string|max:255')]
    public string $status = '';

    public function mount(): void
    {
        $this->authorize('create categories');
    }

    public function createCategory(): void
    {
        $this->validate();

        Category::query()->create([
            'name' => $this->name,
            'slug' => Str::slug($this->name, '-'),
            'description' => $this->description,
            'status' => $this->status,
        ]);

        $this->flash('success', __('categories.category_created'));

        $this->redirect(route('admin.categories.index'), true);
    }

    #[Layout('components.layouts.admin')]
    public function render(): View
    {
        return view('livewire.admin.categories.create-category', [
            'statuses' => AdminCategoriesEnum::cases(),
        ]);
    }
}

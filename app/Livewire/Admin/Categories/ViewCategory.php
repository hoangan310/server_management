<?php

namespace App\Livewire\Admin\Categories;

use App\Models\Category;
use Illuminate\Contracts\View\View;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Attributes\Layout;
use Livewire\Component;

class ViewCategory extends Component
{
    use LivewireAlert;
    
    public Category $category;

    public function mount(Category $category): void
    {
        $this->authorize('view categories');

        $this->category = $category;
    }

    #[Layout('components.layouts.admin')]
    public function render(): View
    {
        return view('livewire.admin.categories.view-category');
    }
}

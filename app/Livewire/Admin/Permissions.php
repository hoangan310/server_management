<?php

namespace App\Livewire\Admin;

use Illuminate\Contracts\View\View;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Session;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;
use Spatie\Permission\Models\Permission;

class Permissions extends Component
{
    use LivewireAlert;
    use WithPagination;

    /** @var array<string,string> */
    protected $listeners = [
        'permissionDeleted' => '$refresh',
    ];

    #[Session]
    public int $perPage = 10;

    /** @var array<int,string> */
    public array $searchableFields = ['name'];

    #[Url]
    public string $search = '';

    public ?int $confirmingPermissionId = null;

    public function mount(): void
    {
        $this->authorize('view permissions');
    }

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function confirmDelete(int $permissionId): void
    {
        $this->confirmingPermissionId = $permissionId;
    }

    public function afterDeletePermission(): void
    {
        $this->confirmingPermissionId = null;
    }

    public function deletePermission(): void
    {
        if (!$this->confirmingPermissionId) {
            return;
        }

        $this->authorize('delete permissions');

        $permission = Permission::query()->where('id', $this->confirmingPermissionId)->firstOrFail();
        $permission->delete();

        $this->alert('success', __('permissions.permission_deleted'));
        $this->afterDeletePermission();
        $this->dispatch('permissionDeleted');
    }

    public function deletePermissionOld(string $permissionId): void
    {

        $this->authorize('delete permissions');

        $permission = Permission::query()->where('id', $permissionId)->firstOrFail();

        $permission->delete();

        $this->alert('success', __('permissions.permission_deleted'));

        $this->dispatch('permissionDeleted');
    }

    #[Layout('components.layouts.admin')]
    public function render(): View
    {
        return view('livewire.admin.permissions', [
            'permissions' => Permission::query()
                ->when($this->search, function ($query, $search): void {
                    $query->whereAny($this->searchableFields, 'LIKE', "%$search%");
                })
                ->paginate($this->perPage),
        ]);
    }
}

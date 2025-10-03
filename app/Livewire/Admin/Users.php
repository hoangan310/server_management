<?php

namespace App\Livewire\Admin;

use App\Models\User;
use Illuminate\Contracts\View\View;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Session;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;
use Spatie\Permission\Models\Role;

class Users extends Component
{
    use LivewireAlert;
    use WithPagination;

    #[Session]
    public int $perPage = 10;

    #[Url]
    public string $search = '';

    public ?string $role = null;

    public bool $isShowModal = false;
    public ?int $confirmingUserId = null; // id user đang chờ confirm xóa

    /** @var array<int,string> */
    public array $searchableFields = ['name', 'email'];

    protected $listeners = [
        'userDeleted' => '$refresh',
        'userNotDeleted' => '$refresh',
    ];

    public function mount(): void
    {
        $this->authorize('view users');
    }

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    // Khi nhấn delete → lưu user id và mở modal
    public function confirmDelete(int $userId): void
    {
        $this->isShowModal = true;
        $this->confirmingUserId = $userId;
    }

    public function afterDeleteUser(): void
    {
        $this->isShowModal = false;
        $this->confirmingUserId = null;
    }

    // Thực sự xóa user khi confirm modal
    public function deleteUser(): void
    {
        if (!$this->confirmingUserId) {
            return;
        }

        $this->authorize('delete users');

        $user = User::findOrFail($this->confirmingUserId);

        if ($user->hasRole('Super Admin') || $user->id === auth()->id()) {
            $this->alert('error', __('users.cannot_delete'));
            $this->confirmingUserId = null;
            $this->afterDeleteUser();
            return;
        }

        $user->delete();

        $this->alert('success', __('users.user_deleted'));

        $this->confirmingUserId = null;
        $this->resetPage();

        // **Đóng modal**
        $this->afterDeleteUser();
    }

    #[Layout('components.layouts.admin')]
    public function render(): View
    {
        $users = User::query()
            ->with('roles')
            ->when($this->search, function ($query) {
                foreach ($this->searchableFields as $field) {
                    $query->orWhere($field, 'LIKE', "%{$this->search}%");
                }
            })
            ->when($this->role, fn($query) => $query->role($this->role))
            ->paginate($this->perPage);

        $roles = Role::all();

        return view('livewire.admin.users', compact('users', 'roles'));
    }
}

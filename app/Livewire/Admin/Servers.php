<?php

namespace App\Livewire\Admin;

use App\Models\Server;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Session;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

class Servers extends Component
{
    use LivewireAlert;
    use WithPagination;

    #[Session]
    public int $perPage = 10;

    #[Url]
    public string $search = '';

    public ?string $type = null;
    public ?string $status = null;

    public bool $isShowModal = false;
    public ?int $confirmingServerId = null; // id server đang chờ confirm xóa

    /** @var array<int,string> */
    public array $searchableFields = ['name', 'ip', 'provider'];

    protected $listeners = [
        'serverDeleted' => '$refresh',
        'serverNotDeleted' => '$refresh',
    ];

    public function mount(): void
    {
        $this->authorize('view servers');
    }

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function updatingType(): void
    {
        $this->resetPage();
    }

    public function updatingStatus(): void
    {
        $this->resetPage();
    }

    // Khi nhấn delete → lưu user id và mở modal
    public function confirmDelete(int $serverId): void
    {
        $this->isShowModal = true;
        $this->confirmingServerId = $serverId;
    }

    public function afterDeleteServer(): void
    {
        $this->isShowModal = false;
        $this->confirmingServerId = null;
    }

    // Thực sự xóa user khi confirm modal
    public function deleteServer(): void
    {
        if (!$this->confirmingServerId) {
            return;
        }

        $this->authorize('delete servers');

        $server = Server::findOrFail($this->confirmingServerId);

        if ($server->created_by === Auth::id()) {
            $this->alert('error', __('servers.cannot_delete'));
            $this->confirmingServerId = null;
            $this->afterDeleteServer();
            return;
        }

        $server->delete();

        $this->alert('success', __('servers.server_deleted'));

        $this->confirmingServerId = null;
        $this->resetPage();

        // **Đóng modal**
        $this->afterDeleteServer();
    }

    #[Layout('components.layouts.admin')]
    public function render(): View
    {
        $servers = Server::query()
            ->when($this->search, function ($query) {
                foreach ($this->searchableFields as $field) {
                    $query->orWhere($field, 'LIKE', "%{$this->search}%");
                }
            })
            ->when($this->type, function ($query) {
                $query->where('type', $this->type);
            })
            ->when($this->status, function ($query) {
                $query->where('status', $this->status);
            })
            ->paginate($this->perPage);

        return view('livewire.admin.servers', compact('servers'));
    }
}

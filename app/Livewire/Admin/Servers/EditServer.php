<?php

namespace App\Livewire\Admin\Servers;

use Illuminate\Contracts\View\View;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Attributes\Layout;
use Livewire\Component;
use App\Models\Server;
use Livewire\Attributes\Validate;
use Illuminate\Support\Facades\Auth;
use App\Enums\Server\ServerTypesEnum;
use App\Enums\Server\ServerStatusEnum;

class EditServer extends Component
{
  use LivewireAlert;

  #[Validate('required|string|max:255')]
  public string $name = '';

  #[Validate('nullable|string|max:5000')]
  public string $description = '';

  #[Validate('required|string|max:255')]
  public string $type = '';

  #[Validate('required|ipv4')]
  public string $ip = '';

  #[Validate('required|string|max:255')]
  public string $username = '';

  #[Validate('required|string|max:255')]
  public string $password = '';

  #[Validate('required|numeric|min:1')]
  public int $cpu = 1;

  #[Validate('required|numeric|min:1')]
  public int $memory = 1;

  #[Validate('required|numeric|min:1')]
  public int $disk_space = 1;

  #[Validate('required|numeric|min:0')]
  public int $disk_space_left = 0;

  #[Validate('required|numeric|min:1')]
  public int $bandwidth = 1;

  #[Validate('required|numeric|min:1|max:65535')]
  public int $port = 22;

  #[Validate('required|string|max:255')]
  public string $provider = '';

  #[Validate('required|string|max:255')]
  public string $status = '';
  public Server $server;

  public function mount(Server $server): void
  {
    $this->authorize('edit servers');
    $this->server = $server;
    $this->name = $this->server->name;
    $this->description = $this->server->description;
    $this->provider = $this->server->provider;
    $this->ip = $this->server->ip;
    $this->port = $this->server->port;
    $this->cpu = $this->server->cpu;
    $this->memory = $this->server->memory;
    $this->disk_space = $this->server->disk_space;
    $this->disk_space_left = $this->server->disk_space_left;
    $this->bandwidth = $this->server->bandwidth;
    $this->type = $this->server->type;
    $this->status = $this->server->status;
  }

  public function editServer(): void
  {
    $this->authorize('edit servers');
    $this->validate();

    $this->server->update([
      'name' => $this->name,
      'description' => $this->description,
      'type' => $this->type,
      'ip' => $this->ip,
      'cpu' => $this->cpu,
      'memory' => $this->memory,
      'disk_space' => $this->disk_space,
      'disk_space_left' => $this->disk_space_left,
      'bandwidth' => $this->bandwidth,
      'port' => $this->port,
      'provider' => $this->provider,
      'status' => $this->status,
      'updated_by' => Auth::user()->id,
    ]);

    $this->flash('success', __('servers.server_updated'));

    $this->redirect(route('admin.servers.index'), true);
  }

  #[Layout('components.layouts.admin')]
  public function render(): View
  {
    return view('livewire.admin.servers.edit-server', [
      'serverTypes' => ServerTypesEnum::cases(),
      'serverStatuses' => ServerStatusEnum::cases(),
    ]);
  }
}

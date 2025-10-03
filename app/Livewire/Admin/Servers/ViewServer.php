<?php

namespace App\Livewire\Admin\Servers;

use App\Models\Server;
use Illuminate\Contracts\View\View;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Attributes\Layout;
use Livewire\Component;

class ViewServer extends Component
{
  use LivewireAlert;
  public Server $server;

  public function mount(Server $server): void
  {
    $this->authorize('view servers');

    $this->server = $server;
  }

  #[Layout('components.layouts.admin')]
  public function render(): View
  {
    return view('livewire.admin.servers.view-server');
  }
}

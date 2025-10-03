<?php

namespace App\Services\Scaffold;

use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Str;

class ShowLivewireScaffold
{
  protected string $modelName;
  protected Filesystem $files;
  public function __construct(string $modelName)
  {
    $this->modelName = $modelName;
    $this->files = new Filesystem();
  }


  protected function getShowLivewireComponentStub(): string
  {
    return <<<STUB
<?php

namespace App\Livewire\Admin\{{PluralModelName}};

use App\Models\{{ModelName}};

class Show{{ModelName}} extends Component
{
  use LivewireAlert;

  public function mount(): void
  {
    \$this->authorize('show {{PluralModelName}}');
  }

  public function show{{ModelName}}(): void
  {
    \$this->authorize('show {{PluralModelName}}');

    \$this->validate();
    
  }

  #[Layout('components.layouts.admin')]
  public function render(): View
  {
    return view('livewire.admin.{{PluralModelName}}.show-{{ModelName}}');
  }
  
}

STUB;
  }

  protected function getShowBladeStub(): string
  {
    return <<<STUB
<section class="w-full">
    <x-page-heading>
        <x-slot:title>{{ __('{{PluralModelName}}.view_{{ModelName}}') }}</x-slot:title>
        <x-slot:subtitle>Viewing {{ \${{ModelName}}->name }}</x-slot:subtitle>
        <x-slot:buttons>
            @can('update {{PluralModelName}}')
              <flux:button icon="edit" variant="primary" href="{{ route('admin.{{PluralModelName}}.edit', {{ModelName}}) }}">
                {{ __('{{PluralModelName}}.edit_{{ModelName}}') }}
              </flux:button>
            @endcan
        </x-slot:buttons>
    </x-page-heading>


</section>


STUB;
  }

  public function createShowLivewireComponent()
  {
    $showLivewireComponentPath = app_path('Livewire/Admin/' . Str::pluralStudly($this->modelName) . "/Show{$this->modelName}.php");
    if (!$this->files->exists($showLivewireComponentPath)) {
      $stub = $this->getShowLivewireComponentStub();
      $stub = str_replace('{{ModelName}}', $this->modelName, $stub);
      $stub = str_replace('{{PluralModelName}}', Str::pluralStudly($this->modelName), $stub);
      if (!is_dir(dirname($showLivewireComponentPath))) {
        mkdir(dirname($showLivewireComponentPath), 0755, true);
      }
      $this->files->put($showLivewireComponentPath, $stub);
    }
  }

  public function createShowBlade()
  {
    $pluralModelName = Str::pluralStudly($this->modelName);
    $pluralModelNameCamel = Str::camel($pluralModelName);
    $showBladePath = resource_path('views/livewire/admin/' . $pluralModelNameCamel . "/show-{$pluralModelNameCamel}.blade.php");
    if (!$this->files->exists($showBladePath)) {
      $stub = $this->getShowBladeStub();
      $stub = str_replace('{{ModelName}}', $this->modelName, $stub);
      $stub = str_replace('{{PluralModelName}}', Str::pluralStudly($this->modelName), $stub);
    }
    if (!is_dir(dirname($showBladePath))) {
      mkdir(dirname($showBladePath), 0755, true);
    }
    $this->files->put($showBladePath, $stub);
  }
}

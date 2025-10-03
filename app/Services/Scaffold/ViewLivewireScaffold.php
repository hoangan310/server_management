<?php

namespace App\Services\Scaffold;

use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Str;

class ViewLivewireScaffold
{
  protected string $modelName;
  protected Filesystem $files;
  public function __construct(string $modelName)
  {
    $this->modelName = $modelName;
    $this->files = new Filesystem();
  }


  protected function getViewLivewireComponentStub(): string
  {
    return <<<STUB
<?php

namespace App\Livewire\Admin\{{PluralModelName}};

use App\Models\{{ModelName}};
use Illuminate\Contracts\View\View;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Attributes\Layout;
use Livewire\Component;

class View{{ModelName}} extends Component
{
   use LivewireAlert;
  public {{ModelName}} \${{ModelName}};

  public function mount({{ModelName}} \${{ModelName}}): void
  {
    \$this->authorize('view {{PluralModelName}}');

    \$this->{{ModelName}} = \${{ModelName}};
  }

  #[Layout('components.layouts.admin')]
  public function render(): View
  {
    return view('livewire.admin.{{PluralModelName}}.view-{{ModelName}}');
  }
}

STUB;
  }

  protected function getViewBladeStub(): string
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

  public function createViewLivewireComponent()
  {
    $viewLivewireComponentPath = app_path('Livewire/Admin/' . Str::pluralStudly($this->modelName) . "/View{$this->modelName}.php");
    if (!$this->files->exists($viewLivewireComponentPath)) {
      $stub = $this->getViewLivewireComponentStub();
      $stub = str_replace('{{ModelName}}', $this->modelName, $stub);
      $stub = str_replace('{{PluralModelName}}', Str::pluralStudly($this->modelName), $stub);
      if (!is_dir(dirname($viewLivewireComponentPath))) {
        mkdir(dirname($viewLivewireComponentPath), 0755, true);
      }
      $this->files->put($viewLivewireComponentPath, $stub);
    }
  }

  public function createViewBlade()
  {
    $pluralModelName = Str::pluralStudly($this->modelName);
    $pluralModelNameCamel = Str::camel($pluralModelName);
    $modelNameCamel = Str::camel($this->modelName);
    $viewBladePath = resource_path('views/livewire/admin/' . $pluralModelNameCamel . "/view-{$modelNameCamel}.blade.php");
    if (!$this->files->exists($viewBladePath)) {
      $stub = $this->getViewBladeStub();
      $stub = str_replace('{{ModelName}}', $this->modelName, $stub);
      $stub = str_replace('{{PluralModelName}}', Str::pluralStudly($this->modelName), $stub);

      if (!is_dir(dirname($viewBladePath))) {
        mkdir(dirname($viewBladePath), 0755, true);
      }
      $this->files->put($viewBladePath, $stub);
    }
  }
}

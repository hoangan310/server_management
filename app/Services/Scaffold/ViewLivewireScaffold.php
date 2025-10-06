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
    
    public {{ModelName}} \${{modelName}};

    public function mount({{ModelName}} \${{modelName}}): void
    {
        \$this->authorize('view {{pluralModelNameCamel}}');

        \$this->{{modelName}} = \${{modelName}};
    }

    #[Layout('components.layouts.admin')]
    public function render(): View
    {
        return view('livewire.admin.{{pluralModelNameCamel}}.view-{{modelName}}');
    }
}

STUB;
  }

  protected function getViewBladeStub(): string
  {
    return <<<STUB
<section class="w-full">
    <x-page-heading>
        <x-slot:title>{{ __('{{pluralModelNameCamel}}.view_{{modelName}}') }}</x-slot:title>
        <x-slot:subtitle>Viewing {{ \${{modelName}}->name }}</x-slot:subtitle>
        <x-slot:buttons>
            @can('update {{pluralModelNameCamel}}')
              <flux:button icon="edit" variant="primary" href="{{ route('admin.{{pluralModelNameCamel}}.edit', \${{modelName}}) }}">
                {{ __('{{pluralModelNameCamel}}.edit_{{modelName}}') }}
              </flux:button>
            @endcan
        </x-slot:buttons>
    </x-page-heading>

    <div class="space-y-6">
        <div class="bg-white dark:bg-gray-800 shadow rounded-lg p-6">
            <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4">{{ __('{{pluralModelNameCamel}}.{{modelName}}_details') }}</h3>
            <dl class="grid grid-cols-1 gap-x-4 gap-y-6 sm:grid-cols-2">
                <div>
                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('{{pluralModelNameCamel}}.{{modelName}}_name') }}</dt>
                    <dd class="mt-1 text-sm text-gray-900 dark:text-white">{{ \${{modelName}}->name }}</dd>
                </div>
                <div>
                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('global.created_at') }}</dt>
                    <dd class="mt-1 text-sm text-gray-900 dark:text-white">{{ \${{modelName}}->created_at->format('M d, Y H:i') }}</dd>
                </div>
                <div>
                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('global.updated_at') }}</dt>
                    <dd class="mt-1 text-sm text-gray-900 dark:text-white">{{ \${{modelName}}->updated_at->format('M d, Y H:i') }}</dd>
                </div>
            </dl>
        </div>
    </div>

</section>

STUB;
  }

  public function createViewLivewireComponent()
  {
    $viewLivewireComponentPath = app_path('Livewire/Admin/' . Str::pluralStudly($this->modelName) . "/View{$this->modelName}.php");
    if (!$this->files->exists($viewLivewireComponentPath)) {
      $modelName = Str::camel($this->modelName);
      $ModelName = Str::studly($this->modelName);
      $PluralModelName = Str::pluralStudly($this->modelName);
      $pluralModelNameCamel = Str::camel($PluralModelName);
      $stub = $this->getViewLivewireComponentStub();
      $stub = str_replace('{{modelName}}', $modelName, $stub);
      $stub = str_replace('{{ModelName}}', $ModelName, $stub);
      $stub = str_replace('{{PluralModelName}}', $PluralModelName, $stub);
      $stub = str_replace('{{pluralModelNameCamel}}', $pluralModelNameCamel, $stub);
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
      $modelName = Str::camel($this->modelName);
      $ModelName = Str::studly($this->modelName);
      $PluralModelName = Str::pluralStudly($this->modelName);
      $pluralModelNameCamel = Str::camel($PluralModelName);
      $stub = $this->getViewBladeStub();
      $stub = str_replace('{{modelName}}', $modelName, $stub);
      $stub = str_replace('{{ModelName}}', $ModelName, $stub);
      $stub = str_replace('{{PluralModelName}}', $PluralModelName, $stub);
      $stub = str_replace('{{pluralModelNameCamel}}', $pluralModelNameCamel, $stub);

      if (!is_dir(dirname($viewBladePath))) {
        mkdir(dirname($viewBladePath), 0755, true);
      }
      $this->files->put($viewBladePath, $stub);
    }
  }
}

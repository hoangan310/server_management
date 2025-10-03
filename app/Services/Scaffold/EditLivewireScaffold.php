<?php

namespace App\Services\Scaffold;

use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Str;

class EditLivewireScaffold
{
  protected string $modelName;
  protected Filesystem $files;
  public function __construct(string $modelName)
  {
    $this->modelName = $modelName;
    $this->files = new Filesystem();
  }


  protected function getEditLivewireComponentStub(): string
  {
    return <<<STUB
<?php

namespace App\Livewire\Admin\{{PluralModelName}};

use App\Models\{{ModelName}};

class Edit{{ModelName}} extends Component
{
  use LivewireAlert;

  public function mount(): void
  {
    \$this->authorize('edit {{PluralModelName}}');
  }

  public function edit{{ModelName}}(): void
  {
    \$this->authorize('edit {{PluralModelName}}');

    \$this->validate();
    
  }

  #[Layout('components.layouts.admin')]
  public function render(): View
  {
    return view('livewire.admin.{{PluralModelName}}.edit-{{ModelName}}');
  }
  
}

STUB;
  }

  protected function getEditBladeStub(): string
  {
    return <<<STUB
<section class="w-full">
    <x-page-heading>
        <x-slot:title>{{ __('{{PluralModelName}}.edit_{{ModelName}}') }}</x-slot:title>
        <x-slot:subtitle>{{ __('{{PluralModelName}}.edit_{{ModelName}}_description') }}</x-slot:subtitle>
    </x-page-heading>

    <x-form wire:submit="edit_{{ModelName}}" class="space-y-6">
        <flux:input wire:model.live="name" label="{{ __('{{PluralModelName}}.name') }}" />

        <flux:button type="submit" icon="save" variant="primary">
            {{ __('{{PluralModelName}}.edit_{{ModelName}}') }}
        </flux:button>
    </x-form>

</section>

STUB;
  }

  public function createEditLivewireComponent()
  {
    $editLivewireComponentPath = app_path('Livewire/Admin/' . Str::pluralStudly($this->modelName) . "/Edit{$this->modelName}.php");
    if (!$this->files->exists($editLivewireComponentPath)) {
      $stub = $this->getEditLivewireComponentStub();
      $stub = str_replace('{{ModelName}}', $this->modelName, $stub);
      $stub = str_replace('{{PluralModelName}}', Str::pluralStudly($this->modelName), $stub);
      if (!is_dir(dirname($editLivewireComponentPath))) {
        mkdir(dirname($editLivewireComponentPath), 0755, true);
      }
      $this->files->put($editLivewireComponentPath, $stub);
    }
  }

  public function createEditBlade()
  {
    $pluralModelName = Str::pluralStudly($this->modelName);
    $pluralModelNameCamel = Str::camel($pluralModelName);
    $modelNameCamel = Str::camel($this->modelName);
    $editBladePath = resource_path('views/livewire/admin/' . $pluralModelNameCamel . "/edit-{$modelNameCamel}.blade.php");
    if (!$this->files->exists($editBladePath)) {
      $stub = $this->getEditBladeStub();
      $stub = str_replace('{{ModelName}}', $this->modelName, $stub);
      $stub = str_replace('{{PluralModelName}}', Str::pluralStudly($this->modelName), $stub);
    }
    if (!is_dir(dirname($editBladePath))) {
      mkdir(dirname($editBladePath), 0755, true);
    }
    $this->files->put($editBladePath, $stub);
  }
}

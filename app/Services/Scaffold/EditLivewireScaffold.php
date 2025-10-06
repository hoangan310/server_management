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
use Illuminate\Contracts\View\View;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Validate;
use Livewire\Component;
use Livewire\Features\SupportRedirects\HandlesRedirects;

class Edit{{ModelName}} extends Component
{
    use HandlesRedirects;
    use LivewireAlert;

    public {{ModelName}} \${{modelName}};

    #[Validate(['required', 'string', 'max:255'])]
    public string \$name = '';

    public function mount({{ModelName}} \${{modelName}}): void
    {
        \$this->authorize('update {{pluralModelNameCamel}}');

        \$this->{{modelName}} = \${{modelName}};
        \$this->name = \$this->{{modelName}}->name;
    }

    public function update{{ModelName}}(): void
    {
        \$this->authorize('update {{pluralModelNameCamel}}');

        \$this->validate();

        \$this->{{modelName}}->update([
            'name' => \$this->name,
        ]);

        \$this->flash('success', __('{{pluralModelNameCamel}}.{{modelName}}_updated'));

        \$this->redirect(route('admin.{{pluralModelNameCamel}}.index'), true);
    }

    #[Layout('components.layouts.admin')]
    public function render(): View
    {
        return view('livewire.admin.{{pluralModelNameCamel}}.edit-{{modelName}}');
    }
}

STUB;
  }

  protected function getEditBladeStub(): string
  {
    return <<<STUB
<section class="w-full">
    <x-page-heading>
        <x-slot:title>{{ __('{{pluralModelNameCamel}}.edit_{{modelName}}') }}</x-slot:title>
        <x-slot:subtitle>{{ __('{{pluralModelNameCamel}}.edit_{{modelName}}_description') }}</x-slot:subtitle>
    </x-page-heading>

    <x-form wire:submit="update{{ModelName}}" class="space-y-6">
        <flux:input wire:model.live="name" label="{{ __('{{pluralModelNameCamel}}.{{modelName}}_name') }}" />

        <flux:button type="submit" icon="save" variant="primary">
            {{ __('{{pluralModelNameCamel}}.update_{{modelName}}') }}
        </flux:button>
    </x-form>

</section>

STUB;
  }

  public function createEditLivewireComponent()
  {
    $editLivewireComponentPath = app_path('Livewire/Admin/' . Str::pluralStudly($this->modelName) . "/Edit{$this->modelName}.php");
    if (!$this->files->exists($editLivewireComponentPath)) {
      $modelName = Str::camel($this->modelName);
      $ModelName = Str::studly($this->modelName);
      $PluralModelName = Str::pluralStudly($this->modelName);
      $pluralModelNameCamel = Str::camel($PluralModelName);
      $stub = $this->getEditLivewireComponentStub();
      $stub = str_replace('{{modelName}}', $modelName, $stub);
      $stub = str_replace('{{ModelName}}', $ModelName, $stub);
      $stub = str_replace('{{PluralModelName}}', $PluralModelName, $stub);
      $stub = str_replace('{{pluralModelNameCamel}}', $pluralModelNameCamel, $stub);
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
      $modelName = Str::camel($this->modelName);
      $ModelName = Str::studly($this->modelName);
      $PluralModelName = Str::pluralStudly($this->modelName);
      $pluralModelNameCamel = Str::camel($PluralModelName);
      $stub = $this->getEditBladeStub();
      $stub = str_replace('{{modelName}}', $modelName, $stub);
      $stub = str_replace('{{ModelName}}', $ModelName, $stub);
      $stub = str_replace('{{PluralModelName}}', $PluralModelName, $stub);
      $stub = str_replace('{{pluralModelNameCamel}}', $pluralModelNameCamel, $stub);
      if (!is_dir(dirname($editBladePath))) {
        mkdir(dirname($editBladePath), 0755, true);
      }
      $this->files->put($editBladePath, $stub);
    }
  }
}

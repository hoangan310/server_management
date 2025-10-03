<?php

namespace App\Services\Scaffold;

use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Str;

class CreateLivewireScaffold
{
    protected string $modelName;
    protected Filesystem $files;
    public function __construct(string $modelName)
    {
        $this->modelName = $modelName;
        $this->files = new Filesystem();
    }


    protected function getCreateLivewireComponentStub(): string
    {
        return <<<STUB
<?php

namespace App\Livewire\Admin\{{PluralModelName}};

use App\Models\{{ModelName}};

class Create{{ModelName}} extends Component
{
  use LivewireAlert;

  public function mount(): void
  {
    \$this->authorize('create {{PluralModelName}}');
  }

  public function create{{ModelName}}(): void
  {
    \$this->authorize('create {{PluralModelName}}');

    \$this->validate();
    
  }

  #[Layout('components.layouts.admin')]
  public function render(): View
  {
    return view('livewire.admin.{{PluralModelName}}.create-{{ModelName}}');
  }
  
}

STUB;
    }

    protected function getCreateBladeStub(): string
    {
        return <<<STUB
<section class="w-full">
    <x-page-heading>
        <x-slot:title>{{ __('{{PluralModelName}}.create_{{ModelName}}') }}</x-slot:title>
        <x-slot:subtitle>{{ __('{{PluralModelName}}.create_{{ModelName}}_description') }}</x-slot:subtitle>
    </x-page-heading>

    <x-form wire:submit="create_{{ModelName}}" class="space-y-6">
        <flux:input wire:model.live="name" label="{{ __('{{PluralModelName}}.name') }}" />

        <flux:button type="submit" icon="save" variant="primary">
            {{ __('{{PluralModelName}}.create_{{ModelName}}') }}
        </flux:button>
    </x-form>

</section>

STUB;
    }

    public function createCreateLivewireComponent()
    {
        $createLivewireComponentPath = app_path('Livewire/Admin/' . Str::pluralStudly($this->modelName) . "/Create{$this->modelName}.php");
        if (!$this->files->exists($createLivewireComponentPath)) {
            $stub = $this->getCreateLivewireComponentStub();
            $stub = str_replace('{{ModelName}}', $this->modelName, $stub);
            $stub = str_replace('{{PluralModelName}}', Str::pluralStudly($this->modelName), $stub);
            if (!is_dir(dirname($createLivewireComponentPath))) {
                mkdir(dirname($createLivewireComponentPath), 0755, true);
            }
            $this->files->put($createLivewireComponentPath, $stub);
        }
    }

    public function createCreateBlade()
    {
        $pluralModelName = Str::pluralStudly($this->modelName);
        $createBladePath = app_path('Views/Admin/' . Str::pluralStudly($this->modelName) . "/create-{$pluralModelName}.blade.php");
        if (!$this->files->exists($createBladePath)) {
            $stub = $this->getCreateBladeStub();
            $stub = str_replace('{{ModelName}}', $this->modelName, $stub);
            $stub = str_replace('{{PluralModelName}}', Str::pluralStudly($this->modelName), $stub);
        }
        if (!is_dir(dirname($createBladePath))) {
            mkdir(dirname($createBladePath), 0755, true);
        }
        $this->files->put($createBladePath, $stub);
    }
}

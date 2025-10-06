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
use Illuminate\Contracts\View\View;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Validate;
use Livewire\Component;
use Livewire\Features\SupportRedirects\HandlesRedirects;

class Create{{ModelName}} extends Component
{
    use HandlesRedirects;
    use LivewireAlert;

    #[Validate('required|string|max:255')]
    public string \$name = '';

    public function mount(): void
    {
        \$this->authorize('create {{pluralModelNameCamel}}');
    }

    public function create{{ModelName}}(): void
    {
        \$this->validate();

        {{ModelName}}::query()->create([
            'name' => \$this->name,
        ]);

        \$this->flash('success', __('{{pluralModelNameCamel}}.{{modelName}}_created'));

        \$this->redirect(route('admin.{{pluralModelNameCamel}}.index'), true);
    }

    #[Layout('components.layouts.admin')]
    public function render(): View
    {
        return view('livewire.admin.{{pluralModelNameCamel}}.create-{{modelName}}');
    }
}

STUB;
    }

    protected function getCreateBladeStub(): string
    {
        return <<<STUB
<section class="w-full">
    <x-page-heading>
        <x-slot:title>{{ __('{{pluralModelNameCamel}}.create_{{modelName}}') }}</x-slot:title>
        <x-slot:subtitle>{{ __('{{pluralModelNameCamel}}.create_{{modelName}}_description') }}</x-slot:subtitle>
    </x-page-heading>

    <x-form wire:submit="create{{ModelName}}" class="space-y-6">
        <flux:input wire:model.live="name" label="{{ __('{{pluralModelNameCamel}}.{{modelName}}_name') }}" />

        <flux:button type="submit" icon="save" variant="primary">
            {{ __('{{pluralModelNameCamel}}.create_{{modelName}}') }}
        </flux:button>
    </x-form>

</section>

STUB;
    }

    public function createCreateLivewireComponent()
    {
        $createLivewireComponentPath = app_path('Livewire/Admin/' . Str::pluralStudly($this->modelName) . "/Create{$this->modelName}.php");
        if (!$this->files->exists($createLivewireComponentPath)) {
            $modelName = Str::camel($this->modelName);
            $ModelName = Str::studly($this->modelName);
            $PluralModelName = Str::pluralStudly($this->modelName);
            $pluralModelNameCamel = Str::camel($PluralModelName);
            $stub = $this->getCreateLivewireComponentStub();
            $stub = str_replace('{{modelName}}', $modelName, $stub);
            $stub = str_replace('{{ModelName}}', $ModelName, $stub);
            $stub = str_replace('{{PluralModelName}}', $PluralModelName, $stub);
            $stub = str_replace('{{pluralModelNameCamel}}', $pluralModelNameCamel, $stub);
            if (!is_dir(dirname($createLivewireComponentPath))) {
                mkdir(dirname($createLivewireComponentPath), 0755, true);
            }
            $this->files->put($createLivewireComponentPath, $stub);
        }
    }

    public function createCreateBlade()
    {
        $pluralModelName = Str::pluralStudly($this->modelName);
        $pluralModelNameCamel = Str::camel($pluralModelName);
        $modelNameCamel = Str::camel($this->modelName);
        $createBladePath = resource_path('views/livewire/admin/' . $pluralModelNameCamel . "/create-{$modelNameCamel}.blade.php");
        if (!$this->files->exists($createBladePath)) {
            $modelName = Str::camel($this->modelName);
            $ModelName = Str::studly($this->modelName);
            $PluralModelName = Str::pluralStudly($this->modelName);
            $pluralModelNameCamel = Str::camel($PluralModelName);
            $stub = $this->getCreateBladeStub();
            $stub = str_replace('{{modelName}}', $modelName, $stub);
            $stub = str_replace('{{ModelName}}', $ModelName, $stub);
            $stub = str_replace('{{PluralModelName}}', $PluralModelName, $stub);
            $stub = str_replace('{{pluralModelNameCamel}}', $pluralModelNameCamel, $stub);
            if (!is_dir(dirname($createBladePath))) {
                mkdir(dirname($createBladePath), 0755, true);
            }
            $this->files->put($createBladePath, $stub);
        }
    }
}

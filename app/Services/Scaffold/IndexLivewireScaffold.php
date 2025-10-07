<?php

namespace App\Services\Scaffold;

use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Str;

class IndexLivewireScaffold
{
    protected string $modelName;
    protected Filesystem $files;
    public function __construct(string $modelName)
    {
        $this->modelName = $modelName;
        $this->files = new Filesystem();
    }


    protected function getIndexStub(): string
    {
        return <<<STUB
<?php

namespace App\Livewire\Admin;

use App\Models\{{ModelName}};
use Mary\Traits\Toast;
use Illuminate\Contracts\View\View;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Session;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

class {{PluralModelName}} extends Component
{
    use LivewireAlert;
    use Toast;
    use WithPagination;

    #[Session]
    public int \$perPage = 10;

    #[Url]
    public string \$search = '';

    public ?int \$confirming{{ModelName}}Id = null;

    /** @var array<int,string> */
    public array \$searchableFields = ['name'];

    protected \$listeners = [
        '{{modelName}}Deleted' => '\$refresh',
        '{{modelName}}NotDeleted' => '\$refresh',
    ];

    public function mount(): void
    {
        \$this->authorize('view {{pluralModelNameCamel}}');
    }

    public function updatingSearch(): void
    {
        \$this->resetPage();
    }

    public function confirmDelete(int \${{modelName}}Id): void
    {
        \$this->confirming{{ModelName}}Id = \${{modelName}}Id;
    }

    public function afterDelete{{ModelName}}(): void
    {
        \$this->confirming{{ModelName}}Id = null;
    }

    public function delete{{ModelName}}(): void
    {
        if (!\$this->confirming{{ModelName}}Id) {
            return;
        }

        \$this->authorize('delete {{pluralModelNameCamel}}');

        \${{modelName}} = {{ModelName}}::findOrFail(\$this->confirming{{ModelName}}Id);
        \${{modelName}}->delete();

        \$this->alert('success', __('{{pluralModelNameCamel}}.{{modelName}}_deleted'));
        \$this->success('{{ModelName}} deleted successfully');

        \$this->dispatch('{{modelName}}Deleted');

        \$this->resetPage();
        \$this->afterDelete{{ModelName}}();
    }

    #[Layout('components.layouts.admin')]
    public function render(): View
    {
        \${{pluralModelNameCamel}} = {{ModelName}}::query()
            ->when(\$this->search, function (\$query) {
                foreach (\$this->searchableFields as \$field) {
                    \$query->orWhere(\$field, 'LIKE', "%{\$this->search}%");
                }
            })
            ->paginate(\$this->perPage);

        return view('livewire.admin.{{pluralModelNameCamel}}', compact('{{pluralModelNameCamel}}'));
    }
}

STUB;
    }

    public function createIndex()
    {
        $modelPath = app_path('Livewire/Admin/' . Str::pluralStudly($this->modelName) . '.php');
        if (!$this->files->exists($modelPath)) {
            $modelName = Str::camel($this->modelName);
            $ModelName = Str::studly($this->modelName);
            $PluralModelName = Str::pluralStudly($this->modelName);
            $pluralModelNameCamel = Str::camel($PluralModelName);
            $stub = $this->getIndexStub();
            $stub = str_replace('{{modelName}}', $modelName, $stub);
            $stub = str_replace('{{ModelName}}', $ModelName, $stub);
            $stub = str_replace('{{PluralModelName}}', $PluralModelName, $stub);
            $stub = str_replace('{{pluralModelNameCamel}}', $pluralModelNameCamel, $stub);
            if (!is_dir(dirname($modelPath))) {
                mkdir(dirname($modelPath), 0755, true);
            }
            $this->files->put($modelPath, $stub);
        }
    }

    public function createIndexBlade()
    {
        $pluralModelName = Str::pluralStudly($this->modelName);
        $pluralModelNameCamel = Str::camel($pluralModelName);
        $indexBladePath = resource_path('views/livewire/admin/' . $pluralModelNameCamel . '.blade.php');
        if (!$this->files->exists($indexBladePath)) {
            $modelName = Str::camel($this->modelName);
            $ModelName = Str::studly($this->modelName);
            $PluralModelName = Str::pluralStudly($this->modelName);
            $pluralModelNameCamel = Str::camel($PluralModelName);
            $stub = $this->getIndexBladeStub();
            $stub = str_replace('{{ModelName}}', $ModelName, $stub);
            $stub = str_replace('{{modelName}}', $modelName, $stub);
            $stub = str_replace('{{PluralModelName}}', $PluralModelName, $stub);
            $stub = str_replace('{{pluralModelNameCamel}}', $pluralModelNameCamel, $stub);
            if (!is_dir(dirname($indexBladePath))) {
                mkdir(dirname($indexBladePath), 0755, true);
            }
            $this->files->put($indexBladePath, $stub);
        }
    }

    protected function getIndexBladeStub(): string
    {
        return <<<STUB
<div class="p-6">
    <x-header title="{{ __('{{pluralModelNameCamel}}.title') }}" subtitle="{{ __('{{pluralModelNameCamel}}.title_description') }}">
        @can('create {{pluralModelNameCamel}}')
        <x-slot:actions>
            <x-button label="{{ __('{{pluralModelNameCamel}}.create_{{modelName}}') }}" 
                      icon="o-plus" 
                      class="btn-primary" 
                      href="{{ route('admin.{{pluralModelNameCamel}}.create') }}" />
        </x-slot:actions>
        @endcan
    </x-header>

    <div class="flex items-center justify-between w-full mb-6 gap-2">
        <x-input wire:model.live="search" 
                 placeholder="{{ __('global.search_here') }}" 
                 class="!w-auto" />
        
        <x-select wire:model.live="perPage" 
                  class="!w-auto"
                  :options="[
                      ['id' => '10', 'name' => '{{ __('global.10_per_page') }}'],
                      ['id' => '25', 'name' => '{{ __('global.25_per_page') }}'],
                      ['id' => '50', 'name' => '{{ __('global.50_per_page') }}'],
                      ['id' => '100', 'name' => '{{ __('global.100_per_page') }}']
                  ]" />
    </div>

    <x-table :headers="[
        ['key' => 'id', 'label' => '{{ __('global.id') }}'],
        ['key' => 'name', 'label' => '{{ __('{{pluralModelNameCamel}}.{{modelName}}_name') }}'],
        ['key' => 'actions', 'label' => '{{ __('global.actions') }}', 'class' => 'text-right']
    ]" :rows="\${{pluralModelNameCamel}}">
        @scope('cell_actions', \${{modelName}})
            <div class="flex gap-2 justify-end">
                @can('view {{pluralModelNameCamel}}')
                <x-button icon="o-eye" 
                          href="{{ route('admin.{{pluralModelNameCamel}}.show', \${{modelName}}) }}" 
                          class="btn-ghost btn-sm" />
                @endcan

                @can('update {{pluralModelNameCamel}}')
                <x-button icon="o-pencil" 
                          href="{{ route('admin.{{pluralModelNameCamel}}.edit', \${{modelName}}) }}" 
                          class="btn-ghost btn-sm" />
                @endcan

                @can('delete {{pluralModelNameCamel}}')
                <x-button icon="o-trash" 
                          wire:click="confirmDelete({{ \${{modelName}}->id }})" 
                          class="btn-ghost btn-sm text-error" />
                @endcan
            </div>
        @endscope
    </x-table>

    <div class="mt-4">
        {{ \${{pluralModelNameCamel}}->links() }}
    </div>

    <!-- Delete Confirmation Modal -->
    <x-modal wire:model="confirming{{ModelName}}Id" title="{{ __('{{pluralModelNameCamel}}.delete_{{modelName}}') }}?">
        <p>{{ __('{{pluralModelNameCamel}}.you_are_about_to_delete') }}</p>
        <p>{{ __('global.this_action_is_irreversible') }}</p>
        
        <x-slot:actions>
            <x-button label="{{ __('global.cancel') }}" 
                      wire:click="afterDelete{{ModelName}}" 
                      class="btn-ghost" />
            <x-button label="{{ __('{{pluralModelNameCamel}}.delete_{{modelName}}') }}" 
                      wire:click="delete{{ModelName}}" 
                      class="btn-error" />
        </x-slot:actions>
    </x-modal>
</div>
STUB;
    }
}

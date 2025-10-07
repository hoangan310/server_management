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
use Flux\Flux;
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
        Flux::modal('delete-{{modelName}}-modal')->close();

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
<section class="w-full">
    <x-page-heading>
        <x-slot:title>{{ __('{{pluralModelNameCamel}}.title') }}</x-slot:title>
        <x-slot:subtitle>{{ __('{{pluralModelNameCamel}}.title_description') }}</x-slot:subtitle>
        <x-slot:buttons>
            @can('create {{pluralModelNameCamel}}')
            <flux:button href="{{ route('admin.{{pluralModelNameCamel}}.create') }}" variant="primary" icon="plus">
                {{ __('{{pluralModelNameCamel}}.create_{{modelName}}') }}
            </flux:button>
            @endcan
        </x-slot:buttons>
    </x-page-heading>

    <div class="flex items-center justify-between w-full mb-6 gap-2">
        <flux:input wire:model.live="search" placeholder="{{ __('global.search_here') }}" class="!w-auto" />
        <flux:spacer />
        
        <flux:select wire:model.live="perPage" class="!w-auto">
            <flux:select.option value="10">{{ __('global.10_per_page') }}</flux:select.option>
            <flux:select.option value="25">{{ __('global.25_per_page') }}</flux:select.option>
            <flux:select.option value="50">{{ __('global.50_per_page') }}</flux:select.option>
            <flux:select.option value="100">{{ __('global.100_per_page') }}</flux:select.option>
        </flux:select>
    </div>

    <x-table>
        <x-slot:head>
            <x-table.row>
                <x-table.heading>{{ __('global.id') }}</x-table.heading>
                <x-table.heading>{{ __('{{pluralModelNameCamel}}.{{modelName}}_name') }}</x-table.heading>
                <x-table.heading class="text-right">{{ __('global.actions') }}</x-table.heading>
            </x-table.row>
        </x-slot:head>
        <x-slot:body>
            @foreach(\${{pluralModelNameCamel}} as \${{modelName}})
            <x-table.row wire:key="{{modelName}}-{{ \${{modelName}}->id }}">
                <x-table.cell>{{ \${{modelName}}->id }}</x-table.cell>
                <x-table.cell>{{ \${{modelName}}->name }}</x-table.cell>
                <x-table.cell class="gap-2 flex justify-end">
                    @can('view {{pluralModelNameCamel}}')
                    <flux:button href="{{ route('admin.{{pluralModelNameCamel}}.show', \${{modelName}}) }}" size="sm" variant="ghost">
                        {{ __('global.view') }}
                    </flux:button>
                    @endcan

                    @can('update {{pluralModelNameCamel}}')
                    <flux:button href="{{ route('admin.{{pluralModelNameCamel}}.edit', \${{modelName}}) }}" size="sm">
                        {{ __('global.edit') }}
                    </flux:button>
                    @endcan

                    @can('delete {{pluralModelNameCamel}}')
                    <flux:modal.trigger name="delete-{{modelName}}-modal">
                        <flux:button size="sm" variant="danger" wire:click="confirmDelete({{ \${{modelName}}->id }})">
                            {{ __('global.delete') }}
                        </flux:button>
                    </flux:modal.trigger>
                    @endcan
                </x-table.cell>
            </x-table.row>
            @endforeach
        </x-slot:body>
    </x-table>

    <div class="mt-4">
        {{ \${{pluralModelNameCamel}}->links() }}
    </div>

    <!-- Modal chung cho tất cả {{pluralModelNameCamel}} -->
    <flux:modal name="delete-{{modelName}}-modal"
        class="min-w-[22rem] space-y-6 flex flex-col justify-between">
        <div>
            <flux:heading size="lg">{{ __('{{pluralModelNameCamel}}.delete_{{modelName}}') }}?</flux:heading>
            <flux:subheading>
                <p>{{ __('{{pluralModelNameCamel}}.you_are_about_to_delete') }}</p>
                <p>{{ __('global.this_action_is_irreversible') }}</p>
            </flux:subheading>
        </div>
        <div class="flex gap-2 !mt-auto mb-0">
            <flux:modal.close>
                <flux:button variant="ghost" wire:click="afterDelete{{ModelName}}">{{ __('global.cancel') }}</flux:button>
            </flux:modal.close>
            <flux:spacer />
            <flux:button type="button" variant="danger" wire:click="delete{{ModelName}}">
                {{ __('{{pluralModelNameCamel}}.delete_{{modelName}}') }}
            </flux:button>
        </div>
    </flux:modal>
</section>
STUB;
    }
}

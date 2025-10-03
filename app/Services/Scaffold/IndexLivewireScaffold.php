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
use Illuminate\Contracts\View\View;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Session;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Auth;

class {{PluralModelName}} extends Component
{
    use LivewireAlert;
    use WithPagination;

    #[Session]
    public int \$perPage = 10;

    #[Url]
    public string \$search = '';

    public bool \$isShowModal = false;
    public ?int \$confirming{{ModelName}}Id = null; // id đang chờ confirm xóa

    /** @var array<int,string> */
    public array \$searchableFields = ['name'];

    protected \$listeners = [
        '{{ModelName}}Deleted' => '\$refresh',
        '{{ModelName}}NotDeleted' => '\$refresh',
    ];

    public function mount(): void
    {
        \$this->authorize('view {{PluralModelName}}');
    }

    public function updatingSearch(): void
    {
        \$this->resetPage();
    }

    // Khi nhấn delete → lưu user id và mở modal
    public function confirmDelete(int \${{ModelName}}Id): void
    {
        \$this->isShowModal = true;
        \$this->confirming{{ModelName}}Id = \${{ModelName}}Id;
    }

    public function afterDelete{{ModelName}}(): void
    {
        \$this->isShowModal = false;
        \$this->confirming{{ModelName}}Id = null;
    }

    // Thực sự xóa user khi confirm modal
    public function delete{{ModelName}}(): void
    {
        if (!\$this->confirming{{ModelName}}Id) {
            return;
        }

        \$this->authorize('delete {{PluralModelName}}');

        \${{ModelName}} = {{ModelName}}::findOrFail(\$this->confirming{{ModelName}}Id);

        if (\${{ModelName}}->hasRole('Super Admin') || \${{ModelName}}->id === Auth::id()) {
            \$this->alert('error', __('{{PluralModelName}}.cannot_delete'));
            \$this->confirming{{ModelName}}Id = null;
            \$this->afterDelete{{ModelName}}();
            return;
        }

        \${{ModelName}}->delete();

        \$this->alert('success', __('{{PluralModelName}}.{{ModelName}}_deleted'));

        \$this->confirming{{ModelName}}Id = null;
        \$this->resetPage();

        // **Đóng modal**
        \$this->afterDelete{{ModelName}}();
    }

    #[Layout('components.layouts.admin')]
    public function render(): View
    {
        \${{PluralModelName}} = {{ModelName}}::query()
            ->when(\$this->search, function (\$query) {
                foreach (\$this->searchableFields as \$field) {
                    \$query->orWhere(\$field, 'LIKE', "%{\$this->search}%");
                }
            })
            ->paginate(\$this->perPage);


        return view('livewire.admin.{{PluralModelName}}', compact('{{PluralModelName}}'));
    }
}

STUB;
    }

    public function createIndex()
    {
        $modelPath = app_path('Livewire/Admin/' . Str::pluralStudly($this->modelName) . '.php');
        if (!$this->files->exists($modelPath)) {
            $stub = $this->getIndexStub();
            $stub = str_replace('{{ModelName}}', $this->modelName, $stub);
            $stub = str_replace('{{PluralModelName}}', Str::pluralStudly($this->modelName), $stub);
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
            $stub = $this->getIndexBladeStub();
            $stub = str_replace('{{ModelName}}', $this->modelName, $stub);
            $stub = str_replace('{{PluralModelName}}', Str::pluralStudly($this->modelName), $stub);
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
        <x-slot:title>{{ __('{{PluralModelName}}.title') }}</x-slot:title>
        <x-slot:subtitle>{{ __('{{PluralModelName}}.title_description') }}</x-slot:subtitle>
        <x-slot:buttons>
            @can('create {{PluralModelName}}')
            <flux:button href="{{ route('admin.servers.create') }}" variant="primary" icon="plus">
                {{ __('{{PluralModelName}}.create_{{ModelName}}') }}
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
                <x-table.heading>{{ __('{{PluralModelName}}.{{ModelName}}_name') }}</x-table.heading>
                <x-table.heading class="text-right">{{ __('global.actions') }}</x-table.heading>
            </x-table.row>
        </x-slot:head>
        <x-slot:body>
            @foreach(\${{PluralModelName}} as \${{ModelName}})
            <x-table.row wire:key="{{ModelName}}-{{ \${{ModelName}}->id }}">
                <x-table.cell>{{ \${{ModelName}}->id }}</x-table.cell>
                <x-table.cell>{{ \${{ModelName}}->name }}</x-table.cell>
            </x-table.row>
            @endforeach
        </x-slot:body>
    </x-table>

    <div class="mt-4">
        {{ \${{PluralModelName}}->links() }}
    </div>

    <!-- Modal chung cho tất cả {{PluralModelName}} -->
    <flux:modal name="delete-{{ModelName}}-modal"
        x-show="\$wire.isShowModal"
        class="min-w-[22rem] space-y-6 flex flex-col justify-between">
        <div>
            <flux:heading size="lg">{{ __('{{PluralModelName}}.delete_{{ModelName}}') }}?</flux:heading>
            <flux:subheading>
                <p>{{ __('{{PluralModelName}}.you_are_about_to_delete') }}</p>
                <p>{{ __('global.this_action_is_irreversible') }}</p>
            </flux:subheading>
        </div>
        <div class="flex gap-2 !mt-auto mb-0">
            <flux:modal.close>
                <flux:button variant="ghost">{{ __('global.cancel') }}</flux:button>
            </flux:modal.close>
            <flux:spacer />
            <flux:button type="button" variant="danger" wire:click="delete{{ModelName}}">
                {{ __('{{PluralModelName}}.delete_{{ModelName}}') }}
            </flux:button>
        </div>
    </flux:modal>
</section>
STUB;
    }
}

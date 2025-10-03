<?php

namespace App\Services\Scaffold;

use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Str;

class NewModuleScaffold
{
    protected string $moduleName;
    protected string $modelName;
    protected string $tableName;
    protected string $livewireName;
    protected string $livewireComponents;
    protected Filesystem $files;
    public function __construct(string $moduleName)
    {
        $this->moduleName = $moduleName;
        $this->modelName = Str::studly($moduleName);
        $this->tableName = Str::snake(Str::pluralStudly($moduleName));
        $this->files = new Filesystem();
    }
    protected function getIndexLivewireStub(): string
    {
        return <<<STUB
<?php

namespace App\Http\Livewire\Admin;

use App\Models\{{ModelName}};
use Livewire\Component;
use Livewire\WithPagination;

class {{ModelName}} extends Component
{
    use WithPagination;

    public int \$perPage = 10;
    public string \$search = '';
    public array \$searchableFields = ['name'];

    public function render()
    {
        \$items = {{ModelName}}::query()
            ->when(\$this->search, function (\$query) {
                foreach (\$this->searchableFields as \$field) {
                    \$query->orWhere(\$field, 'LIKE', "%{\$this->search}%");
                }
            })
            ->paginate(\$this->perPage);

        return view('livewire.admin.{{tableName}}', [
            'items' => \$items
        ]);
    }
}
STUB;
    }


    protected function getIndexBladeStub(): string
    {
        return <<<STUB
<div class="p-4">
    <h2 class="text-xl font-bold mb-4">{{ '{{' }} __('{{ModelName}}') {{ '}}' }}</h2>

    <input type="text" wire:model="search" placeholder="Search..." class="border p-2 mb-4 w-full">

    <table class="w-full border">
        <thead>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach(\$items as \$item)
                <tr>
                    <td>{{ '{{' }} \$item->id {{ '}}' }}</td>
                    <td>{{ '{{' }} \$item->name {{ '}}' }}</td>
                    <td>
                        <button class="bg-blue-500 text-white px-2 py-1 rounded">Edit</button>
                        <button class="bg-red-500 text-white px-2 py-1 rounded">Delete</button>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="mt-4">
        {{ '{{' }} \$items->links() {{ '}}' }}
    </div>
</div>
STUB;
    }

    protected function getModelStub(): string
    {
        return <<<STUB
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class {{ModelName}} extends Model
{
    use HasFactory, SoftDeletes;

    protected \$fillable = [
    ];
}

STUB;
    }

    public function createModel()
    {
        $modelPath = app_path('Models/' . $this->modelName . '.php');
        if (!$this->files->exists($modelPath)) {
            $stub = $this->getModelStub();
            $stub = str_replace('{{ModelName}}', $this->modelName, $stub);
            $this->files->put($modelPath, $stub);
        }
    }

    public function createLivewire()
    {
        $livewirePath = app_path("Http/Livewire/Admin/$this->modelName.php");
        if (!$this->files->exists($livewirePath)) {
            $stub = $this->getIndexLivewireStub();
            $stub = str_replace('{{ModelName}}', $this->modelName, $stub);
            $stub = str_replace('{{tableName}}', $this->tableName, $stub);
            $this->files->put($livewirePath, $stub);
        }
    }

    public function createLivewireComponents()
    {
        $livewirePath = app_path('Livewire/Admin/' . $this->modelName . '/Index.php');
        if (!$this->files->exists($livewirePath)) {
            $stub = $this->getIndexLivewireStub();
            $stub = str_replace('{{ModelName}}', $this->modelName, $stub);
            $stub = str_replace('{{tableName}}', $this->tableName, $stub);
            $this->files->put($livewirePath, $stub);
        }
    }
}

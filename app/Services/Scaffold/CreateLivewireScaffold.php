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

    public function createCreateLivewireComponent()
    {
        $createLivewireComponentPath = app_path('Livewire/Admin/' . Str::pluralStudly($this->modelName) . '/Create.php');
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
}

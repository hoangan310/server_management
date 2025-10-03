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

  public function create{{ModelName}}(): void
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

  public function createEditLivewireComponent()
  {
    $editLivewireComponentPath = app_path('Livewire/Admin/' . Str::pluralStudly($this->modelName) . '/Create.php');
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
}

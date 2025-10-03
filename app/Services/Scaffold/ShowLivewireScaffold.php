<?php

namespace App\Services\Scaffold;

use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Str;

class ShowLivewireScaffold
{
  protected string $modelName;
  protected Filesystem $files;
  public function __construct(string $modelName)
  {
    $this->modelName = $modelName;
    $this->files = new Filesystem();
  }


  protected function getShowLivewireComponentStub(): string
  {
    return <<<STUB
<?php

namespace App\Livewire\Admin\{{PluralModelName}};

use App\Models\{{ModelName}};

class Show{{ModelName}} extends Component
{
  use LivewireAlert;

  public function mount(): void
  {
    \$this->authorize('show {{PluralModelName}}');
  }

  public function create{{ModelName}}(): void
  {
    \$this->authorize('show {{PluralModelName}}');

    \$this->validate();
    
  }

  #[Layout('components.layouts.admin')]
  public function render(): View
  {
    return view('livewire.admin.{{PluralModelName}}.show-{{ModelName}}');
  }
  
}

STUB;
  }

  public function createShowLivewireComponent()
  {
    $showLivewireComponentPath = app_path('Livewire/Admin/' . Str::pluralStudly($this->modelName) . '/Show.php');
    if (!$this->files->exists($showLivewireComponentPath)) {
      $stub = $this->getShowLivewireComponentStub();
      $stub = str_replace('{{ModelName}}', $this->modelName, $stub);
      $stub = str_replace('{{PluralModelName}}', Str::pluralStudly($this->modelName), $stub);
      if (!is_dir(dirname($showLivewireComponentPath))) {
        mkdir(dirname($showLivewireComponentPath), 0755, true);
      }
      $this->files->put($showLivewireComponentPath, $stub);
    }
  }
}

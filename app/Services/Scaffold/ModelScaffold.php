<?php

namespace App\Services\Scaffold;

use Illuminate\Filesystem\Filesystem;

class ModelScaffold
{
    protected string $modelName;
    protected Filesystem $files;
    public function __construct(string $modelName)
    {
        $this->modelName = $modelName;
        $this->files = new Filesystem();
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
            if (!is_dir(dirname($modelPath))) {
                mkdir(dirname($modelPath), 0755, true);
            }
            $this->files->put($modelPath, $stub);
        }
    }
}

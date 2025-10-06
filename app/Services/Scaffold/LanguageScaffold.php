<?php

namespace App\Services\Scaffold;

use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Str;

class LanguageScaffold
{
    protected string $modelName;
    protected Filesystem $files;
    protected array $supportedLocales = ['en', 'da', 'vi'];

    public function __construct(string $modelName)
    {
        $this->modelName = $modelName;
        $this->files = new Filesystem();
    }

    protected function getLanguageStub(): string
    {
        return <<<STUB
<?php

return [
    'title' => '{{PluralModelName}}',
    'title_description' => 'Manage {{pluralModelNameCamel}} in the application',
    'delete_{{modelName}}' => 'Delete {{modelName}}',
    'you_are_about_to_delete' => 'You are about to delete the {{modelName}}',
    '{{modelName}}_updated' => '{{ModelName}} updated',
    '{{modelName}}_deleted' => '{{ModelName}} deleted',
    '{{modelName}}_created' => '{{ModelName}} created',
    'cannot_delete' => 'Cannot delete this {{modelName}}',
    '{{modelName}}_name' => '{{ModelName}} name',
    'create_{{modelName}}' => 'Create {{modelName}}',
    'create_{{modelName}}_description' => 'Create a new {{modelName}} in the application',
    'edit_{{modelName}}' => 'Edit {{modelName}}',
    'edit_{{modelName}}_description' => 'Edit the {{modelName}} in the application',
    'update_{{modelName}}' => 'Update {{modelName}}',
    'view_{{modelName}}' => 'View {{modelName}}',
    '{{modelName}}_details' => '{{ModelName}} details',
    'created_at' => 'Created at',
    'updated_at' => 'Updated at',
];

STUB;
    }

    public function createLanguageFiles(): void
    {
        foreach ($this->supportedLocales as $locale) {
            $this->createLanguageFile($locale);
        }
    }

    protected function createLanguageFile(string $locale): void
    {
        $pluralModelName = Str::pluralStudly($this->modelName);
        $pluralModelNameCamel = Str::camel($pluralModelName);
        $modelName = Str::camel($this->modelName);
        $ModelName = Str::studly($this->modelName);

        $languagePath = lang_path($locale . '/' . $pluralModelNameCamel . '.php');

        if (!$this->files->exists($languagePath)) {
            $stub = $this->getLanguageStub();
            $stub = str_replace('{{modelName}}', $modelName, $stub);
            $stub = str_replace('{{ModelName}}', $ModelName, $stub);
            $stub = str_replace('{{PluralModelName}}', $pluralModelName, $stub);
            $stub = str_replace('{{pluralModelNameCamel}}', $pluralModelNameCamel, $stub);

            if (!is_dir(dirname($languagePath))) {
                mkdir(dirname($languagePath), 0755, true);
            }

            $this->files->put($languagePath, $stub);
        }
    }

    public function createLanguageFileForLocale(string $locale): void
    {
        $this->createLanguageFile($locale);
    }
}

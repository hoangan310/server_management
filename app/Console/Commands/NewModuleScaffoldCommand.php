<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Symfony\Component\Console\Command\Command as CommandAlias;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Filesystem\Filesystem;
use App\Services\Scaffold\NewModuleScaffold;

class NewModuleScaffoldCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'new:module {name}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Creates a new module';

    protected Filesystem $files;

    protected NewModuleScaffold $newModuleScaffold;

    public function __construct()
    {
        parent::__construct();
        $this->files = new Filesystem();
    }

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $name = $this->argument('name');
        $this->newModuleScaffold = new NewModuleScaffold($this->argument('name'));

        $modelClass = Str::studly($name);
        $tableName = Str::snake(Str::pluralStudly($name));
        $this->info("Tạo Model: $modelClass");
        Artisan::call('make:model', [
            'name' => $modelClass,
            '-m' => true, // tạo migration luôn
            '-f' => true, // tạo factory luôn
            '-s' => true, // tạo seeder luôn
        ]);
        $this->info(Artisan::output());

        $this->info("Tạo Livewire Components: $modelClass");
        $this->newModuleScaffold->createModel();
        $this->newModuleScaffold->createIndexComponent();
        $this->newModuleScaffold->createCreateComponent();
        $this->newModuleScaffold->createEditComponent();
        $this->newModuleScaffold->createShowComponent();
        $this->info(Artisan::output());

        $this->info("Tạo Language Files: $modelClass");
        $this->newModuleScaffold->createLanguageFiles();
        $this->info("Language files created successfully!");

        return CommandAlias::SUCCESS;
    }
}

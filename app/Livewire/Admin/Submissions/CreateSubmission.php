<?php

namespace App\Livewire\Admin\Submissions;

use App\Models\Category;
use App\Models\Company;
use App\Models\Submission;
use App\Services\ImageService;
use Illuminate\Contracts\View\View;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Validate;
use Livewire\Component;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;
use Livewire\Features\SupportFileUploads\WithFileUploads;
use Livewire\Features\SupportRedirects\HandlesRedirects;

class CreateSubmission extends Component
{
    use HandlesRedirects;
    use LivewireAlert;
    use WithFileUploads;

    #[Validate('required|string|max:255')]
    public string $name = '';

    #[Validate('required|email|max:255')]
    public string $email = '';

    #[Validate('required|exists:companies,id')]
    public int $company_id = 0;

    #[Validate('required|exists:categories,id')]
    public int $category_id = 0;

    #[Validate('nullable|image|max:5120')] // 5MB max
    public ?TemporaryUploadedFile $logo = null;

    public array $galeries = [];

    #[Validate('required|string|max:1000')]
    public string $message = '';

    public function mount(): void
    {
        $this->authorize('create submissions');
    }

    public function createSubmission(): void
    {
        $this->validate();

        // Validate galeries array
        $this->validate([
            'galeries' => 'nullable|array|max:10',
            'galeries.*' => 'nullable|image|max:5120',
        ]);

        $imageService = app(ImageService::class);

        $data = [
            'name' => $this->name,
            'email' => $this->email,
            'company_id' => $this->company_id,
            'category_id' => $this->category_id,
            'message' => $this->message,
        ];

        // Handle logo upload
        if ($this->logo) {
            $logoPath = $imageService->upload($this->logo, 'submissions', 300, 300);
            $data['logo'] = $logoPath;
        }

        // Handle galeries upload
        if (!empty($this->galeries)) {
            $galeryPaths = [];
            foreach ($this->galeries as $galery) {
                if ($galery) {
                    $galeryPaths[] = $imageService->upload($galery, 'submissions/galeries', 800, 600);
                }
            }
            $data['galeries'] = $galeryPaths;
        }

        Submission::create($data);

        $this->flash('success', __('submissions.submission_created'));

        $this->redirect(route('admin.submissions.index'), true);
    }

    public function removeLogo(): void
    {
        $this->logo = null;
    }

    public function removeGalery($index): void
    {
        unset($this->galeries[$index]);
        $this->galeries = array_values($this->galeries);
    }

    #[Layout('components.layouts.admin')]
    public function render(): View
    {
        $companies = Company::orderBy('name')->get();
        $categories = Category::orderBy('name')->get();

        return view('livewire.admin.submissions.create-submission', compact('companies', 'categories'));
    }
}

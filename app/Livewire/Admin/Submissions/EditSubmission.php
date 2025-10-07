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
use Livewire\Attributes\On;
use Livewire\Component;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;
use Livewire\Features\SupportFileUploads\WithFileUploads;
use Livewire\Features\SupportRedirects\HandlesRedirects;

class EditSubmission extends Component
{
    use HandlesRedirects;
    use LivewireAlert;
    use WithFileUploads;

    public Submission $submission;

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

    #[Validate('required|string|max:1000')]
    public string $message = '';

    public string $currentLogo = '';
    public bool $shouldRemoveLogo = false;

    public function mount(Submission $submission): void
    {
        $this->authorize('edit submissions');

        $this->submission = $submission;
        $this->name = $submission->name;
        $this->email = $submission->email;
        $this->company_id = $submission->company_id;
        $this->category_id = $submission->category_id;
        $this->message = $submission->message;
        $this->currentLogo = $submission->logo ?? '';
    }

    public function updateSubmission(): void
    {
        $this->validate();

        $data = [
            'name' => $this->name,
            'email' => $this->email,
            'company_id' => $this->company_id,
            'category_id' => $this->category_id,
            'message' => $this->message,
        ];

        $imageService = app(ImageService::class);

        // Handle logo removal
        if ($this->shouldRemoveLogo && $this->currentLogo) {
            $imageService->delete($this->currentLogo);
            $data['logo'] = null;
            $this->currentLogo = '';
        }

        // Handle new logo upload
        if ($this->logo) {
            // Delete old logo if exists and not already deleted
            if ($this->currentLogo) {
                $imageService->delete($this->currentLogo);
            }

            $logoPath = $imageService->upload($this->logo, 'submissions', 300, 300);
            $data['logo'] = $logoPath;
            $this->currentLogo = $logoPath;
        }

        $this->submission->update($data);

        $this->flash('success', __('submissions.submission_updated'));

        $this->redirect(route('admin.submissions.index'), true);
    }

    #[On('cancel')]
    public function cancel(): void
    {
        $this->redirect(route('admin.submissions.index'), true);
    }

    public function removeLogo(): void
    {
        $this->logo = null;
        $this->shouldRemoveLogo = true;
    }

    #[Layout('components.layouts.admin')]
    public function render(): View
    {
        $companies = Company::orderBy('name')->get();
        $categories = Category::orderBy('name')->get();

        return view('livewire.admin.submissions.edit-submission', compact('companies', 'categories'));
    }
}

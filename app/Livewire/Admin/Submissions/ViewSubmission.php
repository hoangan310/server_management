<?php

namespace App\Livewire\Admin\Submissions;

use App\Models\Submission;
use Illuminate\Contracts\View\View;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\Features\SupportRedirects\HandlesRedirects;

class ViewSubmission extends Component
{
    use HandlesRedirects;
    use LivewireAlert;

    public Submission $submission;

    public function mount(Submission $submission): void
    {
        $this->authorize('view submissions');
        $this->submission = $submission;
    }

    public function deleteSubmission(): void
    {
        $this->authorize('delete submissions');

        // Delete logo if exists
        if ($this->submission->logo) {
            $imageService = app(\App\Services\ImageService::class);
            $imageService->delete($this->submission->logo);
        }

        $this->submission->delete();

        $this->flash('success', __('submissions.submission_deleted'));

        $this->redirect(route('admin.submissions.index'), true);
    }

    #[Layout('components.layouts.admin')]
    public function render(): View
    {
        return view('livewire.admin.submissions.view-submission');
    }
}
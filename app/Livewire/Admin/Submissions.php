<?php

namespace App\Livewire\Admin;

use App\Models\Submission;
use Illuminate\Contracts\View\View;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Session;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

class Submissions extends Component
{
    use LivewireAlert;
    use WithPagination;

    #[Session]
    public int $perPage = 10;

    #[Url]
    public string $search = '';

    public ?int $confirmingSubmissionId = null;

    /** @var array<int,string> */
    public array $searchableFields = ['name'];

    protected $listeners = [
        'submissionDeleted' => '$refresh',
        'submissionNotDeleted' => '$refresh',
    ];

    public function mount(): void
    {
        $this->authorize('view submissions');
    }

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function confirmDelete(int $submissionId): void
    {
        $this->confirmingSubmissionId = $submissionId;
    }

    public function afterDeleteSubmission(): void
    {
        $this->confirmingSubmissionId = null;
    }

    public function deleteSubmission(): void
    {
        if (!$this->confirmingSubmissionId) {
            return;
        }

        $this->authorize('delete submissions');

        $submission = Submission::findOrFail($this->confirmingSubmissionId);
        $submission->delete();

        $this->alert('success', __('submissions.submission_deleted'));

        $this->dispatch('submissionDeleted');

        $this->resetPage();
        // $this->afterDeleteSubmission();
    }

    #[Layout('components.layouts.admin')]
    public function render(): View
    {
        $submissions = Submission::query()
            ->with(['company', 'category'])
            ->when($this->search, function ($query) {
                foreach ($this->searchableFields as $field) {
                    $query->orWhere($field, 'LIKE', "%{$this->search}%");
                }
            })
            ->paginate($this->perPage);

        return view('livewire.admin.submissions', compact('submissions'));
    }
}

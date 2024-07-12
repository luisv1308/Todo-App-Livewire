<?php

namespace App\Livewire;

use App\Models\Todo;
use Illuminate\View\View;
use Livewire\Attributes\Rule;
use Livewire\Component;
use Livewire\WithPagination;
use phpDocumentor\Reflection\Types\Boolean;

class TodoList extends Component
{
    use WithPagination;

    #[Rule('required|min:3|max:50')]
    public $name;
    public $search = '';

    public $editingTodoID;
    #[Rule('required|min:3|max:50')]
    public $editingTodoName;
    protected $updatesQueryString = ['search'];
    /**
     * Reset the pagination page when the search query is updated.
     */
    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function create(): void
    {
        $validated = $this->validateOnly('name');

        Todo::create($validated);

        $this->reset('name');

        session()->flash('success', 'Todo created successfully.');
        $this->resetPage();
    }

    public function delete($todoId): void
    {
        try {
            Todo::findOrFail($todoId)->delete();
        } catch (\Exception $exception) {
            session()->flash('error', 'Something went wrong while deleting the todo.');
        }
    }

    public function toogle(Todo $todo): bool
    {
        return  $todo->update(['completed' => ! $todo->completed]);
    }

    public function edit(Todo $todo): void
    {
        $this->editingTodoID = $todo->id;
        $this->editingTodoName = $todo->name;

    }

    public function update(Todo $todo): void
    {
        $validated = $this->validateOnly('editingTodoName');
        $todo->update([
            'name' => $this->editingTodoName
        ]);
        $this->cancelEdit();


    }

    public function cancelEdit(): void
    {
        $this->reset('editingTodoID', 'editingTodoName'); // reset this
    }
    public function render(): View
    {
        return view('livewire.todo-list', [
            'todos' => Todo::latest()->where('name', 'like', "%{$this->search}%")->paginate(5)
        ]);
    }
}

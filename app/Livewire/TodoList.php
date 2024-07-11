<?php

namespace App\Livewire;

use App\Models\Todo;
use Illuminate\View\View;
use Livewire\Attributes\Rule;
use Livewire\Component;

class TodoList extends Component
{
    #[Rule('required|min:3|max:50')]
    public $name;

    public function create(): void
    {
        // validate
        // create the todo
        // clear the input
        // send flash message

        $validated = $this->validateOnly('name');

        Todo::create($validated);

        $this->reset('name');

        session()->flash('success', 'Todo created successfully.');
    }
    public function render(): View
    {
        return view('livewire.todo-list');
    }
}

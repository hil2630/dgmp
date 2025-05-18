<?php

namespace App\Livewire;

use App\Models\Bracket;
use Livewire\Component;
use Livewire\WithPagination;

class Leaderboard extends Component
{
    use WithPagination;

    public ?Bracket $bracket = null;
    public $search = '';
    public $sortField = 'key_level';
    public $sortDirection = 'desc';

    public function mount(?Bracket $bracket = null)
    {
        $this->bracket = $bracket;
    }

    public function sortBy($field)
    {
        if ($this->sortField === $field) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortField = $field;
            $this->sortDirection = 'desc';
        }
    }

    public function render()
    {
        $query = $this->bracket
            ? $this->bracket->runs()
            : \App\Models\Run::query();

        $runs = $query
            ->when($this->search, function ($query) {
                $query->where(function ($query) {
                    $query->where('dungeon_name', 'like', '%' . $this->search . '%')
                        ->orWhereHas('team', function ($query) {
                            $query->where('name', 'like', '%' . $this->search . '%');
                        });
                });
            })
            ->orderBy($this->sortField, $this->sortDirection)
            ->paginate(10);

        return view('livewire.leaderboard', [
            'runs' => $runs,
        ]);
    }
}

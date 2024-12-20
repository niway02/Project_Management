<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Activity; // Adjust the model as necessary

class ActivityTable extends Component
{
    public $activities;

    public function mount()
    {
        // Fetch activities from the database
        $this->activities = Activity::all(); // Adjust the query as necessary
    }

    public function editActivity($id)
    {
        // Logic to edit the activity
        // Redirect or emit an event to open a modal, etc.
    }

    public function deleteActivity($id)
    {
        // Logic to delete the activity
        Activity::find($id)->delete();
        $this->activities = Activity::all(); // Refresh the activities list
        session()->flash('message', 'Activity deleted successfully.');
    }

    public function render()
    {
        return view('livewire.activity-table');
    }
}
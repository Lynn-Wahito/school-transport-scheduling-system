<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;
use App\Models\User;
use App\Models\TransportRequest;

class AdminDashboard extends Component
{
    public $totalUsers;
    public $totalTransportRequests;
    /**
     * Create a new component instance.
     */
    public function __construct()
    {
        $this->totalUsers = User::count();
        $this->totalTransportRequests = TransportRequest::count();
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.admin-dashboard');
    }
}

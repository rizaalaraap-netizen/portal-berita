<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\DashboardAnalyticsRequest;
use App\Services\AnalyticsDashboardService;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function __construct(private readonly AnalyticsDashboardService $analyticsDashboard)
    {
    }

    public function __invoke(DashboardAnalyticsRequest $request): View
    {
        return view('admin.dashboard', $this->analyticsDashboard->data($request->validated()));
    }
}

<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Services\Handlers\Dashboard\GetDashboardStatsHandler;
use Inertia\Inertia;
use Inertia\Response;

class DashboardController extends Controller
{
    public function __construct(private readonly GetDashboardStatsHandler $getDashboardStatsHandler)
    {
    }

    public function index(): Response
    {
        $stats = $this->getDashboardStatsHandler->handle();

        return Inertia::render('Dashboard', $stats);
    }
}

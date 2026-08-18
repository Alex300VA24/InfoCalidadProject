<?php

namespace Modules\Core\Http\Controllers;

use Inertia\Inertia;
use Inertia\Response;

class WelcomeController extends Controller
{
    /**
     * Display the module selection landing page.
     */
    public function __invoke(): Response
    {
        return Inertia::render('Welcome');
    }
}

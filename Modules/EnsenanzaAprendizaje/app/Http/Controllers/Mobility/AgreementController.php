<?php

namespace Modules\EnsenanzaAprendizaje\Http\Controllers\Mobility;

use Illuminate\Http\Request;
use Inertia\Inertia;
use Modules\Core\Http\Controllers\Controller;
use Modules\EnsenanzaAprendizaje\Http\Requests\StoreAgreementRequest;
use Modules\EnsenanzaAprendizaje\Models\Agreement;

class AgreementController extends Controller
{
    public function index(Request $request)
    {
        $query = Agreement::query();

        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $agreements = $query->latest('start_date')->paginate(15)->withQueryString();
        $types = Agreement::TYPES;
        $statuses = Agreement::STATUSES;

        return Inertia::render('Agreements/Index', [
            'agreements' => $agreements,
            'types' => $types,
            'statuses' => $statuses,
            'filters' => $request->only(['type', 'status']),
        ]);
    }

    public function create()
    {
        $types = Agreement::TYPES;
        $statuses = Agreement::STATUSES;

        return Inertia::render('Agreements/Create', [
            'types' => $types,
            'statuses' => $statuses,
        ]);
    }

    public function store(StoreAgreementRequest $request)
    {
        Agreement::create($request->validated());

        return redirect()->route('mobility.agreements.index')
            ->with('success', 'Convenio registrado correctamente.');
    }
}

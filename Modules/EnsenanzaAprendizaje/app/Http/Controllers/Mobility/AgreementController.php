<?php

namespace Modules\EnsenanzaAprendizaje\Http\Controllers\Mobility;

use Illuminate\Http\Request;
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

        $agreements = $query->latest('start_date')->paginate(15);
        $types = Agreement::TYPES;
        $statuses = Agreement::STATUSES;

        return view('mobility.agreements', compact('agreements', 'types', 'statuses'));
    }

    public function create()
    {
        $types = Agreement::TYPES;
        $statuses = Agreement::STATUSES;

        return view('mobility.agreements-create', compact('types', 'statuses'));
    }

    public function store(StoreAgreementRequest $request)
    {
        Agreement::create($request->validated());

        return redirect()->route('mobility.agreements.index')
            ->with('success', 'Convenio registrado correctamente.');
    }
}

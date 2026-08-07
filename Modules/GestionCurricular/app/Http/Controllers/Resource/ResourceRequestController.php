<?php

namespace Modules\GestionCurricular\Http\Controllers\Resource;

use Modules\Core\Http\Controllers\Controller;
use Modules\GestionCurricular\Http\Requests\StoreResourceRequest;
use Modules\GestionCurricular\Models\ResourceRequest;
use Modules\Core\Models\AcademicPeriod;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ResourceRequestController extends Controller
{
    public function index(Request $request)
    {
        $query = ResourceRequest::with(['academicPeriod', 'applicant']);

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        if ($request->filled('request_type')) {
            $query->where('request_type', $request->request_type);
        }

        $requests = $query->latest()->paginate(10);
        $periods = AcademicPeriod::all();

        return view('resources.index', compact('requests', 'periods'));
    }

    public function create()
    {
        $periods = AcademicPeriod::all();

        return view('resources.create', compact('periods'));
    }

    public function store(StoreResourceRequest $request)
    {
        $resourceRequest = ResourceRequest::create([
            'code' => 'RR-' . date('Y') . '-' . Str::padLeft(ResourceRequest::max('id') + 1, 4, '0'),
            'academic_period_id' => $request->academic_period_id,
            'applicant_id' => $request->user()->id,
            'title' => $request->title,
            'description' => $request->description,
            'request_type' => $request->request_type,
            'status' => 'pending',
        ]);

        if ($request->hasFile('documents')) {
            foreach ($request->file('documents') as $doc) {
                $path = $doc->store("resources/{$resourceRequest->code}/documents", 'public');
                $resourceRequest->documents()->create([
                    'document_type' => 'petition',
                    'filename' => $doc->getClientOriginalName(),
                    'file_path' => $path,
                    'file_size' => $doc->getSize(),
                ]);
            }
        }

        if ($request->hasFile('attachments')) {
            foreach ($request->file('attachments') as $att) {
                $path = $att->store("resources/{$resourceRequest->code}/attachments", 'public');
                $resourceRequest->attachments()->create([
                    'filename' => $att->getClientOriginalName(),
                    'file_path' => $path,
                    'file_size' => $att->getSize(),
                ]);
            }
        }

        return redirect()->route('resources.show', $resourceRequest)
            ->with('success', 'Solicitud de recursos creada correctamente.');
    }

    public function show(ResourceRequest $resourceRequest)
    {
        $resourceRequest->load(['academicPeriod', 'applicant', 'documents', 'attachments']);

        return view('resources.show', compact('resourceRequest'));
    }

    public function addResponseDocument(Request $request, ResourceRequest $resourceRequest)
    {
        $request->validate([
            'document_number' => 'required|string|max:50',
            'subject' => 'required|string|max:255',
            'file' => 'required|file|mimes:pdf|max:10240',
        ]);

        $file = $request->file('file');
        $path = $file->store("resources/{$resourceRequest->code}/documents", 'public');

        $resourceRequest->documents()->create([
            'document_type' => 'response',
            'document_number' => $request->document_number,
            'subject' => $request->subject,
            'filename' => $file->getClientOriginalName(),
            'file_path' => $path,
            'file_size' => $file->getSize(),
        ]);

        $resourceRequest->update(['status' => 'completed']);

        return redirect()->route('resources.show', $resourceRequest)
            ->with('success', 'Documento de respuesta agregado correctamente.');
    }

    public function downloadDocument($documentId)
    {
        $doc = \Modules\GestionCurricular\Models\ResourceDocument::findOrFail($documentId);

        if (!Storage::disk('public')->exists($doc->file_path)) {
            return back()->with('error', 'El archivo no se encuentra disponible.');
        }

        return Storage::disk('public')->download($doc->file_path, $doc->filename);
    }
}

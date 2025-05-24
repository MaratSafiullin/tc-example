<?php

namespace App\Http\Web\Controllers;

use App\Http\Core\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class DocsController extends Controller
{
    public function publicPage(): View
    {
        return view('scribe_public.index');
    }

    public function publicPostman(): BinaryFileResponse
    {
        return response()->download(Storage::disk('local')->path('scribe_public/collection.json'));
    }

    public function publicOpenApi(): BinaryFileResponse
    {
        return response()->download(Storage::disk('local')->path('scribe_public/openapi.yaml'));
    }
}

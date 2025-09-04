<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\PublicationResource;
use App\Http\Resources\PublicationCollection;
use App\Models\Publication;
use Illuminate\Http\Request;

class PublicationController extends Controller
{
    /**
     * Display a listing of the publications.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $publications = Publication::with(['community', 'author'])->paginate($request->get('per_page', 15));
        
        return new PublicationCollection($publications);
    }

    /**
     * Display the specified publication.
     *
     * @param  \App\Models\Publication  $publication
     * @return \Illuminate\Http\Response
     */
    public function show(Publication $publication)
    {
        $publication->load(['community', 'author']);
        
        return new PublicationResource($publication);
    }
}
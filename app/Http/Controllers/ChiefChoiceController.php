<?php

namespace App\Http\Controllers;

use App\Http\Requests\ChiefChoiceRequest;
use Facades\App\Repositories\Contracts\ChiefChoiceRepository;
use Facades\App\Repositories\Contracts\PostRepository;

class ChiefChoiceController extends Controller
{
    public function index()
    {
        $this->authorize('manage-chief-choice');

        $response = ChiefChoiceRepository::news();

        return response()->json($response);
    }

    public function store(ChiefChoiceRequest $request)
    {
        $news = PostRepository::findOrFail($request->post_id);

        $news->chiefChoice()->create($request->validated());

        if ($request->delete_item) {
            ChiefChoiceRepository::find($request->delete_item)->delete();
        }

        return response()->json([
            'message' => 'با موفقیت انجام شد',
        ], 201);
    }
}

<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Variation;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Validation\Rule;

class AlertVariationsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|Response|\Illuminate\View\View
     */
    public function index()
    {
        return view('welcome');
    }


    /**
     * Store the specified resource in storage.
     *
     * @param Request $request
     * @return Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'group_id' => 'required|integer|min:1|max:10',
            'name' => 'required|string',
            'display_duration' => 'required|integer',
            'text_delay' => 'required|integer',
            'text_duration' => 'required|integer',
            'volume' => 'required|integer|min:0|max:100',
        ]);
        $request->user()->variations()->create($request->all());

        return response()->json([
            'errors' => null,
            'status' => 'success',
            'text' => 'Изменения были успешно сохранены!'
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param $id
     * @return Response
     */
    public function update(Request $request, $id)
    {
        $variation = Variation::findOrFail($id);

        if ($variation->is_default) {
            $request->validate([
                'background_color' => 'required|string',
                'display_duration' => 'required|integer',
                'text_delay' => 'required|integer',
                'text_duration' => 'required|integer',
                'volume' => 'required|integer|min:0|max:100',
            ]);

            $variation->update($request->all());
            $variation->save();

            return response()->json([
                'errors' => null,
                'status' => 'success',
                'text' => 'Изменения были успешно сохранены!'
            ]);
        }

        else {
            $request->validate([
                'group_id' => 'required|integer|min:1|max:10',
                'name' => 'required|string',
                'display_duration' => 'required|integer',
                'text_delay' => 'required|integer',
                'text_duration' => 'required|integer',
                'volume' => 'required|integer|min:0|max:100',
            ]);
            $variation->update($request->all());
            $variation->save();

            return response()->json([
                'errors' => null,
                'status' => 'success',
                'text' => 'Изменения были успешно сохранены!'
            ]);
        }
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param Request $request
     * @return Response
     */
    public function remove(Request $request, $id)
    {
        $variation = Variation::findOrFail($id);
        if ($variation->is_default) {
            return response()->json([
                'status' => 'error',
                'text' => 'Недостаточно прав для удаления этой вариации!'
            ], 403);
        }

        $variation->delete();

        return response()->json([
            'status' => 'success',
            'text' => 'Вариация была удалена!'
        ]);
    }
}

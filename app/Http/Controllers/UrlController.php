<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Url;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Inertia\Inertia;
use App\Http\Requests\UrlRequest;


class UrlController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Inertia\Response
     */
    public function index()
    {
        return Inertia::render('Index', [
            'urls' => Url::with('user')->get(),
            'columns' => $this->get_all_columns(new UrlRequest()),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(UrlRequest $request)
    {
        try {
            $validated = $request->validated();
            $validated['user_id'] = Auth::user()->id;
            $validated['short'] = $this->shorten_link($validated['long']);

            $url = Url::create($validated);

            return response()->json([
                'status' => 'success',
                'message' => 'Запись сохранена!',
                'url' => $url->load('user')
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => $e->getMessage()
            ]);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Models\Url $url
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(Url $url)
    {
        try {
            $url->delete();
            return response()->json([
                'message' => 'Запись удалена!'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => $e->getMessage()
            ]);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param \App\Models\Url $url
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector|\Inertia\Response
     */
    public function open($short)
    {
        $url = Url::where('short', $short)->first();
        return redirect($url->long);
    }

}

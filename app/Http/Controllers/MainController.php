<?php

namespace App\Http\Controllers;

use App\Models\Shotlink;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MainController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('shotlink');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'linkold' => 'required|url',
        ]);
        $auth_id = Auth::id();
        Shotlink::create([
            'urlold' => $request->linkold,
            'urlnew' => $this->generateShortenedLink(),
            'auth_id' => $auth_id,
        ]);

        return response()->json(['status' => 'success']);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'linkold' => 'required|url',
        ]);

        $shotlink = Shotlink::findOrFail($id);
        $shotlink->urlold = $request['linkold'];
        $shotlink->urlnew = $this->generateShortenedLink();
        $shotlink->save();

        return response()->json(['status' => 'success']);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $shotlink = Shotlink::findOrFail($id)->delete();
    }

    private function generateShortenedLink()
    {
        return substr(md5(uniqid(rand(), true)), 0, 6);
    }

    public function getData(Request $request)
    {
        $data = Shotlink::where('auth_id', Auth::id())->get();
        return response()->json($data);
    }

    public function openlink($urlnew)
    {
        $urlreal = Shotlink::where('urlnew', $urlnew)->first('urlold');
        return redirect((url($urlreal['urlold'])));
    }

}

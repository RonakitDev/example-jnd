<?php

namespace App\Http\Controllers;

use App\Models\Shotlink;
use Illuminate\Http\Request;

class ShotlinkController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        return view('shotlink');
    }

    public function add(Request $request)
    {
        $request->validate([
            'linkold' => 'required|url',
        ]);

        Shotlink::create([
            'urlold' => $request->linkold,
            'urlnew' => $this->generateShortenedLink(),
        ]);

        return response()->json(['status' => 'success']);
    }

    public function update(Request $request, $id)
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

    public function delete($id)
    {
        $shotlink = Shotlink::findOrFail($id)->delete();
    }

    private function generateShortenedLink()
    {
        return substr(md5(uniqid(rand(), true)), 0, 6);
    }

    public function getData(Request $request)
    {
        $data = Shotlink::all();
        return response()->json($data);
    }

    public function openlink($urlnew)
    {
        $urlreal = Shotlink::where('urlnew', $urlnew)->first('urlold');
        return redirect((url($urlreal['urlold'])));
    }


}

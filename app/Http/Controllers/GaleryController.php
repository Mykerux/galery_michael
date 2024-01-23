<?php

namespace App\Http\Controllers;

use index;
use App\Models\Galery;
use Illuminate\Contracts\Session\Session as SessionSession;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session as FacadesSession;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\Session\Session;

class GaleryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $galeries = Galery::get();
        return view('index', compact('galeries'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $val = $request->validate([
            'judul' => "required",
            'deskripsi' => "required",
            'photo' => "required",
        ]);

        if ($request->hasFile('photo')) {
            $filePath = Storage::disk('public')->put('images/posts/', request()->file('photo'));
            $val['photo'] = $filePath;
        }

        $create = Galery::create([
            'judul' => $val['judul'],
            'deskripsi' => $val['deskripsi'],
            'photo' => $val['photo'],
            'user_id' => FacadesSession::get('user_id'),
            // 'user_id' => Session::get('user_id'),
        ]);

        
        if ($create) {
            session()->flash('success', 'Galery Berhasil Dimuat');

            return redirect('/gallery');
        }

        return abort(500);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Galery  $galery
     * @return \Illuminate\Http\Response
     */
    public function show(Galery $galery)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Galery  $galery
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $req)
    {
        $galery = Galery::find($req->id);
        // dd($galery);
        return response()->json($galery);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Galery  $galery
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Galery $galery)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Galery  $galery
     * @return \Illuminate\Http\Response
     */
    public function destroy(Galery $galery)
    {
        $galery->delete();
        return redirect('/gallery');
    }
}
<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Post;
use Illuminate\Support\Facades\Storage;

class GalleryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index () {
        $data = array(
        'id' => "posts",
        'menu' => 'Gallery',
        'galleries' => Post::where('picture', '!=',
        '')->whereNotNull('picture')->orderBy('created_at', 'desc')->paginate (30)
        );
        return view('gallery.index')->with ($data);
    }
    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('gallery.create');
    }

    /**
     * Store a newly created resource in storage.
     */
public function store(Request $request)
{
    $request->validate([
        'title' => 'required',
        'description' => 'required',
        'picture' => 'nullable|image'
    ]);

    if ($request->hasFile('picture')) {
        $filenameWithExt = $request->file('picture')->getClientOriginalName();
        $filename = pathinfo($filenameWithExt, PATHINFO_FILENAME);
        $extension = $request->file('picture')->getClientOriginalExtension();
        $basename = uniqid() . time();
        $smallFilename = "small_{$basename}. {$extension}";
        $mediumFilename = "medium {$basename}. {$extension}";
        $largeFilename = "large {$basename}.{$extension}";
        $filenameSimpan = "{$basename}.{$extension}";
        $path = $request->file('picture')->storeAs('posts_image', $filenameSimpan);
    } 
    else {
        $filenameSimpan  = 'noimage.png';
    }
        // dd($request->input());
        $post =  new Post;
        $post->picture = $filenameSimpan;
        $post->title  = $request->input('title');
        $post->description = $request->input('description');
        $post->save();
        return redirect('gallery')->with('success', 'Berhasil menambahkan data baru');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        // $request->validate([
        //     'title' => 'required',
        //     'description' => 'required',
        //     'picture' => 'nullable|image'
        // ], 
        // // Error message:
        // [
        //     'title.required' => 'Title harus diisi.',
        //     'description.required' => 'description harus diisi.',
        // ]);

        // dd("lolos bang");

        $post = Post::findOrFail($id);

        // Update gambar jika ada file baru yang diunggah
        if ($request->hasFile('picture')) {
            // Hapus gambar lama jika ada
            if ($post->picture != 'noimage.png') {
                Storage::delete('posts_image/' . $post->picture);
            }

            $filenameWithExt = $request->file('picture')->getClientOriginalName();
            $filename = pathinfo($filenameWithExt, PATHINFO_FILENAME);
            $extension = $request->file('picture')->getClientOriginalExtension();
            $basename = uniqid() . time();
            $filenameSimpan = "{$basename}.{$extension}";
            $path = $request->file('picture')->storeAs('posts_image', $filenameSimpan);

            $post->picture = $filenameSimpan;
        }

        // Update title dan description
        $post->title = $request->input('title');
        $post->description = $request->input('description');
        $post->save();

        return redirect('gallery')->with('success', 'Data berhasil diperbarui');
    }

    public function destroy(string $id)
    {
        $post = Post::findOrFail($id);

        // Hapus gambar jika ada (selain 'noimage.png')
        if ($post->picture != 'noimage.png') {
            Storage::delete('posts_image/' . $post->picture);
        }

        $post->delete();

        return redirect('gallery')->with('success', 'Data berhasil dihapus');
    }

}

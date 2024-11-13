<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use App\Models\User;


class UserController extends Controller
{
    public function index()
    {
        if (!Auth::check()) {
            return redirect()->route('login')
            ->withErrors([ 'email' => 'Please login to access the dashboard.', ])->onlyInput('email');
            }
            $dataUsers = User::latest()->paginate(5);
            return view('users', compact('dataUsers'));
    }
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'namaUser' => 'required|string|max:250',
            'emailUser' => 'required|email|max:250|unique:users,email',
            'passwordUser' => 'required|min:1',
            'password_confirmation' => 'required|same:passwordUser',
            'isAdmin' => 'required',
            'photo' => 'nullable|image|mimes:jpg,jpeg,png'
        ]);

        if ($request->hasFile('photo')) {
            $filenameWithExt = $request->file('photo')->getClientOriginalName();
            $filename = pathinfo($filenameWithExt, PATHINFO_FILENAME);
            $extension = $request->file('photo')->getClientOriginalExtension();
            $filenameSimpan = $filename. '_' . time() . '.'. $extension;
            
            // Simpan file ke storage
            $path = $request->file('photo')->storeAs('public/photo', $filenameSimpan);
        }
    
        // Jika validasi berhasil, simpan data mahasiswa
        $data = [
            'name' => $validatedData['namaUser'],
            'email' => $validatedData['emailUser'],
            'photo' => $path,
            'password' => $validatedData['passwordUser'],
            'isAdmin' => $validatedData['isAdmin'],
        ];
    
        
        
    
        User::create($data);
    
        return redirect()->route('datausers.index')->with('success', 'Data user berhasil ditambahkan.');
    }
    
    

    public function update(Request $request, User $user)
    {
    
        // Cek jika nama baru berbeda dari yang ada di database
        if ($request->namaUser !== $user->name) {
            $user->name = $request->namaUser;
        }
    
        // Cek jika email baru berbeda dari yang ada di database
        if ($request->emailUser !== $user->email) {
            $user->email = $request->emailUser;
        }
    
        // Cek jika ada upload foto baru
        if ($request->hasFile('photo')) {

            if ($user->photo) {
                // Menghapus file foto lama
                Storage::delete($user->photo);
            }

            $filenameWithExt = $request->file('photo')->getClientOriginalName();
            $filename = pathinfo($filenameWithExt, PATHINFO_FILENAME);
            $extension = $request->file('photo')->getClientOriginalExtension();
            $filenameSimpan = $filename. '_' . time() . '.'. $extension;
            
            // Simpan file ke storage
            $path = $request->file('photo')->storeAs('public/photo', $filenameSimpan);
            $user->photo = $path;
        }
    
    
    
        // Simpan perubahan hanya jika ada perubahan data
        if ($user->isDirty()) {
            $user->save();
            return redirect()->route('datausers.index')->with('success', 'Data user berhasil diperbarui.');
        }
    
        return redirect()->route('datausers.index')->with('info', 'Tidak ada perubahan pada data user.');
    }
    

    public function destroy(User $user)
    {

        $user = User::find($user->id);
        $file = public_path() .'/storage/' .$user->photo;
        try {
        if (File:: exists($file)) {
            File::delete ($file);
            $user->delete();
        }
        } catch (\Throwable $th) {
            return redirect('users')->with('error', 'Gagal hapus data');
        }
        $user->delete();

        return redirect()->route('datausers.index')->with('success', 'Data user berhasil dihapus.');
    }
}

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
class UsuariosController extends Controller
{
    public function index()
    {
        $users = User::with('roles') // carga los roles para evitar consultas N+1
            ->where('id', '!=', auth()->id())
            ->orderBy('name', 'ASC')
            ->get();
        return view('admin.usuarios.index', compact('users'));
    }
    public function form($id = null)
    {
        $user = null;

        $roles = Role::all();
        if ($id) {
            $user = User::findOrFail($id);
            return view('admin.usuarios.manage_usuarios', compact('user', 'roles'));
        }
        return view('admin.usuarios.manage_usuarios', compact('roles'));
    }

    public function saveUser(Request $request)
    {
        $rules = [
            'name' => 'required|string|max:255',
            'email' => 'required|unique:users,email,' . $request->id,
            'password' => $request->id ? 'nullable|min:6' : 'required|min:6',
            'img' => 'nullable|image|mimes:jpeg,png',
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'errors' => $validator->errors()
            ], 422);
        }

        $user = $request->id ? User::findOrFail($request->id) : new User;

        $user->name = $request->name;
        $user->email = $request->email;

        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }
        $user->assignRole($request->type);
        $user->save();


        // Procesar imagen y guardarla en public/uploads/avatars/
        if ($request->hasFile('img')) {
            $avatarPath = public_path('uploads/avatars');
            if (!is_dir($avatarPath)) {
                mkdir($avatarPath, 0755, true);
            }

            $file = $request->file('img');
            $filename = $user->id . '.png';
            $fullPath = $avatarPath . DIRECTORY_SEPARATOR . $filename;

            // Si existe imagen anterior, eliminarla
            if (file_exists($fullPath)) {
                unlink($fullPath);
            }

            // Mover el archivo original sin modificar
            $file->move($avatarPath, $filename);

            // Guardar ruta relativa con cache busting en la DB
            $user->avatar = "uploads/avatars/{$filename}?v=" . time();
            $user->save();
        }

        if ($request->id) {
            $request->session()->flash('success', 'Detalles actualizados correctamente.');
        } else {
            $request->session()->flash('success', 'Usuario creado exitosamente.');
        }


        return response()->json([
            'status' => 'success',
            'message' => 'Detalles del usuario guardados correctamente.',
            'user' => $user,
        ]);
    }

    public function destroy($id)
    {
        $user = User::find($id);

        if (!$user) {
            return response()->json(['status' => 'error', 'message' => 'Usuario no encontrado.'], 404);
        }

        // Ruta del avatar
        $avatarPath = public_path("uploads/avatars/{$user->id}.png");

        try {
            // Eliminar usuario
            $user->delete();

            // Eliminar archivo avatar si existe
            if (file_exists($avatarPath)) {
                unlink($avatarPath);
            }

            session()->flash('success', 'Usuario eliminado correctamente.');

            return response()->json(['status' => 'success', 'message' => 'Usuario eliminado correctamente.']);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => 'Error al eliminar el usuario.'], 500);
        }
    }

    public function perfil($id)
    {
        $user = null;

        $roles = Role::all();

        $user = User::findOrFail($id);
        return view('admin.usuarios.perfil', compact('user', 'roles'));

    }


}

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Models\SystemInfo;
use Illuminate\Support\Facades\File;
class SystemController extends Controller
{
    public function index()
    {
        // Carga las configuraciones en formato clave => valor
        $settings = SystemInfo::pluck('meta_value', 'meta_field');

        $bannerFiles = [];
        $bannerPath = public_path('uploads/banner');
        if (File::exists($bannerPath)) {
            foreach (File::files($bannerPath) as $file) {
                $bannerFiles[] = $file->getFilename();
            }
        }

        // Pasa $settings y $bannerFiles a la vista
        return view('admin.system.index', compact('settings', 'bannerFiles'));
    }

    public function update(Request $request)
    {
        $data = $request->validate([
            'name' => 'nullable|string',
            'short_name' => 'nullable|string',
            'logo' => 'nullable|image|mimes:png',
            'cover' => 'nullable|image|mimes:jpg,jpeg,png',
            'banners.*' => 'nullable|image|mimes:jpg,jpeg,png',
        ]);

        // Guardar textos (name, short_name)
        foreach (['name', 'short_name'] as $field) {
            if ($request->filled($field)) {
                SystemInfo::updateOrCreate(
                    ['meta_field' => $field],
                    ['meta_value' => $request->input($field)]
                );
            }
        }

        // Guardar logo
        if ($request->hasFile('logo')) {
            $logoFile = $request->file('logo');
            $logoName = 'logo.' . $logoFile->getClientOriginalExtension();
            $destinationPath = public_path('uploads');

            if (!file_exists($destinationPath)) {
                mkdir($destinationPath, 0755, true);
            }

            $logoFile->move($destinationPath, $logoName);

            SystemInfo::updateOrCreate(
                ['meta_field' => 'logo'],
                ['meta_value' => $logoName]
            );
        }

        // Guardar cover
        if ($request->hasFile('cover')) {
            $coverFile = $request->file('cover');
            $coverName = 'cover.' . $coverFile->getClientOriginalExtension();
            $destinationPath = public_path('uploads');

            if (!file_exists($destinationPath)) {
                mkdir($destinationPath, 0755, true);
            }

            $coverFile->move($destinationPath, $coverName);
            SystemInfo::updateOrCreate(
                ['meta_field' => 'cover'],
                ['meta_value' => $coverName]
            );
        }




        // Guardar banners (usando nombre original)
        if ($request->hasFile('banners')) {
            $bannerNames = [];

            foreach ($request->file('banners') as $banner) {
                $originalName = $banner->getClientOriginalName();
                $destinationPath = public_path('uploads/banner');

                // Crear el directorio si no existe
                if (!file_exists($destinationPath)) {
                    mkdir($destinationPath, 0755, true);
                }

                // Mover el archivo a public/uploads/banner
                $banner->move($destinationPath, $originalName);

                $bannerNames[] = $originalName;
            }

        }


        return redirect()->back()->with('success', 'Información actualizada correctamente.');
    }



    public function deleteImage(Request $request)
    {
        $fileName = $request->input('path'); // ej: wp1.jpg

        // Validar que no tenga directorios extraños
        if (basename($fileName) !== $fileName) {
            return response()->json(['status' => 'error', 'message' => 'Invalid file name']);
        }

        $fullPath = public_path('uploads/banner/' . $fileName);

        if (File::exists($fullPath)) {
            File::delete($fullPath);
            return response()->json(['status' => 'success']);
        } else {
            return response()->json(['status' => 'error', 'message' => 'File does not exist']);
        }
    }
}

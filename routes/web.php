<?php

use App\Http\Controllers\CategoryController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ReportesController;
use App\Http\Controllers\SalesController;
use App\Http\Controllers\UsuariosController;
use App\Http\Controllers\SystemController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return redirect('login');
});

require __DIR__ . '/auth.php';


Route::middleware('auth')->group(function () {
    // Dashboard
    Route::get('/admin', [DashboardController::class, 'index'])->name('admin.index');

    // Categorias
    Route::get('/admin/categorias', [CategoryController::class, 'index'])->name('admin.categorias.index')->middleware('can:admin.categorias.index');
    Route::get('/admin/categorias/form/{id?}', [CategoryController::class, 'form'])->name('admin.categorias.form')->middleware('can:admin.categorias.index');
    Route::post('/admin/categorias', [CategoryController::class, 'storeOrUpdate'])->middleware('can:admin.categorias.index');
    Route::get('/admin/categorias/{id}/view', [CategoryController::class, 'view'])->middleware('can:admin.categorias.index');
    Route::delete('/admin/categorias/{id}/delete', [CategoryController::class, 'destroy'])->middleware('can:admin.categorias.index');

    //Productos
    Route::get('/admin/productos', [ProductController::class, 'index'])->name('admin.productos.index')->middleware('can:admin.productos.index');
    Route::get('/admin/productos/form/{id?}', [ProductController::class, 'form'])->name('admin.productos.form')->middleware('can:admin.productos.index');
    Route::post('/admin/productos', [ProductController::class, 'storeOrUpdate'])->middleware('can:admin.productos.index');
    Route::delete('/admin/productos/{id}/delete', [ProductController::class, 'destroy'])->middleware('can:admin.productos.index');
    Route::get('/admin/productos/{id}/show', [ProductController::class, 'show'])->name('admin.productos.show')->middleware('can:admin.productos.index');

    //Ventas
    Route::get('/admin/ventas', [SalesController::class, 'index'])->name('admin.sales.index');
    Route::get('/admin/ventas/form/{id?}', [SalesController::class, 'form'])->name('admin.sales.form');
    Route::post('/admin/ventas', [SalesController::class, 'saveSale']);
    Route::get('/admin/ventas/{id}/show', [SalesController::class, 'show'])->name('admin.sales.show');
    Route::post('/admin/ventas/delete', [SalesController::class, 'destroy']);

    //Reportes
    Route::get('/admin/reportes', [ReportesController::class, 'index'])->name('admin.reportes.index');

    //Usuarios
    Route::get('/admin/usuarios/index', [UsuariosController::class, 'index'])->name('admin.usuarios.index')->middleware('can:admin.usuarios.index');
    Route::get('/admin/usuarios/form/{id?}', [UsuariosController::class, 'form'])->name('admin.usuarios.form')->middleware('can:admin.usuarios.index');
    Route::get('/usuario/perfil/{id}', [UsuariosController::class, 'perfil'])->name('admin.usuarios.perfil');
    Route::post('/admin/usuario', [UsuariosController::class, 'saveUser']);
    Route::delete('/admin/usuario/{id}/delete', [UsuariosController::class, 'destroy'])->middleware('can:admin.usuarios.index');

    //System
    Route::get('/admin/system/settings', [SystemController::class, 'index'])->name('admin.system.edit')->middleware('can:admin.system.edit');
    Route::post('/admin/system/update', [SystemController::class, 'update'])->name('admin.system.update')->middleware('can:admin.system.edit');
    Route::post('/admin/system/delete-image', [SystemController::class, 'deleteImage'])->name('admin.system.delete_image')->middleware('can:admin.system.edit');
});
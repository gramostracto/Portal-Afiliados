<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RolController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Configs;
use App\Http\Controllers\UsuarioController;
use App\Http\Controllers\FacturaController;
use App\Http\Controllers\ConsultarAfiliadoController;
use App\Http\Controllers\ErrorController;
use App\Http\Controllers\FormController;
use App\Http\Controllers\PasswordController;
use App\Http\Controllers\PerfilController;
use Illuminate\Support\Facades\Auth;

Route::get('/', function () {
    return view('auth.login');
});


Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home')->middleware('verified');

Auth::routes(['verify' => true]);

//? Usuarios-Clientes
Route::prefix('portal/users')->controller(UsuarioController::class)->middleware('auth')->group(function () {
    Route::get('/', 'index', 'can:/usuario.index')->name('usuario.index');
    Route::get('config/{id}', 'checkout')->name('check');
    route::get('edit/{id}', 'edit')->name('edit');
    Route::get('confirmar/{usuario}/{estado}', 'confirmarDatos')->name('usuario.estado');
    Route::post('userAsociado', 'createUserAsociado')->name('userAsociado.create');
    Route::post('/', 'filtros')->name('user.filtros');
    Route::delete('/deleted', 'destroy')->name('usuario.eliminar');
    Route::get('/confimacion', 'confirmarUser')->name('consultar.proveedorLocal');
    Route::post('/change-password', 'changePassword')->name('change-password.update');
    Route::get('/testinvoice', 'test')->name('consultar.invoicetest');
    Route::get('/deleted/get', 'getdeletedUsers')->name('get.users.deleted');
    Route::post('/reactivate', 'reactivateUser')->name('users.reactivate');
});

Route::resource('portal/usuarios', UsuarioController::class)->middleware(['auth', 'permission:/usuario.index']);

Route::get('forgot-password', [AuthController::class, 'email'])->name('forgot-password');

Route::prefix('profile')->controller(PerfilController::class)->middleware('auth')->group(function () {
    Route::get('/', 'index')->name('profile');
    Route::get('userAsociado/{id}', 'eliminarUserAsociado')->name('userAsociado.delete');
    Route::get('userAsociadoRestore/{id}', 'reasignarUserAsociado')->name('userAsociado.restore');
    Route::put('/update', 'update')->name('profile.update');
    Route::post('/photoUpdate', 'photoUpdate')->name('photo-profile.updatePhoto');
});

Route::prefix('consultaOTM')->controller(ConsultarAfiliadoController::class)->middleware('auth')->group(function () {
    Route::get('/', 'index')->name('consultar');
    Route::get('afiliado/{id}', 'consultaOTM')->middleware('permission:/usuario.index')->name('consultar.afiliado');
});

Route::controller(ConsultarAfiliadoController::class)->middleware('auth')->group(function () {

    Route::post('totalsByStatus', 'totalsByStatus')->name('invoices.totalsByStatus');

    Route::post('invoiceLines', 'getInvoiceLines')->name('invoice.lines');

    Route::post('searchInvoices', 'searchInvoices')->name('invoices.search');
    Route::post('facturas/transporte', 'getShipmentOtm')->name('facturas.transporte');
    Route::post('facturas/transporte/detalle', 'getShipmentDetalle')->name('facturas.transporte.detalle');
    Route::post('suppliernumber', 'getSupplierNumber')->name('supplier.number');
    Route::get('SelectSuppliernumber', 'SelectSupplierNumber')->name('selectSupplier.number');
    Route::post('consultaOTM/afiliado', 'consultaOTM')->middleware('permission:/usuario.index')->name('afiliado.consulta');
    Route::post('proveedor', 'proveedorEncargado')->middleware('permission:/usuario.index')->name('proveedor.encargado');
});

Route::prefix('customers')->controller(FacturaController::class)->middleware('auth')->group(function () {
    Route::get('{id}', 'index')->name('blogs.index');
});

Route::middleware('auth', 'can:/roles')->group(function () {
    Route::resource('portal/roles', RolController::class);
    Route::delete('portal/roles/deleted', [RolController::class, 'destroy'])->name('roles.eliminar');
});

Route::prefix('portal/setting')->controller(Configs::class)->middleware(['auth', 'permission:/usuario.index'])->group(function () {
    Route::get('/', 'index')->name('setting');
    Route::post('/date', 'getDecryptedData')->name('setting.date');
    Route::post('/', 'update')->name('setting.update');
    Route::get('/create', 'create')->name('setting.create');
    Route::post('/store', 'store')->name('setting.store');
    Route::get('/system', 'configSistem')->name('setting.system');
    Route::get('/system/modific', 'configSistemModificacion')->name('setting.system.modific');
    Route::get('/statistics', 'statistics')->name('setting.statistics');
    Route::get('/statistics/affiliate', 'listarAfiliados')->name('setting.affiliate');
    Route::get('/statistics/countLogin', 'countLogin')->name('setting.statistics.countLogin');
    Route::get('/statistics/actionHome', 'countActionHome')->name('setting.statistics.actionHome');
    Route::get('/statistics/filter', 'filter')->name('setting.statistics.filter');
});

Route::get('/refresh-captcha', [FormController::class, 'refreshCaptcha'])->name('refresh.captcha');

Route::prefix('error')->controller(ErrorController::class)->middleware('auth')->group(function () {
    Route::get('/404', 'error404')->name('error404');
    Route::get('/proveedor-no-encontrado', 'proveedorNoEncontrado')->name('error.proveedor-no-encontrado');
});

Route::post('/enviar-contrasena', [PasswordController::class, 'enviarContrasenaPorCorreo'])->middleware(['auth', 'permission:/usuario.index'])->name('enviar-contrasena');

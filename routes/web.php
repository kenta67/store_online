<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ActividadController;
use App\Http\Controllers\CarritoController;
use App\Http\Controllers\ProductoController;

// Rutas principales
Route::get('/', [HomeController::class, 'index'])->name('index');
Route::get('/home', [HomeController::class, 'index'])->name('home');

// Rutas de autenticación
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::get('/register', [AuthController::class, 'showRegistrationForm'])->name('register');
Route::post('/register', [AuthController::class, 'register']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Rutas de productos
Route::get('/producto/{id}', [ProductoController::class, 'detalle'])->name('producto.detalle');

// Rutas protegidas (requieren autenticación)
Route::middleware(['auth'])->group(function () {
    // Actividad
    Route::get('/actividad', [ActividadController::class, 'index'])->name('actividad');
    Route::get('/actividad/compras', [ActividadController::class, 'compras'])->name('actividad.compras');
    Route::get('/actividad/compra/{id}', [ActividadController::class, 'detalleCompra'])->name('actividad.compra.detalle');
    
    // Carrito
    Route::get('/carrito', [CarritoController::class, 'index'])->name('carrito');
    Route::post('/carrito/agregar/{idProducto}', [CarritoController::class, 'agregar'])->name('carrito.agregar');
    Route::put('/carrito/actualizar/{idCarrito}', [CarritoController::class, 'actualizar'])->name('carrito.actualizar');
    Route::delete('/carrito/eliminar/{idCarrito}', [CarritoController::class, 'eliminar'])->name('carrito.eliminar');
    Route::delete('/carrito/vaciar', [CarritoController::class, 'vaciar'])->name('carrito.vaciar');
    Route::post('/carrito/direccion/guardar', [CarritoController::class, 'guardarDireccion'])->name('carrito.guardar-direccion');
    
    // PayPal
    Route::post('/carrito/paypal/crear-pago', [CarritoController::class, 'crearPagoPayPal'])->name('carrito.paypal.crear-pago');
    Route::get('/carrito/paypal/exito', [CarritoController::class, 'pagoExitoso'])->name('carrito.paypal.exito');
    Route::get('/carrito/paypal/cancelado', [CarritoController::class, 'pagoCancelado'])->name('carrito.paypal.cancelado');
    Route::get('/carrito/completado', [CarritoController::class, 'completado'])->name('carrito.completado');
    
    // Configuración
    Route::post('/configuracion/actualizar', [HomeController::class, 'actualizarConfiguracion'])->name('configuracion.actualizar');
});
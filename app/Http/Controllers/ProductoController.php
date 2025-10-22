<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Producto;
use App\Models\Carrito;
use App\Models\Cliente;

class ProductoController extends Controller
{
    public function detalle($id)
    {
        // Obtener el producto con sus relaciones
        $producto = Producto::with(['marca', 'categoria'])
            ->where('idproducto', $id)
            ->where('estado', 1)
            ->firstOrFail();

        // Productos sugeridos (excluyendo el actual)
        $sugeridos = Producto::with(['marca', 'categoria'])
            ->where('estado', 1)
            ->where('stock', '>', 0)
            ->where('idproducto', '!=', $id)
            ->inRandomOrder()
            ->limit(4)
            ->get();

        // Datos del usuario si está logueado
        $user = Auth::user();
        $cliente = null;
        $cartCount = 0;
        
        if ($user) {
            $cliente = Cliente::where('idusuario', $user->idusuario)->first();
            if ($cliente) {
                $cartCount = Carrito::where('idcliente', $cliente->idcliente)->sum('cantidad');
            }
        }

        return view('producto.detalle', compact(
            'producto', 
            'sugeridos', 
            'user', 
            'cliente', 
            'cartCount'
        ));
    }

    public function agregarAlCarrito(Request $request, $id)
    {
        if (!Auth::check()) {
            return response()->json([
                'success' => false,
                'message' => 'Debes iniciar sesión para agregar productos al carrito',
                'redirect' => route('login')
            ], 401);
        }

        $user = Auth::user();
        $cliente = Cliente::where('idusuario', $user->idusuario)->first();

        if (!$cliente) {
            return response()->json([
                'success' => false,
                'message' => 'Cliente no encontrado'
            ], 404);
        }

        $producto = Producto::find($id);
        if (!$producto) {
            return response()->json([
                'success' => false,
                'message' => 'Producto no encontrado'
            ], 404);
        }

        $cantidad = $request->input('cantidad', 1);

        // Verificar stock
        if ($producto->stock < $cantidad) {
            return response()->json([
                'success' => false,
                'message' => 'No hay suficiente stock disponible. Stock actual: ' . $producto->stock
            ], 400);
        }

        // Agregar al carrito
        $carrito = Carrito::where('idcliente', $cliente->idcliente)
            ->where('idproducto', $id)
            ->first();

        if ($carrito) {
            $carrito->cantidad += $cantidad;
            $carrito->save();
        } else {
            Carrito::create([
                'idcliente' => $cliente->idcliente,
                'idproducto' => $id,
                'cantidad' => $cantidad
            ]);
        }

        // Actualizar contador del carrito
        $cartCount = Carrito::where('idcliente', $cliente->idcliente)->sum('cantidad');

        return response()->json([
            'success' => true,
            'message' => 'Producto agregado al carrito',
            'cartCount' => $cartCount,
            'producto' => [
                'nombre' => $producto->nombre,
                'precio' => $producto->precio,
                'imagen' => $producto->imagen_url
            ]
        ]);
    }
}
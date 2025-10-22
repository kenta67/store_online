<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\Cliente;
use App\Models\Compra;
use App\Models\DetalleCompra;
use App\Models\Carrito;

class ActividadController extends Controller
{
    public function index()
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $user = Auth::user();
        $cliente = Cliente::where('idusuario', $user->idusuario)->first();

        if (!$cliente) {
            return redirect()->route('index')->with('error', 'Cliente no encontrado.');
        }

        // Obtener compras del cliente
        $compras = Compra::with(['detalles.producto.marca', 'detalles.producto.categoria'])
            ->where('idcliente', $cliente->idcliente)
            ->orderBy('fecharegistro', 'desc')
            ->get();

        // Obtener productos en el carrito
        $carrito = Carrito::with('producto.marca')
            ->where('idcliente', $cliente->idcliente)
            ->get();

        // Estadísticas del cliente
        $estadisticas = [
            'total_compras' => $compras->count(),
            'total_gastado' => $compras->sum('montototal'),
            'productos_comprados' => $compras->sum('totalproducto'),
            'productos_carrito' => $carrito->sum('cantidad'),
        ];

        // Productos más comprados
        $productosFrecuentes = DetalleCompra::select(
                'producto.idproducto',
                'producto.nombre',
                'marca.descripcion as marca',
                DB::raw('SUM(detallecompra.cantidad) as total_comprado'),
                DB::raw('SUM(detallecompra.total) as total_gastado')
            )
            ->join('producto', 'detallecompra.idproducto', '=', 'producto.idproducto')
            ->join('marca', 'producto.idmarca', '=', 'marca.idmarca')
            ->join('compra', 'detallecompra.idcompra', '=', 'compra.idcompra')
            ->where('compra.idcliente', $cliente->idcliente)
            ->groupBy('producto.idproducto', 'producto.nombre', 'marca.descripcion')
            ->orderBy('total_comprado', 'desc')
            ->limit(5)
            ->get();

        return view('actividad.index', compact(
            'compras', 
            'carrito', 
            'estadisticas', 
            'productosFrecuentes',
            'cliente',
            'user'
        ));
    }

    public function compras()
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $user = Auth::user();
        $cliente = Cliente::where('idusuario', $user->idusuario)->first();

        $compras = Compra::with(['detalles.producto.marca', 'detalles.producto.categoria'])
            ->where('idcliente', $cliente->idcliente)
            ->orderBy('fecharegistro', 'desc')
            ->paginate(10);

        return view('actividad.compras', compact('compras', 'cliente', 'user'));
    }

    public function detalleCompra($id)
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $user = Auth::user();
        $cliente = Cliente::where('idusuario', $user->idusuario)->first();

        $compra = Compra::with([
                'detalles.producto.marca', 
                'detalles.producto.categoria',
                'direccion',
                'metodoPago'
            ])
            ->where('idcliente', $cliente->idcliente)
            ->where('idcompra', $id)
            ->firstOrFail();

        return view('actividad.detalle-compra', compact('compra', 'cliente', 'user'));
    }
}
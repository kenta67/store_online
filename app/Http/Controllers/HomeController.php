<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\Producto;
use App\Models\Marca;
use App\Models\Categoria;
use App\Models\Carrito;
use App\Models\User;
use App\Models\Cliente;

class HomeController extends Controller
{
    private $pageSize = 8; 

    public function index(Request $request)
    {
        $orden = $request->get('orden', 'rand');
        $marcaFiltro = $request->get('marca', 0);
        $categoriaFiltro = $request->get('categoria', 0);
        $busqueda = $request->get('busqueda', '');
        $currentPage = $request->get('page', 1);

        
        $marcas = Marca::where('estado', 1)->orderBy('descripcion')->get();
        $categorias = Categoria::where('estado', 1)->orderBy('descripcion')->get();

        
        $query = Producto::with(['marca', 'categoria'])
            ->where('producto.estado', 1);


        if ($marcaFiltro != 0) {
            $query->where('producto.idmarca', $marcaFiltro);
        }

        if ($categoriaFiltro != 0) {
            $query->where('producto.idcategoria', $categoriaFiltro);
        }

        if (!empty($busqueda)) {
            $query->where(function ($q) use ($busqueda) {
                $q->where('producto.nombre', 'LIKE', "%{$busqueda}%")
                  ->orWhere('producto.descripcion', 'LIKE', "%{$busqueda}%");
            });
        }

        
        switch ($orden) {
            case 'precio_asc':
                $query->orderBy('producto.precio', 'asc');
                break;
            case 'precio_desc':
                $query->orderBy('producto.precio', 'desc');
                break;
            case 'nombre_asc':
                $query->orderBy('producto.nombre', 'asc');
                break;
            case 'nombre_desc':
                $query->orderBy('producto.nombre', 'desc');
                break;
            case 'nuevos':
                $query->orderBy('producto.fecharegistro', 'desc');
                break;
            default:
                $query->inRandomOrder();
                break;
        }

        $totalProducts = $query->count();
        $productos = $query->offset(($currentPage - 1) * $this->pageSize)
            ->limit($this->pageSize)
            ->get();

        
        $sugeridos = $this->getProductosSugeridos($marcaFiltro, $categoriaFiltro);

        
        $totalPages = ceil($totalProducts / $this->pageSize);

        
        $user = Auth::user();
        $cliente = null;
        $cartCount = 0;
        
        if ($user) {
            $cliente = Cliente::where('idusuario', $user->idusuario)->first();
            if ($cliente) {
                $cartCount = Carrito::where('idcliente', $cliente->idcliente)->sum('cantidad');
            }
        }

        return view('home.index', compact(
            'productos', 'sugeridos', 'marcas', 'categorias', 'totalProducts', 
            'currentPage', 'totalPages', 'orden', 'marcaFiltro', 'categoriaFiltro',
            'busqueda', 'cliente', 'user', 'cartCount'
        ))->with('pageSize', $this->pageSize); 
    }

    private function getProductosSugeridos($marcaFiltro = 0, $categoriaFiltro = 0)
    {
        $query = Producto::with(['marca', 'categoria'])
            ->where('producto.estado', 1)
            ->where('producto.stock', '>', 0);


        if ($marcaFiltro != 0) {
            $query->where('producto.idmarca', $marcaFiltro);
        }

        if ($categoriaFiltro != 0) {
            $query->where('producto.idcategoria', $categoriaFiltro);
        }

        return $query->inRandomOrder()
            ->limit(4)
            ->get();
    }

    public function agregarAlCarrito(Request $request, $idProducto)
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $user = Auth::user();
        $cliente = Cliente::where('idusuario', $user->idusuario)->first();

        if (!$cliente) {
            return back()->with('error', 'Cliente no encontrado.');
        }

        
        $producto = Producto::find($idProducto);
        if (!$producto || $producto->stock <= 0) {
            return back()->with('error', 'Producto sin stock.');
        }

        
        $carrito = Carrito::where('idcliente', $cliente->idcliente)
            ->where('idproducto', $idProducto)
            ->first();

        if ($carrito) {
            $carrito->cantidad += 1;
            $carrito->save();
        } else {
            Carrito::create([
                'idcliente' => $cliente->idcliente,
                'idproducto' => $idProducto,
                'cantidad' => 1
            ]);
        }

        return back()->with('success', 'Producto agregado al carrito.');
    }

    public function actualizarConfiguracion(Request $request)
    {
        $user = Auth::user();
        
        $request->validate([
            'telefono' => 'nullable|string|max:20',
            'clave' => 'nullable|string|min:6'
        ]);

        if ($request->filled('telefono')) {
            $user->telefono = $request->telefono;
        }

        if ($request->filled('clave')) {
            $user->clave = bcrypt($request->clave);
        }

        $user->save();

        return back()->with('success', 'Configuración actualizada correctamente.');
    }

    public function limpiarFiltros()
    {
        return redirect()->route('index');
    }

    public function getPageSize()
    {
        return $this->pageSize;
    }
}
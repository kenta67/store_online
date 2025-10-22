<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\Carrito;
use App\Models\Producto;
use App\Models\Cliente;
use App\Models\Direccion;
use App\Models\Compra;
use App\Models\DetalleCompra;
use App\Models\MetodoPago;

use PayPalCheckoutSdk\Core\PayPalHttpClient;
use PayPalCheckoutSdk\Core\SandboxEnvironment;
use PayPalCheckoutSdk\Core\ProductionEnvironment;
use PayPalCheckoutSdk\Orders\OrdersCreateRequest;
use PayPalCheckoutSdk\Orders\OrdersCaptureRequest;

class CarritoController extends Controller
{
    private $client;

    public function __construct()
    {
        $this->client = $this->getPayPalClient();
    }

    private function getPayPalClient()
    {
        $clientId = config('services.paypal.client_id');
        $clientSecret = config('services.paypal.secret');
        
        if (config('services.paypal.settings.mode') === 'production') {
            $environment = new ProductionEnvironment($clientId, $clientSecret);
        } else {
            $environment = new SandboxEnvironment($clientId, $clientSecret);
        }
        
        return new PayPalHttpClient($environment);
    }

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

        $carrito = Carrito::with('producto.marca')
            ->where('idcliente', $cliente->idcliente)
            ->get();

        if (!$carrito instanceof \Illuminate\Database\Eloquent\Collection) {
            $carrito = collect(); 
        }

        $subtotal = 0;
        foreach ($carrito as $item) {
            $subtotal += $item->producto->precio * $item->cantidad;
        }

        $impuesto = $subtotal * 0.18;
        $total = $subtotal + $impuesto;

        $direcciones = Direccion::where('idcliente', $cliente->idcliente)->get();

        return view('carrito.index', compact('carrito', 'subtotal', 'impuesto', 'total', 'direcciones', 'cliente'));
    }

    public function agregar(Request $request, $idProducto)
    {
        if (!Auth::check()) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Debes iniciar sesión para agregar productos al carrito',
                    'redirect' => route('login')
                ], 401);
            }
            return redirect()->route('login');
        }

        $user = Auth::user();
        $cliente = Cliente::where('idusuario', $user->idusuario)->first();

        if (!$cliente) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Cliente no encontrado'
                ], 404);
            }
            return back()->with('error', 'Cliente no encontrado.');
        }

        $producto = Producto::find($idProducto);
        if (!$producto) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Producto no encontrado'
                ], 404);
            }
            return back()->with('error', 'Producto no encontrado.');
        }

        $cantidad = $request->input('cantidad', 1);

        if ($producto->stock < $cantidad) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'No hay suficiente stock disponible. Stock actual: ' . $producto->stock
                ], 400);
            }
            return back()->with('error', 'No hay suficiente stock disponible.');
        }

        $carrito = Carrito::where('idcliente', $cliente->idcliente)
            ->where('idproducto', $idProducto)
            ->first();

        if ($carrito) {
            $carrito->cantidad += $cantidad;
            $carrito->save();
        } else {
            Carrito::create([
                'idcliente' => $cliente->idcliente,
                'idproducto' => $idProducto,
                'cantidad' => $cantidad
            ]);
        }

        $cartCount = Carrito::where('idcliente', $cliente->idcliente)->sum('cantidad');

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Producto agregado al carrito',
                'cartCount' => $cartCount
            ]);
        }

        return back()->with('success', 'Producto agregado al carrito');
    }

    public function actualizar(Request $request, $idCarrito)
    {
        $request->validate([
            'cantidad' => 'required|integer|min:1'
        ]);

        $carrito = Carrito::with('producto')->findOrFail($idCarrito);
        
        if ($carrito->producto->stock < $request->cantidad) {
            return back()->with('error', 'No hay suficiente stock disponible.');
        }

        $carrito->cantidad = $request->cantidad;
        $carrito->save();

        return back()->with('success', 'Carrito actualizado correctamente.');
    }

    public function eliminar($idCarrito)
    {
        $carrito = Carrito::findOrFail($idCarrito);
        $carrito->delete();

        return back()->with('success', 'Producto eliminado del carrito.');
    }

    public function vaciar()
    {
        $user = Auth::user();
        $cliente = Cliente::where('idusuario', $user->idusuario)->first();

        Carrito::where('idcliente', $cliente->idcliente)->delete();

        return back()->with('success', 'Carrito vaciado correctamente.');
    }

    public function guardarDireccion(Request $request)
    {
        $request->validate([
            'telefono' => 'required|string|max:20',
            'direccion' => 'required|string|max:200',
            'detallelugar' => 'required|string|max:200'
        ]);

        $user = Auth::user();
        $cliente = Cliente::where('idusuario', $user->idusuario)->first();

        $direccion = Direccion::create([
            'idcliente' => $cliente->idcliente,
            'telefono' => $request->telefono,
            'direccion' => $request->direccion,
            'detallelugar' => $request->detallelugar
        ]);

        return response()->json([
            'success' => 'Dirección guardada correctamente',
            'direccion' => $direccion
        ]);
    }

    public function crearPagoPayPal(Request $request)
    {
        try {
            $request->validate([
                'iddireccion' => 'required|exists:direccion,iddireccion'
            ]);

            $user = Auth::user();
            $cliente = Cliente::where('idusuario', $user->idusuario)->first();

            if (!$cliente) {
                return back()->with('error', 'Cliente no encontrado.');
            }

            $carrito = Carrito::with('producto')
                ->where('idcliente', $cliente->idcliente)
                ->get();

            if ($carrito->isEmpty()) {
                return back()->with('error', 'El carrito está vacío.');
            }

            foreach ($carrito as $item) {
                if ($item->producto->stock < $item->cantidad) {
                    return back()->with('error', 
                        "El producto '{$item->producto->nombre}' no tiene suficiente stock. Stock disponible: {$item->producto->stock}");
                }
            }

            $subtotal = 0;
            $totalProductos = 0;
            foreach ($carrito as $item) {
                $subtotal += $item->producto->precio * $item->cantidad;
                $totalProductos += $item->cantidad;
            }
            
            $impuesto = $subtotal * 0.18;
            $total = $subtotal + $impuesto;

            if ($total <= 0) {
                return back()->with('error', 'El total del carrito no es válido.');
            }

            // Obtener la dirección de envío
            $direccionEnvio = Direccion::find($request->iddireccion);
            if (!$direccionEnvio) {
                return back()->with('error', 'Dirección de envío no encontrada.');
            }

            // SOLUCIÓN: Cambiar shipping_preference a NO_SHIPPING para evitar problemas de dirección
            $requestPayPal = new OrdersCreateRequest();
            $requestPayPal->prefer('return=representation');
            $requestPayPal->body = [
                "intent" => "CAPTURE",
                "purchase_units" => [
                    [
                        "reference_id" => "tienda_store_" . uniqid(),
                        "description" => "Compra en Tienda Store",
                        "custom_id" => $cliente->idcliente,
                        "invoice_id" => uniqid(),
                        "amount" => [
                            "currency_code" => "USD",
                            "value" => number_format($total, 2, '.', ''),
                            "breakdown" => [
                                "item_total" => [
                                    "currency_code" => "USD",
                                    "value" => number_format($subtotal, 2, '.', '')
                                ],
                                "tax_total" => [
                                    "currency_code" => "USD",
                                    "value" => number_format($impuesto, 2, '.', '')
                                ]
                            ]
                        ],
                        "items" => $this->getPayPalItems($carrito)
                    ]
                ],
                "application_context" => [
                    "brand_name" => "Tienda Store",
                    "landing_page" => "BILLING",
                    "shipping_preference" => "NO_SHIPPING", // CAMBIO IMPORTANTE
                    "user_action" => "PAY_NOW",
                    "return_url" => route('carrito.paypal.exito'),
                    "cancel_url" => route('carrito.paypal.cancelado')
                ]
            ];

            \Log::info('Creando orden PayPal', [
                'total' => $total,
                'cliente_id' => $cliente->idcliente,
                'items_count' => $carrito->count()
            ]);

            $response = $this->client->execute($requestPayPal);

            \Log::info('Orden PayPal creada', [
                'order_id' => $response->result->id,
                'status' => $response->result->status
            ]);

            session([
                'paypal_order_id' => $response->result->id,
                'iddireccion' => $request->iddireccion,
                'idcliente' => $cliente->idcliente,
                'carrito_total' => $total,
                'carrito_items' => $carrito->toArray()
            ]);

            $approvalLink = '';
            foreach ($response->result->links as $link) {
                if ($link->rel === 'approve') {
                    $approvalLink = $link->href;
                    break;
                }
            }

            if (empty($approvalLink)) {
                throw new \Exception('No se pudo obtener el link de aprobación de PayPal');
            }

            return redirect($approvalLink);

        } catch (\Exception $ex) {
            \Log::error('Error al crear orden PayPal: ' . $ex->getMessage(), [
                'file' => $ex->getFile(),
                'line' => $ex->getLine(),
                'trace' => $ex->getTraceAsString()
            ]);

            // Mensaje más amigable para el usuario
            $errorMessage = 'Error al procesar el pago con PayPal. ';
            $errorMessage .= 'Por favor verifica tu conexión o intenta más tarde.';

            return back()->with('error', $errorMessage);
        }
    }

    private function getPayPalItems($carrito)
    {
        $items = [];
        
        foreach ($carrito as $item) {
            $items[] = [
                "name" => substr($item->producto->nombre, 0, 127),
                "description" => substr($item->producto->descripcion ?: 'Producto de Tienda Store', 0, 127),
                "sku" => "PROD_" . $item->idproducto,
                "unit_amount" => [
                    "currency_code" => "USD",
                    "value" => number_format($item->producto->precio, 2, '.', '')
                ],
                "quantity" => $item->cantidad,
                "category" => "PHYSICAL_GOODS"
            ];
        }
        
        return $items;
    }

    public function pagoExitoso(Request $request)
    {
        try {
            $orderId = $request->input('token') ?: session('paypal_order_id');

            if (!$orderId) {
                return redirect()->route('carrito')->with('error', 'No se encontró la información de la orden.');
            }

            \Log::info('Capturando orden PayPal', ['order_id' => $orderId]);

            $requestPayPal = new OrdersCaptureRequest($orderId);
            $response = $this->client->execute($requestPayPal);

            \Log::info('Orden PayPal capturada', [
                'order_id' => $response->result->id,
                'status' => $response->result->status
            ]);

            if ($response->result->status === 'COMPLETED') {
                $compra = $this->crearCompra($response->result->id);
                
                if ($compra) {
                    return redirect()->route('carrito.completado')->with('success', '¡Pago realizado con éxito!');
                } else {
                    return redirect()->route('carrito')->with('error', 'Error al procesar la compra.');
                }
            } else {
                \Log::warning('Orden PayPal no completada', [
                    'order_id' => $orderId,
                    'status' => $response->result->status
                ]);
                return redirect()->route('carrito')->with('error', 'El pago no se completó. Estado: ' . $response->result->status);
            }

        } catch (\Exception $ex) {
            \Log::error('Error al capturar orden PayPal: ' . $ex->getMessage(), [
                'file' => $ex->getFile(),
                'line' => $ex->getLine()
            ]);

            return redirect()->route('carrito')->with('error', 'Error al procesar el pago: ' . $ex->getMessage());
        }
    }

    public function pagoCancelado()
    {
        session()->forget(['paypal_order_id', 'iddireccion', 'idcliente', 'carrito_total', 'carrito_items']);
        
        return redirect()->route('carrito')->with('error', 'Pago cancelado por el usuario.');
    }

    private function crearCompra($orderId)
    {
        DB::beginTransaction();
        
        try {
            $user = Auth::user();
            $cliente = Cliente::where('idusuario', $user->idusuario)->first();

            // Usar los datos de la sesión en caso de que el carrito ya esté vacío
            $carritoItems = session('carrito_items', []);
            
            if (empty($carritoItems)) {
                $carrito = Carrito::with('producto')
                    ->where('idcliente', $cliente->idcliente)
                    ->get();
                $carritoItems = $carrito->toArray();
            }

            if (empty($carritoItems)) {
                \Log::error('Carrito vacío al crear compra');
                DB::rollBack();
                return false;
            }

            $subtotal = 0;
            $totalProductos = 0;
            foreach ($carritoItems as $item) {
                if (is_array($item)) {
                    $precio = $item['producto']['precio'];
                    $cantidad = $item['cantidad'];
                } else {
                    $precio = $item->producto->precio;
                    $cantidad = $item->cantidad;
                }
                $subtotal += $precio * $cantidad;
                $totalProductos += $cantidad;
            }
            
            $impuesto = $subtotal * 0.18;
            $total = $subtotal + $impuesto;

            $metodoPago = MetodoPago::create([
                'idcliente' => $cliente->idcliente,
                'tipopago' => 'PayPal',
                'idtransaccion' => $orderId
            ]);

            $compra = Compra::create([
                'idcliente' => $cliente->idcliente,
                'iddireccion' => session('iddireccion'),
                'idmetodo' => $metodoPago->idmetodo,
                'totalproducto' => $totalProductos,
                'montototal' => $total,
                'enviado' => 0,
                'fecharegistro' => now()
            ]);

            foreach ($carritoItems as $item) {
                if (is_array($item)) {
                    $idproducto = $item['idproducto'];
                    $cantidad = $item['cantidad'];
                    $precio = $item['producto']['precio'];
                } else {
                    $idproducto = $item->idproducto;
                    $cantidad = $item->cantidad;
                    $precio = $item->producto->precio;
                }

                DetalleCompra::create([
                    'idcompra' => $compra->idcompra,
                    'idproducto' => $idproducto,
                    'cantidad' => $cantidad,
                    'total' => $precio * $cantidad,
                    'fecharegistro' => now()
                ]);

                $producto = Producto::find($idproducto);
                if ($producto) {
                    $producto->stock -= $cantidad;
                    $producto->save();
                }
            }

            Carrito::where('idcliente', $cliente->idcliente)->delete();

            session()->forget(['paypal_order_id', 'iddireccion', 'idcliente', 'carrito_total', 'carrito_items']);

            DB::commit();

            \Log::info('Compra creada exitosamente', [
                'compra_id' => $compra->idcompra,
                'order_id' => $orderId
            ]);

            return $compra;

        } catch (\Exception $ex) {
            DB::rollBack();
            \Log::error('Error al crear compra: ' . $ex->getMessage());
            return false;
        }
    }

    public function completado()
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        return view('carrito.completado');
    }
}
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <meta name="description" content="" />
    <meta name="author" content="" />
    <title>Mi Actividad - Tienda Store</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.5.0/font/bootstrap-icons.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css"> 
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <style>
        .stats-card {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border-radius: 15px;
            padding: 20px;
            margin-bottom: 20px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
        }
        .activity-card {
            border: none;
            border-radius: 15px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            margin-bottom: 20px;
        }
        .nav-pills .nav-link.active {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border: none;
        }
        .badge-status {
            font-size: 0.8rem;
            padding: 6px 12px;
            border-radius: 20px;
        }
    </style>
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <div class="container px-4 px-lg-5">
            <a class="navbar-brand" href="{{ route('index') }}">Tienda Store</a>
            <div class="collapse navbar-collapse">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0 ms-lg-4">
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('index') }}">Inicio</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" href="{{ route('actividad') }}">Mi Actividad</a>
                    </li>
                </ul>
                <a href="{{ route('carrito') }}" class="btn btn-outline-dark me-4">
                    <i class="bi bi-cart-fill me-1"></i>
                    Carrito
                </a>
                @auth
                    <ul class="navbar-nav">
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle d-flex align-items-center" href="#" role="button" data-bs-toggle="dropdown">
                                <i class="fas fa-user me-2"></i>
                                <span class="d-none d-lg-inline">{{ $user->nombre }}</span>
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end">
                                <li><a class="dropdown-item" href="{{ route('actividad') }}"><i class="fas fa-chart-line me-2"></i>Actividad</a></li>
                                <li><a class="dropdown-item" href="#" onclick="mostrarModalConfiguracion()"><i class="fas fa-cog me-2"></i>Configuración</a></li>
                                <li>
                                    <a class="dropdown-item" href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                        <i class="fas fa-sign-out-alt me-2"></i>Cerrar sesión
                                    </a>
                                </li>
                            </ul>
                        </li>
                    </ul>
                @endauth
            </div>
        </div>
    </nav>

    <div class="container mt-4">
        <div class="row">
            <div class="col-12">
                <h1 class="mb-4">Mi Actividad</h1>
                
                <!-- Estadísticas -->
                <div class="row mb-4">
                    <div class="col-md-3">
                        <div class="stats-card">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="mb-0">Total Compras</h6>
                                    <h3 class="mb-0">{{ $estadisticas['total_compras'] }}</h3>
                                </div>
                                <i class="fas fa-shopping-bag fa-2x"></i>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="stats-card" style="background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="mb-0">Total Gastado</h6>
                                    <h3 class="mb-0">${{ number_format($estadisticas['total_gastado'], 2) }}</h3>
                                </div>
                                <i class="fas fa-dollar-sign fa-2x"></i>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="stats-card" style="background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="mb-0">Productos Comprados</h6>
                                    <h3 class="mb-0">{{ $estadisticas['productos_comprados'] }}</h3>
                                </div>
                                <i class="fas fa-box fa-2x"></i>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="stats-card" style="background: linear-gradient(135deg, #43e97b 0%, #38f9d7 100%);">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="mb-0">En Carrito</h6>
                                    <h3 class="mb-0">{{ $estadisticas['productos_carrito'] }}</h3>
                                </div>
                                <i class="fas fa-shopping-cart fa-2x"></i>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Navegación -->
                <ul class="nav nav-pills mb-4" id="myTab" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active" id="compras-tab" data-bs-toggle="tab" data-bs-target="#compras" type="button" role="tab">
                            <i class="fas fa-shopping-bag me-2"></i>Mis Compras
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="carrito-tab" data-bs-toggle="tab" data-bs-target="#carrito" type="button" role="tab">
                            <i class="fas fa-shopping-cart me-2"></i>Mi Carrito
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="frecuentes-tab" data-bs-toggle="tab" data-bs-target="#frecuentes" type="button" role="tab">
                            <i class="fas fa-star me-2"></i>Productos Frecuentes
                        </button>
                    </li>
                </ul>

                <!-- Contenido de las pestañas -->
                <div class="tab-content" id="myTabContent">
                    <!-- Pestaña de Compras -->
                    <div class="tab-pane fade show active" id="compras" role="tabpanel">
                        @if($compras->count() > 0)
                            @foreach($compras as $compra)
                                <div class="card activity-card">
                                    <div class="card-body">
                                        <div class="row align-items-center">
                                            <div class="col-md-8">
                                                <h5 class="card-title">Compra #{{ $compra->idcompra }}</h5>
                                                <p class="card-text mb-1">
                                                    <strong>Fecha:</strong> {{ $compra->fecharegistro->format('d/m/Y H:i') }}
                                                </p>
                                                <p class="card-text mb-1">
                                                    <strong>Productos:</strong> {{ $compra->totalproducto }}
                                                </p>
                                                <p class="card-text mb-0">
                                                    <strong>Total:</strong> ${{ number_format($compra->montototal, 2) }}
                                                </p>
                                            </div>
                                            <div class="col-md-4 text-end">
                                                <span class="badge {{ $compra->enviado ? 'bg-success' : 'bg-warning' }} badge-status">
                                                    {{ $compra->enviado ? 'Enviado' : 'Pendiente' }}
                                                </span>
                                                <br>
                                                <a href="{{ route('actividad.compra.detalle', $compra->idcompra) }}" class="btn btn-outline-primary mt-2">
                                                    Ver Detalles
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        @else
                            <div class="text-center py-5">
                                <i class="fas fa-shopping-bag fa-3x text-muted mb-3"></i>
                                <h4 class="text-muted">No tienes compras realizadas</h4>
                                <p class="text-muted">¡Comienza a comprar en nuestra tienda!</p>
                                <a href="{{ route('index') }}" class="btn btn-primary">Ir a Comprar</a>
                            </div>
                        @endif
                    </div>

                    <!-- Pestaña de Carrito -->
                    <div class="tab-pane fade" id="carrito" role="tabpanel">
                        @if($carrito->count() > 0)
                            @foreach($carrito as $item)
                                <div class="card activity-card">
                                    <div class="card-body">
                                        <div class="row align-items-center">
                                            <div class="col-md-2">
                                                <img src="{{ $item->producto->imagen_url }}" alt="{{ $item->producto->nombre }}" class="img-fluid rounded" style="max-height: 80px;">
                                            </div>
                                            <div class="col-md-6">
                                                <h6 class="card-title">{{ $item->producto->nombre }}</h6>
                                                <p class="card-text mb-1 text-muted">{{ $item->producto->marca->descripcion }}</p>
                                                <p class="card-text mb-0">
                                                    <strong>Precio:</strong> ${{ number_format($item->producto->precio, 2) }}
                                                </p>
                                            </div>
                                            <div class="col-md-2 text-center">
                                                <span class="badge bg-primary">Cantidad: {{ $item->cantidad }}</span>
                                            </div>
                                            <div class="col-md-2 text-end">
                                                <strong>Subtotal: ${{ number_format($item->producto->precio * $item->cantidad, 2) }}</strong>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                            <div class="text-end mt-3">
                                <a href="{{ route('carrito') }}" class="btn btn-primary">Ir al Carrito</a>
                            </div>
                        @else
                            <div class="text-center py-5">
                                <i class="fas fa-shopping-cart fa-3x text-muted mb-3"></i>
                                <h4 class="text-muted">Tu carrito está vacío</h4>
                                <p class="text-muted">¡Agrega algunos productos!</p>
                                <a href="{{ route('index') }}" class="btn btn-primary">Ir a Comprar</a>
                            </div>
                        @endif
                    </div>

                    <!-- Pestaña de Productos Frecuentes -->
                    <div class="tab-pane fade" id="frecuentes" role="tabpanel">
                        @if($productosFrecuentes->count() > 0)
                            @foreach($productosFrecuentes as $producto)
                                <div class="card activity-card">
                                    <div class="card-body">
                                        <div class="row align-items-center">
                                            <div class="col-md-8">
                                                <h6 class="card-title">{{ $producto->nombre }}</h6>
                                                <p class="card-text mb-1 text-muted">{{ $producto->marca }}</p>
                                                <p class="card-text mb-0">
                                                    <strong>Comprado:</strong> {{ $producto->total_comprado }} veces
                                                </p>
                                            </div>
                                            <div class="col-md-4 text-end">
                                                <span class="badge bg-success">Total: ${{ number_format($producto->total_gastado, 2) }}</span>
                                                <br>
                                                <a href="{{ route('producto.detalle', $producto->idproducto) }}" class="btn btn-outline-primary mt-2">
                                                    Ver Producto
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        @else
                            <div class="text-center py-5">
                                <i class="fas fa-star fa-3x text-muted mb-3"></i>
                                <h4 class="text-muted">No tienes productos frecuentes</h4>
                                <p class="text-muted">¡Realiza algunas compras para ver tus productos favoritos!</p>
                                <a href="{{ route('index') }}" class="btn btn-primary">Ir a Comprar</a>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Inicializar tabs
        var triggerTabList = [].slice.call(document.querySelectorAll('#myTab button'))
        triggerTabList.forEach(function (triggerEl) {
            var tabTrigger = new bootstrap.Tab(triggerEl)
            triggerEl.addEventListener('click', function (event) {
                event.preventDefault()
                tabTrigger.show()
            })
        });
    </script>
</body>
</html>
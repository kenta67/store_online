<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <meta name="description" content="" />
    <meta name="author" content="" />
    <title>Tienda Online - Productos</title>
    
    <link rel="icon" type="image/x-icon" href="assets/favicon.ico" />
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.5.0/font/bootstrap-icons.css" rel="stylesheet" />
    
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css"> 

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <style>
        .nav-link.dropdown-toggle {
            padding: 0.5rem 1rem !important;
        }

        .fa-user {
            font-size: 1.25rem;
        }
        body {
            background-image: url('/fondo/143788648_ede90800-3eb2-48d2-96f2-8cf04effd44c.jpg');
            background-size: cover;        
            background-position: center;   
            background-repeat: no-repeat; 
        }
        .carousel-item {
            height: 500px;
        }
        .carousel-item img {
            object-fit: cover;
            height: 100%;
            width: 100%;
            filter: brightness(0.8); 
        }
        .filter-section {
            background: rgba(255, 255, 255, 0.95);
            padding: 25px;
            border-radius: 15px;
            margin-bottom: 30px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
        }
        .filter-card {
            background: white;
            border-radius: 10px;
            padding: 20px;
            margin-bottom: 20px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
        }
        .filter-title {
            font-weight: 600;
            color: #333;
            margin-bottom: 15px;
            border-bottom: 2px solid #007bff;
            padding-bottom: 8px;
        }
        .bg-plomo {
            background-color: #6c757d !important; 
        }
        .pagination .page-link {
            background-color: white !important;
            color: black !important;
            border: 1px solid #000 !important;
            margin: 0 2px;
            border-radius: 0 !important;
        }

        .pagination .page-link:hover {
            background-color: #f8f9fa !important;
            color: black !important;
            border-color: #000 !important;
        }

        .pagination .page-item.active .page-link {
            background-color: black !important;
            color: white !important;
            border-color: black !important;
        }

        .pagination .page-item.disabled .page-link {
            background-color: #e9ecef !important;
            color: #6c757d !important;
            border-color: #dee2e6 !important;
        }

        .product-card {
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            border: none;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        .product-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 5px 20px rgba(0,0,0,0.15);
        }
        .badge-category {
            background: linear-gradient(45deg, #007bff, #0056b3);
            color: white;
            font-size: 0.75rem;
        }
        .badge-marca {
            background: linear-gradient(45deg, #28a745, #1e7e34);
            color: white;
            font-size: 0.75rem;
        }
        .stats-card {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border-radius: 10px;
            padding: 15px;
            margin-bottom: 15px;
        }
        .filter-badge {
            background: #007bff;
            color: white;
            padding: 5px 10px;
            border-radius: 20px;
            font-size: 0.8rem;
            margin: 2px;
        }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <div class="container px-4 px-lg-5">
            <a class="navbar-brand" href="#!">Tienda Store</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation"><span class="navbar-toggler-icon"></span></button>
            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0 ms-lg-4">
                    <li class="nav-item">
                        <a class="nav-link active" href="{{ route('index') }}">Inicio</a>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            Categorías
                        </a>
                        <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
                            @foreach($categorias as $categoria)
                                <li>
                                    <a class="dropdown-item" href="{{ route('index', ['categoria' => $categoria->idcategoria]) }}">
                                        {{ $categoria->descripcion }}
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                    </li>
                </ul>
                
                <a href="{{ route('carrito') }}" class="btn btn-outline-dark me-4">
                    <i class="bi bi-cart-fill me-1"></i>
                    Carrito
                    <span class="badge bg-dark text-white ms-1 rounded-pill">{{ $cartCount }}</span>
                </a>

                @guest
                    <a href="{{ route('login') }}" class="btn btn-outline-dark d-flex align-items-center gap-2">
                        <i class="bi bi-box-arrow-in-right"></i> Iniciar Sesión
                    </a>
                @else
                    <ul class="navbar-nav">
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle d-flex align-items-center" 
                                id="navbarDropdownUser" 
                                href="#" 
                                role="button" 
                                data-bs-toggle="dropdown" 
                                aria-expanded="false">
                                <i class="fas fa-user me-2"></i>
                                <span class="d-none d-lg-inline">{{ $user->nombre }}</span>
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdownUser">
                                <li><a class="dropdown-item" href="{{ route('actividad') }}"><i class="fas fa-chart-line me-2"></i>Actividad</a></li>
                                <li>
                                    <a class="dropdown-item" href="#" onclick="mostrarModalConfiguracion()">
                                        <i class="fas fa-cog me-2"></i>Configuración
                                    </a>
                                </li>
                                <li>
                                    <a class="dropdown-item" href="{{ route('logout') }}"
                                       onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                                        <i class="fas fa-sign-out-alt me-2"></i>Cerrar sesión
                                    </a>
                                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                        @csrf
                                    </form>
                                </li>
                            </ul>
                        </li>
                    </ul>
                @endguest
            </div>
        </div>
    </nav>
    
    <!-- Carrusel -->
    <div id="productCarousel" class="carousel slide" data-bs-ride="carousel">
        <div class="carousel-indicators">
            <button type="button" data-bs-target="#productCarousel" data-bs-slide-to="0" class="active"></button>
            <button type="button" data-bs-target="#productCarousel" data-bs-slide-to="1"></button>
            <button type="button" data-bs-target="#productCarousel" data-bs-slide-to="2"></button>
        </div>
        <div class="carousel-inner">
            <div class="carousel-item active">
                <img src="https://images.unsplash.com/photo-1511707171634-5f897ff02aa9?ixlib=rb-4.0.3&auto=format&fit=crop&w=1500&q=60&brightness=0.7" class="d-block w-100" alt="Smartphones oscuros">
                <div class="carousel-caption d-none d-md-block">
                    <h5>Los mejores smartphones</h5>
                    <p>Encuentra el dispositivo perfecto para ti</p>
                </div>
            </div>
            <div class="carousel-item">
                <img src="https://images.unsplash.com/photo-1565849904461-04a58ad377e0?ixlib=rb-4.0.3&auto=format&fit=crop&w=1500&q=60&brightness=0.7" class="d-block w-100" alt="Teléfonos modernos">
                <div class="carousel-caption d-none d-md-block">
                    <h5>Tecnología avanzada</h5>
                    <p>Descubre la última generación de smartphones</p>
                </div>
            </div>
            <div class="carousel-item">
                <img src="https://images.unsplash.com/photo-1598327105666-5b89351aff97?ixlib=rb-4.0.3&auto=format&fit=crop&w=1500&q=60&brightness=0.7" class="d-block w-100" alt="Teléfonos premium">
                <div class="carousel-caption d-none d-md-block">
                    <h5>Diseño premium</h5>
                    <p>Elegancia y funcionalidad en cada dispositivo</p>
                </div>
            </div>
        </div>
        <button class="carousel-control-prev" type="button" data-bs-target="#productCarousel" data-bs-slide="prev">
            <span class="carousel-control-prev-icon"></span>
        </button>
        <button class="carousel-control-next" type="button" data-bs-target="#productCarousel" data-bs-slide="next">
            <span class="carousel-control-next-icon"></span>
        </button>
    </div>

    <!-- Sección de Filtros Mejorada -->
    <section class="py-5">
        <div class="container px-4 px-lg-5">
            <div class="filter-section">
                <div class="row">
                    <div class="col-md-8">
                        <h4 class="filter-title">Filtrar Productos</h4>
                        <form method="GET" action="{{ route('index') }}">
                            <div class="row g-3 align-items-center">
                                <div class="col-md-4">
                                    <label class="form-label fw-bold">Ordenar por:</label>
                                    <select name="orden" class="form-select" onchange="this.form.submit()">
                                        <option value="rand" {{ request('orden') == 'rand' ? 'selected' : '' }}>Recomendados</option>
                                        <option value="precio_asc" {{ request('orden') == 'precio_asc' ? 'selected' : '' }}>Precio: Más barato primero</option>
                                        <option value="precio_desc" {{ request('orden') == 'precio_desc' ? 'selected' : '' }}>Precio: Más caro primero</option>
                                        <option value="nombre_asc" {{ request('orden') == 'nombre_asc' ? 'selected' : '' }}>Nombre: A-Z</option>
                                        <option value="nombre_desc" {{ request('orden') == 'nombre_desc' ? 'selected' : '' }}>Nombre: Z-A</option>
                                        <option value="nuevos" {{ request('orden') == 'nuevos' ? 'selected' : '' }}>Más nuevos primero</option>
                                    </select>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label fw-bold">Filtrar por marca:</label>
                                    <select name="marca" class="form-select" onchange="this.form.submit()">
                                        <option value="0">Todas las marcas</option>
                                        @foreach($marcas as $marca)
                                            <option value="{{ $marca->idmarca }}" {{ request('marca') == $marca->idmarca ? 'selected' : '' }}>
                                                {{ $marca->descripcion }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>                 
                                <div class="col-md-4">
                                    <label class="form-label fw-bold">Filtrar por categoría:</label>
                                    <select name="categoria" class="form-select" onchange="this.form.submit()">
                                        <option value="0">Todas las categorías</option>
                                        @foreach($categorias as $categoria)
                                            <option value="{{ $categoria->idcategoria }}" {{ request('categoria') == $categoria->idcategoria ? 'selected' : '' }}>
                                                {{ $categoria->descripcion }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-8">
                                    <label class="form-label fw-bold">Buscar producto:</label>
                                    <div class="input-group">
                                        <input type="text" name="busqueda" class="form-control" placeholder="Nombre del producto..." value="{{ request('busqueda') }}">
                                        <button type="submit" class="btn btn-primary">Buscar</button>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label fw-bold">&nbsp;</label>
                                    <div class="d-grid">
                                        <a href="{{ route('index') }}" class="btn btn-outline-secondary">Limpiar filtros</a>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="col-md-4">
                        <h4 class="filter-title">Estadísticas</h4>
                        <div class="stats-card">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="mb-0">Total Productos</h6>
                                    <h4 class="mb-0">{{ $totalProducts }}</h4>
                                </div>
                                <i class="fas fa-box fa-2x"></i>
                            </div>
                        </div>
                        <div class="stats-card" style="background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="mb-0">Marcas</h6>
                                    <h4 class="mb-0">{{ $marcas->count() }}</h4>
                                </div>
                                <i class="fas fa-tags fa-2x"></i>
                            </div>
                        </div>
                        <div class="stats-card" style="background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="mb-0">Categorías</h6>
                                    <h4 class="mb-0">{{ $categorias->count() }}</h4>
                                </div>
                                <i class="fas fa-layer-group fa-2x"></i>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Filtros activos -->
                @if(request('marca') || request('categoria') || request('busqueda'))
                <div class="row mt-3">
                    <div class="col-12">
                        <h6 class="filter-title">Filtros aplicados:</h6>
                        <div class="d-flex flex-wrap">
                            @if(request('marca') && request('marca') != 0)
                                @php
                                    $marcaActiva = $marcas->where('idmarca', request('marca'))->first();
                                @endphp
                                @if($marcaActiva)
                                    <span class="filter-badge">
                                        Marca: {{ $marcaActiva->descripcion }}
                                        <a href="{{ request()->fullUrlWithQuery(['marca' => 0]) }}" class="text-white ms-2">×</a>
                                    </span>
                                @endif
                            @endif
                            @if(request('categoria') && request('categoria') != 0)
                                @php
                                    $categoriaActiva = $categorias->where('idcategoria', request('categoria'))->first();
                                @endphp
                                @if($categoriaActiva)
                                    <span class="filter-badge">
                                        Categoría: {{ $categoriaActiva->descripcion }}
                                        <a href="{{ request()->fullUrlWithQuery(['categoria' => 0]) }}" class="text-white ms-2">×</a>
                                    </span>
                                @endif
                            @endif
                            @if(request('busqueda'))
                                <span class="filter-badge">
                                    Búsqueda: "{{ request('busqueda') }}"
                                    <a href="{{ request()->fullUrlWithQuery(['busqueda' => '']) }}" class="text-white ms-2">×</a>
                                </span>
                            @endif
                        </div>
                    </div>
                </div>
                @endif
            </div>
        </div>
    </section>

    <!-- Productos Destacados -->
    <section class="pb-4">
        <div class="container px-4 px-lg-5 mt-0">
            <div class="d-flex justify-content-between align-items-center mb-5">
                <h1 class="text-center mb-0">Productos Destacados</h1>
                <div class="text-muted">
                    Mostrando {{ ($currentPage - 1) * $pageSize + 1 }}-{{ min($currentPage * $pageSize, $totalProducts) }} de {{ $totalProducts }} productos
                </div>
            </div>
            
            @if($productos->isNotEmpty())
            <div class="row mt-4">
                <div class="col-12">
                    <nav aria-label="Page navigation">
                        <ul class="pagination justify-content-center">
                            <li class="page-item {{ $currentPage == 1 ? 'disabled' : '' }}">
                                <a class="page-link border-0" href="{{ request()->fullUrlWithQuery(['page' => 1]) }}">«</a>
                            </li>
                            <li class="page-item {{ $currentPage == 1 ? 'disabled' : '' }}">
                                <a class="page-link border-0" href="{{ request()->fullUrlWithQuery(['page' => $currentPage - 1]) }}">‹</a>
                            </li>

                            @for ($i = max(1, $currentPage - 2); $i <= min($totalPages, $currentPage + 2); $i++)
                                <li class="page-item {{ $i == $currentPage ? 'active' : '' }}">
                                    <a class="page-link border-0" href="{{ request()->fullUrlWithQuery(['page' => $i]) }}">{{ $i }}</a>
                                </li>
                            @endfor

                            <li class="page-item {{ $currentPage == $totalPages ? 'disabled' : '' }}">
                                <a class="page-link border-0" href="{{ request()->fullUrlWithQuery(['page' => $currentPage + 1]) }}">›</a>
                            </li>
                            <li class="page-item {{ $currentPage == $totalPages ? 'disabled' : '' }}">
                                <a class="page-link border-0" href="{{ request()->fullUrlWithQuery(['page' => $totalPages]) }}">»</a>
                            </li>
                        </ul>
                    </nav>
                    <!-- Agregar información de paginación aquí también si es necesario -->
                    <div class="text-center text-muted mt-2">
                        Página {{ $currentPage }} de {{ $totalPages }} - 
                        Mostrando {{ ($currentPage - 1) * $pageSize + 1 }}-{{ min($currentPage * $pageSize, $totalProducts) }} de {{ $totalProducts }} productos
                    </div>
                </div>
            </div>
            @endif
        </div>
    </section>

    <!-- Paginación -->
    @if($productos->isNotEmpty())
    <div class="row mt-4">
        <div class="col-12">
            <nav aria-label="Page navigation">
                <ul class="pagination justify-content-center">
                    <li class="page-item {{ $currentPage == 1 ? 'disabled' : '' }}">
                        <a class="page-link border-0" href="{{ request()->fullUrlWithQuery(['page' => 1]) }}">«</a>
                    </li>
                    <li class="page-item {{ $currentPage == 1 ? 'disabled' : '' }}">
                        <a class="page-link border-0" href="{{ request()->fullUrlWithQuery(['page' => $currentPage - 1]) }}">‹</a>
                    </li>

                    @for ($i = max(1, $currentPage - 2); $i <= min($totalPages, $currentPage + 2); $i++)
                        <li class="page-item {{ $i == $currentPage ? 'active' : '' }}">
                            <a class="page-link border-0" href="{{ request()->fullUrlWithQuery(['page' => $i]) }}">{{ $i }}</a>
                        </li>
                    @endfor

                    <li class="page-item {{ $currentPage == $totalPages ? 'disabled' : '' }}">
                        <a class="page-link border-0" href="{{ request()->fullUrlWithQuery(['page' => $currentPage + 1]) }}">›</a>
                    </li>
                    <li class="page-item {{ $currentPage == $totalPages ? 'disabled' : '' }}">
                        <a class="page-link border-0" href="{{ request()->fullUrlWithQuery(['page' => $totalPages]) }}">»</a>
                    </li>
                </ul>
            </nav>
        </div>
    </div>
    @endif

    <!-- Productos Sugeridos -->
    @if($sugeridos->isNotEmpty())
    <section class="py-5 bg-plomo">
        <div class="container px-4 px-lg-5 mt-5">
            <h2 class="text-center mb-5 text-white">Productos Sugeridos</h2>
            <div class="row gx-4 gx-lg-5 row-cols-2 row-cols-md-3 row-cols-xl-4 justify-content-center">
                @foreach($sugeridos as $producto)
                    <div class="col mb-5">
                        <div class="card h-100 shadow product-card">
                            <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-warning text-dark">
                                Sugerido
                            </span>
                            <img class="card-img-top" src="{{ $producto->imagen_url }}" alt="{{ $producto->nombre }}" style="height: 200px; object-fit: cover;" />
                            <div class="card-body p-4">
                                <div class="text-center">
                                    <h5 class="fw-bolder">{{ $producto->nombre }}</h5>
                                    <div class="mb-2">
                                        <span class="badge badge-marca">{{ $producto->marca->descripcion }}</span>
                                        <span class="badge badge-category">{{ $producto->categoria->descripcion }}</span>
                                    </div>
                                    <span class="fw-bold text-primary fs-4">${{ number_format($producto->precio, 2) }}</span>
                                </div>
                            </div>
                            <div class="card-footer p-4 pt-0 border-top-0 bg-transparent">
                                <div class="text-center">
                                    <a class="btn btn-outline-dark mt-auto" href="{{ route('producto.detalle', $producto->idproducto) }}">
                                        Ver Detalles
                                    </a>
                                </div>
                                @auth
                                    <div class="text-center mt-2">
                                        <form action="{{ route('carrito.agregar', $producto->idproducto) }}" method="POST">
                                            @csrf
                                            <button type="submit" class="btn btn-outline-success mt-auto" {{ $producto->stock <= 0 ? 'disabled' : '' }}>
                                                <i class="bi bi-cart-plus"></i> {{ $producto->stock > 0 ? 'Añadir' : 'Sin Stock' }}
                                            </button>
                                        </form>
                                    </div>
                                @endauth
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>
    @endif

    <!-- Modal de Configuración -->
    @auth
    <div class="modal fade" id="configModal" tabindex="-1" aria-labelledby="configModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="configModalLabel">Configuración de Cuenta</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('configuracion.actualizar') }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">Nombre</label>
                            <input type="text" class="form-control" value="{{ $user->nombre }}" readonly>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Apellido Paterno</label>
                            <input type="text" class="form-control" value="{{ $user->apellido_p }}" readonly>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Apellido Materno</label>
                            <input type="text" class="form-control" value="{{ $user->apellido_m }}" readonly>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Documento</label>
                            <input type="text" class="form-control" value="{{ $user->documento }}" readonly>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Correo</label>
                            <input type="text" class="form-control" value="{{ $user->correo }}" readonly>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Teléfono</label>
                            <input type="text" name="telefono" class="form-control" value="{{ $user->telefono }}">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Nueva Contraseña</label>
                            <input type="password" name="clave" class="form-control" placeholder="Dejar en blanco para mantener la actual">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                        <button type="submit" class="btn btn-primary">Guardar Cambios</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    @endauth

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>

        function agregarAlCarrito(idProducto) {
            fetch(`/carrito/agregar/${idProducto}`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Content-Type': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Actualizar contador del carrito
                    document.querySelectorAll('.badge.bg-dark').forEach(badge => {
                        badge.textContent = data.cartCount;
                    });
                    
                    Swal.fire('Éxito', data.success, 'success');
                } else if (data.error) {
                    Swal.fire('Error', data.error, 'error');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                Swal.fire('Error', 'Error al agregar al carrito', 'error');
            });
        }

        // Actualizar todos los botones "Añadir al carrito"
        document.addEventListener('DOMContentLoaded', function() {
            document.querySelectorAll('form[action*="carrito/agregar"]').forEach(form => {
                form.addEventListener('submit', function(e) {
                    e.preventDefault();
                    const idProducto = this.querySelector('input[name="idProducto"]')?.value || 
                                    this.getAttribute('action').split('/').pop();
                    agregarAlCarrito(idProducto);
                });
            });
        });


        function mostrarModalConfiguracion() {
            var myModal = new bootstrap.Modal(document.getElementById('configModal'));
            myModal.show();
        }

        var myCarousel = document.querySelector('#productCarousel')
        var carousel = new bootstrap.Carousel(myCarousel, {
            interval: 3000,
            wrap: true
        });

        // Auto-submit forms cuando cambian los selects
        document.addEventListener('DOMContentLoaded', function() {
            const selects = document.querySelectorAll('select[onchange]');
            selects.forEach(select => {
                select.addEventListener('change', function() {
                    this.form.submit();
                });
            });
        });
    </script>

    <!-- Mostrar alertas con SweetAlert -->
    @if (session('success'))
        <script>
            Swal.fire('Éxito', '{{ session('success') }}', 'success');
        </script>
    @endif

    @if (session('error'))
        <script>
            Swal.fire('Error', '{{ session('error') }}', 'error');
        </script>
    @endif
</body>
</html>
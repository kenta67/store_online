<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <meta name="description" content="" />
    <meta name="author" content="" />
    <title>{{ $producto->nombre }} - Tienda Store</title>
    
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
        .btn-outline-dark:disabled {
            opacity: 0.5;
            cursor: not-allowed;
        }
        body {
            background-image: url('/fondo/143788648_ede90800-3eb2-48d2-96f2-8cf04effd44c.jpg');
            background-size: cover;        
            background-position: center;   
            background-repeat: no-repeat; 
            background-color: #1a1a1a; 
            min-height: 100vh;
        }
        
        .product-detail-container {
            background-color: white;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            padding: 40px;
            margin: 30px auto;
            max-width: 1200px;
        }
        .product-image {
            border-radius: 10px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        }
        .quantity-input {
            width: 80px;
            text-align: center;
        }
        .bg-plomo {
            background-color: #6c757d !important;
        }
        .badge-category {
            background: linear-gradient(45deg, #007bff, #0056b3);
            color: white;
        }
        .badge-marca {
            background: linear-gradient(45deg, #28a745, #1e7e34);
            color: white;
        }
        .price-tag {
            font-size: 2.5rem;
            font-weight: bold;
            color: #28a745;
        }
        .stock-badge {
            font-size: 1rem;
            padding: 8px 15px;
        }
    </style>
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <div class="container px-4 px-lg-5">
            <a class="navbar-brand" href="{{ route('index') }}">Tienda Store</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0 ms-lg-4">
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('index') }}">Inicio</a>
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

    <!-- Detalle del Producto -->
    <section class="py-5">
        <div class="container px-4 px-lg-5 my-5">
            <div class="product-detail-container">
                <div class="row gx-4 gx-lg-5 align-items-center">
                    <div class="col-md-6">
                        <img class="card-img-top mb-5 mb-md-0 product-image" 
                             src="{{ $producto->imagen_url }}" 
                             alt="{{ $producto->nombre }}" 
                             style="max-height: 500px; object-fit: cover;" />
                    </div>
                    <div class="col-md-6">
                        <div class="small mb-2">
                            <span class="badge badge-marca">{{ $producto->marca->descripcion }}</span>
                            <span class="badge badge-category">{{ $producto->categoria->descripcion }}</span>
                        </div>
                        
                        <h1 class="display-5 fw-bolder">{{ $producto->nombre }}</h1>
                        
                        <div class="fs-5 mb-4">
                            <span class="price-tag">${{ number_format($producto->precio, 2) }}</span>
                        </div>
                        
                        <p class="lead">{{ $producto->descripcion }}</p>
                        
                        <div class="mb-4">
                            @if($producto->stock > 0)
                                <span class="badge bg-success stock-badge">
                                    <i class="fas fa-check-circle me-1"></i>
                                    En Stock: {{ $producto->stock }} unidades
                                </span>
                            @else
                                <span class="badge bg-danger stock-badge">
                                    <i class="fas fa-times-circle me-1"></i>
                                    Agotado
                                </span>
                            @endif
                        </div>

                        <div class="d-flex align-items-center mb-4">
                            <label class="me-3 fw-bold">Cantidad:</label>
                            <input type="number" 
                                   id="inputQuantity" 
                                   class="form-control text-center quantity-input" 
                                   value="1" 
                                   min="1" 
                                   max="{{ $producto->stock }}"
                                   {{ $producto->stock <= 0 ? 'disabled' : '' }}>
                        </div>

                        <div class="d-flex gap-3">
                            @auth
                                <button type="button" 
                                        id="btnAddToCart" 
                                        class="btn btn-primary btn-lg flex-shrink-0"
                                        {{ $producto->stock <= 0 ? 'disabled' : '' }}>
                                    <i class="bi bi-cart-plus me-2"></i>
                                    {{ $producto->stock > 0 ? 'Añadir al Carrito' : 'Sin Stock' }}
                                </button>
                            @else
                                <a href="{{ route('login') }}" class="btn btn-primary btn-lg flex-shrink-0">
                                    <i class="bi bi-box-arrow-in-right me-2"></i>
                                    Iniciar sesión para comprar
                                </a>
                            @endauth
                            
                            <a href="{{ route('index') }}" class="btn btn-outline-secondary btn-lg">
                                <i class="bi bi-arrow-left me-2"></i>
                                Seguir Comprando
                            </a>
                        </div>

                        <!-- Información adicional -->
                        <div class="mt-4 pt-4 border-top">
                            <h6 class="fw-bold mb-3">Información del Producto</h6>
                            <div class="row">
                                <div class="col-6">
                                    <small class="text-muted">Marca:</small>
                                    <p class="mb-2 fw-bold">{{ $producto->marca->descripcion }}</p>
                                </div>
                                <div class="col-6">
                                    <small class="text-muted">Categoría:</small>
                                    <p class="mb-2 fw-bold">{{ $producto->categoria->descripcion }}</p>
                                </div>
                                <div class="col-6">
                                    <small class="text-muted">Estado:</small>
                                    <p class="mb-2">
                                        <span class="badge {{ $producto->stock > 0 ? 'bg-success' : 'bg-danger' }}">
                                            {{ $producto->stock > 0 ? 'Disponible' : 'Agotado' }}
                                        </span>
                                    </p>
                                </div>
                                <div class="col-6">
                                    <small class="text-muted">SKU:</small>
                                    <p class="mb-2 fw-bold">#{{ str_pad($producto->idproducto, 6, '0', STR_PAD_LEFT) }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Productos Sugeridos -->
    @if($sugeridos->count() > 0)
    <section class="py-5 bg-plomo">
        <div class="container px-4 px-lg-5 mt-5">
            <h2 class="text-center mb-5 text-white">Productos Sugeridos</h2>
            <div class="row gx-4 gx-lg-5 row-cols-2 row-cols-md-3 row-cols-xl-4 justify-content-center">
                @foreach($sugeridos as $sugerido)
                    <div class="col mb-5">
                        <div class="card h-100 shadow product-card">
                            <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-warning text-dark">
                                Sugerido
                            </span>
                            <img class="card-img-top" 
                                 src="{{ $sugerido->imagen_url }}" 
                                 alt="{{ $sugerido->nombre }}"
                                 style="height: 200px; object-fit: cover;" />
                            <div class="card-body p-4">
                                <div class="text-center">
                                    <h5 class="fw-bolder">{{ $sugerido->nombre }}</h5>
                                    <div class="mb-2">
                                        <span class="badge badge-marca">{{ $sugerido->marca->descripcion }}</span>
                                    </div>
                                    <span class="fw-bold text-primary">${{ number_format($sugerido->precio, 2) }}</span>
                                </div>
                            </div>
                            <div class="card-footer p-4 pt-0 border-top-0 bg-transparent">
                                <div class="text-center">
                                    <a class="btn btn-outline-dark mt-auto" 
                                       href="{{ route('producto.detalle', $sugerido->idproducto) }}">
                                        Ver Detalles
                                    </a>
                                </div>
                                @auth
                                    <div class="text-center mt-2">
                                        <button type="button" 
                                                class="btn btn-outline-success mt-auto agregar-carrito-sugerido"
                                                data-producto-id="{{ $sugerido->idproducto }}"
                                                {{ $sugerido->stock <= 0 ? 'disabled' : '' }}>
                                            <i class="bi bi-cart-plus"></i> 
                                            {{ $sugerido->stock > 0 ? 'Añadir' : 'Sin Stock' }}
                                        </button>
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
        function mostrarModalConfiguracion() {
            var myModal = new bootstrap.Modal(document.getElementById('configModal'));
            myModal.show();
        }

        // Agregar al carrito desde el detalle
        document.getElementById('btnAddToCart')?.addEventListener('click', function() {
            const productoId = {{ $producto->idproducto }};
            const cantidad = parseInt(document.getElementById('inputQuantity').value);
            
            agregarAlCarrito(productoId, cantidad);
        });

        // Agregar al carrito desde productos sugeridos
        document.querySelectorAll('.agregar-carrito-sugerido').forEach(button => {
            button.addEventListener('click', function() {
                const productoId = this.getAttribute('data-producto-id');
                agregarAlCarrito(productoId, 1);
            });
        });

        // Función para agregar al carrito
        function agregarAlCarrito(productoId, cantidad) {
            fetch(`/carrito/agregar/${productoId}`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Content-Type': 'application/json',
                    'Accept': 'application/json'
                },
                body: JSON.stringify({
                    cantidad: cantidad
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Actualizar contador del carrito
                    document.querySelectorAll('.badge.bg-dark').forEach(badge => {
                        badge.textContent = data.cartCount;
                    });
                    
                    Swal.fire({
                        title: '¡Éxito!',
                        text: data.message,
                        icon: 'success',
                        showConfirmButton: false,
                        timer: 1500
                    });
                } else {
                    if (data.redirect) {
                        window.location.href = data.redirect;
                    } else {
                        Swal.fire('Error', data.message, 'error');
                    }
                }
            })
            .catch(error => {
                console.error('Error:', error);
                Swal.fire('Error', 'Error al agregar al carrito', 'error');
            });
        }

        // Validar cantidad máxima
        document.getElementById('inputQuantity')?.addEventListener('change', function() {
            const maxStock = {{ $producto->stock }};
            if (this.value > maxStock) {
                this.value = maxStock;
                Swal.fire('Información', `Solo hay ${maxStock} unidades disponibles`, 'info');
            }
        });
    </script>

    <!-- Mostrar alertas -->
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
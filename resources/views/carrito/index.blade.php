<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <meta name="description" content="" />
    <meta name="author" content="" />
    <title>Carrito de Compras - Tienda Store</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.5.0/font/bootstrap-icons.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css"> 
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <style>
        .cart-item {
            border-bottom: 1px solid #eee;
            padding: 20px 0;
        }
        .quantity-input {
            width: 80px;
            text-align: center;
        }
        .summary-card {
            background: #f8f9fa;
            border-radius: 10px;
            padding: 25px;
            position: sticky;
            top: 20px;
        }
        .address-card {
            border: 2px dashed #dee2e6;
            border-radius: 10px;
            padding: 20px;
            margin-bottom: 20px;
            cursor: pointer;
            transition: all 0.3s ease;
        }
        .address-card:hover, .address-card.selected {
            border-color: #007bff;
            background-color: #f8f9ff;
        }
        .payment-method {
            border: 2px solid #dee2e6;
            border-radius: 10px;
            padding: 15px;
            margin-bottom: 15px;
            cursor: pointer;
            transition: all 0.3s ease;
        }
        .payment-method:hover, .payment-method.selected {
            border-color: #007bff;
            background-color: #f8f9ff;
        }
        .payment-method.disabled {
            opacity: 0.5;
            cursor: not-allowed;
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
                        <a class="nav-link active" href="{{ route('carrito') }}">Carrito</a>
                    </li>
                </ul>
                @auth
                    <ul class="navbar-nav">
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle d-flex align-items-center" href="#" role="button" data-bs-toggle="dropdown">
                                <i class="fas fa-user me-2"></i>
                                <span class="d-none d-lg-inline">{{ Auth::user()->nombre }}</span>
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

    <div class="container py-5">
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <h1 class="mb-4">Carrito de Compras</h1>

        @if($carrito->count() > 0)
        <div class="row">
            <div class="col-lg-8">
                <!-- Productos en el carrito -->
                <div class="card">
                    <div class="card-body">
                        @foreach($carrito as $item)
                            <div class="cart-item">
                                <div class="row align-items-center">
                                    <div class="col-md-2">
                                        <img src="{{ $item->producto->imagen_url }}" alt="{{ $item->producto->nombre }}" class="img-fluid rounded">
                                    </div>
                                    <div class="col-md-4">
                                        <h5 class="mb-1">{{ $item->producto->nombre }}</h5>
                                        <p class="text-muted mb-0">{{ $item->producto->marca->descripcion }}</p>
                                        <small class="text-muted">Stock: {{ $item->producto->stock }}</small>
                                    </div>
                                    <div class="col-md-2">
                                        <span class="h5">${{ number_format($item->producto->precio, 2) }}</span>
                                    </div>
                                    <div class="col-md-2">
                                        <form action="{{ route('carrito.actualizar', $item->idcarrito) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('PUT')
                                            <input type="number" name="cantidad" value="{{ $item->cantidad }}" min="1" max="{{ $item->producto->stock }}" 
                                                   class="form-control quantity-input" onchange="this.form.submit()">
                                        </form>
                                    </div>
                                    <div class="col-md-2">
                                        <span class="h5 text-primary">${{ number_format($item->producto->precio * $item->cantidad, 2) }}</span>
                                        <form action="{{ route('carrito.eliminar', $item->idcarrito) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-link text-danger p-0 ms-2" title="Eliminar">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        @endforeach

                        <div class="text-end mt-3">
                            <form action="{{ route('carrito.vaciar') }}" method="POST" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-outline-danger" onclick="return confirm('¿Estás seguro de vaciar el carrito?')">
                                    <i class="fas fa-trash me-1"></i> Vaciar Carrito
                                </button>
                            </form>
                        </div>
                    </div>
                </div>

                <!-- Dirección de Envío -->
                <div class="card mt-4">
                    <div class="card-header">
                        <h5 class="mb-0"><i class="fas fa-map-marker-alt me-2"></i>Dirección de Envío</h5>
                    </div>
                    <div class="card-body">
                        @if($direcciones->count() > 0)
                            <div class="row" id="direcciones-list">
                                @foreach($direcciones as $direccion)
                                    <div class="col-md-6 mb-3">
                                        <div class="address-card" data-id="{{ $direccion->iddireccion }}">
                                            <div class="form-check">
                                                <input class="form-check-input" type="radio" name="direccion" value="{{ $direccion->iddireccion }}" id="dir{{ $direccion->iddireccion }}">
                                                <label class="form-check-label w-100" for="dir{{ $direccion->iddireccion }}">
                                                    <strong>{{ $direccion->direccion }}</strong><br>
                                                    <small class="text-muted">{{ $direccion->detallelugar }}</small><br>
                                                    <small class="text-muted">Tel: {{ $direccion->telefono }}</small>
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @endif

                        <!-- Formulario para nueva dirección -->
                        <div class="mb-3">
                            <button class="btn btn-outline-primary w-100" type="button" data-bs-toggle="collapse" data-bs-target="#nuevaDireccion">
                                <i class="fas fa-plus me-2"></i> Agregar Nueva Dirección
                            </button>
                        </div>

                        <div class="collapse" id="nuevaDireccion">
                            <div class="card card-body">
                                <form id="nueva-direccion-form">
                                    @csrf
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label for="telefono" class="form-label">Teléfono *</label>
                                                <input type="text" class="form-control" id="telefono" name="telefono" required>
                                            </div>
                                        </div>
                                        <div class="col-md-12">
                                            <div class="mb-3">
                                                <label for="direccion" class="form-label">Dirección Completa *</label>
                                                <input type="text" class="form-control" id="direccion" name="direccion" required>
                                            </div>
                                        </div>
                                        <div class="col-md-12">
                                            <div class="mb-3">
                                                <label for="detallelugar" class="form-label">Detalle (Casa, Departamento, Referencia) *</label>
                                                <input type="text" class="form-control" id="detallelugar" name="detallelugar" required>
                                            </div>
                                        </div>
                                    </div>
                                    <button type="submit" class="btn btn-primary">Guardar Dirección</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Método de Pago -->
                <div class="card mt-4">
                    <div class="card-header">
                        <h5 class="mb-0"><i class="fas fa-credit-card me-2"></i>Método de Pago</h5>
                    </div>
                    <div class="card-body">
                        <div class="payment-method selected" data-method="paypal">
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="metodo_pago" value="paypal" id="paypal" checked>
                                <label class="form-check-label w-100" for="paypal">
                                    <img src="https://www.paypalobjects.com/webstatic/mktg/logo/pp_cc_mark_111x69.jpg" alt="PayPal" height="30" class="me-2">
                                    <strong>PayPal</strong>
                                    <small class="text-muted d-block">Paga de forma segura con tu cuenta PayPal</small>
                                </label>
                            </div>
                        </div>

                        <div class="payment-method disabled">
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="metodo_pago" value="tarjeta" id="tarjeta" disabled>
                                <label class="form-check-label w-100" for="tarjeta">
                                    <i class="fas fa-credit-card fa-2x me-2 text-muted"></i>
                                    <strong>Tarjeta de Crédito/Débito</strong>
                                    <small class="text-muted d-block">Próximamente disponible</small>
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Resumen del Pedido -->
            <div class="col-lg-4">
                <div class="summary-card">
                    <h4 class="mb-4">Resumen del Pedido</h4>
                    
                    <div class="d-flex justify-content-between mb-2">
                        <span>Subtotal:</span>
                        <span>${{ number_format($subtotal, 2) }}</span>
                    </div>
                    <div class="d-flex justify-content-between mb-2">
                        <span>Impuestos (18%):</span>
                        <span>${{ number_format($impuesto, 2) }}</span>
                    </div>
                    <div class="d-flex justify-content-between mb-2">
                        <span>Envío:</span>
                        <span class="text-success">Gratis</span>
                    </div>
                    <hr>
                    <div class="d-flex justify-content-between mb-3">
                        <strong>Total:</strong>
                        <strong class="h4 text-primary">${{ number_format($total, 2) }}</strong>
                    </div>

                    <form id="pago-form" action="{{ route('carrito.paypal.crear-pago') }}" method="POST">
                        @csrf
                        <input type="hidden" name="iddireccion" id="iddireccion-input">
                        <button type="submit" class="btn btn-primary btn-lg w-100" id="pagar-btn" disabled>
                            <i class="fab fa-paypal me-2"></i>Pagar con PayPal
                        </button>
                    </form>

                    <div class="text-center mt-3">
                        <small class="text-muted">
                            <i class="fas fa-lock me-1"></i>
                            Tu pago está seguro y encriptado
                        </small>
                    </div>
                </div>
            </div>
        </div>
        @else
            <div class="text-center py-5">
                <i class="fas fa-shopping-cart fa-4x text-muted mb-4"></i>
                <h3 class="text-muted">Tu carrito está vacío</h3>
                <p class="text-muted mb-4">¡Agrega algunos productos increíbles!</p>
                <a href="{{ route('index') }}" class="btn btn-primary btn-lg">
                    <i class="fas fa-shopping-bag me-2"></i>Continuar Comprando
                </a>
            </div>
        @endif
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Selección de dirección
        document.querySelectorAll('.address-card').forEach(card => {
            card.addEventListener('click', function() {
                // Remover selección anterior
                document.querySelectorAll('.address-card').forEach(c => c.classList.remove('selected'));
                // Seleccionar nueva
                this.classList.add('selected');
                const radio = this.querySelector('input[type="radio"]');
                radio.checked = true;
                
                // Habilitar botón de pago
                document.getElementById('iddireccion-input').value = radio.value;
                document.getElementById('pagar-btn').disabled = false;
            });
        });

        // Guardar nueva dirección
        document.getElementById('nueva-direccion-form').addEventListener('submit', function(e) {
            e.preventDefault();
            
            const formData = new FormData(this);
            
            fetch('{{ route("carrito.guardar-direccion") }}', {
                method: 'POST',
                body: formData,
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Recargar la página para mostrar la nueva dirección
                    location.reload();
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Error al guardar la dirección');
            });
        });

        // Validación antes de pagar
        document.getElementById('pago-form').addEventListener('submit', function(e) {
            const direccionSeleccionada = document.getElementById('iddireccion-input').value;
            if (!direccionSeleccionada) {
                e.preventDefault();
                alert('Por favor selecciona una dirección de envío');
                return false;
            }
        });
    </script>
</body>
</html>
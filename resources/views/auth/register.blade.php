<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <meta name="description" content="" />
    <meta name="author" content="" />
    <title>Register - Store Online</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://use.fontawesome.com/releases/v6.3.0/js/all.js" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        body {
            background-image: url('');
            background-size: cover;        
            background-position: center;   
            background-repeat: no-repeat; 
        }
    </style>
</head>
<body>
    <div id="layoutAuthentication">
        <div id="layoutAuthentication_content">
            <main>
                <div class="container">
                    <div class="row justify-content-center">
                        <div class="col-lg-7">
                            <div class="card shadow-lg border-0 rounded-lg mt-5">
                                <div class="card-header"><h3 class="text-center font-weight-light my-4">CREAR CUENTA</h3></div>
                                <div class="card-body">
                                    <form method="POST" action="{{ route('register') }}">
                                        @csrf
                                        <div class="row mb-3">
                                            <div class="col-md-6">
                                                <div class="form-floating mb-3 mb-md-0">
                                                    <input class="form-control @error('nombre') is-invalid @enderror" id="txtnombre" name="nombre" type="text" placeholder="Ingrese su nombre" value="{{ old('nombre') }}" required />
                                                    <label for="txtnombre">NOMBRE</label>
                                                    @error('nombre')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-floating">
                                                    <input class="form-control @error('apellido_p') is-invalid @enderror" id="txtapellido_p" name="apellido_p" type="text" placeholder="Ingrese su apellido paterno" value="{{ old('apellido_p') }}" required />
                                                    <label for="txtapellido_p">APELLIDO PATERNO</label>
                                                    @error('apellido_p')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row mb-3">
                                            <div class="col-md-6">
                                                <div class="form-floating mb-3 mb-md-0">
                                                    <input class="form-control @error('apellido_m') is-invalid @enderror" id="txtapellido_m" name="apellido_m" type="text" placeholder="Ingrese su apellido materno" value="{{ old('apellido_m') }}" />
                                                    <label for="txtapellido_m">APELLIDO MATERNO</label>
                                                    @error('apellido_m')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-floating">
                                                    <input class="form-control @error('telefono') is-invalid @enderror" id="txttelefono" name="telefono" type="text" placeholder="Teléfono" value="{{ old('telefono') }}" required />
                                                    <label for="txttelefono">TELÉFONO</label>
                                                    @error('telefono')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row mb-3">
                                            <div class="col-md-6">
                                                <div class="form-floating mb-3 mb-md-0">
                                                    <input class="form-control @error('documento') is-invalid @enderror" id="txtdocumento" name="documento" type="text" placeholder="Documento" value="{{ old('documento') }}" required />
                                                    <label for="txtdocumento">DOCUMENTO (NIT/CI)</label>
                                                    @error('documento')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-floating">
                                                    <input class="form-control @error('correo') is-invalid @enderror" id="txtcorreo" name="correo" type="email" placeholder="name@example.com" value="{{ old('correo') }}" required />
                                                    <label for="txtcorreo">CORREO</label>
                                                    @error('correo')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row mb-3">
                                            <div class="col-md-6">
                                                <div class="form-floating mb-3 mb-md-0">
                                                    <input class="form-control @error('clave') is-invalid @enderror" id="txtclave" name="clave" type="password" placeholder="Contraseña" required />
                                                    <label for="txtclave">CONTRASEÑA</label>
                                                    @error('clave')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-floating mb-3 mb-md-0">
                                                    <input class="form-control" id="txtclave_confirmation" name="clave_confirmation" type="password" placeholder="Confirmar contraseña" required />
                                                    <label for="txtclave_confirmation">CONFIRMAR CONTRASEÑA</label>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="mt-4 mb-0">
                                            <button type="submit" class="btn btn-primary btn-block">CREAR CUENTA</button>
                                        </div>
                                    </form>
                                </div>
                                <div class="card-footer text-center py-3">
                                    <div class="small"><a href="{{ route('login') }}">¿Tienes una cuenta? Ve a iniciar sesión</a></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </main>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
    @if($errors->any())
        <script>
            Swal.fire({
                title: 'Error',
                text: '{{ $errors->first() }}',
                icon: 'error'
            });
        </script>
    @endif
    @if(session('success'))
        <script>
            Swal.fire({
                title: 'Éxito',
                text: '{{ session('success') }}',
                icon: 'success'
            });
        </script>
    @endif
</body>
</html>
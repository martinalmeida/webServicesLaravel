@extends('template.login')

@section('title')
    VolApp - Login
@endsection

@section('content')
<form name="frm_login" class="form" id="frm_login">
    @csrf
    <div class="form-group">
        <label class="form-label" for="email">Nombre de Usuario</label>
        <input type="email" onKeyPress="if(this.value.length==100)return false;" min="0" class="form-control form-control-lg" id="email" name="email" placeholder="Ingresa tu email" required>
        <div class="invalid-feedback">Falta Correo Electronico.</div>
        <div class="help-block">Escribe tu email</div>
    </div>
    <div class="form-group">
        <label class="form-label" for="password">Contraseña</label>
        <input type="password" onKeyPress="if(this.value.length==100)return false;" min="0" class="form-control form-control-lg" id="password" name="password" placeholder="Ingresa tu contraseña" required>
        <div class="invalid-feedback">Falta contraseña.</div>
        <div class="help-block">Escribe tu contraseña</div>
    </div>

    <div class="form-group text-left">
    </div>
    <div class="row no-gutters">
        <div class="col-lg-12 pr-lg-1 my-2">
            <button class="btn btn-info btn-block btn-lg text-white" type="button" onclick="login();">Iniciar Sesión <i class="fal fa-sign-in"></i></button>
        </div>
    </div>
</form>
@endsection

@section('script')
    <script>
        function login()
        { 
            if($('#email').val() == "")
            { 
                Command: toastr["warning"](
                    "Por favor digite su correo electronico.",
                    "Falta Correo Electronico"
                );
                toastr.options = {
                    closeButton: false,
                    debug: false,
                    newestOnTop: true,
                    progressBar: true,
                    positionClass: "toast-top-right",
                    preventDuplicates: true,
                    onclick: null,
                    showDuration: 300,
                    hideDuration: 100,
                    timeOut: 5000,
                    extendedTimeOut: 1000,
                    showEasing: "swing",
                    hideEasing: "linear",
                    showMethod: "fadeIn",
                    hideMethod: "fadeOut",
                };
                return false;
            }
            else if($('#password').val() == "")
            {
                Command: toastr["warning"](
                    "Por favor digite su nombre de contraseña.",
                    "Falta Conreaseña"
                );
                toastr.options = {
                    closeButton: false,
                    debug: false,
                    newestOnTop: true,
                    progressBar: true,
                    positionClass: "toast-top-right",
                    preventDuplicates: true,
                    onclick: null,
                    showDuration: 300,
                    hideDuration: 100,
                    timeOut: 5000,
                    extendedTimeOut: 1000,
                    showEasing: "swing",
                    hideEasing: "linear",
                    showMethod: "fadeIn",
                    hideMethod: "fadeOut",
                };
                return false;
            }
            else {
                var data = $("#frm_login").serialize();
    
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });
    
                $.ajax({
                    type : 'POST',
                    url: '/check',
                    data : data,
                    success : function(response)
                    {
                        if(response == 1)
                        {
                            window.location.replace('/home');
                        }
                        else if(response == 3)
                        {
                            Swal.fire({
                                icon: "error",
                                title: "<strong>Credenciales Incorrectas</strong>",
                                html: "<h5>Si no recuerda sus credenciales dirijase a sistemas.</h5>",
                                showCloseButton: true,
                                showConfirmButton: false,
                                cancelButtonText: "Cerrar",
                                cancelButtonColor: "#dc3545",
                                showCancelButton: true,
                                backdrop: true,
                            });
                        }
                    }
                });
            }
        }
    </script>
@endsection

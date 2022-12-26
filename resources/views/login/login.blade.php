@extends('template.login')

@section('title')
    VolApp - Login
@endsection

@section('content')
<form id="frmLogin">
    <div class="form-group">
        <label class="form-label" for="usuarioLogin">Correo</label>
        <input type="email" onKeyPress="if(this.value.length==100)return false;" min="0" class="form-control form-control-lg" id="usuarioLogin" name="usuarioLogin" placeholder="Ingresa tu email" required>
        <div class="invalid-feedback">Falta Correo Electronico.</div>
        <div class="help-block">Escribe tu email</div>
    </div>
    <div class="form-group">
        <label class="form-label" for="contraseniaLogin">Contraseña</label>
        <input type="password" onKeyPress="if(this.value.length==100)return false;" min="0" class="form-control form-control-lg" id="contraseniaLogin" name="contraseniaLogin" placeholder="Ingresa tu contraseña" required>
        <div class="invalid-feedback">Falta contraseña.</div>
        <div class="help-block">Escribe tu contraseña</div>
    </div>

    <div class="panel-container show">
        <div class="panel-content d-flex justify-content-center">
            <div class="demo" id="spinnerLogin">
            </div>
        </div>
    </div>

    <div class="form-group text-left">
    </div>
    <div class="row no-gutters">
        <div class="col-lg-12 pr-lg-1 my-2">
            <a class="btn btn-info btn-block btn-lg text-white" id="btnLogin" onclick="login('frmLogin');">Iniciar Sesión <i class="fal fa-sign-in"></i></a>
        </div>
    </div>
</form>
@endsection

@section('script')
    <script>
        // --Funcion de inicio de Sesión--
        function login(form) {
            var respuestavalidacion = validarcampos("#" + form);
            if (respuestavalidacion) {
                $("#btnLogin").prop("disabled", true);
                var formData = new FormData(document.getElementById(form)); //Datos del formulario
                $.ajax({
                cache: false, //necesario para enviar archivos
                contentType: false, //necesario para enviar archivos
                processData: false, //necesario para enviar archivos
                data: formData, //necesario para enviar archivos
                dataType: "json", //Si no se especifica jQuery automaticamente encontrará el tipo basado en el header del archivo llamado (pero toma mas tiempo en cargar, asi que especificalo)
                url: urlBase + "routes/login/getUser", //url a donde hacemos la peticion
                type: "POST",
                beforeSend: function () {
                    var html = "";
                    html +=
                    '<div class="spinner-border text-primary" role="status"><span class="sr-only">Loading...</span></div>';

                    $("#spinnerLogin").html(html);
                },
                complete: function () {
                    // $("#spinnerLogin").html("");
                },
                success: function (result) {
                    $("#btnLogin").prop("disabled", false);
                    var estado = result.status;

                    switch (estado) {
                    case "0":
                        Swal.fire({
                        icon: "error",
                        title: "<strong>Error en el servidor</strong>",
                        html: "<h5>Se ha presentado un error al intentar consultar la información.</h5>",
                        showCloseButton: true,
                        showConfirmButton: false,
                        cancelButtonText: "Cerrar",
                        cancelButtonColor: "#dc3545",
                        showCancelButton: true,
                        backdrop: true,
                        });
                        $("#spinnerLogin").html("");
                        break;

                    case "1":
                        if (result.url != "") {
                        window.location = urlBase + result.url;
                        }
                        break;

                    case "2":
                        Swal.fire({
                        icon: "error",
                        title: "<strong>Error de Validacón</strong>",
                        html: "<h5>Se ha presentado un error al intentar validar la información.</h5>",
                        showCloseButton: true,
                        showConfirmButton: false,
                        cancelButtonText: "Cerrar",
                        cancelButtonColor: "#dc3545",
                        showCancelButton: true,
                        backdrop: true,
                        });
                        $("#spinnerLogin").html("");
                        break;

                    case "3":
                        Swal.fire({
                        icon: "info",
                        title: "<strong>Credenciales Incorrectas</strong>",
                        html: "<h5>Las credenciales son incorrectas.</h5>",
                        showCloseButton: true,
                        showConfirmButton: false,
                        cancelButtonText: "Cerrar",
                        cancelButtonColor: "#dc3545",
                        showCancelButton: true,
                        backdrop: true,
                        });
                        $("#spinnerLogin").html("");
                        break;

                    case "8":
                        Swal.fire({
                        icon: "info",
                        title: "<strong>Usuario Inactivo</strong>",
                        html: "<h5>El usuario se encuentra inactivo actualmente.</h5>",
                        showCloseButton: true,
                        showConfirmButton: false,
                        cancelButtonText: "Cerrar",
                        cancelButtonColor: "#dc3545",
                        showCancelButton: true,
                        backdrop: true,
                        });
                        $("#spinnerLogin").html("");
                        break;

                    case "9":
                        Swal.fire({
                        icon: "info",
                        title: "<strong>Empresa Inactiva</strong>",
                        html: "<h5>La empresa se encuentra inactiva actualmente.</h5>",
                        showCloseButton: true,
                        showConfirmButton: false,
                        cancelButtonText: "Cerrar",
                        cancelButtonColor: "#dc3545",
                        showCancelButton: true,
                        backdrop: true,
                        });
                        $("#spinnerLogin").html("");
                        break;

                    default:
                        break;
                    }
                },
                error: function (xhr) {
                    $("#btnLogin").prop("disabled", false);
                },
                });
            }
        }

        // --Funcion de cerrar Sesión--
        function cerrarSesion() {
        $.ajax({
            dataType: "json", //Si no se especifica jQuery automaticamente encontrará el tipo basado en el header del archivo llamado (pero toma mas tiempo en cargar, asi que especificalo)
            url: urlBase + "routes/login/logout", //url a donde hacemos la peticion
            type: "POST",
            beforeSend: function () {
            // $("#overlayText").text("Cerrando Sesión...");
            // $(".overlayCargue").fadeOut("slow");
            },
            complete: function () {
            // $(".overlayCargue").fadeIn("slow");
            },
            success: function (result) {
            var estado = result.status;
            switch (estado) {
                case "1":
                window.location.replace(urlBase);
                break;
            }
            },
            error: function (xhr) {
            console.log(xhr);
            },
        });
        }
    </script>
@endsection

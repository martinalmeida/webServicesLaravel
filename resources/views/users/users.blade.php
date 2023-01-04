@extends('template.constructor')

@section('title')
    VolApp - Users
@endsection

@section('head')
    <x-header title="Usuarios">
        <button type="button" class="btn btn-info active m-4" onclick="showModalRegistro();">
            Agregar <i class="fal fa-plus-square"></i>
        </button>
    </x-header>
@endsection

@section('content')
    <x-panel title="Tabla Usuario" subTitle="Adminstración de usuarios.">
        <x-table id="tablaUsuarios">
            <th>ID</th>
            <th>Nombre</th>
            <th>Correo Electronico</th>
            <th>Contraseña</th>
            <th>Rol</th>
            <th>Estado</th>
            <th>Acciones</th>
        </x-table>
    </x-panel>

    <x-modal-form id="ModalRegistro" title="Registro de Usuarios" text="Los usuarios que crees se limitan a dos roles.">
        <div class="col-md-12 mb-3">
            <lottie-player src="https://assets8.lottiefiles.com/private_files/lf30_wuojlcng.json" background="transparent"
                speed="1" style="width: 20%; margin: auto;" loop autoplay></lottie-player>
        </div>
        <div class="col-md-6 mb-3">
            <label class="form-label" for="nombre">Nombre de Usuario</label>
            <input type="text" onKeyPress="if(this.value.length==80)return false;" class="form-control" id="nombre"
                name="nombre" placeholder="Nombre del Usuario" minlength="3" maxlength="80" required>
        </div>
        <div class="col-md-6 mb-3">
            <label class="form-label" for="correo">Correo Electronico</label>
            <input type="email" onKeyPress="if(this.value.length==200)return false;" class="form-control" id="correo"
                name="correo" placeholder="Correo Electronico">
        </div>
        <div class="col-md-6 mb-3">
            <label class="form-label" for="password">Contraseña</label>
            <input type="text" onKeyPress="if(this.value.length==50)return false;" class="form-control" id="password"
                name="password" placeholder="Contraseña">
        </div>
        <div class="col-md-6 mb-3">
            <label class="form-label" for="rol">Rol:</label>
            <select class="select2 custom-select form-control" id="rol" name="rol">
            </select>
        </div>
    </x-modal-form>
@endsection

@section('subName')
    Pagina de Usuarios
@endsection

@section('script')
    <script type="text/javascript">
        let edit = false;
        var peticion = null;
        var tablaUsuarios = "";

        $(document).ready(function() {
            tablaUsuarios = $('#tablaUsuarios').DataTable({
                processing: true,
                serverSide: true,
                ajax: "{{ route('table.user') }}",
                columns: [{
                        data: 'id',
                        name: 'id'
                    },
                    {
                        data: 'name',
                        name: 'name'
                    },
                    {
                        data: 'email',
                        name: 'email'
                    },
                    {
                        data: 'contrasenia',
                        name: 'contrasenia'
                    },
                    {
                        data: 'rol',
                        name: 'rol'
                    },
                    {
                        data: 'status',
                        name: 'status'
                    },
                    {
                        data: 'action',
                        name: 'action',
                        orderable: false,
                        searchable: false
                    },
                ],
                responsive: true,
                lengthChange: false,
                dom: "<'row mb-3'<'col-sm-12 col-md-6 d-flex align-items-center justify-content-start'f><'col-sm-12 col-md-6 d-flex align-items-center justify-content-end'lB>>" +
                    "<'row'<'col-sm-12'tr>>" +
                    "<'row'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7'p>>",
                buttons: [{
                        extend: 'pdfHtml5',
                        text: 'PDF',
                        titleAttr: 'Generate PDF',
                        className: 'btn-outline-danger btn-sm mr-1'
                    },
                    {
                        extend: 'excelHtml5',
                        text: 'Excel',
                        titleAttr: 'Generate Excel',
                        className: 'btn-outline-success btn-sm mr-1'
                    }
                ]
            });
            $("#rol").select2({
                placeholder: "Selecciona el rol",
                allowClear: true,
            });
            selects();
        });

        function selects() {
            $.ajax({
                dataType: "json",
                url: "{{ route('select.roles') }}",
                type: "GET",
                success: function(result) {

                    var html = "";
                    for (let i = 0; i < result.length; i++) {
                        html += '<option value="' +
                            result[i].id +
                            '">' +
                            result[i].rol +
                            '</option>';
                    }
                    $("#rol").html(html);

                },
                error: function(xhr) {
                    console.log(xhr);
                    Swal.fire({
                        icon: "error",
                        title: "<strong>Error!</strong>",
                        html: "<h5>Se ha presentado un error, por favor informar al area de Sistemas.</h5>",
                        showCloseButton: true,
                        showConfirmButton: false,
                        cancelButtonText: "Cerrar",
                        cancelButtonColor: "#dc3545",
                        showCancelButton: true,
                        backdrop: true,
                    });
                },
            });
        }

        function register(form) {
            if ($('#nombre').val() == 0 || $('#correo').val() == 0 || $('#password').val() == 0 || $('#rol').val() ==
                null) {
                Command: toastr["error"](
                    "Por favor digite todos los campos del formulario para poder guardarlo.",
                    "Formulario Incompleto"
                );
            }
            else {
                edit == true ? peticion = "/updateUser" : peticion = "/createUser";
                var data = $("#" + form).serialize();
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });
                $.ajax({
                    type: 'POST',
                    url: peticion,
                    data: data,
                    success: function(result) {
                        if (result.status == true) {
                            Command: toastr["success"](
                                "El registro se ha guardado exitosamente.",
                                "Registro Guardado"
                            );
                            tablaUsuarios.clear().draw();
                            $("#ModalRegistro").modal("hide");
                        }
                        else {
                            Command: toastr["error"](
                                "El registro no se ha logrado guardar.",
                                "Fallo al Registrar"
                            );
                        }
                    },
                    error: function(xhr) {
                        console.log(xhr);
                        Swal.fire({
                            icon: "error",
                            title: "<strong>Error!</strong>",
                            html: "<h5>Se ha presentado un error, por favor informar al area de Sistemas.</h5>",
                            showCloseButton: true,
                            showConfirmButton: false,
                            cancelButtonText: "Cerrar",
                            cancelButtonColor: "#dc3545",
                            showCancelButton: true,
                            backdrop: true,
                        });
                    },
                });
            }
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
        }

        function listData(id) {
            $("#inputsEdit").html("");
            edit = true;
            $.ajax({
                type: 'GET',
                url: '/user/' + id,
                success: function(result) {
                    $("#btnRegistro").text("Editar Registro");
                    $("#btnRegistro").attr("onclick", "register('frmRegistro');");
                    $("#btnRegistro").removeClass("btn btn-info");
                    $("#btnRegistro").addClass("btn btn-success");
                    $("#nombre").val(result[0].name);
                    $("#correo").val(result[0].email);
                    $("#rol").val(result[0].rolId);
                    $("#rol").val(result[0].rolId).trigger("change");
                    $("#inputsEdit").html('<input type="hidden" id="idUser" name="idUser" value="' + result[0]
                        .id + '">');
                    $("#ModalRegistro").modal({
                        backdrop: "static",
                        keyboard: false,
                    });
                },
                error: function(xhr) {
                    console.log(xhr);
                    Swal.fire({
                        icon: "error",
                        title: "<strong>Error!</strong>",
                        html: "<h5>Se ha presentado un error, por favor informar al area de Sistemas.</h5>",
                        showCloseButton: true,
                        showConfirmButton: false,
                        cancelButtonText: "Cerrar",
                        cancelButtonColor: "#dc3545",
                        showCancelButton: true,
                        backdrop: true,
                    });
                },
            });
        }

        function statusChange(id, status) {
            $.ajax({
                type: 'GET',
                url: '/statusUser/' + id + '/' + status,
                success: function(result) {
                    if (result.status == true) {
                        Command: toastr["success"](
                            "Estado del Usuariio cambiado exitosamente.",
                            "Estado Cambiado"
                        );
                    }
                    else {
                        Command: toastr["error"](
                            "El estado del Usuariio no se pudo cambiar.",
                            "Estado No Cambiado"
                        );
                    }
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
                    tablaUsuarios.clear().draw();
                },
                error: function(xhr) {
                    console.log(xhr);
                    Swal.fire({
                        icon: "error",
                        title: "<strong>Error!</strong>",
                        html: "<h5>Se ha presentado un error, por favor informar al area de Sistemas.</h5>",
                        showCloseButton: true,
                        showConfirmButton: false,
                        cancelButtonText: "Cerrar",
                        cancelButtonColor: "#dc3545",
                        showCancelButton: true,
                        backdrop: true,
                    });
                },
            });
        }

        function deleteRegister(id) {
            Swal.fire({
                icon: "warning",
                title: "Eliminar Registro?",
                text: "Eliminar el registro del sistema!",
                type: "warning",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "Eliminar Registro",
                preConfirm: function() {
                    $.ajax({
                        type: 'GET',
                        url: '/deleteUser/' + id,
                        success: function(result) {
                            if (result.status == true) {
                                Command: toastr["success"](
                                    "el registro se ha eliminado exitosamente.",
                                    "Registro Eliminado"
                                );
                            }
                            else {
                                Command: toastr["error"](
                                    "el registro no se ha eliminado.",
                                    "Registro no Eliminado"
                                );
                            }
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
                            tablaUsuarios.clear().draw();
                        },
                        error: function(xhr) {
                            console.log(xhr);
                            Swal.fire({
                                icon: "error",
                                title: "<strong>Error!</strong>",
                                html: "<h5>Se ha presentado un error, por favor informar al area de Sistemas.</h5>",
                                showCloseButton: true,
                                showConfirmButton: false,
                                cancelButtonText: "Cerrar",
                                cancelButtonColor: "#dc3545",
                                showCancelButton: true,
                                backdrop: true,
                            });
                        },
                    });
                },
            });
        }

        function showModalRegistro() {
            reset();
            $("#btnRegistro").text("Crear Nuevo Registro");
            $("#btnRegistro").attr("onclick", "register('frmRegistro');");
            $("#ModalRegistro").modal({
                backdrop: "static",
                keyboard: false,
            });
            edit = false;
            $("#inputsEdit").html("");
        }

        function reset() {
            edit = false;
            vercampos("#frmRegistro", 1);
            limpiarcampos("#frmRegistro");
            $("#imagenBase64").html("");
            $("#btnRegistro").removeClass("btn btn-success");
            $("#btnRegistro").addClass("btn btn-info");
        }

        function reajustDatatables() {
            tablaUsuarios.columns.adjust().draw();
        }
    </script>
@endsection

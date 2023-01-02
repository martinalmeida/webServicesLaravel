@extends('template.constructor')

@section('title')
    VolApp - Users
@endsection

@section('head')
    <x-header title="Usuarios">
        <button type="button" class="btn btn-info active" onclick="showModalRegistro();">Agregar <i
                class="fal fa-plus-square"></i></button>
    </x-header>
@endsection

@section('content')
    <x-panel title="Tabla Usuario" subTitle="Adminstración de usuarios.">
        <x-table id="tablaUsuarios">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nombre</th>
                    <th>Correo Electronico</th>
                    <th>Rol</th>
                    <th>Estado</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody></tbody>
        </x-table>
    </x-panel>

    <x-modal id="modalUsuario" title="Registro de Usuarios" text="Los usuarios que crees se limitan a dos roles.">
        <x-form>
            <div class="col-md-6 mb-3">
                <label class="form-label" for="nombre">Nombre de Usuario</label>
                <input type="text" onKeyPress="if(this.value.length==80)return false;" class="form-control"
                    id="nombre" name="nombre" placeholder="Nombre del Usuario" required>
            </div>
            <div class="col-md-6 mb-3">
                <label class="form-label" for="correo">Correo Electronico</label>
                <input type="email" onKeyPress="if(this.value.length==200)return false;" class="form-control"
                    id="correo" name="correo" placeholder="Correo Electronico" required>
            </div>
            <div class="col-md-6 mb-3">
                <label class="form-label" for="password">Contraseña</label>
                <input type="text" onKeyPress="if(this.value.length==50)return false;" class="form-control"
                    id="password" name="password" placeholder="Contraseña" required>
            </div>
            <div class="col-md-6 mb-3">
                <label class="form-label">Rol:</label>
                <select class="select2 custom-select form-control" id="rol" name="rol">
                </select>
            </div>
        </x-form>
    </x-modal>
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

        function statusChange(id, status) {
            $.ajax({
                type: 'GET',
                url: '/status/' + id + '/' + status,
                success: function(result) {
                    if (result.status == TRUE) {

                    }
                    Command: toastr["success"](
                        "Estado del Usuariio cambiado exitosamente.",
                        "Estado Cambiado"
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

        function showModalRegistro() {
            reset();
            $("#btnRegistro").text("Registrar Usuario");
            $("#btnRegistro").attr("onclick", "registrar('frmRegistro');");
            $("#modalUsuario").modal({
                backdrop: "static",
                keyboard: false,
            });
            edit = false;
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

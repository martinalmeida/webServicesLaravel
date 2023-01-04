@extends('template.constructor')

@section('title')
    VolApp - Asignar
@endsection

@section('head')
    <x-header title="Asignar Placas">
        <button type="button" class="btn btn-info active m-4" onclick="showModalRegistro();">
            Agregar <i class="fal fa-plus-square"></i>
        </button>
    </x-header>
@endsection

@section('content')
    <x-panel title="Tabla Asignar" subTitle="Asignación de placas a usuarios.">
        <x-table id="tablAsignar">
            <th>ID</th>
            <th>Usuario</th>
            <th>Placa</th>
            <th>Estado</th>
            <th>Acciones</th>
        </x-table>
    </x-panel>

    <x-modal-form id="ModalRegistro" title="Asignar Placa a Usuario"
        text="Los usuarios pueden ser dueños de más de una placa.">
        <div class="col-md-12 mb-3">
            <lottie-player src="https://assets5.lottiefiles.com/packages/lf20_khm3kzeu.json" background="transparent"
                speed="1" style="width: 20%; margin: auto;" loop autoplay></lottie-player>
        </div>
        <div class="col-md-6 mb-3">
            <label class="form-label" for="usuario">Usuario:</label>
            <select class="select2 custom-select form-control" id="usuario" name="usuario">
            </select>
        </div>
        <div class="col-md-6 mb-3">
            <label class="form-label" for="placa">Placa:</label>
            <select class="select2 custom-select form-control" id="placa" name="placa">
            </select>
        </div>
    </x-modal-form>
@endsection

@section('subName')
    Pagina de Asignar Placar a Usuarios
@endsection

@section('script')
    <script type="text/javascript">
        let edit = false;
        var peticion = null;
        var tablAsignar = "";

        $(document).ready(function() {
            tablAsignar = $('#tablAsignar').DataTable({
                processing: true,
                serverSide: true,
                ajax: "{{ route('table.asignar') }}",
                columns: [{
                        data: 'id',
                        name: 'id'
                    },
                    {
                        data: 'name',
                        name: 'name'
                    },
                    {
                        data: 'placa',
                        name: 'placa'
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
            $("#usuario").select2({
                placeholder: "Selecciona el usuario",
                allowClear: true,
            });
            $("#placa").select2({
                placeholder: "Selecciona la placa",
                allowClear: true,
            });
            selects();
        });

        function selects() {
            $.ajax({
                dataType: "json",
                url: "{{ route('select.users') }}",
                type: "GET",
                success: function(result) {

                    var html = "";
                    for (let i = 0; i < result.length; i++) {
                        html += '<option value="' +
                            result[i].id +
                            '">' +
                            result[i].name +
                            '</option>';
                    }
                    $("#usuario").html(html);

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
            $.ajax({
                dataType: "json",
                url: "{{ route('select.placas') }}",
                type: "GET",
                success: function(result) {

                    var html = "";
                    for (let i = 0; i < result.length; i++) {
                        html += '<option value="' +
                            result[i].id +
                            '">' +
                            result[i].placa +
                            '</option>';
                    }
                    $("#placa").html(html);

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
            console.log($('#usuario').val())
            if ($('#usuario').val() == null || $('#placa').val() == null) {
                Command: toastr["error"](
                    "Por favor digite todos los campos del formulario para poder guardarlo.",
                    "Formulario Incompleto"
                );
            }
            else {
                edit == true ? peticion = "/updateAsigne" : peticion = "/createAsigne";
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
                            tablAsignar.clear().draw();
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
                url: '/asignar/' + id,
                success: function(result) {
                    $("#btnRegistro").text("Editar Registro");
                    $("#btnRegistro").attr("onclick", "register('frmRegistro');");
                    $("#btnRegistro").removeClass("btn btn-info");
                    $("#btnRegistro").addClass("btn btn-success");
                    $("#usuario").val(result[0].userId);
                    $("#usuario").val(result[0].userId).trigger("change");
                    $("#placa").val(result[0].placaId);
                    $("#placa").val(result[0].placaId).trigger("change");
                    $("#inputsEdit").html('<input type="hidden" id="idAsigne" name="idAsigne" value="' + result[
                            0]
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
                url: '/statusAsigne/' + id + '/' + status,
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
                    tablAsignar.clear().draw();
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
                        url: '/deleteAsigne/' + id,
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
                            tablAsignar.clear().draw();
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
            tablAsignar.columns.adjust().draw();
        }
    </script>
@endsection

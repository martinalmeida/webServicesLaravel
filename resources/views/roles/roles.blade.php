@extends('template.constructor')

@section('title')
    VolApp - Roles
@endsection

@section('head')
    <x-header title="Roles">
        <lottie-player src="https://assets6.lottiefiles.com/packages/lf20_4kxDIRVtSo.json" background="transparent"
            speed="1" style="width: 6%;" loop autoplay></lottie-player>
    </x-header>
@endsection

@section('content')
    <x-panel title="Tabla Roles" subTitle="solo se puede visualizar información">
        <x-table id="tablaRoles">
            <th>ID</th>
            <th>Rol</th>
            <th>Descripción</th>
            <th>Acciones</th>
        </x-table>
    </x-panel>

    <x-modal-form id="ModalVer" title="Registro de Usuarios" text="Los usuarios que crees se limitan a dos roles.">
        <div class="col-md-12 mb-3">
            <lottie-player src="https://assets5.lottiefiles.com/packages/lf20_s31beiqq.json" background="transparent"
                speed="1" style="width: 20%; margin: auto;" loop autoplay></lottie-player>
        </div>
        <div class="col-md-12 mb-3">
            <label class="form-label" for="rol">Nombre de Rol</label>
            <input type="text" class="form-control" id="rol">
        </div>
        <div class="col-md-12 mb-3">
            <label class="form-label" for="descripcion">Descripción de Rol</label>
            <textarea class="form-control" id="descripcion" rows="5" style="height: 77px;"></textarea>
        </div>
    </x-modal-form>
@endsection

@section('subName')
    Pagina de Roles
@endsection

@section('script')
    <script type="text/javascript">
        $(function() {
            var table = $('#tablaRoles').DataTable({
                processing: true,
                serverSide: true,
                ajax: "{{ route('table.roles') }}",
                columns: [{
                        data: 'id',
                        name: 'id'
                    },
                    {
                        data: 'rol',
                        name: 'rol'
                    },
                    {
                        data: 'descripcion',
                        name: 'descripcion'
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
        });

        function visualizar(id) {
            edit = true;
            $.ajax({
                type: 'GET',
                url: '/rol/' + id,
                success: function(result) {
                    $("#btnRegistro").text("Ver Registro");
                    $("#btnRegistro").addClass("btn btn-info");
                    $('#btnRegistro').attr('disabled', true);
                    $("#rol").val(result[0].rol);
                    $('#rol').attr('disabled', true);
                    $("#descripcion").val(result[0].descripcion);
                    $('#descripcion').attr('disabled', true);
                    $("#ModalVer").modal({
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
    </script>
@endsection

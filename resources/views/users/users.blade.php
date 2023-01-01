@extends('template.constructor')

@section('title')
    VolApp - Users
@endsection

@section('head')
<x-header title="Usuarios">
    <button type="button" class="btn btn-info active" onclick="showModalRegistro();">Agregar <i class="fal fa-plus-square"></i></button>
</x-header>
@endsection

@section('content')
<x-panel title="Tabla Usuario" subTitle="Adminstración de usuarios.">
    <x-table id="tablaUsuarios">
        <thead>
            <tr>
                <th>ID</th>
                <th>Rol</th>
                <th>Nombre</th>
                <th>Correo Electronico</th>
                <th>Estado</th>
                <th width="100px">Acciones</th>
            </tr>
        </thead>
        <tbody></tbody>
    </x-table>
</x-panel>

<x-modal id="modalUsuario" title="Usuario" text="Modal de usuario">
    <x-form>
            <div class="col-md-12 mb-3">
                <label class="form-label" for="nombrerol">Nombre Rol</label>
                <input type="text" onKeyPress="if(this.value.length==50)return false;" class="form-control" id="nombrerol" name="nombrerol" placeholder="Nombre del Rol" required>
            </div>
            <div class="col-md-12 mb-3">
                <label class="form-label" for="descripcion">Descripción de Rol</label>
                <textarea onKeyPress="if(this.value.length==1000)return false;" class="form-control" id="descripcion" name="descripcion" rows="5" style="height: 77px;" required></textarea>
            </div>
    </x-form>
</x-modal>
@endsection

@section('subName')
Pagina de Usuarios
@endsection

@section('script')
    <script type="text/javascript">
        $(function () {
            var table = $('#tablaUsuarios').DataTable({
                processing: true,
                serverSide: true,
                ajax: "{{ route('table.user') }}",
                columns: [
                    {data: 'id', name: 'id'},
                    {data: 'rol', name: 'rol'},
                    {data: 'name', name: 'name'},
                    {data: 'email', name: 'email'},
                    {data: 'status', name: 'status'},
                    {data: 'action', name: 'action', orderable: false, searchable: false},
                ],
                responsive: true,
                lengthChange: false,
                dom:
                    "<'row mb-3'<'col-sm-12 col-md-6 d-flex align-items-center justify-content-start'f><'col-sm-12 col-md-6 d-flex align-items-center justify-content-end'lB>>" +
                    "<'row'<'col-sm-12'tr>>" +
                    "<'row'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7'p>>",
                buttons: [
                    {
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

        function showModalRegistro() {
            reset();
            $("#btnRegistro").text("Registrar Contrato");
            $("#btnRegistro").attr("onclick", "registrar('frmRegistro');");
            $("#modalUsuario").modal({
                backdrop: "static",
                keyboard: false,
            });
            edit = false;
        }
  </script>
@endsection
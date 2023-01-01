@extends('template.constructor')

@section('title')
    VolApp - Asignar
@endsection

@section('head')
<x-header title="Asignar Placas a Usuarios">
    <button type="button" class="btn btn-info active" onclick="showModalRegistro();">Agregar <i class="fal fa-plus-square"></i></button>
</x-header>
@endsection

@section('content')
<x-panel title="Tabla Asiganar" subTitle="Asignación de placas a usuarios.">
    <x-table id="tablAsdignar">
        <thead>
            <tr>
                <th>ID</th>
                <th>Usuario</th>
                <th>Placa</th>
                <th>Estado</th>
                <th width="100px">Acciones</th>
            </tr>
        </thead>
        <tbody></tbody>
    </x-table>
</x-panel>
@endsection

@section('subName')
Pagina de Asignar Placar a Usuarios
@endsection

@section('script')
    <script type="text/javascript">
        $(function () {
        var table = $('#tablAsdignar').DataTable({
            processing: true,
            serverSide: true,
            ajax: "{{ route('table.asignar') }}",
            columns: [
                {data: 'id', name: 'id'},
                {data: 'name', name: 'name'},
                {data: 'placaId', name: 'placaId'},
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
  </script>
@endsection
@extends('template.constructor')

@section('title')
    VolApp - Roles
@endsection

@section('head')
    <x-header title="Roles">
    </x-header>
@endsection

@section('content')
    <x-panel title="Tabla Roles" subTitle="solo se puede visualizar información">
        <x-table id="tablaRoles">
            <th>ID</th>
            <th>Rol</th>
            <th>Descripción</th>
        </x-table>
    </x-panel>
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
    </script>
@endsection

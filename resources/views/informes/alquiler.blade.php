@extends('template.constructor')

@section('title')
    VolApp - Informes Alquiler
@endsection

@section('head')
    <x-header title="Informes a Proovedores por Alquiler">
        <button type="button" class="btn btn-primary active m-1" onClick="history.go(-1); return false;"><i
                class="fal fa-arrow-left"></i> Regresar</button>
    </x-header>
@endsection

@section('content')
    <x-panel title="Informes a Proovedor por Alquiler" subTitle="modulo de generación de informes.">
        <div class="panel-content">
            <form id="frmRelacion">
                <div class="form-row">
                    @csrf
                    <div class="col-md-4 mb-3"><select class="custom-select form-control" id="placa"
                            name="placa"></select></div>
                    <div class="input-group col-md-4 mb-3"><input type="text" class="form-control" id="fechaInicio"
                            name="fechaInicio" placeholder="Fecha Inicial" data-inputmask="'mask': '99/99/9999'"
                            im-insert="true">
                        <div class="input-group-append"><span class="input-group-text fs-xl"><i
                                    class="fal fa-calendar-check"></i></span></div>
                    </div>
                    <div class="input-group col-md-4 mb-3"><input type="text" class="form-control" id="fechaFin"
                            name="fechaFin" placeholder="Fecha Final" data-inputmask="'mask': '99/99/9999'"
                            im-insert="true">
                        <div class="input-group-append"><span class="input-group-text fs-xl"><i
                                    class="fal fa-calendar-check"></i></span></div>
                    </div>
                </div>
            </form>
            <button type="button" id="btnGenerar"
                class="btn btn-primary btn-pills btn-block waves-effect waves-themed">Generar el Informe para el
                Proovedor por Alquiler <i class="fal fa-file-download"></i></button>
        </div>
        <div class="panel-content" id="tablaInforme">
        </div>
    </x-panel>
@endsection

@section('subName')
    Pagina de Informes por Alquiler
@endsection

@section('script')
    <script type="text/javascript">
        var html = '';
        var tablaInformes = "";
        var controls = {
            leftArrow: '<i class="fal fa-angle-left" style="font-size: 1.25rem"></i>',
            rightArrow: '<i class="fal fa-angle-right" style="font-size: 1.25rem"></i>',
        };
        var runDatePicker = function() {
            $("#fechaInicio").datepicker({
                orientation: "buttom left",
                todayHighlight: true,
                templates: controls,
            });
            $("#fechaFin").datepicker({
                orientation: "buttom left",
                todayHighlight: true,
                templates: controls,
            });
        };

        $(document).ready(function() {
            $("#btnGenerar").attr("onclick", "cargarTablaInforme();");
            $("#placa").select2({
                placeholder: "Selecciona la placa",
                allowClear: true,
            });
            runDatePicker();
            selects();
            $(":input").inputmask();
        });

        function selects() {
            $.ajax({
                dataType: "json",
                url: "{{ route('select.placasInforme') }}",
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
                    $("#placa").val("").trigger("change");

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

        function cargarTablaInforme() {
            let placa = $("#placa").val();
            let fechaInicio = $("#fechaInicio").val();
            let fechaFin = $("#fechaFin").val();

            if (placa == null || fechaInicio == 0 || fechaFin == 0) {
                Command: toastr["error"](
                    "Por favor digite todos los campos del formulario.",
                    "Formulario Incompleto"
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
            }
            else {
                html =
                    '<table id="tablaInformes" class="table table-bordered table-hover table-striped w-100"><thead><tr>' +
                    "<th>Placa o #Registro</th><th>fecha Inicio</th><th>fecha Fin</th><th>Titulo del Contrato</th><th>Horometro Inicial</th>" +
                    "<th>Horometro Final</th><th>Total Horas Trabjadas</th><th>Stand-By</th><th>Valor Hora</th><th>Sub Total</th>" +
                    "<th>Deducible Administración</th><th>Deducible Retefuente</th><th>Deducible Reteica</th><th>Deducible anticipo</th>" +
                    "<th>Otros Deducible</th><th>Total</th><th>Observacion</th></tr></thead><tbody></tbody></table>";
                $("#tablaInforme").html(html);

                tablaInformes = $('#tablaInformes').DataTable({
                    processing: true,
                    serverSide: true,
                    searching: false,
                    destroy: true,
                    ajax: {
                        url: "{{ route('table.informeAlquiler') }}",
                        type: "POST",
                        data: {
                            placa: placa,
                            fechaInicio: fechaInicio,
                            fechaFin: fechaFin,
                            _token: '{{ csrf_token() }}'
                        },
                        dataType: "json",
                    },
                    columns: [{
                            data: 'placa',
                            name: 'placa'
                        },
                        {
                            data: 'fechaInicio',
                            name: 'fechaInicio'
                        },
                        {
                            data: 'fechaFin',
                            name: 'fechaFin'
                        },
                        {
                            data: 'titulo',
                            name: 'titulo'
                        },
                        {
                            data: 'horometroInicial',
                            name: 'horometroInicial'
                        },
                        {
                            data: 'horometroFin',
                            name: 'horometroFin'
                        },
                        {
                            data: 'totalHoras',
                            name: 'totalHoras'
                        },
                        {
                            data: 'standby',
                            name: 'standby'
                        },
                        {
                            data: 'horaTarifa',
                            name: 'horaTarifa'
                        },
                        {
                            data: 'subTotal',
                            name: 'subTotal'
                        },
                        {
                            data: 'admon',
                            name: 'admon'
                        },
                        {
                            data: 'retefuente',
                            name: 'retefuente'
                        },
                        {
                            data: 'reteica',
                            name: 'reteica'
                        },
                        {
                            data: 'anticipo',
                            name: 'anticipo'
                        },
                        {
                            data: 'otros',
                            name: 'otros'
                        },
                        {
                            data: 'total',
                            name: 'total'
                        },
                        {
                            data: 'observacion',
                            name: 'observacion'
                        },
                    ],
                    responsive: true,
                    lengthChange: false,
                    dom: "<'row mb-3'<'col-sm-12 col-md-6 d-flex align-items-center justify-content-start'f><'col-sm-12 col-md-6 d-flex align-items-center justify-content-end'lB>>" +
                        "<'row'<'col-sm-12'tr>>" +
                        "<'row'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7'p>>",
                    buttons: [{
                        extend: 'excelHtml5',
                        autoFilter: true,
                        text: "Descargar <i class='fal fa-file-excel'></i>",
                        titleAttr: 'Generate Excel',
                        className: "bg-success-900 btn-sm mr-1",
                        title: "Informe para el Proovedor por Alquiler",
                    }],
                    paging: false
                });
            }
        }
    </script>
@endsection

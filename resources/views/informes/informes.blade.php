@extends('template.constructor')

@section('title')
    VolApp - Informes
@endsection

@section('head')
    <x-header title="Informes a Proveedores">
        <lottie-player src="https://assets6.lottiefiles.com/packages/lf20_cRqOdOiWmq.json" background="transparent"
            speed="1" style="width: 6%;" loop autoplay></lottie-player>
    </x-header>
@endsection

@section('content')
    <x-panel title="Informes a Proovedor" subTitle="modulo de generación de informes.">
        <div class="card-group">
            <div class="card bg-success">
                <div class="card-body text-center">
                    <h3 class="card-title text-white">Informe por Alquiler</h3>
                    <h1 class="text-white"><i class="fal fa-hands-usd fa-4x"></i></i></h1>
                    <p class="card-text text-white">En el modo alquiler se calcula el Stand By de horas, u horometro final
                        menos horometro inicial.</p>
                    <a class="btn btn-outline-light btn-pills text-white waves-effect waves-themed" href="/alquiler">Ingresar
                        al
                        Informe por Alquiler</a>
                </div>
            </div>
            <div class="card bg-info">
                <div class="card-body text-center">
                    <h3 class="card-title text-white">Informe por Flete</h3>
                    <h1 class="text-white"><i class="fal fa-route fa-4x"></i></h1>
                    <p class="card-text text-white">En el modo flete se calcula solo un valor pactado por viaje con el socio
                        o dueño del vehículo.</p>
                    <a class="btn btn-outline-light btn-pills text-white waves-effect waves-themed" href="/flete">Ingresar
                        al Informe por Fletes</a>
                </div>
            </div>
            <div class="card bg-primary">
                <div class="card-body text-center">
                    <h3 class="card-title text-white">Informe por Movimiento</h3>
                    <h1 class="text-white"><i class="fal fa-truck-container fa-4x"></i></h1>
                    <p class="card-text text-white">Calculo de nro de viajes, tarifa de ruta pactada, metraje cubico y
                        kilometros recorridos.</p>
                    <a class="btn btn-outline-light btn-pills text-white waves-effect waves-themed"
                        href="/movimiento">Ingresar al
                        Informe por Movimientos</a>
                </div>
            </div>
        </div>
    </x-panel>
@endsection

@section('subName')
    Pagina de Informes
@endsection

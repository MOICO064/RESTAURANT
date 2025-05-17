@extends('layouts.main')
@section('content')
    <h1>Bienvenido a {{ system_info('name') }}</h1>
    <hr>

    <div class="row">
        <!-- Categories -->
        <div class="col-12 col-sm-4 col-md-4">
            <div class="info-box">
                <span class="info-box-icon bg-gradient-light elevation-1"><i class="fas fa-th-list"></i></span>
                <div class="info-box-content">
                    <span class="info-box-text">Lista de Categorias</span>
                    <span class="info-box-number text-right">{{ number_format($categoriesCount) }}</span>
                </div>
            </div>
        </div>

        <!-- Products -->
        <div class="col-12 col-sm-4 col-md-4">
            <div class="info-box">
                <span class="info-box-icon bg-gradient-navy elevation-1"><i class="fas fa-mug-hot"></i></span>
                <div class="info-box-content">
                    <span class="info-box-text">Lista de Productos</span>
                    <span class="info-box-number text-right">{{ number_format($productsCount) }}</span>
                </div>
            </div>
        </div>

        <!-- Sales -->
        <div class="col-12 col-sm-4 col-md-4">
            <div class="info-box">
                <span class="info-box-icon bg-gradient-primary elevation-1"><i class="fas fa-calendar-day"></i></span>
                <div class="info-box-content">
                    <span class="info-box-text">Ventas de Hoy</span>
                    <span class="info-box-number text-right">{{ number_format($salesTotal, 2) }}</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Carousel -->
    <div class="container mt-4">
        @if(count($banners) > 0)
            <div id="tourCarousel" class="carousel slide" data-ride="carousel" data-interval="3000">
                <div class="carousel-inner h-100">
                    @foreach($banners as $index => $img)
                        <div class="carousel-item h-100 {{ $index == 0 ? 'active' : '' }}">
                            <img class="d-block w-100 h-100" style="object-fit:contain" src="{{ $img }}" alt="">
                        </div>
                    @endforeach
                </div>
                <a class="carousel-control-prev" href="#tourCarousel" role="button" data-slide="prev">
                    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                    <span class="sr-only">Previous</span>
                </a>
                <a class="carousel-control-next" href="#tourCarousel" role="button" data-slide="next">
                    <span class="carousel-control-next-icon" aria-hidden="true"></span>
                    <span class="sr-only">Next</span>
                </a>
            </div>
        @else
            <p class="text-center">No banners found.</p>
        @endif
    </div>
@endsection
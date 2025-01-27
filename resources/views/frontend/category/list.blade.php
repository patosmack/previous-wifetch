@extends('frontend.app')

@section('content')



    <div class="wrapper">
        @include('frontend.shared.alert')
        <div class="life-theme white-bg">
            <div class="container">
                <div class="row">
                    <div class="col-md-12">
                        <div class="default-title">
                            <h2>Shop By Categories</h2>
                            <p>Order from supermarkets, restaurants and much more.</p>
                        </div>
                    </div>
                </div>
                <div class="dd-content mt-50">
                    <div class="row">
                        @foreach($categories as $categoryItem)
                            <div class="col-6 col-sm-4 col-md-2">
                                <a href="{{ route('merchants.by_category', $categoryItem->friendly_url) }}" class="category-item">
                                <div class="p-3">
                                    <img src="{{ asset($categoryItem->icon) }}" alt="{{ $categoryItem->name }}" class="img-fluid">
                                    <h4 style="padding: 8px 12px 5px;background: #F3E44E !important;border: 1px solid #F3E44E !important;">{{ $categoryItem->name }}</h4>
                                </div>
                                </a>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection

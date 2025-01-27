@extends('frontend.app')

@section('content')

    <div class="theme-Breadcrumb">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb pl-1 pl-sm-0">
                            <li class="breadcrumb-item d-none d-md-block"><a href="{{ route('categories.list') }}">Categories</a> /</li>
                            <li class="breadcrumb-item active" aria-current="page">{{ $category->name }}</li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>
    </div>

    <div class="wrapper-breadcrumb">
        @include('frontend.shared.alert')
        <div class="all-product-grid">
            <div class="container">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="product-top-dt">
                            <div class="product-left-title">
                                <h1>{{ $category->name }}</h1>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="product-list-view">
                    <div class="row">
                        @foreach($merchants as $merchantItem)
                            @include('frontend.components.merchantCard', ['merchantCardItem' => $merchantItem])
                        @endforeach
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <div class="main-title-tt">
                            <div class="main-title-left">
                                <span>Browse more</span>
                                <h2>Categories</h2>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="owl-carousel cate-slider owl-theme">
                            @foreach($categories as $categoryItem)
                                @if($category->id != $categoryItem->id)
                                <div class="item">
                                    <a href="{{ route('merchants.by_category', $categoryItem->friendly_url) }}" class="category-item">
                                        <div class="cate-img">
                                            <img src="{{ asset($categoryItem->icon) }}" alt="{{ $categoryItem->name }}">
                                        </div>
                                        <h4>{{ $categoryItem->name }}</h4>
                                    </a>
                                </div>
                                @endif
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection

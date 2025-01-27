<div class="section145 pt-0 pt-sm-5">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="main-title-tt">
                    <div class="main-title-left">
                        <span>Shop By</span>
                        <h2>Categories</h2>
                    </div>
                </div>
            </div>
            <div class="col-md-12">
                <div class="row">

                    <div class="col-12">

                        <div class="category-grid">

                @foreach($categories as $categoryItem)
{{--                    <div class="row mb-3">--}}

{{--                            <div class="col-3 col-sm-3 col-md">--}}
                                <div class="category-grid-item">
                                    <a href="{{ route('merchants.by_category', $categoryItem->friendly_url) }}">
                                        <div class="cate-img">
                                            <img src="{{ asset($categoryItem->icon) }}" alt="{{ $categoryItem->name }}" class="img-fluid">
                                        </div>
                                        <h4>{{ $categoryItem->name }}</h4>
                                    </a>
                                </div>
{{--                            </div>--}}
{{--                    </div>--}}
                @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

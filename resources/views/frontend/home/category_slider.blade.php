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
                <div class="owl-carousel cate-slider owl-theme">
                    @foreach($categories as $categoryItem)
                        <div class="item">
                            <a href="{{ route('merchants.by_category', $categoryItem->friendly_url) }}" class="category-item">
                                <div class="cate-img">
                                    <img src="{{ asset($categoryItem->icon) }}" alt="{{ $categoryItem->name }}">
                                </div>
                                <h4>{{ $categoryItem->name }}</h4>
                            </a>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>

<div id="search_model" class="header-cate-model main-hover-model modal fade" tabindex="-1" role="dialog" aria-modal="false">
    <div class="modal-dialog search-ground-area" role="document">
        <div class="category-area-inner">
            <div class="modal-header">
                <button type="button" class="close btn-close" data-dismiss="modal" aria-label="Close">
                    <i class="uil uil-multiply"></i>
                </button>
            </div>
            <div class="category-model-content modal-content">
                <div class="search-header">
                    <form action="{{ route('merchant.product.search') }}" method="GET">
                        <input type="text" name="search" value="{{ isset($headerSearch) ? $headerSearch : '' }}" placeholder="Search for products...">
                        <button type="submit"><i class="uil uil-search"></i></button>
                    </form>
                </div>
                <div class="search-by-cat">
                    @foreach($categories->take(4) as $category)
                        <a href="{{ route('merchants.by_category', $category->friendly_url) }}" class="single-cat">
                            <div class="icon">
                                <img src="{{ asset($category->icon) }}" alt="{{ $category->name }}">
                            </div>
                            <div class="text">
                                {{ $category->name }}
                            </div>
                        </a>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>

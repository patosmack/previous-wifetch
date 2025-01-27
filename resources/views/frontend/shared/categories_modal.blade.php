<div id="category_model" class="header-cate-model main-hover-model modal fade" tabindex="-1" role="dialog" aria-modal="false">
    <div class="modal-dialog category-area" role="document">
        <div class="category-area-inner">
            <div class="modal-header">
                <button type="button" class="close btn-close" data-dismiss="modal" aria-label="Close">
                    <i class="uil uil-multiply"></i>
                </button>
            </div>
            <div class="category-model-content modal-content">
                <div class="cate-header">
                    <div class="d-flex flex-row justify-content-between align-items-center">
                        <h4 class="p-0 m-0">Select Category</h4>
                        <a href="{{ route('home') }}"><img src="{{ asset('assets/common/logo-yellow.svg') }}" alt="" width="124"></a>
                    </div>

                </div>
                <ul class="category-by-cat">
                    @foreach($categories->take(6) as $category)
                        <li>
                            <a href="{{ route('merchants.by_category', $category->friendly_url) }}" class="single-cat-item">
                                <div class="icon">
                                    <img src="{{ asset($category->icon) }}" alt="{{ $category->name }}">
                                </div>
                                <div class="text"> {{ $category->name }} </div>
                            </a>
                        </li>
                    @endforeach
                </ul>
                <a href="{{ route('categories.list') }}" class="morecate-btn"><i class="uil uil-apps"></i>More Categories</a>
            </div>
        </div>
    </div>
</div>

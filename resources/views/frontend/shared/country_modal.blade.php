@if(!isset($userCurrentCountry) || !$userCurrentCountry)
<div id="country_modal" class="header-cate-model main-hover-model modal fade" tabindex="-1" role="dialog" aria-modal="false" data-backdrop="static">
    <div class="modal-dialog category-area" role="document">
        <div class="category-area-inner">
            <div class="modal-header">

            </div>
            <div class="category-model-content modal-content">
                <div class="cate-header">
                    <h4>Select Your Country</h4>
                </div>
                <ul class="country-modal-pick">
                    @foreach($countries as $countryItem)
                        <li>
                            <a href="{{ route('country.select', strtolower($countryItem->iso)) }}" class="single-cat-item">
                                <div class="icon">
                                    <img src="{{ asset("assets/flags/{$countryItem->iso}.svg") }}" alt="{{ $countryItem->name }}">
                                </div>
                                <div class="text"> {{ $countryItem->name }} </div>
                            </a>
                        </li>
                    @endforeach
                </ul>
            </div>
        </div>
    </div>
</div>
<a href="#" id="country_modal_btn" class="d-none" data-toggle="modal" data-target="#country_modal" title="Countries"></a>
@endif

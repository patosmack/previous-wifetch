<div class="section145 pt-0 d-none d-sm-block">
    <div class="container-fluid p-0">
        <div class="owl-carousel home-slider owl-theme">
            @foreach($banners as $banner)
                @if($banner->image)
                    <div class="item">
                        <a href="{{ $banner->target ?? '' }}">
                            <img src="{{ asset($banner->image) }}" class="img-fluid" alt="{{ $banner->name ?? '' }}">
                        </a>
                    </div>
                @endif
            @endforeach
        </div>
    </div>
</div>
<div class="section145 pt-0 d-block d-sm-none">
    <div class="container-fluid p-0">
        <div class="owl-carousel home-slider-mobile owl-theme">
            @foreach($banners as $banner)
                @if($banner->image_mobile)
                    <div class="item">
                        <a href="{{ $banner->target ?? '' }}">
                            <img src="{{ asset($banner->image_mobile) }}" class="img-fluid" alt="{{ $banner->name ?? '' }}">
                        </a>
                    </div>
                @endif
            @endforeach
        </div>
    </div>
</div>

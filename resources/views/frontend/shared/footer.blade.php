<footer class="footer">
    <div class="footer-first-row">
        <div class="container">
            <div class="row">
                <div class="col-md-6 col-sm-6 text-center text-sm-left">
                    <ul class="call-email-alt">
                        <li><a href="tel:2462648994" class="callemail"><i class="uil uil-phone"></i>(246) 264-8994</a></li>
                        <li><a href="mailto:info@wifetch.com" class="callemail"><i class="uil uil-envelope-alt"></i>info@wifetch.com</a></li>
                    </ul>
                </div>
                <div class="col-md-6 col-sm-6">
                    <div class="social-links-footer">
                        <ul class="text-center text-sm-right">
                            <li><a href="https://www.facebook.com/wifetch/"><i class="fab fa-facebook-f"></i></a></li>
                            <li><a href="https://twitter.com/wifetch"><i class="fab fa-twitter"></i></a></li>
                            <li><a href="https://www.instagram.com/wifetch/?hl=es-la"><i class="fab fa-instagram"></i></a></li>
                            <li><a href="https://www.youtube.com/channel/UCf68mL7g9CytCLaBF696uvg"><i class="fab fa-youtube"></i></a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="footer-second-row">
        <div class="container">
            <div class="row">
                <div class="col-lg-3 col-md-6 col-sm-6">
                    <div class="second-row-item text-center text-sm-left">
                        <h4 class="text-center text-sm-left">Categories</h4>
                        <ul>
                            @foreach($categories->take(5) as $category)
                                <li><a href="#">{{ $category->name }}</a></li>
                            @endforeach
                            <li><a href="#">View All</a></li>
                        </ul>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6 col-sm-6">
                    <div class="second-row-item text-center text-sm-left">
                        <h4 class="text-center text-sm-left">Company</h4>
                        <ul>
                            <li><a href="{{ route('company.about_us') }}">About US</a></li>
                            <li><a href="{{ route('company.privacy') }}">Privacy</a></li>
                            <li><a href="{{ route('company.terms') }}">Terms Of Use</a></li>
                            <li><a href="{{ route('company.contact') }}">Contact Us</a></li>
                            <li><a href="{{ route('company.become_a_vendor') }}">Become A Merchant</a></li>
{{--                            <li><a href="{{ route('company.become_a_driver') }}">Become A Driver</a></li>--}}
                        </ul>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6 col-sm-6">
                    <div class="second-row-item text-center text-sm-left">
                        <h4 class="text-center text-sm-left">WIFETCH INC</h4>
                        <ul>
                            <li>Suite 1, Durants</li>
                            <li>Christ Church</li>
                            <li>Barbados</li>
                        </ul>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6 col-sm-6">
{{--                    <div class="second-row-item-app">--}}
{{--                        <h4>Download App</h4>--}}
{{--                        <ul>--}}
{{--                            <li><a href="#"><img class="download-btn" src="{{ asset('assets/common/download-playstore.svg') }}" alt=""></a></li>--}}
{{--                            <li><a href="#"><img class="download-btn" src="{{ asset('assets/common/download-appstore.svg') }}" alt=""></a></li>--}}
{{--                        </ul>--}}
{{--                    </div>--}}
                    <div class="second-row-item-payment text-center text-sm-left">
                        <h4 class="text-center text-sm-left">Safe & Secure</h4>
                        <div class="footer-payments">
                            <ul id="paypal-gateway" class="financial-institutes">
                                <li class="financial-institutes__logo">
                                    <img alt="" title="Visa" src="{{ asset('assets/cards/visa.png') }}">
                                </li>
                                <li class="financial-institutes__logo">
                                    <img alt="" title="MasterCard" src="{{ asset('assets/cards/mastercard.png') }}">
                                </li>
                                <li class="financial-institutes__logo">
                                    <img alt="" title="American Express" src="{{ asset('assets/cards/amex.png') }}">
                                </li>
                                <li class="financial-institutes__logo">
                                    <img alt="" title="RBC" src="{{ asset('assets/cards/rbc.png') }}">
                                </li>
                                <li class="financial-institutes__logo">
                                    <img alt="" title="Scotiabank" src="{{ asset('assets/cards/scotiabank.png') }}">
                                </li>
                                <li class="financial-institutes__logo">
                                    <img alt="" title="CIBC" src="{{ asset('assets/cards/cibc.png') }}">
                                </li>
                            </ul>
                        </div>
                    </div>
                    <div class="second-row-item-payment">
                        <h5 class="text-center text-sm-left">All prices in Barbados dollars</h5>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="footer-last-row">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <div class="footer-bottom-links text-center text-sm-left">
                        <ul>
                            <li class="pb-4 pb-md-0"><a href="{{ route('company.about_us') }}">About US</a></li>
                            <li class="pb-4 pb-md-0"><a href="{{ route('company.contact') }}">Contact US</a></li>
                            <li class="pb-4 pb-md-0"><a href="{{ route('company.privacy') }}">Privacy</a></li>
                            <li class="pb-4 pb-md-0"><a href="{{ route('company.terms') }}">Term Of Use</a></li>
                        </ul>
                    </div>
                    <div class="copyright-text text-center text-sm-left">
                        <i class="uil uil-copyright"></i>Copyright {{ date('Y') }} <b>WiFetch.com</b> . All rights reserved
                    </div>
                </div>
            </div>
        </div>
    </div>
</footer>

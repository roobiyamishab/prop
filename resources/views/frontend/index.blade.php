@extends('frontend.layouts.master')

@section('content')


    <main>

        <section class="banner-four-section ">
            <div class="banner-four-slide swiper">
                <div class="swiper-wrapper slide-transition">
                    <div class="swiper-slide inner-slide-element">
                        <img src="{{ asset('frontend/assets/images/banner/banner-four4.jpg') }}" alt="banner image" class="img-fluid w-100">
                    </div>
                    <div class="swiper-slide inner-slide-element">
                        <img src="{{ asset('frontend/assets/images/banner/banner-four4.jpg') }}" alt="banner image" class="img-fluid w-100">
                    </div>
                    <div class="swiper-slide inner-slide-element">
                        <img src="{{ asset('frontend/assets/images/banner/banner-four4.jpg') }}" alt="banner image" class="img-fluid w-100">
                    </div>
                    <div class="swiper-slide inner-slide-element">
                        <img src="{{ asset('frontend/assets/images/banner/banner-four4.jpg') }}" alt="banner image" class="img-fluid w-100">
                    </div>
                    <div class="swiper-slide inner-slide-element">
                        <img src="{{ asset('frontend/assets/images/banner/banner-four4.jpg') }}" alt="banner image" class="img-fluid w-100">
                    </div>
                    <div class="swiper-slide inner-slide-element">
                        <img src="{{ asset('frontend/assets/images/banner/banner-four4.jpg') }}" alt="banner image" class="img-fluid w-100">
                    </div>
                    <div class="swiper-slide inner-slide-element">
                        <img src="{{ asset('frontend/assets/images/banner/banner-four4.jpg') }}" alt="banner image" class="img-fluid w-100">
                    </div>
                    <div class="swiper-slide inner-slide-element">
                        <img src="{{ asset('frontend/assets/images/banner/banner-four4.jpg') }}" alt="banner image" class="img-fluid w-100">
                    </div>
                    <div class="swiper-slide inner-slide-element">
                        <img src="{{ asset('frontend/assets/images/banner/banner-four4.') }}" alt="banner image" class="img-fluid w-100">
                    </div>
                    <div class="swiper-slide inner-slide-element">
                        <img src="{{ asset('frontend/assets/images/banner/banner-four4.jpg') }}" alt="banner image" class="img-fluid w-100">
                    </div>
                    <div class="swiper-slide inner-slide-element">
                        <img src="{{ asset('frontend/assets/images/banner/banner-four4.jpg') }}" alt="banner image" class="img-fluid w-100">
                    </div>
                </div>
            </div>
            <div class="container">
                <div class="row position-relative justify-content-center ">
                    <div class="col-lg-10">
                        <div class="banner-content text-center">
                            <h1>Where Real Estate <span> Meets Intelligence</span></h1>
                            <p>
                                Smart. Secure. Profitable. Discover AI-driven property insights, investment scoring, and personalized matching for smarter real estate decisions.
                            </p>
                            <form action="#">
                                <input type="text" name="text" placeholder="Ask AIPropMatchIQ: 'Find 3BHK apartments...!">
                                <a href="" class="btn btn--border">
                                    Search
                                </a>
                            </form>
                            <div class="banner-tags">
                                <span>
                                    Popular Tags:
                                </span>
                                <a href="">Kerala</a>
                                <a href="">Delhi</a>
                                <a href="">Punjab</a>
                                <a href="">Goa</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>


        <section class="feature-two-section py-120">
            <div class="container">
                <div class="row justify-content-center">
                    <div class="col-lg-8">
                        <div class="section-title text-center">
                            <span class="sub-title top-reveal">AIPropMatchIQ</span>
                            <h2 class="top-reveal">Real Estate</h2>
                        </div>
                    </div>
                </div>
                <div class="row justify-content-center row-gap-4 mt-60">
                    <div class="col-lg-3 col-md-6">
                        <div class="feature-section__item top-reveal">
                            <div class="icon">
                                <img src="{{ asset('frontend/assets/images/icon/building.png') }}">
                            </div>
                            <div class="text">
                                <h5>AIPropMatch Engine</h5>
                                <p>Your personal AI Property Browser — analyzing millions of data points to simplify
                                        real estate investment decisions with precision and confidence.</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-6">
                        <div class="feature-section__item top-reveal">
                            <div class="icon">
                                <img src="{{ asset('frontend/assets/images/icon/building.png') }}">
                            </div>
                            <div class="text">
                                <h5>ISS Score</h5>
                                <p>Each property is evaluated using 25+ parameters including location intelligence, appreciation history, rental yield, development potential, and legal health.</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-6">
                        <div class="feature-section__item top-reveal">
                            <div class="icon">
                                <img src="{{ asset('frontend/assets/images/icon/building.png') }}">
                            </div>
                            <div class="text">
                                <h5>Smart Matching</h5>
                                <p>AI matches investors with properties that align perfectly with their budget, investment goals, and risk appetite.</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-6">
                        <div class="feature-section__item top-reveal">
                            <div class="icon">
                                <img src="{{ asset('frontend/assets/images/icon/building.png') }}">
                            </div>
                            <div class="text">
                                <h5>Predictive Insights</h5>
                                <p>Identifies early market shifts, price trends, and future appreciation hotspots before they go mainstream.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>




        <div class="py-60 section-two-bg">
            <div class="text-slide swiper">
                <div class="swiper-wrapper slide-transition">
                    <div class="swiper-slide inner-slide-element">
                        <div class="slide-text">
                            Where Real Estate 
                        </div>
                    </div>
                    <div class="swiper-slide inner-slide-element">
                        <div class="slide-text">
                            <img src="{{ asset('frontend/assets/images/shape/icon.png') }}" alt="icon">
                        </div>
                    </div>
                    <div class="swiper-slide inner-slide-element">
                        <div class="slide-text">
                            Meets Intelligence
                        </div>
                    </div>
                    <div class="swiper-slide inner-slide-element">
                        <div class="slide-text">
                            <img src="{{ asset('frontend/assets/images/shape/icon.png') }}" alt="icon">
                        </div>
                    </div>
                    <div class="swiper-slide inner-slide-element">
                        <div class="slide-text">
                            Where Real Estate 
                        </div>
                    </div>
                    <div class="swiper-slide inner-slide-element">
                        <div class="slide-text">
                            <img src="{{ asset('frontend/assets/images/shape/icon.png') }}" alt="icon">
                        </div>
                    </div>
                    <div class="swiper-slide inner-slide-element">
                        <div class="slide-text">
                            Meets Intelligence
                        </div>
                    </div>
                    <div class="swiper-slide inner-slide-element">
                        <div class="slide-text">
                            <img src="{{ asset('frontend/assets/images/shape/icon.png') }}" alt="icon">
                        </div>
                    </div>
                </div>
            </div>
        </div>



    </main>

@endsection
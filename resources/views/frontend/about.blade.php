@extends('frontend.layouts.master')

@section('content')


    <main>

        <section class="breadcrumb">
            <div class="container">
                <div class="row justify-content-center">
                    <div class="col-lg-8">
                        <div class="breadcrumb__wrapper">
                            <h2 class="breadcrumb__title"> About Us</h2>
                            <ul class="breadcrumb__list">
                                <li class="breadcrumb__item">
                                    <a href="index.html"> Home</a>
                                </li>
                                <li class="breadcrumb__item">
                                    <i class="fa-solid fa-arrow-right"></i>
                                </li>
                                <li class="breadcrumb__item">
                                    <span class="breadcrumb__item-text"> about us</span>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- About Us Section : AIPropMatch -->
        <section class="about-section section-two-bg py-120" id="about">
            <div class="container">
                <div class="row row-gap-4 align-items-center">
                    <!-- Images -->
                    <div class="col-lg-6">
                        <div class="about-section__img position-relative">
                            <div class="image-one mb-3">
                                <figure class="image-effect right-reveal rounded-4 overflow-hidden shadow">
                                    <img src="{{ asset('frontend/assets/images/about/1.jpg') }}" alt="AIPropMatch real estate analytics" class="img-fluid w-100">
                                </figure>
                            </div>
                            <div class="image-two d-grid gap-3">
                                <figure class="image-effect bottom-reveal rounded-4 overflow-hidden shadow">
                                    <img src="{{ asset('frontend/assets/images/about/2.jpg') }}" alt="AI property intelligence platform" class="img-fluid w-100">
                                </figure>
                                <figure class="image-effect top-reveal rounded-4 overflow-hidden shadow">
                                    <img src="{{ asset('frontend/assets/images/about/3.jpg') }}" alt="Verified real estate investment" class="img-fluid w-100">
                                </figure>
                            </div>
                        </div>
                    </div>

                    <!-- Content -->
                    <div class="col-lg-6">
                        <div class="about-section__content ps-lg-4">
                            <div class="section-title mb-4">
                                <span class="sub-title right-reveal">About AIPropMatch</span>

                                <h2 class="right-reveal">
                                    Where Real Estate Meets <span class="text-primary">AI Intelligence</span>
                                </h2>

                                <p class="right-reveal mt-3">
                                    <strong>AIPropMatch.com</strong> is India’s first AI-powered real estate intelligence platform —
                                    an initiative by <strong>Cybpress Innovative Solutions LLP</strong> in collaboration with
                                    <strong>Dolphin Lands</strong>.
                                    <br><br>

                                    Designed to transform real estate from manual discovery into intelligent decision-making,
                                    AIPropMatch connects verified property owners, developers, and investors across India and
                                    global markets.
                                    <br><br>

                                    We go beyond listings and search. AIPropMatch delivers AI-driven insights, data-backed
                                    analytics, and scientific evaluation tools that make property transactions
                                    <strong>transparent, trustworthy, and highly profitable</strong>.
                                </p>

                                
                            </div>



                        </div>
                    </div>


                    <div class="mt-4 right-reveal">
                                    <h5 class="fw-bold mb-3">Powered by the AIPropMatch Engine</h5>
                                    <p class="mb-3">
                                        Your personal AI Property Browser — analyzing millions of data points to simplify
                                        real estate investment decisions with precision and confidence.
                                    </p>

                                      <!-- Key Capabilities -->
                            <div class="row g-3 mb-4">
                                <div class="col-md-6 right-reveal">
                                    <div class="feature-box p-3 rounded-4 shadow-sm h-100">
                                        <h6>✔ Investment Suitability Score (ISS)</h6>
                                        <p class="mb-0">Each property is evaluated using 25+ parameters including location intelligence, appreciation history, rental yield, development potential, and legal health.</p>
                                    </div>
                                </div>
                                <div class="col-md-6 right-reveal">
                                    <div class="feature-box p-3 rounded-4 shadow-sm h-100">
                                        <h6>✔ Smart Matching</h6>
                                        <p class="mb-0">AI matches investors with properties that align perfectly with their budget, investment goals, and risk appetite.</p>
                                    </div>
                                </div>
                                <div class="col-md-6 right-reveal">
                                    <div class="feature-box p-3 rounded-4 shadow-sm h-100">
                                        <h6>✔ Predictive Insights</h6>
                                        <p class="mb-0">Identifies early market shifts, price trends, and future appreciation hotspots before they go mainstream.</p>
                                    </div>
                                </div>
                                <div class="col-md-6 right-reveal">
                                    <div class="feature-box p-3 rounded-4 shadow-sm h-100">
                                        <h6>✔ Automated Due Diligence</h6>
                                        <p class="mb-0">Cross-checks registry data, RERA filings, tax records, zoning layers, and encumbrance indicators to generate a comprehensive Trust Index for every listing.</p>
                                    </div>
                                </div>
                            </div>
    
                    </div>


                <section class="feature-two-section py-120">
                    <div class="container">
                        <div class="row justify-content-center">
                            <div class="col-lg-8">
                                <div class="section-title text-center">
                                    <h2 class="top-reveal">Core Features</h2>
                                </div>
                            </div>
                        </div>

                        <div class="row justify-content-center row-gap-4 mt-60">
                            <div class="col-lg-3 col-md-6">
                                <div class="feature-section__item top-reveal">
                                    <div class="icon">
                                        <img src="{{ asset('frontend/assets/images/icon/badge.png') }}">
                                    </div>
                                    <div class="text">
                                        <h5> AI-Verified Listings</h5>
                                        <p>Every property undergoes document screening, authenticity scoring, and data verification.</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-3 col-md-6">
                                <div class="feature-section__item top-reveal">
                                    <div class="icon">
                                        <img src="{{ asset('frontend/assets/images/icon/investment.png') }}">
                                    </div>
                                    <div class="text">
                                        <h5>AI Investment Dashboard</h5>
                                        <p>Compare properties instantly based on returns, rental yield, liquidity index, market confidence, and risk factors.</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-3 col-md-6">
                                <div class="feature-section__item top-reveal">
                                    <div class="icon">
                                        <img src="{{ asset('frontend/assets/images/icon/verified.png') }}">
                                    </div>
                                    <div class="text">
                                        <h5>Trusted Owner Network</h5>
                                        <p>Only direct owners, verified developers, and validated agents can list.</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-3 col-md-6">
                                <div class="feature-section__item top-reveal">
                                    <div class="icon">
                                        <img src="{{ asset('frontend/assets/images/icon/curriculum-vitae.png') }}">
                                    </div>
                                    <div class="text">
                                        <h5>Portfolio Tracker</h5>
                                        <p>Monitor capital growth, appreciation cycles, rental income patterns, and predictive ROI.</p>
                                    </div>
                                </div>
                            </div>

                            <div class="col-lg-6 col-md-6">
                                <div class="feature-section__item top-reveal">
                                    <div class="icon">
                                        <img src="{{ asset('frontend/assets/images/icon/mission.png') }}">
                                    </div>
                                    <div class="text">
                                        <h5>Our Mission</h5>
                                        <p>To make every property transaction Smart, Secure, and Profitable—
 shifting India’s real estate market from speculative guesswork to science-driven property intelligence, powered by AI, verified data, and investor trust.</p>
                                    </div>
                                </div>
                            </div>

                            <div class="col-lg-6 col-md-6">
                                <div class="feature-section__item top-reveal">
                                    <div class="icon">
                                        <img src="{{ asset('frontend/assets/images/icon/vision.png') }}">
                                    </div>
                                    <div class="text">
                                        <h5> Our Vision</h5>
                                        <p>To build India’s most trusted AI-powered real estate intelligence network —
 empowering investors to make confident, data-backed decisions and connecting every verified property to its ideal buyer or investor.</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>

                </div>
            </div>
        </section>








    </main>
   @endsection
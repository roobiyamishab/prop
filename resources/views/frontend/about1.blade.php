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

        <section class="about-section section-two-bg py-120">
            <div class="container">
                <div class="row row-gap-4">
                    <div class="col-lg-6 align-self-center">
                        <div class="about-section__img">
                            <div class="image-one">
                                <figure class="image-effect right-reveal">
                                    <img src="{{ asset('frontend/assets/images/about/1.jpg') }}" alt="about images" class="img-fluid w-100">
                                </figure>
                            </div>
                            <div class="image-two d-grid">
                                <figure class="image-effect bottom-reveal">
                                    <img src="{{ asset('frontend/assets/images/about/2.jpg') }}" alt="about images" class="img-fluid w-100">
                                </figure>
                                <figure class="image-effect top-reveal">
                                    <img src="{{ asset('frontend/assets/images/about/3.jpg') }}" alt="about images" class="img-fluid w-100">
                                </figure>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6 align-self-center">
                        <div class="about-section__content">
                            <div class="section-title">
                                <span class="sub-title right-reveal">About Us</span>
                                <h2 class="right-reveal">Welcome to Lorem Ipsum is simply dummy text</h2>
                                <p class="right-reveal">Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book
                                    <br>
                                    <br>
                                    Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>




    </main>

   @endsection
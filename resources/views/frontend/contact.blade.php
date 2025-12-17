@extends('frontend.layouts.master')

@section('content')

    <main>

        <section class="breadcrumb">
            <div class="container">
                <div class="row justify-content-center">
                    <div class="col-lg-8">
                        <div class="breadcrumb__wrapper">
                            <h2 class="breadcrumb__title"> Contact Us</h2>
                            <ul class="breadcrumb__list">
                                <li class="breadcrumb__item">
                                    <a href="index.html"> Home</a>
                                </li>
                                <li class="breadcrumb__item">
                                    <i class="fa-solid fa-arrow-right"></i>
                                </li>
                                <li class="breadcrumb__item">
                                    <span class="breadcrumb__item-text"> Contact us</span>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <section class="contact-section py-120">
            <div class="container">
                <div class="row row-gap-4 justify-content-center">
                    <div class="col-md-4 col-sm-6">
                        <div class="contact-info-item top-reveal">
                            <i class="fa-solid fa-phone-volume"></i>
                            <h4>Phone:</h4>
                            <p>
                                <a href="tel:+91">+91 9447 9447 35</a>
                            </p>
                        </div>
                    </div>
                    <div class="col-md-4 col-sm-6">
                        <div class="contact-info-item top-reveal">
                            <i class="fa-solid fa-location-dot"></i>
                            <h4>Address:</h4>
                            <p>
                                AIPropMatch
                            </p>
                        </div>
                    </div>
                    <div class="col-md-4 col-sm-6">
                        <div class="contact-info-item top-reveal">
                            <i class="fa-solid fa-envelope"></i>
                            <h4>Email:</h4>
                            <p>
                                <a href=""><span>hello@aipropmatch.com</span></a>
                            </p>
                        </div>
                    </div>
                </div>
                <div class="row pt-120">
                    <div class="col-md-12">
                        <div class="touch-contact">
                            <div class="touch-left">
                                <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3913.0830746541183!2d75.78664157407663!3d11.255299050180252!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3ba6594683df7bfd%3A0x2aac07f6ccfc556!2sCYBPRESS%20INNOVATIVE%20SOLUTIONS!5e0!3m2!1sen!2sin!4v1765260265919!5m2!1sen!2sin" width="600" height="450" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
                            </div>
                            <div class="touch-right">
                                <form action="https://photoclerks.com/html/pixgix-update/pixgix/index.html">
                                    <input type="text" class="form-control" placeholder="Full Name*">
                                    <input type="email" class="form-control" placeholder="E-Mail*">
                                    <input type="text" class="form-control" placeholder="Phone*">
                                    <textarea name="messages" class="form-control"
                                        placeholder="Type your message"></textarea>
                                    <div class="button">
                                        <button type="submit" class="btn btn--base">
                                            Send Me Message
                                            <i class="flaticon-right-arrow"></i>
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>


  
    </main>

   @endsection
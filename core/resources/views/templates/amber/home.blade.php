@extends($activeTemplate . '.layouts.app')

@section('styles')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />
@endsection

@section('content')
    <!-- Custom Slider -->
    <div class="swiper HeroSlider">
        <div class="swiper-wrapper">
            @foreach (array_slice(getImages(), 0, 3) as $index => $slider)
                @if ($slider)
                    <div class="swiper-slide">
                        <img src="{{ url($slider) }}" alt="{{ __('alts.slider_img_' . ($index + 1)) }}" />
                    </div>
                @endif
            @endforeach
        </div>
        <div class="slide-content container">
            <div class="row justify-content-center">
                <div class="col-12 col-md-4 d-flex justify-content-center align-items-center mb-3 mb-md-0">
                    <div class="caption-icon text-center">
                        <img src="{{ asset('assets/' . $activeTemplatePath . '/images/icons/1.svg') }}" class="img-fluid"
                            alt="iptv for all devices" />
                        <span class="text-uppercase">{{ __('general.for_all_devices') }}</span>
                    </div>
                </div>
                <div class="col-12 col-md-6 col-lg-4 d-flex justify-content-center align-items-center">
                    <div class="caption-icon text-center">
                        <img src="{{ asset('assets/' . $activeTemplatePath . '/images/icons/2.svg') }}"
                            class="img-fluid mb-2" alt="iptv free trial" />
                        <span class="text-uppercase">{{ __('general.channels_movies_sports') }}</span>
                    </div>
                </div>
            </div>
            <h1 class="pb-0">{{ __('general.best_iptv_subscription') }}</h1>
            <h1 class="pt-0">{{ __('general.best_iptv_secondary') }}</h1>
            <p class="d-none d-md-block quote">{{ __('general.service_description') }}
            </p>
            <div class="hero-button">
                <a
                    href="https://wa.me/{{ whatsapp() }}?text={{ urlencode(whatsappText()) }}"><span>{{ __('buttons.iptv_free_trial') }}</span></a>
                <a href="{{ route('channels.index') }}"><span>{{ __('buttons.more_details') }}</span></a>
            </div>
        </div>
        <!-- Indicators -->
        <div class="indicators">
            <span class="indicator active" data-slide-index="0"></span>
            <span class="indicator" data-slide-index="1"></span>
            <span class="indicator" data-slide-index="2"></span>
        </div>
    </div>

    <section class="three-step">
        <div class="container">
            <div class="text-center">
                <div class="sec-title">“ {{ __('general.watch_in') }} <span>{{ __('general.three_step') }} ”</span></div>
                <p class="mini-title d-none d-sm-block">
                    {{ __('general.three_step_description') }}
                </p>
            </div>
            <div class="steps d-flex flex-column flex-md-row justify-content-center mb-1 flex-wrap">
                <div class="col-12 col-lg-4 mb-3 mb-lg-0 corner">
                    <img src="{{ asset('assets/' . $activeTemplatePath . '/images/steps/1.webp') }}" class="img-fluid"
                        alt="{{ __('alts.three_step_alt_1') }}" />
                    <div class="step-content">
                        <div class="d-flex justify-content-center align-items-center">
                            <h1>1</h1>
                            <span>{{ __('general.get_subscription') }}</span>
                        </div>
                        <small>
                            {{ __('general.get_subscription_des') }}
                        </small>
                    </div>
                </div>

                <div class="col-12 col-lg-4 mb-3 mb-lg-0 corner">
                    <img src="{{ asset('assets/' . $activeTemplatePath . '/images/steps/2.webp') }}" class="img-fluid"
                        alt="{{ __('alts.three_step_alt_2') }}" />
                    <div class="step-content">
                        <div class="d-flex justify-content-center align-items-center">
                            <h1>2</h1>
                            <span>{{ __('general.get_account') }} &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; </span>
                        </div>
                        <small>
                            {{ __('general.get_account_desc') }}
                        </small>
                    </div>
                </div>

                <div class="col-12 col-lg-4 mb-3 mb-lg-0 corner">
                    <img src="{{ asset('assets/' . $activeTemplatePath . '/images/steps/3.webp') }}" class="img-fluid"
                        alt="{{ __('alts.three_step_alt_3') }}" />
                    <div class="step-content">
                        <div class="d-flex justify-content-center align-items-center">
                            <h1>3</h1>
                            <span>{{ __('general.enjoy_watching') }}</span>
                        </div>
                        <small>
                            {{ __('general.enjoy_watching_desc') }}
                        </small>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <section class="plans" id="pricing">
        <div class="container">
            <div class="text-center">
                <div class="plan-title">“ {{ __('general.plan_title') }} ”</div>
                <p class="sec-description d-none d-md-block">
                    {{ __('general.plan_desc') }}
                </p>
            </div>
            <div class="d-flex justify-content-center justify-content-lg-start align-items-center flex-wrap">
                @foreach ($plans as $plan)
                    <div class="col-md-6 col-lg-4 col-xxl-3 d-flex justify-content-center align-items-center">
                        <div class="pricing-card">
                            <div class="pricing-card-overlay"></div>
                            @if ($plan->best_plan == 1)
                                <div class="pricing-bar"></div>
                                <span class="pricing-bar-text">✦ {{ __('general.best_plan') }} ✦</span>
                            @endif
                            <div class="pricing-header">
                                <h2 class="plan-name">iptv</h2>
                            </div>
                            <div class="pricing-body">
                                <div class="text-center">
                                    <h3 class="pricing-title">{{ $plan->duration_type }}</h3>
                                    <h3 class="pricing-pricing">
                                        {{ $plan->price }}<span>{{ getCurrency($visitor_country) }}</span>
                                    </h3>
                                </div>
                                <div class="pricing-features">
                                    <ul class="px-0">
                                        @foreach (explode("\n", $plan->description) as $feature)
                                            <li>{{ trim($feature) }}</li>
                                        @endforeach
                                    </ul>
                                </div>

                                <div class="pricing-button">
                                    <a
                                        href="https://wa.me/{{ whatsapp() }}?text={{ urlencode(whatsappText()) }}">{{ __('buttons.get_now') }}</a>
                                </div>
                            </div>
                            <div class="pricing-footer"></div>
                        </div>
                    </div>
                @endforeach


            </div>
        </div>
    </section>
    <section class="best">
        <div class="container">
            <div class="text-center">
                <h1 class="sec-title text-white">{!! __('general.best_sec_title') !!}</h1>
                <p class="sec-description d-none d-md-block">
                    {{ __('general.best_sec_description') }}
                </p>
            </div>
            <div class="our-features d-block d-md-flex justify-content-center flex-wrap">
                <div class="col-12 col-lg-6 col-xl-4 parallelogram-wrapper">
                    <svg class="parallelogram" viewBox="0 0 412 160" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path
                            d="M50.9733 13.219C53.8089 5.57368 61.1021 0.5 69.2563 0.5H401.625C408.244 0.5 412.834 7.09822 410.533 13.3036L361.027 146.781C358.191 154.426 350.898 159.5 342.744 159.5H10.3746C3.75616 159.5 -0.834038 152.902 1.46748 146.696L50.9733 13.219Z"
                            fill="white" stroke="white" />
                    </svg>

                    <div class="step-content">
                        <div class="d-flex justify-content-start align-items-center pb-0 pb-md-2">
                            <div class="step-icon-box">
                                <svg viewBox="0 0 39 54" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path
                                        d="M34.378 20.4953C34.2319 20.3129 34.0548 20.3487 33.962 20.386C33.8842 20.4174 33.706 20.5178 33.7307 20.7695C33.7603 21.0716 33.7769 21.3797 33.7801 21.6852C33.7933 22.9528 33.2832 24.1945 32.3807 25.0922C31.4839 25.984 30.3045 26.4645 29.0486 26.4506C27.3331 26.4288 25.9102 25.5368 24.9338 23.8712C24.1264 22.4939 24.4813 20.7175 24.857 18.8366C25.0768 17.7358 25.3042 16.5973 25.3042 15.5139C25.3042 7.07756 19.6147 2.21041 16.2233 0.0600115C16.1532 0.0156093 16.0864 0 16.0273 0C15.9311 0 15.8549 0.0413436 15.8174 0.066656C15.7446 0.115804 15.6281 0.227812 15.6655 0.426092C16.9618 7.28807 13.0954 11.4152 9.00184 15.7845C4.78241 20.2883 0 25.3931 0 34.5996C0 45.2971 8.73056 54 19.462 54C28.2978 54 36.0882 47.8592 38.4065 39.0667C39.9875 33.0716 38.3308 25.4348 34.378 20.4953ZM19.9475 49.859C17.2603 49.9811 14.7047 49.0204 12.7528 47.16C10.8218 45.3193 9.71421 42.7506 9.71421 40.1125C9.71421 35.1617 11.6132 31.5272 16.7207 26.7023C16.8043 26.6233 16.8899 26.5983 16.9645 26.5983C17.0321 26.5983 17.0907 26.6188 17.131 26.6381C17.216 26.679 17.3556 26.78 17.3368 26.9985C17.1542 29.1169 17.1574 30.8751 17.3461 32.2247C17.8286 35.6719 20.3601 37.9881 23.6458 37.9881C25.2568 37.9881 26.7913 37.3838 27.9666 36.2864C28.012 36.2429 28.0674 36.2112 28.1279 36.194C28.1884 36.1767 28.2522 36.1745 28.3138 36.1876C28.3911 36.2042 28.4947 36.2515 28.549 36.3819C29.0363 37.5548 29.2854 38.7999 29.2892 40.0823C29.3047 45.2423 25.1141 49.6282 19.9475 49.859Z" />
                                </svg>
                            </div>

                            <span class="ms-1">{{ __('general.best_sec_step_one') }}</span>
                        </div>
                        <small>
                            {{ __('general.best_sec_step_one_desc') }}
                        </small>
                    </div>
                </div>
                <div class="col-12 col-lg-6 col-xl-4 parallelogram-wrapper">
                    <svg class="parallelogram" viewBox="0 0 412 160" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path
                            d="M50.9733 13.219C53.8089 5.57368 61.1021 0.5 69.2563 0.5H401.625C408.244 0.5 412.834 7.09822 410.533 13.3036L361.027 146.781C358.191 154.426 350.898 159.5 342.744 159.5H10.3746C3.75616 159.5 -0.834038 152.902 1.46748 146.696L50.9733 13.219Z"
                            fill="white" stroke="white" />
                    </svg>

                    <div class="step-content">
                        <div class="d-flex justify-content-start align-items-center pb-0 pb-md-2">
                            <div class="step-icon-box">
                                <svg class="pt-0 pb-2" viewBox="0 0 49 51" fill="none"
                                    xmlns="http://www.w3.org/2000/svg">
                                    <path
                                        d="M39.2537 9.98828H8.78815C6.45815 9.99076 4.22429 10.9175 2.57673 12.565C0.92917 14.2126 0.00248137 16.4464 0 18.7764V42.2115C0.00248137 44.5415 0.92917 46.7754 2.57673 48.4229C4.22429 50.0705 6.45815 50.9972 8.78815 50.9996H39.2537C41.5837 50.9972 43.8176 50.0705 45.4651 48.4229C47.1127 46.7754 48.0394 44.5415 48.0419 42.2115V18.7764C48.0394 16.4464 47.1127 14.2126 45.4651 12.565C43.8176 10.9175 41.5837 9.99076 39.2537 9.98828ZM31.0116 33.3179L23.953 38.0213C23.3355 38.4341 22.6175 38.6713 21.8757 38.7076C21.1339 38.744 20.3961 38.5781 19.7413 38.2277C19.0864 37.8773 18.5391 37.3555 18.1578 36.7182C17.7764 36.0808 17.5755 35.3519 17.5763 34.6092V26.3764C17.5763 25.6773 17.7551 24.9898 18.0956 24.3791C18.4361 23.7685 18.927 23.2551 19.5218 22.8876C20.1166 22.5202 20.7954 22.3108 21.4939 22.2795C22.1923 22.2482 22.8871 22.396 23.5124 22.7088L30.5687 26.2382C31.2112 26.5593 31.758 27.0435 32.1546 27.6423C32.5511 28.2412 32.7834 28.9337 32.8283 29.6505C32.8731 30.3674 32.7289 31.0834 32.4101 31.727C32.0913 32.3706 31.609 32.9192 31.0116 33.3179Z" />
                                    <path
                                        d="M24.0198 14.0898C23.7119 14.0907 23.4069 14.0304 23.1225 13.9125C22.8381 13.7947 22.5799 13.6215 22.3629 13.4032L12.9889 4.02916C12.7651 3.81298 12.5865 3.55439 12.4637 3.26847C12.3409 2.98255 12.2762 2.67504 12.2735 2.36387C12.2708 2.0527 12.3301 1.74411 12.4479 1.4561C12.5658 1.16809 12.7398 0.906435 12.9598 0.686397C13.1799 0.466359 13.4415 0.292346 13.7295 0.174513C14.0175 0.0566793 14.3261 -0.00261549 14.6373 8.84831e-05C14.9485 0.00279246 15.256 0.0674408 15.5419 0.190262C15.8278 0.313082 16.0864 0.491616 16.3026 0.715445L24.0198 8.43261L31.7369 0.715445C31.9531 0.491616 32.2117 0.313082 32.4976 0.190262C32.7835 0.0674408 33.0911 0.00279246 33.4022 8.84831e-05C33.7134 -0.00261549 34.022 0.0566793 34.31 0.174513C34.598 0.292346 34.8597 0.466359 35.0797 0.686397C35.2997 0.906435 35.4737 1.16809 35.5916 1.4561C35.7094 1.74411 35.7687 2.0527 35.766 2.36387C35.7633 2.67504 35.6987 2.98255 35.5758 3.26847C35.453 3.55439 35.2745 3.81298 35.0506 4.02916L25.6766 13.4032C25.4596 13.6215 25.2014 13.7947 24.917 13.9125C24.6326 14.0304 24.3276 14.0907 24.0198 14.0898Z" />
                                </svg>
                            </div>
                            <span class="ms-1">{{ __('general.best_sec_step_two') }}</span>
                        </div>
                        <small>
                            {{ __('general.best_sec_step_two_desc') }}
                        </small>
                    </div>
                </div>
                <div class="col-12 col-lg-6 col-xl-4 parallelogram-wrapper">
                    <svg class="parallelogram" viewBox="0 0 412 160" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path
                            d="M50.9733 13.219C53.8089 5.57368 61.1021 0.5 69.2563 0.5H401.625C408.244 0.5 412.834 7.09822 410.533 13.3036L361.027 146.781C358.191 154.426 350.898 159.5 342.744 159.5H10.3746C3.75616 159.5 -0.834038 152.902 1.46748 146.696L50.9733 13.219Z"
                            fill="white" stroke="white" />
                    </svg>
                    <div class="step-content">
                        <div class="d-flex justify-content-start align-items-center pb-0 pb-md-2">
                            <div class="step-icon-box">
                                <svg class="pt-2" viewBox="0 0 45 45" fill="none"
                                    xmlns="http://www.w3.org/2000/svg">
                                    <path
                                        d="M34.7727 0H10.2273C7.51583 0.00324787 4.91637 1.0818 2.99909 2.99907C1.0818 4.91633 0.00324789 7.51578 0 10.2272V26.5907C0.00297516 28.9475 0.818366 31.2312 2.30874 33.0569C3.79912 34.8826 5.87334 36.1386 8.18182 36.6134V42.9543C8.18176 43.3246 8.28224 43.6879 8.47253 44.0056C8.66282 44.3233 8.93578 44.5834 9.2623 44.7581C9.58881 44.9328 9.95663 45.0156 10.3265 44.9976C10.6964 44.9796 11.0545 44.8616 11.3625 44.6561L23.1136 36.8179H34.7727C37.4842 36.8147 40.0836 35.7361 42.0009 33.8189C43.9182 31.9016 44.9967 29.3022 45 26.5907V10.2272C44.9967 7.51578 43.9182 4.91633 42.0009 2.99907C40.0836 1.0818 37.4842 0.00324787 34.7727 0ZM30.6818 24.5453H14.3182C13.7757 24.5453 13.2554 24.3298 12.8718 23.9462C12.4882 23.5626 12.2727 23.0423 12.2727 22.4998C12.2727 21.9574 12.4882 21.4371 12.8718 21.0535C13.2554 20.6699 13.7757 20.4544 14.3182 20.4544H30.6818C31.2243 20.4544 31.7446 20.6699 32.1282 21.0535C32.5118 21.4371 32.7273 21.9574 32.7273 22.4998C32.7273 23.0423 32.5118 23.5626 32.1282 23.9462C31.7446 24.3298 31.2243 24.5453 30.6818 24.5453ZM34.7727 16.3635H10.2273C9.68478 16.3635 9.16452 16.148 8.78092 15.7644C8.39732 15.3808 8.18182 14.8606 8.18182 14.3181C8.18182 13.7756 8.39732 13.2553 8.78092 12.8717C9.16452 12.4881 9.68478 12.2726 10.2273 12.2726H34.7727C35.3152 12.2726 35.8355 12.4881 36.2191 12.8717C36.6027 13.2553 36.8182 13.7756 36.8182 14.3181C36.8182 14.8606 36.6027 15.3808 36.2191 15.7644C35.8355 16.148 35.3152 16.3635 34.7727 16.3635Z" />
                                </svg>
                            </div>
                            <span class="ms-1">{{ __('general.best_sec_step_three') }}</span>
                        </div>
                        <small>
                            {{ __('general.best_sec_step_two_three') }}
                        </small>
                    </div>
                </div>
            </div>
            <div class="col-12">
                <img src="{{ url(getImages()[3]) }}" class="img-fluid" alt="{{ __('alts.all_device') }}" />
            </div>
        </div>
    </section>
    <section class="social" id="social">
        <div class="container">
            <div class="row text-center">
                @foreach (getSocialMediaLinks() as $social)
                    <div class="col-2 share-icon">
                        <a href="{{ $social['url'] ?? '#' }}" target="_blank" rel="noopener noreferrer"
                            class="d-flex flex-column align-items-center text-decoration-none"
                            aria-label="{{ $social['name'] ?? '' }}">
                            <img src="{{ url($social['icon'] ?? '') }}" class="img-fluid"
                                alt="{{ $social['name'] ?? '' }}">
                            <span class="d-none d-md-block">{{ $social['name'] ?? '' }}</span>
                        </a>
                    </div>
                @endforeach
            </div>

        </div>
    </section>

    <section class="our-program" id="programSection">
        <div class="row align-items-center mx-0">
            <div class="col-12 col-xl-6 px-0 mb-3">
                <div class="d-flex justify-content-center align-items-center d-xl-block program-info">
                    <h1 class="sec-title text-xl-start" id="title">
                        {!! __('general.program_sec_title') !!}
                    </h1>
                    <p class="d-none d-xl-block" id="description"></p>
                    <a href="#" class="btn-explore d-none d-xl-flex">{{ __('buttons.explore') }}</a>
                </div>
            </div>
            <div class="col-12 col-xl-6 px-0 d-flex align-items-center" style="height: 455px">
                <div class="program-slider swiper ProgramSwiper">
                    <div class="swiper-wrapper d-flex align-items-center" id="slider">
                        @foreach ($sliders as $slider)
                            <div class="swiper-slide" data-bg="{{ url($slider->slider_background) }}"
                                data-title="{{ $slider->title }}" data-subtitle="{{ $slider->subtitle }}">
                                <img src="{{ url($slider->img) }}" alt="{{ $slider->title }}" />
                            </div>
                        @endforeach
                    </div>

                </div>
            </div>
            <div class="col-xl-6"></div>
            <div class="col-6 px-0 mt-4 mx-auto mx-xl-0">
                <div class="program-slider-button d-flex gap-3 justify-content-center justify-content-xl-start">
                    <div class="prev"></div>
                    <div class="next"></div>
                </div>
            </div>
        </div>
    </section>

    <section class="partners">
        <div class="container">
            <div class="text-center px-5">
                <div class="d-flex flex-column align-items-center">
                    <img src="{{ url(getImages()[4]) }}"
                        class="img-fluid partner-logo mt-3 mb-lg-3" alt="Logo" />

                    <!-- Partner Title -->
                    <h1 class="sec-title">
                        {!! __('general.partners_sec_title') !!}

                    </h1>
                    <p class="mb-0 sec-description text-black">
                        "{{ __('general.partners_sec_desc') }}"
                    </p>
                </div>

                <!-- Partner Logos -->
                <div class="mx-auto mt-4 mt-lg-5" style="max-width: 992px">
                    <div class="d-flex justify-content-center flex-wrap align-items-center partners-logo my-4 mx-auto">
                        @foreach ($partners->take(6) as $partner)
                            <div class="col-auto">
                                <img src="{{ asset($partner->logo) }}" alt="{{ $partner->alt ?? $partner->name }}"
                                    class="img-fluid" />
                            </div>
                        @endforeach
                    </div>
                    <div class="d-flex flex-wrap justify-content-center align-items-center partners-logo mx-auto">
                        @foreach ($partners->skip(6) as $partner)
                            <div class="col-auto">
                                <img src="{{ asset($partner->logo) }}" alt="{{ $partner->alt ?? $partner->name }}"
                                    class="img-fluid" />
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="faq" id="faqs">
        <div class="container">
            <h1 class="sec-title">{!! __('general.faq_sec_title') !!}</h1>
            <div class="faq-list mt-5">
                <div class="accordion" id="accordionExample">
                    @foreach ($faqs as $index => $faq)
                        <div class="accordion-item">
                            <h2 class="accordion-header" id="heading{{ $index }}">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                    data-bs-target="#collapse{{ $index }}" aria-expanded="false"
                                    aria-controls="collapse{{ $index }}">
                                    {{ $faq->question }}
                                </button>
                            </h2>
                            <div id="collapse{{ $index }}" class="accordion-collapse collapse"
                                aria-labelledby="heading{{ $index }}">
                                <div class="accordion-body pt-0">
                                    {{ $faq->answer }}
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </section>
@endsection

@section('scripts')
    <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            var swiper = new Swiper(".HeroSlider", {
                loop: true,
                speed: 500,
                autoplay: {
                    delay: 3000,
                    disableOnInteraction: false
                },
                on: {
                    slideChange: function() {
                        updateIndicators(this.realIndex);
                    },
                },
            });

            function updateIndicators(activeIndex) {
                document.querySelectorAll(".indicator").forEach((el, index) => {
                    el.classList.toggle("active", index === activeIndex);
                });
            }
            document.querySelectorAll(".indicator").forEach((indicator, index) => {
                indicator.addEventListener("click", function() {
                    swiper.slideToLoop(index);
                });
            });
        });
    </script>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const section = document.getElementById("programSection");
            const title = document.getElementById("title");
            const description = document.getElementById("description");
            const programInfo = document.querySelector(".program-info");
            let isTransitioning = false;

            var swiper = new Swiper(".ProgramSwiper", {
                slidesPerView: "auto",
                spaceBetween: 34,
                loop: true,
                freeMode: false,
                speed: 500,
                preventInteractionOnTransition: true,
                navigation: {
                    nextEl: ".next",
                    prevEl: ".prev"
                },
                autoplay: {
                    delay: 3000,
                    disableOnInteraction: false
                },
                on: {
                    init: function() {
                        setTimeout(() => {
                            updateContent(this.slides[this.realIndex]);
                            updateImgHeight();
                            showProgramInfo();
                        }, 0);
                    },
                    slideChangeTransitionStart: function() {
                        isTransitioning = true;
                        hideProgramInfo();
                    },
                    slideChangeTransitionEnd: function() {
                        isTransitioning = false;
                        updateContent(this.slides[this.realIndex]);
                        updateImgHeight();
                        showProgramInfo();
                    },
                },
            });

            function updateContent(activeSlide) {
                if (!activeSlide) return;
                const bgImage = activeSlide.dataset.bg;
                const titleText = activeSlide.dataset.title;
                const subtitleText = activeSlide.dataset.subtitle;

                section.style.backgroundImage = `url('${bgImage}')`;
                title.innerHTML = titleText;
                description.textContent = subtitleText;
            }

            function updateImgHeight() {
                const slides = document.querySelectorAll("section.our-program .program-slider .swiper-slide img");
                slides.forEach((img) => {
                    img.style.height = "";
                });
                const activeSlide = document.querySelector(
                    "section.our-program .program-slider .swiper-slide-active"
                );
                if (activeSlide) {
                    const activeImg = activeSlide.querySelector("img");
                    if (activeImg) {
                        activeImg.style.height = "455px";
                    }
                }
            }

            function showProgramInfo() {
                if (isTransitioning) return;
                programInfo.classList.remove("hiding");
                programInfo.classList.add("visible");
            }

            function hideProgramInfo() {
                programInfo.classList.remove("visible");
                programInfo.classList.add("hiding");
            }

            document.querySelectorAll('.prev, .next').forEach(button => {
                button.addEventListener('click', function(e) {
                    if (isTransitioning) {
                        e.preventDefault();
                        e.stopPropagation();
                    }
                });
            });
        });
    </script>
@endsection

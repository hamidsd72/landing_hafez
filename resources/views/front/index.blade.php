@extends('front.layouts.app')

@section('main_header')
    <div class=clear></div>
    <div class="haftseen wow fadeInUp"></div>
    <div class=clear></div>

    <div class="logo logo-mobile" >
        <a href="https://hadafhafez.com/" alt="شرکت مشاور سرمایه گذاری هدف حافظ">
            <img style="width: 200px;" src="{{url('assets/img/hafez _ hadaf-02.png')}}" alt="شرکت مشاور سرمایه گذاری هدف حافظ" height=50>
        </a>
    </div>
    <div class=clear></div>
    <section class="header-title" style="margin-top: 18%">
        <h1 class="wow swing">
            این عیدی رو هدف حافظ میده
        </h1>
        <p class="about wow fadeInUp">
            به پاس همراهی ارزشمند شما، و به میمنت نوروز باستانی و سال جدید، تلاش کردیم متفاوت از گذشته و با رعایت پروتکل های بهداشتی، هدیه‌ای ناقابل را تقدیم حضور کنیم.
        </p>
        <div class="countdown" style="background: transparent;color: #a57d24;">
            <div id="countdown" class="login-txt">برای دریافت هدیه، شماره همراه خود را وارد فرمایید</div>
            <div class="clear"></div>
        </div>

        <form method="post" action="{{route('front-checkphone')}}" id="sendnumber">
            @csrf
            <input style="" name="mobile" class="in-mobile" placeholder="۰۹۰۰۰۰۰۰۰۰">
            <input class="countdown btn-login" style="width: 200px;" id="countdown" type="Submit" value="دریافت هدیه حافظ">

            <div class="clear"></div>

      {{--  <div class="countdown" style="width: 200px;">

--}}{{--
            <div id="countdown" onclick="function(){document.getElementById('sendnumber').submit();}"> دریافت هدیه حافظ</div>
--}}{{--

        </div>--}}
        </form>
        <a class="help-Link" href="{{asset('infographic-hadaf.jpg')}}" target="_blank">راهنمای دریافت هدیه</a>
    </section>

@endsection

@section('main')
    <section class="offer-products" style="padding-top: 100px;">
        <div class="container">
            <div class="title">
                <div class="title-border"></div>
                <h2 class="wow tada">هدایای ما</h2>
            </div>
            <div class="product-list">
                @foreach($Gifts as $Gift)
                    <a class="product-box wow pulse">
                        <div class=product-img>
                            @if($Gift->photo != null && $Gift->photo != '')
                                <img src="{{url($Gift->photo)}}">
                            @endif
                        </div>
                        <div class=product-title>
                            <h3>{{$Gift->title}}</h3>
                        </div>
                        <div class=product-price>

                        </div>
                    </a>
                @endforeach
            </div>
        </div>
    </section>
    <section class="why-us gray-background">
        <div class=container>
            <a href="https://castbox.fm/channel/%D8%A8%D9%88%D8%B1%D8%B3-%D9%BE%D9%84%D8%A7%D8%B3-id2944342">
            <div class="fes-day wow slideInRight" style="background: #ff8d47;">
                <h4>
                    <i class="mic"></i>
                    <span>تحلیل روزانه بازار بورس</span>
                </h4>

            </div>
            </a>
            <a href="https://hadafhafez.com/">
            <div class="free-delivery wow slideInUp">
                <h4>آخرین اخبار بازار سرمایه</h4>
            </div>
            </a>

            <a href="https://www.instagram.com/hafezplus/">
            <div class="quality-guarantee wow slideInLeft">
                <i class="instagramm"></i>
                <h4>ما را در اینیستاگرام دنبال کنید</h4>
            </div>
            </a>
        </div>
    </section>

    <section class="coupon" style="padding-top: 80px;">
        <div class=container>
            <div class="social-network wow zoomIn">
                <h2 style="color:#000;" class=section-title>نوبهار است ، درآن کوش که خوشدل باشی</h2>
            </div>
        </div>
    </section>


@endsection

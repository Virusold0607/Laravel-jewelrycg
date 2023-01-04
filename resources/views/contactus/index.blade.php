<x-app-layout page-title="Contact Us">
    @section('css')
        <link rel="stylesheet" href="{{ asset('assets/css/client/contact.css') }}">
    @endsection
    <div class="container-contact100">
        <div class="wrap-contact100">
            <form class="contact100-form validate-form">
<span class="contact100-form-title">
Send Us A Message
</span>
                <label class="label-input100" for="first-name">Tell us your name *</label>
                <div class="wrap-input100 rs1-wrap-input100 validate-input" data-validate="Type first name">
                    <input id="first-name" class="input100" type="text" name="first-name" placeholder="First name">
                    <span class="focus-input100"></span>
                </div>
                <div class="wrap-input100 rs2-wrap-input100 validate-input" data-validate="Type last name">
                    <input class="input100" type="text" name="last-name" placeholder="Last name">
                    <span class="focus-input100"></span>
                </div>
                <label class="label-input100" for="email">Enter your email *</label>
                <div class="wrap-input100 validate-input" data-validate="Valid email is required: ex@abc.xyz">
                    <input id="email" class="input100" type="text" name="email" placeholder="Eg. example@email.com">
                    <span class="focus-input100"></span>
                </div>
                <label class="label-input100" for="phone">Enter phone number</label>
                <div class="wrap-input100">
                    <input id="phone" class="input100" type="text" name="phone" placeholder="Eg. +1 800 000000">
                    <span class="focus-input100"></span>
                </div>
                <label class="label-input100" for="message">Message *</label>
                <div class="wrap-input100 validate-input" data-validate="Message is required">
                    <textarea id="message" class="input100" name="message" placeholder="Write us a message"></textarea>
                    <span class="focus-input100"></span>
                </div>
                <div class="container-contact100-form-btn">
                    <button class="contact100-form-btn">
                        Send Message
                    </button>
                </div>
            </form>
            <div class="contact100-more d-flex flex-column align-items-center justify-content-center" style="background-image: url({{asset("assets/img/contact_bg.png")}});">
                <div class="flex-w size1 p-b-47">
                    <div class="txt1 p-r-25">
                        <i class="bi bi-geo-alt"></i>
                    </div>
                    <div class="flex-col size2">
                    <span class="txt1 p-b-20">
                    Address
                    </span>
                                            <span class="txt2">
                    Mada Center 8th floor, 379 Hudson St, New York, NY 10018 US
                    </span>
                    </div>
                </div>
                <div class="d-flex size1 p-b-47">
                    <div class="txt1 p-r-25">

                        <i class="bi bi-telephone"></i>
                    </div>
                    <div class="flex-col size2">
                        <span class="txt1 p-b-20">
                    Lets Talk
                    </span>
                                            <span class="txt3">
                    +1 800 1236879
                    </span>
                    </div>
                </div>
                <div class="d-flex size1 p-b-47">
                    <div class="txt1 p-r-25">
                        <i class="bi bi-envelope"></i>

                    </div>
                    <div class="flex-col size2">
<span class="txt1 p-b-20">
General Support
</span>
                        <span class="txt3">
contact@example.com
</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

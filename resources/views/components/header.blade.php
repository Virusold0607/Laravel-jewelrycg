<header class="navbar navbar-expand-lg">
    <style>
        header{
            z-index:100000;
        }
        .notification-badge-container i {
            position: relative;
            font-size: 24px;
        }

        .notification-badge {
            position: absolute;
            top: 0;
            right: 0;
            font-size: 10px;
            font-style: normal;
            width: 15px;
            height: 15px;
            display: flex;
            justify-content: center;
            align-items: center;
            border-radius: 100px;
            color: white;
            background-color: red;
            transform: translate(50%, -50%);
        }

        .notification-container {
            display: flex;
            flex-direction: row;
            gap: 10px;
            width: 300px;
            padding: 5px;
        }

        .notification-thumb {
            width: 50px;
            height: 50px;
            border-radius: 1000px;
            border: 1px solid gray;
        }

        .notification-body {
            flex: 1 1 0%;
            display: flex;
            flex-direction: column;
            height: 50px;
            gap: 5px;
        }

        .notification-message {
            flex: 1 1 0%;
            overflow: hidden;
            padding: 0;
            margin: 0;
        }

        .notification-time {
            padding: 0;
            margin: 0;
            font-size: 10px;
        }
    </style>
    <nav class="container">
        <a class="col-auto navbar-brand fw-800" href="{{ route('index') }}">
                <!--
                <img src="{{ asset('img/logo.png') }}" width="50" height="50" alt="logo">
                <img class="logo" src="https://districtgurus.com/public/uploads/all/SC008HOLHmfOeB8E3SxNDONHI7nad1YJcmSl0ds9.png" data-src="https://districtgurus.com/public/uploads/all/SC008HOLHmfOeB8E3SxNDONHI7nad1YJcmSl0ds9.png" alt="District Gurus">-->
                #JEWELRYCG
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
        <i class="bi bi-list"></i>
        </button>
        <div class="collapse navbar-collapse" id="navbarSupportedContent">

            <!-- left navbar-->
            <ul class="mb-2 navbar-nav mb-lg-0">
                <li class="nav-item menu-area d-none">
                    <a class="nav-link" href="{{ route('index') }}">Home</a>
                </li>
                <li class="nav-item menu-area">
                    <a class="nav-link active" href="{{ route('shop_index') }}">3D Models</a>
                </li>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" aria-current="page" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false" href="#">Learn</a>
                    <ul class="dropdown-menu half-menu" aria-labelledby="navbarDropdown">
                        <li>
                            <a class="dropdown-item" href="{{ route('blog') }}">
                                <div class="row">
                                    <div class="col-auto">
                                        <div class="dropdown-icon-wrap"><i class="bi bi-book"></i></div>
                                    </div>
                                    <div class="col-auto w-80">
                                        <div class="mb-2 w-100 fw-800">Blog</div>
                                        <div class="w-100">Learn product design in just 16 weeks...</div>
                                    </div>
                                </div>
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item" href="{{ route('courses.index') }}">
                                <div class="row">
                                    <div class="col-auto">
                                        <div class="dropdown-icon-wrap"><i class="bi bi-book"></i></div>
                                    </div>
                                    <div class="col-auto w-80">
                                        <div class="mb-2 w-100 fw-800">Browse our courses</div>
                                        <div class="w-100">Learn how to create jewelry & start a business.</div>
                                    </div>
                                </div>
                            </a>
                        </li>
                        <!--
                        <li><a class="dropdown-item" href="{{ route('categoryAll') }}">Categories</a></li>
                        <li><a class="dropdown-item" href="{{ route('tagAll') }}">Tags</a></li>
                        -->
                    </ul>
                </li>
                <li class="nav-item menu-area">
                    <a class="nav-link" href="{{ route('services.all') }}">Hire a Pro</a>
                </li>
            </ul>
            <!-- end left navbar-->


            <!-- right navbar-->
            <ul class="mb-2 ml-auto navbar-nav mb-lg-0">
                <li class="nav-item dropdown menu-area">
                    <a href="{{route('cart.index')}}" class="nav-link">
                        <?php
                            if(Cart::instance('default')->content()->count() == 0
                                && auth()->check()
                            )
                            {
                                Cart::merge(auth()->id());
                            }
                        ?>
                        @if ($cart_items = Cart::content()->count())
                        Cart (<span class="cart-count"><span class="cart-count-number">{{$cart_items}}</span></span>)
                        @endif
                    </a>
                </li>

                @auth
                @php
                    $new_count =Auth::user()->notifications()->where('status', 0)->count();
                    $notifications = Auth::user()->notifications()->where('status', 0)->get();
                    $message_notifications = App\Models\Message::where('conversation_id',Auth::id())->groupBy('user_id')->get();
                    
                    $user_id = Auth::id();
                    function user_name ($id) {
                        return App\Models\User::where('id',$id)->get();
                    }
                    $seller_id = App\Models\Product::groupBy('vendor')->get('vendor');
                    $role = false;
                    foreach($seller_id as $t){
                        if($user_id == $t->vendor){
                            $role = true;
                        }
                    }
                   
                @endphp
                <li class="nav-item dropdown">
                    <a class="nav-link notification-badge-container" aria-current="page" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false" href="#">
                        <i class="bi bi-bell fs-18">
                            @if ($new_count)
                            <div class="notification-badge">{{ $new_count }}</div>
                            @endif
                        </i>
                    </a>
                    <ul class="dropdown-menu half-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                        <div class="dropdown-header pb-2 border-bottom">
                            <span class="dropdown-title">Notifications (0)</span>
                        </div>
                        @foreach ($notifications as $notification)
                        <a href="/notifications/check/{{$notification->id}}">
                            <div class="notification-container">
                                <img class="notification-thumb" src="{{$notification->thumb ? $notification->thumb : "/assets/img/jewelrycg_default_logo.png"}}">
                                <div class="notification-body">
                                    <p class="notification-message">{{ $notification->message }}</p>
                                    <p class="notification-time">{{ get_period($notification->created_at) }}</p>
                                </div>
                            </div>
                        </a>
                        @endforeach
                    </ul>
                </li>

                
                <li class="nav-item dropdown">
                    <a class="nav-link notification-badge-container" aria-current="page" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false" href="#">
                        <i class="bi bi-envelope fs-18">
                            <div class="notification-badge"></div>
                        </i>
                    </a>
                  
                    <ul class="dropdown-menu half-menu dropdown-menu-end" aria-labelledby="navbarDropdown" style="width:250px;">
                        <div class="dropdown-header pb-2 border-bottom">
                            <span class="dropdown-title">Messages (0)</span>
                        </div>
                        @foreach ($message_notifications as $message_notification)
                        <a href="{{ env('APP_URL')}}/chat/{{$message_notification->user_id }}" class="filterDiscussions all unread single active d-block py-2 border-bottom"  data-toggle="list" role="tab">
                            <div class="row">
                                <div class="col-lg-3">
                                    <img class="avatar-md w-100 rounded-circle" src="{{user_name($message_notification->user_id)[0]->uploads->getImageOptimizedFullName(100,100)}}" data-toggle="tooltip" data-placement="top" title="Janette" alt="{{user_name($message_notification->user_id)[0]->first_name}} avatar">
                                </div>
                                <div class="data col-lg-9">
                                    <p class="fw-700">{{user_name($message_notification->user_id)[0]->first_name}} {{user_name($message_notification->user_id)[0]->last_name}}</p>
                                    <p>{{$message_notification->message}}</p>
                                </div>
                            </div>
                        </a>
                        @endforeach
                    </ul>
                </li>
               
                
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" aria-current="page" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false" href="#">{{ Auth::user()->first_name }}</a>
                    <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                        <li><a class="dropdown-item" href="/dashboard">Dashboard</a></li>
                        @if (auth()->user()->role == 2)
                        <li><a class="dropdown-item" href="{{route('seller.services.list')}}">Services</a></li>
                        @endif
                        <li><a class="dropdown-item" href="{{route('user.index', auth()->user()->id)}}">My Info</a></li>
                        <li><a class="dropdown-item" href="{{route('orders.index')}}">{{ auth()->user()->role ? 'All Orders' : 'My Orders' }}</a></li>
                        <li><a class="dropdown-item" href="{{route('wishlist')}}">My Wishlist</a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li><a class="dropdown-item" href="{{ route('logout') }}">Logout</a></li>
                    </ul>
                </li>
                @else
                <li class="ml-1 nav-item">
                    <a class="auth-btn" href="{{ route('login') }}">Log In</a>
                </li>
                <li class="ml-1 nav-item">
                    <a class="auth-btn auth-primary" href="{{ route('signup') }}">Sign Up</a>
                </li>
                @endauth
            </ul>
            <!--end right nav-->
        </div>
    </nav>
</header>

<script>
    $(document).ready(function() {
        console.log("loaded");
        $('.notification-badge-container').click(function () {
            console.log("clicked");
            $.ajax({
                url: '{{ route("notifications.overview") }}',
                type: 'POST',
                data: {
                    "_token": "{{ csrf_token() }}",
                },
                success: function (response) {
                    $('.notification-badge').hide();
                    console.log(response);
                }
            })
        })
    })
</script>

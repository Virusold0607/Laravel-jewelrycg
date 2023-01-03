<x-app-layout page-title="Messages">
    @section('css')
        <link rel="stylesheet" href="//use.fontawesome.com/releases/v5.0.7/css/all.css">
        <style>
            .dropzone {
                border-radius: 25px;
                width: 132px;
                overflow: hidden;
                padding: 4px;
                background: transparent;
            }
            .dropzone .dz-preview{
                margin: 0;
            }

            .dz-image img {
                width: 100%;
            }
            .list-group > a:hover {
                background: #f0f2f5
            }



            .data > span {
                margin-right: 10px;
            }

            i {
                font-size: 20px;
            }

            body {
                margin-top: 20px;
            }

            .chat-online {
                color: #34ce57
            }

            .chat-offline {
                color: #e4606d
            }

            .chat-messages {
                display: flex;
                flex-direction: column;
                max-height: 60vh;
                overflow-y: scroll;
            }

            .chat-message-left,
            .chat-message-right {
                display: flex;
                flex-shrink: 0
            }

            .chat-message-left {
                margin-right: auto
            }

            .chat-message-right {
                flex-direction: row-reverse;
                margin-left: auto
            }

            .py-3 {
                padding-top: 1rem !important;
                padding-bottom: 1rem !important;
            }

            .px-4 {
                padding-right: 1.5rem !important;
                padding-left: 1.5rem !important;
            }

            .flex-grow-0 {
                flex-grow: 0 !important;
            }

            .border-top {
                border-top: 1px solid #dee2e6 !important;
            }

            .border-right {
                border-right: 1px solid #dee2e6 !important;
            }

            .float-right {
                float: right !important;
            }

            .list-group-item {
                position: relative;
                display: block;
                padding: 0.75rem 1.25rem;
                background-color: #fff;
                border: 1px solid rgba(0, 0, 0, .125);
            }
            .messages{
                padding: 30px;
            }
        </style>
    @endsection
    <section class="messages bg-white py-8">
        @php
            function users_name($id){
                return \App\Models\User::where('id',$id)->get();
            }
//        @endphp

        <input type="hidden" id="user_name" value="{{auth()->user()->first_name.' '.auth()->user()->last_name}}"/>
        <input type="hidden" id="seller" value="{{$conversation_id}}" />
        <div class="container p-0">
            <h1 class="h3 mb-3">Messages</h1>
            <div class="card">
                <div class="row g-0">
                    <div class="col-12 col-lg-5 col-xl-3 border-right">

                        <div class="px-4 d-none d-md-block">
                            <div class="d-flex align-items-center">
                                <div class="flex-grow-1">
                                    <input type="text" class="form-control my-3" placeholder="Search...">
                                </div>
                            </div>
                        </div>
                        @foreach($side_info as $info)
                            <a href="#" class="list-group-item list-group-item-action border-0 filterDiscussions all unread single active"  data-toggle="list" role="tab" data-id="{{$info->conversation_id}}">
                                <div class="badge bg-success float-right">
                                    <span>{{$info->cnt > 0 ? $info->cnt : '' }}</span>
                                </div>
                                <div class="d-flex align-items-start">
                                    <img
                                        src="{{users_name($info->conversation_id)[0]->uploads->getImageOptimizedFullName(100,100)}}"
                                        data-toggle="tooltip" data-placement="top" title="Janette"
                                        alt="avatar"
                                        class="rounded-circle mr-1" alt="Vanessa Tucker" width="40" height="40">
                                    <div class="flex-grow-1 ml-10px">
                                        {{users_name($info->conversation_id)[0]->first_name}} {{users_name($info->conversation_id)[0]->last_name}}
                                        <div class="small"><span class="fas fa-circle chat-online"></span> Online</div>
                                    </div>
                                </div>
                            </a>
                            <input type="hidden" name="client_id" id="client_id"
                                   value="{{$info->id}}"/>
                        @endforeach
                        <hr class="d-block d-lg-none mt-1 mb-0">
                    </div>

                    <div class="col-12 col-lg-7 col-xl-9">
                        <div class="py-2 px-4 border-bottom d-none d-lg-block">
                            <div class="d-flex align-items-center py-1">
                                <div class="position-relative">
                                    <img  src="{{users_name($conversation_id)->first()->uploads->getImageOptimizedFullName(100,100)}}" data-toggle="tooltip" data-placement="top" title="Keith" alt="avatar"
                                         class="rounded-circle mr-1"  width="40" height="40">
                                </div>

                                <div class="flex-grow-1 pl-3">
                                    <strong>{{users_name($conversation_id)->first()->first_name}} {{users_name($conversation_id)->first()->last_name}}</strong>
                                    <div class="text-muted small"><em>Active now.</em></div>

                                </div>
                                <div>
                                    <button class="btn btn-primary btn-lg mr-1 px-3">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                             viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                             stroke-linecap="round" stroke-linejoin="round"
                                             class="feather feather-phone feather-lg">
                                            <path
                                                d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"></path>
                                        </svg>
                                    </button>
                                    <button class="btn btn-info btn-lg mr-1 px-3 d-none d-md-inline-block">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                             viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                             stroke-linecap="round" stroke-linejoin="round"
                                             class="feather feather-video feather-lg">
                                            <polygon points="23 7 16 12 23 17 23 7"></polygon>
                                            <rect x="1" y="5" width="15" height="14" rx="2" ry="2"></rect>
                                        </svg>
                                    </button>
                                    <button class="btn btn-light border btn-lg px-3">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                             viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                             stroke-linecap="round" stroke-linejoin="round"
                                             class="feather feather-more-horizontal feather-lg">
                                            <circle cx="12" cy="12" r="1"></circle>
                                            <circle cx="19" cy="12" r="1"></circle>
                                            <circle cx="5" cy="12" r="1"></circle>
                                        </svg>
                                    </button>
                                </div>
                            </div>
                        </div>

                        <div class="position-relative">
                            <div class="chat-messages p-4">

                                <div class="content" id="content">
                                    <div class="container" id="chat-container">
                                        <div class="col-md-12" id="chat-content">
                                            @foreach($chat_content as $content)
                                                @if($content->message != null)
                                                    @if(Auth::id() == $content->user_id)
                                                        <div class="chat-message-right pb-4">
                                                            <div class="ml-10px">
                                                                <img  src="{{Auth::user()->uploads->getImageOptimizedFullName(100,100)}}"
                                                                      class="rounded-circle mr-1" alt="Chris Wood" width="40" height="40">
                                                                <div class="text-muted small text-nowrap mt-2">{{date('g:i a',strtotime($content->updated_at))}}</div>
                                                            </div>
                                                            <div class="flex-shrink-1 bg-light rounded py-2 px-3 mr-3">
                                                                <div class="font-weight-bold mb-1">You</div>
                                                                {{$content->message}}
                                                            </div>
                                                        </div>
                                                    @else
                                                        <div class="chat-message-left pb-4">
                                                            <div class="mr-10px">
                                                                <img src="{{users_name($info->conversation_id)[0]->uploads->getImageOptimizedFullName(100,100)}}"
                                                                     class="rounded-circle mr-1" alt="Sharon Lessman" width="40" height="40">
                                                                <div class="text-muted small text-nowrap mt-2">{{date('g:i A',strtotime($content->updated_at))}}</div>
                                                            </div>
                                                            <div class="flex-shrink-1 bg-light rounded py-2 px-3 ml-3">
                                                                <div class="font-weight-bold mb-1">{{users_name($content->conversation_id)->first()->first_name}} {{users_name($content->conversation_id)->first()->last_name}}</div>
                                                                {{$content->message}}
                                                            </div>
                                                        </div>
                                                    @endif
                                                @endif
                                            @endforeach
                                        </div>
                                    </div>
                                </div>



                            </div>
                        </div>

                        <div class="flex-grow-0 py-3 px-4 border-top">
                            <div class="position-relative w-100">
                                <div class="input-group">
                                    <input form="uploadFileForm" type="text" id="chat_input" class="form-control" class="form-control"
                                           placeholder="Start typing for reply...">

                                    <button class="btn btn-primary">Send</button>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="container">

            @section('js')
                <script src="https://cdn.ably.com/lib/ably.min-1.js"></script>
                <script type="text/javascript">

                    const userImageUrl = @json(Auth::user()->uploads->getImageOptimizedFullName(100,100));

                    const ably = new Ably.Realtime.Promise("{{env('ABLY_KEY')}}");
                    var client_id = document.getElementById('seller').value;
                    // const ably = new Ably.Realtime.Promise('n-4_Uw.JXK4Fg:Q68j6Dp4ZoeVLbo--o3Mane1kNfcpVckpO-xp-CAGZ4');
                    let ablyConnected = false;
                    let channel;
                    ably.connection.once('connected').then(res => {
                        console.log('ably connected');
                        ablyConnected = true;
                        channel = ably.channels.get('chat-channel');
                        channel.subscribe('chat-{{auth()->id()}}', (msg) => {
                            handleReceivedMessage(msg);
                        })
                    })

                    $('document').ready(function () {
                        $('.uploadFile').click(function (){
                            document.getElementById('fileUpload').click();
                        });

                        $("#content").animate({scrollTop: $('#content').prop("scrollHeight")}, 10); // Scroll the chat output div

                        $('.filterDiscussions').click(function(){

                            client_id = $(this).attr('data-id');
                            $(location).attr('href',`{{env('APP_URL')}}/chat/${client_id}`);
                            // windows.location.href(`http://localhost/jewelrycg/public/chat/${client_id}`);
                            // $.ajax({
                            //     type: 'GET',
                            //     url: "{{ route('chat.clientId') }}",
                            //     data: {
                            //         "client_id": $(this).attr('data-id')
                            //     },
                            //     dataType: "json",
                            //     success: (result) => {
                            //          const contentTab = $("div#content #chat-content");
                            //          contentTab.html("");
                            //         $.each(result.chat_content, function(key, value){
                            //              if(value.message !=null){
                            //                 if($('#user_name').val() == value.name){
                            //                     var message = '';
                            //                     message += '<div class="message me" <img class="avatar-md" src="{{asset('assets/img/avatar.png')}}" data-toggle="tooltip" data-placement="top" title="Keith" alt="avatar">'+ '<div class="text-main">' + '<div class="text-group me">' + '<div class="text me">' + '<p>' + value.message + '</p></div></div>' + '<span>' + value.updated_at + '</span></div></div>';
                            //                     contentTab.append(message);
                            //                 }else{
                            //                     var message = '';
                            //                     message +='<div class="message"> <img class="avatar-md" src="{{asset('assets/img/avatar.png')}}" data-toggle="tooltip" data-placement="top" title="Keith" alt="avatar">'+ '<div class="text-main">' + '<div class="text-group">' + '<div class="text">' + '<p>' + value.message + '</p></div></div>' + '<span>' + value.updated_at + '</span></div></div>';
                            //                     contentTab.append(message);
                            //                 }
                            //              }
                            //         });

                            //     },
                            //     error: (resp) => {
                            //         var result = resp.responseJSON;
                            //         if (result.errors && result.message) {
                            //             alert(result.message);
                            //             return;
                            //         }
                            //     }
                            // });
                        })
                    });
                    function formatAMPM(date) {
                        var hours = date.getHours();
                        var minutes = date.getMinutes();
                        var ampm = hours >= 12 ? 'pm' : 'am';
                        hours = hours % 12;
                        hours = hours ? hours : 12; // the hour '0' should be '12'
                        minutes = minutes < 10 ? '0'+minutes : minutes;
                        var strTime = hours + ':' + minutes + ' ' + ampm;
                        return strTime;
                    }
                    function getDateFormat() {
                        return formatAMPM(new Date);
                    }

                    // Bind onkeyup event after connection
                    $('#chat_input').on('keyup', function (e) {
                        if (e.keyCode === 13 && !e.shiftKey) {
                            let chat_msg = $(this).val();
                            let msg = JSON.stringify({
                                'type': 'chat',
                                'user_id': '{{auth()->id()}}',
                                'user_name': '{{auth()->user()->first_name.' '.auth()->user()->last_name}}',
                                'chat_msg': chat_msg,
                                'conversation_id' : client_id,
                            })
                            sendMessage(msg);
                            let content = `
                                    <div class="chat-message-right pb-4">
                                    <div>
                                        <img src="${userImageUrl}"
                                             class="rounded-circle mr-1" alt="Chris Wood" width="40" height="40">
                                        <div class="text-muted small text-nowrap mt-2">${getDateFormat()}</div>
                                    </div>
                                    <div class="flex-shrink-1 bg-light rounded py-2 px-3 mr-3">
                                        <div class="font-weight-bold mb-1">You</div>
                                        ${chat_msg}
                                    </div>
                                </div>
`;                    $('#chat-content').append(content);
                            $(this).val('');
                            $(`#shortmsg_${client_id}`).text(chat_msg);
                            // $("#content").animate({ scrollTop: $("#content").height()+20  }, 1000);
                            $("#content").animate({scrollTop: $('#content').prop("scrollHeight")}, 10); // Scroll the chat output div
                        }
                    });
                    function sendMessage(msg) {
                        if (channel) {
                            console.log(channel);
                            console.log('clientid: ', client_id);
                            channel.publish('chat-' + client_id, msg);

                            $.ajax({
                                type: 'POST',
                                url: "{{ route('chat.message_log') }}",
                                data: {
                                    "data": JSON.parse(msg),
                                    "_token": '{{ csrf_token() }}'
                                },
                                dataType: "json",
                                success: (result) => {

                                },
                                error: (resp) => {
                                    var result = resp.responseJSON;
                                    if (result.errors && result.message) {
                                        alert(result.message);
                                        return;
                                    }
                                }
                            });
                        }
                    }
                    function handleReceivedMessage(msg) {
                        const data = JSON.parse(msg.data);

                        if(data.conversation_id == {{auth()->id()}}){
                            switch (data.type) {
                                case 'chat':
                                    const msg =  `<div class="chat-message-left pb-4">
                                <div>
                                    <img src="{{asset('assets/img/avatar.png')}}"
                                         class="rounded-circle mr-1" alt="Sharon Lessman" width="40" height="40">
                                        <div class="text-muted small text-nowrap mt-2">${getDateFormat()}</div>
                                </div>
                                <div class="flex-shrink-1 bg-light rounded py-2 px-3 ml-3">
                                    <div class="font-weight-bold mb-1">Sharon Lessman</div>
                                    ${data.chat_msg}
                                </div>
                            </div>
                        `;
                                    $('#chat-content').append(msg); // Append the new message received
                                    $("#content").animate({scrollTop: $('#content').prop("scrollHeight")}, 10); // Scroll the chat output div
                                    break;
                                case 'socket':
                                    $('#chat-content').append(data.msg);
                                    console.log("Received " + data.msg);
                                    break;
                            }}
                    }
                </script>
        </div>
    </section>
    @endsection
</x-app-layout>


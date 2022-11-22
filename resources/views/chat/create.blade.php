<x-app-layout page-title="Messages">
@section('css')
<link rel="stylesheet" href="{{ asset('assets/css/swipe.min.css') }}" data-hs-appearance="default" as="style" type="text/css">
<link rel="stylesheet" href="//use.fontawesome.com/releases/v5.0.7/css/all.css">
<style>

.list-group>a:hover {
    background:#f0f2f5
}
.data>span{
    margin-right:10px;
}

i{
    font-size:20px;
}
</style>
@endsection 
<section class="messages bg-white py-8">
    <div class="container">

    <div class="layout chat-container">
        <div class="sidebar" id="sidebar">
            <div class="container">
                <div class="col-md-12">
                    <div class="tab-content">
                        <div id="discussions" class="tab-pane fade active show">
                            <div class="search">
                            
                            </div>
                            <input type="hidden" id="seller" value="{{$conversation_id}}" />
                            <div class="discussions">
                                <h1>Discussions</h1>
                            @php
                            function users_name($id){
                                return \App\Models\User::where('id',$id)->get();
                            }
                            @endphp
                            
                                <div class="list-group" id="chats" role="tablist">
                                
                                    @foreach($side_info as $info)
                                        <a  class="filterDiscussions all unread single active"  data-toggle="list" role="tab" data-id="{{$info->conversation_id}}">
                                            <img class="avatar-md" src="{{users_name($info->conversation_id)[0]->uploads->getImageOptimizedFullName(100,100)}}" data-toggle="tooltip" data-placement="top" title="Janette" alt="avatar">
                                            <input type="hidden" name="client_id" id="client_id" value="{{$info->id}}"/>
                                            @if($info->cnt > 0)
                                            <div class="new bg-yellow">
                                                <span>{{$info->cnt}}</span>
                                            </div>
                                            @endif
                                            <div class="data">
                                                <h5>{{users_name($info->conversation_id)[0]->first_name}} {{users_name($info->conversation_id)[0]->last_name}}</h5>
                                                <!-- <span>Mon</span> -->
                                                <p id="shortmsg_{{$info->conversation_id}}">{{$info->message}}</p>
                                            </div>
                                        </a>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="main">
            <div class="tab-content" id="nav-tabContent">
                <!-- Start of Babble -->
                <div class="babble tab-pane fade active show" id="list-chat" role="tabpanel" aria-labelledby="list-chat-list">
                    <!-- Start of Chat -->
                    <div class="chat" id="chat1">
                        <div class="top">
                            <div class="container">
                                <div class="col-md-12">
                                    <div class="inside">
                                        <a href="#"><img class="avatar-md" src="{{Auth::user()->uploads->getImageOptimizedFullName(100,100)}}" data-toggle="tooltip" data-placement="top" title="Keith" alt="avatar"></a>
                                        <div class="data">
                                            <h5><a href="#">{{ Auth::user()->first_name }} {{ Auth::user()->last_name }}</a></h5>
                                            <span>Active now</span>
                                        </div>
                                        
                                    </div>
                                </div>
                            </div>
                        </div>
                        <input type="hidden" id="user_name" value="{{auth()->user()->first_name.' '.auth()->user()->last_name}}"/>
                        <div class="content" id="content">
                            <div class="container" id="chat-container">
                                <div class="col-md-12" id="chat-content">
                                    @foreach($chat_content as $content)
                                        @if($content->message != null)
                                            @if(Auth::id() == $content->user_id)
                                                <div class="message me">
                                                    <div class="text-main">
                                                        <div class="text-group me">
                                                            <div class="text me">
                                                                <p>{{$content->message}}</p>
                                                            </div>
                                                        </div>
                                                        <span>{{$content->updated_at}}</span>
                                                    </div>
                                                </div>
                                            @else    
                                                <div class="message">
                                                    <img class="avatar-md" src="{{users_name($info->conversation_id)[0]->uploads->getImageOptimizedFullName(100,100)}}" data-toggle="tooltip" data-placement="top" title="Keith" alt="avatar">
                                                    <div class="text-main">
                                                        <div class="text-group">
                                                            <div class="text">
                                                                <p>{{$content->message}}</p>
                                                            </div>
                                                        </div>
                                                        <span>{{$content->updated_at}}</span>
                                                    </div>
                                                </div>
                                            @endif    
                                        @endif    
                                    @endforeach
                                </div>
                            </div>
                        </div>
                        <div class="container">
                            <div class="col-md-12">
                                <div class="bottom">
                                    <form class="position-relative w-100">
                                        <textarea id="chat_input" class="form-control" placeholder="Start typing for reply..." rows="1"></textarea>
                                        <a class="btn emoticons" style="padding-top:18px;"><i class="fas fa-smile" aria-hidden="false"></i></a>
                                        <button type="button" class="btn send"><i class="fa fa-paper-plane" aria-hidden="true"></i></button>
                                    </form>
                                
                                    <!-- <label>
                                        <input type="file">
                                        <span class="btn attach d-sm-block d-none"><i class="material-icons">attach_file</i></span>
                                    </label>  -->
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div> <!-- Layout -->
    @section('js')
        <script src="https://cdn.ably.com/lib/ably.min-1.js"></script>
        <script type="text/javascript">

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

            function getDateFormat() {
                var d = new Date,
                    dformat = [d.getFullYear(),
                                d.getMonth()+1,
                                d.getDate()
                                ].join('-')+' '+
                                [d.getHours(),
                                d.getMinutes()].join(':');
                return dformat;
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
                    let content = `<div class='message me'><div class='text-main'><div class='text-group me'><div class='text me'><p>${chat_msg}</p></div></div><span>${getDateFormat()}</span></div></div>`;
                    $('#chat-content').append(content);
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
                            const msg = `<div class='message'><img class="avatar-md" src="{{asset('assets/img/avatar.png')}}" data-toggle="tooltip" data-placement="top" title="Keith" alt="avatar"><div class='text-main'><div class='text-group'><div class='text'><p>${data.chat_msg}</p></div></div><span>${getDateFormat()}</span></div></div>`;
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

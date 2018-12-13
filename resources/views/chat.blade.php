@extends('main')

@section('content')

<!-- order list area start -->
<div class="row">
    <div class="col-lg-12 col-centered">
        <div class="container">
            <div class="card mt-5" style="height: 100%;">
                <div class="card-body">
                    <h4 class="header-title">Chat Box</h4>
                    <center>
                        <button type="button" class="btn btn-flat btn-primary" id="button-request-conversation">Request Conversation</button>
                        <p id="message-request-conversation" style="margin-top: 50px;" hidden>Menunggu approve dari admin.</div>
                        <div class="loader" style="margin-top: 50px;" hidden></div>
                    </center>
                    <div class="chat-box"></div>
                </div>
                <div class="card-footer">
                    <div class="input-group">
                        <input id="chat-textbox" class="form-control mb-4" type="text" placeholder="Input message here" disabled>
                        <button id="chat-button" type="button" class="btn btn-flat btn-primary mb-4" disabled>Kirim</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- order list area end -->



<div class="modal modal-start" data-backdrop="static">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Start</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>


<script>
$(document).ready(function(){

    Pusher.logToConsole = true;
    var pusher = new Pusher('{{ env("PUSHER_APP_KEY") }}', {
        cluster: 'ap1',
        forceTLS: true
    })

    var channel_approve = pusher.subscribe('channel-approve-{{ session("session.id") }}')
    channel_approve.bind('event-approve-{{ session("session.id") }}', function(data) {
        $('.modal-start').modal()
        $('.modal-body').empty()
        $('.modal-body').append('Anda telah terhubung dengan admin '+ data.admin.name +'<br>Silahkan anda memulai chat.')
        
        $('.request-conversation').attr('hidden', true)
        $('.loader').attr('hidden', true)

        $('.header-title').text('#'+ data.id +' - '+ data.admin.name)
        
        $('#chat-textbox').attr('disabled', false)
        $('#chat-button').attr('disabled', false)
        
        $('#chat-button').data('info', data.id)

        checkConversation()
    })

    function breakConversation(){
        var break_conversation = pusher.subscribe('channel-break-conversation-'+conversation_id)
        break_conversation.bind('event-break-conversation-'+conversation_id, function(data) {
            $('.modal-start').modal()
            $('.modal-body').empty()
            $('.modal-body').append('Chat anda telah diputus oleh admin<br>Silahkan anda memulai chat lagi.')

            $('#button-request-conversation').attr('hidden', false) 

            $('.header-title').text('Chat Box')
            $('.header-message').attr('hidden', true)
        
            $('#chat-textbox').attr('disabled', true)
            $('#chat-button').attr('disabled', true)
        })
    }
    breakConversation()

    function conversationSplit(response){
        if(response.user.roles == 'admin'){
            $('.chat-box').append('<div class="msg header-message">'+
                '<div class="bubble">'+
                    '<div class="txt">'+
                        '<span class="name">'+ response.user.name +'</span>'+
                        '<span class="timestamp">'+ $.format.date(response.created_at, 'HH:mm') +'</span>'+      
                        '<span class="message">'+
                            response.message +
                        '</span> '+
                    '</div>'+
                '<div class="bubble-arrow"></div>'+
                '</div>'+
            '</div>')
            $('.chat-box').animate({
                scrollTop: $('.chat-box').get(0).scrollHeight
            }, 1)
        }
        else {
            $('.chat-box').append('<div class="msg header-message">'+
                '<div class="bubble alt">'+
                    '<div class="txt">'+
                        '<span class="name">'+ response.user.name +'</span>'+
                        '<span class="timestamp">'+ $.format.date(response.created_at, 'HH:mm') +'</span>'+      
                        '<span class="message">'+
                        response.message + 
                        '</span> '+
                    '</div>'+
                    '<div class="bubble-arrow"></div>'+
                '</div>'+
            '</div>')
            $('.chat-box').animate({
                scrollTop: $('.chat-box').get(0).scrollHeight
            }, 1)
        }
    }

    var conversation_id = ''
    function checkConversation(){

        var id = {{ session('session.id') }}
        
        $('.header-message').remove()
        $('.body-message').remove()
                
        $.ajax({
            type: 'GET',
            url: '{{ url("check-conversation") }}?user_id=' + id,
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            async: false,
            success: function(response){
                
                if(response.conversation){
                    $('#button-request-conversation').attr('hidden', true)

                    $('.header-title').text('#'+ response.conversation.id +' - '+ response.conversation.admin.name)
                    
                    $('.button-request-conversation').attr('hidden', true)
                    $('#message-request-conversation').attr('hidden', true)
                    $('.loader').attr('hidden', true)

                    $('#chat-textbox').attr('disabled', false)
                    $('#chat-button').attr('disabled', false)

                    $('#chat-button').data('info', response.conversation.id)
                }

                conversation_id = response.conversation.id
                var channel_message = pusher.subscribe('channel-message-'+ conversation_id)
                channel_message.bind('event-message-'+ conversation_id, function(data) {
                    conversationSplit(data)
                })

                if(response.messages){
                    $.each(response.messages, function(){
                        conversationSplit(this)
                    })
                }
                
                breakConversation()
            }
        })
    }
    checkConversation()

    $('#chat-button').on('click', function() {
        var conversation_id = $(this).data('info')
        var user_id = {{ session('session.id') }}
        var message = $('#chat-textbox').val()

        if($('#chat-textbox').val() != ''){
            $.ajax({
                type: 'POST',
                url: '{{ url("send-message") }}',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: {
                    conversation_id: conversation_id,
                    message: message,
                    user_id: user_id
                },
                success: function(response){
                    
                }
            })
        }
        
        $('#chat-textbox').val('')
    })

    $('#chat-textbox').keypress(function(e) {
        if(e.which == 13) {
            $(this).blur()
            $('#chat-button').focus().click()
            $('#chat-textbox').val('')
            $('#chat-textbox').focus()
        }
    })

    $('#button-request-conversation').on('click', function() {
        var id = {{ session('session.id') }}

        $.ajax({
            type: 'GET',
            url: '{{ url("request-conversation") }}?id=' + id,
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            async: false,
            success: function(response){
                $('.modal-start').modal()
                $('.modal-body').empty()
                $('.modal-body').append('Terima kasih,<br>Silahkan menunggu konfirmasi dari admin, tidak lama kok')
                
                $('#message-request-conversation').attr('hidden', false)
                $('.loader').attr('hidden', false)
            }
        })
    })
})    
</script>
@endsection

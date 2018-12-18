@extends('main')

@section('content')

<!-- order list area start -->
<div class="row">
    <div class="col-lg-12 col-centered">
        <div class="container">
            <div class="card mt-5" style="height: 100%;">
                <div class="card-body">
                    <h4 class="header-title">Chat Box</h4>
                    <div class="text-center request-conversation"></div>
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
            <div class="modal-body"></div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>


<script>
$(document).ready(function(){

    // split conversation between admin role and user role
    function conversationSplit(response){
        if(response.messages){
            $('.chat-box').empty()
            $.each(response.messages, function(){
                var roles = this.user.roles == 'user' ? ' alt' : ''
                
                $('.chat-box').append('<div class="msg header-message">'+
                    '<div class="bubble'+ roles +'">'+
                        '<div class="txt">'+
                            '<span class="name">'+ this.user.name +'</span>'+
                            '<span class="timestamp">'+ $.format.date(this.created_at, 'HH:mm') +'</span>'+      
                            '<span class="message">'+
                                this.message +
                            '</span> '+
                        '</div>'+
                    '<div class="bubble-arrow"></div>'+
                    '</div>'+
                '</div>')
            })
        }
    }

    function checkConversation(){
        
        var id = {{ session('session.id') }}
        
        $.ajax({
            type: 'GET',
            url: '{{ url("check-conversation") }}?user_id=' + id,
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            async: false,
            success: function(response){
                
                $('.request-conversation').empty()
                
                $('#chat-textbox').attr('disabled', false)
                $('#chat-button').attr('disabled', false)
                $('#chat-button').data('info', response.conversation.id)

                $('.header-title').text('#'+ response.conversation.id +' - '+ response.conversation.admin.name)
                
                var total_message_now = $('.msg').length
                var total_message_after = response.messages.length
                
                conversationSplit(response)
                
                if(total_message_now != total_message_after)
                $('.chat-box').animate({
                    scrollTop: $('.chat-box').get(0).scrollHeight
                }, 1)
                console.clear()
            },
            error: function(response){
                $('.header-title').text('Chat-Box')

                $('.chat-box').empty()
                $('.request-conversation').empty()
                $('.request-conversation').append('<button type="button" class="btn btn-flat btn-primary" id="button-request-conversation">Request Conversation</button>')
                console.clear()
            }
        })
    }
    setInterval(checkConversation, 1000)

    $('.request-conversation').on('click', $('#button-request-conversation'), function() {
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
                $('.modal-body').append('Terima kasih,<br>Silahkan menunggu konfirmasi dari admin')
            }
        })
    })

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
})    
</script>
@endsection

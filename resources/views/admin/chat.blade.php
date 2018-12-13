@extends('admin.main')

@section('content')

<div class="row">
<!-- team member area start -->
<div class="col-lg-4 mt-5" style="height: 650px;">
    <div class="card">
        <div class="card-body">
            <div class="d-sm-flex justify-content-between align-items-center">
                <h4 class="header-title"></h4>
                <div class="trd-history-tabs">
                    <ul class="nav" role="tablist">
                        <li>
                            <a class="active" data-toggle="tab" href="#conversation"  id="conversation-tab" role="tab">Conversation</a>
                        </li>
                        <li>
                            <a data-toggle="tab" href="#unapproved" id="unapproved-tab" role="tab">Unapproved</a>
                        </li>
                    </ul>
                </div>
                <div class="custome-select border-0 pr-3"></div>
            </div>

            <div class="trad-history mt-4">
                <div class="tab-content" id="myTabContent">
                    <div class="tab-pane fade show active" id="conversation" role="tabpanel" style="height: 500px; overflow: auto;">
                        
                    </div>
                    <div class="tab-pane fade" id="unapproved" role="tabpanel" style="height: 500px; overflow: auto;">
                        
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- team member area end -->

<!-- trading history area start -->
<div class="col-lg-8 mt-5" style="height: 650px;">
    <div class="card">
        <div class="card-header">Chat Box</div>
        <div class="card-body">
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
<!-- trading history area end -->
</div>



<script>
$(document).ready(function(){
    Pusher.logToConsole = true;
    var pusher = new Pusher('{{ env("PUSHER_APP_KEY") }}', {
        cluster: 'ap1',
        forceTLS: true
    })

    function conversationTab(){
        var admin_id = '{{ session("session.id") }}'
        
        $.ajax({
            type: 'GET',
            url: '{{ url("admin/conversation-list") }}?admin_id='+ admin_id,
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function(response){
                $('#conversation').empty()
                
                $.each(response, function(){
                    $('#conversation').append('<div class="s-member conversation conversation-'+ this.id +'">'+
                            '<div class="media align-items-center">'+
                                '<img src="{{ asset("public/assets/images/author/avatar.png") }}" class="d-block ui-w-30 rounded-circle" alt="">'+
                                '<div class="media-body ml-5">'+
                                    '<p>'+ this.user.name +'</p><span>'+ $.format.date(this.created_at, 'dd-MM-yyyy, HH:mm') +'</span>'+
                                '</div>'+
                                '<div class="tm-social">'+
                                    '<a href="#" class="open-chat" data-info="'+ this.id +','+ this.user.name +'"><i class="fa fa-wechat"></i></a>'+
                                    '<a href="#" class="break-chat" data-info="'+ this.id +'"><i class="fa fa-unlink"></i></a>'+
                                '</div>'+
                            '</div>'+
                        '</div>')
                })
            }
        })
    }
    conversationTab()

    $('#conversation-tab').on('click', function(){
        conversationTab()
    })

    $('#unapproved-tab').on('click', function(){
        $.ajax({
            type: 'GET',
            url: '{{ url("admin/unapproved-list") }}',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function(response){
                $('#unapproved').empty()
                $.each(response, function(){
                    $('#unapproved').append('<div class="s-member unapproved unapproved-'+ this.id +'">'+
                        '<div class="media align-items-center">'+
                            '<img src="{{ asset("public/assets/images/author/avatar.png") }}" class="d-block ui-w-30 rounded-circle" alt="">'+
                            '<div class="media-body ml-5">'+
                                '<p>'+ this.name +'</p><span>'+ $.format.date(this.updated_at, 'dd-MM-yyyy, HH:mm') +'</span>'+
                            '</div>'+
                            '<div class="tm-social">'+
                                '<button class="btn btn-flat btn-primary btn-xs button-approved" data-info="'+ this.id +'">Approve</button>'+
                            '</div>'+
                        '</div>'+
                    '</div>')
                })
            }
        })
    })
    
    $('#unapproved').on('click', '.button-approved', function() {
        var admin_id = {{ session('session.id') }}
        var user_id = $(this).attr('data-info')
        
        $('.unapproved-'+user_id).attr('hidden', true)
        
        $.ajax({
            type: 'GET',
            url: '{{ url("admin/approved") }}?admin_id='+ admin_id +'&&user_id='+user_id,
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function(response){
                $('.unapproved-'+user_id).attr('hidden', true)
                console.log(response)
            }
        })
    })

    function conversationSplit(response){
        if(response.user.roles == 'user'){
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
    $('#conversation').on('click', '.open-chat', function() {
        var data_info = $(this).attr('data-info').split(',')
        var conversation_id = data_info[0]
        var user_name = data_info[1]

        $('.header-message').remove()
        
        $('#chat-textbox').attr('disabled', false)
        $('#chat-button').attr('disabled', false)
        $('#chat-button').data('info', conversation_id)

        $.ajax({
            type: 'GET',
            url: '{{ url("admin/open-conversation") }}?conversation_id=' + conversation_id,
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            async: false,
            success: function(response){
                $('.card-header').text('#'+response.conversation.id+' - '+ response.conversation.user.name)
                
                conversation_id = response.conversation.id
                var channel_message = pusher.subscribe('channel-message-'+ conversation_id)
                channel_message.bind('event-message-'+ conversation_id, function(data) {
                    conversationSplit(data)
                })
                
                $.each(response.messages, function(){
                    conversationSplit(this)
                })
            }
        })
    })

    $('#conversation').on('click', '.break-chat', function() {
        var id = $(this).attr('data-info')

        $('.conversation-'+ id).attr('hidden', true)

        $('.card-header').text('Chat Box')

        $('.header-message').remove()
        
        $('#chat-textbox').attr('disabled', true)
        $('#chat-button').attr('disabled', true)
        
        $.ajax({
            type: 'GET',
            url: '{{ url("admin/break-conversation") }}?id='+ id,
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
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
                url: '{{ url("admin/send-message") }}',
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

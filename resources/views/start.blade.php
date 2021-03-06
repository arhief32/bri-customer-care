@extends('user')

@section('content')
<!-- login area start -->
<div class="login-area login-s2">
    <div class="container">
        <div class="login-box ptb--100">
            <form>
                <div class="login-form-head">
                    <img src="{{ asset('public/assets/images/icon/logo-2.png') }}" />
                </div>
                    
                <div class="login-form-body">
                    <center><h4>Start!</h4></center>
                    <div class="form-gp">
                        <label for="email">Email Address</label>
                        <input type="email" id="email">
                        <i class="ti-email"></i>
                    </div>
                    <div class="form-gp">
                        <label for="full-name">Full Name</label>
                        <input type="text" id="full-name">
                        <i class="ti-user"></i>
                    </div>
                    <div class="submit-btn-area">
                        <button id="submit-start" type="submit">Submit <i class="ti-arrow-right"></i></button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
<!-- login area end -->

<!-- Modal -->
<div class="modal fade" id="exampleModalLong">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Start</h5>
                <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
            </div>
            <div class="modal-body">
                <p id="text"></p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Start Chat!</button>
            </div>
        </div>
    </div>
</div>

<script>
$(document).ready(function(e){
    $('#submit-start').click(function(e){

        var email = $('#email').val()
        var full_name = $('#full-name').val()

        e.preventDefault()
        
        $.ajax({
            type: 'POST',
            url: '{{ url("start-validation") }}',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            data: {
                email: email,
                full_name: full_name,
            },
            success: function(response){
                // window.location.replace('{{ url("chat") }}')
                if(response.status == 'insert'){
                    $('.modal').modal();
                    $('#text').text('Anda telah sukses mendaftar sebagai user')
                    $('.btn-secondary').click(function(){
                        window.location.replace('{{ url("chat") }}')
                    })
                }
                if(response.status == 'exist'){
                    $('.modal').modal();
                    $('#text').text('Anda telah terdaftar di sistem kami\nHalaman ini akan otomatis menuju dashboard chat anda')
                    $('.btn-secondary').click(function(){
                        window.location.replace('{{ url("chat") }}')
                    })
                }
            }
        })
    })
})
</script>
@endsection

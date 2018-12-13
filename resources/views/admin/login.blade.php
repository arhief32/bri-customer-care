@extends('admin.admin')

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
                        <center><h4>Sign In</h4></center>
                        <div class="alert-dismiss">
                            <div class="alert alert-danger alert-dismissible fade" role="alert">
                                <button type="button" class="close">
                                    <span class="fa fa-times"></span>
                                </button>
                                <strong>Email dan Password</strong> tidak cocok.
                            </div>
                        </div>
                        <div class="form-gp">
                            <label for="email">Email Address</label>
                            <input type="email" id="email">
                            <i class="ti-email"></i>
                        </div>
                        <div class="form-gp">
                            <label for="password">Password</label>
                            <input type="password" id="password">
                            <i class="ti-lock"></i>
                        </div>
                        <div class="submit-btn-area">
                            <button id="submit-login" type="submit">Submit <i class="ti-arrow-right"></i></button>
                        </div>
                        <div class="form-footer text-center mt-5">
                            <p class="text-muted">Don't have an account? <a href="{{ url('admin/register') }}">Register</a></p>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-- login area end -->

<script>
$(document).ready(function(){
    $('#submit-login').click(function(e){

        var email = $('#email').val()
        var password = $('#password').val()

        e.preventDefault()
        
        $.ajax({
            type: 'POST',
            url: '{{ url("admin/login-validation") }}',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            data: {
                email: email,
                password: password,
            },
            success: function(response){
                if(response.status == 'failed'){
                    $('.alert').addClass('show')
                }else{
                    window.location.replace('{{ url("admin/chat") }}')
                }
            }
        })
    })

    $('.close').click(function(){
        $('.alert').removeClass('show')
    })
})
</script>
@endsection

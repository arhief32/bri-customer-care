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
                    <center><h4>Sign Up</h4></center>
                    <div class="alert-dismiss">
                        <div class="alert alert-danger alert-dismissible fade" role="alert">
                            <button type="button" class="close">
                                <span class="fa fa-times"></span>
                            </button>
                            <strong>Password dan Password Konfirmasi</strong> tidak cocok.
                        </div>
                    </div>
                    <div class="form-gp">
                        <label for="full-name">Full Name</label>
                        <input type="text" id="full-name">
                        <i class="ti-user"></i>
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
                    <div class="form-gp">
                        <label for="password-confirm">Confirm Password</label>
                        <input type="password" id="password-confirm">
                        <i class="ti-lock"></i>
                    </div>
                    <div class="submit-btn-area">
                        <button id="submit-register" type="submit">Submit <i class="ti-arrow-right"></i></button>
                    </div>
                    <div class="form-footer text-center mt-5">
                        <p class="text-muted">Have an account? <a href="{{ url('admin/login') }}">Sign in</a></p>
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
                <h5 class="modal-title"></h5>
                <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
            </div>
            <div class="modal-body">
                <p id="text"></p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<script>
$(document).ready(function(){
    $('#submit-register').click(function(e){
        var email = $('#email').val()
        var full_name = $('#full-name').val()
        var password = $('#password').val()
        var password_confirmation = $('#password-confirm').val()
        
        e.preventDefault()

        if(password != password_confirmation)
        {
            $('.alert').addClass('show')
        }
        else
        {
            $.ajax({
                type: 'POST',
                url: '{{ url("admin/register-validation") }}',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: {
                    email: email,
                    full_name: full_name,
                    password: password,
                },
                success: function(response){
                    if(response.status == 'insert'){
                        $('.modal').modal();
                        $('.modal-title').text('Registration Success')
                        $('#text').append('Anda telah sukses mendaftar sebagai admin<br>Silahkan login')
                        $('.btn-secondary').click(function(){
                            window.location.replace('{{ url("admin/login") }}')
                        })
                    }else{
                        $('.modal').modal();
                        $('#text').append('Anda telah terdaftar di sistem kami<br>Halaman ini akan otomatis menuju dashboard chat anda')
                        $('.btn-secondary').click(function(){
                            window.location.replace('{{ url("admin/chat") }}')
                        })
                    }
                }
            })
        }

    })

    $('.close').click(function(){
        $('.alert').removeClass('show')
    })
})
</script>
@endsection

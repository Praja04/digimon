<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>

<style>
    body {
        background-color: white !important;
        font-family: 'Inter UI', sans-serif;
        margin: 0;
        padding: 20px;
    }

    .page {
        background: rgb(100, 100, 100);
        display: flex;
        flex-direction: column;
        height: calc(100% - 40px);
        position: absolute;
        place-content: center;
        width: calc(100% - 40px);
    }

    .container {
        display: flex;
        height: 400px;
        margin: 0 auto;
        width: 640px;
    }

    .left {
        background: white;
        height: calc(100% - 40px);
        top: 20px;
        position: relative;
        width: 50%;
    }

    .register {
        font-size: 50px;
        font-weight: 900;
        margin: 50px 40px 40px;
    }

    .right {
        background: #474A59;
        box-shadow: 0px 0px 40px 16px rgba(0, 0, 0, 0.22);
        color: #F1F1F2;
        position: relative;
        width: 50%;
    }

    .form {
        margin: 40px;
        position: absolute;
    }

    label {
        color: #c2c2c5;
        display: block;
        font-size: 14px;
        height: 16px;
        margin-top: 10px;
        margin-bottom: 5px;
    }

    input {
        background: transparent;
        border: 0;
        color: #f2f2f2;
        font-size: 20px;
        height: 30px;
        line-height: 30px;
        outline: none !important;
        width: 100%;
    }

    input::-moz-focus-inner {
        border: 0;
    }

    #submit {
        color: #707075;
        margin-top: 20px;
        transition: color 300ms;
        cursor: pointer;
    }

    #submit:focus {
        color: #f2f2f2;
    }

    #submit:active {
        color: #d0d0d2;
    }

    .error-message {
        color: red;
        margin-top: 10px;
        font-size: 14px;
    }
</style>

<body>
    <div class="page">
        <div class="container">
            <div class="left">
                <div class="register">Register</div>
            </div>
            <div class="right">
                <div class="form">
                    <label for="name">Name</label>
                    <input type="text" id="name" autocomplete="off">

                    <label for="email">Email</label>
                    <input type="email" id="email" autocomplete="off">

                    <label for="password">Password</label>
                    <input type="password" id="password" autocomplete="off">

                    <label for="role">Role</label>
                    <select id="role">
                        <option value="analis">Analis</option>
                        <option value="foreman">Foreman</option>
                        <option value="supervisor">Supervisor</option>
                        <option value="dept_head">Dept Head</option>
                    </select>

                    <input type="submit" id="submit" value="Register">
                    <div class="error-message" id="error-message"></div>
                </div>
            </div>
        </div>
    </div>

    <script>
        $(document).ready(function() {
            $('#submit').click(function(e) {
                e.preventDefault();

                var name = $('#name').val();
                var email = $('#email').val();
                var password = $('#password').val();
                var role = $('#role').val();

                $('#error-message').text("");

                if (name === "" || email === "" || password === "" || role === "") {
                    $('#error-message').text("Semua kolom harus diisi.");
                    return;
                }

                $.ajax({
                    url: "{{ route('register.submit') }}",
                    type: "POST",
                    data: {
                        _token: "{{ csrf_token() }}",
                        name: name,
                        email: email,
                        password: password,
                        role: role
                    },
                    success: function(response) {
                        alert("Registrasi berhasil! Silakan login.");
                        window.location.href = "{{ route('login') }}";
                    },
                    error: function(xhr) {
                        $('#error-message').text("Terjadi kesalahan. Coba lagi.");
                    }
                });
            });
        });
    </script>
</body>

</html>

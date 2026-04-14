<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login - {{ config('app.name', 'SmartHome Panel') }}</title>

    <meta name="csrf-token" content="{{ csrf_token() }}">

    @vite('resources/css/app.css')
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>

<body class="bg-gray-950 text-white flex items-center justify-center min-h-screen">

    <div class="w-full max-w-md p-6">

        <!-- TITLE -->
        <div class="text-center mb-8">
            <h1 class="text-3xl font-semibold">ARZ Smarthome</h1>
            <p class="text-gray-400 text-sm mt-2">Login to control your home</p>
        </div>

        <!-- CARD -->
        <div class="bg-gray-900 border border-gray-800 rounded-2xl p-6 shadow-lg">

            <!-- ALERT -->
            <div id="alertBox" class="hidden mb-4 text-sm rounded-lg p-3"></div>

            <!-- FORM -->
            <form id="loginForm" class="space-y-5">
                @csrf

                <!-- EMAIL -->
                <div>
                    <label class="text-sm text-gray-400">Email</label>
                    <input type="email" name="email" required autofocus
                        class="w-full mt-1 px-4 py-2 bg-gray-800 border border-gray-700 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500"
                        placeholder="you@example.com">
                </div>

                <!-- PASSWORD -->
                <div>
                    <label class="text-sm text-gray-400">Password</label>
                    <input type="password" name="password" required
                        class="w-full mt-1 px-4 py-2 bg-gray-800 border border-gray-700 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500"
                        placeholder="••••••••">
                </div>

                <div class="flex justify-center">
                    <div class="g-recaptcha" data-sitekey="{{ env('GOOGLE_RECAPTCHA_KEY') }}"></div>
                </div>

                <!-- BUTTON -->
                <button type="submit"
                    class="w-full bg-blue-600 hover:bg-blue-700 transition rounded-xl py-2 font-semibold">
                    Login
                </button>
            </form>
        </div>

        <!-- FOOTER -->
        <p class="text-center text-gray-500 text-xs mt-6">
            © {{ date('Y') }} ARZ Smarthome System
        </p>

    </div>

    <script src="https://www.google.com/recaptcha/api.js" async defer></script>

    <script>
        $(document).ready(function () {

            $('#loginForm').on('submit', function (e) {
                e.preventDefault();

                let captcha = grecaptcha.getResponse();

                if (!captcha) {
                    alert('Harap verifikasi captcha!');
                    return;
                }

                let form = $(this);
                let btn = form.find('button');
                let alertBox = $('#alertBox');

                btn.prop('disabled', true).text('Logging in...');

                $.ajax({
                    url: "{{ route('login') }}",
                    method: "POST",
                    data: form.serialize() + '&g-recaptcha-response=' + captcha,
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function (res) {
                        alertBox
                            .removeClass('hidden bg-red-500/10 text-red-400')
                            .addClass('bg-green-500/10 text-green-400')
                            .text(res.message);

                        setTimeout(() => {
                            window.location.href = res.redirect;
                        }, 1000);
                    },
                    error: function (xhr) {

                        let msg = 'Terjadi kesalahan';

                        if (xhr.responseJSON?.message) {
                            msg = xhr.responseJSON.message;
                        }

                        alertBox
                            .removeClass('hidden bg-green-500/10 text-green-400')
                            .addClass('bg-red-500/10 text-red-400')
                            .text(msg);

                        grecaptcha.reset(); // reset captcha kalau gagal
                        btn.prop('disabled', false).text('Login');
                    }
                });
            });

        });
    </script>

</body>

</html>

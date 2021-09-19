<?php

use Illuminate\Support\Str;

?>

<!doctype html>
<html lang="<?php  app()->getLocale(); ?>">
<head>
    <meta charset="UTF-8"/>
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0"/>
    <meta http-equiv="X-UA-Compatible" content="ie=edge"/>
    <link rel="shortcut icon" href="{{ env('APP_URL').config('settings.application.company_icon') }}" />
    <link rel="apple-touch-icon" href="{{ env('APP_URL').config('settings.application.company_icon') }}" />
    <link rel="apple-touch-icon-precomposed" href="{{ env('APP_URL').config('settings.application.company_icon') }}" />
    <title>{{ trans('default.login') }} - {{ config('app.name') }}</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    @stack('before-styles')
    {{ style(mix('css/core.css')) }}
    {{ style(mix('css/fontawesome.css')) }}
    @stack('after-styles')
</head>
<body>
<div class="root-preloader position-absolute overlay-loader-wrapper">
    <div class="spinner-bounce d-flex align-items-center justify-content-center h-100">
        <span class="bounce1 mr-1"></span>
        <span class="bounce2 mr-1"></span>
        <span class="bounce3 mr-1"></span>
        <span class="bounce4"></span>
    </div>
</div>

<div id="app">
    <app-auth-login @if(env('MARKET_PLACE_VERSION')) :market-place-version="{{env('MARKET_PLACE_VERSION')}}" @endif previous-url="{{ Str::of(url()->previous())->contains(env('APP_URL')) ? url()->previous() : env('APP_URL')}}" :config-data="{{json_encode(config('settings.application'))}}"></app-auth-login>
</div>
<script>
    window.settings = <?php echo json_encode($settings ?? '') ?>
</script>
<script>
    if(!window.localStorage.getItem('app-language')){
        // initital language added
        window.localStorage.setItem('app-language',"en");
    };
    window.localStorage.setItem('base_url', '<?php echo request()->root(); ?>');
</script>
<script>
    window.localStorage.setItem('app-languages',
        JSON.stringify(
            @php
                echo
                    json_encode(
                        include resource_path()
                            . DIRECTORY_SEPARATOR . 'lang'
                            . DIRECTORY_SEPARATOR .
                            (
                                Cookie::has('user_lang') ? Cookie::get('user_lang') : ($settings['language'] ?? 'en')
                            )
                            . DIRECTORY_SEPARATOR . 'default.php'
                    )
            @endphp
        )
    );
    window.localStorage.setItem('base_url', '<?php echo request()->root(); ?>');
    window.addEventListener('load', function() {
        document.querySelector('.root-preloader').remove();
    });
</script>
@stack('before-scripts')
{!! script(mix('js/manifest.js')) !!}
{!! script(mix('js/vendor.js')) !!}
{!! script(mix('js/core.js')) !!}
@stack('after-scripts')
</body>
</html>

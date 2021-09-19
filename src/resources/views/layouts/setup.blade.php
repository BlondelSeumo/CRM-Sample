<html lang="<?php  app()->getLocale(); ?>">
<head>
    <meta charset="UTF-8"/>
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0"/>
    <meta http-equiv="X-UA-Compatible" content="ie=edge"/>
   
    <link rel="shortcut icon" href="{{ config('settings.application.company_icon') }}" />
    <link rel="apple-touch-icon" href="{{ config('settings.application.company_icon') }}" />
    <link rel="apple-touch-icon-precomposed" href="{{ config('settings.application.company_icon') }}" />
    <title>{{ __t('install') }} - {{ config('app.name') }}</title>
    @include('layouts.includes.header')
</head>
<body>
<div id="app">
    <div class="container">
        @yield('contents')
    </div>
</div>
<script>
    window.localStorage.setItem('app-languages',
        JSON.stringify(
            <?php echo json_encode(include resource_path().DIRECTORY_SEPARATOR.'lang'.DIRECTORY_SEPARATOR.(app()->getLocale() ?? 'en').DIRECTORY_SEPARATOR.'default.php')?>
        )
    );
    window.localStorage.setItem('base_url', '<?php echo request()->root(); ?>');
</script>

<script>
    window.localStorage.setItem('app-language', '<?php echo app()->getLocale() ?? "en"; ?>');
    window.appLanguage = window.localStorage.getItem('app-language');
</script>
@include('layouts.includes.footer')
</body>
</html>

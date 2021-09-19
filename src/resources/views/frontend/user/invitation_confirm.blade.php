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
    <title>{{ trans('default.complete_invitation') }} - {{ config('app.name') }}</title>
    @stack('before-styles')
    {{ style(mix('css/core.css')) }}
    {{ style(mix('css/fontawesome.css')) }}
    @stack('after-styles')
</head>
<body>

<div id="app">
    <complete-user-invitation :user="{{json_encode($user)}}" :config-data="{{json_encode(config('settings.application'))}}"></complete-user-invitation>
</div>
<script>
    window.settings = <?php echo json_encode($settings) ?>
</script>
<script>
    window.localStorage.setItem('app-language', '<?php echo app()->getLocale() ?? "en"; ?>');
    window.localStorage.setItem('app-languages',
        JSON.stringify(
            <?php echo json_encode(include resource_path().DIRECTORY_SEPARATOR.'lang'.DIRECTORY_SEPARATOR.(app()->getLocale() ?? 'en').DIRECTORY_SEPARATOR.'default.php')?>
        )
    );
    window.localStorage.setItem('base_url', '<?php echo request()->root(); ?>');
</script>
@stack('before-scripts')
{!! script(mix('js/manifest.js')) !!}
{!! script(mix('js/vendor.js')) !!}
{!! script(mix('js/core.js')) !!}
@stack('after-scripts')
</body>
</html>

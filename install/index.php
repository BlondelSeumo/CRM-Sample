<?php
include 'getResult.php';

$result = getResult();

$is_errors = isset($result['errors']) ? $result['errors'] : false;

$is_supported = $result['is_supported'];

if ($is_supported) {
    $sub_folder = str_replace('index.php', '', $_SERVER['PHP_SELF']);
    $sub_folder = str_replace('install', '', $sub_folder);
    $base_url = rtrim($sub_folder, '/');
    $base_url = $base_url ? $base_url.'/' : '/';
    echo '<script>window.location = "'.$base_url.'setup/environment"</script>';
}

?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8"/>
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0"/>
    <meta http-equiv="X-UA-Compatible" content="ie=edge"/>
    <link rel="stylesheet" type="text/css" href="../../css/core.css" />
    <link rel="stylesheet" type="text/css" href="../../css/fontawesome.css" />
</head>
<body>

<div id="app">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-sm-8 py-5">
                <h3 class="text-center text-capitalize mb-primary">
                    Required Environments
                </h3>
                <div class="font-size-default">
                    <div id="accordionExample" class="accordion">
                        <?php if (!$result['php']['supported'] || $is_errors) { ?>
                            <div class="card card-with-shadow border-0 p-primary">
                                <div class="card-header bg-transparent border-0 px-0 pt-0">
                                    <span href="#"
                                       data-toggle="collapse"
                                       data-target="#requirements"
                                       aria-expanded="false"
                                       aria-controls="permission"
                                       class="d-block position-relative text-capitalize"
                                    >
                                        PHP
                                        <span class="font-size-90">
                                        Version <?php echo $result['php']['minimum'] ?> required
                                    </span>
                                        <div class="float-right">
                                            <?php
                                            $class = $result['php']['supported'] ? 'text-success': 'text-danger'
                                            ?>
                                            <span class="<?php echo $class ?>">
                                            <?php echo $result['php']['current'] ?>
                                        </span>
                                        </div>
                                    </span>
                                </div>

                                <?php
                                $php_requirements = $result['requirements']['php'];

                                $requirements = array_filter(array_keys($php_requirements), function ($requirement) use ($php_requirements) {
                                    return !$php_requirements[$requirement];
                                });

                                if (count($requirements)) {
                                    ?>
                                    <div class="card-body p-0">
                                        <div id="requirements"
                                             data-parent="#accordionExample"
                                             class="collapse show">
                                            <div class="card-body p-0 border-0">
                                                <ul class="list-group" >
                                                    <?php
                                                    foreach ($php_requirements as $key => $requirement) {
                                                        if (!$requirement) {
                                                        ?>
                                                        <li class="border-0 list-group-item d-flex justify-content-between align-items-center px-0" >
                                                            <?php echo ucfirst($key) ?>
                                                            <?php if(!$requirement){ ?>
                                                                <span class="text-danger">&times</span>
                                                            <?php } ?>
                                                        </li>
                                                    <?php } } ?>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                  <?php
                                }
                                ?>
                            </div><br/>
                        <?php } ?>
                        <?php
                        $permissions_required = $result['permissions']['permissions'];

                        $permissions = array_filter($permissions_required, function ($permission) {
                            return !$permission['isSet'];
                        });

                        $permissions = array_map(function ($permission) {
                            return $permission['folder'];
                        }, $permissions);

                        $string = implode(', ', $permissions);

                        if (count($permissions)) {
                            ?>
                            <div class="note-title d-flex">
                                <span class="fa fa-book-open"></span>
                                <h6 class="card-title pl-2">Attention </h6>
                            </div>

                            <div class="note note-warning p-4">
                                <p class="m-1 text-muted"><b><?php echo $string ?></b> from <b>src</b> directory required server write permission to install and run the apps. You can remove write permission of <b>src/.env</b> after installation.</p>
                            </div>
                        <?php } ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script src="../../js/vendor.js"></script>
</body>
</html>

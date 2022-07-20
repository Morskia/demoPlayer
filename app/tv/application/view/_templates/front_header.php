<!DOCTYPE html>
<html class="h-100">
<head>
    <title>MrMusic<?php
        //		if ( isset( $content->title ) ) {
        //			echo " - " . $content->title;
        //		}
        //		if ( isset( $musician->username ) ) {
        //			echo " - " . $musician->username;
        //		}
        //		if ( isset( $song->name ) ) {
        //			echo " - " . $song->name;
        //		}
        //		if ( isset( $songs[0]->cattitle ) ) {
        //			echo " - " . $songs[0]->cattitle;
        //		}
        //		if ( isset( $posts[0]->catTitle ) ) {
        //			echo " - " . $posts[0]->catTitle;
        //		}
        ?></title>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport"
          content="width=device-width, initial-scale=1, maximum-scale=1.0, user-scalable=no, shrink-to-fit=no">


    <link rel="apple-touch-icon" sizes="180x180" href="<?php echo URL;?>apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="<?php echo URL;?>favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="<?php echo URL;?>favicon-16x16.png">
    <link rel="manifest" href="<?php echo URL;?>site.webmanifest">
    <meta name="msapplication-TileColor" content="#da532c">
    <meta name="theme-color" content="#ffffff">


    <meta name="description" content="<?php
    mb_internal_encoding("UTF-8");
    $match = true;
    if (isset($content->body)) {
        $match = false;
        // echo mb_substr( strip_tags( html_entity_decode(preg_replace('/[\x00-\x1F\x7F\xA0]/u', '', $content->body ))), 0, 200 );
    }
    if (isset($song->description)) {
        $match = false;
        //echo mb_substr( strip_tags( html_entity_decode(preg_replace('/[\x00-\x1F\x7F\xA0]/u', '', $song->description ))), 0, 200 );
    }
    if (isset($posts[0]->catContent)) {
        $match = false;
        //echo mb_substr( strip_tags(html_entity_decode( preg_replace('/[\x00-\x1F\x7F\xA0]/u', '', $posts[0]->catContent  ))), 0, 200 );
    }
    if (isset($songs[0]->catContent)) {
        $match = false;
        //echo mb_substr( strip_tags( html_entity_decode(preg_replace('/[\x00-\x1F\x7F\xA0]/u', '',$songs[0]->catContent  )) ), 0, 200 );
    }
    if ($match) {
        echo " ";
    }
    ?>">
    <meta name="author" content="MrMusic">
    <!--    <link rel="manifest" href="manifest.webmanifest">-->
    <script src="https://kit.fontawesome.com/53aa1a9925.js" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css"
          crossorigin="anonymous">
    <link href="<?php echo URL; ?>css/colors.css?v=<?php echo time(); ?>" rel="stylesheet">
    <link href="<?php echo URL; ?>css/front.css?v=<?php echo time(); ?>" rel="stylesheet">
    <link href="<?php echo URL; ?>css/player.css?v=<?php echo time(); ?>" rel="stylesheet">
    <link rel="stylesheet"
          href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.13.1/css/bootstrap-select.css"/>
    <link href="<?php echo URL; ?>vendor/datatables/dataTables.bootstrap4.min.css" rel="stylesheet">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Comfortaa:wght@300;600&display=swap" rel="stylesheet">
    <script> const URL = "<?php echo URL; ?>"; </script>
    <script src="https://code.jquery.com/jquery-3.4.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.20/js/jquery.dataTables.min.js"></script>
    <script src="<?php echo URL; ?>vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.2.3/js/dataTables.responsive.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.13.1/js/bootstrap-select.min.js"></script>
    <script src="<?php echo URL; ?>js/owl.carousel.min.js"></script>

</head>

<body id="page-top" class="position-relative pt-5 mt-5" style="background: #fff">
<nav class="navbar navbar-expand-lg  fixed-top">
    <div class="cotnainer container p-0 my-0 mx-auto">
        <a class="navbar-brand" href="<?php echo URL; ?>"><img src="<?php echo URL; ?>img/small.png"></a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarsExample09"
                aria-controls="navbarsExample09" aria-expanded="false" aria-label="Toggle navigation">
            <img src="<?php echo URL; ?>img/small.png">



        </button>

        <div class="collapse navbar-collapse" id="navbarsExample09">
            <ul class="navbar-nav mr-auto">

<!--                <li class="nav-item active"><a class="nav-link" href="--><?php //echo URL; ?><!--">Музика</a></li>-->
<!--                <li class="nav-item active"><a class="nav-link" href="--><?php //echo URL; ?><!--видео">Видео</a></li>-->
<!--                <li class="nav-item active"><a class="nav-link" href="--><?php //echo URL; ?><!--какво-е-mrmusic-">Kакво е Mr.Music </a></li>-->
<!--                <li class="nav-item active"><a class="nav-link" href="--><?php //echo URL; ?><!--контакти-с-mrmusic">Контакти</a></li>-->
<!--                <li class="nav-item active"><a class="nav-link" href="--><?php //echo URL; ?><!--как-да-ползвам">Права за ползване</a></li>-->
<!--                <li class="nav-item active"><a class="nav-link" href="--><?php //echo URL; ?><!--включи-се">ВКЛЮЧИ СЕ</a></li>-->
<!--                <li class="nav-item active"><a class="nav-link" href="--><?php //echo URL; ?><!--новини">Новини</a></li>-->



            </ul>
            <ul class="form-inline navbar-nav my-2 my-md-0">
                <li class="nav-item">
                    <?php if (isset($_SESSION['user'])) { ?>
                        <a class="nav-link"
                           href="<?php echo URL; ?>login/logout">   <?php echo $_SESSION['lang'] == 'en' ? 'Hello' : 'Здравей'; ?> <?php echo $_SESSION['user']; ?>
                            ,
                           Излез </a>
                    <?php } else { ?>
                        <a href="<?php echo URL; ?>login"
                           class="nav-link text-light d-block btn bg-green">Влез</a>
                    <?php } ?>
                </li>


            </ul>
        </div>
    </div>
</nav>
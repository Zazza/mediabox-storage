<?php

require_once __DIR__.'/../vendor/autoload.php';

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Fm\Components\Files;
use Fm\Components\Save;

mb_internal_encoding('utf-8');

$app = new Silex\Application();

$app->register(new Silex\Provider\TwigServiceProvider(), array(
    'twig.path' => __DIR__.'/silex/views',
));
$app->register(new Silex\Provider\SessionServiceProvider());
$app->register(new Silex\Provider\UrlGeneratorServiceProvider());

$app['debug'] = false;

$app['filetypes'] = array(
    'bmp' => 'image/bmp',
    'jpg' => 'image/jpeg ',
    'jpeg' => 'image/jpeg ',
    'gif' => 'image/gif ',
    'png' => 'image/png',
    'ogg' => 'audio/ogg',
    'mp3' => 'audio/mp3',
    'mp4' => 'video/mp4',
    'mov' => 'video/quicktime ',
    'wmv' => 'video/x-ms-wmv',
    'flv' => 'video/x-flv',
    'avi' => 'video/x-msvideo ',
    'mpg' => 'video/mpeg'
);

$ini_path['upload']  = "upload/";
$app["rel_upload"] = $ini_path['upload'];
$app["upload"] = __DIR__ . "/" . $ini_path['upload'];

$app['save'] = $app->share(function () use ($app) {
    return new Save($app);
});

$app['files'] = $app->share(function () use ($app) {
    return new Files($app);
});

$app->get('/', function (Request $request) use ($app) {
    return new Response("welcome", 200);
});

// upload files
$app->match('/save/', function (Request $request) use ($app) {
    $save = new Save($app);

    if ($save->handleUpload("[" . $request->request->get("id") . "][" . $request->request->get("name") . "]")) {
        return new Response("", 200);
    } else {
        header('Access-Control-Allow-Origin: *');
        header('Access-Control-Allow-Methods: POST, GET, OPTIONS');
        header('Access-Control-Allow-Headers: X-Requested-With, Content-Type');
        header('Access-Control-Max-Age: 600');

        return new Response("", 200);
    }
});

$app->get('/get/', function (Request $request) use ($app) {
    $app["files"]->set($request->query->get("id"));

    if (file_exists($app["files"]->path)) {
        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $content_type = finfo_file($finfo, $app["files"]->path);
        finfo_close($finfo);

        return $app["files"]->streaming($content_type);
    }

    return new Response("", 404);
});

$app->get('/remove/', function (Request $request) use ($app) {
    $app["files"]->set($request->query->get("id"));

    if ($app["files"]->rmFiles()) {
        return new Response($request->query->get("callback") . "('')", 200);
    } else {
        return new Response("", 500);
    }
});

$app->run();

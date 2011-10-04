# Gaelic PHP is a PHP 5.3+ routing framework...

Because we needed another one of those. The aim of Gaelic is to implement ideas found in the Google App Engine "webapp" framework in PHP 5.3+, making it GAE-like. Ah, puns.

## Goals

Primarily, **I want Gaelic to be easy to use** for new developers and myself. For me, that means **unit testing the core components** so that I know what's going on, **keeping a small code footprint** because that affects performance, testing, and throughput, and having **multiple entry points** so that I can use what I need, when I need it. If I have **less to remember,** I have **less to teach,** and you have **less to learn** before you're productive. That means staying focused on a single task -- routing requests and rendering responses -- and doing that well. Thus, **Gaelic is __just__ a routing framework; nothing more.**

## Theory of Operation

The `App` object implements a Singleton pattern. It's the gateway for everything: bootstrapping, autoloading, androuting. It needs to know where your application and where your libraries exist, usually by providing it a `ROOT_PATH` and a list of relative `include_path` directories. `App::init()` minimally accepts those options, along with a list of `Route` definitions. From there, `App::run()` starts the show.

Behind the scenes, the `App` instantiates a new `Request` obejct, representing the HTTP request as reported by the various values in the `$_SERVER` superglobal and attempt to match the requested URI to one of the `Route` definitions. A `Route` simply matches an application URI to a callable that can handle the `Request` and return a `Response`, which the `App` renders via `echo`. Thus, even a simple string is a valid response.

The simplest callable provided to a `Route` accepts a `Request` and returns a string or a "stringable" `Response`, which is great for just processing a static page template or issuing a redirect (see `RedirectResponse`). For more complex processing, consider using a `Handler` subclass, which has a set of methods that correspond to the HTTP verbs: `GET`, `POST`, `PUT`, `PATCH`, `DELETE`, and `OPTIONS`. Here's an example:

    // Initialize the App...
    $app = App::init(realpath(.), array(
        'routes' => array(
            array('/', 'HelloWorldPage'), // Setup our route...
        ),
    ));

    // Initialize a reusable Request instance...
    $request = new Request('/');

    // Let's start with a GET request...
    $request->setMethod(Request::METHOD_GET);

    $app->run($request); // Will invoke HelloWorldPage::get()

    // What about a POST request...?
    $request->setMethod(Request::METHOD_POST)->setPost(array(
        'foo' => 'bar',
    ));

    $app->run($request); // Will invoke HelloWorldPage::post().


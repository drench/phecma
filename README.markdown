phecma
======

The beginnings of an ECMAScript to PHP 5.3 compiler, written in Ruby
of all things.

Most of the heavy lifting courtesy of http://rubyforge.org/projects/rkelly/
(and the license reflects this)

* ## Why?

    PHP is everywhere and is inescapable these days.

    A typical hosting plan will include PHP. Maybe not PHP 5.3 yet
    (which is what phecma currently "targets"), but definitely some version.
    And "regular people" like PHP, or more accurately, they like stuff
    written in PHP: Wordpress, Drupal, Magento, SugarCRM, and just about
    every other common CMS, shopping cart, and bulletin board system.

    Irregular people (programmers) typically aren't so into PHP.
    Programmers may not agree on many things but they do tend to think
    JavaScript is not too bad (that's high praise, BTW).
    It's everybody's 2nd favorite language.

    I don't know about you, but when given a task to, say, write a
    plugin for some PHP app, I would love to write it in a language I
    enjoy using. Sure, I could always throw out the entire codebase and
    start over in Node.js or something, but I don't have time for that.

    Or more accurately, my clients do not want to pay me to do that.
    And even if they did, they won't necessarily be happy clients when
    they find out that their "Wordpress" site won't let them install
    Wordpress plugins, because they're not actually running Wordpress
    anymore, but rather some incompatible custom blog engine running on
    a strange new platform.

    Short version: phecma could be part of a piecemeal transition to
    a better place. No need to throw out the entire codebase and platform.
    Boil that PHP frog slowly. Eventually, it's all ECMAScript.
    When that happens, this is like the Wordpress Singularity or something.

    But don't get too excited.

* ## Current state

    Though only basic code will work (meaning many of ECMAScript's cool
    functional programming features are still missing), it may be enough
    to be useful.

    I'm beginning to implement the http://commonjs.org/ standards,
    starting with XMLHttpRequest, which is far enough along to actually
    make requests, assuming your PHP installation has the curl library.

    Here's a sample:

        var req = require('xhr').XMLHttpRequest;
        req.defaultSettings.host = 'http://github.com/';
        var client = req();
        client.getRequestHeader('Referer', 'http://example.com/');
        print(client.open('GET', 'drench/phecma').send().responseText);
        print(client.getResponseHeader('Server'));

    This is what it looks like when piped through phecma:

        <?php
        require_once('./php-lib/commonjs.php');
        $req = CommonJS::_require('xhr')->XMLHttpRequest;
        $req->defaultSettings->host = 'http://github.com/';
        $client = $req();
        $client->setRequestHeader('Referer', 'http://example.com/');
        echo($client->open('GET', 'drench/phecma')->send()->responseText);
        echo($client->getResponseHeader('Server'));
        ?>

    Most of the action is happening inside the commonjs PHP classes.

    The generated code also depends on features that only exist in PHP 5.3+
    (anonymous functions), which I know limits its utility.
    But I think this has potential.
    And you?

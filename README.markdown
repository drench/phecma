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

    Though only basic code will work (meaning some of ECMAScript's cool
    features are still missing), it may be enough to be useful.

    I'm beginning to implement the http://commonjs.org/ standards.

    XMLHttpRequest is far enough along to actually make requests,
    assuming your PHP installation has the curl library.

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

    Many of the filesystem operations work now too. Sample input:

        var fs = require('fs');

        if (fs.isReadable('/etc/hosts')) {
            etchosts = fs.read('/etc/hosts');

            fs.open('/tmp/fstest', 'w').write("some stuff\n");
            fs.open('/tmp/fstest', 'a').write("more stuff\n");

            fs.copy('/etc/hosts', '/tmp/hosts');
        }

    And translated to PHP:

        <?php
        require_once('./php-lib/commonjs.php');
        $fs = CommonJS::_require('fs');
        if($fs->isReadable('/etc/hosts')) {
          $etchosts = $fs->read('/etc/hosts');
          $fs->open('/tmp/fstest', 'w')->write("some stuff\n");
          $fs->open('/tmp/fstest', 'a')->write("more stuff\n");
          $fs->copy('/etc/hosts', '/tmp/hosts');
        }
        ?>

    Most of the action is happening inside the commonjs PHP classes.

    Anonymous functions and closures (mostly) work too! The big caveat is
    phecma depends on features of PHP 5.3+ for these, which I know
    limits its utility. One of the main reasons I started writing phecma
    was because, even if PHP's closures are essentially the same, I like
    the ECMAScript syntax for it a lot more.

    Here's a version of a classic closure example, the "counter" maker:

        function make_counter (n) {
            var ctr = function () {
                n++; // because ++n is buggy
                return n;
            };
            return ctr;
        }

        var c = make_counter(15);
        var d = make_counter(7);

        // using '+' to concatenate strings doesn't work yet
        print(c()); print("\t"); print(d()); print("\n");
        print(c()); print("\t"); print(d()); print("\n");
        print(c()); print("\t"); print(d()); print("\n");
        print(c()); print("\t"); print(d()); print("\n");

    PHP-ified:

        <?php
        require_once('./php-lib/phecma.php');
        require_once('./php-lib/commonjs.php');
        function make_counter($n) {
          $ctr = function() use (&$n, &$ctr) {
            $n++;
            return($n);
          };
          return($ctr);
        }
        $c = make_counter(15);
        $d = make_counter(7);
        echo($c());
        echo("\t");
        echo($d());
        echo("\n");
        echo($c());
        echo("\t");
        echo($d());
        echo("\n");
        echo($c());
        echo("\t");
        echo($d());
        echo("\n");
        echo($c());
        echo("\t");
        echo($d());
        echo("\n");
        ?>

    I think this has potential. And you?

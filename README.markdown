phecma
======

The beginnings of an ECMAScript (JavaScript) to PHP 5.3 compiler,
written in Ruby of all things.

Quick start
-----------

I lied. This might not be all that quick.

First, you need Ruby 1.9. I'm using some 1.9.2 release candidate but it
may work with 1.9.1 too. I don't know. And if you manage to get it
working under 1.8, wonderful. This is github: fork, patch, and send a
pull request.

Next, you'll need to install RKelly, a JavaScript parser written in Ruby.
But not just any RKelly; you need to install my fork of RKelly:

    % git clone git://github.com/drench/rkelly.git
    % cd rkelly
    % rake gem
    % sudo gem install ./pkg/rkelly-1.0.2.gem

But wait! RKelly depends on hoe and racc so if you don't have them yet...

    % sudo gem install hoe
    % sudo gem install racc

Though you don't need a PHP installation to run the compliler, you need
one to run the code it spits out. That should be obvious, right?

Phecma's compiler and supporting libraries lean heavily on anonymous
functions, and these did not appear in PHP until 5.3.0.
I'm using PHP 5.3.2. Think you can get it to work on PHP < 5.3 ?
Fork, patch, and send a pull request.

Finally! Let's get phecma so we can compile and run something:

    % git clone git://github.com/drench/phecma.git
    % cd phecma
    % ./phecma < ./examples/hello.js | php
    Hello world!

If you have a js executable in your $PATH -- perhaps you've installed
spidermonkey or Rhino -- give the test suite a try:

    % ./run-tests
    test/arithmetic.js .. ok
    test/arrays.js .. ok
    test/closures.js .. ok
    test/concatstring.js .. ok
    test/functions.js .. ok
    test/printstring.js .. ok
    test/regex.js .. ok

Why?
----

PHP is everywhere and is inescapable these days.

A typical hosting plan includes PHP. Maybe not PHP 5.3 yet,
but definitely some version. And "regular people" like PHP, or more
accurately, they like stuff written in PHP: Wordpress, Drupal,
Magento, SugarCRM, and just about every other common CMS, shopping cart,
and bulletin board system.

Irregular people (programmers) typically aren't so keen on PHP.
Programmers may not agree on many things but they do tend to think
JavaScript is not too bad, and that's high praise, BTW.
It's everybody's 2nd favorite language.

I don't know about you, but when given a task to, say, write a
plugin for some PHP app, I would love to write it in a language I
enjoy using. Sure, I could always throw out the entire codebase and
start over in Node.js or something, but I don't have time for that.

Or more truthfully, my clients do not want to pay me to do that.
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

Current state
-------------

The basic Array, Math, RegExp, Object, and String classes
are in place and work as they should, with a few exceptions.
Hey, it's early. Fork, patch, send-pull-request, remember?

Anonymous functions and closures (mostly) work too!
There is a version of the classic closure example, a "counter" maker,
at ./examples/classic-closure-counter.js

Semicolons are not optional! The "official" version of RKelly takes
the official semicolons-are-optional stance (as it should). But there
is a bug in the parser in handling prefix "--" and "++" and when I forked
& "fixed" it, optional semicolons no longer were. I'm not a fan of
leaving off semicolons anyway, so I'm fine with that.

Many (most?) of your common JavaScript engines (I'm thinking of
Spidermonkey and Rhino) have a print() function that, well, prints
stuff in the traditional programming meaning of "print". Don't confuse
this with the print() function that exists in web browser implementations,
which sends the current page to your printer.

Anyway, these "traditional" print() functions tack on newlines without
you asking for them. Phecma's print() is a straight translation to PHP's
echo(), which doesn't tack on newlines. I could change this, but there
are legitimate reasons to call print() and not want automatic newlines.
So for now, if you want newlines, add them yourself.

I hope you don't mind but I didn't even try to implement eval(), and
I don't see it happening anytime soon. We are sorry for any inconvenience.
Does anyone use eval() for anything legitimate other than "parsing" JSON?

Phecma is following the http://commonjs.org/ standards, as they are:

* XMLHttpRequest is far enough along to actually make requests, assuming your PHP installation has the curl library. (see ./examples/xhr.js)

* Filesystem operations are pretty much there too, though it's "lightly tested" to be generous. (see ./examples/filesystem.js)

* JSGI is far enough along to handle very basic requests (no POSTs yet even; see ./examples/jsgi.js)

So
--

I think this has potential. And you?

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

    I've only put an hour or two into this so far, and only the most basic
    code will compile. Here's some JS source:

        var one = function () { return 1; };
        var two = function () {
            var t = 2;
            return t;
        };
        
        try {
            var eins = one();
            var zwei = two();
            var drei = eins + zwei;
        
            if (drei > 1) {
                drei *= -1;
            }
            else {
                drei = drei * 2;
            }
        }
        catch (err) {
            // whatever
        }

    This is what it looks like when piped through phecma right now:

        <?php
        $one = function() {
          return(1);
        };
        $two = function() {
          $t = 2;
          return($t);
        };
        try {
          $eins = $one();
          $zwei = $two();
          $drei = $eins + $zwei;
          if($drei > 1) {
            $drei *= -1;
          } else {
            $drei = $drei * 2;
          }
        } catch($err) {
        
        }
        ?>

    Yes, that's PHP 5.3's anonymous function syntax, which probably means
    you can't use this yet. So it may not yet be useful (there's not even
    an equivalent of "print" or "echo" yet!), but I think this has potential.
    And you?

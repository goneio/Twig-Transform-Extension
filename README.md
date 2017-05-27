Twig-Transform
==============
[![Build Status](https://travis-ci.org/goneio/Twig-Transform-Extension.svg?branch=master)](https://travis-ci.org/goneio/Twig-Transform-Extension)

Add the ability to transform from one case style to another programatically.

Example:

`{{ input|transform_camel_to_snake }}` will output `this_is_an_example` for the input `thisIsAnExample`

###Supported transformers:

Any combination of the following:

 * Camel `thisIsAnExample`
 * Snake `this_is_an_example`
 * Screaming Snake `THIS_IS_AN_EXAMPLE`
 * Spinal `this-is-an-example`
 * Studly Caps `ThisIsAnExample`
 
 using the format 'transform-`from`-to-`target`'
 
#### Credits
Written ontop of the wonderful [Camel](https://github.com/MattKetmo/camel) package written by `Matthieu Moquet` 
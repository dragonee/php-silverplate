php-boilerplate
===============

PHP Boilerplate is the ultimate code for your static website needs.
Written in less than 200 lines, it is a small app that allows you to
care only about the content you are writing.


## Quickstart Guide

This project has submodules. Make sure to
    $ git submodule update --init 
them.

Modify index.php file and put inside some content. Then modify the
layout.php file to put some style declarations and so on. Then, access
the main directory of PHP Boilerplate installation.

## Features

- single layout.php file for the whole website (with an ability to
specify layout file on a per-file basis).
- file formats: .php for HTML/PHP files, .md for Markdown, .redir for
redirects to other URLs.
- Markdown files are parsed by excellent PHP Markdown library:
http://michelf.ca/projects/php-markdown/
- pretty URL rewriting with .htaccess
- simple structure - place text.php in the website directory and access 
it via the example.com/text/ address.
- nested directories are also supported.
- single 404.php file for your convenience.

vim: set tw=72:

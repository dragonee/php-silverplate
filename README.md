php-silverplate
===============

Forget to include header.php and footer.php anymore.

PHP Silverplate serves your static content on a silver platter. It does
not matter if you need to write simple, one-page website, or a whole book - PHP
Silverplate accepts any **HTML** or **Markdown** file and displays it
inside a predetermined layout file. Pretty URLs and HTML5 Boilerplate
included.

## Quickstart Guide

Use Composer to download the stable version:

    $ curl -s https://getcomposer.org/installer | php
    $ php composer.phar create-project dragonee/php-silverplate

Make your changes in the `index.php` file. Add necessary style
declarations to the `css/main.css`. Then access the main directory of PHP Silverplate 
installation with your web browser.

## Features

- single `layout.php` file for the whole website (with an ability to
specify layout file on a per-file basis).
- file formats: `.php` for HTML/PHP files, `.md` for Markdown, `.redir` for
redirects to other URLs.
- Markdown files are parsed by excellent PHP Markdown library:
http://michelf.ca/projects/php-markdown/
- pretty URL rewriting with `.htaccess`
- simple structure - place `text.php` in the website directory and access 
it via the `example.com/text/` address.
- nested directories are also supported.
- single `404.php` file for your convenience.
- HTML5 Boilerplate v4.0.2 - you can go straight to the business.

## Usage

When an user navigates to the arbitrary URI, for example `/example`,
`app.php` begins to search for corresponding file in the following order:

- At first, it checks for an directory named `example`. If such directory
exists, `app.php` sets its URI internally to `/example/index` and resets the
search.
- Next, a file with the name `example.md` is looked up. If it exists, it
is parsed as Markdown file and rendered in the `layout.php` file.
- Next, a file with the name `example.php` is looked up. If it exists, it
is treated as the PHP file and rendered in the `layout.php` file.
- Next, a file with the name `example.redir` is looked up. If it exists,
the script returns 302 Found response and redirects to the link
specified in the contents of the .redir file.
- If no file is found, `404.php` is displayed and the application responds
with 404 Page Not Found response. `404.php` is rendered independently
from the layout file.

`app.php` is completely transparent and relies on the filesystem to find
files. That means you are able to structure your files in subdirectories
any way you want.

`app.php` does not allow accessing files that  are outside the directory
`app.php` is located in. This also affects symbolic links.

### Blocks

You can provide blocks in the layout file that can be overwritten by
data specified in the content file. The most common use case is to
provide the `<title>` tag of the document.

In order to define a block, put a `get()` function in the layout file:

    <title><?php echo get('title', 'Default Title') ?></title>

You can override the default value of the title block by setting it in
the content file:

    <?php meta('title', 'Another page') ?>

Or in the Markdown file:

    Meta title: Another page

In PHP content files you can also `open()` and `close()` multiline blocks:

    <?php open('javascripts') ?>
        <script type="text/javascript" src="js/jquery-1.7.2.min.js"></script>
        <script type="text/javascript" src="js/jquery.plugin.js"></script>
        [...]
    <?php close('javascripts') ?>

Currently Markdown files do not have any support for the opening and
closing blocks.

### Custom Layouts

In order to specify custom layout file for a content file, fill a layout
block:

    <?php meta('layout', 'my_layout') ?>

or:

    Meta layout: my_layout

Make sure, that the `my_layout.php` file is present before using it.

### Relative URLs

With the release of v0.8.1, PHP Silverplate gives you a way to write
paths in your layout and content files in a relative maneer to the
application root directory.

- Use `path://some-image.png` in your Markdown files.
- Use `App::path('some-image.png')` in your PHP files.

### Design

You can style your files differently based on their type. `app.php`
provides your `layout.php` file with two different classes:

- `.php-file` for PHP files.
- `.md-file` for Markdown files.

These classes can help you distinguish between styles specific for your
Markdown document layouts and between your PHP layouts.

The `layout.php` provided in this distribution also defines the `classes` 
block, which can be used to build some conditional CSS styles for some
files on your website.

<!-- vim: set tw=72: -->

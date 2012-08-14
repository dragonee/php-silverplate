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

## Usage

When an user navigates to the arbitrary URI, for example /example,
app.php begins to search for corresponding file in the following order:

- At first, it checks for an directory named example. If such directory
exists, app.php sets its URI internally to /example/index and resets the
search.
- Next, a file with the name example.md is looked up. If it exists, it
is parsed as Markdown file and rendered in the layout.php file.
- Next, a file with the name example.php is looked up. If it exists, it
is treated as the PHP file and rendered in the layout.php file.
- Next, a file with the name example.redir is looked up. If it exists,
the script returns 302 Found response and redirects to the link
specified in the contents of the .redir file.
- If no file is found, 404.php is displayed and the application responds
with 404 Page Not Found response. 404.php is not rendered into the
layout file.

app.php is completely transparent and relies on the filesystem to find
files. That means you are able to structure your files in subdirectories
any way you want.

app.php does not allow accessing files that  are outside the directory
app.php is located in. This also affects symbolic links.

### Blocks

You can provide blocks in the layout file that can be overwritten by
data specified in the content file. The most common use case is to
provide the &lt;title&gt; tag of the document.

In order to define a block, put a get() function in the layout file:

    <title><?php echo get('title', 'Default Title') ?></title>

You can override the default value of the title block by setting it in
the content file:

    <?php meta('title', 'Another page') ?>

Or in the Markdown file:

    Meta title: Another page

In PHP content files you can also open() and close() multiline blocks:

    <?php open('javascripts') ?>
        <script type="text/javascript" src="js/jquery-1.7.2.min.js"></script>
        <script type="text/javascript" src="js/jquery.plugin.js"></script>
        [...]
    <?php close('javascripts') ?>

Currently Markdown files do not have any support for the opening and
closing blocks.

vim: set tw=72:

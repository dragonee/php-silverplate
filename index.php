<?php meta('title', 'Hello World') ?>

<h1>Hello World</h1>

<p>If you see this message, PHP Silverplate is working correctly.</p>

<p>This message is located in the index.php file in the main directory of your website. This file responds to your main site index, you can modify it to your needs.</p>

<p>PHP Silverplate can also render Markdown files. You can access the documentation in form of the <a href="README">README.md file</a> to see how it works.</p>

<p>The layout.php file is responsible for the headers and footers of your website. In this file you can put your style declarations, JavaScripts and other data.</p>

<?php open('footer') ?>
<p>The contents of this &lt;footer&gt; tag are defined in the index.php file.</p>
<?php close('footer') ?>

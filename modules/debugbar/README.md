### About

Debugbar displays useful benchmarks and application data, such as DB metrics and Global arrays for Kohana 3.1.  It is forked from <https://github.com/biakaveron/debug-toolbar> which is originally based on "Kohana Debug Toolbar" <http://pifantastic.com/kohana-debug-toolbar/> for Kohana v2.3 (by Aaron Forsander).

### Changes (from fork)

* Renamed to debugbar (there are various names used in the previous iteration, including debug-toolbar and toolbar)
* Created Controller_Debugbar (similar to the official Kohana userguide) so it can be portable (doesn't show up in production anyway) and also added Route via init.php
* Moved Debugbar and FirePHP into cascading file structure (Kohana_Debugbar and Kohana_FirePHP)
* Moved css, js and img to subfolder "media/debugbar" and renamed to debugbar.css and debugbar.js
* Moved all inline output of CSS and JS to external files
* Moved all inline output to bottom of page after the &lt;/html> tag (seems to be ok)
* Trigger from after() method of base controller and not have to change index.php (or bootstrap.php) https://github.com/marcelorodrigo/developerbar
* Rename all HTML, JS and CSS elements to debugbar 

### Usage

* Checkout or download project and put into a folder called debugbar under modules
* Enable Module - see <http://kohanaframework.org/3.1/guide/kohana/modules#enabling-modules>

~~~
    Kohana::modules(array(
        ...
        'debugbar'    => MODPATH.'debugbar',   // debugbar
        ...
    ));
~~~

* Copy modules/debugbar/config/debugbar.php to application/config/debugbar.php and change 

~~~
    $config['auto_render'] = TRUE;  // change to TRUE
~~~



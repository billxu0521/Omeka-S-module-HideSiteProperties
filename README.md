
# Note! this moudle is developing

HideSiteProperties (module for Omeka S)
===================================
This module only affects the position of all meta data in the show page. 
The theme calls to explicitly print the specified value, and API access permissions and other areas where values may appear will continue to work as usual.
The displayed meta data fields can be defined through the settings of the site. Or use global control in the settings.
This module is based on [Hide Properties].


Installation
------------
Uncompress files in the module directory and rename module folder `HideSiteProperties`.
Then install it like any other Omeka module and follow the config instructions.

See general end user documentation for [Installing a module].

Usage
-----
After install module, `HideSiteProperties` will auto show in `item` page.`HideSiteProperties` is a read-tool that has two windows, the right side can be used to view the content, the left side can view the media(\*.png,\*.jpg or \*.pdf).The sort binding of the two windows is through the properties 'content' and the sort of 'media'.It is recommended that the paragraphs of the content of `item` are in the same order as the media of `item`.

Warning
-------

Use it at your own risk.

It’s always recommended to backup your files and your databases and to check
your archives regularly so you can roll back if needed.

- Option : GetSelection is not finished.

FutureWork
----------


Troubleshooting
---------------

See online issues on the [module issues] page on GitHub.


License
-------

This module is published under the GNU/GPL License.


Copyright
---------

* Copyright billxu, 2019-2020 (see [billxu] on GitHub)


[Omeka S]: https://omeka.org/s
[Installing a module]: http://dev.omeka.org/docs/s/user-manual/modules/#installing-modules
[GNU/GPL]: https://www.gnu.org/licenses/gpl-3.0.html
[FSF]: https://www.fsf.org
[OSI]: http://opensource.org
[billxu]: https://github.com/billxu0521 "Billxu"
[Vue]: https://vuejs.org/
[Hide Properties]: https://github.com/zerocrates/HideProperties
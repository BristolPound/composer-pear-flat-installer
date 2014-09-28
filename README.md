# A Multi-Framework [Composer](http://getcomposer.org) Library Installer


This is for PHP package authors to require in their `composer.json`. It will
install their package to the correct location based on the specified package
type.

The goal of `installers` is to be a simple package type to install path map.
Users can also customize the install path per package and package authors can
modify the package name upon installing.

`installers` isn't intended on replacing all custom installers. If your
package requires special installation handling then by all means, create a
custom installer to handle it.

### Purpose
This repository is intended only to give idea about installer plugins.
It is used in Composer related articles avaiable at Clearcode.cc blog.
For more installers please visit original source of Composer Installers plugin
at https://github.com/composer/installers
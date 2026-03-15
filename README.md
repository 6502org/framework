Framework
=========

This is the PHP framework used by the [6502.org](http://6502.org) website.  It is forked from 
[`maintainable/framework`](https://github.com/maintainable/framework), which is no longer developed.

The purpose of this fork is only to update the framework as needed by 6502.org.  This fork is not a continuation of general development of the framework.  Issue reports and pull requests against this repository generally won't be reviewed, expect when related to 6502.org.

The framework based around the Model-View-Controller pattern
and modeled after Ruby on Rails 1.2.  It is compatible with PHP
version 5.1.4 and later.

Repository Layout
-----------------

This repository shares the same layout as an application built with the
framework.  This is mostly because of historical reasons.


Starting a New Application
--------------------------

You can use `./script/createapp appname` to generate an application
directory in the current directory. Adjust the path to the createapp
script to create the application in a different place.

If you are familiar with Rails, using the framework should be rather
straightfoward at this point.

The framework expects its `index.php` file is located in the `DocumentRoot` of
an Apache `VirtualHost` and that `mod_rewrite` is enabled.  If you aren't familiar
with how to set this up, the framework is probably not for you.  We haven't
made any attempt to support running it out of a subdirectory or on shared hosting.

One noticeable difference from Rails is that files are named with uppercase
as in `PostsController.php` rather than `posts_controller.php`.  This is
largely because our libraries use PEAR-like conventions, where the uppercase
naming style is used.  Browse the repository directories under `app/` to see
the naming of controllers, models, and views.  Similarly, we use camelCase
names for methods (e.g. `respondTo` rather than `respond_to`).

You can generate stubs with `./script/generate`.  You'll probably want to
start with `./script/generate model post`, which will create a `Post` model
file with associated migration and test files.  Use `./script/generate`
with no arguments for help.

Tasks such as `db:migrate` are run as `./script/task db:migrate`.  For a
list of tasks, use `./script/task -T`.  If you copied the `Rakefile` and have
Rake installed, you can call the tasks using the `rake` command.


Packages and Dependencies
-------------------------

Applications built with the framework use class naming conventions similar to
Rails, e.g. classes named `Post` and `PostsController`.  However, the
framework itself is built entirely with PEAR-style naming conventions and
does not pollute the global space.

Classes required by the framework are placed in the `vendor/` directory,
which is a simple PEAR-style directory.  Any framework classes are
prefixed with `Mad_` (Mike and Derek) and placed under `vendor/Mad/`.

There are some libraries from other projects included in the `vendor/`
directory as well that are dependencies.  We have no interest in building
a monolith and try to share our code with other projects as we can.  Most
of the dependencies in `vendor/` started out as `Mad_` classes that found
homes elsewhere.  We hope in the future that the framework will continue
to shrink in this way.


Running Tests
-------------

The repository has the same layout as an application.  The framework tests
are run the same way as an application's tests would be.  Change to the
`test/` directory and run `phpunit AllTests`.

Before you can run the tests, you need to create a database and configure
the connection in `database.yml`.  You also need to build the tests database
using the file `db/tests/madmodel_test.sql`.

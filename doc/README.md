# Framework Documentation

- [Models](models.md)
- [Controllers](controllers.md)
- [Views](views.md)
- [Support](support.md)
- [Unit Testing](unit_testing.md)
- [Model Testing](model_testing.md)
- [Functional Testing](functional_testing.md)

## Directory Structure

All applications built using the framework share the exact same directory
structure.  This keeps projects consistent, allows team members to easily
transition between projects, and allows for tooling that runs under the
assumption of this structure.

![](images/main_dir_structure.gif)

Most of the server-side application development will take place in
the `app/` and `test/` directories. Client-side CSS,
images, and JavaScript will be in the `public/` directory.
Vendor libraries such as the framework itself and its
dependencies reside in `vendor/` to permit easy upgrading.

### Application Code

The application code resides under `app/`:

![](images/app_dir_structure.gif)

It is split into three different layers.  Each layer has a directory
under `/app`: `models/`, `views/`, and `controllers`.
We also have an additional `helpers/` directory under here
for view helper methods.

- Models: `/app/models/Users.php`
- Controllers: `/app/controllers/UsersController.php`
- Views: `/app/views/users/show.html`
- Helpers: `/app/helpers/UsersHelper.php`

### Web-accessible

Images, CSS, and JavaScript are all stored in `public/`:

![](images/web_dir_structure.gif)

### Configuration

#### Environments

The three different runtime environments are:

- `development`
- `production`
- `test`

Every request will include the common `config/environment.php` file and then
its respective `/config/environments/{environment}.php` file. These files
include constants and configuration used throughout the application.

Normal MVC code that goes in `app/` will never need
`require()` statements as this is done automatically.
Vendor (library) code in the `vendor/` directory also does
not need to be explicitly required because it will be autoloaded
by the PEAR convention (which all vendor files must abide).

#### URL Routes

Request Routing is configured in `/config/routes.php`. This file defines what
code gets run when a particular URL is requested. This is explained in more detail
under routing.

### Vendor Libraries

All vendor libraries, including the framework itself, are
located under `vendor/`.  The framework does not invent its own
plugin system or other exotic loading techniques.  Libraries must simply
reside in this directory and abide by the PEAR naming conventions.  The
framework libraries are all under `vendor/Mad/` and hence the
classes are prefixed `Mad_` by this convention.

### Naming Conventions

The framework has important naming conventions that are crucial to
how the code integrates together. By sticking to these conventions,
it makes code more consistent and less work gluing the pieces together.

#### Models

| Table | Class | File            |
|-------|-------|-----------------|
| users | User  | models/User.php |

#### Controllers

| URL         | Class           | File                            | Method |
|-------------|-----------------|---------------------------------|--------|
| /users/show | UsersController | controllers/UsersController.php | show() |

#### Views

| URL         | HTML File             | Ajax Response       |
|-------------|-----------------------|---------------------|
| /users/show | views/users/show.html | views/users/show.js |

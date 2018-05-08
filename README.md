Pre-launch Landing Page
=======================

Simple pre-launch landing page application using the [Nette](https://nette.org) and sample [Bootstrap 4](http://getbootstrap.com) template. 

Features
--------

- [x] Sign up with email
- [x] Referrals
- [x] Share to social channels

Preview ([LIVE](https://www.evasioapp.com))
-------

![Preview build with sample Bootstrap 4 template](https://i.imgur.com/gQk5TAB.png)

Requirements
------------

* PHP 5.6 or higher
* MySQL/MariaDB
* [Composer](https://getcomposer.org/)


Installation
------------

The best way to install this application is using Composer:

```bash
composer create-project evasio/prelaunch-landing-page path/to/install
cd path/to/install
```

Manual installation:

```bash
git clone git@github.com:evasio/prelaunch-landing-page.git
cd prelaunch-landing-page
composer install
```

Execute SQL query from `database/schema.sql` to create `signup` table.

Replace `<DATABASE>`, `<USER>` and `<PASSWORD>` with correct values in `app/config/config.local.neon`.

Make directories `temp/` and `log/` writable.

Customize text in `app/presenters/HomepagePresenter.php` and templates in `app/presenters/templates/Homepage/`.


Web Server Setup
----------------

The simplest way to get started is to start the built-in PHP server in the root directory of your project:

```bash
php -S localhost:8000 -t www
```

Then visit `http://localhost:8000` in your browser to see the welcome page.

For Apache or Nginx, setup a virtual host to point to the `www/` directory of the project and you
should be ready to go.

**It is CRITICAL that whole `app/`, `log/` and `temp/` directories are not accessible directly
via a web browser. See [security warning](https://nette.org/security-warning).**

Notice: Composer PHP version
----------------------------

This project forces `PHP 5.6` as your PHP version for Composer packages. If you have newer version on production you should change it in `composer.json`.

```json
"config": {
	"platform": {
		"php": "5.6"
	}
}
```

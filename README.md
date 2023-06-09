# Frame Valet WordPress Driver #
A custom upgraded Valet driver that handles the unique structure of Frame's projects - supports sites with the web root in `site` and `dist`.

- Handles multisite, including the subfolder structure
- Corrects paths for WP includes and built-in resources

This driver allows you to run `valet link` in the project root folder, rather than having to link from the `site` or `dist` folder - this makes valet work better overall, and commands such as `valet isolate`, `valet php` and `valet composer` will work with less complications whe the Valet site root is set as the root folder of the project.

## Installation ##

Clone this repo to your local machine - this will allow for easy updates to be published in the future;

```shell
git clone https://github.com/framecreative/frame-valet-drivers.git
```

Run the installation script on the cli, this will remove any old version of this specific driver, and create a symlink from valet's config to the driver in this repo.

```shell
php install.php
```

## Updates ##

By utilising the symlink method above, updating is as simple as pulling changes from github.

```shell
git pull origin master
```

## Changelog ##

### Version 1.0.0 ###
- Initial release
- Move to github vs sharing the file in team slack
- Compatible with Valet 2.x, works with Valet 3.x with minor issues

### Version 2.0.0 ###
- Updates to support valet 4.x releases
- Added installation script for easier setup
- Requires PHP 7+ (standard)
- Supports 3.x and 4.x versions of Valet - Valet 1.x and 2.x support is deprecated


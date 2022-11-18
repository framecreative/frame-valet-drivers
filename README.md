# Frame Valet WordPress Driver #
A custom upgraded Valet driver that handles the unique structure of Frame's projects - supports sites with the web root in `site` and `dist`.

- Handles multisite, including the subfolder structure
- Corrects paths for WP includes and built-in resources

This driver allows you to run `valet link` in the project root folder, rather than having to link from the `site` or `dist` folder - this makes valet work better overall, and commands such as `valet isolate`, `valet php` and `valet composer` will work with less complications whe the Valet site root is set as the root folder of the project.

To enable, copy or symlink into the `~/.config/valet/Drivers/` folder.
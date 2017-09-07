# Changelog

## 1.0.6 - 2017-09-07

- Updated generated .travis.yml configuration with fixes for setting up EPV testing.

## 1.0.5 - 2017-07-28

- Updated generated .travis.yml configuration to comply with recent changes to Travis and EPV. The Travis configuration file will also be generated appropriately for 3.1.x or 3.2.x environments based on the extension's minimum phpBB version.

## 1.0.4 - 2017-05-04

- Updated generated composer.json files to include the composer/installers requirement.
- Updated generated acp_demo_body.html template to follow a more correct ACP layout configuration.
- Updated generated acp main_module.php to generate use the correct error flags in trigger_errors.
- Switched skeleton's nav-bar icon to an SVG image for better style & retina display compatibility.

## 1.0.3 - 2017-01-12

- Add support for UTF-8 characters in fields (extension full name, description, author info, etc).
- Only add information about unit testing to the README when testing components are selected.
- Fix the extension name used in the generated build script component.
- Fix undeclared class properties generated in the ACP module component.
- Fix missing empty new line at the end of the generated composer.json file.
- Increased minimum PHP requirement to PHP 5.4 to support proper JSON file generation (such as UTF-8 support).

## 1.0.2 - 2016-11-19

- Re-organized some of the "Extension packaging" fields with clearer names and ordering.
- Shortened the name of the nav-bar link in the web interface to "Skeleton Extension".
- Helpful tooltips have been added to each of the checkboxes in the "Components" section of the web interface.
- Added a link to Skeleton Extension's Documentation in the web interface.
- Added warnings when trying to use 'phpbb' or 'core' as vendor and extension names.
- Added view online page sample code to the event listener for the controller component.
- Added a missing migration file to generated UCP components.
- Fixed an issue where the controller component did not generate a correct event listener.
- Fixed an issue where the controller component did not generate a template event listener for the controller's nav-bar link.
- Fixed an issue where the notifications component did not generate a valid ext.php (references to the notification services will now have the correct vendor and ext names instead of "dev.dev").
- Use the correct Symfony syntax for non-shared services: `scope: prototype` in phpBB 3.1.x and `shared: false` in phpBB 3.2.

## 1.0.1 - 2016-10-13

- Fixed an issue where in phpBB 3.1 boards, the Command Line Interface would not work.
- Skeleton files now have file docblocks uniquely named after the extension (was previously based on phpBB's own docblock). The current date and primary author name are also automatically added to the docblock's @copyright.
- Skeleton ACP PHP files have correct visibility declarations for methods and properties now.
- Skeleton config service files correctly encapsulate all strings in quotes that begin with @ and % per Symfony specifications.
- Skeleton PHP files have all their docblocks updated to be consistent with PSR-5 recommendations.
- Skeleton event class methods that do not use the $event variable do not have them defined as an argument (this is done to demonstrate that the $event argument is only needed if actually used in an event listener method).
- Internal docblocks have been updated.

## 1.0.0 - 2016-08-04

- First release

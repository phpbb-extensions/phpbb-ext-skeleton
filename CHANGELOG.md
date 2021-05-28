# Changelog

## 1.1.7 - 2021-05-28

- Added validation on user input for homepage URLs, author email addresses, and version requirement patterns.
- Fixed validation of extension display name when using the CLI.
- Internally moved the creation of extension composer.json from PHP logic to a TWIG template.
- Updated default phpBB version max requirement to <4.0.0@dev.

## 1.1.6 - 2020-12-28

- Added option to generate Github Actions workflow for Continuous Integration testing.
- Fixed an issue that prevented dot-files and dot-directories from being generated.
- Updated some syntax in the sample PHPUnit test files.

## 1.1.5 - 2020-09-11

- Make sure config created for cron by migration is dynamic.
- Updated all PHP arrays to use modern shortened syntax.
- Updated Travis config files.
- Updated Read Me files.

## 1.1.4 - 2020-02-13

- Generated permissions now include a Moderator Role.
- Updated the GPL-2.0 license text file to the most current version.
- Updated documentation links to correctly point to the 3.3.x branch. 
- Updated to a fancy tooltip when hovering the mouse over the "Components" options in the web-based interface.

## 1.1.3 - 2019-11-22

- Updated to support phpBB 3.3.x.
- Will now enforce lowercase-only vendor and extension names.
- Will now use Twig template namespaces for rendering template files in generated controller files.
- Will now generate Travis-CI tests to run against phpBB 3.3.x (unless you specify an earlier version of phpBB as a maximum version constraint).
- Will attempt to do a better job of creating the correct notification files, based on your phpBB version constraints.
- Removed 3rd party Symfony components previously required in phpBB 3.1 installs, resulting in much faster performance.
- Loads of additional little code inspection fixes.

## 1.1.2 - 2019-07-13

- Updated generated .travis.yml configuration to use the trusty build.

## 1.1.1 - 2019-02-27

- Added a new Permissions component to generate skeleton files that add and use permissions in an extension.
- Improved the handling of special characters in the Display Name. Now &, < and > are displayed correctly, while double-quotes are strictly invalid.
- Improved the generated sample migration file with a lot more documented examples of configs, config_text, permissions and custom functions.
- Improved the depends_on() values in generated migration files.
- Improved the naming of generated control panel language variables.
- Improved the generation of skeleton files so that only files for the selected components will be built.

## 1.1.0 - 2019-01-14

- Interface updates:
	- The Skeleton Extension can no longer be installed on phpBB 3.1 boards. The minimum requirement is now phpBB 3.2.0 or newer. (Note that you can still create skeletons that will support and run on phpBB 3.1 boards).
	- The components section of the web UI has been organized into categories to improve the user experience when choosing what the Skeleton will create.
	- The web UI user documentation link has been enhanced with a graphic icon to improve its visibility.
- Skeleton updates:
	- Skeleton files have dropped the use of "Acme" and "Demo" in favor of your own vendor and extension names. 
	- Skeleton template files now use TWIG syntax instead of old phpBB 3.0 style template syntax.
	- Improved distinctions between skeletons designed to be compatible with phpBB >=3.1 vs >=3.2. For example, skeletons built to support 3.1 and above will still use the User object for language functions, while skeletons built for 3.2 and above will use the newer Language object for language functions.
	- All skeleton files have been overhauled with improved Docblocks and sample code that exemplifies phpBB's coding guidelines and best practices.
	- ACP, UCP and MCP modules have been updated to utilise controller classes.

## 1.0.7 - 2018-03-14

- The generated composer.json will now have the standard "GPL-2.0-only" license identifier.
- The generated .travis.yml has a more up-to-date test configuration for phpBB 3.2.x environment.

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

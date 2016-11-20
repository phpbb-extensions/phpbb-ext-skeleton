# Changelog

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

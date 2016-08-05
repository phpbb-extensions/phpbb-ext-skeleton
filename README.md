# phpBB Skeleton Extension 

phpBB's Skeleton Extension is a tool for extension authors to help speed up and simplify the task of starting a new extension project. It generates sample starter files and directories in a skeleton package that you can use to begin building your extension.

## Requirements

- phpBB version 3.1.4 or later (also compatible with phpBB 3.2.0-b3 or newer).
- PHP version 5.3.3 or later.
- PHP module `ZipArchive` enabled.

## Installation

- Ensure your phpBB and PHP installations meet the Skeleton Extension requirements.
- [Download the latest release](https://www.phpbb.com/customise/db/official_tool/ext_skeleton/).
- Unzip and install to your phpBB `ext/` folder
- Go to "ACP" > "Customise" > "Extensions" and enable the "phpBB Skeleton Extension" extension.

## Contributing

To install the source code from this repository, clone the contents of this repository to `phpBB/ext/phpbb/skeleton`, then run Composer from the extension's directory:

	$ php composer.phar install

## Create an Extension Skeleton

### Web-based user interface

In order to create an extension via the web UI just open your local development board and visit the "Create skeleton extension" link in the forum's navigation bar:

    https://localhost/phpBB/app.php/skeleton

A packaged ZIP file is then offered as a download. Additionally it can be found in
`store/tmp-ext/`.

### Command line interface

In order to create an extension via the CLI, you need to open the console of your server.
Then run the following command in your phpBB root (next to config.php):

    $ ./bin/phpbbcli.php extension:create

A packaged ZIP file can be found in `store/tmp-ext/`.

## License

[GPLv2](license.txt)

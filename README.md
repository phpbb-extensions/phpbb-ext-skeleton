# phpBB Skeleton Extension 

phpBB's Skeleton Extension is a tool for extension authors to help speed up and simplify the task of starting a new extension project. It generates sample starter files and directories in a skeleton package that you can use to begin building your extension.

## Installation

- [Download the latest release](https://github.com/phpbb-extensions/phpbb-ext-skeleton/releases).
- Unzip and install to your phpBB `ext/` folder
- Go to "ACP" > "Customise" > "Extensions" and enable the "phpBB Skeleton Extension" extension.

## Contributing

To install the source code from this repository, clone the contents of this repository to `phpBB/ext/phpbb/skeleton`, then run Composer from the extension's directory:

	$ php composer.phar install

## Create an Extension Skeleton

### Web-based user interface

In order to create an extension via the web UI just open your board an visit the
"Create skeleton extension" link in the forum's navigation bar:

    https://localhost/phpBB/app.php/skeleton

A packaged ZIP file is then offered as a download. Additionally it can be found in
`store/tmp-ext/`.

### Command line interface

In order to create an extension via the CLI, you need to open the console of your server.
Then run the following command in your phpBB root (next to config.php):

    $ ./bin/phpbbcli.php extension:create

Afterwards copy your extension from `store/tmp-ext/` into the `ext/` folder

## License

[GPLv2](license.txt)

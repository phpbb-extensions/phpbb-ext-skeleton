# phpBB Skeleton Extension

## Installation

Copy the extension to phpBB/ext/phpbb/skeleton

Go to "ACP" > "Customise" > "Extensions" and enable the "phpBB Skeleton Extension" extension.

## Creating an extension

In order to create an extension, you need to open the console of your server.
Then run the following command in your phpBB root (next to config.php):

    ./bin/phpbbcli.php skeleton:create

Afterwards copy your extension from `store/tmp-ext/` into the `ext/` folder

## License

[GPLv2](license.txt)

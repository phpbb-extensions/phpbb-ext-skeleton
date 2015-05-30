# phpBB Skeleton Extension

## Installation

Copy the extension to `phpBB/ext/phpbb/skeleton`

Go to "ACP" > "Customise" > "Extensions" and enable the "phpBB Skeleton Extension" extension.

## Creating an extension via web UI

In order to create an extension via the web UI just open your board an visit the
"Create skeleton extension" link at the top left:

    https://localhost/phpBB/app.php/skeleton

The `.zip` is then offered as a download. Additionally it can be found at
`store/tmp-ext/`.

## Creating an extension via console

In order to create an extension, you need to open the console of your server.
Then run the following command in your phpBB root (next to config.php):

    ./bin/phpbbcli.php extension:create

Afterwards copy your extension from `store/tmp-ext/` into the `ext/` folder

## License

[GPLv2](license.txt)

services:
<!-- IF COMPONENT.controller -->
    {EXTENSION.vendor_name}.{EXTENSION.extension_name}.controller:
        class: {EXTENSION.vendor_name}\{EXTENSION.extension_name}\controller\main
        arguments:
            - @config
            - @controller.helper
            - @template
            - @user
<!-- ENDIF -->
<!-- IF COMPONENT.phplistener -->

    {EXTENSION.vendor_name}.{EXTENSION.extension_name}.listener:
        class: {EXTENSION.vendor_name}\{EXTENSION.extension_name}\event\main_listener
        arguments:
            - @controller.helper
            - @template
        tags:
            - { name: event.listener }
<!-- ENDIF -->

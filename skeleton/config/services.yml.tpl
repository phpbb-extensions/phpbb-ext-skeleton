<!-- IF COMPONENT.service -->
imports:
    - { resource: parameters.yml }
<!-- ENDIF -->

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
<!-- IF COMPONENT.service -->
    {EXTENSION.vendor_name}.{EXTENSION.extension_name}.service:
        class: {EXTENSION.vendor_name}\{EXTENSION.extension_name}\service
        arguments:
            - @user
            - %{EXTENSION.vendor_name}.{EXTENSION.extension_name}.tables.demo_table%

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

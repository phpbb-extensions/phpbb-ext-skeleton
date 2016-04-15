{% if COMPONENT.service %}
imports:
    - { resource: parameters.yml }
{% endif %}

services:
{% if COMPONENT.controller %}
    {{ EXTENSION.vendor_name }}.{{ EXTENSION.extension_name }}.controller:
        class: {{ EXTENSION.vendor_name }}\{{ EXTENSION.extension_name }}\controller\main
        arguments:
            - '@config'
            - '@controller.helper'
            - '@template'
            - '@user'

{% endif %}
{% if COMPONENT.service %}
    {{ EXTENSION.vendor_name }}.{{ EXTENSION.extension_name }}.service:
        class: {{ EXTENSION.vendor_name }}\{{ EXTENSION.extension_name }}\service
        arguments:
            - '@user'
            - '%{{ EXTENSION.vendor_name }}.{{ EXTENSION.extension_name }}.tables.demo_table%'

{% endif %}
{% if COMPONENT.phplistener %}
    {{ EXTENSION.vendor_name }}.{{ EXTENSION.extension_name }}.listener:
        class: {{ EXTENSION.vendor_name }}\{{ EXTENSION.extension_name }}\event\main_listener
        arguments:
            - '@controller.helper'
            - '@template'
        tags:
            - { name: event.listener }

{% endif %}
{% if COMPONENT.console %}
    {{ EXTENSION.vendor_name }}.{{ EXTENSION.extension_name }}.command.demo:
        class: {{ EXTENSION.vendor_name }}\{{ EXTENSION.extension_name }}\console\command\demo
        arguments:
            - '@user'
        tags:
            - { name: console.command }

{% endif %}
{% if COMPONENT.cron %}
    {{ EXTENSION.vendor_name }}.{{ EXTENSION.extension_name }}.cron.task.demo:
        class: {{ EXTENSION.vendor_name }}\{{ EXTENSION.extension_name }}\cron\task\demo
        arguments:
            - '@config'
        calls:
            - [set_name, [cron.task.demo]]
        tags:
            - { name: cron.task }

{% endif %}

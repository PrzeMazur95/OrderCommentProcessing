Requirements :

- Magento 2.4.7
- PHP 7.4
- RabbitMQ setup up and configured as a message broker


1. Put module files in app/code/YellowCard/OrderCommentProcessing
2. Enable module: 
    - bin/magento module:enable YellowCard_OrderCommentProcessing, 
    - or add to app/etc/config.php into modules list - 'YellowCard_OrderCommentProcessing' => 1,
3. Module is enabled by default in admin panel. If we do not want to add comments to order, we can simply disable it in admin panel.</br>
   You have to update comment, that you would like to add to an order, in settings on <b>Default scope</b>:
    1. Stores -> Configuration -> Sales -> Sales -> Order Comments Settings
    2. Set the comment in the field "Default Order Comment"
    3. Save configuration. For now this comment is set on Default scope, so will be added to all orders on every website/store/store view.
4. Register our new consumer that is responsible for handling comment adding in app/etc/env.php, or for testing purposes run it manually in the command line:
    1. env.php :
        - in cron_consumers_runner node -> consumers -> add 'yellowcardOrderComment.consumer.one' which is the name of our consumer
        - check if cron is running, and configured properly
        - more info how to set it up : https://experienceleague.adobe.com/en/docs/commerce-operations/configuration-guide/message-queues/manage-message-queues#behavior-by-default
    2. manually in command line:
        - php bin/magento queue:consumers:start yellowcardOrderComment.consumer.one
5. Run bin/magento setup:upgrade
6. Palace an order, comment should be added to comments history. Customer will not be notified about added comment.

![Settings.png](docs/Settings.png)
![Comment.png](docs/Comment.png)




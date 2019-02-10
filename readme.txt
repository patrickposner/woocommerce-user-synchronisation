=== WooCommerce User Synchronisation ===
Contributors: patrickposner
Tags: woocommerce, synchronsise users, user management, multiple installations
Requires at least: 4.6
Tested up to: 5.0
Requires PHP: 7
Stable tag: 1.0

== Description ==

= Features =
Transfer your customers from one site to another.
If the salts are matching you can also transfer the passwords and the customer can login on the other store without any
needs to resetting the password.

= Settings =

You can manage all settings under WooCommerce->Settings->WooCommerce User Synchronisation.

Install the plugin on both stores, and decide which is the sender and which is the receiver.

== Sender ==
Enter the URL of the store you would like to send the users and configure the number of batches per run.
The transfer type should be "Sender".

== Receiver ==
Enter the number of batches per run to import the users and set the transfer type to "Receiver".
You don't need to provide the URL to the other store.

After everything is setup, you can click the button "Send user transfer" from the senders store settings.
This process may take a while. If it's done there will be a message displayed on the settings page.

Please don't close the settings page until the process is done and the message was displayed.

= Salts =
You can generate your salts here: https://api.wordpress.org/secret-key/1.1/salt/
If you want to share the passwords, the salts should be the same in both sites.

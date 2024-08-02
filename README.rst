TYPO3 CMS Extension "t3monitoring"
==================================

.. image:: https://travis-ci.org/georgringer/t3monitoring.svg?branch=master
    :target: https://travis-ci.org/georgringer/t3monitoring

This extensions provides the possibility to monitor all of your TYPO3 installations and shows you

- used TYPO3 version and if it is up to date
- available TYPO3 extensions and if those are installed, insecure or if there are bugfix, minor or major updates
- additional information like PHP & Mysql versions.

**Requirements**

- At least TYPO3 CMS 11 LTS (monitoring works also for 6.2 installations)
- The host must have access to every client to be able to fetch the data

**Pricing**

This extension is completely free to use! However maintaining an extension takes an enormous amount of time.
Therefore I am using a concept which is based on your trust!
If you use this extension to monitor your clients, please consider to pay (once):

- € 150 for less than 20 clients
- € 350 for less than 100 clients
- € 500 for more than 100 clients

Please contact me via email (mail@ringer.it) or slack for questions and to receive an invoice!

Screenshots
^^^^^^^^^^^

**Overview**

.. figure:: Documentation/images/t3monitoring_index.png
		:alt: Overview including most important information

**Search result**

.. figure:: Documentation/images/t3monitoring-search.png
		:alt: Search result

**Single view of a client**

.. figure:: Documentation/images/t3monitoring-client.png
		:alt: Client

**List of all used extensions**

.. figure:: Documentation/images/t3monitoring-extensions.png
		:alt: Extensions

How to start
------------

Before you can actually monitor any installation, you need to install the extension *t3monitoring_client* on every installation (called "client").
This extension provides the data which will be fetched by the master installation. You can find this extension on github (https://github.com/georgringer/t3monitoring_client) or later in the TER.

.. important:: Please secure the installation as much as possible, as it contains data of all your clients. Restrict access by running it in your intranet only, or at least use a *Basic HTTP Authentication*.

Create the clients
""""""""""""""""""

Create a record "**Client**" on any sys folder and fill out at least the following required fields:

- Title
- Domain. Include ``http://`` or ``https://``.
- Secret: This is the same secret as defined in the configuration of *t3monitoring_client* in the client installation. Please don't reuse any secrets twice.
- (Optional) BasicAuth username and password: (if your client is secured via HTTP Basic Auth)
- (Optional) Host Header: (if you want to monitor a client which can't be resolved via public DNS services) (
- (Optional) Ignore Certificate Errors: Ignores certificate errors (mostly necessary if you use the previous field "Host Header" in conjunction with Let's Encrypt)
- (Optional) Force IP Resolve: IPv4 or IPv6

Create an optional record "**SLA**" to group your clients. Examples could be:

- VIP: Do all updates ASAP
- First ask: Before doing any updates, ask client for proper time schedule

Import the data
"""""""""""""""

To be able to deliver proper results, this extensions requires information about all core versions and all extensions.
This information is provided by get.typo3.org.

To import the data, use the command line: ::

	vendor/bin/typo3 monitoring:importAll


You can add this call also as task in the scheduler extension.

Especially the import of extensions can take a while, therefore you can use different calls for all required imports:

- ``vendor/bin/typo3 monitoring:importCore`` to fetch latest core versions
- ``vendor/bin/typo3 monitoring:importExtensions`` to fetch the extensions
- ``vendor/bin/typo3 monitoring:importClients`` to fetch the client data

Notifications
-------------

t3monitoring contains various notifications.

Failed to fetch client info
"""""""""""""""""""""""""""

This notification sends an email, if a clients information (provided by the extension "t3monitoring_client") cannot be accessed/fetched.
You may configure the email recipient in the "Extension Configuration" of the t3monitoring extension using the setting "records.emailForFailedClient".

The notification is send by the Symfony Console Command **"monitoring:importClients"** and consists of a single email which contains a list of all failing clients.
The frequency of the notification email depends on your scheduled task for importing the clients.

The "Extension Configuration" also contains a option "records.emailAllowedAmountOfFailures".
This value provides the ability to only send the notification email, if a client fails multiple times in a row.

Client report
"""""""""""""

Another possible notification is the "Client report". It is represented by the Symfony Console Command **"reporting:client"**.
It generates an email for each client and uses the clients email property ("email") as recipient address.
If there is no email set, its not possible to send a notification.

If t3monitoring identifies security problems, an outdated core or additional error messages the notification is sent.
If the client does not break with these regulations, no email is sent.

The frequency is again defined by the according scheduled task.

Admin report
""""""""""""

Last but not least, the "Admin report" (Symfony Console Command: **reporting:admin**) generates a single email with all problematic clients and sends it.
The recipients email address needs to be configured as argument of the Symfony Console Command (respective the scheduled task).

The frequency of the sent notification is also defined by the occurrence of the scheduled task.

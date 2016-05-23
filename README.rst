TYPO3 CMS Extension "t3monitoring"
==================================
.. image:: https://travis-ci.org/georgringer/t3monitoring.svg?branch=master
    :target: https://travis-ci.org/georgringer/t3monitoring

This extensions provides the possibility to monitor all of your TYPO3 installations and shows you

- used TYPO3 version and if it is up to date
- available TYPO3 extensions and if those are installed, insecure or if there are bugfix, minor or major updates
- additional information like PHP & Mysql versions.

**Requirements**

- TYPO3 CMS 7 LTS (monitoring works also for 6.2 installations)
- The host must have access to every client to be able to fetch the data

Important: This extension is still beta and things might change!

**Pricing**
This extension is completly free to use! However maintaining an extension takes an enormous amount of time. Therefore I am using a concept which is based on your trust!
If you use this extension to monitor your clients, please consider to pay (once) per supervised client:

- less than 20 clients: €150
- less than 100 clients: €350
- more than 100 clients: €500

Please contact me via email (mail@ringer.it) or slack for questions and to receive an invoice!

Screenshots
^^^^^^^^^^^

**Overview**

.. figure:: Resources/Public/Screenshots/t3monitoring_index.png
		:alt: Overview including most important information

**Search result**

.. figure:: Resources/Public/Screenshots/t3monitoring-search.png
		:alt: Search result

**Single view of a client**

.. figure:: Resources/Public/Screenshots/t3monitoring-client.png
		:alt: Client

**List of all used extensions**

.. figure:: Resources/Public/Screenshots/t3monitoring-extensions.png
		:alt: Extensions

How to start
------------
Before you can actually monitor any installation, you need to install the extension *t3monitoring_client* on every installation (called "client").
This extension provides the day which will be fetched by the master installation. You can find this extension on github (https://github.com/georgringer/t3monitoring_client) or later in the TER.

.. important:: Please secure the installation as much as possible, as it contains data of all your clients. Restrict access by running it in your intranet only, or at least use a *Basic HTTP Authentication*.

Create the clients
""""""""""""""""""
Create a record "**Client**" on any sys folder and fill out at least the following required fields:

- Title
- Domain. Include ``http://`` or ``https://``.
- Secret: This is the same secret as defined in the configuration of *t3monitoring_client* in the client installation. Please don't reuse any secrets twice.

Create an optional record "**SLA**" to group your clients. Examples could be:

- VIP: Do all updates ASAP
- First ask: Before doing any updates, ask client for proper time schedule

Import the data
"""""""""""""""
To be able to deliver proper results, this extensions requires information about all core versions and all extensions. This information is provided by typo3.org.

To import the data, use the command line: ::

	./typo3/cli_dispatch.phpsh extbase monitoring:importAll


You can add this call also as task in the scheduler extension.

Especially the import of extensions can take a while, therefore you can use different calls for all required imports:

- ``./typo3/cli_dispatch.phpsh extbase monitoring:importCore`` to fetch latest core versions
- ``./typo3/cli_dispatch.phpsh extbase monitoring:importExtensions`` to fetch the extensions
- ``./typo3/cli_dispatch.phpsh extbase monitoring:importClients`` to fetch the client data



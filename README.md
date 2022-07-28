# README

# Contents

- Introduction
- Prerequisites
- Rebranding
- Installing the payment module
- License

# Introduction

This CartThrob module provides an easy method to integrate with the payment gateway.
 - The httpdocs directory contains the files that need to be uploaded to the root of your Expression Engine installation directory
 - Supports CartThrob versions: **3.0+**
 - Supports Expression Engine versions: **3.0+**

# Prerequisites

- The module requires the following prerequisites to be met in order to function correctly:
    - The 'bcmath' php extension module: https://www.php.net/manual/en/book.bc.php

> Please note that we can only offer support for the module itself. While every effort has been made to ensure the payment module is complete and bug free, we cannot guarantee normal functionality if unsupported changes are made.

# Rebranding

To rebrand this module, complete the following steps:

1. In file `httpdocs/system/user/addons/cartthrob/third_party/payment_gateways/Cartthrob_payment_network.php` change the following:
	- Line 20: `* @author Payment Network <https://www.example.com>` to your website URL
	- Line 53: `'default' => 'https://commerce-api.handpoint.com/hosted/'` change this URL to your gateway URL we supply
2. In file `httpdocs/system/user/addons/cartthrob/third_party/language/english/cartthrob_payment_network_hosted_lang.php` change the following:
	- Line 3: `'payment_network_title' => 'Payment Network (Hosted)',` changing Payment Network to your brand name
	- Line 4: `'payment_network_description' => '<a href="https://www.example.com"><img src="system/user/addons/cartthrob/third_party/payment_gateways/cs-logo.png" border="0" alt="Payment Network" /></a>` changing the  website URL to your brand URL and the Payment Network alt text to your brand name
	- Line 7: `<p><a href="https://mms.example.com/">Login to the Payment Network merchant area</a></p>` changing the URL to that of your branded MMS
	- Line 8: `<p>Accept credit cards through the Payment Network payment gateway.</p>` changing Payment Network to your brand name
3. Replace the `cs-logo.png` file with your own in the following directories:
	- `httpdocs/system/user/addons/cartthrob/third_party/payment_gateways/`
	- `httpdocs/system/user/addons/cartthrob/third_party/language/english/`
4. When downloading as a zip file, you can right-click and rename to remove the `Unbranded` text from the filename.

# Installing and configuring the module

1. Copy the contents of the httpdocs folder to your root Expression Engine directory, clicking 'Yes' merge folders
2. Log in to the admin area of Expression Engine, then navigate to the developer tools (Wrench icon) in the top right
3. Then click 'Add-on Manager'
4. Locate your 'CartThrob Add-on' in the "Thrid Party Add-Ons" section and click 'manage'
5. Navigate to and click the 'PAYMENTS' tab
6. In the "Select a gateway to edit its settings" dropdown choose 'PaymentNetwork (Hosted)'
7. Configure the "PaymentNetwork (Hosted)" payment gateway settings. Once configured, scroll down and locate "Allow Gateway Selection in Checkout Form?". Find "PaymentNetwork (Hosted)" and check it, then click 'submit'. The module has now been added to the checkout page
8. By default we have set the module to the use the test details. To use your live details simply replace the test details in the text boxes with the ones supplied from your PaymentNetwork live letter

License
----
MIT

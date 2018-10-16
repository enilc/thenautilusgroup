#Spottr Details

## Details

The primary files for the Spottr system are index.php, spottr.js, and the contents of the /etc/ folder.

We've tried to outline to the best of our ability below and in the Capstone Report "Final Implementation" section.


## Usage

Spottr makes extensive use of free and open source software. Credit for which is included
here, and throughout Spottr's files, in a clear and forthright manner.

* Bootstrap HTML/CSS/Javascript library: 	https://getbootstrap.com/
* Bootstrap Template: 						https://startbootstrap.com/template-overviews/sb-admin-2/
* PHPMailer:								https://github.com/PHPMailer/PHPMailer
* Aws SDK for PHP: 							https://github.com/aws/aws-sdk-php

# File List

### Files created, modified and in whole or in part credited to the Nautilus group: 
* /images/*
	* *Note: Marker icon is creative commons image*
* /includes/*
	* *Note: This file is customized from the Boostrap template, see above.*
* /etc/db_connect.php
* /etc/db_interface.php
* /etc/db_mediator.php
* /etc/registerUser.php
* /etc/registerUserPresentationPartOne.php
	* *Note: This file is mostly comprised of code from the Bootstrap template used.*
* /etc/registerUserPresentationPartTwo.php
	* *Note: This file is mostly comprised of code fro the Bootstrap template used.*
* /etc/upload.php
* /etc/user_funct.php
* /tests/*
	* *Notes: These are database testing files used for backend TDD*
* /index.php
* /login.php
	* *Note: Customization from SB Admin 2 template is light*
* /logout.php
* /manifest.json
* /service-worker.js
* /spottr.js

## Amazon SDK for PHP on GitHub
### Credit: https://github.com/aws/aws-sdk-php 
	/etc/Aws/*
	/etc/GuzzleHttp/*
	/etc/JmesPath/*
	/etc/Psr/*
	/etc/aws-autoloader.php

## Files used for PHPMailer
### Credit: https://github.com/PHPMailer/PHPMailer
	/etc/src/*

## Elastic Beanstalk Configuration File
### Credit: https://serverfault.com/a/546784
	/.ebextensions/project.config

## Bootstrap SB Admin 2 Template
### Credit: https://startbootstrap.com/template-overviews/sb-admin-2/
	/dist/*
	/vendor/*
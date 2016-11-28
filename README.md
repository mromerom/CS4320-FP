# CS4320-FP
Final project repo for CMP_SC 4320.

### Server
The easiest way to deploy our software is through AWS.
From https://aws.amazon.com/ create an EC2 instance running Ubuntu 16.04

### Server configuration
Our project runs using MongoDB Enterprise 3.2.11, so follow [this guide](https://docs.mongodb.com/manual/tutorial/install-mongodb-enterprise-on-ubuntu/) to install it on your server.  
Then follow [this guide](http://lornajane.net/posts/2016/php-7-0-and-5-6-on-ubuntu) to install and switch php from version 7.0 to 5.6 because there isn’t a php mongodb driver for php7.0.  
Finally, run `sudo apt-get install php5.6-mongo` from the command line to install the driver

### The software
Clone the master branch of this repository.
Using Filezilla

# CS4320-FP
Final project repository for Mizzou CS 4320.  
Check out [our wiki](https://github.com/mromerom/CS4320-FP/wiki) for more information.

## Set up
### Server
The easiest way to deploy our software is through AWS.  
From https://aws.amazon.com/ create an EC2 instance running Ubuntu 16.04.  
Once you create your instance, the site instructions for connecting via command line.

### Server configuration
Our project runs using MongoDB Enterprise 3.2.11, so follow [this guide](https://docs.mongodb.com/manual/tutorial/install-mongodb-enterprise-on-ubuntu/) to install it on your server.  
Then follow [this guide](http://lornajane.net/posts/2016/php-7-0-and-5-6-on-ubuntu) to install and switch php from version 7.0 to 5.6 because there isn’t a php mongodb driver for php7.0.  
Finally, run `sudo apt-get install php5.6-mongo` from the command line to install the driver

### The software
Clone the master branch of this repository.  
Using FileZilla, connect to your instance. FileZilla download can be found [here](https://filezilla-project.org/).  

##### Steps to connect via FileZilla are:  
1. Go to File -> Site Manager... -> New Site  
2. Copy and paste your instance's url into the host field.  
3. Set Protocol to SFTP.  
4. Set Logon type to Keyfile.  
5. Set User to "ubuntu".  
6. Browse for the .pem keyfile that you downloaded when you created your instance.  
7. Hit connect.  

Remove the "index.html" file located in the `/var/www/html` directory on your instance.  
Move all the files from the `Website_Files` directory of the repository into the `/var/www/html` directory.  
Access your instance via a web browser and you should be good to go!

##### Note:  
To create an admin user for the site, you must edit the "createUser.php" file in `Website_Files`.  
1. On line 70, uncomment the switch option for an admin user.  
2. Using FileZilla, upload the edited "createUser.php" to the site (in `/var/www/html`).  
3. Access the createUser page of the site and create your admin user.  
4. Comment back out what you uncommented in step 1.  
5. Using FileZilla, upload "createUser.php" to the site.

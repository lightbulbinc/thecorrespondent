# Running commands
Is possible, run all commands on the `git bash`
# Clone the repo
1. Open the Git bash
2. Go to the folder where you want to put your cms
3. Run the following command:
```
git clone https://github.com/lightbulbinc/thecorrespondent.git
```
4. cd into the cloned dir

# Set up bower
Run the following command:
```
bower install
```
# Set up the node modules
Run the following command:
```
npm install
```
# Install the Database
1. Open a mysql app (either command line or GUI app such as HeidiSQL)
2. Create a Database
3. Run the following sql files:
```
docs/db.sql
docs/db-insert.sql
```
# Update Database meta information
1. Go the following file:
```
app/services/dataobjectserver/common/dbmetadata.json
```
2. Update the Database meta data as required
# Starting the local php server
1. Go to the php folder (c:/dr-nefario-setup/php)
2. Run the following command (point to the cms/app dir):
 ```
php -S localhost:9001 -t ../thecorrespondent/admin-ui-1/app

```
# Start the grunt
1. Go back to your cms folder
2. Run the following command:
 ```
grunt serve

```

# Kill a process by port number (Windows)
1. First run the following command to find out the processes running on this machine
```
netstat -a -o -n > p.txt
```
2. Open the output file (`p.txt`) and search for the required Port number. It should be in the local address (2nd) column
3. Find it's PID (most probably last column)
4. Use the following command to kill the process by PID
```
taskkill /F /PID <pid>
```

# Setup on godaddy (*mktayal*)

## Database

### Run db.sql
**Todo:** Create a standard `scott / tiger` or better still `admin / lightbulb` user as the default admin. Add the entry to the db.sql `appuser insert`

## Files

### Files to be updated

# glyphicons-halflings-regular.woff2  error
This appears to be a Chrome browser issue so we will not be handling. At least not for now.

# Creating a new template
***We should easily be able to create a script (python) where we only need to create the main template table and it should do everything else given below***
## Database
Add an entry in the `pagetemplate` table for the new template.

#### Example:
```
INSERT INTO `pagetemplate` (`id`, `pagetype`, `description`, `title`, `template`) VALUES
(4, 'piyoutubevideo', 'Use this template to create youtube video content', 'Youtube video', 'youtube');
```
Create a table with the same name and the `pagetype` field value in the `pagetemplate` table. This table holds the template data.

#### Example:
```
CREATE TABLE `piyoutubevideo` (
  `id` smallint UNSIGNED NOT NULL auto_increment,
  `url` varchar(256) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
```
*Note*: This is a child of the `pageitem` table. So, things like title, create date and stuff are already part of the `pageitem` data

Create a mapping table to the parent `pageitem` table

#### Example:
```
CREATE TABLE `pageitem_map_piyoutubevideo` (
  `id` smallint NOT NULL auto_increment,
  `pageitemid` smallint UNSIGNED NOT NULL,
  `piyoutubevideoid` smallint UNSIGNED NOT NULL,
  PRIMARY KEY (`id`),
  FOREIGN KEY (`pageitemid`) REFERENCES `pageitem` (`id`),
  FOREIGN KEY (`piyoutubevideoid`) REFERENCES `piyoutubevideo` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
```



## Data object server
Create the data object server files
`<pagetype>.php` and `<pagetype>collection.php`
## View
Create a view <pagetype>.html that is designed for user input for this template
## Code
As long as you don't need any special data to be saved, the existing save functionality will work just fine

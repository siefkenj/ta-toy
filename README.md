[![Build Status](https://travis-ci.org/siefkenj/ta-toy.png?branch=master)](https://travis-ci.org/siefkenj/ta-toy)

# ta-toy

Toy application to learn how to use Vue.js

## Task

Working together, you will create a one-page Vue.js app that acts like an oversimplified
TA feedback application. The app's default page wills show a list of classes. Upon clicking a class,
a list of TAs for that class will be displayed. Upon clicking a TA, a list of tutorial/lecture sections
assigned to that TA will be displayed. In all, there are four views:

1.  A parent view which provides consistent navigation for the child views.
2.  A view that allows the user to select a course.
3.  A view that allows the user to select a TA provided a course is selected.
4.  A view that displays all tutorial/lecture sections taught provided a course and TA are selected.

## What's Provided

An `index.html` which loads the needed javascript libraries. The container app is in `js/app.js` and
stubs for the three other components are in `js/component-...js`. As well, `get_info.php` is provided
which supplies dummy data for populating the app.

## Initial Set Up

```
npm install
```

## Building

```
npm start
```

```
php -S localhost:3000 -t dist/
```

## Getting Data

A dummy php script `get_info.php` is provided with some data. To get a list of courses

    get_info.php

To get a list of TAs

    get_info.php?course=THECOURSE

To get a list of sections

    get_info.php?course=THECOURSE&ta=THETA

Every query returns a JSON object with a `TYPE` attribute which is either `courses`, `tas`, `sections`, or `error`.

## What's Next

After you get an app working with the dummy data from `get_info.php`, you can tackle getting a mariadb instance
set up and having a php script gather information from a database.

## Database Setup

Login to database via Mariadb (I used MYSQL client via windows)

Once logged, do the following to create your local database and user to allow access via php:

### 1. Database creation

```
MariaDB[(none)]> CREATE DATABASE `mydb`;
```

### 2. User creation

```
MariaDB[(none)]> CREATE USER 'myuser' IDENTIFIED BY 'mypassword';
```

### 3. Grant permissions to access and use the MySQL server

Only allow access from localhost (this is the most secure and common configuration you will use for a web application):

```
MariaDB[(none)]> GRANT USAGE ON *.* TO 'myuser'@localhost IDENTIFIED BY 'mypassword';
```

To apply the changes of the new assigned permissions, you must finish with the following command:

```
MariaDB[(none)]> FLUSH PRIVILEGES;
```

### 5. Verify your new user has the right permissions

```
MariaDB[(none)]> SHOW GRANTS FOR 'myuser'@localhost;
+--------------------------------------------------------------+
| Grants for myuser@localhost                                  |
+--------------------------------------------------------------+
| GRANT USAGE ON *.* TO 'myuser'@'localhost'                   |
| GRANT ALL PRIVILEGES ON `mydb`.* TO 'myuser'@'localhost'     |
+--------------------------------------------------------------+
2 rows in set (0,00 sec)
```

If you made a mistake at some point you can undo all the steps above by executing the following commands, taking the precaution of replacing localhost with ‘%’ if you also changed it in the previous commands:

```
DROP USER myuser@localhost;
DROP DATABASE mydb;
```

#### Now that the Database is created, you could use the following command to see the list of databases that are available

```
MariaDB[(none)]> SHOW DATABASES;
```

Select the database that's being used

```
MariaDB[(none)]> USE database_name;
```

Once the database is selected, import the db/schema.sql file

```
MariaDB[database_name]> source "/absolute/path/to/.../db/schema.sql"
```

Check if table is created:

```
MariaDB[database_name]> SHOW TABLES;
```

Check if the newly created table is populated:

```
MariaDB[database_name]> SELECT * FROM table_name;
```

## Mocha testing

#### Install all the dependencies

```
$ npm install
```

#### Start the testing

```
$ npm test
```

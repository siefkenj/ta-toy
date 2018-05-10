# ta-toy
Toy application to learn how to use Vue.js

## Task

Working together, you will create a one-page Vue.js app that acts like an oversimplified
TA feedback application. The app's default page wills show a list of classes. Upon clicking a class,
a list of TAs for that class will be displayed. Upon clicking a TA, a list of tutorial/lecture sections
assigned to that TA will be displayed. In all, there are four views:

1. A parent view which provides consistent navigation for the child views.
2. A view that allows the user to select a course.
3. A view that allows the user to select a TA provided a course is selected.
4. A view that displays all tutorial/lecture sections taught provided a course and TA are selected.

## What's Provided

An `index.html` which loads the needed javascript libraries. The container app is in `js/app.js` and
stubs for the three other components are in `js/component-...js`. As well, `get_info.php` is provided
which supplies dummy data for populating the app.

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

Once logged in, follow the tutorial on the following website to setup database and assign user to it
http://www.daniloaz.com/en/how-to-create-a-user-in-mysql-mariadb-and-grant-permissions-on-a-specific-database/

You can use the following command to see the list of database that is available

```
MariaDB[(none)]> SHOW DATABASES;
```
After creating the database, select the database that's being used

```
MariaDB[(none)]> USE database_name;
```

Once the database is selected, import the .sql file with sample data from db/
```
MariaDB[database_name]> source "Absolute_Path_to_.sql_file"
```

Check table is created:
```
MariaDB[database_name]> SHOW TABLES;
```

Check table is populated:
```
MariaDB[database_name]> SELECT * FROM table_name;
```

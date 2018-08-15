# Ramverk1 project: Reddit copy

[![Build Status](https://travis-ci.org/emsa16/ramverk1-project.svg?branch=master)](https://travis-ci.org/emsa16/ramverk1-project)
[![Build Status](https://scrutinizer-ci.com/g/emsa16/ramverk1-project/badges/build.png?b=master)](https://scrutinizer-ci.com/g/emsa16/ramverk1-project/build-status/master)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/emsa16/ramverk1-project/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/emsa16/ramverk1-project/?branch=master)



Repo for course ramverk1 final project, BTH 2018


Installation
------------------

```
git clone https://github.com/emsa16/ramverk1-project.git
cd ramverk1-project
composer install
```

For the project to work the database also needs to be setup:

```
mv config/database_default.php config/database.php
```

Then change dsn, username and password within `database.php` to match your environment.

Database tables for users, posts and comments need to be added manually. Use the following DDL files to do that (NOTE: the tables need to be entered in this order in order to avoid problems with referenced tables):
-  `sql/ddl/user_mysql_default.sql`
-  `sql/ddl/post_mysql_default.sql`
-  `sql/ddl/comment_mysql_default.sql`

There is also a file containing sample content, as the tables are empty by default. Use the DDL in `sql/ddl/sample.sql` after all tables have been setup.


License
------------------

This software carries a MIT license.



```
 .  
..:  Copyright (c) 2018 Emil Sandberg (emil.hietanen@gmail.com)
```

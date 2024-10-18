# php-chatapp
This is the backend code of a chat app written in PHP

# Database Design

Database nam: chatapp.db

Contains multiple tables for different purposes.\
Tables:
1. Users
	columns-
	1. id - Integer (auto increment, primary key)
	2. username - alphanumeric, unique with no spaces and special characters, identifier
	3. name - string, name of the user, alphabetical only, no leading or trailing or consecutive spaces
	4. createdat - timestamp at which this user is created

2. Groups
	columns-
	1. id - Integer (auto increment, primary key)
	2. groupid - alphanumeric, unique with no spaces and special characters, identifier, always starts with 'group'. Example groupsoftwarengineering, users should use this id to join groups
	3. name - string, name of the group
	4. createdat - timestamp at which this group is created
	5. createdby - username of the user who created the group (foreign key from users table (username))

3. Messages
	columns-
	1. id - Integer (auto increment, primary key)
	2. groupid - groupid of the group this message is intended for (foreign key, groups table (groupid))
	3. username - username of the user created this message (foreing key, users table (username))
	4. content - string, content of the message
	5. createdat - timestamp at which this message is created

4. Members
	columns-
	1. id - Integer (auto increment, primary key)
	2. groupid - groupid of the group (foreign key, groups table (groupid))
	3. username - username of the user that joined a particular group (foreign key, users table (username))
	4. joinedat - timestamp at which this user has joined the group\
	the index (groupid, username) should be unique for the table

Please note that some of these constraints are enforced in the backend application code and some are eforced in the database.




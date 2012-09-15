CREATE TABLE USER 
(
	id SERIAL PRIMARY KEY,
	name text,
	dob date,
	weight integer,
	height integer,
	email text,
	pw-hash text
)

CREATE TABLE ACTIVITY
(
	id SERIAL PRIMARY KEY,
	type_id int,
	user_id int,
	duration int,
	date timestamp,
	comment text
)

CREATE TABLE ACTIVITY_TYPE
(
	id SERIAL PRIMARY KEY,
	name text,
	consumption_per_hour real
)

CREATE TABLE MEAL
(
	id SERIAL PRIMARY KEY,
	type_id int,
	user_id int,
	date timestamp,
	comment text
)

CREATE TABLE MEAL_TYPE
(
	id SERIAL PRIMARY KEY,
	name text
)

CREATE TABLE FOOD_MEAL_MAP
(
	meal_id int,
	food_id int,
	amount int
)

CREATE TABLE FOOD
(
	id SERIAL PRIMARY KEY,
	name text,
	carbs_per_unit real,
	protein_per_unit real,
	calories_per_unit real,
	unit_type_id int
)

CREATE TABLE FOOD_UNIT_TYPE
(
	id SERIAL PRIMARY KEY,
	name text
)

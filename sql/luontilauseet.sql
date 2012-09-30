CREATE TABLE USER_DATA
(
	id SERIAL PRIMARY KEY,
	type int DEFAULT 0,
	name text NOT NULL,
	dob date,
	weight integer,
	height integer,
	email text UNIQUE NOT NULL,
	pw_hash text,
	pw_salt text
);

CREATE TABLE ACTIVITY_TYPE
(
	id SERIAL PRIMARY KEY,
	name text UNIQUE NOT NULL,
	consumption_per_minute real
);

CREATE TABLE MEAL_TYPE
(
	id SERIAL PRIMARY KEY,
	name text UNIQUE NOT NULL
);

CREATE TABLE FOOD_UNIT_TYPE
(
	id SERIAL PRIMARY KEY,
	name text UNIQUE NOT NULL
);

CREATE TABLE ACTIVITY
(
	id SERIAL PRIMARY KEY,
	type_id int REFERENCES ACTIVITY_TYPE (id) ON DELETE RESTRICT,
	user_id int REFERENCES USER_DATA (id) ON DELETE CASCADE,
	duration int,
	date timestamp,
	comment text
);

CREATE TABLE MEAL
(
	id SERIAL PRIMARY KEY,
	type_id int REFERENCES MEAL_TYPE (id) ON DELETE RESTRICT,
	user_id int REFERENCES USER_DATA (id) ON DELETE CASCADE,
	date timestamp,
	comment text
);

CREATE TABLE FOOD
(
	id SERIAL PRIMARY KEY,
	name text UNIQUE NOT NULL,
	carbs_per_unit real,
	protein_per_unit real,
	calories_per_unit real,
	unit_type_id int REFERENCES FOOD_UNIT_TYPE (id) ON DELETE RESTRICT
);

CREATE TABLE FOOD_MEAL_MAP
(
	id SERIAL PRIMARY KEY,
	meal_id int REFERENCES MEAL (id) ON DELETE CASCADE,
	food_id int REFERENCES FOOD (id) ON DELETE RESTRICT,
	quantity int,
	UNIQUE(meal_id, food_id)
);


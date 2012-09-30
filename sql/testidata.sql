INSERT INTO USER_DATA VALUES (1, 1, 'Testi-1', '1980-04-12', 91, 185, 'testi@testi.com', md5('testi'), substring(md5(random()::TEXT) from 1 for 8));
UPDATE USER_DATA SET pw_hash=md5('testi' || USER_DATA.pw_salt) WHERE id=1;
INSERT INTO USER_DATA VALUES (2, DEFAULT, 'Testi-2', '1980-04-12', 91, 185, 'testi2@testi.com', md5('testi2'), substring(md5(random()::TEXT) from 1 for 8));
UPDATE USER_DATA SET pw_hash=md5('testi2' || USER_DATA.pw_salt) WHERE id=2;
INSERT INTO USER_DATA VALUES (3, DEFAULT, 'Testi-3', '1980-04-12', 91, 185, 'testi3@testi.com', md5('testi3'), substring(md5(random()::TEXT) from 1 for 8));
UPDATE USER_DATA SET pw_hash=md5('testi3' || USER_DATA.pw_salt) WHERE id=3;
INSERT INTO USER_DATA VALUES (4, DEFAULT, 'Testi-4', '1980-04-12', 91, 185, 'testi4@testi.com', md5('testi4'), substring(md5(random()::TEXT) from 1 for 8));
UPDATE USER_DATA SET pw_hash=md5('testi4' || USER_DATA.pw_salt) WHERE id=4;
INSERT INTO USER_DATA VALUES (5, DEFAULT, 'Testi-5', '1980-04-12', 91, 185, 'testi5@testi.com', md5('testi5'), substring(md5(random()::TEXT) from 1 for 8));
UPDATE USER_DATA SET pw_hash=md5('testi5' || USER_DATA.pw_salt) WHERE id=5;

INSERT INTO ACTIVITY_TYPE VALUES (0, 'Running', 15.0);
INSERT INTO ACTIVITY_TYPE VALUES (1, 'Walking', 5.0);
INSERT INTO ACTIVITY_TYPE VALUES (2, 'Cycling', 10.0);
INSERT INTO ACTIVITY_TYPE VALUES (3, 'Swimming', 30.0);

INSERT INTO ACTIVITY VALUES (DEFAULT, 0, 1, 55, '2012-09-22 15:20', 'Kauhee meininki 1');
INSERT INTO ACTIVITY VALUES (DEFAULT, 3, 2, 15, '2012-09-22 15:21', 'Kauhee meininki 2');
INSERT INTO ACTIVITY VALUES (DEFAULT, 1, 2, 25, '2012-09-22 15:22', 'Kauhee meininki 3');
INSERT INTO ACTIVITY VALUES (DEFAULT, 2, 1, 35, '2012-09-22 15:23', 'Kauhee meininki 4');
INSERT INTO ACTIVITY VALUES (DEFAULT, 2, 4, 45, '2012-09-22 15:24', 'Kauhee meininki 5');

INSERT INTO MEAL_TYPE VALUES (0, 'Breakfast');
INSERT INTO MEAL_TYPE VALUES (1, 'Morning snack');
INSERT INTO MEAL_TYPE VALUES (2, 'Lunch');
INSERT INTO MEAL_TYPE VALUES (3, 'Afternoon snack');
INSERT INTO MEAL_TYPE VALUES (4, 'Dinner');
INSERT INTO MEAL_TYPE VALUES (5, 'Supper');
INSERT INTO MEAL_TYPE VALUES (6, 'Other');

INSERT INTO FOOD_UNIT_TYPE VALUES (1, 'kg');
INSERT INTO FOOD_UNIT_TYPE VALUES (2, 'g');
INSERT INTO FOOD_UNIT_TYPE VALUES (3, 'l');
INSERT INTO FOOD_UNIT_TYPE VALUES (4, 'dl');
INSERT INTO FOOD_UNIT_TYPE VALUES (5, 'ml');
INSERT INTO FOOD_UNIT_TYPE VALUES (6, 'pcs');
INSERT INTO FOOD_UNIT_TYPE VALUES (7, 'pkg');

INSERT INTO FOOD VALUES (1, 'Hamburger', 60, 30, 600, 6);
INSERT INTO FOOD VALUES (2, 'Coke', 11, 0, 42, 4);
INSERT INTO FOOD VALUES (3, 'Carrot', 6, 0, 20, 6);
INSERT INTO FOOD VALUES (4, 'Milk', 6, 3, 30, 4);
INSERT INTO FOOD VALUES (5, 'Skyr', 20, 20, 300, 7);
INSERT INTO FOOD VALUES (6, 'Sugar', 1, 0, 1, 2);

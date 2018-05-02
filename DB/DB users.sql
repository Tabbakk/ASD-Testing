
CREATE USER 'therapist'@'localhost' IDENTIFIED BY 'therapistpw';
GRANT USAGE ON * . * TO 'therapist'@'localhost' IDENTIFIED BY 'therapistpw';
GRANT SELECT , INSERT , UPDATE , DELETE , EXECUTE ON `DISCAB` . * TO 'therapist'@'localhost';

CREATE USER 'user'@'localhost' IDENTIFIED BY 'userpw';
GRANT USAGE ON * . * TO 'user'@'localhost' IDENTIFIED BY 'userpw';
GRANT SELECT , INSERT , UPDATE ,  EXECUTE ON `DISCAB` . * TO 'user'@'localhost';

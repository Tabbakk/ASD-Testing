SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `DISCAB`
--
DROP DATABASE IF EXISTS `DISCAB`;
CREATE DATABASE `DISCAB` DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci;
USE `DISCAB`;

DELIMITER $$
--
-- Procedure
--
CREATE DEFINER=`root`@`localhost` PROCEDURE `active_test_ext`(in list varchar(50), out error tinyint)
BEGIN
	DECLARE tnum int;
	DECLARE actv int;
	DECLARE ntests int;
	DECLARE i int;
	DECLARE wlimit int;
	DECLARE exit handler for sqlexception, sqlwarning
	BEGIN
		ROLLBACK;
		set error = 1;
	END;
	set ntests = 8;
	set error=0;
	set wlimit = (ntests*2)+1;
	set i = 1;
	while i < wlimit do
		SELECT cast(SUBSTRING_INDEX(SUBSTRING_INDEX(list, ",", i), ",", -1) as char(1)) into tnum;
		SELECT cast(SUBSTRING_INDEX(SUBSTRING_INDEX(list, ",", i+1), ",", -1) as char(1)) into actv;
		update test set active=actv where code=tnum;
		set i=i+2;
	end while;
	commit;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `aq_delete`(IN idnum int)
BEGIN
	delete from aq_a where coidnum = idnum;
    delete from aq_c where idnumber = idnum;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `aq_insert`(IN list varchar(101), IN p int(11), IN qtype tinyint, IN atype tinyint, OUT error tinyint)
BEGIN
	DECLARE i INT;
	DECLARE id INT;
	DECLARE ans Char(1);
	declare tot tinyint(4);
	DECLARE exit handler for sqlexception, sqlwarning
        BEGIN
			ROLLBACK;
        	set error = 1;
        END;
	start transaction;		
		set error = 0;
		INSERT INTO aq_c (patient, test, qtype, atype, day) values (p,1,qtype,atype,curdate());
		set id = 0;
		select max(idnumber) into id from aq_c where patient = p;
		SET i = 1;
		set ans = '';
		set tot = 0;
		WHILE i < 51 DO
			set @punti = 0;
			set @tipo = 0;
			SELECT cast(SUBSTRING_INDEX(SUBSTRING_INDEX(list, ",", i), ",", -1) as char(1)) into ans;
			set @query = concat('select ', ans, ' into @punti from aq_ca where QNum = ',cast(i as char));
			prepare stmt from @query;
			execute stmt;
			deallocate prepare stmt;
			INSERT INTO aq_a (coidnum, qnum, answer) values (id,i,ans);
			set tot = tot + @punti;
			set i = i+1;
		END WHILE;
		update aq_c set total = tot where idnumber = id;
	commit;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `attint_delete`(IN idnum int)
BEGIN
	delete from attint_a where coidnum = idnum;
    delete from attint_c where idnumber = idnum;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `attint_insert`(IN list varchar(81), IN s tinyint, IN p int(11), IN qtype tinyint, IN atype tinyint, OUT error tinyint)
BEGIN
	DECLARE i INT;
	DECLARE id INT;
	DECLARE ans Char(1);
	declare tot tinyint(4);
	declare q tinyint(4);
	DECLARE exit handler for sqlexception, sqlwarning
        BEGIN
			ROLLBACK;
        	set error = 1;
        END;
	start transaction;		
		set error = 0;
		INSERT INTO attint_c (series, patient, test, qtype, atype, day) values (s,p,2,qtype,atype,curdate());
		set id = 0;
		select max(idnumber) into id from attint_c where patient = p;
		SET i = 1;
		set ans = '';
		set tot = 0;
		if s = 2 then
			set q = 13;
		elseif s = 3 then
			set q = 13;
		else
			set q = 15;
		end if;
		WHILE i < q DO
			set @correct = '';
			SELECT cast(SUBSTRING_INDEX(SUBSTRING_INDEX(list, ",", i), ",", -1) as char(1)) into ans;
			set @query = concat('select Answer into @correct from attint_ca where QNum = ',cast(i as char),' and Series = ',s);
			prepare stmt from @query;
			execute stmt;
			deallocate prepare stmt;
			INSERT INTO attint_a (coidnum, qnum, answer, series) values (id,i,ans, s);
			if @correct = ans then
				set tot = tot+1;
			end if;
			set i = i+1;
		END WHILE;
		update attint_c set total = tot where idnumber = id;
	commit;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `attint_view`(IN idnum int, IN atype int)
BEGIN
	declare s int;
	declare i int;
	declare lim int;
	DROP TEMPORARY TABLE IF EXISTS answers;
	create temporary table answers (
		qid int,
		address varchar(255)
	);
	select series into s from attint_a where coidnum=idnum limit 1;
	if s=2 or s=3 then
		set lim =13;
	else
		set lim=15;
	end if;
	set i = 1;
	while i<lim do
		set @a = '';
		set @link = '';
		select answer into @a from attint_a where coidnum=idnum and qnum=i;
		SET @query = CONCAT('SELECT ',@a,' into @link FROM attint_ac where qnum=',i,' and series=',s,' and atype=',atype);

		PREPARE stmt FROM @query;
		EXECUTE stmt;

		insert into answers (qid,address) values (i, @link);
		set i = i+1;
	end while;
	select attint_a.series as series, attint_a.qnum as qnum, attint_qc.A as A, attint_qc.B as B, attint_qc.C as C, answers.address as loc from attint_a, attint_qc, answers where attint_a.coidnum=idnum and attint_qc.series=attint_a.series and attint_qc.qnum=attint_a.qnum and answers.qid=attint_a.qnum;
	
	DROP TEMPORARY TABLE IF EXISTS answers;

END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `bes_delete`(IN idnum int)
BEGIN
	delete from bes_a where coidnum = idnum;
    delete from bes_c where idnumber = idnum;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `bes_insert`(IN list varchar(41), IN p int(11), IN qtype tinyint, IN atype tinyint, OUT error tinyint)
BEGIN
	DECLARE i INT;
	DECLARE id INT;
	DECLARE ans Char(1);
	declare pce tinyint(4);
	declare pae tinyint(4);
	DECLARE exit handler for sqlexception, sqlwarning
        BEGIN
			ROLLBACK;
        	set error = 1;
        END;
	start transaction;		
		set error = 0;
		INSERT INTO bes_c (patient, test, qtype, atype, day) values (p,3,qtype,atype,curdate());
		set id = 0;
		select max(idnumber) into id from bes_c where patient = p;
		SET i = 1;
		set ans = '';
		set pae = 0;
		set pce = 0;
		WHILE i < 21 DO
			SELECT cast(SUBSTRING_INDEX(SUBSTRING_INDEX(list, ",", i), ",", -1) as char(1)) into ans;
			set @query = concat('select ', ans, ' into @punti from bes_ca where QNum = ',cast(i as char));
			prepare stmt from @query;
			execute stmt;
			set @query = concat('select type into @tipo from bes_ca where QNum = ',cast(i as char));
			prepare stmt from @query;
			execute stmt;
			deallocate prepare stmt;
			INSERT INTO bes_a (coidnum, qnum, answer) values (id,i,ans);
			if @tipo = 1 then
				set pce = pce + @punti;
			else
				set pae = pae + @punti;
			end if;
			set i = i+1;
		END WHILE;
		update bes_c set ce = pce , ae = pae where idnumber = id;
	commit;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `delete_clinicalg`(in i int, out error tinyint)
BEGIN
	DECLARE exit handler for sqlexception, sqlwarning
	BEGIN
		ROLLBACK;
		set error = 1;
	END;
	set error=0;
	update patient set cg=-1 where cg = i;
	delete from clinicalg where code = i;
	commit;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `delete_ext_user`(in i int)
deleteExtUser:BEGIN
	select patient into @i from user where id = i;
	if @i != 0 then 
		LEAVE deleteExtUser;
	end if;
	delete from aq_a where coidnum in (select idnumber from aq_c where patient = i);
	delete from aq_c where patient = i;
	delete from attint_a where coidnum in (select idnumber from attint_c where patient = i);
	delete from attint_c where patient = i;
	delete from bes_a where coidnum in (select idnumber from bes_c where patient = i);
	delete from bes_c where patient = i;
	delete from emotatt_a where coidnum in (select idnumber from emotatt_c where patient = i);
	delete from emotatt_c where patient = i;
	delete from eyestask_a where coidnum in (select idnumber from eyestask_c where patient = i);
	delete from eyestask_c where patient = i;
	delete from qe_a where coidnum in (select idnumber from qe_c where patient = i);
	delete from qe_c where patient = i;
	delete from socialsit_a where coidnum in (select idnumber from socialsit_c where patient = i);
	delete from socialsit_c where patient = i;
	delete from tom_a where coidnum in (select idnumber from tom_c where patient = i);
	delete from tom_c where patient = i;
	delete from ext_user where id = i;
	delete from patient_tests where id = i;
	delete from user where id = i;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `delete_patient`(in i int)
deletePatient:BEGIN
	select patient into @i from user where id = i;
	if @i != 1 then 
		LEAVE deletePatient;
	end if;
	delete from aq_a where coidnum in (select idnumber from aq_c where patient = i);
	delete from aq_c where patient = i;
	delete from attint_a where coidnum in (select idnumber from attint_c where patient = i);
	delete from attint_c where patient = i;
	delete from bes_a where coidnum in (select idnumber from bes_c where patient = i);
	delete from bes_c where patient = i;
	delete from emotatt_a where coidnum in (select idnumber from emotatt_c where patient = i);
	delete from emotatt_c where patient = i;
	delete from eyestask_a where coidnum in (select idnumber from eyestask_c where patient = i);
	delete from eyestask_c where patient = i;
	delete from qe_a where coidnum in (select idnumber from qe_c where patient = i);
	delete from qe_c where patient = i;
	delete from socialsit_a where coidnum in (select idnumber from socialsit_c where patient = i);
	delete from socialsit_c where patient = i;
	delete from tom_a where coidnum in (select idnumber from tom_c where patient = i);
	delete from tom_c where patient = i;
	delete from patient where id = i;
	delete from patient_tests where id = i;
	delete from user where id = i;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `delete_therapist`(in i int, out error tinyint)
deleteTherapist:BEGIN
	set error=0;
	select count(*) into @i from therapist where admin = 1 and id != -1;
	if @i = 1 then
		select admin into @ad from therapist where id = i;
		if @ad = 1 then
			set error = 1;
			LEAVE deleteTherapist;
		end if;
	end if;
	update emotatt_a set docid=-1 where docid=i;
	update tom_a set docid=-1 where docid=i;
	delete from therapist where id = i;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `emotatt_correct`(in tnmbr int, in qnmbr int, in giusto boolean, in addword boolean, in tid int, out error boolean)
BEGIN
	declare s1 int;
	declare s2 int;
	declare s3 int;
	declare s4 int;
	declare s5 int;
	declare s6 int;
	declare s7 int;
	declare i int;
	DECLARE exit handler for sqlexception, sqlwarning
        BEGIN
			ROLLBACK;
        	set error = 1;
        END;
	DECLARE CONTINUE HANDLER FOR NOT FOUND
    BEGIN
       SELECT 1 INTO @handler_invoked FROM (SELECT 1) AS t;
    END;
	start transaction;
		set error = 0;
		if giusto = 1 then
			select answer into @ca from emotatt_ca where qnum=qnmbr;
			update emotatt_a set ans_code=@ca, docid=tid, verified=1, day=curdate() where coidnum=tnmbr and qnum=qnmbr;
			if addword = 1 then
				select answer into @a from emotatt_a where coidnum=tnmbr and qnum=qnmbr;
				SELECT count(emocode) into @num FROM emotion where emot like @a;
				if @num = 0 then
					insert into emotion (emocode, emot) values (@ca, @a);
				end if;
			end if;	
		else
			update emotatt_a set ans_code=0, docid=tid, verified=1, day=curdate() where coidnum=tnmbr and qnum=qnmbr;
		end if;
		set @incomplete = null;
		select count(qnum) into @incomplete from emotatt_a where coidnum = tnmbr and verified = 0;
		if @incomplete = 0 then
			SET s1 = 0;
			SET s2 = 0;
			SET s3 = 0;
			SET s4 = 0;
			SET s5 = 0;
			SET s6 = 0;
			SET s7 = 0;
			set i = 1;
			WHILE i < 59 DO
				set @correct = 0;
				set @given = 0;
				select answer into @correct from emotatt_ca where qnum = i;
				select ans_code into @given from emotatt_a where qnum = i and coidnum = tnmbr;
				if @correct = @given then
					if @correct = 1 then
						set s1 = s1 + 1;
					elseif @correct = 2 then
						set s2 = s2 + 1;
					elseif @correct = 3 then
						set s3 = s3 + 1;
					elseif @correct = 4 then
						set s4 = s4 + 1;
					elseif @correct = 5 then
						set s5 = s5 + 1;
					elseif @correct = 6 then
						set s6 = s6 + 1;
					elseif @correct = 7 then
						set s7 = s7 + 1;
					end if;
				end if;
				set i = i+1;
			end while;
			update emotatt_c set e1=s1, e2=s2, e3=s3, e4=s4, e5=s5, e6=s6, e7=s7, completed = 1 where idnumber = tnmbr;
		end if;
	commit;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `emotatt_delete`(IN idnum int)
BEGIN
	delete from emotatt_a where coidnum = idnum;
    delete from emotatt_c where idnumber = idnum;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `emotatt_insert`(IN list text, IN p int(11), IN qtype tinyint, IN atype tinyint, OUT error tinyint)
BEGIN
	DECLARE i INT;
	DECLARE id INT;
	declare text_ans varchar(20);
	declare ver tinyint;
	declare s1 int;
	declare s2 int;
	declare s3 int;
	declare s4 int;
	declare s5 int;
	declare s6 int;
	declare s7 int;
	DECLARE exit handler for sqlexception, sqlwarning
        BEGIN
			ROLLBACK;
        	set error = 1;
        END;
	DECLARE CONTINUE HANDLER FOR NOT FOUND
    BEGIN
       SELECT 1 INTO @handler_invoked FROM (SELECT 1) AS t;
    END;
	start transaction;		
		set error = 0;
		INSERT INTO emotatt_c (patient, test, qtype, atype, day) values (p,4,qtype,atype,curdate());
		set id = 0;
		select max(idnumber) into id from emotatt_c where patient = p;
		SET i = 1;
		set text_ans = '';
		WHILE i < 59 DO
			set @code = null;
			SELECT substring_index(substring_index(list, ',', i),',',-1) into text_ans;
			SELECT EmoCode into @code from emotion where Emot like text_ans;
			if @code is null then
				set ver = 0;
			else 
				set ver = 1;
			end if;
			INSERT INTO emotatt_a (coidnum, qnum, answer, ans_code, verified) values (id,i,text_ans,@code,ver);
			set i = i+1;
		END WHILE;
		set i = 1;
		set @incomplete = null;
		select count(qnum) into @incomplete from emotatt_a where coidnum = id and verified = 0;
		if @incomplete = 0 then
			SET s1 = 0;
			SET s2 = 0;
			SET s3 = 0;
			SET s4 = 0;
			SET s5 = 0;
			SET s6 = 0;
			SET s7 = 0;
			set i = 1;
			WHILE i < 59 DO
				set @correct = 0;
				set @given = 0;
				select answer into @correct from emotatt_ca where qnum = i;
				select ans_code into @given from emotatt_a where qnum = i and coidnum = id;
				if @correct = @given then
					if @correct = 1 then
						set s1 = s1 + 1;
					elseif @correct = 2 then
						set s2 = s2 + 1;
					elseif @correct = 3 then
						set s3 = s3 + 1;
					elseif @correct = 4 then
						set s4 = s4 + 1;
					elseif @correct = 5 then
						set s5 = s5 + 1;
					elseif @correct = 6 then
						set s6 = s6 + 1;
					elseif @correct = 7 then
						set s7 = s7 + 1;
					end if;
				end if;
				set i = i+1;
			end while;
			update emotatt_c set e1=s1, e2=s2, e3=s3, e4=s4, e5=s5, e6=s6, e7=s7, completed = 1 where idnumber = id;
			update emotatt_a set day = curdate() where coidnum = id;
		end if;	
	commit;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `emotatt_score`(in tnmbr int, out error boolean)
BEGIN
	declare s1 int;
	declare s2 int;
	declare s3 int;
	declare s4 int;
	declare s5 int;
	declare s6 int;
	declare s7 int;
	declare i int;
	DECLARE exit handler for sqlexception, sqlwarning
        BEGIN
			ROLLBACK;
        	set error = 1;
        END;
	DECLARE CONTINUE HANDLER FOR NOT FOUND
    BEGIN
       SELECT 1 INTO @handler_invoked FROM (SELECT 1) AS t;
    END;
	start transaction;
		set error = 0;
		set @incomplete = null;
		select count(qnum) into @incomplete from emotatt_a where coidnum = tnmbr and verified = 0;
		if @incomplete = 0 then
			SET s1 = 0;
			SET s2 = 0;
			SET s3 = 0;
			SET s4 = 0;
			SET s5 = 0;
			SET s6 = 0;
			SET s7 = 0;
			set i = 1;
			WHILE i < 59 DO
				set @correct=0;
				set @given=0;
				select answer into @correct from emotatt_ca where qnum = i;
				select ans_code into @given from emotatt_a where qnum = i and coidnum = tnmbr;
				if @correct = @given then
					if @correct = 1 then
						set s1 = s1 + 1;
					elseif @correct = 2 then
						set s2 = s2 + 1;
					elseif @correct = 3 then
						set s3 = s3 + 1;
					elseif @correct = 4 then
						set s4 = s4 + 1;
					elseif @correct = 5 then
						set s5 = s5 + 1;
					elseif @correct = 6 then
						set s6 = s6 + 1;
					elseif @correct = 7 then
						set s7 = s7 + 1;
					end if;
				end if;
				set i = i+1;
			end while;
			update emotatt_c set e1=s1, e2=s2, e3=s3, e4=s4, e5=s5, e6=s6, e7=s7, completed = 1 where idnumber = tnmbr;
		end if;
	commit;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `eyestask_delete`(IN idnum int)
BEGIN
	delete from eyestask_a where coidnum = idnum;
    delete from eyestask_c where idnumber = idnum;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `eyestask_insert`(IN list varchar(75), IN p int(11), IN qtype tinyint, IN atype tinyint, OUT error tinyint)
BEGIN
	DECLARE i INT;
	DECLARE id INT;
	DECLARE ans Char(1);
	declare cans char(1);
	declare tot tinyint(4);
	DECLARE exit handler for sqlexception, sqlwarning
        BEGIN
			ROLLBACK;
        	set error = 1;
        END;
	start transaction;		
		set error = 0;
		INSERT INTO eyestask_c (patient, test, qtype, atype, day) values (p,5,qtype,atype,curdate());
		set id = 0;
		select max(idnumber) into id from eyestask_c where patient = p;
		SET i = 1;
		set ans = '';
		set tot = 0;
		WHILE i < 38 DO
			set @ca = '';
			SELECT cast(SUBSTRING_INDEX(SUBSTRING_INDEX(list, ",", i), ",", -1) as char(1)) into ans;
			SELECT answer into @ca from eyestask_ca where QNum = cast(i as char);
			INSERT INTO eyestask_a (coidnum, qnum, answer) values (id, i, @ca);
			if ans = @ca then
				set tot = tot + 1;
			end if;
			set i = i+1;
		END WHILE;
		update eyestask_c set total = tot where idnumber = id;
	commit;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `insert_ext_user`(in uname varchar(50), in pass varchar(50), in bday varchar(15), in s varchar(1), in schol tinyint, in eml varchar(50), in fac varchar(50), in sy varchar(50), in mat varchar(50), out error tinyint)
BEGIN
	declare userid int;
	DECLARE exit handler for sqlexception, sqlwarning
	BEGIN
		ROLLBACK;
		set error = 1;
	END;
	start transaction;
		set error = 0;
		set userid = 0;
		Insert into user (username, password, birthdate, sex, scholarity, patient) values (uname, pass, cast(bday as date), s, schol, 0);
		select id into userid from user where username = uname;
		Insert into ext_user (id, email, faculty, syear, idnumber) values (userid, eml, fac, sy, mat);
	commit;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `insert_patient`(in uname varchar(50), in pass varchar(50), in bday varchar(15), in s varchar(1), in schol tinyint, in pname varchar(50), in psurname varchar(50), in num varchar(50), in cgrp tinyint, out error tinyint)
BEGIN
	declare userid int;
	DECLARE exit handler for sqlexception, sqlwarning
	BEGIN
		ROLLBACK;
		set error = 1;
	END;
	start transaction;
		set error = 0;
		set userid = 0;
		Insert into user (username, password, birthdate, sex, scholarity, patient) values (uname, pass, cast(bday as date), s, schol, 1);
		select id into userid from user where username = uname;
		Insert into patient (id, cg, name, surname, number) values (userid, cgrp, pname, psurname, num);
		insert into patient_tests (id, aq, attint, bes, emotatt, eyestask, qe, socialsit, tom) values (userid,1,1,1,1,1,1,1,1);
	commit;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `insert_therapist`(in uname varchar(50), in pass varchar(50), in nm varchar(50), in snm varchar(50), in adm boolean, out error tinyint)
BEGIN
	declare userid int;
	DECLARE exit handler for sqlexception, sqlwarning
	BEGIN
		ROLLBACK;
		set error = 1;
	END;
	start transaction;
		set error = 0;
		set userid = 0;
		Insert into therapist (username, password, name, surname, admin) values (uname, pass, nm, snm, adm);
	commit;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `prova`(IN s varchar(255),in n int, OUT c int)
BEGIN
	SELECT EmoCode into @code from emotion where Emot like s;
	set c = @code;
	if @code is null then
		set @ver = 0;
	else 
		set @ver = 1;
	end if;
	INSERT INTO emotatt_a (coidnum, qnum, answer, ans_code, verified) values (6,n,s,@code,@ver);
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `qe_delete`(IN idnum int)
BEGIN
	delete from qe_a where coidnum = idnum;
    delete from qe_c where idnumber = idnum;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `qe_insert`(IN list varchar(81), IN p int(11), IN qtype tinyint, IN atype tinyint, OUT error tinyint)
BEGIN
	DECLARE i INT;
	DECLARE id INT;
	DECLARE ans Char(1);
	declare pce tinyint(4);
	declare pee tinyint(4);
	declare pss tinyint(4);
	declare tot tinyint(4);
	DECLARE exit handler for sqlexception, sqlwarning
        BEGIN
			ROLLBACK;
        	set error = 1;
        END;
	start transaction;		
		set error = 0;
		INSERT INTO qe_c (patient, test, qtype, atype, day) values (p,6,qtype,atype,curdate());
		set id = 0;
		select max(idnumber) into id from qe_c where patient = p;
		SET i = 1;
		set ans = '';
		set pce = 0;
		set pee = 0;
		set pss = 0;
		set tot = 0;
		WHILE i < 41 DO
			set @punti = 0;
			set @tipo = 0;
			SELECT cast(SUBSTRING_INDEX(SUBSTRING_INDEX(list, ",", i), ",", -1) as char(1)) into ans;
			set @query = concat('select ', ans, ' into @punti from qe_ca where QNum = ',cast(i as char));
			prepare stmt from @query;
			execute stmt;
			set @query = concat('select type into @tipo from qe_ca where QNum = ',cast(i as char));
			prepare stmt from @query;
			execute stmt;
			deallocate prepare stmt;
			INSERT INTO qe_a (coidnum, qnum, answer) values (id,i,ans);
			if @tipo = 1 then
				set pce = pce + @punti;
			elseif @tipo = 2 then
				set pee = pee + @punti;
			elseif @tipo = 3 then
				set pss = pss + @punti;
			else
				set tot = tot + @punti;
			end if;
			set i = i+1;
		END WHILE;
		set tot = tot + pce + pee + pss;
		update qe_c set ce = pce , ee = pee, ss = pss, total = tot where idnumber = id;
	commit;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `socialsit_delete`(IN idnum int)
BEGIN
	delete from socialsit_a where coidnum = idnum;
    delete from socialsit_c where idnumber = idnum;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `socialsit_insert`(IN list varchar(81), IN p int(11), IN qtype tinyint, IN atype tinyint, OUT error tinyint)
BEGIN
	DECLARE i INT;
	DECLARE id INT;
	DECLARE ans Char(1);
	declare qnum_list varchar(255);
	declare subqnum_list varchar(255);
	declare qnum_cycle tinyint;
	declare subqnum_cycle tinyint;
	declare norm tinyint(4);
	declare viol tinyint(4);
	declare sev tinyint(4);
	DECLARE exit handler for sqlexception, sqlwarning
        BEGIN
			ROLLBACK;
        	set error = 1;
        END;
	start transaction;		
		set error = 0;
		set qnum_list = '1,1,2,2,3,3,3,4,5,5,5,6,7,7,8,8,9,10,11,12,12,13,14,14,15,16,17,17,17,18,19,20,21,21,22,23,23,24,25,25';
		set subqnum_list = '1,2,1,2,1,2,3,1,1,2,3,1,1,2,1,2,1,1,1,1,2,1,1,2,1,1,1,2,3,1,1,1,1,2,1,1,2,1,1,2';
		INSERT INTO socialsit_c (patient, test, qtype, atype, day) values (p,7,qtype,atype,curdate());
		set id = 0;
		select max(idnumber) into id from socialsit_c where patient = p;
		SET i = 1;
		set qnum_cycle = 0;
		set subqnum_cycle = 0;
		set ans = '';
		set norm = 0;
		set viol = 0;
		set sev = 0;
		WHILE i < 41 DO
			set @punti = 0;
			set @tipo = 0;
			SELECT cast(SUBSTRING_INDEX(SUBSTRING_INDEX(qnum_list, ",", i), ",", -1) as char(5)) into qnum_cycle;
			SELECT cast(SUBSTRING_INDEX(SUBSTRING_INDEX(subqnum_list, ",", i), ",", -1) as char(5)) into subqnum_cycle;
			SELECT cast(SUBSTRING_INDEX(SUBSTRING_INDEX(list, ",", i), ",", -1) as char(1)) into ans;
			set @query = concat('select ', ans, ' into @punti from socialsit_ca where QNum = ',qnum_cycle,' and SubQNum = ',subqnum_cycle);
			prepare stmt from @query;
			execute stmt;
			set @query = concat('select type into @tipo from socialsit_ca where QNum = ',qnum_cycle,' and SubQNum = ',subqnum_cycle);
			prepare stmt from @query;
			execute stmt;
			deallocate prepare stmt;
			INSERT INTO socialsit_a (coidnum, qnum, subqnum, answer) values (id, qnum_cycle, subqnum_cycle, ans);
			if @tipo = 0 then
				set norm = norm + @punti;
			else
				set sev = sev + @punti;
				if @punti > 0 then
					set viol = viol + 1;
				end if;
			end if;
			set i = i+1;
		END WHILE;
		update socialsit_c set NormB = norm , Violation = viol, Severity = sev where idnumber = id;
	commit;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `tom_correct`(IN id int, IN list varchar(50), IN tid int, out error boolean)
CorrectTom:BEGIN
	declare score int;
	declare i int;
	DECLARE exit handler for sqlexception, sqlwarning
	BEGIN
		ROLLBACK;
		set error = 1;
    END;
	select count(idnumber) into @v from tom_c where idnumber=id and completed=1;
	if @v = 1 then
		set error = 1;
		LEAVE CorrectTom;
	end if;	
	start transaction;		
		set error = 0;
		set score = 0;
		set i = 1;
		WHILE i < 14 DO
			SELECT cast(SUBSTRING_INDEX(SUBSTRING_INDEX(list, ",", i), ",", -1) as signed) into @a;
			if @a = 1 then
				set score=score+1;
			end if;
			update tom_a set verified = 1, correct = @a, docid=tid, day=curdate() where coidnum = id and qnum = i;
			set i = i+1;
		end while;
		update tom_c set completed=1, total=score where idnumber=id;		
	commit;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `tom_delete`(IN idnum int)
BEGIN
	delete from tom_a where coidnum = idnum;
    delete from tom_c where idnumber = idnum;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `tom_insert`(IN list1 varchar(25),IN list2 text, IN p int(11), IN qtype tinyint, IN atype tinyint, OUT error tinyint)
BEGIN
	DECLARE i INT;
	DECLARE id INT;
	DECLARE ans Char(1);
	declare text_ans varchar(255);
	DECLARE exit handler for sqlexception, sqlwarning
        BEGIN
			ROLLBACK;
        	set error = 1;
        END;
	start transaction;		
		set error = 0;
		INSERT INTO tom_c (patient, test, qtype, atype, day) values (p,8,qtype,atype,curdate());
		set id = 0;
		select max(idnumber) into id from tom_c where patient = p;
		SET i = 1;
		set ans = '';
		set text_ans = '';
		WHILE i < 14 DO
			SELECT cast(SUBSTRING_INDEX(SUBSTRING_INDEX(list1, ",", i), ",", -1) as char(1)) into ans;
			SELECT substring_index(substring_index(list2, ',', i),',',-1) into text_ans;
			INSERT INTO tom_a (coidnum, qnum, a1, a2, verified, correct) values (id,i,cast(ans as signed),text_ans,0,0);
			set i = i+1;
		END WHILE;
	commit;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `update_ext_user`(in uid int, in uname varchar(50), in bday varchar(15), in s varchar(1), in schol tinyint, in eml varchar(50), in fac varchar(50), in sy varchar(50), in mat varchar(50), out error tinyint)
BEGIN
	declare userid int;
	DECLARE exit handler for sqlexception, sqlwarning
	BEGIN
		ROLLBACK;
		set error = 1;
	END;
	start transaction;
		set error = 0;
		update user set username=uname, birthdate=cast(bday as date), sex=s, scholarity=schol where id=uid;
		update ext_user set email=eml, faculty=fac, syear=sy, idnumber=mat where id=uid;
	commit;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `update_ext_user_pw`(in uid int, in uname varchar(50), in pass varchar(50), in bday varchar(15), in s varchar(1), in schol tinyint, in eml varchar(50), in fac varchar(50), in sy varchar(50), in mat varchar(50), out error tinyint)
BEGIN
	declare userid int;
	DECLARE exit handler for sqlexception, sqlwarning
	BEGIN
		ROLLBACK;
		set error = 1;
	END;
	start transaction;
		set error = 0;
		update user set username=uname, password=pass, birthdate=cast(bday as date), sex=s, scholarity=schol where id=uid;
		update ext_user set email=eml, faculty=fac, syear=sy, idnumber=mat where id=uid;
	commit;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `update_patient`(in uid int, in uname varchar(50), in bday varchar(15), in s varchar(1), in schol tinyint, in pname varchar(50), in psurname varchar(50), in num varchar(50), in cgrp tinyint, out error tinyint)
BEGIN
	DECLARE exit handler for sqlexception, sqlwarning
	BEGIN
		ROLLBACK;
		set error = 1;
	END;
	start transaction;
		set error = 0;
		update user set username=uname, birthdate=cast(bday as date), sex=s, scholarity=schol where id = uid;
		update patient set cg=cgrp, name=pname, surname=psurname, number=num where id=uid;
	commit;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `update_patient_pw`(in uid int, in uname varchar(50), in pass varchar(50), in bday varchar(15), in s varchar(1), in schol tinyint, in pname varchar(50), in psurname varchar(50), in num varchar(50), in cgrp tinyint, out error tinyint)
BEGIN
	DECLARE exit handler for sqlexception, sqlwarning
	BEGIN
		ROLLBACK;
		set error = 1;
	END;
	start transaction;
		set error = 0;
		update user set username=uname, password=pass, birthdate=cast(bday as date), sex=s, scholarity=schol where id = uid;
		update patient set cg=cgrp, name=pname, surname=psurname, number=num where id=uid;
	commit;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `update_therapist`(in uid int, in uname varchar(50), in nm varchar(50), in snm varchar(50), in adm boolean, out error tinyint)
updt_therapist:BEGIN
	declare admstatus int;
	declare admcount int;
	DECLARE exit handler for sqlexception, sqlwarning
	BEGIN
		ROLLBACK;
		set error = 1;
	END;
	start transaction;
		if adm = 0 then
			select admin into admstatus from therapist where id=uid;
			if admstatus = 1 then
				select count(*) into admcount from therapist where admin=1 and id != -1;
				if admcount = 1 then
					set error = 2;
					LEAVE updt_therapist;
				end if;
			end if;
		end if;
		set error = 0;
		update therapist set username=uname, name=nm, surname=snm, admin=adm where id=uid;
	commit;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `update_therapist_pw`(in uid int, in uname varchar(50), in pass varchar(50), in nm varchar(50), in snm varchar(50), in adm boolean, out error tinyint)
updt_therapist:BEGIN
	declare admstatus int;
	declare admcount int;
	DECLARE exit handler for sqlexception, sqlwarning
	BEGIN
		ROLLBACK;
		set error = 1;
	END;
	start transaction;
		if adm = 0 then
			select admin into admstatus from therapist where id=uid;
			if admstatus = 1 then
				select count(*) into admcount from therapist where admin=1 and id != -1;
				if admcount = 1 then
					set error = 2;
					LEAVE updt_therapist;
				end if;
			end if;
		end if;
		set error = 0;
		update therapist set username=uname, password=pass, name=nm, surname=snm, admin=adm where id=uid;
	commit;
END$$

DELIMITER ;

-- --------------------------------------------------------

--
-- Struttura della tabella `aq_a`
--
-- Creazione: Set 29, 2016 alle 15:12
--

CREATE TABLE IF NOT EXISTS `aq_a` (
  `COIDNum` int(11) NOT NULL DEFAULT '0',
  `QNum` tinyint(4) NOT NULL DEFAULT '0',
  `Answer` char(1) DEFAULT NULL,
  PRIMARY KEY (`COIDNum`,`QNum`),
  KEY `QNum` (`QNum`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- RELATIONS FOR TABLE `aq_a`:
--   `COIDNum`
--       `aq_c` -> `idnumber`
--   `QNum`
--       `aq_ca` -> `QNum`
--

--
-- Dump dei dati per la tabella `aq_a`
--


-- --------------------------------------------------------

--
-- Struttura della tabella `aq_c`
--
-- Creazione: Set 29, 2016 alle 15:12
--

CREATE TABLE IF NOT EXISTS `aq_c` (
  `idnumber` int(11) NOT NULL AUTO_INCREMENT,
  `patient` int(10) unsigned DEFAULT NULL,
  `test` tinyint(3) unsigned DEFAULT NULL,
  `QType` tinyint(4) NOT NULL,
  `AType` tinyint(4) NOT NULL,
  `total` tinyint(4) DEFAULT NULL,
  `Day` date DEFAULT NULL,
  PRIMARY KEY (`idnumber`),
  KEY `patient` (`patient`),
  KEY `test` (`test`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=4 ;

--
-- RELATIONS FOR TABLE `aq_c`:
--   `patient`
--       `user` -> `id`
--   `test`
--       `test` -> `code`
--

--
-- Dump dei dati per la tabella `aq_c`
--

-- --------------------------------------------------------

--
-- Struttura della tabella `aq_ca`
--
-- Creazione: Set 29, 2016 alle 15:12
--

CREATE TABLE IF NOT EXISTS `aq_ca` (
  `QNum` tinyint(4) NOT NULL DEFAULT '0',
  `A` tinyint(4) NOT NULL,
  `B` tinyint(4) NOT NULL,
  `C` tinyint(4) NOT NULL,
  `D` tinyint(4) NOT NULL,
  PRIMARY KEY (`QNum`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dump dei dati per la tabella `aq_ca`
--

INSERT INTO `aq_ca` (`QNum`, `A`, `B`, `C`, `D`) VALUES
(1, 0, 0, 1, 1),
(2, 1, 1, 0, 0),
(3, 0, 0, 1, 1),
(4, 1, 1, 0, 0),
(5, 1, 1, 0, 0),
(6, 1, 1, 0, 0),
(7, 1, 1, 0, 0),
(8, 0, 0, 1, 1),
(9, 1, 1, 0, 0),
(10, 0, 0, 1, 1),
(11, 0, 0, 1, 1),
(12, 1, 1, 0, 0),
(13, 1, 1, 0, 0),
(14, 0, 0, 1, 1),
(15, 0, 0, 1, 1),
(16, 1, 1, 0, 0),
(17, 0, 0, 1, 1),
(18, 1, 1, 0, 0),
(19, 1, 1, 0, 0),
(20, 1, 1, 0, 0),
(21, 1, 1, 0, 0),
(22, 1, 1, 0, 0),
(23, 1, 1, 0, 0),
(24, 0, 0, 1, 1),
(25, 0, 0, 1, 1),
(26, 1, 1, 0, 0),
(27, 0, 0, 1, 1),
(28, 0, 0, 1, 1),
(29, 0, 0, 1, 1),
(30, 0, 0, 1, 1),
(31, 0, 0, 1, 1),
(32, 0, 0, 1, 1),
(33, 1, 1, 0, 0),
(34, 0, 0, 1, 1),
(35, 1, 1, 0, 0),
(36, 0, 0, 1, 1),
(37, 0, 0, 1, 1),
(38, 0, 0, 1, 1),
(39, 1, 1, 0, 0),
(40, 0, 0, 1, 1),
(41, 1, 1, 0, 0),
(42, 1, 1, 0, 0),
(43, 1, 1, 0, 0),
(44, 0, 0, 1, 1),
(45, 1, 1, 0, 0),
(46, 1, 1, 0, 0),
(47, 0, 0, 1, 1),
(48, 0, 0, 1, 1),
(49, 0, 0, 1, 1),
(50, 0, 0, 1, 1);

-- --------------------------------------------------------

--
-- Struttura della tabella `aq_qc`
--
-- Creazione: Set 29, 2016 alle 15:14
--

CREATE TABLE IF NOT EXISTS `aq_qc` (
  `QNum` tinyint(4) NOT NULL DEFAULT '0',
  `QType` tinyint(4) NOT NULL DEFAULT '0',
  `Question` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`QNum`,`QType`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- RELATIONS FOR TABLE `aq_qc`:
--   `QNum`
--       `aq_ca` -> `QNum`
--

--
-- Dump dei dati per la tabella `aq_qc`
--

INSERT INTO `aq_qc` (`QNum`, `QType`, `Question`) VALUES
(1, 1, 'Preferisco svolgere le attività con gli altri piuttosto che da solo.'),
(2, 1, 'Preferisco fare le cose sempre nello stesso modo.'),
(3, 1, 'Se cerco di immaginare qualcosa, trovo molto semplice creare un’immagine nella mia mente.'),
(4, 1, 'Frequentemente vengo così fortemente assorbito da una cosa che perdo di vista le altre cose.'),
(5, 1, 'Spesso noto piccoli suoni che gli altri non notano.'),
(6, 1, 'Di solito noto i numeri di targa delle macchine o simili sequenze di informazioni.'),
(7, 1, 'Frequentemente le altre persone mi dicono che quanto ho detto è scortese, anche quando io penso sia cortese.'),
(8, 1, 'Quando leggo una storia, riesco facilmente a immaginare come i personaggi potrebbero apparire.'),
(9, 1, 'Sono affascinato dalle date.'),
(10, 1, 'In un gruppo sociale, riesco facilmente a seguire le conversazioni di parecchie persone.'),
(11, 1, 'Trovo le situazioni sociali semplici.'),
(12, 1, 'Ho la tendenza a notare dettagli che gli altri non notano.'),
(13, 1, 'Preferisco andare in biblioteca piuttosto che ad una festa.'),
(14, 1, 'Trovo semplice inventare racconti.'),
(15, 1, 'Mi trovo attratto più fortemente dalle persone che dalle cose.'),
(16, 1, 'Tendo ad avere interessi molto forti e mi innervosisco se non posso perseguirli.'),
(17, 1, 'Mi piace chiacchierare.'),
(18, 1, 'Quando parlo, non è sempre facile per gli altri inserirsi nella conversazione.'),
(19, 1, 'Sono affascinato dai numeri.'),
(20, 1, 'Quando leggo una storia, trovo difficile capire le intenzioni dei personaggi.'),
(21, 1, 'Non amo particolarmente leggere romanzi.'),
(22, 1, 'Trovo difficile fare nuove amicizie.'),
(23, 1, 'Noto costantemente degli schemi nelle cose.'),
(24, 1, 'Preferisco andare al teatro piuttosto che al museo.'),
(25, 1, 'Non mi infastidisco se le mie routine quotidiane vengono disturbate.'),
(26, 1, 'Mi capita frequentemente di non sapere come continuare una conversazione.'),
(27, 1, 'Trovo semplice “leggere tra le righe” quando qualcuno mi parla.'),
(28, 1, 'Di solito mi concentro di più sull’intera figura che su piccolidettagli.'),
(29, 1, 'Non sono molto bravo a ricordare i numeri telefonici.'),
(30, 1, 'Di solito non noto piccoli cambiamenti in una situazione, o nell’aspetto di una persona.'),
(31, 1, 'So distinguere se chi mi ascolta si sta annoiando.'),
(32, 1, 'Trovo semplice fare più di una cosa contemporaneamente.'),
(33, 1, 'Quando parlo al telefono, non sono sicuro quando è il mio turno di parlare.'),
(34, 1, 'Amo fare le cose spontaneamente.'),
(35, 1, 'Sono spesso l’ultimo a capire il punto di una battuta.'),
(36, 1, 'Trovo semplice capire cosa una persona sta pensando o provando, semplicemente guardandola in faccia.'),
(37, 1, 'Se c’è un’interruzione, io posso ritornare a ciò che stavo facendo molto velocemente.'),
(38, 1, 'Sono bravo nella conversazione sociale.'),
(39, 1, 'Le persone spesso mi dicono che persevero sempre sulla stessa cosa.'),
(40, 1, 'Quando ero piccolo, mi piaceva fare giochi di finzione con altri bambini.'),
(41, 1, 'Mi piace raccogliere informazioni su categorie di cose (es. tipi di macchine, tipi di uccelli, tipi di treni, tipi di piante, etc.)'),
(42, 1, 'Trovo difficile immaginarmi nei panni di qualcun’altro.'),
(43, 1, 'Mi piace pianificare attentamente ogni attività a cui partecipo.'),
(44, 1, 'Mi piacciono gli eventi sociali.'),
(45, 1, 'Trovo difficile capire le intenzioni delle persone.'),
(46, 1, 'Le situazioni nuove mi rendono ansioso.'),
(47, 1, 'Mi piace incontrare persone nuove.'),
(48, 1, 'Sono un buon diplomatico.'),
(49, 1, 'Non sono molto bravo a ricordare la data di nascita delle persone.'),
(50, 1, 'Trovo semplice fare giochi di finzione con i bambini.');

-- --------------------------------------------------------

--
-- Struttura della tabella `attint_a`
--
-- Creazione: Set 01, 2016 alle 17:57
--

CREATE TABLE IF NOT EXISTS `attint_a` (
  `COIDNum` int(11) NOT NULL DEFAULT '0',
  `QNum` tinyint(4) NOT NULL DEFAULT '0',
  `Series` tinyint(4) NOT NULL DEFAULT '0',
  `Answer` char(1) DEFAULT NULL,
  PRIMARY KEY (`COIDNum`,`QNum`,`Series`),
  KEY `QNum` (`QNum`,`Series`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- RELATIONS FOR TABLE `attint_a`:
--   `COIDNum`
--       `attint_c` -> `idnumber`
--

--
-- Dump dei dati per la tabella `attint_a`
--


-- --------------------------------------------------------

--
-- Struttura della tabella `attint_ac`
--
-- Creazione: Set 01, 2016 alle 17:57
--

CREATE TABLE IF NOT EXISTS `attint_ac` (
  `QNum` tinyint(4) NOT NULL DEFAULT '0',
  `Series` tinyint(4) NOT NULL DEFAULT '0',
  `AType` tinyint(4) NOT NULL DEFAULT '0',
  `A` varchar(255) DEFAULT NULL,
  `B` varchar(255) DEFAULT NULL,
  `C` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`QNum`,`Series`,`AType`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dump dei dati per la tabella `attint_ac`
--

INSERT INTO `attint_ac` (`QNum`, `Series`, `AType`, `A`, `B`, `C`) VALUES
(1, 1, 2, 'img1_1_4', 'img1_1_5', 'img1_1_6'),
(1, 2, 2, 'img2_1_4', 'img2_1_5', 'img2_1_6'),
(1, 3, 2, 'img3_1_4', 'img3_1_5', 'img3_1_6'),
(1, 4, 2, 'img4_1_4', 'img4_1_5', 'img4_1_6'),
(1, 5, 2, 'img5_1_4', 'img5_1_5', 'img5_1_6'),
(2, 1, 2, 'img1_2_4', 'img1_2_5', 'img1_2_6'),
(2, 2, 2, 'img2_2_4', 'img2_2_5', 'img2_2_6'),
(2, 3, 2, 'img3_2_4', 'img3_2_5', 'img3_2_6'),
(2, 4, 2, 'img4_2_4', 'img4_2_5', 'img4_2_6'),
(2, 5, 2, 'img5_2_4', 'img5_2_5', 'img5_2_6'),
(3, 1, 2, 'img1_3_4', 'img1_3_5', 'img1_3_6'),
(3, 2, 2, 'img2_3_4', 'img2_3_5', 'img2_3_6'),
(3, 3, 2, 'img3_3_4', 'img3_3_5', 'img3_3_6'),
(3, 4, 2, 'img4_3_4', 'img4_3_5', 'img4_3_6'),
(3, 5, 2, 'img5_3_4', 'img5_3_5', 'img5_3_6'),
(4, 1, 2, 'img1_4_4', 'img1_4_5', 'img1_4_6'),
(4, 2, 2, 'img2_4_4', 'img2_4_5', 'img2_4_6'),
(4, 3, 2, 'img3_4_4', 'img3_4_5', 'img3_4_6'),
(4, 4, 2, 'img4_4_4', 'img4_4_5', 'img4_4_6'),
(4, 5, 2, 'img5_4_4', 'img5_4_5', 'img5_4_6'),
(5, 1, 2, 'img1_5_4', 'img1_5_5', 'img1_5_6'),
(5, 2, 2, 'img2_5_4', 'img2_5_5', 'img2_5_6'),
(5, 3, 2, 'img3_5_4', 'img3_5_5', 'img3_5_6'),
(5, 4, 2, 'img4_5_4', 'img4_5_5', 'img4_5_6'),
(5, 5, 2, 'img5_5_4', 'img5_5_5', 'img5_5_6'),
(6, 1, 2, 'img1_6_4', 'img1_6_5', 'img1_6_6'),
(6, 2, 2, 'img2_6_4', 'img2_6_5', 'img2_6_6'),
(6, 3, 2, 'img3_6_4', 'img3_6_5', 'img3_6_6'),
(6, 4, 2, 'img4_6_4', 'img4_6_5', 'img4_6_6'),
(6, 5, 2, 'img5_6_4', 'img5_6_5', 'img5_6_6'),
(7, 1, 2, 'img1_7_4', 'img1_7_5', 'img1_7_6'),
(7, 2, 2, 'img2_7_4', 'img2_7_5', 'img2_7_6'),
(7, 3, 2, 'img3_7_4', 'img3_7_5', 'img3_7_6'),
(7, 4, 2, 'img4_7_4', 'img4_7_5', 'img4_7_6'),
(7, 5, 2, 'img5_7_4', 'img5_7_5', 'img5_7_6'),
(8, 1, 2, 'img1_8_4', 'img1_8_5', 'img1_8_6'),
(8, 2, 2, 'img2_8_4', 'img2_8_5', 'img2_8_6'),
(8, 3, 2, 'img3_8_4', 'img3_8_5', 'img3_8_6'),
(8, 4, 2, 'img4_8_4', 'img4_8_5', 'img4_8_6'),
(8, 5, 2, 'img5_8_4', 'img5_8_5', 'img5_8_6'),
(9, 1, 2, 'img1_9_4', 'img1_9_5', 'img1_9_6'),
(9, 2, 2, 'img2_9_4', 'img2_9_5', 'img2_9_6'),
(9, 3, 2, 'img3_9_4', 'img3_9_5', 'img3_9_6'),
(9, 4, 2, 'img4_9_4', 'img4_9_5', 'img4_9_6'),
(9, 5, 2, 'img5_9_4', 'img5_9_5', 'img5_9_6'),
(10, 1, 2, 'img1_10_4', 'img1_10_5', 'img1_10_6'),
(10, 2, 2, 'img2_10_4', 'img2_10_5', 'img2_10_6'),
(10, 3, 2, 'img3_10_4', 'img3_10_5', 'img3_10_6'),
(10, 4, 2, 'img4_10_4', 'img4_10_5', 'img4_10_6'),
(10, 5, 2, 'img5_10_4', 'img5_10_5', 'img5_10_6'),
(11, 1, 2, 'img1_11_4', 'img1_11_5', 'img1_11_6'),
(11, 2, 2, 'img2_11_4', 'img2_11_5', 'img2_11_6'),
(11, 3, 2, 'img3_11_4', 'img3_11_5', 'img3_11_6'),
(11, 4, 2, 'img4_11_4', 'img4_11_5', 'img4_11_6'),
(11, 5, 2, 'img5_11_4', 'img5_11_5', 'img5_11_6'),
(12, 1, 2, 'img1_12_4', 'img1_12_5', 'img1_12_6'),
(12, 2, 2, 'img2_12_4', 'img2_12_5', 'img2_12_6'),
(12, 3, 2, 'img3_12_4', 'img3_12_5', 'img3_12_6'),
(12, 4, 2, 'img4_12_4', 'img4_12_5', 'img4_12_6'),
(12, 5, 2, 'img5_12_4', 'img5_12_5', 'img5_12_6'),
(13, 1, 2, 'img1_13_4', 'img1_13_5', 'img1_13_6'),
(13, 4, 2, 'img4_13_4', 'img4_13_5', 'img4_13_6'),
(13, 5, 2, 'img5_13_4', 'img5_13_5', 'img5_13_6'),
(14, 1, 2, 'img1_14_4', 'img1_14_5', 'img1_14_6'),
(14, 4, 2, 'img4_14_4', 'img4_14_5', 'img4_14_6'),
(14, 5, 2, 'img5_14_4', 'img5_14_5', 'img5_14_6');

-- --------------------------------------------------------

--
-- Struttura della tabella `attint_c`
--
-- Creazione: Set 01, 2016 alle 17:57
--

CREATE TABLE IF NOT EXISTS `attint_c` (
  `idnumber` int(11) NOT NULL AUTO_INCREMENT,
  `patient` int(10) unsigned DEFAULT NULL,
  `test` tinyint(3) unsigned DEFAULT NULL,
  `QType` tinyint(4) NOT NULL,
  `AType` tinyint(4) NOT NULL,
  `Series` tinyint(4) DEFAULT NULL,
  `Total` tinyint(4) DEFAULT NULL,
  `Day` date DEFAULT NULL,
  PRIMARY KEY (`idnumber`),
  KEY `patient` (`patient`),
  KEY `test` (`test`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

--
-- RELATIONS FOR TABLE `attint_c`:
--   `patient`
--       `user` -> `id`
--   `test`
--       `test` -> `code`
--

--
-- Dump dei dati per la tabella `attint_c`
--

-- --------------------------------------------------------

--
-- Struttura della tabella `attint_ca`
--
-- Creazione: Set 01, 2016 alle 17:57
--

CREATE TABLE IF NOT EXISTS `attint_ca` (
  `Series` tinyint(4) NOT NULL DEFAULT '0',
  `QNum` tinyint(4) NOT NULL DEFAULT '0',
  `Answer` char(1) DEFAULT NULL,
  PRIMARY KEY (`QNum`,`Series`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dump dei dati per la tabella `attint_ca`
--

INSERT INTO `attint_ca` (`Series`, `QNum`, `Answer`) VALUES
(1, 1, 'C'),
(2, 1, 'B'),
(3, 1, 'C'),
(4, 1, 'B'),
(5, 1, 'B'),
(1, 2, 'A'),
(2, 2, 'B'),
(3, 2, 'A'),
(4, 2, 'B'),
(5, 2, 'C'),
(1, 3, 'C'),
(2, 3, 'C'),
(3, 3, 'B'),
(4, 3, 'C'),
(5, 3, 'B'),
(1, 4, 'A'),
(2, 4, 'A'),
(3, 4, 'C'),
(4, 4, 'A'),
(5, 4, 'A'),
(1, 5, 'B'),
(2, 5, 'C'),
(3, 5, 'A'),
(4, 5, 'C'),
(5, 5, 'B'),
(1, 6, 'A'),
(2, 6, 'A'),
(3, 6, 'C'),
(4, 6, 'A'),
(5, 6, 'C'),
(1, 7, 'C'),
(2, 7, 'A'),
(3, 7, 'B'),
(4, 7, 'A'),
(5, 7, 'B'),
(1, 8, 'B'),
(2, 8, 'C'),
(3, 8, 'A'),
(4, 8, 'C'),
(5, 8, 'C'),
(1, 9, 'B'),
(2, 9, 'B'),
(3, 9, 'B'),
(4, 9, 'B'),
(5, 9, 'A'),
(1, 10, 'A'),
(2, 10, 'A'),
(3, 10, 'A'),
(4, 10, 'A'),
(5, 10, 'C'),
(1, 11, 'B'),
(2, 11, 'B'),
(3, 11, 'B'),
(4, 11, 'A'),
(5, 11, 'A'),
(1, 12, 'B'),
(2, 12, 'A'),
(3, 12, 'C'),
(4, 12, 'A'),
(5, 12, 'A'),
(1, 13, 'A'),
(4, 13, 'B'),
(5, 13, 'C'),
(1, 14, 'C'),
(4, 14, 'C'),
(5, 14, 'B');

-- --------------------------------------------------------

--
-- Struttura della tabella `attint_qc`
--
-- Creazione: Set 01, 2016 alle 17:57
--

CREATE TABLE IF NOT EXISTS `attint_qc` (
  `QNum` tinyint(4) NOT NULL DEFAULT '0',
  `Series` tinyint(4) NOT NULL DEFAULT '0',
  `QType` tinyint(4) NOT NULL DEFAULT '0',
  `A` varchar(255) DEFAULT NULL,
  `B` varchar(255) DEFAULT NULL,
  `C` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`QNum`,`Series`,`QType`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dump dei dati per la tabella `attint_qc`
--

INSERT INTO `attint_qc` (`QNum`, `Series`, `QType`, `A`, `B`, `C`) VALUES
(1, 1, 2, 'img1_1_1', 'img1_1_2', 'img1_1_3'),
(1, 2, 2, 'img2_1_1', 'img2_1_2', 'img2_1_3'),
(1, 3, 2, 'img3_1_1', 'img3_1_2', 'img3_1_3'),
(1, 4, 2, 'img4_1_1', 'img4_1_2', 'img4_1_3'),
(1, 5, 2, 'img5_1_1', 'img5_1_2', 'img5_1_3'),
(2, 1, 2, 'img1_2_1', 'img1_2_2', 'img1_2_3'),
(2, 2, 2, 'img2_2_1', 'img2_2_2', 'img2_2_3'),
(2, 3, 2, 'img3_2_1', 'img3_2_2', 'img3_2_3'),
(2, 4, 2, 'img4_2_1', 'img4_2_2', 'img4_2_3'),
(2, 5, 2, 'img5_2_1', 'img5_2_2', 'img5_2_3'),
(3, 1, 2, 'img1_3_1', 'img1_3_2', 'img1_3_3'),
(3, 2, 2, 'img2_3_1', 'img2_3_2', 'img2_3_3'),
(3, 3, 2, 'img3_3_1', 'img3_3_2', 'img3_3_3'),
(3, 4, 2, 'img4_3_1', 'img4_3_2', 'img4_3_3'),
(3, 5, 2, 'img5_3_1', 'img5_3_2', 'img5_3_3'),
(4, 1, 2, 'img1_4_1', 'img1_4_2', 'img1_4_3'),
(4, 2, 2, 'img2_4_1', 'img2_4_2', 'img2_4_3'),
(4, 3, 2, 'img3_4_1', 'img3_4_2', 'img3_4_3'),
(4, 4, 2, 'img4_4_1', 'img4_4_2', 'img4_4_3'),
(4, 5, 2, 'img5_4_1', 'img5_4_2', 'img5_4_3'),
(5, 1, 2, 'img1_5_1', 'img1_5_2', 'img1_5_3'),
(5, 2, 2, 'img2_5_1', 'img2_5_2', 'img2_5_3'),
(5, 3, 2, 'img3_5_1', 'img3_5_2', 'img3_5_3'),
(5, 4, 2, 'img4_5_1', 'img4_5_2', 'img4_5_3'),
(5, 5, 2, 'img5_5_1', 'img5_5_2', 'img5_5_3'),
(6, 1, 2, 'img1_6_1', 'img1_6_2', 'img1_6_3'),
(6, 2, 2, 'img2_6_1', 'img2_6_2', 'img2_6_3'),
(6, 3, 2, 'img3_6_1', 'img3_6_2', 'img3_6_3'),
(6, 4, 2, 'img4_6_1', 'img4_6_2', 'img4_6_3'),
(6, 5, 2, 'img5_6_1', 'img5_6_2', 'img5_6_3'),
(7, 1, 2, 'img1_7_1', 'img1_7_2', 'img1_7_3'),
(7, 2, 2, 'img2_7_1', 'img2_7_2', 'img2_7_3'),
(7, 3, 2, 'img3_7_1', 'img3_7_2', 'img3_7_3'),
(7, 4, 2, 'img4_7_1', 'img4_7_2', 'img4_7_3'),
(7, 5, 2, 'img5_7_1', 'img5_7_2', 'img5_7_3'),
(8, 1, 2, 'img1_8_1', 'img1_8_2', 'img1_8_3'),
(8, 2, 2, 'img2_8_1', 'img2_8_2', 'img2_8_3'),
(8, 3, 2, 'img3_8_1', 'img3_8_2', 'img3_8_3'),
(8, 4, 2, 'img4_8_1', 'img4_8_2', 'img4_8_3'),
(8, 5, 2, 'img5_8_1', 'img5_8_2', 'img5_8_3'),
(9, 1, 2, 'img1_9_1', 'img1_9_2', 'img1_9_3'),
(9, 2, 2, 'img2_9_1', 'img2_9_2', 'img2_9_3'),
(9, 3, 2, 'img3_9_1', 'img3_9_2', 'img3_9_3'),
(9, 4, 2, 'img4_9_1', 'img4_9_2', 'img4_9_3'),
(9, 5, 2, 'img5_9_1', 'img5_9_2', 'img5_9_3'),
(10, 1, 2, 'img1_10_1', 'img1_10_2', 'img1_10_3'),
(10, 2, 2, 'img2_10_1', 'img2_10_2', 'img2_10_3'),
(10, 3, 2, 'img3_10_1', 'img3_10_2', 'img3_10_3'),
(10, 4, 2, 'img4_10_1', 'img4_10_2', 'img4_10_3'),
(10, 5, 2, 'img5_10_1', 'img5_10_2', 'img5_10_3'),
(11, 1, 2, 'img1_11_1', 'img1_11_2', 'img1_11_3'),
(11, 2, 2, 'img2_11_1', 'img2_11_2', 'img2_11_3'),
(11, 3, 2, 'img3_11_1', 'img3_11_2', 'img3_11_3'),
(11, 4, 2, 'img4_11_1', 'img4_11_2', 'img4_11_3'),
(11, 5, 2, 'img5_11_1', 'img5_11_2', 'img5_11_3'),
(12, 1, 2, 'img1_12_1', 'img1_12_2', 'img1_12_3'),
(12, 2, 2, 'img2_12_1', 'img2_12_2', 'img2_12_3'),
(12, 3, 2, 'img3_12_1', 'img3_12_2', 'img3_12_3'),
(12, 4, 2, 'img4_12_1', 'img4_12_2', 'img4_12_3'),
(12, 5, 2, 'img5_12_1', 'img5_12_2', 'img5_12_3'),
(13, 1, 2, 'img1_13_1', 'img1_13_2', 'img1_13_3'),
(13, 4, 2, 'img4_13_1', 'img4_13_2', 'img4_13_3'),
(13, 5, 2, 'img5_13_1', 'img5_13_2', 'img5_13_3'),
(14, 1, 2, 'img1_14_1', 'img1_14_2', 'img1_14_3'),
(14, 4, 2, 'img4_14_1', 'img4_14_2', 'img4_14_3'),
(14, 5, 2, 'img5_14_1', 'img5_14_2', 'img5_14_3');

-- --------------------------------------------------------

--
-- Struttura della tabella `bes_a`
--
-- Creazione: Set 01, 2016 alle 17:57
--

CREATE TABLE IF NOT EXISTS `bes_a` (
  `COIDNum` int(11) NOT NULL DEFAULT '0',
  `QNum` tinyint(4) NOT NULL DEFAULT '0',
  `Answer` char(1) DEFAULT NULL,
  PRIMARY KEY (`COIDNum`,`QNum`),
  KEY `QNum` (`QNum`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- RELATIONS FOR TABLE `bes_a`:
--   `COIDNum`
--       `bes_c` -> `idnumber`
--   `QNum`
--       `bes_ca` -> `QNum`
--

--
-- Dump dei dati per la tabella `bes_a`
--


-- --------------------------------------------------------

--
-- Struttura della tabella `bes_c`
--
-- Creazione: Set 01, 2016 alle 17:57
--

CREATE TABLE IF NOT EXISTS `bes_c` (
  `idnumber` int(11) NOT NULL AUTO_INCREMENT,
  `patient` int(10) unsigned DEFAULT NULL,
  `test` tinyint(3) unsigned DEFAULT NULL,
  `QType` tinyint(4) NOT NULL,
  `AType` tinyint(4) NOT NULL,
  `CE` tinyint(4) DEFAULT NULL,
  `AE` tinyint(4) DEFAULT NULL,
  `Day` date DEFAULT NULL,
  PRIMARY KEY (`idnumber`),
  KEY `patient` (`patient`),
  KEY `test` (`test`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=5 ;

--
-- RELATIONS FOR TABLE `bes_c`:
--   `patient`
--       `user` -> `id`
--   `test`
--       `test` -> `code`
--

--
-- Dump dei dati per la tabella `bes_c`
--


-- --------------------------------------------------------

--
-- Struttura della tabella `bes_ca`
--
-- Creazione: Set 01, 2016 alle 17:57
--

CREATE TABLE IF NOT EXISTS `bes_ca` (
  `QNum` tinyint(4) NOT NULL DEFAULT '0',
  `A` tinyint(4) DEFAULT NULL,
  `B` tinyint(4) DEFAULT NULL,
  `C` tinyint(4) DEFAULT NULL,
  `D` tinyint(4) DEFAULT NULL,
  `E` tinyint(4) DEFAULT NULL,
  `Type` tinyint(4) DEFAULT NULL,
  PRIMARY KEY (`QNum`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dump dei dati per la tabella `bes_ca`
--

INSERT INTO `bes_ca` (`QNum`, `A`, `B`, `C`, `D`, `E`, `Type`) VALUES
(1, 5, 4, 3, 2, 1, 2),
(2, 1, 2, 3, 4, 5, 2),
(3, 1, 2, 3, 4, 5, 1),
(4, 1, 2, 3, 4, 5, 2),
(5, 1, 2, 3, 4, 5, 2),
(6, 5, 4, 3, 2, 1, 1),
(7, 5, 4, 3, 2, 1, 2),
(8, 5, 4, 3, 2, 1, 2),
(9, 1, 2, 3, 4, 5, 1),
(10, 1, 2, 3, 4, 5, 1),
(11, 1, 2, 3, 4, 5, 2),
(12, 1, 2, 3, 4, 5, 1),
(13, 5, 4, 3, 2, 1, 2),
(14, 1, 2, 3, 4, 5, 1),
(15, 1, 2, 3, 4, 5, 2),
(16, 1, 2, 3, 4, 5, 1),
(17, 1, 2, 3, 4, 5, 2),
(18, 5, 4, 3, 2, 1, 2),
(19, 5, 4, 3, 2, 1, 1),
(20, 5, 4, 3, 2, 1, 1);

-- --------------------------------------------------------

--
-- Struttura della tabella `bes_qc`
--
-- Creazione: Set 01, 2016 alle 17:57
--

CREATE TABLE IF NOT EXISTS `bes_qc` (
  `QNum` tinyint(4) NOT NULL DEFAULT '0',
  `QType` tinyint(4) NOT NULL DEFAULT '0',
  `Question` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`QNum`,`QType`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- RELATIONS FOR TABLE `bes_qc`:
--   `QNum`
--       `bes_ca` -> `QNum`
--

--
-- Dump dei dati per la tabella `bes_qc`
--

INSERT INTO `bes_qc` (`QNum`, `QType`, `Question`) VALUES
(1, 1, 'Le emozioni di un mio amico non mi toccano molto'),
(2, 1, 'Dopo essere stato con un amico che è triste per qualcosa, solitamente mi sento triste anch’io'),
(3, 1, 'Riesco a capire la felicità di un mio amico quando lui/lei riesce bene in qualche cosa'),
(4, 1, 'Mi spavento quando guardo i personaggi di un film realmente pauroso'),
(5, 1, 'Mi lascio facilmente coinvolgere dalle emozioni delle altre persone'),
(6, 1, 'Trovo difficile capire quando i miei amici sono spaventati'),
(7, 1, 'Non divento triste quando vedo altre persone piangere'),
(8, 1, 'Le emozioni delle altre persone non mi toccano affatto'),
(9, 1, 'Quando qualcuno si sente “giù” riesco solitamente a capire come si sente'),
(10, 1, 'Di solito riesco a capire quando i miei amici sono spaventati'),
(11, 1, 'Divento spesso triste quando guardo cose tristi in televisione o nei film'),
(12, 1, 'Riesco spesso a capire come si sentono le persone anche prima che me lo dicano'),
(13, 1, 'Vedere una persona arrabbiata non ha effetto sulle mie emozioni'),
(14, 1, 'Di solito riesco a capire quando le persone sono allegre'),
(15, 1, 'Tendo a sentirmi spaventato quando sono con amici che hanno paura'),
(16, 1, 'Di solito mi accorgo subito quando un amico è arrabbiato'),
(17, 1, 'Spesso sono coinvolto dalle emozioni di un mio amico'),
(18, 1, 'L’infelicità di un mio amico non mi fa provare nulla'),
(19, 1, 'Di solito non sono consapevole delle emozioni di un mio amico'),
(20, 1, 'Ho difficoltà a capire quando i miei amici sono felici');

-- --------------------------------------------------------

--
-- Struttura della tabella `clinicalg`
--
-- Creazione: Set 01, 2016 alle 17:57
--

CREATE TABLE IF NOT EXISTS `clinicalg` (
  `code` tinyint(4) NOT NULL AUTO_INCREMENT,
  `name` char(50) DEFAULT NULL,
  PRIMARY KEY (`code`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=4 ;

--
-- Dump dei dati per la tabella `clinicalg`
--

INSERT INTO `clinicalg` (`code`, `name`) VALUES
(-1, 'nessun gruppo');

-- --------------------------------------------------------

--
-- Struttura della tabella `emotatt_a`
--
-- Creazione: Set 01, 2016 alle 17:57
--

CREATE TABLE IF NOT EXISTS `emotatt_a` (
  `COIDNum` int(11) NOT NULL DEFAULT '0',
  `QNum` tinyint(4) NOT NULL DEFAULT '0',
  `Answer` varchar(20) DEFAULT NULL,
  `ans_code` tinyint(4) DEFAULT NULL,
  `docid` tinyint(4) DEFAULT NULL,
  `Verified` tinyint(1) DEFAULT NULL,
  `Day` date DEFAULT NULL,
  PRIMARY KEY (`COIDNum`,`QNum`),
  KEY `QNum` (`QNum`),
  KEY `docid` (`docid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- RELATIONS FOR TABLE `emotatt_a`:
--   `COIDNum`
--       `emotatt_c` -> `idnumber`
--   `QNum`
--       `emotatt_ca` -> `QNum`
--   `docid`
--       `therapist` -> `id`
--

--
-- Dump dei dati per la tabella `emotatt_a`
--


-- --------------------------------------------------------

--
-- Struttura della tabella `emotatt_c`
--
-- Creazione: Set 01, 2016 alle 17:57
--

CREATE TABLE IF NOT EXISTS `emotatt_c` (
  `idnumber` int(11) NOT NULL AUTO_INCREMENT,
  `patient` int(10) unsigned DEFAULT NULL,
  `test` tinyint(3) unsigned DEFAULT NULL,
  `QType` tinyint(4) NOT NULL,
  `AType` tinyint(4) NOT NULL,
  `E1` tinyint(4) DEFAULT NULL,
  `E2` tinyint(4) DEFAULT NULL,
  `E3` tinyint(4) DEFAULT NULL,
  `E4` tinyint(4) DEFAULT NULL,
  `E5` tinyint(4) DEFAULT NULL,
  `E6` tinyint(4) DEFAULT NULL,
  `E7` tinyint(4) DEFAULT NULL,
  `Day` date DEFAULT NULL,
  `completed` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`idnumber`),
  KEY `patient` (`patient`),
  KEY `test` (`test`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

--
-- RELATIONS FOR TABLE `emotatt_c`:
--   `patient`
--       `user` -> `id`
--   `test`
--       `test` -> `code`
--

--
-- Dump dei dati per la tabella `emotatt_c`
--

-- --------------------------------------------------------

--
-- Struttura della tabella `emotatt_ca`
--
-- Creazione: Set 01, 2016 alle 17:57
--

CREATE TABLE IF NOT EXISTS `emotatt_ca` (
  `QNum` tinyint(4) NOT NULL DEFAULT '0',
  `Answer` tinyint(4) DEFAULT NULL,
  PRIMARY KEY (`QNum`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dump dei dati per la tabella `emotatt_ca`
--

INSERT INTO `emotatt_ca` (`QNum`, `Answer`) VALUES
(1, 1),
(2, 2),
(3, 3),
(4, 1),
(5, 4),
(6, 1),
(7, 2),
(8, 1),
(9, 1),
(10, 5),
(11, 2),
(12, 6),
(13, 6),
(14, 3),
(15, 7),
(16, 4),
(17, 3),
(18, 4),
(19, 1),
(20, 4),
(21, 2),
(22, 4),
(23, 7),
(24, 3),
(25, 4),
(26, 2),
(27, 5),
(28, 3),
(29, 2),
(30, 4),
(31, 1),
(32, 4),
(33, 6),
(34, 5),
(35, 6),
(36, 1),
(37, 2),
(38, 4),
(39, 7),
(40, 6),
(41, 1),
(42, 4),
(43, 2),
(44, 3),
(45, 6),
(46, 2),
(47, 3),
(48, 3),
(49, 6),
(50, 3),
(51, 2),
(52, 3),
(53, 6),
(54, 3),
(55, 6),
(56, 6),
(57, 1),
(58, 3);

-- --------------------------------------------------------

--
-- Struttura della tabella `emotatt_qc`
--
-- Creazione: Set 01, 2016 alle 17:57
--

CREATE TABLE IF NOT EXISTS `emotatt_qc` (
  `QNum` tinyint(4) NOT NULL DEFAULT '0',
  `QType` tinyint(4) NOT NULL DEFAULT '0',
  `Question` text,
  PRIMARY KEY (`QNum`,`QType`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- RELATIONS FOR TABLE `emotatt_qc`:
--   `QNum`
--       `emotatt_ca` -> `QNum`
--

--
-- Dump dei dati per la tabella `emotatt_qc`
--

INSERT INTO `emotatt_qc` (`QNum`, `QType`, `Question`) VALUES
(1, 1, 'I quadri di Simone sono arrivati ultimi ad un concorso. Come si sentirà Simone in questa situazione?'),
(2, 1, 'Elisabetta sta guidando lungo una strada quando una donna le compare improvvisamente davanti e lei la travolge in pieno. Cosa proverà Elisabetta in questa situazione?'),
(3, 1, 'Maria deve fare un discorso al lavoro. E’ in piedi nella stanza di fronte a tutti. Non ricorda cosa deve dire. Tutti la fissano. Come si sentirà Maria in questa situazione?'),
(4, 1, 'Luca si è appena rivelato alla ragazza che desiderava da mesi. Lei gli dà uno schiaffo in faccia. Come si sentirà Luca in questa situazione?'),
(5, 1, 'Enrico ha appena scoperto che avrà un aumento della paga extra. Come si sentirà Enrico in questa situazione?'),
(6, 1, 'A Rosanna è stato appena detto dal suo fidanzato che lui non vuole più uscire con lei. Come si sentirà Rosanna in questa situazione?'),
(7, 1, 'Carlo è sdraiato in mezzo alla foresta. Un ragno velenoso cade sul suo petto. Cosa proverà Carlo in questa situazione?'),
(8, 1, 'Giuseppe ha appena saputo che il suo stipendio si ridurrà il prossimo mese. Come si sentirà Giuseppe in questa situazione?'),
(9, 1, 'Iris ha appena saputo che ha un cancro maligno che potrebbe ucciderla in meno di un anno. Cosa proverà Iris in questa situazione?'),
(10, 1, 'Elena ordina un panino in un ristorante. Mangia il primo boccone e guarda il resto. C’è un verme morto nel panino. Cosa proverà Elena in questa situazione?'),
(11, 1, 'Paolo sente il suono che avvisa l’imminente caduta di una bomba atomica. Cosa proverà Paolo in questa situazione?'),
(12, 1, 'Un uomo cammina verso Andrea e lo chiama idiota. Come si sentirà Andrea in questa situazione?'),
(13, 1, 'A Tania venne detto che se avesse truccato il viso ad una donna le sarebbero state pagate 20.000 lire. Ma dopo che ebbe completato il lavoro le vennero date 10.000 lire. Come si sentirà Tania in questa situazione?'),
(14, 1, 'Fabio lancia un libro ad un suo amico. Il suo amico non lo prende e il libro finisce in faccia ad un altro uomo. Cosa proverà Fabio in questa situazione?'),
(15, 1, 'Roberto si sintonizza sulle radio notizie e sente che i suoi vicini hanno appena vinto 200 milioni alla lotteria. Il suo vicino sta raccontando al giornalista di tutti i viaggi che farà e di tutte le cose che comprerà. Cosa proverà Roberto in questa situazione?'),
(16, 1, 'Romina non vede suo marito da settimane. Poi lo vede nella strada e gli corre incontro. Come si sentirà Romina in questa situazione?'),
(17, 1, 'Daniele sta camminando in una strada affollata quando inciampa e sbatte con la faccia. Tutti nella strada si girano, lo fissano e cominciano a ridere. Come si sentirà Daniele in questa situazione?'),
(18, 1, 'Tania sta ascoltando i numeri della lotteria. Ha già segnato cinque numeri ed è prossima a vincere 10 milioni. Come si sentirà Tania in questa situazione?'),
(19, 1, 'Ogni settimana al lavoro di Giovanni sottraggono un premio a chiunque stia facendo peggio. Questa settimana è toccato a Giovanni. Come si sentirà Giovanni in questa situazione?'),
(20, 1, 'Katia ha ricevuto i risultati dei suoi esami: sono andati molto bene. Come si sentirà Katia in questa situazione?'),
(21, 1, 'Melania si trova in un vicolo. Un uomo sta andando verso di lei con un coltello. Cosa proverà Melania in questa situazione?'),
(22, 1, 'Elisabetta ha da poco un nuovo fidanzato. Come si sentirà Elisabetta in questa situazione?'),
(23, 1, 'La macchina di Riccardo, che ha 20 anni, si è appena rotta lungo la strada. Mentre sta seduto lì, qualcuno passa con una nuova Jaguar. Riccardo guarda la macchina che passa. Come si sentirà Riccardo in questa situazione?'),
(24, 1, 'Giulia sta in piedi in centro alla sala nuziale per tenere un discorso. Ma mentre è in piedi non le viene niente da dire. Tutti, nella sala, la fissano e qualcuno comincia a ridere. Come si sentirà Giulia in questa situazione?'),
(25, 1, 'Elisa vorrebbe un coniglietto per il suo compleanno, ma le è stato detto dai suoi genitori che le sarà donata una bicicletta, che però lei non vuole. Ma, quando scende per aprire il suo regalo, scopre che dentro c’è un coniglietto. Come si sentirà Elisa in questa situazione?'),
(26, 1, 'Silvia si sveglia e vede che c’è un ragno velenoso nel suo letto. Cosa proverà Silvia in questa situazione?'),
(27, 1, 'Giacomo si è appena preparato una scodella di corn flakes e latte. Ne mangia una cucchiaiata e si rende conto che il latte è andato a male. Cosa proverà Giacomo in questa situazione?'),
(28, 1, 'Sonia sta andando in bicicletta lungo la strada quando perde il controllo e finisce addosso ad un lampione. Alcune persone dall’altra parte della strada si fermano e la fissano; qualcuno la deride. Come si sentirà Sonia in questa situazione?'),
(29, 1, 'Un uomo cammina verso Massimo e minaccia di dargli un pugno. Come si sentirà Massimo in questa situazione?'),
(30, 1, 'William è appena andato al primo appuntamento con una ragazza di cui è interessato da quattro anni. Alla fine dell’appuntamento si baciano. Come si sentirà William in questa situazione?'),
(31, 1, 'Margherita ha appena scoperto che sua madre è morta. Come si sentirà Margherita in questa situazione?'),
(32, 1, 'A Giovanna è stata appena data una promozione. Come si sentirà Giovanna in questa situazione?'),
(33, 1, 'Sara ha parcheggiato all’angolo della strada quando una donna giunge e colpisce la sua auto con un martello, danneggiando parecchio il cofano. Come si sentirà Sara in questa situazione?'),
(34, 1, 'Rebecca sta tirando fuori dal frigo il pranzo della domenica quando si accorge che stanno strisciando dei vermi. Cosa proverà Rebecca in questa situazione?'),
(35, 1, 'Arturo sta parlando con un uomo che non conosce molto bene. L’uomo sta mangiando un panino. Mentre stanno parlando l’uomo sputa un pezzo di cibo nella giacca di Arturo. Arturo afferra l’uomo e gli strappa a pezzi la maglietta. Come si sentirà Arturo in questa situazione?'),
(36, 1, 'A Nadia venne detto che il suo prossimo lavoro sarebbe stato a Roma, la sua città preferita. Invece sarà a Padova, città che non le piace. Come si sentirà Nadia in questa situazione?'),
(37, 1, 'Giampaolo sta campeggiando nella foresta in America. Un orso strappa una parte della sua tenda e viene verso di lui ringhiando. Cosa proverà Giampaolo in questa situazione?'),
(38, 1, 'Ad Andrea, disoccupato, è stato appena offerto un nuovo lavoro. Come si sentirà Andrea in questa situazione?'),
(39, 1, 'Simone si accorge che l’uomo accanto a lui possiede l’orologio d’oro che lui vuole comprarsi da tre anni. Simone sa che non potrà permettersi quel orologio per un altro po’ di anni. Cosa proverà Simone in questa situazione?'),
(40, 1, 'L’amica di Sonia le disse che se avesse badato lei ai suoi figli lunedì lei avrebbe badato i figli di Sonia giovedì. Sonia il lunedì badò i figli dell’amica, ma il giovedì l’amica le disse che non poteva badare i figli di Sonia perché aveva un altro appuntamento. Come si sarà sentita Sonia in questa situazione?'),
(41, 1, 'Mario è stato chiamato dal suo capo. Questo gli dice: ” Sei licenziato.” Come si sentirà Mario in questa situazione?'),
(42, 1, 'La moglie di Daniele ha appena partorito il loro terzo figlio. Come si sentirà la moglie di Daniele in questa situazione?'),
(43, 1, 'Fulvio si trova in banca nel mezzo di una rapina. Uno dei rapinatori gli punta la pistola in faccia. Come si sentirà Fulvio in questa situazione?'),
(44, 1, 'Giusy corre dentro al cinema e non vede la porta di vetro. Ci corre contro. Tutti nella fila si girano e la fissano e qualcuno la deride. Come si sentirà Giusy in questa situazione?'),
(45, 1, 'Katia ha appena scoperto che le sono stati rubati 10 milioni e non può fare niente per averli indietro. Come si sentirà Katia in questa situazione?'),
(46, 1, 'Mauro si è svegliato e sente un ladro che si muove in casa. Come si sentirà Mauro in questa situazione?'),
(47, 1, 'Edy si trova in un bar quando scivola in una macchia di grasso e cade. Tutti nel bar lo fissano. Come si sentirà Edy in questa situazione?'),
(48, 1, 'Enrico sta camminando lungo una via del paese. Purtroppo non vede un ciottolo. La bici sbanda e lui cade nell’erba. Una macchina si ferma e tutti lo vedono cadere. Cosa proverà Enrico in questa situazione?'),
(49, 1, 'Ogni volta che Nicoletta tenta di lavorare al computer non ne trova uno libero al lavoro. Cosa proverà Nicoletta in questa situazione?'),
(50, 1, 'Alessandro sta guardando dalla ringhiera di un ponte. Improvvisamente comincia a scivolare e finisce dritto dentro al fiumiciattolo. Mentre si arrampica fuori dall’acqua si accorge che c’è un pullman pieno di turisti che lo stanno fissando. Come si sentirà Alessandro in questa situazione?'),
(51, 1, 'Gianni viene portato in un safari. L’auto si è rotta e adesso un grande rinoceronte lo sta attaccando. Come si sentirà Gianni in questa situazione?'),
(52, 1, 'Emilia sta camminando mano nella mano con il suo amante lungo la strada. Suo marito, improvvisamente, compare dietro l’angolo e li sorprende insieme. Cosa proverà Emilia in questa situazione?'),
(53, 1, 'Simone scopre un ladro mentre ruba il suo portafoglio dalla borsa. Come si sentirà Simone in questa situazione?'),
(54, 1, 'Filippo si era iscritto ad una gara di pesca e voleva vincere. Così comprò un grosso pesce dal pescivendolo. Purtroppo uno dei giudici era il proprietario del negozio. Quest’uomo racconta a tutti cosa ha fatto Filippo. Cosa proverà Filippo in questa situazione?'),
(55, 1, 'Il capo di Priscilla le ha detto di fare il suo lavoro in un certo modo. Poi, alla fine della giornata, dopo che lei ha lavorato per ore, egli cambia idea vuole qualcosa di differente. Come si sentirà Priscilla in questa situazione?'),
(56, 1, 'Paolo ha fatto un contratto con un uomo. Se spedisce all’uomo la sua collezione di dischi, l’uomo gli dà un’auto. Paolo spedisce all’uomo la sua collezione di dischi. L’uomo però non gli dà l’auto come stabilito. Come si sentirà Paolo in questa situazione?'),
(57, 1, 'A Matteo venne detto che avrebbe avuto il suo gioco preferito per Natale. Invece gli venne regalata un’enciclopedia. Come si sarà sentito Matteo in questa situazione?'),
(58, 1, 'I genitori di Anna si aspettano da lei tutti voti ottimi nei suoi esami. Lei apre la lettera dei risultati davanti a loro. Tutti vedono che ha preso appena sufficiente in ogni esame. Come si sentirà Anna in questa situazione?');

-- --------------------------------------------------------

--
-- Struttura della tabella `emotion`
--
-- Creazione: Set 01, 2016 alle 17:57
--

CREATE TABLE IF NOT EXISTS `emotion` (
  `EmoCode` tinyint(4) NOT NULL DEFAULT '0',
  `Emot` varchar(50) NOT NULL DEFAULT ''
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dump dei dati per la tabella `emotion`
--

INSERT INTO `emotion` (`EmoCode`, `Emot`) VALUES
(1, 'addolorato'),
(1, 'deluso'),
(1, 'disperato'),
(1, 'triste'),
(1, 'tristezza'),
(2, 'impaurito'),
(2, 'paura'),
(2, 'pietrificato'),
(2, 'terrorizzato'),
(3, 'imbarazzato'),
(3, 'imbarazzo'),
(3, 'ridicolo'),
(3, 'umiliato'),
(4, 'estasiato'),
(4, 'felice'),
(4, 'felicità'),
(4, 'pieno di gioia'),
(4, 'sollevato'),
(5, 'disgustato'),
(5, 'disgusto'),
(5, 'fastidio'),
(5, 'ribrezzo'),
(6, 'arrabbiato'),
(6, 'infastidito'),
(6, 'irritato'),
(6, 'rabbia'),
(7, 'gelosia'),
(7, 'geloso'),
(7, 'invidia'),
(7, 'invidioso');

-- --------------------------------------------------------

--
-- Struttura della tabella `ext_user`
--
-- Creazione: Set 01, 2016 alle 17:57
--

CREATE TABLE IF NOT EXISTS `ext_user` (
  `id` int(10) unsigned NOT NULL DEFAULT '0',
  `email` varchar(50) DEFAULT NULL,
  `faculty` varchar(50) DEFAULT NULL,
  `syear` varchar(20) DEFAULT NULL,
  `idnumber` varchar(10) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- RELATIONS FOR TABLE `ext_user`:
--   `id`
--       `user` -> `id`
--

--
-- Dump dei dati per la tabella `ext_user`
--


-- --------------------------------------------------------

--
-- Struttura della tabella `eyestask_a`
--
-- Creazione: Set 01, 2016 alle 17:57
--

CREATE TABLE IF NOT EXISTS `eyestask_a` (
  `COIDNum` int(11) NOT NULL DEFAULT '0',
  `QNum` tinyint(4) NOT NULL DEFAULT '0',
  `Answer` char(1) DEFAULT NULL,
  PRIMARY KEY (`COIDNum`,`QNum`),
  KEY `QNum` (`QNum`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- RELATIONS FOR TABLE `eyestask_a`:
--   `COIDNum`
--       `eyestask_c` -> `idnumber`
--   `QNum`
--       `eyestask_ca` -> `QNum`
--

--
-- Dump dei dati per la tabella `eyestask_a`
--

-- --------------------------------------------------------

--
-- Struttura della tabella `eyestask_ac`
--
-- Creazione: Set 01, 2016 alle 17:57
--

CREATE TABLE IF NOT EXISTS `eyestask_ac` (
  `QNum` tinyint(4) NOT NULL DEFAULT '0',
  `AType` tinyint(4) NOT NULL DEFAULT '0',
  `A` varchar(20) DEFAULT NULL,
  `B` varchar(20) DEFAULT NULL,
  `C` varchar(20) DEFAULT NULL,
  `D` varchar(20) DEFAULT NULL,
  PRIMARY KEY (`QNum`,`AType`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- RELATIONS FOR TABLE `eyestask_ac`:
--   `QNum`
--       `eyestask_ca` -> `QNum`
--

--
-- Dump dei dati per la tabella `eyestask_ac`
--

INSERT INTO `eyestask_ac` (`QNum`, `AType`, `A`, `B`, `C`, `D`) VALUES
(1, 1, 'Geloso', 'Spaventato', 'Arrogante', 'Odioso'),
(2, 1, 'Giocoso', 'Confortante', 'Irritato', 'Annoiato'),
(3, 1, 'Terrorizzato', 'Turbato', 'Arrogante', 'Infastidito'),
(4, 1, 'Scherzoso', 'Agitato', 'Desiderio', 'Convinto'),
(5, 1, 'Scherzoso', 'Insistente', 'Divertito', 'Rilassato'),
(6, 1, 'Irritato', 'Sarcastico', 'Spaventato', 'Amichevole'),
(7, 1, 'Stupefatto', 'Sognante', 'Insofferente', 'Allarmato'),
(8, 1, 'Dispiaciuto', 'Amichevole', 'Inquieto', 'Avvilito'),
(9, 1, 'Scoraggiato', 'Sollevato', 'Intimidito', 'Emozionato'),
(10, 1, 'Infastidito', 'Ostile', 'Impressionato', 'Preoccupato'),
(11, 1, 'Prudente', 'Insistente', 'Annoiato', 'Stupefatto'),
(12, 1, 'Terrorizzato', 'Divertito', 'Pentito', 'Civettuolo'),
(13, 1, 'Indifferente', 'Imbarazzato', 'Scettico', 'Avvilito'),
(14, 1, 'Risoluto', 'Anticipante', 'Minaccioso', 'Intimidito'),
(15, 1, 'Irritato', 'Deluso', 'Depresso', 'Accusante'),
(16, 1, 'Contemplativo', 'Agitato', 'Incoragiante', 'Divertito'),
(17, 1, 'Irritato', 'Pensieroso', 'Incoraggiate', 'Confortante'),
(18, 1, 'Incerto', 'Afettuoso', 'Giocoso', 'Stupefatto'),
(19, 1, 'Risoluto', 'Divertito', 'Stupefatto', 'Annoiato'),
(20, 1, 'Arrogante', 'Riconoscente', 'Sarcastico', 'Intimidito'),
(21, 1, 'Dominante', 'Amichevole', 'Colpevole', 'Impressionato'),
(22, 1, 'Imbarazzato', 'Sognante', 'Confuso', 'Spaventato'),
(23, 1, 'Preoccupato', 'Riconoscente', 'Insistente', 'Implorante'),
(24, 1, 'Contento', 'Dispiaciuto', 'Sprezzante', 'Curioso'),
(25, 1, 'Pensieroso', 'Irritato', 'Emozionato', 'Ostile'),
(26, 1, 'Spaventato', 'Incredulo', 'Scoraggiato', 'Interessato'),
(27, 1, 'Allarmato', 'Intimidito', 'Ostile', 'Ansioso'),
(28, 1, 'Scherzoso', 'Prudente', 'Arrogante', 'Rassicurante'),
(29, 1, 'Interessato', 'Scherzoso', 'Affettuoso', 'Contento'),
(30, 1, 'Insofferente', 'Stupefatto', 'Irritato', 'Riflessivo'),
(31, 1, 'Riconoscente', 'Civettuolo', 'Ostile', 'Deluso'),
(32, 1, 'Vergognato', 'Fiducioso', 'Scherzoso', 'Avvilito'),
(33, 1, 'Serio', 'Vergognato', 'Disorientato', 'Allarmato'),
(34, 1, 'Imbarazzato', 'Colpevole', 'Sognante', 'Preoccupante'),
(35, 1, 'Stupefatto', 'Perplesso', 'Diffidente', 'Terrorizzato'),
(36, 1, 'Perplesso', 'Nervoso', 'Insistente', 'Contemplativo'),
(37, 1, 'Vergognato', 'Nervoso', 'Sospettoso', 'Indeciso');

-- --------------------------------------------------------

--
-- Struttura della tabella `eyestask_c`
--
-- Creazione: Set 01, 2016 alle 17:57
--

CREATE TABLE IF NOT EXISTS `eyestask_c` (
  `idnumber` int(11) NOT NULL AUTO_INCREMENT,
  `patient` int(10) unsigned DEFAULT NULL,
  `test` tinyint(3) unsigned DEFAULT NULL,
  `QType` tinyint(4) NOT NULL,
  `AType` tinyint(4) NOT NULL,
  `Total` tinyint(4) DEFAULT NULL,
  `Day` date DEFAULT NULL,
  PRIMARY KEY (`idnumber`),
  KEY `patient` (`patient`),
  KEY `test` (`test`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

--
-- RELATIONS FOR TABLE `eyestask_c`:
--   `patient`
--       `user` -> `id`
--   `test`
--       `test` -> `code`
--

--
-- Dump dei dati per la tabella `eyestask_c`
--

-- --------------------------------------------------------

--
-- Struttura della tabella `eyestask_ca`
--
-- Creazione: Set 01, 2016 alle 17:57
--

CREATE TABLE IF NOT EXISTS `eyestask_ca` (
  `QNum` tinyint(4) NOT NULL DEFAULT '0',
  `Answer` char(1) NOT NULL,
  PRIMARY KEY (`QNum`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dump dei dati per la tabella `eyestask_ca`
--

INSERT INTO `eyestask_ca` (`QNum`, `Answer`) VALUES
(1, 'B'),
(2, 'A'),
(3, 'B'),
(4, 'C'),
(5, 'B'),
(6, 'C'),
(7, 'B'),
(8, 'C'),
(9, 'A'),
(10, 'D'),
(11, 'A'),
(12, 'C'),
(13, 'C'),
(14, 'B'),
(15, 'D'),
(16, 'A'),
(17, 'B'),
(18, 'A'),
(19, 'A'),
(20, 'D'),
(21, 'B'),
(22, 'B'),
(23, 'A'),
(24, 'C'),
(25, 'A'),
(26, 'D'),
(27, 'C'),
(28, 'B'),
(29, 'A'),
(30, 'D'),
(31, 'B'),
(32, 'B'),
(33, 'A'),
(34, 'D'),
(35, 'C'),
(36, 'B'),
(37, 'C');

-- --------------------------------------------------------

--
-- Struttura della tabella `eyestask_qc`
--
-- Creazione: Set 01, 2016 alle 17:57
--

CREATE TABLE IF NOT EXISTS `eyestask_qc` (
  `QNum` tinyint(4) NOT NULL DEFAULT '0',
  `QType` tinyint(4) NOT NULL DEFAULT '0',
  `Eyes` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`QNum`,`QType`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- RELATIONS FOR TABLE `eyestask_qc`:
--   `QNum`
--       `eyestask_ca` -> `QNum`
--

--
-- Dump dei dati per la tabella `eyestask_qc`
--

INSERT INTO `eyestask_qc` (`QNum`, `QType`, `Eyes`) VALUES
(1, 2, 'img1'),
(2, 2, 'img2'),
(3, 2, 'img3'),
(4, 2, 'img4'),
(5, 2, 'img5'),
(6, 2, 'img6'),
(7, 2, 'img7'),
(8, 2, 'img8'),
(9, 2, 'img9'),
(10, 2, 'img10'),
(11, 2, 'img11'),
(12, 2, 'img12'),
(13, 2, 'img13'),
(14, 2, 'img14'),
(15, 2, 'img15'),
(16, 2, 'img16'),
(17, 2, 'img17'),
(18, 2, 'img18'),
(19, 2, 'img19'),
(20, 2, 'img20'),
(21, 2, 'img21'),
(22, 2, 'img22'),
(23, 2, 'img23'),
(24, 2, 'img24'),
(25, 2, 'img25'),
(26, 2, 'img26'),
(27, 2, 'img27'),
(28, 2, 'img28'),
(29, 2, 'img29'),
(30, 2, 'img30'),
(31, 2, 'img31'),
(32, 2, 'img32'),
(33, 2, 'img33'),
(34, 2, 'img34'),
(35, 2, 'img35'),
(36, 2, 'img36'),
(37, 2, 'img37');

-- --------------------------------------------------------

--
-- Struttura della tabella `help`
--
-- Creazione: Set 01, 2016 alle 17:57
--

CREATE TABLE IF NOT EXISTS `help` (
  `test` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `type` tinyint(4) NOT NULL DEFAULT '0',
  `help` text,
  PRIMARY KEY (`test`,`type`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- RELATIONS FOR TABLE `help`:
--   `test`
--       `test` -> `code`
--

-- --------------------------------------------------------

--
-- Struttura della tabella `patient`
--
-- Creazione: Set 01, 2016 alle 17:57
--

CREATE TABLE IF NOT EXISTS `patient` (
  `id` int(10) unsigned NOT NULL DEFAULT '0',
  `cg` tinyint(4) DEFAULT NULL,
  `name` varchar(30) DEFAULT NULL,
  `surname` varchar(30) DEFAULT NULL,
  `number` varchar(15) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `cg` (`cg`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- RELATIONS FOR TABLE `patient`:
--   `id`
--       `user` -> `id`
--   `cg`
--       `clinicalg` -> `code`
--

--
-- Dump dei dati per la tabella `patient`
--

-- --------------------------------------------------------

--
-- Struttura della tabella `patient_tests`
--
-- Creazione: Set 01, 2016 alle 17:57
--

CREATE TABLE IF NOT EXISTS `patient_tests` (
  `id` int(10) unsigned NOT NULL DEFAULT '0',
  `aq` tinyint(1) DEFAULT NULL,
  `attint` tinyint(1) DEFAULT NULL,
  `bes` tinyint(1) DEFAULT NULL,
  `emotatt` tinyint(1) DEFAULT NULL,
  `eyestask` tinyint(1) DEFAULT NULL,
  `qe` tinyint(1) DEFAULT NULL,
  `socialsit` tinyint(1) DEFAULT NULL,
  `tom` tinyint(1) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- RELATIONS FOR TABLE `patient_tests`:
--   `id`
--       `patient` -> `id`
--

--
-- Dump dei dati per la tabella `patient_tests`
--

-- --------------------------------------------------------

--
-- Struttura della tabella `qe_a`
--
-- Creazione: Set 01, 2016 alle 17:57
--

CREATE TABLE IF NOT EXISTS `qe_a` (
  `COIDNum` int(11) NOT NULL DEFAULT '0',
  `QNum` tinyint(4) NOT NULL DEFAULT '0',
  `Answer` char(1) DEFAULT NULL,
  PRIMARY KEY (`COIDNum`,`QNum`),
  KEY `QNum` (`QNum`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- RELATIONS FOR TABLE `qe_a`:
--   `COIDNum`
--       `qe_c` -> `idnumber`
--   `QNum`
--       `qe_ca` -> `QNum`
--

--
-- Dump dei dati per la tabella `qe_a`
--


-- --------------------------------------------------------

--
-- Struttura della tabella `qe_c`
--
-- Creazione: Set 01, 2016 alle 17:57
--

CREATE TABLE IF NOT EXISTS `qe_c` (
  `idnumber` int(11) NOT NULL AUTO_INCREMENT,
  `patient` int(10) unsigned DEFAULT NULL,
  `test` tinyint(3) unsigned DEFAULT NULL,
  `QType` tinyint(4) NOT NULL,
  `AType` tinyint(4) NOT NULL,
  `CE` tinyint(4) DEFAULT NULL,
  `SS` tinyint(4) DEFAULT NULL,
  `ee` tinyint(4) DEFAULT NULL,
  `total` tinyint(4) DEFAULT NULL,
  `Day` date DEFAULT NULL,
  PRIMARY KEY (`idnumber`),
  KEY `patient` (`patient`),
  KEY `test` (`test`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=4 ;

--
-- RELATIONS FOR TABLE `qe_c`:
--   `patient`
--       `user` -> `id`
--   `test`
--       `test` -> `code`
--

--
-- Dump dei dati per la tabella `qe_c`
--

-- --------------------------------------------------------

--
-- Struttura della tabella `qe_ca`
--
-- Creazione: Set 01, 2016 alle 17:57
--

CREATE TABLE IF NOT EXISTS `qe_ca` (
  `QNum` tinyint(4) NOT NULL DEFAULT '0',
  `A` tinyint(4) NOT NULL,
  `B` tinyint(4) NOT NULL,
  `C` tinyint(4) NOT NULL,
  `D` tinyint(4) NOT NULL,
  `Type` tinyint(4) NOT NULL,
  PRIMARY KEY (`QNum`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dump dei dati per la tabella `qe_ca`
--

INSERT INTO `qe_ca` (`QNum`, `A`, `B`, `C`, `D`, `Type`) VALUES
(1, 2, 1, 0, 0, 1),
(2, 0, 0, 1, 2, 3),
(3, 2, 1, 0, 0, 2),
(4, 0, 0, 1, 2, 3),
(5, 0, 0, 1, 2, 0),
(6, 0, 0, 1, 2, 0),
(7, 0, 0, 1, 2, 3),
(8, 0, 0, 1, 2, 3),
(9, 0, 0, 1, 2, 0),
(10, 0, 0, 1, 2, 0),
(11, 2, 1, 0, 0, 1),
(12, 0, 0, 1, 2, 2),
(13, 2, 1, 0, 0, 2),
(14, 2, 1, 0, 0, 1),
(15, 2, 1, 0, 0, 1),
(16, 0, 0, 1, 2, 2),
(17, 0, 0, 1, 2, 0),
(18, 0, 0, 1, 2, 2),
(19, 0, 0, 1, 2, 2),
(20, 0, 0, 1, 2, 0),
(21, 2, 1, 0, 0, 3),
(22, 2, 1, 0, 0, 1),
(23, 2, 1, 0, 0, 0),
(24, 2, 1, 0, 0, 0),
(25, 0, 0, 1, 2, 0),
(26, 2, 1, 0, 0, 1),
(27, 2, 1, 0, 0, 2),
(28, 2, 1, 0, 0, 2),
(29, 2, 1, 0, 0, 1),
(30, 0, 0, 1, 2, 0),
(31, 0, 0, 1, 2, 2),
(32, 0, 0, 1, 2, 0),
(33, 0, 0, 1, 2, 2),
(34, 2, 1, 0, 0, 1),
(35, 2, 1, 0, 0, 1),
(36, 2, 1, 0, 0, 1),
(37, 2, 1, 0, 0, 3),
(38, 2, 1, 0, 0, 1),
(39, 2, 1, 0, 0, 2),
(40, 2, 1, 0, 0, 0);

-- --------------------------------------------------------

--
-- Struttura della tabella `qe_qc`
--
-- Creazione: Set 01, 2016 alle 17:57
--

CREATE TABLE IF NOT EXISTS `qe_qc` (
  `QNum` tinyint(4) NOT NULL DEFAULT '0',
  `QType` tinyint(4) NOT NULL DEFAULT '0',
  `Question` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`QNum`,`QType`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- RELATIONS FOR TABLE `qe_qc`:
--   `QNum`
--       `qe_ca` -> `QNum`
--

--
-- Dump dei dati per la tabella `qe_qc`
--

INSERT INTO `qe_qc` (`QNum`, `QType`, `Question`) VALUES
(1, 1, 'Capisco con facilità se qualcuno vuole partecipare ad una conversazione'),
(2, 1, 'Trovo difficile spiegare agli altri concetti che io comprendo facilmente, quando loro non capiscono alla prima spiegazione'),
(3, 1, 'Prendermi cura degli altri è qualcosa che mi fa veramente piacere'),
(4, 1, 'Trovo difficile capire come comportarmi in una situazione sociale'),
(5, 1, 'Le persone spesso mi dicono che insisto troppo sul mio punto di vista in una discussione'),
(6, 1, 'Non mi preoccupa più di tanto essere in ritardo ad un appuntamento con un amico'),
(7, 1, 'Le amicizie e le relazioni sociali sono troppo difficili per me, così tendo a non occuparmene'),
(8, 1, 'Spesso trovo difficile giudicare se qualcosa e’ sgarbato o cortese'),
(9, 1, 'In una conversazione tendo a focalizzarmi sulle mie idee piuttosto che su cosa potrebbe stare pensando il mio interlocutore'),
(10, 1, 'Quando ero bambino/a mi divertivo a sezionare i vermi per vedere cosa succedeva'),
(11, 1, 'Riesco facilmente a capire se qualcuno dice una cosa ma ne intende un’altra'),
(12, 1, 'Non capisco perché la gente si offende tanto per certe cose'),
(13, 1, 'Riesco facilmente a mettermi nei panni degli altri'),
(14, 1, 'Sono bravo/a a predire i sentimenti degli altri'),
(15, 1, 'Mi accorgo subito se qualcuno in un gruppo e’ a disagio o imbarazzato'),
(16, 1, 'Se ciò che dico offende qualcuno, penso che questo sia un suo problema, non mio'),
(17, 1, 'Se qualcuno mi chiede un parere sul suo nuovo taglio di capelli, rispondo sinceramente anche se non mi piace'),
(18, 1, 'Non riesco sempre a capire perché qualcuno possa essersi sentito offeso da un commento'),
(19, 1, 'Vedere qualcuno piangere non mi turba più di tanto'),
(20, 1, 'Sono molto diretto e questo viene spesso interpretato come scortesia, anche se non è mia intenzione'),
(21, 1, 'Non ho la tendenza a sentirmi confuso nelle situazioni sociali'),
(22, 1, 'Le persone mi dicono che sono bravo/a a comprendere cosa stanno provando o cosa stanno pensando'),
(23, 1, 'Quando parlo con le persone tendo a discutere più delle loro esperienze che delle mie'),
(24, 1, 'Mi turba vedere soffrire un animale'),
(25, 1, 'Riesco a prendere le decisioni senza lasciarmi influenzare dalle opinioni degli altri'),
(26, 1, 'Riesco facilmente a capire se il mio interlocutore è interessato o annoiato da ciò che dico'),
(27, 1, 'Mi turbano le immagini di gente che soffre quando guardo le notizie in TV'),
(28, 1, 'Gli amici spesso mi parlano dei loro problemi perché si sentono capiti'),
(29, 1, 'Riesco a percepire se la mia presenza è indesiderata, anche se non mi viene detto espressamente'),
(30, 1, 'Talvolta le persone mi dicono che ho esagerato nel prendere in giro'),
(31, 1, 'La gente mi dice spesso che sono insensibile sebbene io non sempre ne capisco il perché'),
(32, 1, 'Se vedo una persona nuova gruppo, penso che stia a lui fare uno in un sforzo per inserirsi'),
(33, 1, 'Solitamente rimango emotivamente distaccato quando guardo un film'),
(34, 1, 'Riesco ad entrare in sintonia con ciò che qualcun altro sta provando in modo rapido e intuitivo'),
(35, 1, 'Riesco facilmente ad intuire di cosa il mio interlocutore desidera parlare'),
(36, 1, 'Capisco se qualcuno sta celando le sue vere emozioni'),
(37, 1, 'Nelle situazioni sociali ho difficoltà a decifrare le regole in modo consapevole'),
(38, 1, 'Sono bravo/a a prevedere ciò che una persona farà'),
(39, 1, 'Tendo a farmi coinvolgere emotivamente dai problemi degli amici'),
(40, 1, 'Di solito tengo in considerazione il punto di vista degli altri anche se non lo condivido');

-- --------------------------------------------------------

--
-- Struttura della tabella `socialsit_a`
--
-- Creazione: Set 01, 2016 alle 17:57
--

CREATE TABLE IF NOT EXISTS `socialsit_a` (
  `COIDNum` int(11) NOT NULL DEFAULT '0',
  `QNum` tinyint(4) NOT NULL DEFAULT '0',
  `SubQNum` tinyint(4) NOT NULL DEFAULT '0',
  `Answer` char(1) DEFAULT NULL,
  PRIMARY KEY (`COIDNum`,`QNum`,`SubQNum`),
  KEY `QNum` (`QNum`,`SubQNum`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- RELATIONS FOR TABLE `socialsit_a`:
--   `COIDNum`
--       `socialsit_c` -> `idnumber`
--

--
-- Dump dei dati per la tabella `socialsit_a`
--


-- --------------------------------------------------------

--
-- Struttura della tabella `socialsit_c`
--
-- Creazione: Set 01, 2016 alle 17:57
--

CREATE TABLE IF NOT EXISTS `socialsit_c` (
  `idnumber` int(11) NOT NULL AUTO_INCREMENT,
  `patient` int(10) unsigned DEFAULT NULL,
  `test` tinyint(3) unsigned DEFAULT NULL,
  `QType` tinyint(4) NOT NULL,
  `AType` tinyint(4) NOT NULL,
  `NormB` tinyint(4) DEFAULT NULL,
  `Violation` tinyint(4) DEFAULT NULL,
  `Severity` tinyint(4) DEFAULT NULL,
  `Day` date DEFAULT NULL,
  PRIMARY KEY (`idnumber`),
  KEY `patient` (`patient`),
  KEY `test` (`test`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

--
-- RELATIONS FOR TABLE `socialsit_c`:
--   `patient`
--       `user` -> `id`
--   `test`
--       `test` -> `code`
--

--
-- Dump dei dati per la tabella `socialsit_c`
--

-- --------------------------------------------------------

--
-- Struttura della tabella `socialsit_ca`
--
-- Creazione: Set 01, 2016 alle 17:57
--

CREATE TABLE IF NOT EXISTS `socialsit_ca` (
  `QNum` tinyint(4) NOT NULL DEFAULT '0',
  `SubQNum` tinyint(4) NOT NULL DEFAULT '0',
  `A` tinyint(4) NOT NULL,
  `B` tinyint(4) NOT NULL,
  `C` tinyint(4) NOT NULL,
  `D` tinyint(4) NOT NULL,
  `type` tinyint(4) DEFAULT NULL,
  PRIMARY KEY (`QNum`,`SubQNum`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dump dei dati per la tabella `socialsit_ca`
--

INSERT INTO `socialsit_ca` (`QNum`, `SubQNum`, `A`, `B`, `C`, `D`, `type`) VALUES
(1, 1, 1, 0, 0, 0, 0),
(1, 2, 0, 1, 2, 3, 1),
(2, 1, 1, 0, 0, 0, 0),
(2, 2, 0, 1, 2, 3, 1),
(3, 1, 0, 1, 2, 3, 1),
(3, 2, 0, 1, 2, 3, 1),
(3, 3, 0, 1, 2, 3, 1),
(4, 1, 1, 0, 0, 0, 0),
(5, 1, 1, 0, 0, 0, 0),
(5, 2, 0, 1, 2, 3, 1),
(5, 3, 0, 1, 2, 3, 1),
(6, 1, 0, 1, 2, 3, 1),
(7, 1, 1, 0, 0, 0, 0),
(7, 2, 0, 1, 2, 3, 1),
(8, 1, 1, 0, 0, 0, 0),
(8, 2, 0, 1, 2, 3, 1),
(9, 1, 0, 1, 2, 3, 1),
(10, 1, 0, 1, 2, 3, 1),
(11, 1, 0, 1, 2, 3, 1),
(12, 1, 1, 0, 0, 0, 0),
(12, 2, 0, 1, 2, 3, 1),
(13, 1, 0, 1, 2, 3, 1),
(14, 1, 1, 0, 0, 0, 0),
(14, 2, 1, 0, 0, 0, 0),
(15, 1, 1, 0, 0, 0, 0),
(16, 1, 0, 1, 2, 3, 1),
(17, 1, 1, 0, 0, 0, 0),
(17, 2, 1, 0, 0, 0, 0),
(17, 3, 0, 1, 2, 3, 1),
(18, 1, 0, 1, 2, 3, 1),
(19, 1, 0, 1, 2, 3, 1),
(20, 1, 0, 1, 2, 3, 1),
(21, 1, 1, 0, 0, 0, 0),
(21, 2, 0, 1, 2, 3, 1),
(22, 1, 0, 1, 2, 3, 1),
(23, 1, 1, 0, 0, 0, 0),
(23, 2, 0, 1, 2, 3, 1),
(24, 1, 0, 1, 2, 3, 1),
(25, 1, 1, 0, 0, 0, 0),
(25, 2, 0, 1, 2, 3, 1);

-- --------------------------------------------------------

--
-- Struttura della tabella `socialsit_qc`
--
-- Creazione: Set 01, 2016 alle 17:57
--

CREATE TABLE IF NOT EXISTS `socialsit_qc` (
  `QNum` tinyint(4) NOT NULL DEFAULT '0',
  `SubQNum` tinyint(4) NOT NULL DEFAULT '0',
  `QType` tinyint(4) NOT NULL DEFAULT '0',
  `Intro` text,
  `Question` text,
  PRIMARY KEY (`QNum`,`SubQNum`,`QType`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dump dei dati per la tabella `socialsit_qc`
--

INSERT INTO `socialsit_qc` (`QNum`, `SubQNum`, `QType`, `Intro`, `Question`) VALUES
(1, 1, 1, 'Carlo era un impiegato che lavorava in un ufficio della sua città. A mezzogiorno egli pranzava in un piccolo parco.', 'Spesso egli rompeva parte di un panino in piccoli pezzi, spargendoli nel terreno per i piccioni.'),
(1, 2, 1, 'Un giorno, mentre si sedeva nella panchina, un passeggino venne parcheggiato proprio lì davanti. Carlo notò che vicino una giovane donna stava dondolando un bambino un po’ più grande. Il bambino nel passeggino cominciò a piangere ma la mamma non lo sentiva. Carlo ha imparato che quando il suo piccolo nipote grida, talvolta significa che il suo pannolino si è\naperto.', 'Piuttosto che disturbare la madre nel parco, Carlo velocemente controllò i vestitini del bambino per vedere se poteva sentire il pannolino aperto.'),
(2, 1, 1, 'Emilia si svegliò in ritardo il giorno del suo viaggio in aereo. C’era appena il tempo per vestirsi e andare all’aeroporto,', 'così saltò la sua colazione.'),
(2, 2, 1, 'A mezzogiorno la hostess venne con il pranzo, ma Emilia era così affamata che una porzione non l’aveva soddisfatta. Guardò una ragazzina attraverso il corridoio che giocherellava con il suo cibo, lamentandosi: ”Non riesco a mangiarlo”. Emilia si sporse dal corridoio e disse:', '”Se la sua piccola bambina non vuole il suo vassoio, lo può passare di qua per me?”'),
(3, 1, 1, 'Ruggero viveva da solo e un giorno venne invitato a cena a casa di un’amica della madre. In genere egli era solito avvertire la cameriera che non mangiava carne e che avrebbe desiderato che la sua verdura gli venisse servita scondita. Quando Ruggero arrivò all’appuntamento si ricordò che non aveva toccato cibo da due ore.', 'Senza perdere alcun tempo, ancor prima di essere presentato, egli chiese alla cameriera quando la cena sarebbe stata servita.'),
(3, 2, 1, 'Lei rispose che ci sarebbe voluta ancora un’ora prima che il cibo fosse pronto.', 'Sentendo ciò, Ruggero aprì la sua valigetta, prese una mela e alcune noci e prontamente le mangiò.'),
(3, 3, 1, 'Proprio prima di cena, la cameriera gli fece vedere un attraente piatto di frutti e verdure, chiedendo se gli sembrava abbastanza.', '“Va bene, grazie” - disse Ruggero - “ma se non le dispiace io aspetterei un’altra ora per mangiare. Ho già preso qualcosa un’ora fa”'),
(4, 1, 1, 'Elisabetta era diabetica da tutta la vita. I dottori le avevano detto di stare molto attenta alla sua dieta per evitare serie complicazioni. Quando veniva invitata da qualcuno per un pranzo, lei spiegava il problema in anticipo.', 'Ma durante gli incontri importanti godeva delle conversazioni e della compagnia che trovava, aspettando di tornare a casa per mangiare.'),
(5, 1, 1, 'Franco faceva il giardiniere. Di solito si portava il pranzo al sacco.', 'A mezzogiorno, Franco si lavava sempre le mani sotto un rubinetto e sedeva in una parte ombreggiata del giardino per mangiare.'),
(5, 2, 1, 'Un giorno cominciò a piovere a mezzogiorno. Franco bussò alla porta della casa dove lavorava e chiese il permesso di mangiare dentro. La signora gli disse di entrare e lui individuò da solo la sala da pranzo.', 'Tolse le briciole dal\r\ntavolo e guardò intorno alla casa in cerca di un posto dove riposarsi.'),
(5, 3, 1, '', 'Il tappeto del salotto era sottile, perciò egli decise di rannicchiarsi per il suo pisolino sopra una larga poltrona.'),
(6, 1, 1, 'Il supermercato dove Roberto era solito fare la spesa aveva una piccola scritta nella porta che diceva: ”È proibito entrare scalzi in questo magazzino”. Un giorno d’estate Roberto vide una bella ragazza entrare nel magazzino a piedi scalzi. Roberto decise di far in modo che i piedi di lei non venissero visti dal direttore.', 'Spinse il suo carrello vicino e davanti a lei, corridoio dopo corridoio.'),
(7, 1, 1, 'Matteo è stato appena nominato in un importante nuovo lavoro. Alle 6 in punto del giorno in cui pensava di cominciare il nuovo lavoro, Matteo ricevette una telefonata da sua madre che stava molto male.', 'Matteo era molto preoccupato e così infilò un maglione sopra il suo pigiama e guidò verso la casa di sua madre per curarla.'),
(7, 2, 1, 'Dopo che il dottore fu arrivato, non c’era abbastanza tempo perché Matteo ritornasse a casa sua e si cambiasse di abito.', 'Egli decise di andare al lavoro con il suo pigiama perché non voleva arrivare tardi al suo nuovo lavoro.'),
(8, 1, 1, 'Giovanna si recò con sua figlia di tre anni all’aeroporto. C’erano file molto lunghe di persone che aspettavano di registrare i loro bagagli.', 'Giovanna si mise in coda alla fila con sua figlia.'),
(8, 2, 1, 'Dopo un po’ di minuti Giovanna vide sua figlia correre attraverso le uscite al controllo del passaporto. Giovanna era molto preoccupata così chiese ad una signora davanti a lei nella fila se poteva passare avanti perché aveva fretta.', 'La signora non rispose a Giovanna, e così lei spinse la signora fuori dalla fila e per terra.'),
(9, 1, 1, 'Era una calda giornata d’estate e Giovanni amava consumare il suo pranzo nel parco lì vicino.', 'Era stato parecchio caldo in ufficio così quando Giovanni arrivò nel parco si levò tutti i vestiti e mangiò il suo panino nudo.'),
(10, 1, 1, 'Maria portò dei fiori nella casa della sua migliore amica perchè la sua cara madre era appena defunta.', 'Ogni persona alla casa era molto turbata e così per tentare di tirarli su di corda Maria decise di raccontare una barzelletta.'),
(11, 1, 1, 'Un giocatore di baseball arrivò tardi ad una partita cruciale e si rese conto di aver dimenticato la sua divisa a casa.', 'Egli non aveva tempo per ritornare a casa e prenderla, così entrò nello stadio con la sua biancheria intima.'),
(12, 1, 1, 'Arturo, un uomo vecchio e fragile, un giorno si recò alla stazione metropolitana. C’era molta gente nel metrò e non riuscì a trovare un posto a sedere.', 'Subito dopo una giovane ragazza lo vide e gli offrì il suo posto.'),
(12, 2, 1, 'Egli notò che la ragazza stava mangiando una tavoletta di cioccolata. Lui era molto affamato visto che non aveva potuto mangiare quel giorno.', 'Si girò verso la ragazza e le chiese se le avrebbe dato un po’ della sua cioccolata.'),
(13, 1, 1, 'William stava bevendo in un pub con dei suoi amici. Quando si incamminò fuori dal pub, un uomo picchiettò forte sulla sua spalla.', 'William gli diede un pugno in bocca.'),
(14, 1, 1, 'Susanna stava facendo la spesa. Stava comprando delle caramelle per sua figlia Katia. Mentre stava uscendo dal negozio, un uomo la afferrò e tentò di prenderle la borsa.', 'Susanna gli diede uno schiaffo in faccia.'),
(14, 2, 1, 'L’uomo corse via lungo la strada.', 'Katia, la figlia di Susanna, cominciò a piangere.'),
(15, 1, 1, 'Francesco ha dei vicini di casa molto ricchi. Essi comprano sempre nuove cose per la loro casa: nuovi frigoriferi, nuovi televisori. Ogni volta che comprano qualcosa di nuovo, essi gettano via quella vecchia.', 'Un giorno i vicini di Francesco comprano un nuovo impianto musicale.'),
(16, 1, 1, 'Elisabetta, una segretaria, un giorno era al lavoro e stava battendo sul suo computer. Poi, l’altra segretaria, Luisa, entrò nella stanza, posò la sua borsa per terra, attaccata alla sedia di Elisabetta, e poi se ne andò.', 'Elisabetta non\r\naveva soldi e così aprì la borsa e tirò fuori 50 € dal portafoglio.'),
(17, 1, 1, '', 'Bruno non vedeva un suo amico da un paio di settimane.'),
(17, 2, 1, 'Lo rincorse lungo la strada per prenderlo.', 'Bruno salutò il suo amico.'),
(17, 3, 1, 'Bruno e il suo amico chiacchierarono per almeno dieci minuti. Poi decisero di prendere un caffè in un bar lì vicino. Quando arrivarono, l’amico di Bruno disse: ”Sono appena ritornato da Roma”.', 'Bruno pensa che ciò sia molto divertente e comincia a ridere.'),
(18, 1, 1, 'Roberto è completamente esausto e vorrebbe andare disperatamente a dormire. Ma deve vedere il suo capo a casa sua sta sera. Prende la metropolitana verso la casa del suo capo. La camminata verso la casa dell’uomo lo rende ancora più stanco. Il suo capo lo accoglie e lo invita dentro e gli dice che tornerà giù tra pochi minuti, visto che deve sistemare delle cose di sopra. Roberto va nella stanza principale della casa. C’è un tappeto piuttosto spesso. Si sente ancora tanto esausto.', 'Così Roberto si stende sul grosso tappeto e decide di fare un sonnellino.'),
(19, 1, 1, 'Maria si è velocemente addormentata a letto. È andata a letto nuda. Alle tre in punto del pomeriggio si sveglia. In casa c’è qualcuno con lei. L’intruso deve essere un ladro.', 'Maria salta giù e corre fuori di casa e lungo la strada senza niente addosso.'),
(20, 1, 1, 'Riccardo è andato al cinema. La maschera gli ha mostrato il suo posto a sedere ed egli è pronto per vedere il suo film. Poi, cinque minuti più tardi, la maschera ritorna con alcune altre persone. A Riccardo è stato assegnato il posto sbagliato. Egli avrebbe dovuto essere nella fila dietro.', 'Riccardo è furioso e urla alla maschera per cinque minuti.'),
(21, 1, 1, 'Roberta è una persona molto timida e paurosa. Un giorno lei e la sua migliore amica vanno in un bar. L’amica di Roberta le racconta una barzelletta che le era stata detta il giorno prima.', 'Roberta pensa che la barzelletta sia molto divertente e ride.'),
(21, 2, 1, 'Si siedono a un tavolo e continuano a chiacchierare. L’amica di Roberta le racconta un incubo che ha avuto la notte prima.', 'Roberta è terrorizzata e si nasconde sotto il tavolo.'),
(22, 1, 1, 'Luca prende l’ascensore al suo lavoro. È un freddo gelido. Fuori c’è neve spessa ma al suo lavoro non si accenderà il riscaldamento. L’ascensore è affollato. La persona accanto a Luca nell’ascensore indossa un grosso maglione di pelo.', 'Luca ha così freddo che si spinge contro quella persona tentando di scaldarsi.'),
(23, 1, 1, 'Marco si trova nella vasca da bagno a casa sua.', 'È sdraiato lì da quindici minuti e adesso sta leggendo un libro.'),
(23, 2, 1, 'Improvvisamente suona il campanello della porta. Marco sa che potrebbe essere il postino che quella sera avrebbe dovuto consegnare un pacchetto per lui. È cruciale che Marco riceva il pacchetto quella sera. Marco corre fuori dal\r\nbagno e cerca un asciugamano ma non riesce a vedere né asciugamani né vestiti.', 'Marco corre alla porta e la apre nudo.'),
(24, 1, 1, 'Giada è nel suo ufficio. Il giorno prima aveva comprato un nuovo paio di scarpe che sono troppo strette e le comprimono i piedi. Battendo al suo computer, Giada non riesce a sopportare a lungo il dolore e così si toglie le\nscarpe e i calzini e si massaggia i piedi. Un’ora più tardi il capo di Giada la chiama nel suo ufficio. L’incontro sarà lungo perché devono discutere parecchie cose. Di nuovo, Giada sente un forte male ai piedi.', 'Giada si toglie le scarpe e i calzini e si massaggia i piedi.'),
(25, 1, 1, 'La madre di Franco è appena morta. L’intera famiglia è al funerale.', 'La sorella di Franco comincia a piangere.'),
(25, 2, 1, 'Il sacerdote invita al silenzio per un minuto di rispetto. Improvvisamente e accidentalmente, l’uomo dietro Franco si muove e dà un calcio molto forte a Franco nella tibia. Franco viene colto di sorpresa. Salta e lancia un guaito. La sorella di Franco ha visto tutto.', 'Lei pensa che ciò sia molto divertente e comincia a ridere.');

-- --------------------------------------------------------

--
-- Struttura della tabella `test`
--
-- Creazione: Set 01, 2016 alle 17:57
--

CREATE TABLE IF NOT EXISTS `test` (
  `code` tinyint(3) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(50) DEFAULT NULL,
  `active` tinyint(1) DEFAULT NULL,
  PRIMARY KEY (`code`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=9 ;

--
-- Dump dei dati per la tabella `test`
--

INSERT INTO `test` (`code`, `name`, `active`) VALUES
(1, 'Quoziente di Spettro di Autismo', 1),
(2, 'Test di Attribuzione delle Intenzioni', 0),
(3, 'Basic Empathy Scale', 1),
(4, 'Test di Attribuzione delle Emozioni', 0),
(5, 'Eyes Task', 0),
(6, 'Test di Quoziente di Empatia', 1),
(7, 'Test delle Situazioni Sociali', 0),
(8, 'Test di Teoria della Mente di livello superiore', 0);

-- --------------------------------------------------------

--
-- Struttura della tabella `test_type`
--
-- Creazione: Set 01, 2016 alle 17:57
--

CREATE TABLE IF NOT EXISTS `test_type` (
  `test` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `type` char(1) NOT NULL DEFAULT '',
  `text` tinyint(1) DEFAULT NULL,
  `image` tinyint(1) DEFAULT NULL,
  `audio` tinyint(1) DEFAULT NULL,
  `video` tinyint(1) DEFAULT NULL,
  PRIMARY KEY (`test`,`type`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- RELATIONS FOR TABLE `test_type`:
--   `test`
--       `test` -> `code`
--

--
-- Dump dei dati per la tabella `test_type`
--

INSERT INTO `test_type` (`test`, `type`, `text`, `image`, `audio`, `video`) VALUES
(1, 'a', 1, 0, 0, 0),
(1, 'q', 1, 0, 0, 0),
(2, 'a', 0, 1, 0, 0),
(2, 'q', 0, 1, 0, 0),
(3, 'a', 1, 0, 0, 0),
(3, 'q', 1, 0, 0, 0),
(4, 'a', 1, 0, 0, 0),
(4, 'q', 1, 0, 0, 0),
(5, 'a', 1, 0, 0, 0),
(5, 'q', 0, 1, 0, 0),
(6, 'a', 1, 0, 0, 0),
(6, 'q', 1, 0, 0, 0),
(7, 'a', 1, 0, 0, 0),
(7, 'q', 1, 0, 0, 0),
(8, 'q', 1, 0, 0, 0);

-- --------------------------------------------------------

--
-- Struttura della tabella `therapist`
--
-- Creazione: Set 01, 2016 alle 17:57
--

CREATE TABLE IF NOT EXISTS `therapist` (
  `id` tinyint(4) NOT NULL AUTO_INCREMENT,
  `username` varchar(20) DEFAULT NULL,
  `name` varchar(30) DEFAULT NULL,
  `surname` varchar(30) DEFAULT NULL,
  `password` varchar(64) DEFAULT NULL,
  `admin` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=3 ;

--
-- Dump dei dati per la tabella `therapist`
--

INSERT INTO `therapist` (`id`, `username`, `name`, `surname`, `password`, `admin`) VALUES
(-1, 'admin', 'admin', 'admin', 'd033e22ae348aeb5660fc2140aec35850c4da997', 1);

-- --------------------------------------------------------

--
-- Struttura della tabella `tom_a`
--
-- Creazione: Set 01, 2016 alle 17:57
--

CREATE TABLE IF NOT EXISTS `tom_a` (
  `COIDNum` int(11) NOT NULL DEFAULT '0',
  `QNum` tinyint(4) NOT NULL DEFAULT '0',
  `A1` tinyint(1) DEFAULT NULL,
  `A2` varchar(255) DEFAULT NULL,
  `docid` tinyint(4) DEFAULT NULL,
  `Verified` tinyint(1) DEFAULT NULL,
  `correct` tinyint(1) DEFAULT NULL,
  `Day` date DEFAULT NULL,
  PRIMARY KEY (`COIDNum`,`QNum`),
  KEY `QNum` (`QNum`),
  KEY `docid` (`docid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- RELATIONS FOR TABLE `tom_a`:
--   `COIDNum`
--       `tom_c` -> `idnumber`
--   `QNum`
--       `tom_ca` -> `QNum`
--   `docid`
--       `therapist` -> `id`
--

--
-- Dump dei dati per la tabella `tom_a`
--

-- --------------------------------------------------------

--
-- Struttura della tabella `tom_c`
--
-- Creazione: Set 01, 2016 alle 17:57
--

CREATE TABLE IF NOT EXISTS `tom_c` (
  `idnumber` int(11) NOT NULL AUTO_INCREMENT,
  `patient` int(10) unsigned DEFAULT NULL,
  `test` tinyint(3) unsigned DEFAULT NULL,
  `QType` tinyint(4) NOT NULL,
  `AType` tinyint(4) NOT NULL,
  `Total` tinyint(4) DEFAULT NULL,
  `Day` date DEFAULT NULL,
  `completed` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`idnumber`),
  KEY `patient` (`patient`),
  KEY `test` (`test`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=3 ;

--
-- RELATIONS FOR TABLE `tom_c`:
--   `patient`
--       `user` -> `id`
--   `test`
--       `test` -> `code`
--

--
-- Dump dei dati per la tabella `tom_c`
--

-- --------------------------------------------------------

--
-- Struttura della tabella `tom_ca`
--
-- Creazione: Set 01, 2016 alle 17:57
--

CREATE TABLE IF NOT EXISTS `tom_ca` (
  `QNum` tinyint(4) NOT NULL DEFAULT '0',
  PRIMARY KEY (`QNum`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dump dei dati per la tabella `tom_ca`
--

INSERT INTO `tom_ca` (`QNum`) VALUES
(1),
(2),
(3),
(4),
(5),
(6),
(7),
(8),
(9),
(10),
(11),
(12),
(13);

-- --------------------------------------------------------

--
-- Struttura della tabella `tom_qc`
--
-- Creazione: Set 01, 2016 alle 17:57
--

CREATE TABLE IF NOT EXISTS `tom_qc` (
  `QNum` tinyint(4) NOT NULL DEFAULT '0',
  `QType` tinyint(4) NOT NULL DEFAULT '0',
  `intro` text,
  `q1` varchar(255) DEFAULT NULL,
  `q2` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`QNum`,`QType`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- RELATIONS FOR TABLE `tom_qc`:
--   `QNum`
--       `tom_ca` -> `QNum`
--

--
-- Dump dei dati per la tabella `tom_qc`
--

INSERT INTO `tom_qc` (`QNum`, `QType`, `intro`, `q1`, `q2`) VALUES
(1, 1, 'Katia ed Emma sono due bambine che stanno giocando a casa. Emma prende una banana dal cestino della frutta e l’avvicina all’orecchio. Dice a Katia:” Guarda! Questa banana è un telefono!”', 'È vero quello che Emma ha detto?', 'Perché Emma ha detto questo?'),
(2, 1, 'Giuseppe voleva comprare un gattino, così andò a trovare la signora Rossi che possedeva molti gattini che non poteva tenere. In realtà la signora Rossi amava i gattini e non voleva che nessuno facesse loro del male, nonostante non potesse tenerli tutti lei. Giuseppe non era sicuro di volere uno dei gattini della signora Rossi. Ma la signora Rossi disse:” Se nessuno compra i gattini sarò costretta ad affogarli!”', 'È vero quello che ha detto la signora Rossi?', 'Perchè la signora Rossi ha detto questo a Giuseppe?'),
(3, 1, 'Gianni andò a casa di Chiara per la prima volta. Gianni arrivò a casa di Chiara, lei gli aprì la porta e il suo cane corse a salutarlo. Il cane di Chiara è enorme: è più o meno grande come Gianni! Quando Gianni vide l’enorme cane di Chiara disse: ”Chiara, non hai affatto un cane. Hai un elefante!”', 'È vero quello che ha detto Gianni?', 'Perché Gianni ha detto questo?'),
(4, 1, 'Un giorno, mentre stava giocando a casa, Anna accidentalmente rovesciò e ruppe il vaso di cristallo preferito di sua madre. Anna sapeva che la madre si sarebbe molto arrabbiata! Così, quando la madre ritornò a casa e vide il vaso rotto e chiese ad Anna cosa fosse successo, Anna disse:” Il cane lo ha rovesciato, non è stata colpa mia!”', 'È vero quello che Anna ha raccontato alla madre?', 'Perché Anna ha detto questo?'),
(5, 1, 'Giovanni odia andare dal dentista, perché ogni volta che ci va deve fare un’otturazione che gli fa molto male. Ma Giovanni sa che quando ha mal di denti, sua madre lo porta sempre dal dentista Adesso Giovanni ha un forte mal di dente, ma quando sua madre nota che lui sta male e gli chiede:”Hai mal di dente Giovanni?”, Giovanni risponde:” No, mamma”.', 'È vero quello che Giovanni dice a sua madre?', 'Perché Giovanni dice questo?'),
(6, 1, 'Elena aspettava tutto l’anno Natale per chiedere ai suoi genitori un coniglietto. Arrivò il giorno di Natale ed Elena corse ad aprire il suo regalo. Era sicura che contenesse un piccolo coniglio nella gabbia. Ma quando lo aprì, con tutta la famiglia che stava intorno, scoprì che il suo regalo era una noiosa enciclopedia, che Elena non desiderava affatto! Poi, quando i genitori di Elena le chiesero quanto le era piaciuto il suo regalo di Natale, lei disse: ”È bellissimo, grazie. È proprio quello che volevo”.', 'È vero quello che Elena ha detto?', 'Perché Elena ha detto questo ai suoi genitori?'),
(7, 1, 'Un giorno zia Lucia venne a visitare Pietro. Di solito a Pietro piace molto sua zia, ma quel giorno indossava un nuovo cappello che a Pietro non piaceva . Pietro pensava che sua zia sembrasse ridicola con quel cappello, e che sarebbe stata molto meglio con quello vecchio. Ma quando zia Lucia chiese a Pietro:” Ti piace il mio nuovo cappello?”, Pietro rispose:” Oh, è molto bello”.', 'È vero quello che Pietro ha detto?', 'Perché Pietro lo ha detto?'),
(8, 1, 'A notte tarda la vecchia signora Bianchi sta tornando a casa .A lei non piace camminare verso casa da sola nel buio perché è sempre preoccupata che qualcuno l’aggredisca e la derubi. Improvvisamente da un ‘ombra sbuca un uomo. Vuole chiedere alla signora Bianchi che ora è, così cammina verso di lei. Quando la signora Bianchi vede l’uomo che cammina verso di lei, comincia a tremare e dice:” Prenda la mia borsa, ma non mi faccia del male, per favore!”', 'L’uomo sarà rimasto sorpreso di quanto ha detto la signora Bianchi?', 'Perché lei gli ha detto questo, visto che lui voleva solo chiederle che ora era?'),
(9, 1, 'Un ladro che aveva appena rubato in un negozio si stava dando alla fuga. Mentre correva verso casa, un poliziotto di turno lo vide che perdeva un guanto. Il poliziotto non sapeva che l’uomo era un ladro, e voleva solo dirgli che aveva perso un guanto. Ma quando il poliziotto gridò al ladro: “Ehi, lei!Si fermi!”, il ladro si girò, vide il poliziotto e si arrese. Con le mani in alto ammise di aver compiuto il misfatto nel negozio.', 'Sarà rimasto sorpreso il poliziotto di quello che il ladro ha detto?', 'Perché il ladro si è comportato così, visto che il poliziotto voleva solo restituirgli il suo\nguanto?'),
(10, 1, 'Daniele e Luca un giorno vedono la signora Verdi che esce dal parrucchiere. Fa un po’ ridere perché il parrucchiere le ha tagliato i capelli troppo corti. Daniele dice a Luca: ” Deve esser stata in un combattimento con una falciatrice!”', 'È vero quello che dice Daniele?', 'Perché Daniele dice questo?'),
(11, 1, 'Simone è un grande bugiardo e Massimo, il fratello di Simone, sa che Simone non dice mai la verità! Proprio ieri Simone ha rubato la racchetta di ping-pong di Massimo, e Massimo sa che Simone l’ha nascosta da qualche parte, nonostante non riesca a trovarla. È molto arrabbiato. Così dice a Simone:” Dov’è la mia racchetta da ping-pong? Devi averla nascosta nell’armadio o sotto il letto, perché ho guardato ovunque. Dov’è, nell’armadio o sotto il letto? “ Simone risponde che è sotto il letto.', 'Sarà vero quello che Simone ha detto a Massimo?', 'Dove cercherà Massimo la sua racchetta da ping-pong? Perché cercherà lì la sua racchetta?'),
(12, 1, 'La madre di Anna ha trascorso molto tempo a cucinare il piatto preferito di Anna: pesce e patatine. Ma quando lo porta ad Anna, lei sta guardando la TV, non la bada e nemmeno la ringrazia. La madre di Anna è arrabbiata e dice:” Bene, questo è un bel comportamento, non trovi? Questa è per me pura maleducazione!”', 'È giusto quello che ha detto la madre di Anna?', 'Perché la madre di Anna ha detto questo?'),
(13, 1, 'Marco e Filippo si stanno divertendo! Hanno rovesciato il tavolo per terra e si sono seduti sopra. Quando la loro madre ritorna, ride e dice: ” Cosa mai state facendo? “, “Questo tavolo è una nave pirata!”, dice Filippo, ”ed è meglio che tu entri prima di affogare, perché lì sei nel mare!”', 'È vero quello che dice Filippo?', 'Perché Filippo dice questo?');

-- --------------------------------------------------------

--
-- Struttura della tabella `user`
--
-- Creazione: Set 01, 2016 alle 17:57
--

CREATE TABLE IF NOT EXISTS `user` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `username` varchar(20) NOT NULL,
  `password` varchar(64) NOT NULL,
  `birthdate` date NOT NULL,
  `sex` char(1) NOT NULL,
  `scholarity` tinyint(4) NOT NULL,
  `patient` tinyint(1) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=8 ;

--
-- Dump dei dati per la tabella `user`
--

--
-- Limiti per le tabelle scaricate
--

--
-- Limiti per la tabella `aq_a`
--
ALTER TABLE `aq_a`
  ADD CONSTRAINT `aq_a_ibfk_1` FOREIGN KEY (`COIDNum`) REFERENCES `aq_c` (`idnumber`),
  ADD CONSTRAINT `aq_a_ibfk_2` FOREIGN KEY (`QNum`) REFERENCES `aq_ca` (`QNum`),
  ADD CONSTRAINT `aq_a_ibfk_3` FOREIGN KEY (`COIDNum`) REFERENCES `aq_c` (`idnumber`),
  ADD CONSTRAINT `aq_a_ibfk_4` FOREIGN KEY (`QNum`) REFERENCES `aq_ca` (`QNum`),
  ADD CONSTRAINT `aq_a_ibfk_5` FOREIGN KEY (`COIDNum`) REFERENCES `aq_c` (`idnumber`),
  ADD CONSTRAINT `aq_a_ibfk_6` FOREIGN KEY (`QNum`) REFERENCES `aq_ca` (`QNum`),
  ADD CONSTRAINT `aq_a_ibfk_7` FOREIGN KEY (`COIDNum`) REFERENCES `aq_c` (`idnumber`),
  ADD CONSTRAINT `aq_a_ibfk_8` FOREIGN KEY (`QNum`) REFERENCES `aq_ca` (`QNum`);

--
-- Limiti per la tabella `aq_c`
--
ALTER TABLE `aq_c`
  ADD CONSTRAINT `aq_c_ibfk_1` FOREIGN KEY (`patient`) REFERENCES `user` (`id`),
  ADD CONSTRAINT `aq_c_ibfk_2` FOREIGN KEY (`test`) REFERENCES `test` (`code`),
  ADD CONSTRAINT `aq_c_ibfk_3` FOREIGN KEY (`patient`) REFERENCES `user` (`id`),
  ADD CONSTRAINT `aq_c_ibfk_4` FOREIGN KEY (`test`) REFERENCES `test` (`code`),
  ADD CONSTRAINT `aq_c_ibfk_5` FOREIGN KEY (`patient`) REFERENCES `user` (`id`),
  ADD CONSTRAINT `aq_c_ibfk_6` FOREIGN KEY (`test`) REFERENCES `test` (`code`),
  ADD CONSTRAINT `aq_c_ibfk_7` FOREIGN KEY (`patient`) REFERENCES `user` (`id`),
  ADD CONSTRAINT `aq_c_ibfk_8` FOREIGN KEY (`test`) REFERENCES `test` (`code`);

--
-- Limiti per la tabella `aq_qc`
--
ALTER TABLE `aq_qc`
  ADD CONSTRAINT `aq_qc_ibfk_1` FOREIGN KEY (`QNum`) REFERENCES `aq_ca` (`QNum`),
  ADD CONSTRAINT `aq_qc_ibfk_2` FOREIGN KEY (`QNum`) REFERENCES `aq_ca` (`QNum`);

--
-- Limiti per la tabella `attint_a`
--
ALTER TABLE `attint_a`
  ADD CONSTRAINT `attint_a_ibfk_1` FOREIGN KEY (`COIDNum`) REFERENCES `attint_c` (`idnumber`),
  ADD CONSTRAINT `attint_a_ibfk_2` FOREIGN KEY (`QNum`, `Series`) REFERENCES `attint_ca` (`QNum`, `Series`),
  ADD CONSTRAINT `attint_a_ibfk_3` FOREIGN KEY (`COIDNum`) REFERENCES `attint_c` (`idnumber`),
  ADD CONSTRAINT `attint_a_ibfk_4` FOREIGN KEY (`QNum`, `Series`) REFERENCES `attint_ca` (`QNum`, `Series`);

--
-- Limiti per la tabella `attint_ac`
--
ALTER TABLE `attint_ac`
  ADD CONSTRAINT `attint_ac_ibfk_1` FOREIGN KEY (`QNum`, `Series`) REFERENCES `attint_ca` (`QNum`, `Series`),
  ADD CONSTRAINT `attint_ac_ibfk_2` FOREIGN KEY (`QNum`, `Series`) REFERENCES `attint_ca` (`QNum`, `Series`);

--
-- Limiti per la tabella `attint_c`
--
ALTER TABLE `attint_c`
  ADD CONSTRAINT `attint_c_ibfk_1` FOREIGN KEY (`patient`) REFERENCES `user` (`id`),
  ADD CONSTRAINT `attint_c_ibfk_2` FOREIGN KEY (`test`) REFERENCES `test` (`code`),
  ADD CONSTRAINT `attint_c_ibfk_3` FOREIGN KEY (`patient`) REFERENCES `user` (`id`),
  ADD CONSTRAINT `attint_c_ibfk_4` FOREIGN KEY (`test`) REFERENCES `test` (`code`);

--
-- Limiti per la tabella `attint_qc`
--
ALTER TABLE `attint_qc`
  ADD CONSTRAINT `attint_qc_ibfk_1` FOREIGN KEY (`QNum`, `Series`) REFERENCES `attint_ca` (`QNum`, `Series`),
  ADD CONSTRAINT `attint_qc_ibfk_2` FOREIGN KEY (`QNum`, `Series`) REFERENCES `attint_ca` (`QNum`, `Series`);

--
-- Limiti per la tabella `bes_a`
--
ALTER TABLE `bes_a`
  ADD CONSTRAINT `bes_a_ibfk_1` FOREIGN KEY (`COIDNum`) REFERENCES `bes_c` (`idnumber`),
  ADD CONSTRAINT `bes_a_ibfk_6` FOREIGN KEY (`QNum`) REFERENCES `bes_ca` (`QNum`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `bes_a_ibfk_7` FOREIGN KEY (`COIDNum`) REFERENCES `bes_c` (`idnumber`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `bes_a_ibfk_8` FOREIGN KEY (`QNum`) REFERENCES `bes_ca` (`QNum`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Limiti per la tabella `bes_c`
--
ALTER TABLE `bes_c`
  ADD CONSTRAINT `bes_c_ibfk_1` FOREIGN KEY (`patient`) REFERENCES `user` (`id`),
  ADD CONSTRAINT `bes_c_ibfk_2` FOREIGN KEY (`test`) REFERENCES `test` (`code`),
  ADD CONSTRAINT `bes_c_ibfk_3` FOREIGN KEY (`patient`) REFERENCES `user` (`id`),
  ADD CONSTRAINT `bes_c_ibfk_4` FOREIGN KEY (`test`) REFERENCES `test` (`code`);

--
-- Limiti per la tabella `bes_qc`
--
ALTER TABLE `bes_qc`
  ADD CONSTRAINT `bes_qc_ibfk_1` FOREIGN KEY (`QNum`) REFERENCES `bes_ca` (`QNum`),
  ADD CONSTRAINT `bes_qc_ibfk_2` FOREIGN KEY (`QNum`) REFERENCES `bes_ca` (`QNum`);

--
-- Limiti per la tabella `emotatt_a`
--
ALTER TABLE `emotatt_a`
  ADD CONSTRAINT `emotatt_a_ibfk_1` FOREIGN KEY (`COIDNum`) REFERENCES `emotatt_c` (`idnumber`),
  ADD CONSTRAINT `emotatt_a_ibfk_2` FOREIGN KEY (`QNum`) REFERENCES `emotatt_ca` (`QNum`),
  ADD CONSTRAINT `emotatt_a_ibfk_3` FOREIGN KEY (`docid`) REFERENCES `therapist` (`id`),
  ADD CONSTRAINT `emotatt_a_ibfk_4` FOREIGN KEY (`COIDNum`) REFERENCES `emotatt_c` (`idnumber`),
  ADD CONSTRAINT `emotatt_a_ibfk_5` FOREIGN KEY (`QNum`) REFERENCES `emotatt_ca` (`QNum`),
  ADD CONSTRAINT `emotatt_a_ibfk_6` FOREIGN KEY (`docid`) REFERENCES `therapist` (`id`);

--
-- Limiti per la tabella `emotatt_c`
--
ALTER TABLE `emotatt_c`
  ADD CONSTRAINT `emotatt_c_ibfk_1` FOREIGN KEY (`patient`) REFERENCES `user` (`id`),
  ADD CONSTRAINT `emotatt_c_ibfk_2` FOREIGN KEY (`test`) REFERENCES `test` (`code`),
  ADD CONSTRAINT `emotatt_c_ibfk_3` FOREIGN KEY (`patient`) REFERENCES `user` (`id`),
  ADD CONSTRAINT `emotatt_c_ibfk_4` FOREIGN KEY (`test`) REFERENCES `test` (`code`);

--
-- Limiti per la tabella `emotatt_qc`
--
ALTER TABLE `emotatt_qc`
  ADD CONSTRAINT `emotatt_qc_ibfk_1` FOREIGN KEY (`QNum`) REFERENCES `emotatt_ca` (`QNum`),
  ADD CONSTRAINT `emotatt_qc_ibfk_2` FOREIGN KEY (`QNum`) REFERENCES `emotatt_ca` (`QNum`);

--
-- Limiti per la tabella `ext_user`
--
ALTER TABLE `ext_user`
  ADD CONSTRAINT `ext_user_ibfk_1` FOREIGN KEY (`id`) REFERENCES `user` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `ext_user_ibfk_2` FOREIGN KEY (`id`) REFERENCES `user` (`id`) ON DELETE CASCADE;

--
-- Limiti per la tabella `eyestask_a`
--
ALTER TABLE `eyestask_a`
  ADD CONSTRAINT `eyestask_a_ibfk_1` FOREIGN KEY (`COIDNum`) REFERENCES `eyestask_c` (`idnumber`),
  ADD CONSTRAINT `eyestask_a_ibfk_2` FOREIGN KEY (`QNum`) REFERENCES `eyestask_ca` (`QNum`),
  ADD CONSTRAINT `eyestask_a_ibfk_3` FOREIGN KEY (`COIDNum`) REFERENCES `eyestask_c` (`idnumber`),
  ADD CONSTRAINT `eyestask_a_ibfk_4` FOREIGN KEY (`QNum`) REFERENCES `eyestask_ca` (`QNum`);

--
-- Limiti per la tabella `eyestask_ac`
--
ALTER TABLE `eyestask_ac`
  ADD CONSTRAINT `eyestask_ac_ibfk_1` FOREIGN KEY (`QNum`) REFERENCES `eyestask_ca` (`QNum`),
  ADD CONSTRAINT `eyestask_ac_ibfk_2` FOREIGN KEY (`QNum`) REFERENCES `eyestask_ca` (`QNum`);

--
-- Limiti per la tabella `eyestask_c`
--
ALTER TABLE `eyestask_c`
  ADD CONSTRAINT `eyestask_c_ibfk_1` FOREIGN KEY (`patient`) REFERENCES `user` (`id`),
  ADD CONSTRAINT `eyestask_c_ibfk_2` FOREIGN KEY (`test`) REFERENCES `test` (`code`),
  ADD CONSTRAINT `eyestask_c_ibfk_3` FOREIGN KEY (`patient`) REFERENCES `user` (`id`),
  ADD CONSTRAINT `eyestask_c_ibfk_4` FOREIGN KEY (`test`) REFERENCES `test` (`code`);

--
-- Limiti per la tabella `eyestask_qc`
--
ALTER TABLE `eyestask_qc`
  ADD CONSTRAINT `eyestask_qc_ibfk_1` FOREIGN KEY (`QNum`) REFERENCES `eyestask_ca` (`QNum`),
  ADD CONSTRAINT `eyestask_qc_ibfk_2` FOREIGN KEY (`QNum`) REFERENCES `eyestask_ca` (`QNum`);

--
-- Limiti per la tabella `help`
--
ALTER TABLE `help`
  ADD CONSTRAINT `help_ibfk_1` FOREIGN KEY (`test`) REFERENCES `test` (`code`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `help_ibfk_2` FOREIGN KEY (`test`) REFERENCES `test` (`code`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Limiti per la tabella `patient`
--
ALTER TABLE `patient`
  ADD CONSTRAINT `patient_ibfk_1` FOREIGN KEY (`id`) REFERENCES `user` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `patient_ibfk_2` FOREIGN KEY (`cg`) REFERENCES `clinicalg` (`code`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `patient_ibfk_3` FOREIGN KEY (`id`) REFERENCES `user` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `patient_ibfk_4` FOREIGN KEY (`cg`) REFERENCES `clinicalg` (`code`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Limiti per la tabella `patient_tests`
--
ALTER TABLE `patient_tests`
  ADD CONSTRAINT `patient_tests_ibfk_1` FOREIGN KEY (`id`) REFERENCES `patient` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `patient_tests_ibfk_2` FOREIGN KEY (`id`) REFERENCES `patient` (`id`) ON DELETE CASCADE;

--
-- Limiti per la tabella `qe_a`
--
ALTER TABLE `qe_a`
  ADD CONSTRAINT `qe_a_ibfk_1` FOREIGN KEY (`COIDNum`) REFERENCES `qe_c` (`idnumber`),
  ADD CONSTRAINT `qe_a_ibfk_2` FOREIGN KEY (`QNum`) REFERENCES `qe_ca` (`QNum`),
  ADD CONSTRAINT `qe_a_ibfk_3` FOREIGN KEY (`COIDNum`) REFERENCES `qe_c` (`idnumber`),
  ADD CONSTRAINT `qe_a_ibfk_4` FOREIGN KEY (`QNum`) REFERENCES `qe_ca` (`QNum`);

--
-- Limiti per la tabella `qe_c`
--
ALTER TABLE `qe_c`
  ADD CONSTRAINT `qe_c_ibfk_1` FOREIGN KEY (`patient`) REFERENCES `user` (`id`),
  ADD CONSTRAINT `qe_c_ibfk_2` FOREIGN KEY (`test`) REFERENCES `test` (`code`),
  ADD CONSTRAINT `qe_c_ibfk_3` FOREIGN KEY (`patient`) REFERENCES `user` (`id`),
  ADD CONSTRAINT `qe_c_ibfk_4` FOREIGN KEY (`test`) REFERENCES `test` (`code`);

--
-- Limiti per la tabella `qe_qc`
--
ALTER TABLE `qe_qc`
  ADD CONSTRAINT `qe_qc_ibfk_1` FOREIGN KEY (`QNum`) REFERENCES `qe_ca` (`QNum`),
  ADD CONSTRAINT `qe_qc_ibfk_2` FOREIGN KEY (`QNum`) REFERENCES `qe_ca` (`QNum`);

--
-- Limiti per la tabella `socialsit_a`
--
ALTER TABLE `socialsit_a`
  ADD CONSTRAINT `socialsit_a_ibfk_1` FOREIGN KEY (`COIDNum`) REFERENCES `socialsit_c` (`idnumber`),
  ADD CONSTRAINT `socialsit_a_ibfk_2` FOREIGN KEY (`QNum`, `SubQNum`) REFERENCES `socialsit_ca` (`QNum`, `SubQNum`),
  ADD CONSTRAINT `socialsit_a_ibfk_3` FOREIGN KEY (`COIDNum`) REFERENCES `socialsit_c` (`idnumber`),
  ADD CONSTRAINT `socialsit_a_ibfk_4` FOREIGN KEY (`QNum`, `SubQNum`) REFERENCES `socialsit_ca` (`QNum`, `SubQNum`);

--
-- Limiti per la tabella `socialsit_c`
--
ALTER TABLE `socialsit_c`
  ADD CONSTRAINT `socialsit_c_ibfk_1` FOREIGN KEY (`patient`) REFERENCES `user` (`id`),
  ADD CONSTRAINT `socialsit_c_ibfk_2` FOREIGN KEY (`test`) REFERENCES `test` (`code`),
  ADD CONSTRAINT `socialsit_c_ibfk_3` FOREIGN KEY (`patient`) REFERENCES `user` (`id`),
  ADD CONSTRAINT `socialsit_c_ibfk_4` FOREIGN KEY (`test`) REFERENCES `test` (`code`);

--
-- Limiti per la tabella `socialsit_qc`
--
ALTER TABLE `socialsit_qc`
  ADD CONSTRAINT `socialsit_qc_ibfk_1` FOREIGN KEY (`QNum`, `SubQNum`) REFERENCES `socialsit_ca` (`QNum`, `SubQNum`),
  ADD CONSTRAINT `socialsit_qc_ibfk_2` FOREIGN KEY (`QNum`, `SubQNum`) REFERENCES `socialsit_ca` (`QNum`, `SubQNum`);

--
-- Limiti per la tabella `test_type`
--
ALTER TABLE `test_type`
  ADD CONSTRAINT `test_type_ibfk_1` FOREIGN KEY (`test`) REFERENCES `test` (`code`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `test_type_ibfk_2` FOREIGN KEY (`test`) REFERENCES `test` (`code`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Limiti per la tabella `tom_a`
--
ALTER TABLE `tom_a`
  ADD CONSTRAINT `tom_a_ibfk_1` FOREIGN KEY (`COIDNum`) REFERENCES `tom_c` (`idnumber`),
  ADD CONSTRAINT `tom_a_ibfk_2` FOREIGN KEY (`QNum`) REFERENCES `tom_ca` (`QNum`),
  ADD CONSTRAINT `tom_a_ibfk_3` FOREIGN KEY (`docid`) REFERENCES `therapist` (`id`),
  ADD CONSTRAINT `tom_a_ibfk_4` FOREIGN KEY (`COIDNum`) REFERENCES `tom_c` (`idnumber`),
  ADD CONSTRAINT `tom_a_ibfk_5` FOREIGN KEY (`QNum`) REFERENCES `tom_ca` (`QNum`),
  ADD CONSTRAINT `tom_a_ibfk_6` FOREIGN KEY (`docid`) REFERENCES `therapist` (`id`);

--
-- Limiti per la tabella `tom_c`
--
ALTER TABLE `tom_c`
  ADD CONSTRAINT `tom_c_ibfk_1` FOREIGN KEY (`patient`) REFERENCES `user` (`id`),
  ADD CONSTRAINT `tom_c_ibfk_2` FOREIGN KEY (`test`) REFERENCES `test` (`code`),
  ADD CONSTRAINT `tom_c_ibfk_3` FOREIGN KEY (`patient`) REFERENCES `user` (`id`),
  ADD CONSTRAINT `tom_c_ibfk_4` FOREIGN KEY (`test`) REFERENCES `test` (`code`);

--
-- Limiti per la tabella `tom_qc`
--
ALTER TABLE `tom_qc`
  ADD CONSTRAINT `tom_qc_ibfk_1` FOREIGN KEY (`QNum`) REFERENCES `tom_ca` (`QNum`),
  ADD CONSTRAINT `tom_qc_ibfk_2` FOREIGN KEY (`QNum`) REFERENCES `tom_ca` (`QNum`);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

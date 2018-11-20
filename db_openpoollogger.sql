CREATE TABLE IF NOT EXISTS `employers` (
  `eid` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `fullname` varchar(128) NOT NULL,
  `username` varchar(32) NOT NULL,
  `password` varchar(40) NOT NULL,
  `email` varchar(255) NOT NULL,
  `admin` tinyint(1) NOT NULL default '0',
  `record_datetime` datetime NOT NULL,
  `modififed_datetime` datetime NOT NULL,
  PRIMARY KEY  (`eid`),
  UNIQUE KEY `eid` (`eid`),
  UNIQUE KEY `email` (`email`)
);

CREATE TABLE IF NOT EXISTS `log_type` (
  `tid` tinyint UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(32),
  `record_time` datetime NOT NULL,
  PRIMARY KEY  (`tid`),
  UNIQUE KEY `tid` (`tid`)
);

CREATE TABLE IF NOT EXISTS `logs` (
  `lid` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `cl` float UNSIGNED,
  `dpd3` float UNSIGNED,
  `ph` float UNSIGNED,
  `temp` float UNSIGNED,
  `maq` int UNSIGNED, -- adicionar o registo de adição de cloro!!!
  `record_time` datetime NOT NULL,
  `log_owner` bigint(20) UNSIGNED NOT NULL,
  `log_type` tinyint UNSIGNED NOT NULL,
  PRIMARY KEY  (`lid`),
  UNIQUE KEY `lid` (`lid`),
  FOREIGN KEY (`log_owner`) REFERENCES employers(`eid`),
  FOREIGN KEY (`log_type`) REFERENCES log_type(`tid`)
);

CREATE TABLE IF NOT EXISTS `settings` (
  `sid` tinyint UNSIGNED NOT NULL AUTO_INCREMENT,
  `entity` varchar(32),
  `entity_url` varchar(256),
  `lang` varchar(5),
  `record_time` datetime NOT NULL,
  PRIMARY KEY  (`sid`),
  UNIQUE KEY `sid` (`sid`)
);

CREATE TABLE IF NOT EXISTS `settings_param` (
  `spid` tinyint UNSIGNED NOT NULL AUTO_INCREMENT,
  `param` varchar(12),
  `param_min` float UNSIGNED,
  `param_max` float UNSIGNED,
  `record_time` datetime NOT NULL,
  PRIMARY KEY  (`spid`),
  UNIQUE KEY `spid` (`spid`)
);



INSERT INTO `employers` (`eid`, `fullname`, `username`, `password`, `email`, `admin`, `record_datetime`, `modififed_datetime`) VALUES
/* ( NULL, 'administrador', 'admin', sha1('teste'), 'admin@pool.lan', '1', NOW() ), */
( NULL, 'Nuno Fidalgo', 'Fidalgo', sha1('admin#2018'), 'nunorfidalgo@gmail.com', '1', NOW(), NOW() ),
( NULL, 'Liliana Sousa', 'Liliana', sha1('piscina#2018'), 'lilianasousa@goodfit.pt', '1', NOW(), NOW() ),
( NULL, 'Fabio Direito', 'Fabio', sha1('piscina#2018'), 'fabiodireito@goodfit.pt', '1', NOW(), NOW() ),
( NULL, 'Nuno Ferreira', 'Nuno', sha1('piscina#2018'), 'nunoferreira@goodfit.pt', '1', NOW(), NOW() ),
( NULL, 'Moisés Direito', 'Moisés', sha1('piscina#2018'), 'moisesdireito@goodfit.pt', '0', NOW(), NOW() ),
( NULL, 'Marisa Sa', 'Marisa', sha1('piscina#2018'), 'marisasa@goodfit.pt', '0', NOW(), NOW() );

INSERT INTO `log_type` (`tid`, `name`, `record_time`) VALUES
( NULL, 'Piscina', NOW() ),
( NULL, 'Jacuzzi', NOW() );

INSERT INTO `settings` (`sid`, `entity`, `entity_url`, `lang`, `record_time`) VALUES
( NULL, 'Good Fit, Lda', 'https://www.goodfit.pt', 'pt-pt', NOW() );

INSERT INTO `settings_param` (`spid`, `param`, `param_min`, `param_max`, `record_time`) VALUES
( NULL, 'cl', '0.00', '2.00', NOW() ),
( NULL, 'dpd3', '0.00', '2.00', NOW() ),
( NULL, 'ph', '0.00', '14.00', NOW() ),
( NULL, 'temp', '0.00', '50.00', NOW() ),
( NULL, 'maq', '1', '999', NOW() );


/*
cloro -> chlorine
cloramina -> chloramine
*/

/* PHP */

$host="127.0.0.1";
$port=3306;
$socket="";
$user="pool";
$password="wbbmZ5dJJOP2eLXx";
$dbname="openpoollog";

$con = new mysqli($host, $user, $password, $dbname, $port, $socket)
	or die ('Could not connect to the database server' . mysqli_connect_error());

//$con->close();

$query = "";

if ($stmt = $con->prepare($query)) {
    $stmt->execute();
    $stmt->bind_result($field1, $field2);
    while ($stmt->fetch()) {
        //printf("%s, %s\n", $field1, $field2);
    }
    $stmt->close();
}

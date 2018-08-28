--- cloro -> chlorine
--- cloramina -> chloramine

/* CREATE TABLE IF NOT EXISTS `funcionarios` (
  `fid` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `fullname` varchar(128) NOT NULL,
  `username` varchar(32) NOT NULL,
  `password` varchar(40) NOT NULL,
  `email` varchar(255) NOT NULL,
  `admin` tinyint(1) NOT NULL default '0',
  `datahora` datetime NOT NULL,
  PRIMARY KEY  (`fid`),
  UNIQUE KEY `fid` (`fid`)
);

CREATE TABLE IF NOT EXISTS `parametros` (
  `pid` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `cloro` float,
  `dpd3` float,
  `ph` float,
  `temperatura` float,
  `maq` int,
  `datahora` datetime NOT NULL,
  `responsavel` bigint(20) UNSIGNED NOT NULL,
  PRIMARY KEY  (`pid`),
  UNIQUE KEY `pid` (`pid`),
  FOREIGN KEY (`responsavel`) REFERENCES funcionarios(`fid`)
); */

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

CREATE TABLE IF NOT EXISTS `logs` (
  `lid` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `cl` float UNSIGNED,
  `dpd3` float UNSIGNED,
  `ph` float UNSIGNED,
  `temp` float UNSIGNED,
  `maq` int UNSIGNED,
-- adicionar o registo de adição de cloro!!!
  `record_time` datetime NOT NULL,
  `log_owner` bigint(20) UNSIGNED NOT NULL,
  `log_type` tinyint UNSIGNED NOT NULL,
  PRIMARY KEY  (`plid`),
  UNIQUE KEY `lid` (`lid`),
  FOREIGN KEY (`log_owner`) REFERENCES employers(`eid`),
  FOREIGN KEY (`log_type`) REFERENCES log_type(`tid`)
);

CREATE TABLE IF NOT EXISTS `log_type` (
  `tid` tinyint UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(32),
  `record_time` datetime NOT NULL,
  PRIMARY KEY  (`tid`),
  UNIQUE KEY `tid` (`tid`)
);

CREATE TABLE IF NOT EXISTS `settings` (
  `sid` tinyint UNSIGNED NOT NULL AUTO_INCREMENT,
  `entity` varchar(32),
  `lang` tinyint,
  `record_time` datetime NOT NULL,
  PRIMARY KEY  (`sid`),
  UNIQUE KEY `sid` (`sid`)
);

INSERT INTO `employers` (`eid`, `fullname`, `username`, `password`, `email`, `admin`, `timedate`) VALUES
-- ( NULL, 'administrador', 'admin', sha1('teste'), 'admin@pool.lan', '1', NOW() ),
( NULL, 'Nuno Fidalgo', 'Fidalgo', sha1('admin#2018'), 'nunorfidalgo@gmail.com', '1', NOW() ),
( NULL, 'Liliana Sousa', 'Liliana', sha1('piscina#2018'), 'lilianasousa@goodfit.pt', '1', NOW() ),
( NULL, 'Fabio Direito', 'Fabio', sha1('piscina#2018'), 'fabiodireito@goodfit.pt', '1', NOW() ),
( NULL, 'Nuno Ferreira', 'Nuno', sha1('piscina#2018'), 'nunoferreira@goodfit.pt', '1', NOW() ),
( NULL, 'Moisés Direito', 'Moisés', sha1('piscina#2018'), 'moisesdireito@goodfit.pt', '0', NOW() ),
( NULL, 'Marisa Sa', 'Marisa', sha1('piscina#2018'), 'marisasa@goodfit.pt', '0', NOW() ),

INSERT INTO `log_type` (`tid`, `name`, `record_time`) VALUES
( NULL, 'Piscina', NOW() ),
( NULL, 'Jacuzzi', NOW() );

-- INSERT INTO `logs` (`lid`, `cl`, `dpd3`, `ph`, `temp`, `maq`, `timedate`, `log_owner`, `log_type`) VALUES
-- (NULL, '1.05', '2.30', '7.66', '30', '479', NOW(), '2', '1'),
-- (NULL, '0.20', '0.54', '7.44', '30', NULL, NOW(), '2', '1'),
-- (NULL, '0.50', '1.71', '7.48', '30', '453', NOW(), '2', '1'),
-- (NULL, '0.70', '2.22', '7.56', '30', '516', NOW(), '2', '1'),
-- (NULL, '0.70', '0.97', '7.54', '30', '516', NOW(), '2', '1'),
-- (NULL, '8.98', '0.22', '6.32', '14', '800', NOW(), '2', '2');

INSERT INTO `settings` (`sid`, `entity`, `lang`, `record_time`) VALUES
( NULL, 'Good Fit, Lda', '1', NOW() );









--- PHP

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

CREATE TABLE `alerts` (
  `Id` int(11) NOT NULL AUTO_INCREMENT,
  `idCompany` int(11) DEFAULT NULL,
  `isEmSMSNotAtWork` varchar(255) DEFAULT NULL,
  `isEmEMLNotAtWork` varchar(255) DEFAULT NULL,
  `timeNotAtWork` varchar(255) DEFAULT NULL,
  `msgNotAtWork` varchar(255) DEFAULT NULL,
  `isEmSMSDelay` varchar(255) DEFAULT NULL,
  `isEmEMLDelay` varchar(255) DEFAULT NULL,
  `isBrSMSDelay` varchar(255) DEFAULT NULL,
  `isBrEMLDelay` varchar(255) DEFAULT NULL,
  `isDeSMSDelay` varchar(255) DEFAULT NULL,
  `isDeEMLDelay` varchar(255) DEFAULT NULL,
  `timeDelay` varchar(255) DEFAULT NULL,
  `msgDelay` varchar(255) DEFAULT NULL,
  `isEmSMSChange` varchar(255) DEFAULT NULL,
  `isEmEMLChange` varchar(255) DEFAULT NULL,
  `isBrSMSChange` varchar(255) DEFAULT NULL,
  `isBrEMLChange` varchar(255) DEFAULT NULL,
  `isDeSMSChange` varchar(255) DEFAULT NULL,
  `isDeEMLChange` varchar(255) DEFAULT NULL,
  `msgChang` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`Id`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4;


CREATE TABLE `branches` (
  `Id` int(11) NOT NULL AUTO_INCREMENT,
  `idTreeInfo` int(11) DEFAULT NULL,
  `strName` varchar(255) DEFAULT NULL,
  `regNumber` varchar(255) DEFAULT NULL,
  `regAddress` varchar(255) DEFAULT NULL,
  `others` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`Id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4;


CREATE TABLE `company` (
  `Id` int(11) NOT NULL AUTO_INCREMENT,
  `idTreeInfo` int(11) DEFAULT NULL,
  `strName` varchar(255) DEFAULT NULL,
  `regAddress` varchar(255) DEFAULT NULL,
  `offAddress` varchar(255) DEFAULT NULL,
  `regNumber` varchar(255) DEFAULT NULL,
  `VATNumber` varchar(255) DEFAULT NULL,
  `others` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`Id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4;


CREATE TABLE `department` (
  `Id` int(11) NOT NULL AUTO_INCREMENT,
  `idTreeInfo` int(11) DEFAULT NULL,
  `strName` varchar(255) DEFAULT NULL,
  `others` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`Id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4;


CREATE TABLE `employee` (
  `Id` int(11) NOT NULL AUTO_INCREMENT,
  `idTreeInfo` int(11) DEFAULT NULL,
  `strName` varchar(255) DEFAULT NULL,
  `SurName` varchar(255) DEFAULT NULL,
  `Code` varchar(255) DEFAULT NULL,
  `Address` varchar(255) DEFAULT NULL,
  `PhoneNumber` varchar(255) DEFAULT NULL,
  `Email` varchar(255) DEFAULT NULL,
  `NFCNumber` varchar(255) DEFAULT NULL,
  `idPosts` int(11) DEFAULT NULL,
  `others` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`Id`)
) ENGINE=InnoDB AUTO_INCREMENT=37 DEFAULT CHARSET=utf8mb4;


CREATE TABLE `employeeabsent` (
  `Id` int(11) NOT NULL AUTO_INCREMENT,
  `idEmployee` int(11) DEFAULT NULL,
  `dateAbsent` date DEFAULT NULL,
  `Type` varchar(255) DEFAULT NULL,
  `idReason` int(11) DEFAULT NULL,
  `others` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`Id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;


CREATE TABLE `employeevacation` (
  `Id` int(11) NOT NULL AUTO_INCREMENT,
  `idEmployee` int(11) DEFAULT NULL,
  `idVacation` int(11) DEFAULT NULL,
  `strPeriod` varchar(255) DEFAULT NULL,
  `others` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`Id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4;


CREATE TABLE `nfccard` (
  `Id` int(11) NOT NULL AUTO_INCREMENT,
  `ActionTime` datetime DEFAULT NULL,
  `CardNumber` varchar(255) DEFAULT NULL,
  `others` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`Id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;


CREATE TABLE `posts` (
  `Id` int(11) NOT NULL AUTO_INCREMENT,
  `idTreeInfo` int(11) DEFAULT NULL,
  `strCode` varchar(255) DEFAULT NULL,
  `strProfession` varchar(255) DEFAULT NULL,
  `strDetails` text,
  `others` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`Id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4;


CREATE TABLE `schedule` (
  `Id` int(11) NOT NULL AUTO_INCREMENT,
  `idNode` int(11) DEFAULT NULL,
  `nodeType` varchar(30) DEFAULT NULL,
  `ScheduleType` varchar(255) DEFAULT NULL,
  `FieldDays` varchar(255) DEFAULT NULL,
  `FieldTime` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`Id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4;


CREATE TABLE `treeinfo` (
  `Id` int(11) NOT NULL AUTO_INCREMENT,
  `strName` varchar(255) DEFAULT NULL,
  `Category` varchar(255) DEFAULT NULL,
  `idParents` int(11) DEFAULT '0',
  `others` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`Id`)
) ENGINE=InnoDB AUTO_INCREMENT=22 DEFAULT CHARSET=utf8mb4;


CREATE TABLE `user` (
  `Id` int(11) NOT NULL AUTO_INCREMENT,
  `UserName` varchar(255) DEFAULT NULL,
  `UserMail` varchar(255) DEFAULT NULL,
  `Password` varchar(255) DEFAULT NULL,
  `VerifyCode` varchar(255) DEFAULT NULL,
  `VerifyStates` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`Id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;


CREATE TABLE `vacation` (
  `Id` int(11) NOT NULL AUTO_INCREMENT,
  `idTreeInfo` int(11) DEFAULT NULL,
  `strName` varchar(255) DEFAULT NULL,
  `strDetails` text,
  `others` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`Id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4;

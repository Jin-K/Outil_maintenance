-- phpMyAdmin SQL Dump
-- version 4.1.14
-- http://www.phpmyadmin.net
--
-- Client :  127.0.0.1
-- Généré le :  Mar 28 Avril 2015 à 01:06
-- Version du serveur :  5.6.17
-- Version de PHP :  5.5.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Base de données :  `maintenance`
--

DELIMITER $$
--
-- Fonctions
--
DROP FUNCTION IF EXISTS `ajoutUser`$$
CREATE DEFINER=`root`@`localhost` FUNCTION `ajoutUser`(`login` VARCHAR(10), `loginBO` VARCHAR(15), `pw` VARCHAR(50)) RETURNS int(11)
begin

declare v_ret int;
declare v_ret_loginBO int;

select count(*) into v_ret from user u where u.login like login;
select count(*) into v_ret_loginBO from user u where u.loginBackOffice like loginBO;

if v_ret=0 and v_ret_loginBO=0
then
	insert into user values (null,login,MD5(pw),loginBO,0);
	set v_ret = last_insert_id();
else
	if v_ret=0
	then
		set v_ret = -2;
	else
		set v_ret=-1;
	end if;
end if;

return v_ret;

end$$

DROP FUNCTION IF EXISTS `modifMaintenance`$$
CREATE DEFINER=`root`@`localhost` FUNCTION `modifMaintenance`(`idBox` INT, `idStep` INT, `idLastUser` INT) RETURNS int(11)
begin

declare v_ret int;

select count(*) into v_ret from maintenance m where m.idBox = idBox;

if v_ret = 0
then
	insert into maintenance values (idBox, idStep, idLastUser);
    set v_ret = idBox;
else
	update maintenance m SET m.idStep = idStep, m.idLastUser = idLastUser WHERE m.idBox = idBox;
    set v_ret = 0;
end if;

return v_ret;
end$$

DELIMITER ;

-- --------------------------------------------------------

--
-- Structure de la table `box`
--

DROP TABLE IF EXISTS `box`;
CREATE TABLE IF NOT EXISTS `box` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(25) NOT NULL,
  `model` varchar(50) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=5 ;

--
-- Contenu de la table `box`
--

INSERT INTO `box` (`id`, `name`, `model`) VALUES
(1, 'FRBE_SBM_022', 'Dell XPS 18'),
(2, 'FRBE_SBN_17', 'Samsung'),
(3, 'FRBE_SBM_013', 'Dell XPS 18'),
(4, 'FRBE_SBM_120', 'Dell XPS 18');

-- --------------------------------------------------------

--
-- Structure de la table `help`
--

DROP TABLE IF EXISTS `help`;
CREATE TABLE IF NOT EXISTS `help` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `idHelp` int(11) NOT NULL,
  `idStep` int(11) NOT NULL,
  `text` text NOT NULL,
  `urlImage` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=113 ;

--
-- Contenu de la table `help`
--

INSERT INTO `help` (`id`, `idHelp`, `idStep`, `text`, `urlImage`) VALUES
(4, 1, 2, 'Go to the "Control Panel" > "Clock, Language and region", > "Language", click on "Add a language".', 'img/help2/slide1.jpg'),
(5, 2, 2, 'Select "English"', 'img/help2/slide2.jpg'),
(6, 3, 2, 'Select "English (United States)"', 'img/help2/slide3.jpg'),
(7, 4, 2, 'In the list of languages, select "English". Then, click on "Options".', 'img/help2/slide4.jpg'),
(8, 5, 2, 'In the window, there is a "Windows Administration icon". Click on it to download and install the whole English language pack. A new window opens and shows a loading bar until the installation is complete.', 'img/help2/slide5.jpg'),
(9, 1, 3, 'Go to the "Control Panel" > "Clock, Language and region" > "Date and Time". Check if the displayed date and time are correct.', 'img/help3/slide1.jpg'),
(10, 2, 3, 'Check as well if the location is correct.', 'img/help3/slide2.jpg'),
(11, 1, 4, 'Go to "Control Panel" > "Programs" > "Uninstall a program", you will see the list of all the installed programs. Spot the antiviruses, uninstall them by clicking on "Uninstall", and wait for each uninstallation to be completed.', 'img/help4/slide1.jpg'),
(12, 1, 5, 'First, in the "Control panel", go to System and Security > Windows Update > Change settings. Under "important updates", in the dropdown menu, choose "Check for updates but let me choose ...". To validate, click on "OK".', 'img/help5/slide1.jpg'),
(13, 2, 5, 'On the left side of the window, click on "Check for updates", wait a few seconds. Then click on "Install updates".', 'img/help5/slide2.jpg'),
(14, 3, 5, 'Once all the updates are done, restart the computer. After restarting, come back to the "Windows Updates" to check if other important updates are available.', 'img/help5/slide3.jpg'),
(15, 1, 6, 'From the Windows "Start" menu, launch "Windows Defender". Check if updates of this software are available (under the Update tab). If so, do the update.', 'img/help6/slide1.jpg'),
(16, 2, 6, 'Get back to the first tab of "Windows Defender", choose "Quick scan", then click on "Analyse now". Once the analysis is done, close the software.', 'img/help6/slide2.jpg'),
(17, 1, 7, 'In the "Control Panel" > "System and Security" > "Action Center", deactivate the "Windows Update" and all the "Maintenance" messages (except "Device Software").', 'img/help7/slide1.jpg'),
(18, 1, 8, 'Go to "Control Panel" > "Hardware and Sound" > "Power options". Choose "High Performance". Then, click on "Change plan settings".', 'img/help8/slide1.jpg'),
(19, 2, 8, 'Change the settings for the ones above : Never "Turn off the display" ; Never "Put the computer to sleep". Then, click on "Change advanced power settings".', 'img/help8/slide2.jpg'),
(20, 3, 8, 'First click on "Change settings that are currently unavailable", then set "Require a password on wakeup" to "No" twice.', 'img/help8/slide3.jpg'),
(21, 4, 8, 'Make sure the machine power is still "High performance", then set "Turn off hard disk after" to "Never".', 'img/help8/slide4.jpg'),
(22, 5, 8, 'Then, go down to "Sleep" settings, set "Sleep after" on "Never" and "Allow hybrid sleep" to "Off".', 'img/help8/slide5.jpg'),
(23, 6, 8, 'Still in the "Sleep" settings, set "Hibernate after" to "Never" and "Allow wake timers" to "Disable".', 'img/help8/slide6.jpg'),
(24, 7, 8, 'Below, set the "USB selective suspend settings" to "Disabled".', 'img/help8/slide7.jpg'),
(25, 8, 8, 'For the "Power buttons", set "Power button action" to "Shut down" and the "Sleep button action" to "Do nothing".', 'img/help8/slide8.jpg'),
(26, 9, 8, 'Under "Processor power management", set the value to "100%" for the "Minimum/Maximum processor state".', 'img/help8/slide9.jpg'),
(27, 10, 8, 'In the "Display" settings set "Turn off display after" to "Never" and "Display brightness" to "100%".', 'img/help8/slide10.jpg'),
(28, 11, 8, 'Under "Display", set "Dimmed display brightness" to "100%" and "Enable adaptive brightness" to "Off".', 'img/help8/slide11.jpg'),
(29, 12, 8, 'If you are working on a Samsung screen, go to the "Control Panel" > "Power options" and choose "High Performance" plan. Set "Turn off display" and "Put the computer to sleep" to "Never". Then, click on "Change advanced power settings".', 'img/help8/slide12.jpg'),
(30, 13, 8, 'First click on "Change settings that are currently unavailable", then set "Require a password on wakeup" to "No".', 'img/help8/slide13.jpg'),
(31, 14, 8, 'Set "Turn off hard disk after" to "Never".', 'img/help8/slide14.jpg'),
(32, 15, 8, 'Set the "Sleep" settings to "Never"/"Off"/"Disable" as shown on the screenshot.', 'img/help8/slide15.jpg'),
(33, 16, 8, 'In the "USB settings" : set "USB selective suspend settings" to "Disabled". In the "Power buttons and lid settings" : set "Power button action to "Shut down" and "Sleep button action" to "Do nothing".', 'img/help8/slide16.jpg'),
(34, 17, 8, 'Set the "Minimum/Maximum processor state" to "100%".', 'img/help8/slide17.jpg'),
(35, 18, 8, 'For the "Display settings" : "Turn off display after" to "Never" and "Enable adaptive brightness" to "Off".', 'img/help8/slide18.jpg'),
(36, 1, 9, 'The list of programs should look like this screenshot - except for "IdentityMine Air Hockey". In this case, this software must be uninstalled.', 'img/help9/slide1.jpg'),
(37, 1, 10, '<a href="#">Download here</a> the Sharing Box image, save it where you want. Then do a right-click on it and select "Set as desktop background". Delete the file after that.', 'img/help10/slide1.jpg'),
(38, 2, 10, 'To create a "Devices and printers" shortcut, go to "Control Panel" > "Hardware and Sound", right-click on "Devices ans printers" and select "Create shortcut". The shortcut will appear on the "Windows Desktop".', 'img/help10/slide2.jpg'),
(39, 1, 11, 'On any "Windows explorer" in the "View" tab, deactivate the "Item check boxes" and activate the "Hidden items".', 'img/help11/slide1.jpg'),
(40, 2, 11, 'To deactivate the screen auto-rotation, go to "Control Panel" > "Appearance and personalization" > "Display" > "Screen Resolution", and uncheck "Allow the screen to auto-rotate". Check if the resolution is at his maximum too.', 'img/help11/slide2.jpg'),
(41, 3, 11, 'Come back to the "Display" settings, and check if the size of all items is set to "Smaller" (the smallest).', 'img/help11/slide3.jpg'),
(42, 1, 12, 'When you are in the software, click on the spanner on the right-top-corner, enter your personal code.', 'img/help12/slide1.jpg'),
(43, 2, 12, 'Once in the sharingbox event manager, click on the spanner. The box config panel appears.', 'img/help12/slide2.jpg'),
(44, 3, 12, 'In the "Box & Users" tab, check if the "Search for update at start up" is "ON". If not, make sure to turn it "ON" ! If an update is available, a message will automatically show up at the starting of the software.', 'img/help12/slide3.jpg'),
(45, 4, 12, 'If there is a Reflex camera in the box, make sure to set the quality to "1080p Photo Shoot", and activate the "Fast-focus".', 'img/help12/slide4.jpg'),
(46, 5, 12, 'If there is a "Logitech HD webcam" in the box, configure the quality to "1080p", and deactivate the "Fast-focus".', 'img/help12/slide5.jpg'),
(47, 1, 13, 'Go to the Google Chrome settings.', 'img/help13/slide1.jpg'),
(48, 2, 13, 'Scroll down and click on "Show advanced settings".', 'img/help13/slide2.jpg'),
(49, 3, 13, 'Right below, under the "Privacy" section, click on "Clear browsing data".', 'img/help13/slide3.jpg'),
(50, 4, 13, 'Select "the beginning of time", check all the checkboxes available and click on the "Clear browsing data" button. Wait until it''s done. Then, quit.', 'img/help13/slide4.jpg'),
(51, 1, 14, 'Make sure to follow all the ste steps indicated on the "blog". Then, go to next step.', 'img/help14/slide1.jpg'),
(52, 1, 15, 'This is how looks the sticker where to find the printer serial number.', 'img/help15/slide1.jpg'),
(53, 2, 15, 'The serial number looks like this. There''s a close-up so you can not miss it !', 'img/help15/slide2.jpg'),
(54, 1, 16, 'To change the color settings of the printer, open the "Printers and devices" window and do a right-click on the "HiTi Photo printer P510", then choose "printing preferences".', 'img/help16/slide1.jpg'),
(55, 2, 16, 'Go to the "Color" tab, then select "HiTi Classic Color".', 'img/help16/slide2.jpg'),
(56, 1, 17, 'From the "Devices and Printers" window, do a right-click on the "HiTi Photo Printer", then select "Printing preferences".', 'img/help17/slide1.jpg'),
(57, 2, 17, 'Under the "Tools" tab, click on "Maintenance Info" and wait a few seconds, a window should open.', 'img/help17/slide2.jpg'),
(58, 3, 17, 'Save the file on the "Desktop".', 'img/help17/slide3.jpg'),
(59, 4, 17, 'In the opened generated text-file should open, take note of the "Total print count", the "Unclean pages" and the "Cleaning at page".', 'img/help17/slide4.jpg'),
(60, 5, 17, 'Go to "My printers" tab in your personal sharingbox backoffice. Type the serial number of the printer in the search bar, then click on "Edit".', 'img/help17/slide5.jpg'),
(61, 6, 17, 'In the printer specific page, set "State" to "Operational". Choose the "current date" and the "Total prints" count for "Last Checking" and "Last Cleaning". In the "Misc" area, paste the full content of the generated text-file. Then, click on "Submit".', 'img/help17/slide6.jpg'),
(62, 1, 18, 'On the Canon Reflex camera, push on the "Menu button" and select "Quality" > "S2".', 'img/help18/slide1.jpg'),
(63, 2, 18, 'Go to the right, select "Off" for "Auto power Off".', 'img/help18/slide2.jpg'),
(64, 3, 18, 'Make sure that the "Flash-off" mode is activated (on the top of the camera).', 'img/help18/slide3.jpg'),
(65, 4, 18, 'Note the serial number.', 'img/help18/slide4.jpg'),
(66, 5, 18, 'If there is a "Logitech webcam" like this one, remember it is a "Logitech HD Pro C920".', 'img/help18/slide5.jpg'),
(67, 6, 18, 'If the Canon''s screen is black, unplug the usb cable and plug it back after the operation.', 'img/help18/slide6.jpg'),
(68, 1, 19, 'Go to the sharingbox backoffice, click on "Administration" > "Boxes", then search your box and click on "Edit".', 'img/help19/slide1.jpg'),
(69, 2, 19, 'Set "State" to "Operational", enter the Canon or webcam model in the specific fields, with the serial number if it a Canon. Select the "Current date" in "Last Maintenance", then click on "Submit" to validate.', 'img/help19/slide2.jpg'),
(102, 1, 1, 'Do a right-click on the desktop, in the dropdown menu choose "Personalize".', 'img/help1/slide1.jpg'),
(103, 2, 1, 'A window opens, click on "Taskbar and Navigation". Then, in the opened window, uncheck "Auto-hide the taskbar".', 'img/help1/slide2.jpg'),
(104, 3, 1, 'In the "Navigation" tab, under "Start screen", activate the option that forces the computer to go to Desktop instead of Windows start screen each time you close all apps or start the sharingbox.', 'img/help1/slide3.jpg');

-- --------------------------------------------------------

--
-- Structure de la table `maintenance`
--

DROP TABLE IF EXISTS `maintenance`;
CREATE TABLE IF NOT EXISTS `maintenance` (
  `idBox` int(11) NOT NULL,
  `idStep` int(11) NOT NULL,
  `idLastUser` int(11) NOT NULL,
  PRIMARY KEY (`idBox`,`idStep`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Contenu de la table `maintenance`
--

INSERT INTO `maintenance` (`idBox`, `idStep`, `idLastUser`) VALUES
(1, 13, 6),
(2, 13, 6),
(3, 13, 6),
(4, 0, 6);

-- --------------------------------------------------------

--
-- Structure de la table `step`
--

DROP TABLE IF EXISTS `step`;
CREATE TABLE IF NOT EXISTS `step` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `step` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `content` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=27 ;

--
-- Contenu de la table `step`
--

INSERT INTO `step` (`id`, `step`, `title`, `content`) VALUES
(1, 1, 'Taskbar / Desktop Start', 'To make the things simpler and easier, you need to restore the taskbar (bottom of the screen), if that is not already done. As well, force the computer to start on the Windows Desktop.'),
(2, 2, 'English pack', 'Then, download and install the English language pack. Go to the "Control Panel".'),
(3, 3, 'Date / Localization', 'Once the language installation is done, please check that the date and the sharingbox localization are correct !\r\n'),
(4, 4, 'Antivirus', 'Now, you need to uninstall all the antiviruses of the machine (such as Kaspersky, Avast, Norton, Symantec, McAfee...)'),
(5, 5, 'Windows Update', 'Make sure all the Windows updates are done : you might need to do the update several times. Do not let an update undone but disable the automatic updates.'),
(6, 6, 'Windows Defender', 'Now, you need to scan the computer with "Windows Defender".'),
(7, 7, 'Automatic messages', 'Now, deactivate the automatic messages from "Windows update" and of "Maintenance"'),
(8, 8, 'Power plan', 'One of the most important things is to change the power plan to "High performance", turn the brightness to max and disable the computer to go to sleep during an event. How to do it ?<br /><br />\r\nDELL : Slides 1-11 / SAMSUNG : Slides 12-18'),
(9, 9, 'Softwares', 'Make sure the softwares installed on the sharingbox are necessary. Go to the "Control Panel" and uninstall unwanted software. Install Google Chrome (if not already installed).'),
(10, 10, 'Desktop', 'Set the Sharing Box image as desktop background. You can download it here. Delete everything from the "Windows Desktop" except the "sharingbox" shortcut and the "Device and Printers" shortcut.'),
(11, 11, 'View settings', 'Deactivate the "Item check boxes" and activate the "Hidden items". Check if the size of all the "Windows items" is set to Smaller (the smallest). And if you''re on a mini, deactivate the "Auto-rotate of the screen".'),
(12, 12, 'Software update', 'If an update of the sharingbox software is available, make sure to do it! Check its availability by launching the software. Then, check if the camera/webcam is operational.'),
(13, 13, 'Google Chrome data', 'Erase the browsing data from Google Chrome.'),
(14, 14, 'Printer update', 'Look for updates for the printer''s driver and/or firmware. You can find them on the sharingbox blog. If it''s up to date, you can go to the next step.'),
(15, 15, 'Printer''s serial number', 'When driver/firmware installation(s)/update(s) are finished, check and note the serial number of the printer.'),
(16, 16, 'Printer settings', 'Make sure your printer is configured on "HiTi Classic Color". It''s explained in this page of the sharingbox blog at the 3rd point.'),
(17, 17, 'Backoffice : Printer', 'With the generated file by the printer and its serial number, fill in the backoffice page of the printer.'),
(18, 18, 'Camera settings', 'Inside the sharingbox, check the Camera/webcam. Then, take note of the serial number and fill in the back office at the next step.'),
(19, 19, 'Backoffice : Box', 'Fill the back office page of the box to confirm it''s done.');

-- --------------------------------------------------------

--
-- Structure de la table `user`
--

DROP TABLE IF EXISTS `user`;
CREATE TABLE IF NOT EXISTS `user` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `login` varchar(10) NOT NULL,
  `pw` varchar(50) NOT NULL,
  `loginBackOffice` varchar(15) NOT NULL,
  `admin` int(1) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=9 ;

--
-- Contenu de la table `user`
--

INSERT INTO `user` (`id`, `login`, `pw`, `loginBackOffice`, `admin`) VALUES
(6, 'seelis', 'a584d6e19827545241b0c19b63a5aca7', 'angel', 1),
(7, 'admin', '21232f297a57a5a743894a0e4a801fc3', 'admin', 1),
(8, 'test', '098f6bcd4621d373cade4e832627b4f6', 'test', 0);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

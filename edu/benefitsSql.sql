/* 2023-09-26 جدول بدل الانتقال*/
CREATE TABLE `districtTravel` (
  `dstrId` int(11) NOT NULL,
  `dstrFrom` int(11) NOT NULL,
  `dstrTo` int(11) NOT NULL,
  `dstrAmount` decimal(8,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

ALTER TABLE `districtTravel`
  ADD PRIMARY KEY (`dstrId`),
  ADD KEY `dstrFrom` (`dstrFrom`),
  ADD KEY `dstrTo` (`dstrTo`);

ALTER TABLE `districtTravel`
  MODIFY `dstrId` int(11) NOT NULL AUTO_INCREMENT;

ALTER TABLE `districtTravel`
  ADD CONSTRAINT `districtTravel_ibfk_1` FOREIGN KEY (`dstrFrom`) REFERENCES `districts` (`dist_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `districtTravel_ibfk_2` FOREIGN KEY (`dstrTo`) REFERENCES `districts` (`dist_id`) ON DELETE CASCADE ON UPDATE CASCADE;

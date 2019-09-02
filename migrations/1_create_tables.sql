CREATE TABLE IF NOT EXISTS `country` (
  `id_country` INT NOT NULL AUTO_INCREMENT,
  `name_country` VARCHAR(45) NOT NULL,
  PRIMARY KEY (`id_country`),
  UNIQUE INDEX `name_country_UNIQUE` (`name_country` ASC)
  );

-- MySQL Workbench Forward Engineering

SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='TRADITIONAL,ALLOW_INVALID_DATES';

-- -----------------------------------------------------
-- Schema spotter
-- -----------------------------------------------------

-- -----------------------------------------------------
-- Schema spotter
-- -----------------------------------------------------
CREATE SCHEMA IF NOT EXISTS `spotter` DEFAULT CHARACTER SET utf8 ;
USE `spotter` ;

-- -----------------------------------------------------
-- Table `spotter`.`Location`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `spotter`.`Location` (
  `location_id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(255) NULL,
  `latitutde` DECIMAL(10,8) NOT NULL,
  `longitude` DECIMAL(11,8) NOT NULL,
  PRIMARY KEY (`location_id`))
ENGINE = InnoDB;

CREATE UNIQUE INDEX `location_id_UNIQUE` ON `spotter`.`Location` (`location_id` ASC);


-- -----------------------------------------------------
-- Table `spotter`.`User`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `spotter`.`User` (
  `email` VARCHAR(255) NOT NULL,
  `password` BLOB NOT NULL,
  `first_name` VARCHAR(45) NOT NULL,
  `last_name` VARCHAR(45) NOT NULL,
  `date_joined` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`email`))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `spotter`.`Photo`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `spotter`.`Photo` (
  `photo_id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `path` VARCHAR(255) NOT NULL,
  `user_email` VARCHAR(255) NOT NULL,
  `location` INT UNSIGNED NOT NULL,
  `date_taken` DATETIME NULL,
  `date_added` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`photo_id`),
  CONSTRAINT `location`
    FOREIGN KEY (`location`)
    REFERENCES `spotter`.`Location` (`location_id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `user_email`
    FOREIGN KEY (`user_email`)
    REFERENCES `spotter`.`User` (`email`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;

CREATE UNIQUE INDEX `photo_id_UNIQUE` ON `spotter`.`Photo` (`photo_id` ASC);

CREATE UNIQUE INDEX `path_UNIQUE` ON `spotter`.`Photo` (`path` ASC);

CREATE INDEX `fk_Photo_Location_idx` ON `spotter`.`Photo` (`location` ASC);

CREATE INDEX `fk_Photo_user1_idx` ON `spotter`.`Photo` (`user_email` ASC);


SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;

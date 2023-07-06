<?php
class ModelModulePets extends Model {

	public function install() {
		$this->installUserPets();
		$this->installAnimal();
		$this->installBreed();
		$this->installGender();
	}

	public function installUserPets() {
		$this->db->query("
			CREATE TABLE IF NOT EXISTS `" . DB_PREFIX . "pets_user` (
				`pets_id` INT(11) NOT NULL AUTO_INCREMENT,
				`pet` VARCHAR(100) NOT NULL,
				`age` int(4) NOT NULL,
				`breed_id` INT(11) NOT NULL,
				`gender_id` INT(1) NOT NULL,
				`user_id` INT(11) NOT NULL,
				PRIMARY KEY(`pets_id`)
			) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci
		");
	}

	public function installAnimal() {
		$this->db->query("
			CREATE TABLE IF NOT EXISTS `" . DB_PREFIX . "pets_animal` (
				`animal_id` INT(11) NOT NULL AUTO_INCREMENT,
				`name` VARCHAR(100) NOT NULL,
				`animal_type` VARCHAR(15) NOT NULL,
				`gender_status` INT(1) NOT NULL DEFAULT '0',
				PRIMARY KEY(`animal_id`)
			) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci
		");
		//default test values
		$this->db->query("INSERT INTO `" . DB_PREFIX . "pets_animal` (`animal_id`, `name`, `animal_type`, `gender_status`) VALUES
		(1, 'Кошка', 'cat', 1),
		(2, 'Собака', 'dog', 1),
		(3, 'Черепаха', 'turtle', 1),
		(4, 'Рыба', 'fish', 0)");
	}

	public function installBreed() {
		$this->db->query("
			CREATE TABLE IF NOT EXISTS `" . DB_PREFIX . "pets_breeds` (
				`breed_id` INT(11) NOT NULL AUTO_INCREMENT,
				`name` VARCHAR(100) NOT NULL,
				`animal_type` VARCHAR(15) NOT NULL,
				PRIMARY KEY(`breed_id`)
			) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci
		");
		//default test values
		$this->db->query("INSERT INTO `" . DB_PREFIX . "pets_breeds` (`breed_id`, `name`, `animal_type`) VALUES
		(1, 'Абиссинская', 'cat'),
		(2, 'Австралийский мист', 'cat'),
		(3, 'Азиатская', 'cat'),
		(4, 'Акита-ину', 'dog'),
		(5, 'Алабай', 'dog'),
		(6, 'Бернский зенненхунд', 'dog'),
		(7, 'Среднеазиатская сухопутная', 'turtle'),
		(8, 'Американская болотная', 'turtle'),
		(9, 'Звездчатая сухопутная', 'turtle'),
		(10, 'Петушок', 'fish'),
		(11, 'Скалярия', 'fish'),
		(12, 'Анциструс', 'fish')");
	}

	public function installGender() {
		$this->db->query("
			CREATE TABLE IF NOT EXISTS `" . DB_PREFIX . "pets_gender` (
				`gender_id` INT(11) NOT NULL AUTO_INCREMENT,
				`name` VARCHAR(100) NOT NULL,
				PRIMARY KEY(`gender_id`)
			) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci
		");
		//default test values
		$this->db->query("INSERT INTO `" . DB_PREFIX . "pets_gender` (`gender_id`, `name`) VALUES
		(1, 'Mужской'),
		(2, 'Женский')");
	}

	public function uninstall() {
		$this->db->query("
			DELETE FROM `" . DB_PREFIX . "setting` 
			WHERE `key` = 'pets_module'
		");

		$this->db->query("
			DROP TABLE `" . DB_PREFIX . "pets_user`
		");

		$this->db->query("
			DROP TABLE `" . DB_PREFIX . "pets_animal`
		");

		$this->db->query("
			DROP TABLE `" . DB_PREFIX . "pets_breeds`
		");

		$this->db->query("
			DROP TABLE `" . DB_PREFIX . "pets_gender`
		");
	}
}
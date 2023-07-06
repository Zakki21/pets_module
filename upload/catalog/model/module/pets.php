<?php
class ModelModulePets extends Model {

	public function getAllUserPets($customer_id) {
		$sql = "SELECT pu.*, pa.name AS pet, pb.name AS breed FROM `" . DB_PREFIX . "pets_user` pu LEFT JOIN `" . DB_PREFIX . "pets_breeds` pb ON (pu.breed_id = pb.breed_id) LEFT JOIN `" . DB_PREFIX . "pets_animal` pa ON (pu.pet = pa.animal_type) WHERE pu.`user_id` = '" . (int)$customer_id . "' ORDER BY pu.`pets_id` DESC";

		$query = $this->db->query($sql);

		return $query->rows;
	}

	public function getAllAnimals() {
		$sql = "SELECT * FROM `" . DB_PREFIX . "pets_animal`";

		$query = $this->db->query($sql);

		return $query->rows;
	}

	public function getNextStepBreedsValues($value) {
		$sql = "SELECT * FROM `" . DB_PREFIX ."pets_breeds` WHERE `animal_type` = '" . $this->db->escape($value) . "'";

		$query = $this->db->query($sql);

		return $query->rows;
	}

	public function getNextStepGenderValues($value) {
		$sql_status = "SELECT pa.gender_status FROM `" . DB_PREFIX . "pets_animal` pa WHERE `animal_type` = '" . $this->db->escape($value) . "'";

		$pets_gender = array();
		if ($this->db->query($sql_status)->row['gender_status']==1) {
			$sql = "SELECT * FROM `" . DB_PREFIX . "pets_gender`";
			$pets_gender = $this->db->query($sql)->rows;
		}

		return $pets_gender;
	}

	public function addPet($data) {
		$this->db->query("INSERT INTO " . DB_PREFIX . "pets_user SET 
			`pet` = '" . $this->db->escape($data['animal']) . "', 
			`age` = '" . (int)$data['age'] . "', 
			`breed_id` = '" . $this->db->escape($data['breed']) . "', 
			`gender_id` = '" . $this->db->escape($data['gender']) . "',
			`user_id` = '" . $this->db->escape($data['user_id']) . "'");

		$pets_id = $this->db->getLastId();

		$query = $this->db->query("SELECT pu.*, pa.name AS pet, pb.name AS breed FROM `" . DB_PREFIX . "pets_user` pu LEFT JOIN `" . DB_PREFIX . "pets_breeds` pb ON (pu.breed_id = pb.breed_id) LEFT JOIN `" . DB_PREFIX . "pets_animal` pa ON (pu.pet = pa.animal_type) WHERE pu.`pets_id` = '" . (int)$pets_id . "'");
		return $query->row;
	}

	public function deletePet($pets_id) {
		$this->db->query("DELETE FROM `" . DB_PREFIX . "pets_user` WHERE `pets_id` = '" . (int)$pets_id . "'");
		$query = $this->db->query("SELECT COUNT(*) AS count FROM `" . DB_PREFIX . "pets_user`");
		return $query->row['count'];
	}
}
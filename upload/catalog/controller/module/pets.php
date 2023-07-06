<?php
class ControllerModulePets extends Controller {
	private $error = array();
	private $step_logic = array(
		'animal' => 'breeds',
		'breeds' => 'gender'
	);

	public function index() {
		$this->load->language('module/pets');
		$this->load->model('module/pets');

		$data['heading_title'] = $this->language->get('heading_title');
		$data['heading_title_form'] = $this->language->get('heading_title_form');

		$data['all_user_pets'] = array();
		$data['logged'] = false;
		$data['user_id'] = 0;

		$data['all_user_pets'] = $this->model_module_pets->getAllUserPets($this->customer->getId());

		if ($this->customer->isLogged()) {
			$data['logged'] = true;
			$data['all_animals'] = $this->model_module_pets->getAllAnimals();
			$data['user_id'] = $this->customer->getId();
		}

		$data['text_select'] = $this->language->get('text_select');
		$data['text_age'] = $this->language->get('text_age');
		$data['entry_pet'] = $this->language->get('entry_pet');
		$data['entry_breed'] = $this->language->get('entry_breed');
		$data['entry_gender'] = $this->language->get('entry_gender');
		$data['entry_age'] = $this->language->get('entry_age');
		$data['button_submit'] = $this->language->get('button_submit');

		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/module/pets.tpl')) {
			return $this->load->view($this->config->get('config_template') . '/template/module/pets.tpl', $data);
		} else {
			return $this->load->view('default/template/module/pets.tpl', $data);
		}
	}

	public function selected() {
		$json = array();

		if ($this->request->server['REQUEST_METHOD'] == 'POST') {
			$selected_id = $this->request->post['selected_id'];
			$selected_value = $this->request->post['selected_value'];
			if($selected_value != -1) {
				$this->load->model('module/pets');
				if(array_key_exists($selected_id, $this->step_logic) && $selected_id == 'animal' && $selected_value != -1) {
					$json['next_step_breeds'] = $this->step_logic[$selected_id];
					$json['next_step_breeds_values'] = $this->model_module_pets->getNextStepBreedsValues($selected_value);

					$json['next_step_gender_values'] = $this->model_module_pets->getNextStepGenderValues($selected_value);
				}
			} else {
				$json['next_step_breeds'] = false;
			}
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}

	public function add() {
		$this->load->language('module/pets');
		$json = array();

		if ($this->request->server['REQUEST_METHOD'] == 'POST' && $this->validateForm()) {
			$this->load->model('module/pets');
			$last_added_pet = $this->model_module_pets->addPet($this->request->post);
			$json['added_pet'] = $last_added_pet;
			$json['success'] = $this->language->get('text_success');
		}

		if (isset($this->error['animal'])) {
			$json['error']['animal'] = $this->error['animal'];
		}
		if (isset($this->error['breed'])) {
			$json['error']['breed'] = $this->error['breed'];
		}
		if (isset($this->error['age'])) {
			$json['error']['age'] = $this->error['age'];
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}

	public function delete() {
		$this->load->language('module/pets');
		$json = array();

		if ($this->request->server['REQUEST_METHOD'] == 'POST' && $this->validateDelete()) {
			$this->load->model('module/pets');
			$json['pets_count'] = $this->model_module_pets->deletePet($this->request->post['pets_id']);
			$json['pets_id'] = $this->request->post['pets_id'];
			$json['success'] = true;
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}

	private function validateForm() {
		if (empty($this->request->post['animal']) || $this->request->post['animal']==-1) {
			$this->error['animal'] = $this->language->get('error_animal');
		}
		if (empty($this->request->post['breed'])) {
			$this->error['breed'] = $this->language->get('error_breed');
		}
		if (empty($this->request->post['age']) || !is_numeric($this->request->post['age'])) {
			$this->error['age'] = $this->language->get('error_age');
		}

		return !$this->error;
	}

	private function validateDelete() {
		if (empty($this->request->post['pets_id']) || !is_numeric($this->request->post['pets_id'])) {
			$this->error['pets_id'] = $this->language->get('error_delete');
		}

		return !$this->error;
	}
}
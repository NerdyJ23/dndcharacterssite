<?php
namespace App\Controller;
use Cake\Controller\Controller;
use App\Controller\Component\Enum\StatusCodes;
use App\Controller\Component\Pagination;
use App\Schema\Character\CharacterBackgroundSchema;

class CharactersBackgroundController extends ApiController {
	public function initialize(): void {
		parent::initialize();
	}

	//Character should only have one background
	public function list() {
		$token = $this->request->getCookie('token');
		$validToken = $token == null ? false : (new AuthenticationController)->validToken($token);

		$id = $this->request->getParam("character_id");
		$charId = $this->decrypt($id);
		if ($charId == false) {
			return $this->response(StatusCodes::NOT_FOUND);
		}

		$charDB = new CharactersController();
		$char = $charDB->getById($this->decrypt($id), $token);
		if (sizeOf($char) == 0) {
			return $this->response(StatusCodes::NOT_FOUND);
		}

		if ($char[0]->Visibility == 1) {
			$query = $this->fetchTable('CharactersBackgrounds')->find('all')
			->where(['Char_ID' => $char[0]->ID]);
		} else {
			$userDB = new UsersController();
			$user = $userDB->getByToken($token);

			if ($user == null) {
				return $this->response(StatusCodes::TOKEN_MISMATCH);
			}
			if ($user->ID == $char[0]->User_Access) {
				$this->fetchTable('CharactersBackgrounds')->find('all')
				->where(['Char_ID' => $char[0]->ID]);
			} else {
				return $this->response(StatusCodes::NOT_FOUND);
			}
		}
		$data = $query->all();
		$resultSet = [];

		foreach($data as $item) {
			$resultSet[] = CharacterBackgroundSchema::toSummarizedSchema($item);
		}
		$this->set("result", $resultSet);
	}
}

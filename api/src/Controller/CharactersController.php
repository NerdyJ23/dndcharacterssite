<?php
namespace App\Controller;

use Cake\Controller\Controller;
use Cake\View\JsonView;
use App\Controller\Security\EncryptionController;
use App\Controller\Security\AuthenticationController;
use Cake\Http\Cookie\Cookie;
use DateTime;
use SplFileInfo;
use Cake\Filesystem\Folder;
use Cake\Filesystem\File;

class CharactersController extends ApiController {
	public function initialize(): void {
		parent::initialize();
	}

	public function listPublicCharacters() {
		$limit = $this->request->getQuery('limit') == null ? 200 : $this->request->getQuery('limit');
		$page = $this->request->getQuery('page') == null ? 1 : $this->request->getQuery('page');
		$count = $this->request->getQuery('count') == null ? false : true;

		$query = $this->Characters->find('all')
			->where(['Characters.Visibility = 1'])
			->limit($limit)
			->page($page);
		$data = $query->all()->toArray();
		$this->set("result", $data);
	}

	public function list() {
		$limit = $this->request->getQuery('limit') == null ? 200 : $this->request->getQuery('limit');
		$page = $this->request->getQuery('page') == null ? 1 : $this->request->getQuery('page');
		$count = $this->request->getQuery('count') == null ? false : true;

		$token = $this->request->getCookie('token');
		if ($token == null) {
			return $this->listPublicCharacters($limit, $page, $count);
		} else if (!(new AuthenticationController)->validToken($token)) {
			return $this->listPublicCharacters($limit, $page, $count);
		}

		$userDB = new UsersController();
		$user = $userDB->get($token);
		if ($user == null || $user == -1) { //display only the public characters
			return $this->listPublicCharacters($limit, $page, $count);
		}

		$userId = (new EncryptionController)->decrypt($user);
		$query = $this->Characters->find('all')
			->where(['Characters.User_Access =' => $userId,
			])
			->limit($limit)
			->page($page);

		if ($query == null) {
			return $this->set('result', []);
		}
		$data = $query->all()->toArray();
		$this->set("result", $data);
	}

	public function get() {
		$id = $this->request->getParam("character_id");
		$token = $this->request->getCookie('token');

		$charId = (new EncryptionController)->decrypt($id);
		if ($charId == false) {
			return $this->response->withStatus(404);
		}

		$result = $this->getById($charId, $token);
		if (sizeOf($result) > 0) {
			$this->set("result", $result);
			return;
		}
		return $this->response->withStatus(404);
	}

	private function getById($charId, $token) {
		$valid = (new AuthenticationController)->validToken($token);
		if (!$valid) {
			$query = $this->Characters->find('all')
			->where([
				'Characters.ID =' => $charId,
				'Visibility = 1'
			]);
			return $query->all()->toArray();
		} else {
			$userDB = new UsersController();
			$userId = $userDB->get($token);
			$query = $this->Characters->find('all')
				->where([
					'Characters.ID =' => $charId,
					'OR' => [
						['Characters.Visibility = 1'],
						['Characters.User_Access =' => (new EncryptionController)->decrypt($userId)]
					]
			]);
			return $result = $query->all()->toArray();
		}
	}
	public function getCharacterImage() {
		$id = $this->request->getParam("character_id");
		$token = $this->request->getCookie('token');

		$charId = (new EncryptionController)->decrypt($id);
		if ($charId == false) {
			return $this->response->withStatus(404);
		}

		$char = $this->getById($charId, $token);
		if (sizeOf($char) == 0) {
			return $this->response->withStatus(404);
		}
		$filepath = $_ENV['CHARACTER_PORTRAIT_DIR'] . $id . '.jpg';
		$image = new SplFileInfo($filepath);
		if ($image->isFile()) {
			return $this->response->withFile($image);
		} else {
			return $this->response->withStatus(404);
		}

		$char = $char[0];
		$this->set('id', $id);
		$this->set('url', $char->Portrait);
		return;
	}

}
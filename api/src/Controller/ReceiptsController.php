<?php
namespace App\Controller;

use Cake\Controller\Controller;
use Cake\View\JsonView;

use Cake\Utility\Security;
use Cake\Core\Configure;
use Cake\Event\EventInterface;

use App\Controller\Security\EncryptionController;
use App\Controller\ItemsController;
use App\Controller\Component\Enum\Success;

class ReceiptsController extends ApiController {
	public function initialize(): void {
		parent::initialize();
	}

	public function afterFilter(EventInterface $event) {
		if ($this->getSuccess() === Success::FAIL) {
			$this->response = $this->response->withStatus(400);
		} else if ($this->getSuccess() === Success::PARTIAL) {
			$this->response = $this->response->withStatus(422);
		}
	}
	public function index() {
		$this->set(['message', 'view help docs for details']);
	}

	public function list() {
		$limit = $this->request->getQuery('limit') == null ? 200 : $this->request->getQuery('limit');
		$page = $this->request->getQuery('page') == null ? 1 : $this->request->getQuery('page');
		$count = $this->request->getQuery('count') == null ? false : true;

		$year = $this->request->getQuery('year') == null ?  date('Y') : $this->request->getQuery('year');
		$month = $this->request->getQuery('month') == null ?  date('m') : $this->request->getQuery('month');

		$query = $this->Receipts->find('all')
			->where(['month(Receipts.Date) =' => $month, 'year(Receipts.Date) =' => $year, 'Receipts.Archive != 1'])
			->contain(['Items'])
			->limit($limit)
			->page($page);

		$data = $query->all()->toArray();
		$result = [];

		foreach($data as $item) {
			$receipt = $this->encodeReceipt($item);
			$result[] = $receipt;
		}
		$this->set('result', $result);

		if($count) {
			$this->set('count',$query->all()->count());
		}

		$this->getYears();
	}

	public function create() {
		$input = $this->request;

		$user = 1;//(new UsersController)->get($input->getCookie('token'));
		$this->set('userid', $user);
		$this->set('token', $input->getCookie('token'));

		$receipt = $this->fetchTable('Receipts')->newEntity([
			'Name' => $input->getData('name'),
			'Location' => $input->getData('location'),
			'ReceiptNumber' => $input->getData('receiptNumber'),
			'User' => $user, // 'User' => $input->getQuery('user')
			'Date' => $input->getData('date'),
			'Cost' => 0,
		]);

		$items = json_decode($input->getData('items'));
		$result = $this->getTableLocator()->get('Receipts')->save($receipt);
		$this->set('result of save', $result);
		$receiptTotal = 0;
		if ($result !== false) {
			$this->setSuccess(true);
		} else {
			$this->setSuccess(false);
			return;
		}

		$itemController = new ItemsController;

		for($i = 0; $i < sizeOf($items); $i++) {
			$itemController->create($items[$i], $result->ID);
			$receiptTotal += floatval($items[$i]->count) * floatval($items[$i]->cost);
		}
		$this->updateTotal($receiptTotal, $result->ID);
		$result->Category = $this->setCategory($result->ID);
		$result = $this->getTableLocator()->get('Receipts')->save($result);
		$this->setSuccess(true);
		return;
	}

	public function get($id) {
		$decrypted = (new EncryptionController)->decrypt($id);
		if($decrypted != false) {
			$query = $this->Receipts->find('all')
				->where(['Receipts.ID =' => $decrypted])
				->contain(['Items'])
				->limit(1);

			$data = $query->all()->toArray();
			$this->set('result', $this->encodeReceipt($data[0]));
		} else {
			$this->set('result', []);
		}
		$this->response = $this->response->withStatus(200);

	}
	public function edit($id) {
		$safeid = (new EncryptionController)->decrypt($id);
		$itemController = new ItemsController;

		if($safeid !== false) {
			$newReceipt = json_decode($this->request->getData('receipt'));
			if($newReceipt === null) {
				$this->setSuccess(false);
				return;
			}
			if(sizeOf($newReceipt->delete) > 0) {
				foreach ($newReceipt->delete as $item) {
					$this->setSuccess($itemController->delete($item));
				}
			}
			$table = $this->getTableLocator()->get('Receipts');

		if((new EncryptionController)->decrypt($newReceipt->id) == false) {
			$this->setSuccess(false);
			return;
		} else {
			$result = true;
			$receipt = $table->get((new EncryptionController)->decrypt($newReceipt->id));

			$this->set('input', $newReceipt);
			$receiptTotal = 0;
			foreach ($newReceipt->items as $item) {
				if ($item->id === 0) {
					$itemController->create($item, $receipt->ID);
				} else {
					$itemController->update($item);
				}
				$receiptTotal += floatval($item->cost) * floatval($item->count);
			}


			$receipt->Name = $newReceipt->name;
			$receipt->Location = $newReceipt->location;
			$receipt->setDate($newReceipt->date);
			$receipt->editedUTC = date('Y-m-s H:i:s');
			$receipt->ReceiptNumber = $newReceipt->receiptNumber;
			$this->updateTotal($receiptTotal, (new EncryptionController)->decrypt($newReceipt->id));
			$receipt->Category = $this->setCategory((new EncryptionController)->decrypt($newReceipt->id));
			$result = $table->save($receipt);
			$this->set('result', $result);
		}

		if($result !== false) {
			$this->setSuccess(true);
		} else {
			$this->setSuccess(false);
		}
		} else {
			$this->setSuccess(false);
		}
	}
	public function delete($id) {
		$decrypted = (new EncryptionController)->decrypt($id);
		if($decrypted != false) {
			$receipt = $this->Receipts->get($decrypted);
			$result = $this->Receipts->delete($receipt);
			if ($result !== false) {
				$this->setSuccess(true);
			} else {
				$this->setSuccess(false);
			}
		} else {
			$this->setSuccess(false);
		}
	}
	public function getYears() {
		$query = $this->Receipts->find()
		->select([
			'date' => 'year(Date)'
		])
		->group('Year(date)');
		$data = $query->all()->toArray();
		$this->set('years', $data);
	}

	private function encodeReceipt($receipt) {
		return [
			'id' => $receipt->get('encodedId'),
			// 'userid' => $receipt->get('userId'),
			'name' => $receipt->Name,
			'receiptNumber' => $receipt->ReceiptNumber,
			'location' => $receipt->Location,
			'cost' => $receipt->Cost,
			'date' => $receipt->Date,
			'category' => $receipt->get('category'),
			'items' => $this->encodeItem($receipt)
		];
	}

	private function encodeItem($receipt) {
		$items = [];
		foreach($receipt->items as $item) {
			$items[] = [
				'id' => $item->get('id'),
				'name' => $item->Name,
				'receiptNumber' => $item->ReceiptNumber,
				'count' => $item->Count,
				'cost' => $item->Cost,
				'category' => $item->Category
			];
		}
		return $items;
	}

	private function updateTotal($total, $receiptID) {
		$receipt = $this->Receipts->get($receiptID);
		$receipt->Cost = $total;
		$result = $this->Receipts->save($receipt);
		if ($result !== false) {
			$this->setSuccess(true);
		} else {
			$this->setSuccess(false);
			return;
		}
	}

	private function setCategory($receiptID) {
		$itemController = new ItemsController;
		$cat = $itemController->listDistinctCagetories($receiptID);
		if(sizeOf($cat) != 1) {
			return 'MIXED';
		} else {
			return $cat[0]->Category;
		}
	}
}

?>
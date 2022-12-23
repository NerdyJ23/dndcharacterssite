<?php
namespace App\Controller;

use Cake\Controller\Controller;
use Cake\View\JsonView;

use Cake\Utility\Security;
use Cake\Core\Configure;

use App\Controller\Security\EncryptionController;
use App\Model\Entity\Item;

class ItemsController extends ApiController {

	public function initialize(): void {
		parent::initialize();
	}

	public function listDistinctCagetories($receiptID) {
		return $this->Items->find()
		->select(['Items.Category'])
		->distinct(['Items.Category'])
		->where(['Items.Receipt = ' => $receiptID])
		->all()
		->toArray();
	}
	public function create($item, $receiptId): Item {
		$newItem = $this->fetchTable('Items')->newEntity([
			'Receipt' => $receiptId,
			'Name' => $item->name,
			'Count' => floatval($item->count),
			'Cost' => floatval($item->cost)
		]);

		if($item->category !== '' && $item->category !== null ) {
			$newItem->Category = $item->category;
		}
		$result = $this->fetchTable('Items')->save($newItem);
		$this->setSuccess($result);
		return $result;
	}

	public function update($item) {
		$table = $this->getTableLocator()->get('Items');
		if((new EncryptionController)->decrypt($item->id) == false) {
			$this->setSuccess(false);
		} else {
			$newItem = $table->get((new EncryptionController)->decrypt($item->id));
			$newItem->Name = $item->name;
			$newItem->Count = floatval($item->count);
			$newItem->Cost = floatval($item->cost);
			$newItem->Category = $item->category;

			$this->setSuccess($table->save($newItem));
		}
	}

	public function delete($item) {
		if((new EncryptionController)->decrypt($item->id) !== false) {
			$toRemove = $this->Items->get((new EncryptionController)->decrypt($item->id));
			$result = $this->Items->delete($toRemove);
		} else {
			$result = false;
		}
		return $result;
	}

}

?>
<?php
namespace App\Controller\Component;
use Cake\Http\ServerRequest;

class Pagination {
	private $page = 1;
	private $limit = 30;

	function __construct(ServerRequest $request) {
		if ($request != null) {
			if ($request->getQuery('limit') != null) {
				$this->setLimit($request->getQuery('limit'));
			}

			if ($request->getQuery('page') != null) {
				$this->setPage($request->getQuery('page'));
			}
		}
	}

	public function setPage(int $page): void {
		$this->page = $page;
	}
	public function getPage(): int {
		return $this->page;
	}

	public function setLimit(int $limit): void {
		$this->limit = $limit;
	}
	public function getLimit(): int {
		return $this->limit;
	}
}

<?php
namespace App\Schema;

interface SchemaInterface {
	static function toExtendedSchema($item);
	static function toSummarizedSchema($item);
}
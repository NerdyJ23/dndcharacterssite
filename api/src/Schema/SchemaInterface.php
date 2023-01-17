<?php
namespace App\Schema;

interface SchemaInterface {
	static function schema($item);
	static function toExtendedSchema($item);
	static function toSummarizedSchema($item);
}
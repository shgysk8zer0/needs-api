<?php
namespace shgysk8zer0\Abstracts;

use \PDO;
use \PDOStatement;

abstract class Schema
{

	final public function __get(string $key)
	{
		$method = sprintf('get%s', ucfirst($key));

		if (method_exists($this, $method)) {
			return call_user_func([$this, $method]);
		} else {
			throw new \BadMethodCallException("No getter avaialble for {$key}");
		}
	}

	final public function __set(string $key, $val)
	{
		$method = sprintf('set%s', ucfirst($key));

		if (method_exists($this, $method)) {
			return call_user_func([$this, $method], $val);
		} else {
			throw new \BadMethodCallException("No setter avaialble for {$key}");
		}
	}

	final public function __isset(string $key): bool
	{
		$method = sprintf('get%s', ucfirst($key));

		if (method_exists($this, $method)) {
			return call_user_func([$this, $method]) !== null;
		} else {
			return false;
		}
	}

	final public function __unset(string $key): bool
	{
		$method = sprintf('set%s', ucfirst($key));

		if (method_exists($this, $method)) {
			call_user_func([$this, $method], null);
		} else {
			throw new \BadMethodCallException("No setter avaialble for {$key}");
		}
	}

	final protected function _usedIdentifier(): string
	{
		return $this->getIdentifier() ?? self::generateUUID();
	}

	final public static function generateUUID(): string
	{
		return trim(`uuidgen`);
	}

	final protected function _filterOutput(array $data): array
	{
		return array_filter($data, function($val): bool
		{
			return isset($val);
		});
	}

	final public function setFromUserInput(\shgysk8zer0\PHPAPI\Interfaces\InputData $data): void
	{
		$this->setFromObject(json_decode(json_encode($data)));
	}

	abstract public function setFromObject(object $data): void;

	abstract public function save(PDO $pdo):? string;
}

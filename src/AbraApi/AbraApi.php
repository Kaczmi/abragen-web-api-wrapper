<?php declare(strict_types=1);

namespace AbraApi;

use AbraApi\CommandBuilders;
use AbraApi\Executors;
use AbraApi\Callers;

class AbraApi
{

	/**
	 * URL, na kterém běží Abra API a kam se bude připojení dotazovat
	 * @var string
	 */
	private $host;

	/**
	 * Databáze, do které se má API dotazovat (systém jej spojí s URL)
	 * @var string
	 */
	private $database;

	/**
	 * Uživatel, na kterého se API přihlásí (pozn. v Abře musí mít povolené API dotazy)
	 * @var string
	 */
	private $userName;

	/**
	 * Heslo, které API použije k připojení (pozn. heslo systém očekává v plain podobě, které následně encoduje na BASE64)
	 * @var string
	 */
	private $password;

	public function __construct(string $host, string $database, string $userName, string $password)
	{
		$this->host = $host;
		$this->database = $database;
		$this->userName = $userName;
		$this->password = $password;
	}

	/**
	 * Začne vytvářet GET požadavek - pro získání dat
	 */
	public function get(): CommandBuilders\GetQuery
	{
		return (new CommandBuilders\GetQuery(new Executors\JsonExecutor(), new Callers\GetQueryResultGetter(new Callers\PostCaller($this))));
	}

	/**
	 * Vrátí obsah dokumentu z daného BO podle vybraného reportu
	 */
	public function getDocument(): CommandBuilders\GetDocumentQuery
	{
		return (new CommandBuilders\GetDocumentQuery(new Executors\JsonExecutor(), new Callers\GetDocumentResultGetter(new Callers\PostCaller($this))));
	}

	/**
	 * Returns new UpdateQuery command builder
	 */
	public function update(): CommandBuilders\UpdateQuery
	{
		return (new CommandBuilders\UpdateQuery(new Callers\UpdateQueryResultGetter(new Callers\PutCaller($this))));
	}

	/**
	 * Returns new QrExpr command builder
	 */
	public function qr(): CommandBuilders\QrQuery
	{
		return (new CommandBuilders\QrQuery(new Executors\JsonExecutor(), new Callers\QrFunctionsResultGetter(new Callers\PostCaller($this))));
	}

	/**
	 * Return new InsertQuery command builder
	 */
	public function insert(): CommandBuilders\InsertQuery
	{
		return (new CommandBuilders\InsertQuery(new Callers\InsertQueryResultGetter(new Callers\PostCaller($this))));
	}

	/**
	 * Return new DeleteQuery command builder
	 */
	public function delete(): CommandBuilders\DeleteQuery
	{
		return (new CommandBuilders\DeleteQuery(new Callers\DeleteQueryResultGetter(new Callers\DeleteCaller($this))));
	}

	/**
	 * Return new ImportQuery command builder
	 */
	public function import(): CommandBuilders\ImportQuery
	{
		return (new CommandBuilders\ImportQuery(new Callers\ImportQueryResultGetter($this)));
	}

	/**
	 * Vrátí URL adresu, na kterou se má Caller dotázat
	 */
	public function getUri(): string
	{
		return $this->host . "/" . $this->database . "/";
	}

	/**
	 * Vrátí base64 zakódované přihlašovací údaje
	 */
	public function getCredentials(): string
	{
		return \base64_encode($this->userName . ":" . $this->password);
	}
}

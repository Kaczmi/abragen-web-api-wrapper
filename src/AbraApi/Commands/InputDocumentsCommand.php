<?php declare(strict_types=1);

namespace AbraApi\Commands;

final class InputDocumentsCommand implements Interfaces\ICommandQueryBuilder
{

	public const DOCUMENTS_SELECTOR = "input_documents";


	/** @var array<mixed> */
	private array $inputDocuments = [];


	/**
	 * @param array<mixed> $inputDocuments
	 */
	public function __construct(array $inputDocuments)
	{
		$this->validateInputDocuments($inputDocuments);
		$this->inputDocuments = $inputDocuments;
	}


    /**
     * @return array<mixed>
     */
    public function getCommand(): array
    {
        if (\count($this->inputDocuments) === 1)
            return [self::DOCUMENTS_SELECTOR => $this->inputDocuments[0]];

        return [self::DOCUMENTS_SELECTOR => $this->inputDocuments];
    }


	/**
	 * @param array<mixed> $inputDocuments
	 */
	private function validateInputDocuments(array $inputDocuments): void
	{
		foreach ($inputDocuments as $documentId) {
			if (!\is_string($documentId) || \strlen($documentId) !== 10) {
				throw new \Exception("Documents are supposed to be array of Bussiness object ID´s (string with length of 10 characters)");
			}
		}
	}

}
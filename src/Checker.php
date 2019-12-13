<?php
namespace Matecat\AJV;

use Matecat\AJV\Validator\Document\DuplicatedKeyValidator;
use Matecat\AJV\Validator\Document\DuplicatedSegmentIdValidator;
use Matecat\AJV\Validator\Document\ValidatorInterface as DocumentValidatorInterface;
use Matecat\AJV\Validator\Document\DuplicateIdValidator;
use Matecat\AJV\Validator\Phrase\MissingKeysValidator;
use Matecat\AJV\Validator\Phrase\TargetValidator;
use Matecat\AJV\Validator\Phrase\ValidatorInterface as PhraseValidatorInterface;

class Checker
{
    /**
     * @var string
     */
    private $jsonPath;

    /**
     * @var string
     */
    private $jsonContent;

    /**
     * @var mixed
     */
    private $jsonDecoded;

    /**
     * @var mixed
     */
    private $jsonPhrases;

    public function __construct(string $jsonPath)
    {
        $this->jsonPath = $jsonPath;
        try {
            $this->jsonContent = file_get_contents($this->jsonPath);
            $this->jsonDecoded = json_decode($this->jsonContent, true);
            $this->jsonPhrases = $this->jsonDecoded['export_phrases'];
        } catch (\Exception $e) {
            $this->jsonContent = null;
            $this->jsonDecoded = null;
            $this->jsonPhrases = null;
        }
    }

    public function report(): array
    {
        $report = [
                'status' => $this->getStatus(),
                'errors' => $this->getErrors()
        ];

        return $report;
    }

    private function getStatus(): string
    {
        if ($this->jsonContent === null) {
            return "File not found";
        }

        if ($this->jsonPhrases === null) {
            return 'No "export_phrases" key';
        }

        if (!is_object($this->jsonDecoded) && !is_array($this->jsonDecoded)) {
            return "Invalid JSON";
        }

        return "OK";
    }

    private function getErrors(): array
    {
        $errors = [];

        if (null === $this->jsonPhrases) {
            return $errors;
        }

        $documentValidators = [
            'ids'         => DuplicateIdValidator::class,
            'keys'        => DuplicatedKeyValidator::class,
            'segment_ids' => DuplicatedSegmentIdValidator::class,
        ];

        /** @var DocumentValidatorInterface $validator */
        foreach ($documentValidators as $key => $validator) {
            $validate = $validator::validate($this->jsonPhrases);

            if (count($validate) > 0) {
                $errors[$key] = $validate;
            }
        }

        foreach ($this->jsonPhrases as $index => $phrase) {
            $phraseValidators = [
                'keys'   => MissingKeysValidator::class,
                'target' => TargetValidator::class,
            ];

            /** @var PhraseValidatorInterface $validator */
            foreach ($phraseValidators as $key => $validator) {
                $validate = $validator::validate($phrase, $index);

                if (count($validate) > 0) {
                    $errors[$key][] = $validate;
                }
            }
        }

        return $errors;
    }
}

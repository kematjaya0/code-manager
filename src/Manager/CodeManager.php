<?php

declare(strict_types=1);

namespace Kematjaya\CodeManager\Manager;

use DateTime;
use Exception;
use Kematjaya\CodeManager\Builder\AbstractCodeBuilder;
use Kematjaya\CodeManager\Entity\CodeLibraryClientInterface;
use Kematjaya\CodeManager\Entity\CodeLibraryInterface;
use Kematjaya\CodeManager\Entity\CodeLibraryResetInterface;
use Kematjaya\CodeManager\Exception\CodeLibraryNotFoundException;
use Kematjaya\CodeManager\Exception\NotSupportedResetKeyException;
use Kematjaya\CodeManager\Repository\CodeLibraryRepositoryInterface;

/**
 * @author Nur Hidayatullah <kematjaya0@gmail.com>
 */
class CodeManager implements CodeManagerInterface
{
    public function __construct(
        private AbstractCodeBuilder $codeBuilder,
        private CodeLibraryRepositoryInterface $codeLibraryRepo,
        private CodeLibraryLogManagerInterface $codeLibraryLogManager,
        private int $numberLength = 4
    ) {
    }

    public function getNumberLength(): int
    {
        return $this->numberLength;
    }

    public function setNumberLength(int $numberLength): self
    {
        $this->numberLength = $numberLength;

        return $this;
    }

    /**
     * Generate code and save into object and create log
     *
     * @param CodeLibraryClientInterface $client
     * @return CodeLibraryClientInterface
     * @throws Exception
     */
    public function generate(CodeLibraryClientInterface $client): CodeLibraryClientInterface
    {
        $codeLibrary = $this->codeLibraryRepo->findOneByClient($client);
        if (!$codeLibrary) {
            throw new CodeLibraryNotFoundException($client);
        }

        if ($codeLibrary instanceof CodeLibraryResetInterface) {
            $this->resetCodeLibrary($client, $codeLibrary);
        }

        $lastSequence = $codeLibrary->getLastSequence() ?? 0;
        $format = $codeLibrary->getFormat() ?? '';
        $separator = $codeLibrary->getSeparator();
        if ($separator === null || $separator === '') {
            $separator = CodeLibraryInterface::SEPARATOR_MINUS;
        }
        $code = $this->codeBuilder->generate($format, $client, $separator);
        $number = $this->generateNumber($lastSequence);

        $completeCode = str_replace(self::REGEX_NUMBER, $number, $code);

        $client->setGeneratedCode($completeCode);
        $this->updateCodeLibrary($codeLibrary, $number, $completeCode);
        $this->codeLibraryLogManager->createLog($client);

        return $client;
    }

    protected function resetCodeLibrary(
        CodeLibraryClientInterface $client,
        CodeLibraryResetInterface $codeLibrary
    ): CodeLibraryResetInterface {
        $resetKey = $codeLibrary->getResetKey();
        if (null === $resetKey) {
            return $codeLibrary;
        }

        if (!$this->codeBuilder->isSupported($resetKey)) {
            throw new NotSupportedResetKeyException($codeLibrary);
        }

        $format = $codeLibrary->getFormat() ?? '';
        if (!str_contains($format, $resetKey)) {
            throw new Exception(
                sprintf("format key '%s' not found inside format '%s'", $resetKey, $format)
            );
        }

        if (null === $codeLibrary->getLastCode()) {
            return $codeLibrary;
        }

        $separator = $codeLibrary->getSeparator();
        if ($separator === null || $separator === '') {
            $separator = CodeLibraryInterface::SEPARATOR_MINUS;
        }
        $library = array_merge($this->codeBuilder->getLibrary(), $client->getLibrary());
        $lastCodes = explode($separator, (string) $codeLibrary->getLastCode());
        $formats = array_flip(explode($separator, $format));
        $key = $formats[$resetKey] ?? null;
        if (null === $key) {
            throw new Exception(sprintf('key not found: %s', $resetKey));
        }

        $lastValue = $lastCodes[$key] ?? null;
        $formatValueKey = $this->codeBuilder->getFormatValue($resetKey);
        $actualValue = $formatValueKey !== null && isset($library[$formatValueKey])
            ? (string) $library[$formatValueKey]
            : null;

        if ($lastValue === $actualValue) {
            return $codeLibrary;
        }

        $codeLibrary->setLastSequence(0);

        return $codeLibrary;
    }

    /**
     * Update last sequence of code library object
     *
     * @param CodeLibraryInterface $codeLibrary
     * @param string $number
     * @param string $code
     * @return void
     */
    protected function updateCodeLibrary(CodeLibraryInterface $codeLibrary, string $number, string $code): void
    {
        $codeLibrary->setLastCode($code)
            ->setLastSequence((int) $number)
            ->setLastUsed(new DateTime());

        $this->codeLibraryRepo->save($codeLibrary);
    }

    /**
     * Generate number provide by last sequence in code library
     *
     * @param int $lastSequence
     * @return string
     */
    protected function generateNumber(int $lastSequence): string
    {
        $number = $lastSequence + 1;

        return str_pad((string) $number, $this->numberLength, '0', STR_PAD_LEFT);
    }
}

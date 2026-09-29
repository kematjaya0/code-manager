<?php

namespace Kematjaya\CodeManager\Manager;

use Kematjaya\CodeManager\Builder\AbstractCodeBuilder;
use Kematjaya\CodeManager\Entity\CodeLibraryClientInterface;
use Kematjaya\CodeManager\Entity\CodeLibraryInterface;
use Kematjaya\CodeManager\Entity\CodeLibraryResetInterface;
use Kematjaya\CodeManager\Repository\CodeLibraryRepositoryInterface;
use Kematjaya\CodeManager\Exception\CodeLibraryNotFoundException;
use Kematjaya\CodeManager\Exception\NotSupportedResetKeyException;
/**
 * @author Nur Hidayatullah <kematjaya0@gmail.com>
 */
class CodeManager implements CodeManagerInterface
{
    /**
     * 
     * @var AbstractCodeBuilder
     */
    private $codeBuilder;
    
    /**
     * 
     * @var CodeLibraryRepositoryInterface
     */
    private $codeLibraryRepo;
    
    /**
     * 
     * @var CodeLibraryLogManagerInterface
     */
    private $codeLibraryLogManager;
    
    public function __construct(AbstractCodeBuilder $codeBuilder, CodeLibraryRepositoryInterface $codeLibraryRepo, CodeLibraryLogManagerInterface $codeLibraryLogManager) 
    {
        $this->codeBuilder = $codeBuilder;
        $this->codeLibraryRepo = $codeLibraryRepo;
        $this->codeLibraryLogManager = $codeLibraryLogManager;
    }
    
    /**
     * Generate code and save into object and create log
     * 
     * @param CodeLibraryClientInterface $client
     * @return CodeLibraryClientInterface
     * @throws \Exception
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
        
        $lastSequence = $codeLibrary->getLastSequence() ? $codeLibrary->getLastSequence() : 0;
        $code = $this->codeBuilder->generate((string) $codeLibrary->getFormat(), $client, $this->getSeparator($codeLibrary));
        $number = $this->generateNumber($lastSequence, $codeLibrary->getLength() ?: 4);
        
        $completeCode = str_replace(self::REGEX_NUMBER, $number, $code);
        
        $client->setGeneratedCode($completeCode);
        $this->updateCodeLibrary($codeLibrary, $number, $completeCode);
        $this->codeLibraryLogManager->createLog($client);
        
        return $client;
    }
    
    protected function resetCodeLibrary(CodeLibraryClientInterface $client, CodeLibraryResetInterface $codeLibrary): CodeLibraryResetInterface
    {
        if (null === $codeLibrary->getResetKey()) {
            return $codeLibrary;
        }
        
        if (!$this->codeBuilder->isSupported($codeLibrary->getResetKey())) {
            throw new NotSupportedResetKeyException($codeLibrary);
        }
        
        $strpos = strpos((string) $codeLibrary->getFormat(), $codeLibrary->getResetKey());
        if (false === $strpos) {
            throw new \Exception(sprintf("format key '%s' not found inside format '%s'", $codeLibrary->getResetKey(), $codeLibrary->getFormat()));
        }
        
        if (null === $codeLibrary->getLastCode()) {
            return $codeLibrary;
        }
         
        $library = array_merge($this->codeBuilder->getLibrary(), $client->getLibrary());
        $lastCodes = $this->explode($codeLibrary);
        $formats = array_flip(explode($this->getSeparator($codeLibrary), $codeLibrary->getFormat()));
        $key = isset($formats[$codeLibrary->getResetKey()]) ? $formats[$codeLibrary->getResetKey()] : null;
        if (null === $key) {
            throw new \Exception(sprintf('key not found: %s', $codeLibrary->getResetKey()));
        }
        
        if (!isset($lastCodes[$key])) {
            // last code tidak bisa dibandingkan dengan format (mis. format berubah), sequence dilanjutkan
            return $codeLibrary;
        }
        
        $libraryKey = $this->codeBuilder->getFormatValue($codeLibrary->getResetKey());
        $lastValue = (string) $lastCodes[$key];
        // key yang tidak ada di library ditulis apa adanya oleh builder
        $actualValue = array_key_exists($libraryKey, $library) ? (string) $library[$libraryKey] : $codeLibrary->getResetKey();
        if ($lastValue === $actualValue) {
            
            return $codeLibrary;
        }
        
        $codeLibrary->setLastSequence(0);
        
        return $codeLibrary;
    }
    
    /**
     * Explode by separator
     * @param CodeLibraryInterface $codeLibrary
     * @return array
     */
    protected function explode(CodeLibraryInterface $codeLibrary):array
    {
        $lastCodes = explode($this->getSeparator($codeLibrary), $codeLibrary->getLastCode());
        if (count($lastCodes)>1) {
            
            return $lastCodes;
        }
        
        foreach ([CodeLibraryInterface::SEPARATOR_BACKSLASH, CodeLibraryInterface::SEPARATOR_MINUS, CodeLibraryInterface::SEPARATOR_SLASH, CodeLibraryInterface::SEPARATOR_DOT] as $separator) {
            $lastCodes = explode($separator, $codeLibrary->getLastCode());
            if (count($lastCodes)>1) {

                return $lastCodes;
            }
        }
        
        return [];
    }
    
    /**
     * Separator of code library, default to minus when not set
     * 
     * @param CodeLibraryInterface $codeLibrary
     * @return string
     */
    protected function getSeparator(CodeLibraryInterface $codeLibrary):string
    {
        $separator = $codeLibrary->getSeparator();
        
        return null === $separator || '' === $separator ? CodeLibraryInterface::SEPARATOR_MINUS : $separator;
    }
    
    /**
     * Update last sequence of code library object
     * 
     * @param CodeLibraryInterface $codeLibrary
     * @param string $number
     * @param string $code
     * @return void
     */
    protected function updateCodeLibrary(CodeLibraryInterface $codeLibrary, string $number, string $code):void
    {
        $codeLibrary->setLastCode($code)
                ->setLastSequence((int)$number)
                ->setLastUsed(new \DateTime());
        
        $this->codeLibraryRepo->save($codeLibrary);
    }
    
    /**
     * Generate number provide by last sequence in code library
     * 
     * @param int $lastSequence
     * @return string
     */
    protected function generateNumber(int $lastSequence, int $length):string
    {
        $number = $lastSequence + 1;
        
        return str_pad((string) $number, $length, '0', STR_PAD_LEFT);
    }
    
}

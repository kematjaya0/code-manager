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
        $code = $this->codeBuilder->generate($codeLibrary->getFormat(), $client, $codeLibrary->getSeparator());
        $number = $this->generateNumber($lastSequence, $codeLibrary->getLength());
        
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
        
        $strpos = strpos($codeLibrary->getFormat(), $codeLibrary->getResetKey());
        if (false === $strpos) {
            throw new \Exception(sprintf("format key '%s' not found inside format '%s'", $codeLibrary->getResetKey(), $codeLibrary->getFormat()));
        }
        
        if (null === $codeLibrary->getLastCode()) {
            return $codeLibrary;
        }
         
        $library = array_merge($this->codeBuilder->getLibrary(), $client->getLibrary());
        $lastCodes = $this->explode($codeLibrary);
        $formats = array_flip(explode($codeLibrary->getSeparator(), $codeLibrary->getFormat()));
        $key = isset($formats[$codeLibrary->getResetKey()]) ? $formats[$codeLibrary->getResetKey()] : null;
        if (!$key) {
            throw new \Exception(sprintf('key not found: %s', $codeLibrary->getResetKey()));
        }
        
        $lastValue = $lastCodes[$key];
        $actualValue = $library[$this->codeBuilder->getFormatValue($codeLibrary->getResetKey())];
        //dump($library);
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
        $lastCodes = explode($codeLibrary->getSeparator(), $codeLibrary->getLastCode());
        if (count($lastCodes)>1) {
            
            return $lastCodes;
        }
        
        foreach ([CodeLibraryInterface::SEPARATOR_BACKSLASH, CodeLibraryInterface::SEPARATOR_MINUS, CodeLibraryInterface::SEPARATOR_SLASH] as $separator) {
            $lastCodes = explode($separator, $codeLibrary->getLastCode());
            if (count($lastCodes)>1) {

                return $lastCodes;
            }
        }
        
        return [];
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
        $numbers = [];
        for ($i = 0; $i < ($length - strlen($number)); $i++) {
            $numbers[] = 0;
        }
        
        $numbers[] = $number;
        
        return implode('', $numbers);
    }
    
}

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
    
    private $numberLength = 4;
    
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
            $this->resetCodeLibrary($codeLibrary);
        }
        
        $lastSequence = $codeLibrary->getLastSequence() ? $codeLibrary->getLastSequence() : 0;
        $code = $this->codeBuilder->generate($codeLibrary->getFormat(), $client, $codeLibrary->getSeparator());
        $number = $this->generateNumber($lastSequence);
        
        $completeCode = str_replace(self::REGEX_NUMBER, $number, $code);
        
        $client->setGeneratedCode($completeCode);
        $this->updateCodeLibrary($codeLibrary, $number, $completeCode);
        $this->codeLibraryLogManager->createLog($client);
        
        return $client;
    }
    
    protected function resetCodeLibrary(CodeLibraryResetInterface $codeLibrary): CodeLibraryResetInterface
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
         
        $library = $this->codeBuilder->getLibrary();
        $lastCodes = explode($codeLibrary->getSeparator(), $codeLibrary->getLastCode());
        $key = array_keys(explode($codeLibrary->getSeparator(), $codeLibrary->getFormat()));
        dump($key);
        exit;
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
    protected function generateNumber(int $lastSequence):string
    {
        $number = $lastSequence + 1;
        $numbers = [];
        for ($i = 0; $i < ($this->numberLength - strlen($number)); $i++) {
            $numbers[] = 0;
        }
        
        $numbers[] = $number;
        
        return implode('', $numbers);
    }

}

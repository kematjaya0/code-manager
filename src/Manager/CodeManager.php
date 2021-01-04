<?php

namespace Kematjaya\CodeManager\Manager;

use Kematjaya\CodeManager\Builder\CodeBuilderInterface;
use Kematjaya\CodeManager\Entity\CodeLibraryClientInterface;
use Kematjaya\CodeManager\Entity\CodeLibraryInterface;
use Kematjaya\CodeManager\Repository\CodeLibraryRepositoryInterface;
use Kematjaya\CodeManager\Exception\CodeLibraryNotFoundException;
/**
 * @author Nur Hidayatullah <kematjaya0@gmail.com>
 */
class CodeManager implements CodeManagerInterface
{
    /**
     * 
     * @var CodeBuilderInterface
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
    
    public function __construct(CodeBuilderInterface $codeBuilder, CodeLibraryRepositoryInterface $codeLibraryRepo, CodeLibraryLogManagerInterface $codeLibraryLogManager) 
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
        
        $lastSequence = $codeLibrary->getLastSequence() ? $codeLibrary->getLastSequence() : 0;
        $code = $this->codeBuilder->generate($codeLibrary->getFormat(), $client, $codeLibrary->getSeparator());
        $number = $this->generateNumber($lastSequence);
        
        $completeCode = str_replace(self::REGEX_NUMBER, $number, $code);
        
        $client->setGeneratedCode($completeCode);
        $this->updateCodeLibrary($codeLibrary, $number, $completeCode);
        $this->codeLibraryLogManager->createLog($client);
        
        return $client;
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

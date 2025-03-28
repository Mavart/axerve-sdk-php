<?php

namespace Axerve\Payment\Model\Payload;

/**
 * Classe base che rappresenta un payload di una risposta di Axerve
 */
abstract class BasePayload
{
    /**
     * Costruttore che inizializza l'oggetto dai dati del payload
     *
     * @param array $data Dati del payload
     */
    public function __construct(array $data)
    {
        foreach ($data as $key => $value) {
            // Chiamiamo il setter dinamico per impostare il valore
            $this->__set($key, $value);
        }
    }

    /**
     * Magic method per l'accesso alle proprietà come se fossero pubbliche
     *
     * @param string $name Nome della proprietà
     * @return mixed|null Valore della proprietà
     */
    public function __get(string $name)
    {
        // Verifichiamo se esiste un metodo getter per questa proprietà
        $getterMethod = 'get' . ucfirst($name);
        
        if (method_exists($this, $getterMethod)) {
            // Se esiste un getter, lo utilizziamo
            return $this->$getterMethod();
        }
        
        // Altrimenti restituiamo null
        return null;
    }

    /**
     * Magic method per impostare le proprietà
     * 
     * @param string $name Nome della proprietà
     * @param mixed $value Valore della proprietà
     * @return void
     */
    public function __set(string $name, $value): void
    {
        // Verifichiamo se esiste un metodo setter per questa proprietà
        $setterMethod = 'set' . ucfirst($name);
        
        if (method_exists($this, $setterMethod)) {
            // Se esiste un setter, lo utilizziamo
            $this->$setterMethod($value);
            return;
        }
        
        // Altrimenti verifichiamo se la proprietà esiste
        // e utilizziamo reflection per settarla anche se è privata
        if (property_exists($this, $name)) {
            $reflection = new \ReflectionProperty($this, $name);
            $reflection->setAccessible(true);
            $reflection->setValue($this, $value);
        }
    }

    /**
     * Verifica se la proprietà esiste
     *
     * @param string $name Nome della proprietà
     * @return bool True se la proprietà esiste
     */
    public function __isset(string $name): bool
    {
        // Verifichiamo se esiste un getter per questa proprietà
        $getterMethod = 'get' . ucfirst($name);
        
        if (method_exists($this, $getterMethod)) {
            // Se esiste un getter, lo utilizziamo per verificare
            return $this->$getterMethod() !== null;
        }
        
        return false;
    }

    /**
     * Converte l'oggetto in un array associativo
     *
     * @return array
     */
    public function toArray(): array
    {
        $result = [];
        $reflection = new \ReflectionClass($this);
        $properties = $reflection->getProperties(\ReflectionProperty::IS_PRIVATE | \ReflectionProperty::IS_PROTECTED);
        
        foreach ($properties as $property) {
            $property->setAccessible(true);
            $name = $property->getName();
            $value = $property->getValue($this);
            
            if ($value !== null) {
                $result[$name] = $value;
            }
        }
        
        return $result;
    }
} 
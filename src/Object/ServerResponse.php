<?php

namespace CkAmaury\WorkConnection\Object;

class ServerResponse{

    private bool $is_connected;
    private bool $is_authorized;
    private string $message_error;
    private $values;


    public function isIsConnected(): bool {
        return $this->is_connected;
    }
    public function setIsConnected(bool $is_connected): self {
        $this->is_connected = $is_connected;
        return $this;
    }

    public function isIsAuthorized(): bool {
        return $this->is_authorized;
    }
    public function setIsAuthorized(bool $is_authorized): self {
        $this->is_authorized = $is_authorized;
        return $this;
    }

    public function getMessageError(): string {
        return $this->message_error;
    }
    public function setMessageError(string $message_error): self {
        $this->message_error = $message_error;
        return $this;
    }

    public function getValues() {
        return $this->values;
    }
    public function setValues($values) : self {
        $this->values = $values;
        return $this;
    }

}
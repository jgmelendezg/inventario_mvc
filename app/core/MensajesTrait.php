<?php

trait MensajesTrait
{
    /**
     * Obtiene el mensaje de alerta basado en un parámetro de la URL.
     *
     * @param string $paramName
     * @param array $messages
     * @return string
     */
    protected function getMensaje(string $paramName, array $messages): string
    {
        return $messages[$_GET[$paramName] ?? ''] ?? '';
    }
}
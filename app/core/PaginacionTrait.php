<?php

trait PaginacionTrait
{
    /**
     * Calcula los datos de paginación.
     *
     * @param int $totalRegistros
     * @param int $registrosPorPag
     * @param int $paginaActual
     * @return array
     */
    protected function calcularPaginacion(int $totalRegistros, int $registrosPorPag, int $paginaActual): array
    {
        $totalPaginas = $totalRegistros > 0 ? (int)ceil($totalRegistros / $registrosPorPag) : 1;
        $paginaActual = max(1, min($paginaActual, $totalPaginas));
        $offset = ($paginaActual - 1) * $registrosPorPag;
        $mostrandoDesde = $totalRegistros > 0 ? $offset + 1 : 0;
        $mostrandoHasta = min($offset + $registrosPorPag, $totalRegistros);

        return [
            'total_registros' => $totalRegistros,
            'pagina_actual' => $paginaActual,
            'total_paginas' => $totalPaginas,
            'mostrando_desde' => $mostrandoDesde,
            'mostrando_hasta' => $mostrandoHasta,
            'offset' => $offset,
            'registros_por_pag' => $registrosPorPag,
        ];
    }
}
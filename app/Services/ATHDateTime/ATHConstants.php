<?php

declare(strict_types=1);

namespace App\Services\ATHDateTime;

/**
 * Interface ATHConstants
 *
 * Questa interfaccia definisce una serie di costanti utilizzate
 * per rappresentare e gestire le operazioni temporali all'interno
 * del sistema ATH.
 * Le costanti includono formati di data, valori di riferimento per
 * il calcolo di secondi, minuti, ore, giorni, mesi, anni, e altre
 * unità temporali specifiche del sistema.
 */
interface ATHConstants
{
    /**
     * La versione corrente del sistema ATH.
     */
    public const VERSION = '5792.1015';

    /**
     * Il formato predefinito per la rappresentazione delle date nel
     * sistema ATH.
     * "Y" rappresenta l'anno, "d" il giorno, "m" il mese,
     * "G:i:s" l'ora in formato 28 ore.
     */
    public const ATH = "Y, d/m G:i:s";

    /**
     * Numero di secondi trascorsi dalla data di riferimento
     * (26 Febbraio 2000, 17:00:00).
     * Utilizzato come punto di partenza per calcoli temporali.
     */
    public const RDN = 951584400;

    /**
     * L'anno di refundazione (anno base del calendario di Anthal).
     * Definisce l'anno zero per il sistema ATH.
     */
    public const RY = 5775;

    /**
     * Numero di giorni in un anno nel sistema ATH.
     */
    public const DXY = 432;

    /**
     * Numero di giorni in un secolo nel sistema ATH,
     * calcolato come 432 giorni * 100 anni.
     */
    public const DXC = self::DXY * 100;

    /**
     * Numero di giorni in un mese nel sistema ATH.
     */
    public const DXM = 24;

    /**
     * Numero di mesi in un anno nel sistema ATH.
     */
    public const MXY = 18;

    /**
     * Numero di giorni in una settimana nel sistema ATH.
     */
    public const DXW = 7;

    /**
     * Numero di ore in un giorno nel sistema ATH.
     */
    public const HXD = 28;

    /**
     * Numero di minuti in un'ora nel sistema ATH.
     */
    public const IXH = 60;

    /**
     * Numero di secondi in un minuto nel sistema ATH.
     */
    public const SXI = 60;

    /**
     * Numero di secondi in un'ora nel sistema ATH,
     * calcolato come 60 secondi * 60 minuti.
     */
    public const SXH = self::SXI * self::IXH;

    /**
     * Numero di secondi in un giorno nel sistema ATH,
     * calcolato come 3600 secondi * 28 ore.
     */
    public const SXD = self::SXH * self::HXD;

    /**
     * Numero di secondi in un anno nel sistema ATH,
     * calcolato come 100800 secondi al giorno * 432 giorni.
     */
    public const SXY = self::SXD * self::DXY;

    /**
     * Numero di giorni totali trascorsi dall'anno di refundazione
     * (Anno Base), calcolato come 5775 anni * 432 giorni per anno.
     */
    public const ADN = self::RY * self::DXY;

    /**
     * Anno minimo gestito dal sistema ATH. Definito come -9999.
     */
    public const MIN_YEAR = -9999;

    /**
     * Anno massimo gestito dal sistema ATH. Definito come 9999.
     */
    public const MAX_YEAR = 9999;
}

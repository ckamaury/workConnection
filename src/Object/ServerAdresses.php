<?php

namespace CkAmaury\WorkConnection\Object;

class ServerAdresses{

    public const IPN_HOST = 'intralignes.airfrance.fr';
    public const IPN_WEB = 'https://'.self::IPN_HOST.'/';

    public const ICART_HOST = 'icart.airfrance.fr';
    public const ICART_API = 'https://'.self::ICART_HOST.'/api/';

    public const CREW_HOST = 'crewbidd.airfrance.fr';
    public const CREW_API = 'https://'.self::CREW_HOST.'/api/cert/';

    public const CONNECTION_HOST = 'fedidp.airfranceklm.com'; //'fedidp.airfrance.fr'
    public const CONNECTION_IS_REQUEST = 'https://'.self::CONNECTION_HOST.'/idp/';
    public const CONNECTION_WEB = 'https://'.self::CONNECTION_HOST; //<-- NE PAS TOUCHER

    public const CCO_HOST = 'mycomcco.airfrance.fr';
    public const CCO_API = 'https://'.self::CCO_HOST.'/api/';

    public const MIDPACK_HOST = 'midpack.airfrance.fr';
    public const MIDPACK_API = 'https://'.self::MIDPACK_HOST.'/';

    public const REQUEST_INFO_ICART = self::ICART_API.'rest/resources/pilote';
    public const REQUEST_INFO_CREWMOBILE = self::CREW_API.'fc/v1';
    public const REQUEST_INFO_CREWMOBILE_FOR = self::CREW_API.'searchro/v1/';

    public const REQUEST_ACTIVITIES_FOR_ME = self::CREW_API.'roster/v1/3/12';
    public const REQUEST_GROUND_ACTIVITIES_INFO = self::CREW_API.'ga/v1/ids/';
    public const REQUEST_FLIGHT_ACTIVITIES_INFO = self::CREW_API.'pairing/v1/ids/';
    public const REQUEST_FLIGHT_ACTIVITIES_CREW = self::CREW_API.'fc/v1/act/';
    public const REQUEST_ACTIVITIES = self::CREW_API.'pairingsearch/v1/';
    public const REQUEST_ACTIVITIES_COUNT = self::CREW_API.'pairingsearch/v1/count';

    public const REQUEST_INDEMNITY = self::CREW_API.'information/v1/indemnity/';

    public const REQUEST_CCO_NUMBERS = self::CCO_API.'keynumbers/';
    public const REQUEST_CCO_NUMBERS_LATEST = self::CCO_API.'keynumbers/latest';

    public const REQUEST_NOTICE_URL_FINDER = self::MIDPACK_API.'escale/fr/pageStandard/Fiches_Escales.html';

    public const REQUEST_LCP_DATES = self::ICART_API.'rest/resources/lcp/list/dates';
    public const REQUEST_LCP = self::ICART_API.'rest/resources/lcp?page=0&size=2000&sort=rang,asc';


    /**
     * @param array $ids Tableau des IDs des activités AF
     */
    public static function REQUEST_GROUND_ACTIVITIES_INFO(array $ids) : string{
        $ids = implode('-',$ids);
        return self::REQUEST_GROUND_ACTIVITIES_INFO . $ids;
    }

    /**
     * @param array $ids Tableau des IDs des activités AF
     */
    public static function REQUEST_FLIGHT_ACTIVITIES_INFO(array $ids) : string{
        $ids = implode('-',$ids);
        return self::REQUEST_FLIGHT_ACTIVITIES_INFO . $ids;
    }

    public static function REQUEST_FLIGHT_ACTIVITIES_CREW(string $key) : string{
        return self::REQUEST_FLIGHT_ACTIVITIES_CREW . rawurlencode($key);
    }

    public static function REQUEST_INFO_CREWMOBILE_FOR(string $matricule) : string{
        return self::REQUEST_INFO_CREWMOBILE_FOR . $matricule;
    }

    public static function REQUEST_INDEMNITY(array $codes_iata) : string{
        $codes_iata = implode('-',$codes_iata);
        return self::REQUEST_INDEMNITY . $codes_iata;
    }
}
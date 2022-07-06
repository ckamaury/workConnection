<?php

namespace CkAmaury\WorkConnection\Object;

use CkAmaury\WorkConnection\Object\Refactorer\CrewInfoRefactorer;
use CkAmaury\WorkConnection\Object\Refactorer\IcartInfoRefactorer;
use CkAmaury\PhpCurl\MultiCurl;
use CkAmaury\PhpDatetime\DateTime;
use CkAmaury\PhpMagicFunctions\ArrayUtils;

class ServerRequest{

    private Cookie $cookie;
    private bool $isCustomCookie;

    public function __construct(){
        $this->initCookie();
    }
    private function initCookie(){
        $this->cookie = new Cookie();
        $this->cookie->loadCustomCookie();
        $this->isCustomCookie = true;
    }

    private function createCurl($url) : Curl{
        $curl = new Curl();
        $curl
            ->setUrl($url)
            ->setCookie($this->cookie);
        $curl->initialize();
        return $curl;
    }

    public function setLogin(string $login){
        $this->cookie->setLogin($login)->loadLoginCookie();
        $this->isCustomCookie = false;
    }

    public function executeAndReturnJson(Curl $curl){
        $response = $curl->executeAndReturnJson();
        if($this->checkIfNeedToTryAgain($curl,$response)){
            $response = $this->executeAndReturnJson($curl);
        }
        return $response;
    }
    public function executeAndReturnResponse(Curl $curl){
        $response = $curl->executeAndReturnResponse();
        if($this->checkIfNeedToTryAgain($curl,$response)){
            $response = $this->executeAndReturnResponse($curl);
        }
        return $response;
    }

    private function checkIfNeedToTryAgain(Curl $curl,$response){
        if($this->isCustomCookie && (
                is_null($response) ||
                (is_array($response) && array_key_exists('www-authenticate',$response))
            )){
            $curl->getCookie()->delete();
            $this->initCookie();
            $curl->setCookie($this->cookie);
            $curl->resetExecuted();
            return TRUE;
        }
        return FALSE;
    }

    public function requestCCO_Numbers(){
        return $this->executeAndReturnJson($this->createCurl(ServerAdresses::REQUEST_CCO_NUMBERS));
    }
    public function requestCCO_Numbers_Latest(){
        return $this->executeAndReturnJson($this->createCurl(ServerAdresses::REQUEST_CCO_NUMBERS_LATEST));
    }

    public function requestIcartInfo(bool $refactor = false){
        $response = $this->executeAndReturnJson($this->createCurl(ServerAdresses::REQUEST_INFO_ICART));
        if($refactor && !is_null($response)){
            $refactorer = new IcartInfoRefactorer();
            $response = $refactorer->refactor($response);
        }
        return $response;
    }
    public function requestCrewInfo(bool $refactor = false){
        $response = $this->executeAndReturnJson($this->createCurl(ServerAdresses::REQUEST_INFO_CREWMOBILE));
        if($refactor && !is_null($response)){
            $refactorer = new CrewInfoRefactorer();
            $response = $refactorer->refactor($response);
        }
        return $response;
    }
    public function requestCrewInfoFor(int $matricule){
        $response = $this->executeAndReturnJson($this->createCurl(ServerAdresses::REQUEST_INFO_CREWMOBILE_FOR($matricule)));
        return $response;
    }

    public function requestActivities(array $options = array()){
        $curl = $this->createCurl(ServerAdresses::REQUEST_ACTIVITIES)
            ->setJsonEncodePost(true)
            ->addPost($options);
        return $this->executeAndReturnJson($curl);
    }
    public function requestActivitiesFlight($keys){
        $curl = $this->createCurl( ServerAdresses::REQUEST_FLIGHT_ACTIVITIES_INFO($keys))
            ->setJsonEncodePost(true);
        return $this->executeAndReturnJson($curl);
    }
    public function requestActivitiesCrew($keys){
        $curl = $this->createCurl( ServerAdresses::REQUEST_FLIGHT_ACTIVITIES_CREW($keys));
        $curl->setJsonEncodePost(true);
        return $this->executeAndReturnJson($curl);
    }

    public function requestActivitiesCount(array $options = array()){
        $curl = $this->createCurl(ServerAdresses::REQUEST_ACTIVITIES_COUNT);
        $curl->setJsonEncodePost(true);
        $curl->addPost($options);
        return $this->executeAndReturnJson($curl);
    }

    public function requestIndemnity(array $codes_iata){
        $curl = $this->createCurl(ServerAdresses::REQUEST_INDEMNITY($codes_iata));
        return $curl->executeAndReturnJson();
    }

    public function requestMyActivities(){
        $curl = $this->createCurl(ServerAdresses::REQUEST_ACTIVITIES_FOR_ME);
        return $curl->executeAndReturnJson();
    }

    public function requestReserve(){

        $date_start = new DateTime();
        $date_start->previousDay();
        $date_end = $date_start
            ->clone()
            ->addDays(15)
            ->setTime(23,59,59);



        $activities = array();
        while ($date_start->isInfOrEqual($date_end)) {
            $options = array(
                "populationType" => 2,
                "scheduledDepartureDateFrom" => $date_start->getMicroTimestamp(),
                "scheduledDepartureDateTo" => $date_start->addDays(2)->getMicroTimestamp(),
                "resultNumber" => 500,
                "startOffset" => 0
            );
            $activities = array_merge_recursive($activities,$this->requestActivities($options));
        }


        $reserves = array();
        foreach ($activities as $activity) {
            if(isset($activity['booking'])) {
                foreach ($activity['booking'] as $booking) {
                    if ($booking['nbRequiredMin'] > $booking['nbAssigned']) {
                        $reserves[$activity['actId']] = $activity;
                        break;
                    }
                }
            }
        }

        $flights = $this->requestActivitiesFlight(array_keys($reserves));

        return [
            'activities' => $reserves,
            'flights' => $flights
        ];
    }

    /**
     * @return Activity[]
     */
    public function requestAllActivitiesOfTheDay(DateTime $date_start) : array{
        $date_start->setTimezone('Europe/Paris');
        $date_start->eraseTime();

        $date_end = $date_start->clone();
        $date_end->addDays(1);

        $options = array(
            "populationType" => 2,
            "resultNumber" => 5000,
            "aircraftTypes" => "318-319-320-321-32A",
            "scheduledDepartureDateFrom" => $date_start->getMicroTimestamp(),
            "scheduledDepartureDateTo" => $date_end->getMicroTimestamp(),
            "startOffset" => 0
        );

        $activities = $this->requestActivities($options);

        $activities_id = array();
        foreach($activities as $v){
            $activities_id[$v['actId']] = $v['activityKey'] ?? null;
        }

        $activities_flight = $this->requestActivitiesFlight(array_keys($activities_id));

        $multi = new MultiCurl();
        foreach($activities_flight as $value){
            $curl = $this->createCurl(ServerAdresses::REQUEST_FLIGHT_ACTIVITIES_CREW($activities_id[$value['activityId']]));
            $multi->add('activities_crew',$curl->getCurl()->getInit());
        }
        $multi->execute();
        $activities_crew = $multi->responseJson();
        $activities_crew = $activities_crew['activities_crew'];


        /*$refactorer = new AfRefactorer();
        $refactorer->setActivities($activities);
        $refactorer->setActivitiesFlight($activities_flight);
        $refactorer->setActivitiesCrew($activities_crew);
        $activities = $refactorer->refactorData();*/

        return array();
    }

    public function requestNoticeUrl():?string{
        $curl = $this->createCurl(ServerAdresses::REQUEST_NOTICE_URL_FINDER);
        $response = $this->executeAndReturnResponse($curl);

        if(!is_null($response)){
            $string = 'https://mybox.airfrance.fr/MyBoxWeb/[^\']*';
            $string = str_replace("/", "\/", $string);
            $pattern = "/".$string."/i";
            preg_match($pattern,$response,$matches);
            if(!empty($matches)){
                return $matches[0];
            }
        }
        return null;
    }
    public function requestNotice($url,$code){
        $curl = $this->createCurl($url.strtoupper($code));
        $response = $this->executeAndReturnResponse($curl);

        if(!is_null($response)){
            $string = '<body>(.*)</body>';
            $string = str_replace("/", "\/", $string);
            $pattern = "/".$string."/s";
            preg_match($pattern,$response,$matches);
            if(!empty($matches)){
                return ($matches[1]);
            }
        }
        return null;
    }

    public function requestLCP_Dates(){
        $curl = $this->createCurl( ServerAdresses::REQUEST_LCP_DATES);
        $curl->setJsonEncodePost(true);
        return $this->executeAndReturnJson($curl);
    }
    public function requestLCP($date):array{

        $response = $this->requestLCPPage(ServerAdresses::REQUEST_LCP,$date);

        $bool = true;
        $lcp = array();

        while($bool){
            $bool = false;
            if(!is_null($response)){
                $lcp = array_merge($lcp, $response['content']);
                foreach($response['links'] as $link){
                    if($link['rel'] === 'next'){
                        $bool = true;
                        $response = $this->requestLCPPage($link['href'] ,$date);
                    }
                }
            }
        }
        return $lcp;
    }
    private function requestLCPPage(string $url,string $date){
        $post = array(
            "lcpHistoId" => array(
                "dtdebtrait" => $date,
                "matricule" => "",
                "etatlcp" => "V"
            ),
            "nom" => "",
            "rang" => 1
        );

        $options = [
            CURLOPT_HTTPHEADER => [
                'Content-Type: application/json;charset=UTF-8'
            ]
        ];

        $curl = $this->createCurl($url);
        $curl->setJsonEncodePost(true);
        $curl->addPost($post);
        $curl->setOptions($options);
        return $this->executeAndReturnJson($curl);
    }
}
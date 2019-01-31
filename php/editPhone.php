<?php

require_once(__DIR__.'/transferData.php');


class getLead extends transferData{



    public function alignNumber($number){
        /* 
            Эта функция проверяет телефон и форматирует его к стандарту +380хххххххх
            Если номер не прошел проверку на корректность, 
            или не будет отесен ни к одному известному данному скрипту оператора
            То перезапись не произойдет. 
            Не корректный номер будет записан в failList.json
        */

        $default    = $number                                ; // save default phone
        $tel        = array()                           ; // save phone has error
        $number          = preg_replace("/[^0-9]/", '', $number)  ; /* delete all characters but numbers */
        $number          = (string) $number                       ;

        /* Если номер изначально в формате +380хх... */
        /* Переписал stitch на if/else  */
        if((strlen($default) === 13 ) && ($default{0} === "+") && ($default{1} === "3") && ($default{2} === "8") && ($default{3} === "0")){
            if($this->is_mobile_operator($default) == TRUE){
                return $default              ; // возвращаем текущий номер без изменений (он и так подходит)
            }
        }elseif(strlen($number) === 12 ){
            if(($number{0} === '3') && ($number{1} === '8') && ($number{2} === '0')){
                $number  = '+'.$number  ;
                if($this->is_mobile_operator($number) == TRUE){
                    return $number;
                }
            }
        }elseif(strlen($number) === 11){
            if(($number{0} === '8') && ($number{1} === '0')){
                $number = '+3'.$number ;
                if($this->is_mobile_operator($number) == TRUE){
                    return $number;
                }
            }
        }elseif(strlen($number) === 10){
            if($number{0} == '0'){
                $number = '+38'.$number;
                if($this->is_mobile_operator($number)){
                    return $number;
                }
            }
        }elseif(strlen($number) === 9){
                $number = '+380'.$number;
                if($this->is_mobile_operator($number)){
                    return $number;
                }
        }/* else{
            $this->option['error'] += 1 ;
            return $default             ; // номер не понятный , вернем как есть
        } */

        if(!$this->is_mobile_operator($number)){
            /* undefine mobile operator */
            return $default                 ; /* return defalult phone         */

        }else if($this->is_mobile_operator($number)){

            return $number; /* return good phone */
        }else{
            parent::writeToLog($number, 'Undefine error...');
            return $default                             ; /* return defalult phone */
        }
    }

    public function is_mobile_operator($tel){
        /* 
            This function most shold take telephone number
            in format +380670000000
        */
        $result = FALSE;
        if(!strlen($tel) === 13){
            parent::writeToLog($tel, 'is_mobile_operator() Не удалось проверить код оператора. Номер странный'  );
        }else{
            $codeTel    =   substr($tel, 3, 3);
            $UA         =   array('067', '096', '097', '098', '050', '066', '095', '099', '063', '073', '093', '044' );

            /* это будем использовать потом */
            /*  $RU         =   array('900', '901', '902', '903', '904', '905', '906', '908', '909', '910', '911', '912', '913', '914', 
                                  '915', '916', '917', '918', '919', '920', '921', '922', '923', '924', '925', '926', '927', '928', 
                                  '929', '930', '931', '932', '933', '934', '936', '937', '938', '939', '941', '950', '951', '952',
                                  '953', '954', '955', '956', '958', '960', '961', '962', 'result963', '964', '965', '966', '967', '968', 
                                  '969', '970', '971', '977', '978', '980', '981', '982', '983', '984', '985', '986', '987', '988', 
                                  '989', '991', '992', '993', '994', '995', '996', '997', '999'); */

            foreach($UA as $key){
                if($key === $codeTel){
                    $result = TRUE;
                    break;
                }
            }
        }
/*         return $result; */
return true;
    }


}
?>
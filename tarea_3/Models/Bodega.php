<?php
    class Bodega{

        private $bodegaId;
        private $cajas;

        public function __construct($bodegaId, $cajas){
            $this->bodegaId = $bodegaId;
            $this->cajas = $cajas;
        }
    
        public function getBodegaId(){
            return $this->bodegaId;
        }
    
        public function setBodegaId($bodegaId){
            $this->bodegaId = $bodegaId;
        }

        public function getCajas(){
            return $this->cajas;
        }
    
        public function setCajas($cajas){
            $this->cajas = $cajas;
        }

        public function moverCajasABodegas($bodegas, $bodegaIdEmisor, $numeroCajas, $bodegaIdReceptor){

            $numeroCajasRestantesPorMover = 0;

            /*
                Egresar el stock de la bodega
                Si una bodega queda vacía debe continuar con la siguiente.
            */
            foreach($bodegas as $index => $bodega){
                if($bodega->bodegaId == $bodegaIdEmisor){
                    if(($bodega->cajas - $numeroCajas) >= 0){
                        $bodegas[$index]->cajas -= $numeroCajas;
                    }else{
                        $numeroCajasRestantesPorMover = $numeroCajas - $bodegas[$index]->cajas;
                        $bodegas[$index]->cajas -= ($numeroCajas - $numeroCajasRestantesPorMover);
                        
                        $indexTemp = $index;

                        do {
                            if($indexTemp < (count($bodegas) - 1)){
                                if($bodegas[$indexTemp + 1]->bodegaId != $bodegaIdReceptor){
                                    if(($bodegas[$indexTemp + 1]->cajas - $numeroCajasRestantesPorMover) >= 0){
                                        
                                        $bodegas[$indexTemp + 1]->cajas -= $numeroCajasRestantesPorMover;
                                        $numeroCajasRestantesPorMover = 0;
                                    }else{
                                        $numeroCajasRestantesPorMover = $numeroCajasRestantesPorMover - $bodegas[$indexTemp + 1]->cajas;
                                        $indexTemp++;
                                    }
                                }else{
                                    $indexTemp++;
                                }
                            }else{
                                $indexTemp = -1;
                            }
                        } while ($numeroCajasRestantesPorMover > 0);
                    }
                }
            }

            /*
                Ingresar el stock en la bodega
            */
            foreach($bodegas as $index => $bodega){
                if($bodega->bodegaId == $bodegaIdReceptor){
                    $bodegas[$index]->cajas += $numeroCajas;
                }
            }

            return $bodegas;
        }
    }